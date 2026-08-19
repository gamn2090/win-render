<?php

namespace App\Support;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TimelinePdfBuilder
{
    private const KEY_VENDOR_ID = '__KEYSTONE__';
    private const MAX_LINES = 26;
    private const SOURCE_RELATIVE = 'html_templates/WIN Timeline Tool 3-18-26.html';

    /**
     * Builds the same "key events + vendor task milestones" schedule the
     * tool's own client-side "Print or Save PDF" export shows — ported from
     * exportPDF() in the raw HTML template so shared PDFs match what a
     * couple would get downloading it themselves.
     *
     * dompdf (a pure-PHP renderer, no browser/OS emoji font available) can't
     * render color emoji — they come out as "?" tofu boxes — so icons use a
     * plain shape marker here instead of the couple's actual emoji picks.
     * Everything else (logo, heart between names, layout) matches the
     * browser print view.
     *
     * @param  array<string, mixed>  $state  Decoded CoupleTimelineDraft payload
     */
    public static function build(array $state, string $coupleName): string
    {
        $dayStartMin = $state['dayStartMin'] ?? 7 * 60;
        $dayEndMin = $state['dayEndMin'] ?? 23 * 60;
        $vendors = $state['vendors'] ?? [];
        $blocks = $state['blocks'] ?? [];

        $vendorsById = [];
        foreach ($vendors as $vendor) {
            if (isset($vendor['id'])) {
                $vendorsById[$vendor['id']] = $vendor;
            }
        }

        $lines = [];

        foreach ($blocks as $block) {
            $isKeyEvent = ($block['vendorId'] ?? null) === self::KEY_VENDOR_ID;

            if ($isKeyEvent) {
                $startMin = (int) ($block['startMin'] ?? 0);
                $endMin = (int) ($block['endMin'] ?? $startMin);
                $lines[] = [
                    'startMin' => $startMin,
                    'start' => self::formatTime($startMin),
                    'end' => self::formatTime($endMin),
                    'label' => $block['eventName'] ?? 'Key event',
                    'icon' => '◆',
                    'durationMin' => max(0, $endMin - $startMin),
                ];
                continue;
            }

            $vendor = $vendorsById[$block['vendorId'] ?? null] ?? null;
            $category = trim($vendor['category'] ?? 'Vendor');
            $icon = '•';

            $tasks = $block['tasks'] ?? [];
            usort($tasks, fn ($a, $b) => ($a['atMin'] ?? 0) <=> ($b['atMin'] ?? 0));

            foreach ($tasks as $task) {
                $atMin = (int) ($task['atMin'] ?? 0);
                if ($atMin < $dayStartMin || $atMin > $dayEndMin) {
                    continue;
                }
                $lines[] = [
                    'startMin' => $atMin,
                    'start' => self::formatTime($atMin),
                    'end' => '',
                    'label' => $category . ': ' . ($task['title'] ?? ''),
                    'icon' => $icon,
                    'durationMin' => null,
                ];
            }
        }

        usort($lines, fn ($a, $b) => $a['startMin'] <=> $b['startMin']);
        $lines = array_slice($lines, 0, self::MAX_LINES);

        $windowLabel = 'US Letter Landscape • Window ' . self::formatTime($dayStartMin) . ' to ' . self::formatTime($dayEndMin);

        $pdf = Pdf::loadView('pdf.timeline', [
            'coupleName' => $coupleName,
            'lines' => $lines,
            'windowLabel' => $windowLabel,
            'stamp' => Carbon::now()->format('M d, Y'),
            'logoSrc' => self::logoSrc(),
        ])->setPaper([0, 0, 792, 612]); // 11in x 8.5in landscape, in points

        return $pdf->output();
    }

    /**
     * The WIN logo lives as a single base64 data URI in the source HTML
     * template (see the .brandLogo <img>) — pull it from there instead of
     * keeping a second copy that could drift out of sync.
     */
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

    private static function formatTime(int $min): string
    {
        $h24 = intdiv($min, 60) % 24;
        $m = $min % 60;
        $ampm = $h24 >= 12 ? 'PM' : 'AM';
        $h12 = ($h24 % 12) === 0 ? 12 : ($h24 % 12);

        return $h12 . ':' . str_pad((string) $m, 2, '0', STR_PAD_LEFT) . ' ' . $ampm;
    }
}
