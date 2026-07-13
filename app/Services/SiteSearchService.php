<?php

namespace App\Services;

use App\Models\Inquiry;
use App\Models\Pairing;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorTypes;
use Chat;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SiteSearchService
{
    /**
     * @return Collection<int, array{title: string, url: string, snippet: ?string, source: string}>
     */
    public function searchCouple(User $user, string $query): Collection
    {
        $results = collect();
        $needle = trim($query);
        if ($needle === '') {
            return $results;
        }

        $pages = [
            ['title' => 'Dashboard', 'url' => route('dashboard'), 'keywords' => ['dashboard', 'home', 'overview', 'stats']],
            ['title' => 'Find Vendors', 'url' => route('search.vendors'), 'keywords' => ['search', 'find', 'vendors', 'browse', 'discover']],
            ['title' => 'Messages', 'url' => route('client.inbox'), 'keywords' => ['messages', 'inbox', 'chat', 'conversations']],
            ['title' => 'Favorite Vendors', 'url' => route('client.favorites'), 'keywords' => ['favorites', 'favorite', 'saved', 'loved']],
            ['title' => 'My Vendors', 'url' => route('client.vendor.list'), 'keywords' => ['my vendors', 'booked', 'wedding team']],
            ['title' => 'My Profile', 'url' => route('client.my_profile'), 'keywords' => ['profile', 'about us', 'bio']],
            ['title' => 'Edit Profile', 'url' => route('user.profile.edit'), 'keywords' => ['edit profile', 'name', 'email', 'wedding date']],
            ['title' => 'Account Settings', 'url' => route('user.account.settings'), 'keywords' => ['account', 'settings', 'password']],
            ['title' => 'Timeline Planner', 'url' => route('couple.timeline'), 'keywords' => ['timeline', 'planner', 'checklist']],
            ['title' => 'Budget Planner', 'url' => route('couple.investment_planner'), 'keywords' => ['budget', 'planner', 'investment', 'money']],
        ];

        $actions = [
            [
                'title' => 'Change Password',
                'url' => route('user.account.settings') . '#vd-password-section',
                'keywords' => ['password', 'change password', 'reset password', 'update password'],
                'description' => 'Update your account password',
                'button_label' => 'Save Password',
            ],
            [
                'title' => 'Delete Account',
                'url' => route('user.account.settings') . '#vd-open-delete-account',
                'keywords' => ['delete', 'delete account', 'remove account', 'close account', 'deactivate account'],
                'description' => 'Permanently delete your account',
                'button_label' => 'Delete Account',
                'variant' => 'danger',
            ],
        ];

        $this->matchPages($pages, $needle, $results);
        $this->matchActions($actions, $needle, $results);
        $this->matchVendorTypesAndDirectory($needle, $results);
        $this->matchFavorites($user, $needle, $results);
        $this->matchBookedVendors($user, $needle, $results);
        $this->matchCoupleProfile($user, $needle, $results);
        $this->matchConversations($user, $needle, $results, 'get.client.conversation', function ($vendor) {
            return $vendor->business_name ?? trim(($vendor->first_name ?? '') . ' ' . ($vendor->last_name ?? ''));
        }, Vendor::class);

        return $results->unique('url')->values();
    }

    /**
     * @return Collection<int, array{title: string, url: string, snippet: ?string, source: string}>
     */
    public function searchVendor(Vendor $vendor, string $query): Collection
    {
        $results = collect();
        $needle = trim($query);
        if ($needle === '') {
            return $results;
        }

        $pages = [
            ['title' => 'Dashboard', 'url' => route('vendor.dashboard'), 'keywords' => ['dashboard', 'home', 'overview']],
            ['title' => 'Find Couples', 'url' => route('vendor.find.couples'), 'keywords' => ['find couples', 'inquiries', 'leads']],
            ['title' => 'Current Clients', 'url' => route('vendor.client.list'), 'keywords' => ['clients', 'current clients', 'my clients']],
            ['title' => 'Vendor Network', 'url' => route('vendor.list'), 'keywords' => ['network', 'connections', 'preferred vendors']],
            ['title' => 'My Storefront', 'url' => route('vendor.storefront'), 'keywords' => ['storefront', 'profile', 'portfolio', 'gallery']],
            ['title' => 'Insights', 'url' => route('vendor.insights'), 'keywords' => ['insights', 'analytics', 'winfluence', 'score']],
            ['title' => 'Messages', 'url' => route('vendor.inbox'), 'keywords' => ['messages', 'inbox', 'chat']],
            ['title' => 'Booked', 'url' => route('vendor.booked'), 'keywords' => ['booked']],
            ['title' => 'Archive', 'url' => route('vendor.archive'), 'keywords' => ['archive', 'archived']],
            ['title' => 'Account Settings', 'url' => route('vendor.account.settings'), 'keywords' => ['account', 'settings', 'password']],
            ['title' => 'Planning Tools', 'url' => route('vendor.planning_tools'), 'keywords' => ['planning', 'timeline', 'budget', 'tools']],
        ];

        $actions = [
            [
                'title' => 'Change Password',
                'url' => route('vendor.account.settings') . '#vd-password-section',
                'keywords' => ['password', 'change password', 'reset password', 'update password'],
                'description' => 'Update your account password',
                'button_label' => 'Save Password',
            ],
            [
                'title' => 'Delete Account',
                'url' => route('vendor.account.settings') . '#vd-open-delete-account',
                'keywords' => ['delete', 'delete account', 'remove account', 'close account', 'deactivate account'],
                'description' => 'Permanently delete your account',
                'button_label' => 'Delete Account',
                'variant' => 'danger',
            ],
        ];

        $this->matchPages($pages, $needle, $results);
        $this->matchActions($actions, $needle, $results);
        $this->matchClients($vendor, $needle, $results);
        $this->matchCoupleDirectory($vendor, $needle, $results);
        $this->matchConnections($vendor, $needle, $results);
        $this->matchConversations($vendor, $needle, $results, 'get.vendor.conversation', function ($user) {
            return trim(($user->first_name ?? '') . ' ' . ($user->fiance_first_name ?? ''));
        }, User::class);

        return $results->unique('url')->values();
    }

    private function matchPages(array $pages, string $needle, Collection $results): void
    {
        foreach ($pages as $page) {
            $haystack = $page['title'] . ' ' . implode(' ', $page['keywords']);
            if (Str::contains(Str::lower($haystack), Str::lower($needle))) {
                $results->push([
                    'title' => $page['title'],
                    'url' => $page['url'],
                    'snippet' => null,
                    'source' => 'Page',
                ]);
            }
        }
    }

    /**
     * @param array<int, array{title: string, url: string, keywords: array<int, string>, description?: string, button_label?: string, variant?: string}> $actions
     */
    private function matchActions(array $actions, string $needle, Collection $results): void
    {
        foreach ($actions as $action) {
            $haystack = $action['title'] . ' ' . implode(' ', $action['keywords']);
            if (Str::contains(Str::lower($haystack), Str::lower($needle))) {
                $results->push([
                    'title' => $action['title'],
                    'url' => $action['url'],
                    'snippet' => $action['description'] ?? null,
                    'source' => 'Action',
                    'is_action' => true,
                    'button_label' => $action['button_label'] ?? $action['title'],
                    'variant' => $action['variant'] ?? 'default',
                ]);
            }
        }
    }

    /**
     * Matches vendor categories (e.g. "photographer") and, when the query also carries
     * extra text (e.g. "photographer in MA"), narrows a vendor-directory search by that
     * remainder against business name / owner name / location.
     */
    private function matchVendorTypesAndDirectory(string $needle, Collection $results): void
    {
        $types = VendorTypes::all();
        $matchedType = $types->first(function ($type) use ($needle) {
            $typeLower = Str::lower($type->type);
            $needleLower = Str::lower($needle);
            return Str::contains($needleLower, $typeLower) || Str::contains($typeLower, $needleLower);
        });

        $remainder = $needle;
        if ($matchedType) {
            $results->push([
                'title' => $matchedType->type,
                'url' => route('search.vendors', ['type' => $matchedType->id]),
                'snippet' => 'Vendor Category',
                'source' => 'Vendor Type',
            ]);

            $remainder = trim(str_ireplace($matchedType->type, '', $needle));
            $remainder = trim(preg_replace('/\s+/', ' ', preg_replace('/\bin\b/i', ' ', $remainder)));
        }

        $vendorsQuery = Vendor::where('visible', 1);
        if ($matchedType) {
            $vendorsQuery->where('type', $matchedType->id);
        }
        if ($remainder !== '') {
            $vendorsQuery->where(function ($q) use ($remainder) {
                $q->where('business_name', 'like', "%{$remainder}%")
                    ->orWhere('first_name', 'like', "%{$remainder}%")
                    ->orWhere('last_name', 'like', "%{$remainder}%")
                    ->orWhere('location', 'like', "%{$remainder}%");
            });
        } elseif (!$matchedType) {
            $vendorsQuery->where(function ($q) use ($needle) {
                $q->where('business_name', 'like', "%{$needle}%")
                    ->orWhere('first_name', 'like', "%{$needle}%")
                    ->orWhere('last_name', 'like', "%{$needle}%")
                    ->orWhere('location', 'like', "%{$needle}%");
            });
        }

        foreach ($vendorsQuery->limit(6)->get() as $matchedVendor) {
            $type = $matchedVendor->getType();
            $results->push([
                'title' => $matchedVendor->business_name,
                'url' => route('profile.vendor', $matchedVendor->uuid),
                'snippet' => collect([$type->type ?? null, $matchedVendor->location])->filter()->implode(' · ') ?: null,
                'source' => 'Vendors',
            ]);
        }
    }

    private function matchCoupleDirectory(Vendor $vendor, string $needle, Collection $results): void
    {
        $inquiryUserIds = Inquiry::where('vendor_type', $vendor->type)->where('requestable', 1)->pluck('user_id');

        $matches = User::whereIn('id', $inquiryUserIds)
            ->where(function ($q) use ($needle) {
                $q->where('first_name', 'like', "%{$needle}%")
                    ->orWhere('last_name', 'like', "%{$needle}%")
                    ->orWhere('fiance_first_name', 'like', "%{$needle}%")
                    ->orWhere('fiance_last_name', 'like', "%{$needle}%");
            })
            ->limit(6)
            ->get();

        foreach ($matches as $client) {
            $label = trim(($client->first_name ?? '') . ' & ' . ($client->fiance_first_name ?? ''), " &");
            $results->push([
                'title' => $label !== '' ? $label : 'Couple',
                'url' => route('vendor.couple.profile', $client->uuid),
                'snippet' => $client->wedding_location ?: 'Prospective couple',
                'source' => 'Couples',
            ]);
        }
    }

    private function matchFavorites(User $user, string $needle, Collection $results): void
    {
        $matches = $user->favoritedVendors()
            ->where(function ($q) use ($needle) {
                $q->where('business_name', 'like', "%{$needle}%")
                    ->orWhere('first_name', 'like', "%{$needle}%")
                    ->orWhere('last_name', 'like', "%{$needle}%");
            })
            ->limit(5)
            ->get();

        foreach ($matches as $vendor) {
            $results->push([
                'title' => 'Favorite Vendors',
                'url' => route('client.favorites'),
                'snippet' => 'Favorited: ' . $vendor->business_name,
                'source' => 'Favorites',
            ]);
        }
    }

    private function matchBookedVendors(User $user, string $needle, Collection $results): void
    {
        $pairings = collect($user->vendorsWithStatus())->filter(fn ($p) => $p->status == 3 && $p->vendor);
        foreach ($pairings as $pairing) {
            $vendor = $pairing->vendor;
            $haystack = $vendor->business_name . ' ' . $vendor->first_name . ' ' . $vendor->last_name;
            if (Str::contains(Str::lower($haystack), Str::lower($needle))) {
                $results->push([
                    'title' => 'My Vendors',
                    'url' => route('client.vendor.list'),
                    'snippet' => 'Booked: ' . $vendor->business_name,
                    'source' => 'My Vendors',
                ]);
            }
        }
    }

    private function matchCoupleProfile(User $user, string $needle, Collection $results): void
    {
        $answers = json_decode($user->questions ?? '[]', true);
        $answers = is_array($answers) ? $answers : [];
        $haystack = Str::lower(implode(' ', array_filter([$user->bio, ...$answers])));
        if ($haystack !== '' && Str::contains($haystack, Str::lower($needle))) {
            $results->push([
                'title' => 'My Profile',
                'url' => route('client.my_profile'),
                'snippet' => 'Matched in your profile bio/answers',
                'source' => 'My Profile',
            ]);
        }
    }

    private function matchClients(Vendor $vendor, string $needle, Collection $results): void
    {
        $pairings = Pairing::where('vendor_id', $vendor->id)->with('client')->limit(200)->get();
        foreach ($pairings as $pairing) {
            $client = $pairing->client;
            if (!$client) {
                continue;
            }
            $haystack = trim($client->first_name . ' ' . $client->last_name . ' ' . $client->fiance_first_name . ' ' . $client->fiance_last_name);
            if (Str::contains(Str::lower($haystack), Str::lower($needle))) {
                $results->push([
                    'title' => 'Current Clients',
                    'url' => route('vendor.client.list'),
                    'snippet' => 'Client: ' . $haystack,
                    'source' => 'Current Clients',
                ]);
            }
        }
    }

    private function matchConnections(Vendor $vendor, string $needle, Collection $results): void
    {
        $matches = $vendor->connections()
            ->where('business_name', 'like', "%{$needle}%")
            ->limit(5)
            ->get();

        foreach ($matches as $connection) {
            $results->push([
                'title' => 'Vendor Network',
                'url' => route('vendor.list'),
                'snippet' => 'Connected with: ' . $connection->business_name,
                'source' => 'Vendor Network',
            ]);
        }
    }

    private function matchConversations($account, string $needle, Collection $results, string $routeName, callable $labelFor, string $otherType): void
    {
        try {
            $conversations = $account->getAllConversations();
        } catch (\Throwable $e) {
            return;
        }

        foreach ($conversations as $convo) {
            $conversation = $convo->conversation ?? null;
            if (!$conversation) {
                continue;
            }

            $participant = collect($conversation->participants)->first(fn ($p) => $p->messageable_type === $otherType);
            if (!$participant || !$participant->messageable) {
                continue;
            }

            $other = $participant->messageable;
            $label = $labelFor($other);
            $lastMessage = $conversation->last_message->body ?? '';
            $haystack = Str::lower($label . ' ' . $lastMessage);

            if (Str::contains($haystack, Str::lower($needle))) {
                $results->push([
                    'title' => 'Messages',
                    'url' => route($routeName, $conversation->id),
                    'snippet' => 'Conversation with ' . $label,
                    'source' => 'Messages',
                ]);
            }
        }
    }
}
