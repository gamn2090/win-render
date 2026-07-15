<?php

namespace App\Http\Controllers;

use App\Models\CoupleTimelineDraft;
use App\Support\WinTimelineToolHtmlPatcher;
use App\Models\VendorTimelineDraft;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
        ], $coupleName);
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

    /**
     * @param  array{saveUrl: string, clearUrl: string}  $routes
     */
    private function renderTimelineView(string $sourcePath, ?string $draftPayload, array $routes, ?string $displayName = null): View
    {
        $timelineHtml = WinTimelineToolHtmlPatcher::apply(file_get_contents($sourcePath), $displayName);

        $runtimeConfig = [
            'csrfToken' => csrf_token(),
            'storageKey' => self::STORAGE_KEY,
            'draftPayload' => $draftPayload,
            'saveUrl' => $routes['saveUrl'],
            'clearUrl' => $routes['clearUrl'],
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
}
