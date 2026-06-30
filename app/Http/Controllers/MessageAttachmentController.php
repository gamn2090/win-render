<?php

namespace App\Http\Controllers;

use App\Models\ChatMessageAttachment;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Chat;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class MessageAttachmentController extends Controller
{
    private const MAX_BYTES = 10 * 1024 * 1024;

    private const ALLOWED_MIMES = [
        'application/pdf',
    ];

    public function upload(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'conversation' => ['required', 'integer', 'min:1'],
            'file' => ['required', 'file', 'mimetypes:application/pdf', 'max:10240'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $sender = $this->resolveAuthenticatedSender();
        if (!$sender) {
            return response()->json(['status' => 'error', 'message' => 'Not authenticated.'], HttpResponse::HTTP_UNAUTHORIZED);
        }

        $conversationId = (int) $validated['conversation'];

        if (!$this->isParticipant($sender, $conversationId)) {
            return response()->json(['status' => 'error', 'message' => 'You are not a participant of this conversation.'], HttpResponse::HTTP_FORBIDDEN);
        }

        $file = $request->file('file');
        if (!in_array($file->getMimeType(), self::ALLOWED_MIMES, true) || $file->getSize() > self::MAX_BYTES) {
            throw ValidationException::withMessages([
                'file' => ['Only PDF files up to 10 MB are allowed.'],
            ]);
        }

        $uuid = (string) Str::uuid();
        $relativeDir = 'chat-attachments/' . $conversationId;
        $relativePath = $relativeDir . '/' . $uuid . '.pdf';

        Storage::disk('local')->makeDirectory($relativeDir);

        $stored = $file->storeAs($relativeDir, $uuid . '.pdf', ['disk' => 'local']);
        if ($stored === false) {
            return response()->json(['status' => 'error', 'message' => 'Could not store the file.'], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $originalName = $this->sanitizeFilename($file->getClientOriginalName());
        $bodyText = trim($validated['message'] ?? '');
        if ($bodyText === '') {
            $bodyText = 'Shared PDF: ' . $originalName;
        }

        try {
            $attachment = DB::transaction(function () use ($sender, $conversationId, $bodyText, $originalName, $relativePath, $file) {
                $conversation = Chat::conversations()->getById($conversationId);

                $message = Chat::message($bodyText)
                    ->from($sender)
                    ->to($conversation)
                    ->type('attachment')
                    ->data([
                        'attachment_name' => $originalName,
                        'attachment_size' => $file->getSize(),
                    ])
                    ->send();

                return ChatMessageAttachment::create([
                    'message_id' => $message->id,
                    'conversation_id' => $conversationId,
                    'original_name' => $originalName,
                    'stored_path' => $relativePath,
                    'mime_type' => 'application/pdf',
                    'size_bytes' => $file->getSize(),
                    'uploader_type' => $sender instanceof Vendor ? Vendor::class : User::class,
                    'uploader_id' => $sender->id,
                ]);
            });
        } catch (\Throwable $e) {
            Storage::disk('local')->delete($relativePath);
            throw $e;
        }

        return response()->json([
            'status' => 'ok',
            'attachment' => [
                'id' => $attachment->id,
                'message_id' => $attachment->message_id,
                'original_name' => $attachment->original_name,
                'human_size' => $attachment->human_size,
                'download_url' => route('messages.attachment.download', ['attachment' => $attachment->id]),
            ],
            'message' => [
                'body' => $bodyText,
                'type' => 'attachment',
            ],
        ]);
    }

    public function download(Request $request, int $attachment): BinaryFileResponse|JsonResponse
    {
        $att = ChatMessageAttachment::find($attachment);
        if (!$att) {
            return response()->json(['status' => 'error', 'message' => 'Attachment not found.'], HttpResponse::HTTP_NOT_FOUND);
        }

        $viewer = $this->resolveAuthenticatedSender();
        if (!$viewer) {
            return response()->json(['status' => 'error', 'message' => 'Not authenticated.'], HttpResponse::HTTP_UNAUTHORIZED);
        }

        if (!$this->isParticipant($viewer, (int) $att->conversation_id)) {
            return response()->json(['status' => 'error', 'message' => 'You are not allowed to access this file.'], HttpResponse::HTTP_FORBIDDEN);
        }

        if (!Storage::disk('local')->exists($att->stored_path)) {
            return response()->json(['status' => 'error', 'message' => 'File no longer exists on the server.'], HttpResponse::HTTP_GONE);
        }

        return response()->download(
            Storage::disk('local')->path($att->stored_path),
            $att->original_name,
            ['Content-Type' => $att->mime_type]
        );
    }

    private function resolveAuthenticatedSender()
    {
        if (Auth::guard('vendor')->check()) {
            return Auth::guard('vendor')->user();
        }

        if (Auth::guard('web')->check()) {
            return Auth::guard('web')->user();
        }

        return null;
    }

    private function isParticipant($sender, int $conversationId): bool
    {
        $type = $sender instanceof Vendor ? Vendor::class : User::class;

        return DB::table('chat_participation')
            ->where('conversation_id', $conversationId)
            ->where('messageable_type', $type)
            ->where('messageable_id', $sender->id)
            ->exists();
    }

    private function sanitizeFilename(string $name): string
    {
        $name = preg_replace('/[\\/\\\\:\\*\\?"<>\\|\\x00-\\x1F]+/', '_', $name) ?? 'document.pdf';
        $name = trim($name);
        if ($name === '') {
            $name = 'document.pdf';
        }

        if (mb_strlen($name) > 200) {
            $name = mb_substr($name, 0, 200);
        }

        if (!Str::endsWith(mb_strtolower($name), '.pdf')) {
            $name .= '.pdf';
        }

        return $name;
    }
}
