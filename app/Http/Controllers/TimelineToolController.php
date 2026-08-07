<?php

namespace App\Http\Controllers;

use App\Mail\TimelineShared;
use App\Models\ChatMessageAttachment;
use App\Models\CoupleTimelineDraft;
use App\Models\Favorite;
use App\Models\Pairing;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorTimeline;
use App\Support\TimelinePdfBuilder;
use App\Support\WinTimelineToolHtmlPatcher;
use App\Models\VendorTimelineDraft;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Chat;

class TimelineToolController extends Controller
{
    private const STORAGE_KEY = 'win-timeline-v1';

    private const SOURCE_RELATIVE = 'html_templates/WIN Timeline Tool 3-18-26.html';

    public function showCouple(Request $request): View
    {
        $sourcePath = base_path(self::SOURCE_RELATIVE);
        abort_unless(file_exists($sourcePath), 404, 'Timeline template file not found.');

        if (! $request->boolean('frame')) {
            return view('tools.embedded_tool_page', [
                'role' => 'couple',
                'page' => 'couple_timeline',
                'title' => 'Timeline Planner',
                'iframeSrc' => route('couple.timeline', ['frame' => 1]),
            ]);
        }

        $draft = CoupleTimelineDraft::where('user_id', $request->user()->id)->first();
        $user = $request->user();
        $partnerOne = trim($user->first_name . ' ' . ($user->last_name ?? ''));
        $partnerTwo = trim(($user->fiance_first_name ?? '') . ' ' . ($user->fiance_last_name ?? ''));
        $coupleName = $partnerTwo !== '' ? $partnerOne . ' ♥ ' . $partnerTwo : $partnerOne;

        return $this->renderTimelineView($sourcePath, $draft?->payload, [
            'saveUrl' => route('couple.timeline.draft.save'),
            'clearUrl' => route('couple.timeline.draft.clear'),
        ], $coupleName, shareConfig: [
            'recipientsUrl' => route('couple.timeline.share.recipients'),
            'sendUrl' => route('couple.timeline.share'),
        ]);
    }

    public function showVendor(Request $request): View
    {
        $sourcePath = base_path(self::SOURCE_RELATIVE);
        abort_unless(file_exists($sourcePath), 404, 'Timeline template file not found.');

        if (! $request->boolean('frame')) {
            return view('tools.embedded_tool_page', [
                'role' => 'vendor',
                'page' => 'vendor_timeline',
                'title' => 'Timeline Planner',
                'iframeSrc' => route('vendor.timeline', ['frame' => 1]),
            ]);
        }

        $draft = VendorTimelineDraft::where('vendor_id', $request->user()->id)->first();
        $vendor = $request->user();
        $displayName = trim($vendor->business_name ?: trim($vendor->first_name . ' ' . $vendor->last_name));

        return $this->renderTimelineView($sourcePath, $draft?->payload, [
            'saveUrl' => route('vendor.timeline.draft.save'),
            'clearUrl' => route('vendor.timeline.draft.clear'),
        ], $displayName);
    }

    public function indexVendorTimelines(Request $request): View
    {
        $timelines = VendorTimeline::where('vendor_id', $request->user()->id)
            ->orderByDesc('updated_at')
            ->get();

        return view('vendor.timelines_index', [
            'timelines' => $timelines,
            'page' => 'vendor_timeline',
        ]);
    }

