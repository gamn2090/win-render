<?php

namespace App\Http\Controllers;

use App\Models\Pairing;
use App\Models\VendorCalendarEvent;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorCalendarController extends Controller
{
    public function index(Request $request): View
    {
        $vendor = $request->user();
        $view = in_array($request->query('view'), ['month', 'week', 'day'], true) ? $request->query('view') : 'month';
        $date = Carbon::parse($request->query('date', now()->toDateString()))->startOfDay();

        [$rangeStart, $rangeEnd] = match ($view) {
            'month' => [
                $date->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY),
                $date->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY),
            ],
            'week' => [
                $date->copy()->startOfWeek(Carbon::SUNDAY),
                $date->copy()->endOfWeek(Carbon::SUNDAY),
            ],
            default => [$date->copy()->startOfDay(), $date->copy()->endOfDay()],
        };

        $events = VendorCalendarEvent::where('vendor_id', $vendor->id)
            ->whereBetween('starts_at', [$rangeStart, $rangeEnd->copy()->endOfDay()])
            ->with('client')
            ->orderBy('starts_at')
            ->get();

        $bookedCouples = Pairing::where('vendor_id', $vendor->id)
            ->where('status', 3)
            ->with('client')
            ->get()
            ->pluck('client')
            ->filter()
            ->values();

        [$prevDate, $nextDate] = match ($view) {
            'month' => [$date->copy()->subMonthNoOverflow()->startOfMonth(), $date->copy()->addMonthNoOverflow()->startOfMonth()],
            'week' => [$date->copy()->subWeek(), $date->copy()->addWeek()],
            default => [$date->copy()->subDay(), $date->copy()->addDay()],
        };

        return view('vendor.calendar', [
            'view' => $view,
            'date' => $date,
            'rangeStart' => $rangeStart,
            'rangeEnd' => $rangeEnd,
            'prevDate' => $prevDate->toDateString(),
            'nextDate' => $nextDate->toDateString(),
            'todayDate' => now()->toDateString(),
            'events' => $events->map(fn (VendorCalendarEvent $event) => $this->formatEvent($event)),
            'bookedCouples' => $bookedCouples,
            'page' => 'vendor_calendar',
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'client_id' => ['required', 'integer'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
        ]);

        $vendor = $request->user();
        $this->authorizeBookedCouple($vendor->id, $validated['client_id']);

        $event = VendorCalendarEvent::create([
            'vendor_id' => $vendor->id,
            'client_id' => $validated['client_id'],
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
        ]);
        $event->load('client');

        return response()->json(['event' => $this->formatEvent($event)]);
    }

    public function update(Request $request, int $event): JsonResponse
    {
        $validated = $request->validate([
            'client_id' => ['required', 'integer'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
        ]);

        $vendor = $request->user();
        $this->authorizeBookedCouple($vendor->id, $validated['client_id']);

        $row = VendorCalendarEvent::where('id', $event)->where('vendor_id', $vendor->id)->first();
        abort_unless($row, 404);

        $row->update($validated);
        $row->load('client');

        return response()->json(['event' => $this->formatEvent($row)]);
    }

    public function destroy(Request $request, int $event): JsonResponse
    {
        $row = VendorCalendarEvent::where('id', $event)->where('vendor_id', $request->user()->id)->first();
        abort_unless($row, 404);

        $row->delete();

        return response()->json(['deleted' => true]);
    }

    private function authorizeBookedCouple(int $vendorId, int $clientId): void
    {
        $isBooked = Pairing::where('vendor_id', $vendorId)
            ->where('client_id', $clientId)
            ->where('status', 3)
            ->exists();

        abort_unless($isBooked, 403, 'You can only schedule events with couples you are booked with.');
    }

    private function formatEvent(VendorCalendarEvent $event): array
    {
        $client = $event->client;
        $partnerOne = trim($client->first_name . ' ' . ($client->last_name ?? ''));
        $partnerTwo = trim(($client->fiance_first_name ?? '') . ' ' . ($client->fiance_last_name ?? ''));
        $coupleName = $partnerTwo !== '' ? $partnerOne . ' ♥ ' . $partnerTwo : $partnerOne;

        return [
            'id' => $event->id,
            'client_id' => $event->client_id,
            'coupleName' => $coupleName,
            // Naive (no timezone offset) on purpose — these are wall-clock
            // times with no real timezone meaning, matching the plain
            // date/time form inputs. Using toIso8601String() here would
            // embed the server's app timezone offset, and JS's Date
            // getHours()/getMinutes() report the BROWSER's local time, not
            // the offset in the string — causing the edit modal to prefill
            // the wrong hour whenever server and browser timezones differ.
            'startsAt' => $event->starts_at->format('Y-m-d\TH:i:s'),
            'endsAt' => $event->ends_at->format('Y-m-d\TH:i:s'),
        ];
    }
}
