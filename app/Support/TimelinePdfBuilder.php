<?php

namespace App\Support;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TimelinePdfBuilder
{
    private const KEY_VENDOR_ID = '__KEYSTONE__';
    private const MAX_LINES = 26;

    /**
     * Builds the same "key events + vendor task milestones" schedule the
     * tool's own client-side "Print or Save PDF" export shows — ported from
     * exportPDF() in the raw HTML template so shared PDFs match what a
     * couple would get downloading it themselves.
     *
     * @param  array<string, mixed>  $state  Decoded CoupleTimelineDraft payload
     */
    public static function build(array $state, string $coupleName): string
    {
        $dayStartMin = $state['dayStartMin'] ?? 10 * 60;
        $dayEndMin = $state['dayEndMin'] ?? 22 * 60;
        $vendors = $state['vendors'] ?? [];
        $blocks = $state['blocks'] ?? [];
        $vendorIcons = $state['vendorIcons'] ?? [];

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
                    'label' => $block['title'] ?? 'Key event',
                    'icon' => $block['icon'] ?? '◆',
                    'durationMin' => max(0, $endMin - $startMin),
                ];
                continue;
            }

            $vendor = $vendorsById[$block['vendorId'] ?? null] ?? null;
            $category = trim($vendor['category'] ?? 'Vendor');
            $icon = $vendorIcons[$category] ?? '•';

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

        $windowLabel = self::formatTime($dayStartMin) . ' to ' . self::formatTime($dayEndMin);

        $pdf = Pdf::loadView('pdf.timeline', [
            'coupleName' => $coupleName,
            'lines' => $lines,
            'windowLabel' => $windowLabel,
            'stamp' => Carbon::now()->format('M d, Y'),
        ])->setPaper([0, 0, 792, 612]); // 11in x 8.5in landscape, in points

        return $pdf->output();
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
