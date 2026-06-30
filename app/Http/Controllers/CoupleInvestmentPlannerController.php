<?php

namespace App\Http\Controllers;

use App\Models\CoupleInvestmentPlannerDraft;
use App\Support\WinInvestmentPlannerHtmlPatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CoupleInvestmentPlannerController extends Controller
{
    private const STORAGE_KEY = 'win_budget_builder_v99';

    public function show(Request $request): View
    {
        $sourcePath = base_path('proyecto_bodas_material_extra/WIN Wedding Investment Planner 3-19-26.html');
        abort_unless(file_exists($sourcePath), 404, 'Investment planner template file not found.');

        $draft = CoupleInvestmentPlannerDraft::where('user_id', $request->user()->id)->first();
        $draftPayload = $draft?->payload;

        $plannerHtml = WinInvestmentPlannerHtmlPatcher::apply(file_get_contents($sourcePath));

        $runtimeConfig = [
            'csrfToken' => csrf_token(),
            'storageKey' => self::STORAGE_KEY,
            'draftPayload' => $draftPayload,
            'saveUrl' => route('couple.investment_planner.draft.save'),
            'clearUrl' => route('couple.investment_planner.draft.clear'),
        ];

        $bridgeScript = '<script>window.__WIN_PLANNER_BRIDGE__ = ' . json_encode($runtimeConfig, JSON_UNESCAPED_SLASHES) . ';(function(){const cfg=window.__WIN_PLANNER_BRIDGE__||{};const key=cfg.storageKey||"win_budget_builder_v99";const saveUrl=cfg.saveUrl;const clearUrl=cfg.clearUrl;const csrf=cfg.csrfToken;const draft=cfg.draftPayload;if(draft&&typeof draft==="string"){try{window.localStorage.setItem(key,draft);}catch(e){}}const nativeGet=window.localStorage.getItem.bind(window.localStorage);const nativeSet=window.localStorage.setItem.bind(window.localStorage);const nativeRemove=window.localStorage.removeItem.bind(window.localStorage);window.localStorage.getItem=function(k){return nativeGet(k)};window.localStorage.setItem=function(k,v){nativeSet(k,v);if(k!==key||!saveUrl){return;}fetch(saveUrl,{method:"POST",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":csrf,"X-Requested-With":"XMLHttpRequest"},credentials:"same-origin",body:JSON.stringify({payload:v})}).catch(function(){});};window.localStorage.removeItem=function(k){nativeRemove(k);if(k!==key||!clearUrl){return;}fetch(clearUrl,{method:"POST",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":csrf,"X-Requested-With":"XMLHttpRequest"},credentials:"same-origin"}).catch(function(){});};})();</script>';

        $plannerHtml = preg_replace('/<script>/', $bridgeScript . '<script>', $plannerHtml, 1);

        return view('tools.couple_investment_planner', [
            'plannerHtml' => $plannerHtml,
        ]);
    }

    public function saveDraft(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'payload' => ['required', 'string', 'max:2000000'],
        ]);

        CoupleInvestmentPlannerDraft::updateOrCreate(
            ['user_id' => $request->user()->id],
            ['payload' => $validated['payload']]
        );

        return response()->json(['saved' => true]);
    }

    public function clearDraft(Request $request): JsonResponse
    {
        CoupleInvestmentPlannerDraft::where('user_id', $request->user()->id)->delete();

        return response()->json(['cleared' => true]);
    }
}

