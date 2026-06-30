<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Musonza\Chat\Models\Message as ChatMessage;

class ChatMessageAttachment extends Model
{
    use HasFactory;

    protected $table = 'chat_message_attachments';

    protected $fillable = [
        'message_id',
        'conversation_id',
        'original_name',
        'stored_path',
        'mime_type',
        'size_bytes',
        'uploader_type',
        'uploader_id',
    ];

    protected $casts = [
        'message_id' => 'integer',
        'conversation_id' => 'integer',
        'size_bytes' => 'integer',
        'uploader_id' => 'integer',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'message_id');
    }

    public function uploader(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'uploader_type', 'uploader_id');
    }

    public function getHumanSizeAttribute(): string
    {
        $bytes = (int) $this->size_bytes;

        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        if ($bytes < 1024 * 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }

        return round($bytes / (1024 * 1024), 1) . ' MB';
    }

    /**
     * Enriches each message with type='attachment' inside the given iterable
     * (LengthAwarePaginator items, Collection, array, etc.) by merging
     * attachment metadata and a download URL into the message's `data` array.
     */
    public static function enrichMessages(iterable $messages): void
    {
        $messageIds = [];
        foreach ($messages as $msg) {
            if (($msg->type ?? null) === 'attachment' && isset($msg->id)) {
                $messageIds[] = $msg->id;
            }
        }

        if (empty($messageIds)) {
            return;
        }

        $byMessageId = self::whereIn('message_id', $messageIds)->get()->keyBy('message_id');

        foreach ($messages as $msg) {
            if (($msg->type ?? null) !== 'attachment') {
                continue;
            }

            $att = $byMessageId->get($msg->id);
            if (!$att) {
                continue;
            }

            $existing = is_array($msg->data) ? $msg->data : [];

            $msg->data = array_merge($existing, [
                'attachment_id' => $att->id,
                'attachment_name' => $att->original_name,
                'attachment_size' => (int) $att->size_bytes,
                'attachment_mime' => $att->mime_type,
                'download_url' => route('messages.attachment.download', ['attachment' => $att->id]),
            ]);
        }
    }
}