    public function storeVendorTimeline(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:190'],
        ]);

        $timeline = VendorTimeline::create([
            'vendor_id' => $request->user()->id,
            'name' => $validated['name'],
            'payload' => null,
        ]);

        return redirect()->route('vendor.timelines.show', $timeline->id);
    }

    public function renameVendorTimeline(Request $request, int $timeline): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:190'],
        ]);

        $row = $this->findVendorTimelineOrFail($request, $timeline);
        $row->update(['name' => $validated['name']]);

        return redirect()->route('vendor.timelines.index')
            ->with('toast', ['message' => 'Timeline renamed.', 'type' => 'success']);
    }

    public function destroyVendorTimeline(Request $request, int $timeline): RedirectResponse
    {
        $row = $this->findVendorTimelineOrFail($request, $timeline);
        $row->delete();

        return redirect()->route('vendor.timelines.index')
            ->with('toast', ['message' => 'Timeline deleted.', 'type' => 'success']);
    }

    public function showVendorTimeline(Request $request, int $timeline): View
    {
        $sourcePath = base_path(self::SOURCE_RELATIVE);
        abort_unless(file_exists($sourcePath), 404, 'Timeline template file not found.');

        $row = $this->findVendorTimelineOrFail($request, $timeline);

        if (! $request->boolean('frame')) {
            return view('tools.embedded_tool_page', [
                'role' => 'vendor',
                'page' => 'vendor_timeline',
                'title' => $row->name,
                'iframeSrc' => route('vendor.timelines.show', ['timeline' => $timeline, 'frame' => 1]),
            ]);
        }

        return $this->renderTimelineView($sourcePath, $row->payload, [
            'saveUrl' => route('vendor.timelines.draft.save', $timeline),
            'clearUrl' => route('vendor.timelines.draft.clear', $timeline),
        ], $row->name);
    }

    public function saveVendorTimelineDraft(Request $request, int $timeline): JsonResponse
    {
        $validated = $request->validate([
            'payload' => ['required', 'string', 'max:2000000'],
        ]);

        $row = $this->findVendorTimelineOrFail($request, $timeline);
        $row->update(['payload' => $validated['payload']]);

        return response()->json(['saved' => true]);
    }

    public function clearVendorTimelineDraft(Request $request, int $timeline): JsonResponse
    {
        $row = $this->findVendorTimelineOrFail($request, $timeline);
        $row->update(['payload' => null]);

        return response()->json(['cleared' => true]);
    }

    public function showCoupleTimelineForVendor(Request $request, string $id): View
    {
        $sourcePath = base_path(self::SOURCE_RELATIVE);
        abort_unless(file_exists($sourcePath), 404, 'Timeline template file not found.');

        $client = User::where('uuid', $id)->first();
        abort_unless($client, 404);

        $isBooked = Pairing::where('vendor_id', $request->user()->id)
            ->where('client_id', $client->id)
            ->where('status', 3)
            ->exists();
        abort_unless($isBooked, 403);

        if (! $request->boolean('frame')) {
            return view('tools.embedded_tool_page', [
                'role' => 'vendor',
                'page' => 'vendor_timeline',
                'title' => 'Timeline Planner (Read-Only)',
                'iframeSrc' => route('vendor.couple.timeline', ['id' => $id, 'frame' => 1]),
            ]);
        }

        $draft = CoupleTimelineDraft::where('user_id', $client->id)->first();
        $partnerOne = trim($client->first_name . ' ' . ($client->last_name ?? ''));
        $partnerTwo = trim(($client->fiance_first_name ?? '') . ' ' . ($client->fiance_last_name ?? ''));
        $coupleName = $partnerTwo !== '' ? $partnerOne . ' ♥ ' . $partnerTwo : $partnerOne;

        return $this->renderTimelineView($sourcePath, $draft?->payload, [
            'saveUrl' => null,
            'clearUrl' => null,
        ], $coupleName, readOnly: true);
    }

    private function findVendorTimelineOrFail(Request $request, int $timelineId): VendorTimeline
    {
        $row = VendorTimeline::where('id', $timelineId)
            ->where('vendor_id', $request->user()->id)
            ->first();

        abort_unless($row, 404);

        return $row;
    }

    /**
     * @param  array{saveUrl: ?string, clearUrl: ?string}  $routes
     */
    private function renderTimelineView(string $sourcePath, ?string $draftPayload, array $routes, ?string $displayName = null, bool $readOnly = false, ?array $shareConfig = null): View
    {
        $timelineHtml = WinTimelineToolHtmlPatcher::apply(file_get_contents($sourcePath), $displayName, $readOnly);

        $runtimeConfig = [
            'csrfToken' => csrf_token(),
            'storageKey' => self::STORAGE_KEY,
            'draftPayload' => $draftPayload,
            'saveUrl' => $routes['saveUrl'],
            'clearUrl' => $routes['clearUrl'],
            'readOnly' => $readOnly,
            // Only the couple's own timeline gets real sharing (Favorites /
            // My Wedding Team are couple-side concepts) — null here means
            // the share button hides itself entirely for vendor views.
            'shareConfig' => $shareConfig,
        ];

        $bridgeScript = '<script>window.__WIN_TIMELINE_BRIDGE__ = ' . json_encode($runtimeConfig, JSON_UNESCAPED_SLASHES) . ';(function(){const cfg=window.__WIN_TIMELINE_BRIDGE__||{};const key=cfg.storageKey||"win-timeline-v1";const saveUrl=cfg.saveUrl;const clearUrl=cfg.clearUrl;const csrf=cfg.csrfToken;const draft=cfg.draftPayload;if(draft&&typeof draft==="string"){try{window.localStorage.setItem(key,draft);}catch(e){}}else{try{window.localStorage.removeItem(key);}catch(e){}}const nativeGet=window.localStorage.getItem.bind(window.localStorage);const nativeSet=window.localStorage.setItem.bind(window.localStorage);const nativeRemove=window.localStorage.removeItem.bind(window.localStorage);window.localStorage.getItem=function(k){return nativeGet(k)};window.localStorage.setItem=function(k,v){nativeSet(k,v);if(k!==key||!saveUrl){return;}fetch(saveUrl,{method:"POST",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":csrf,"X-Requested-With":"XMLHttpRequest"},credentials:"same-origin",body:JSON.stringify({payload:v})}).catch(function(){});};window.localStorage.removeItem=function(k){nativeRemove(k);if(k!==key||!clearUrl){return;}fetch(clearUrl,{method:"POST",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":csrf,"X-Requested-With":"XMLHttpRequest"},credentials:"same-origin"}).catch(function(){});};})();</script>';

        $timelineHtml = preg_replace('/<script>/', $bridgeScript . '<script>', $timelineHtml, 1);

        return view('tools.timeline_tool', [
            'timelineHtml' => $timelineHtml,
        ]);
    }

    public function saveCoupleDraft(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'payload' => ['required', 'string', 'max:2000000'],
        ]);

        CoupleTimelineDraft::updateOrCreate(
            ['user_id' => $request->user()->id],
            ['payload' => $validated['payload']]
        );

        return response()->json(['saved' => true]);
    }

    public function clearCoupleDraft(Request $request): JsonResponse
    {
        CoupleTimelineDraft::where('user_id', $request->user()->id)->delete();

        return response()->json(['cleared' => true]);
    }

    public function saveVendorDraft(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'payload' => ['required', 'string', 'max:2000000'],
        ]);

        VendorTimelineDraft::updateOrCreate(
            ['vendor_id' => $request->user()->id],
            ['payload' => $validated['payload']]
        );

        return response()->json(['saved' => true]);
    }

    public function clearVendorDraft(Request $request): JsonResponse
    {
        VendorTimelineDraft::where('vendor_id', $request->user()->id)->delete();

        return response()->json(['cleared' => true]);
    }

    /**
     * Vendors the couple can share their timeline with: their favorited
     * vendors and the vendors on "My Wedding Team" (booked, pairing status
     * 3). A vendor can appear in both lists — the share UI just collects
     * selected ids, so that's harmless.
     */
    public function shareRecipients(Request $request): JsonResponse
    {
        $user = $request->user();

        $favorites = $user->favoritedVendors()->get()
            ->map(fn (Vendor $v) => self::vendorRow($v))
            ->values();

        $bookedVendorIds = Pairing::where('client_id', $user->id)->where('status', 3)->pluck('vendor_id');
        $weddingTeam = Vendor::whereIn('id', $bookedVendorIds)->get()
            ->map(fn (Vendor $v) => self::vendorRow($v))
            ->values();

        return response()->json([
            'favorites' => $favorites,
            'weddingTeam' => $weddingTeam,
        ]);
    }

    /**
     * Shares the couple's current timeline (rendered as the same PDF the
     * tool's own download produces) with the selected vendors, over the
     * selected channel(s) — WIN chat, email, or both.
     */
    public function share(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'vendor_ids' => ['required', 'array', 'min:1'],
            'vendor_ids.*' => ['integer'],
            'channels' => ['required', 'array', 'min:1'],
            'channels.*' => ['in:chat,email'],
        ]);

        $user = $request->user();

        $draft = CoupleTimelineDraft::where('user_id', $user->id)->first();
        if (! $draft || ! $draft->payload) {
            return response()->json(['status' => false, 'message' => "Nothing to share yet — add something to your timeline first."], 422);
        }

        $state = json_decode($draft->payload, true);
        if (! is_array($state)) {
            return response()->json(['status' => false, 'message' => 'Could not read your timeline.'], 422);
        }

        // Only allow sharing to vendors this couple actually favorited or booked —
        // a client-supplied vendor id alone isn't enough to message/email someone.
        $favoritedIds = Favorite::where('user_id', $user->id)->pluck('vendor_id');
        $bookedIds = Pairing::where('client_id', $user->id)->where('status', 3)->pluck('vendor_id');
        $allowedIds = $favoritedIds->merge($bookedIds)->unique();

        $vendorIds = collect($validated['vendor_ids'])->intersect($allowedIds)->values();
        if ($vendorIds->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'None of the selected vendors are valid recipients.'], 422);
        }

        $vendors = Vendor::whereIn('id', $vendorIds)->get();

        $partnerOne = trim($user->first_name . ' ' . ($user->last_name ?? ''));
        $partnerTwo = trim(($user->fiance_first_name ?? '') . ' ' . ($user->fiance_last_name ?? ''));
        $coupleName = $partnerTwo !== '' ? $partnerOne . ' ♥ ' . $partnerTwo : $partnerOne;

        $pdfBytes = TimelinePdfBuilder::build($state, $coupleName);
        $fileName = Str::slug($coupleName ?: 'couple') . '-timeline.pdf';
        $channels = $validated['channels'];

        $sent = [];
        foreach ($vendors as $vendor) {
            if (in_array('chat', $channels, true)) {
                $this->shareViaChat($user, $vendor, $pdfBytes, $fileName);
            }
            if (in_array('email', $channels, true)) {
                $this->shareViaEmail($vendor, $pdfBytes, $fileName, $coupleName);
            }
            $sent[] = $vendor->id;
        }

        return response()->json(['status' => true, 'sent' => $sent]);
    }

    private function shareViaChat(User $user, Vendor $vendor, string $pdfBytes, string $fileName): void
    {
        $conversation = $user->directMessageWith($vendor);

        $uuid = (string) Str::uuid();
        $relativeDir = 'chat-attachments/' . $conversation->id;
        $relativePath = $relativeDir . '/' . $uuid . '.pdf';

        Storage::disk('local')->makeDirectory($relativeDir);
        Storage::disk('local')->put($relativePath, $pdfBytes);

        $message = Chat::message('Shared our wedding timeline')
            ->from($user)
            ->to($conversation)
            ->type('attachment')
            ->data([
                'attachment_name' => $fileName,
                'attachment_size' => strlen($pdfBytes),
            ])
            ->send();

        ChatMessageAttachment::create([
            'message_id' => $message->id,
            'conversation_id' => $conversation->id,
            'original_name' => $fileName,
            'stored_path' => $relativePath,
            'mime_type' => 'application/pdf',
            'size_bytes' => strlen($pdfBytes),
            'uploader_type' => User::class,
            'uploader_id' => $user->id,
        ]);
    }

    private function shareViaEmail(Vendor $vendor, string $pdfBytes, string $fileName, string $coupleName): void
    {
        if (! $vendor->email) {
            return;
        }

        $vendorName = $vendor->business_name ?: trim($vendor->first_name . ' ' . $vendor->last_name);

        Mail::to($vendor->email)->send(new TimelineShared($coupleName, $vendorName, $pdfBytes, $fileName));
    }

    private static function vendorRow(Vendor $vendor): array
    {
        return [
            'id' => $vendor->id,
            'name' => $vendor->business_name ?: trim($vendor->first_name . ' ' . $vendor->last_name),
            'type' => $vendor->getType()?->type,
        ];
    }
}
