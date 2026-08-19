<?php

namespace App\Support;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InvestmentPlannerPdfBuilder
{
    private const SOURCE_RELATIVE = 'html_templates/WIN Wedding Investment Planner 3-19-26.html';

    /**
     * Ported from VENDOR_TYPES in the raw HTML template (key => [label, color,
     * basePct]) — kept in sync manually since dompdf can't run the client's
     * own JS allocation engine. Only label/color/basePct are needed here.
     */
    private const VENDOR_TYPES = [
        'venue'        => ['label' => 'Venue',             'color' => '#FF7A9B', 'basePct' => 18],
        'planner'      => ['label' => 'Wedding Planner',   'color' => '#C7A6FF', 'basePct' => 7],
        'photographer' => ['label' => 'Photographer',      'color' => '#FFD27A', 'basePct' => 12],
        'hair'         => ['label' => 'Hair & Makeup',     'color' => '#7AFFB6', 'basePct' => 3],
        'dj'           => ['label' => 'DJ',                'color' => '#B9C6FF', 'basePct' => 6],
        'videographer' => ['label' => 'Videographer',      'color' => '#FFB27A', 'basePct' => 8],
        'catering'     => ['label' => 'Caterer',           'color' => '#8BFFC7', 'basePct' => 22],
        'bakery'       => ['label' => 'Bakery / Cake',     'color' => '#FFE4B5', 'basePct' => 4],
        'decor'        => ['label' => 'Rentals & Decor',   'color' => '#7AE6FF', 'basePct' => 5],
        'bar'          => ['label' => 'Bar Services',      'color' => '#7AA7FF', 'basePct' => 14],
        'stationery'   => ['label' => 'Stationery',        'color' => '#FFC4E1', 'basePct' => 2],
        'band'         => ['label' => 'Live Bands',        'color' => '#A8D8FF', 'basePct' => 7],
        'bridal'       => ['label' => 'Bridal / Tux',      'color' => '#E8A0FF', 'basePct' => 5],
        'photobooth'   => ['label' => 'Photo Booth',       'color' => '#FFD4A0', 'basePct' => 3],
        'strings'      => ['label' => 'String Ensembles',  'color' => '#B8FFE4', 'basePct' => 3],
        'officiant'    => ['label' => 'Officiant',         'color' => '#D4E6FF', 'basePct' => 2],
        'transport'    => ['label' => 'Transportation',    'color' => '#FFEACC', 'basePct' => 3],
        'content'      => ['label' => 'Content Creators',  'color' => '#C0F0C0', 'basePct' => 3],
        'jewelers'     => ['label' => 'Jewelers',           'color' => '#FFB0C0', 'basePct' => 5],
        'artist'       => ['label' => 'Live Artists',      'color' => '#F0D4FF', 'basePct' => 3],
        'florist'      => ['label' => 'Florist',           'color' => '#FF9B9B', 'basePct' => 7],
        'other'        => ['label' => 'Other',              'color' => '#F0F0F0', 'basePct' => 5],
    ];

    /**
     * @param  array<string, mixed>  $state  Decoded CoupleInvestmentPlannerDraft payload
     */
    public static function build(array $state, string $coupleName): string
    {
        $total = self::parseMoney($state['totalBudget'] ?? '');
        $booked = is_array($state['booked'] ?? null) ? $state['booked'] : [];
        $planned = is_array($state['planned'] ?? null) ? $state['planned'] : [];
        $allocPct = is_array($state['allocPct'] ?? null) ? $state['allocPct'] : null;

        $activeKeys = [];
        foreach ($booked as $b) {
            if (! empty($b['vendor']) && isset(self::VENDOR_TYPES[$b['vendor']])) {
                $activeKeys[$b['vendor']] = true;
            }
        }
        foreach ($planned as $vk) {
            if (isset(self::VENDOR_TYPES[$vk])) {
                $activeKeys[$vk] = true;
            }
        }
        $activeKeys = array_keys($activeKeys);

        $spentByVendor = [];
        foreach ($booked as $b) {
            if (empty($b['vendor'])) {
                continue;
            }
            $amt = self::parseMoney($b['amount'] ?? '');
            if ($amt > 0) {
                $spentByVendor[$b['vendor']] = ($spentByVendor[$b['vendor']] ?? 0) + $amt;
            }
        }

        // The couple may download before ever reaching the results step, so
        // allocPct (cached client-side once they get there) can be missing —
        // fall back to a simple basePct-weighted split among active
        // categories, the same starting point the tool itself uses.
        if ($allocPct === null) {
            $weightSum = 0;
            foreach ($activeKeys as $key) {
                $weightSum += self::VENDOR_TYPES[$key]['basePct'];
            }
            $allocPct = [];
            if ($weightSum > 0) {
                foreach ($activeKeys as $key) {
                    $allocPct[$key] = (self::VENDOR_TYPES[$key]['basePct'] / $weightSum) * 100;
                }
            }
        }

        $rows = [];
        foreach ($activeKeys as $key) {
            $pct = max(0, (float) ($allocPct[$key] ?? 0));
            if ($pct <= 0) {
                continue;
            }
            $rows[] = [
                'label' => self::VENDOR_TYPES[$key]['label'],
                'color' => self::VENDOR_TYPES[$key]['color'],
                'pct' => $pct,
                'planned' => $total * ($pct / 100),
                'spent' => $spentByVendor[$key] ?? 0,
            ];
        }

        usort($rows, fn ($a, $b) => $b['pct'] <=> $a['pct']);

        $pdf = Pdf::loadView('pdf.investment-planner', [
            'coupleName' => $coupleName,
            'total' => $total,
            'spentTotal' => array_sum($spentByVendor),
            'rows' => $rows,
            'stamp' => Carbon::now()->format('M d, Y'),
            'logoSrc' => self::logoSrc(),
        ])->setPaper('letter', 'portrait');

        return $pdf->output();
    }

    private static function parseMoney($value): float
    {
        $digits = preg_replace('/[^0-9.]/', '', (string) ($value ?? ''));

        return $digits === '' ? 0.0 : max(0.0, (float) $digits);
    }

    private static function logoSrc(): ?string
    {
        $sourcePath = base_path(self::SOURCE_RELATIVE);
        if (! file_exists($sourcePath)) {
            return null;
        }

        $html = file_get_contents($sourcePath);
        if (! preg_match('/class="brandLogo"\s+src="([^"]+)"/', $html, $match)) {
            return null;
        }

        return $match[1];
    }
}
