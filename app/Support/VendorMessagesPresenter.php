<?php

namespace App\Support;

use App\Models\User;
use App\Models\Vendor;
use Chat;
use Illuminate\Support\Carbon;

class VendorMessagesPresenter
{
    /**
     * @return array{newInquiries: array<int, array<string, mixed>>, inbox: array<int, array<string, mixed>>}
     */
    public static function forVendor(Vendor $vendor): array
    {
        $newInquiries = [];
        $inbox = [];
        $seenConversationIds = [];

        foreach ($vendor->getAllConversations() as $convoWrapper) {
            $conversation = $convoWrapper->conversation;

            if ($conversation->messages()->count() === 0) {
                continue;
            }

            if (in_array($conversation->id, $seenConversationIds, true)) {
                continue;
            }

            $other = self::resolveOtherParticipant($conversation, $vendor);
            if ($other === null) {
                continue;
            }

            $row = self::buildRow($vendor, $conversation, $other);
            $seenConversationIds[] = $conversation->id;

            if ($other['role'] === 'client' && $row['is_new_inquiry']) {
                $newInquiries[] = $row;
            } else {
                $inbox[] = $row;
            }
        }

        return [
            'newInquiries' => $newInquiries,
            'inbox' => $inbox,
        ];
    }

    /**
     * @return array{role: string, model: User|Vendor}|null
     */
    private static function resolveOtherParticipant(object $conversation, Vendor $vendor): ?array
    {
        foreach ($conversation->getParticipants() as $participant) {
            $morph = $participant->getMorphClass();

            if ($morph === Vendor::class && (int) $participant->id === (int) $vendor->id) {
                continue;
            }

            if ($morph === User::class) {
                return ['role' => 'client', 'model' => $participant];
            }

            if ($morph === Vendor::class) {
                return ['role' => 'vendor', 'model' => $participant];
            }
        }

        return null;
    }

    /**
     * @param array{role: string, model: User|Vendor} $other
     * @return array<string, mixed>
     */
    private static function buildRow(Vendor $vendor, object $conversation, array $other): array
    {
        /** @var User|Vendor $participant */
        $participant = $other['model'];
        $lastMessage = $conversation->last_message;
        $unreadCount = Chat::conversation($conversation)->setParticipant($vendor)->unreadCount();
        $unreadNotifications = count($conversation->unReadNotifications($vendor));
        $hasUnread = $unreadCount > 0 || $unreadNotifications > 0;

        if ($other['role'] === 'client') {
            /** @var User $participant */
            $name = trim($participant->first_name . ' ' . $participant->last_name);
            $subtitle = $participant->wedding_location ?: '';
            $roleLabel = 'Client';
            $showRole = true;
        } else {
            /** @var Vendor $participant */
            $name = $participant->business_name ?: trim($participant->first_name . ' ' . $participant->last_name);
            $subtitle = trim($participant->first_name . ' ' . $participant->last_name);
            $roleLabel = 'Vendor';
            $showRole = true;
        }

        $sentAt = '';
        if ($lastMessage !== null) {
            $timestamp = Carbon::parse($lastMessage->created_at);
            $sentAt = $timestamp->format('m/d/Y') . ' at ' . $timestamp->format('g:iA');
        }

        return [
            'conversation_id' => $conversation->id,
            'role' => $other['role'],
            'role_label' => $roleLabel,
            'show_role' => $showRole,
            'name' => $name,
            'subtitle' => $subtitle,
            'avatar' => ProfileImageStorage::url($participant->image),
            'preview' => $lastMessage !== null ? $lastMessage->body : 'Start a conversation!',
            'sent_at' => $sentAt,
            'has_unread' => $hasUnread,
            'is_new_inquiry' => $other['role'] === 'client' && $hasUnread,
            'has_new_request' => $other['role'] === 'vendor' && $vendor->hasRequestFrom($participant->id),
            'url' => route('get.vendor.conversation', ['id' => $conversation->id]),
            'view_meta' => self::buildViewMeta($participant, $other['role']),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function buildViewMeta(User|Vendor $participant, string $role): array
    {
        if ($role === 'client') {
            /** @var User $participant */
            $partnerOneFull = trim($participant->first_name . ' ' . ($participant->last_name ?? ''));
            $partnerTwoFull = trim(($participant->fiance_first_name ?? '') . ' ' . ($participant->fiance_last_name ?? ''));

            return [
                'role' => 'client',
                'partner_one' => $participant->first_name,
                'partner_two' => $participant->fiance_first_name ?? '',
                'partner_one_full' => $partnerOneFull,
                'partner_two_full' => $partnerTwoFull,
                'info_heading' => 'Wedding Information:',
                'info_primary' => $participant->wedding_date
                    ? Carbon::parse($participant->wedding_date)->format('m/d/Y')
                    : 'The date is still pending.',
                'info_secondary' => $participant->wedding_location
                    ?: 'Venue location is still pending.',
                'avatar' => ProfileImageStorage::url($participant->image),
                'profile_url' => filled($participant->uuid)
                    ? route('vendor.couple.profile', ['id' => $participant->uuid])
                    : null,
                'profile_label' => 'Visit Profile',
                'other_initials' => self::initials($participant->first_name, $participant->fiance_first_name ?? ''),
                'composer_placeholder' => 'Write a message to ' . $participant->first_name . '...',
            ];
        }

        /** @var Vendor $participant */
        $displayName = $participant->business_name ?: trim($participant->first_name . ' ' . $participant->last_name);
        $location = trim($participant->location ?? '');

        return [
            'role' => 'vendor',
            'partner_one' => $displayName,
            'partner_two' => '',
            'partner_one_full' => trim($participant->first_name . ' ' . ($participant->last_name ?? '')),
            'partner_two_full' => $participant->type ?? '',
            'info_heading' => 'Vendor Information:',
            'info_primary' => $location !== '' ? $location : 'Location not specified.',
            'info_secondary' => $participant->type ?? 'Vendor type not specified.',
            'avatar' => ProfileImageStorage::url($participant->image),
            'profile_url' => filled($participant->uuid)
                ? url('/vendor/profile/' . $participant->uuid)
                : null,
            'profile_label' => 'Visit Storefront',
            'other_initials' => self::initials($participant->first_name, $participant->last_name ?? ''),
            'composer_placeholder' => 'Write a message to ' . ($participant->business_name ?: $participant->first_name) . '...',
        ];
    }

    private static function initials(string ...$names): string
    {
        $result = '';

        foreach ($names as $name) {
            $name = trim($name);
            if ($name !== '') {
                $result .= strtoupper(mb_substr($name, 0, 1));
            }
        }

        return $result !== '' ? $result : '?';
    }
}
