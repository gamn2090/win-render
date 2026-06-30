<?php

require __DIR__ . '/../vendor/autoload.php';

$planner = App\Support\WinInvestmentPlannerHtmlPatcher::apply(file_get_contents(
    dirname(__DIR__) . '/proyecto_bodas_material_extra/WIN Wedding Investment Planner 3-19-26.html'
));
$timeline = App\Support\WinTimelineToolHtmlPatcher::apply(file_get_contents(
    dirname(__DIR__) . '/proyecto_bodas_material_extra/WIN Timeline Tool 3-18-26.html'
));

$checks = [
    'planner sync helper' => str_contains($planner, 'function syncBookedVendorsForChart'),
    'planner sync on results' => str_contains($planner, 'syncBookedVendorsForChart();'),
    'planner guide basePct' => str_contains($planner, 'o[k] = vt ? (vt.basePct||0) : 0;'),
    'planner no donor rebalance' => !str_contains($planner, 'const donors = [...active].filter(k=>'),
    'planner priority bumps pct' => str_contains($planner, 'PRIORITY_BUMP_PCT')
        && str_contains($planner, 'applyPriorityToAlloc')
        && str_contains($planner, 'important: 5, splurge: 10'),
    'planner priority labels' => str_contains($planner, 'adds 5% each') && str_contains($planner, 'adds 10% each'),
    'planner surplus panel' => str_contains($planner, 'function buildSurplusPanel'),
    'planner surplus CTAs' => str_contains($planner, 'surplusExistingBtn') && str_contains($planner, 'surplusNewBtn'),
    'planner manual overrides' => str_contains($planner, 'function applyManualAllocOverrides'),
    'planner active rows spent' => str_contains($planner, 'r.isActive && (r.pct>0 || r.spent>0)'),
    'planner booked parseMoney' => str_contains($planner, 'parseMoney(b.amount)'),
    'planner booked refresh' => str_contains($planner, 'function refreshBudgetPanelAfterBookedChange'),
    'planner flush skip on finish' => str_contains($planner, 'if(state.step!==3){') && str_contains($planner, 'flushPlannedAmountDrafts();'),
    'planner photographer pct' => str_contains($planner, 'basePct:10 },') && str_contains($planner, 'key:"photographer"'),
    'planner catering pct' => str_contains($planner, 'basePct:20 },') && str_contains($planner, 'key:"catering"'),
    'planner band pct' => str_contains($planner, 'key:"band"') && str_contains($planner, 'basePct:6 },') && str_contains($planner, 'label:"Live Bands"'),
    'planner optional pcts' => str_contains($planner, 'key:"photobooth"') && str_contains($planner, 'label:"Photo Booth"') && str_contains($planner, 'basePct:1 },')
        && str_contains($planner, 'key:"strings"') && str_contains($planner, 'label:"String Ensembles"') && str_contains($planner, 'basePct:1 },')
        && str_contains($planner, 'key:"content"') && str_contains($planner, 'basePct:1.5 },')
        && str_contains($planner, 'key:"artist"') && str_contains($planner, 'basePct:4 },'),
    'planner step2 no default row' => !str_contains($planner, 'Default first row to first VENDOR_TYPES entry'),
    'planner step2 add blank row' => str_contains($planner, 'Select vendor type'),
    'planner step3 label' => str_contains($planner, '3. Extra Services'),
    'planner disclaimer wrap' => str_contains($planner, 'class="donutDisclaimer"'),
    'timeline confirm details only' => str_contains($timeline, 'opt.textContent = "Confirm Details"') && !str_contains($timeline, 'optCustom.value="__custom__"'),
    'timeline setup cards' => str_contains($timeline, 'function renderSetupCards'),
    'timeline overlap patch' => str_contains($timeline, 'laneEnds') && str_contains($timeline, 'minGapPct'),
    'timeline remove add prefix' => !str_contains($timeline, 'Add: " + cat'),
    'timeline vendor select css' => str_contains($timeline, '#vendorBlockVendor'),
    'timeline setup sidebar scroll' => str_contains($timeline, 'setupCard') && str_contains($timeline, 'overscroll-behavior:contain'),
    'timeline vendor row density' => str_contains($timeline, 'const vendorSurface = containerEl')
        && str_contains($timeline, '#vendorRows .rowRight')
        && str_contains($timeline, 'vendorSurface ? 76 : 92'),
    'timeline task time select' => str_contains($timeline, '<select id="taskTime"></select>')
        && str_contains($timeline, 'function syncTaskTimeOptions'),
    'timeline setup card actions' => str_contains($timeline, 'setupCardActions')
        && str_contains($timeline, 'justify-content:center'),
    'timeline key event dedupe' => str_contains($timeline, 'usedKeyEventLabels')
        && str_contains($timeline, 'captureKeyEventSetupDrafts')
        && str_contains($timeline, 'takenElsewhere'),
    'timeline vendor dedupe' => str_contains($timeline, 'usedVendorIds')
        && str_contains($timeline, 'captureVendorBlockSetupDrafts')
        && str_contains($timeline, 'usedBlockCategories'),
];

foreach ($checks as $label => $ok) {
    echo ($ok ? 'OK' : 'FAIL') . " - {$label}\n";
}

exit(in_array(false, $checks, true) ? 1 : 0);
