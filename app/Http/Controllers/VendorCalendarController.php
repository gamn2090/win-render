<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
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

        $manualEvents = VendorCalendarEvent::where('vendor_id', $vendor->id)
            ->whereBetween('starts_at', [$rangeStart, $rangeEnd->copy()->endOfDay()])
            ->with('client')
            ->orderBy('starts_at')
            ->get()
            ->map(fn (VendorCalendarEvent $event) => $this->formatEvent($event));

        // Booked appointments (consultations, weddings, etc. booked through WIN)
        // auto-populate onto the calendar alongside anything the vendor adds by
        // hand — vendors shouldn't have to re-enter what WIN already knows about.
        $meetingEvents = Meeting::where('vendor', $vendor->id)
            ->where('type', '!=', 'manual')
            ->where('approved', 1)
            ->whereBetween('date', [$rangeStart, $rangeEnd->copy()->endOfDay()])
            ->with('client')
            ->orderBy('date')
            ->get()
            ->filter(fn (Meeting $meeting) => $meeting->getRelation('client'))
            ->map(fn (Meeting $meeting) => $this->formatMeetingEvent($meeting));

        $events = $manualEvents->concat($meetingEvents)->sortBy('startsAt')->values();

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
            'events' => $events,
            'bookedCouples' => $bookedCouples,
            'page' => 'vendor_calendar',
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // A calendar event either links to a couple the vendor is
            // actually booked with, or — for anything else (a day off,
            // a non-WIN client's wedding, etc.) — carries its own free-text
            // title. Exactly one of the two is required.
            'client_id' => ['nullable', 'integer'],
            'title' => ['nullable', 'string', 'max:255', 'required_without:client_id'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $vendor = $request->user();
        $clientId = $validated['client_id'] ?? null;
        if ($clientId !== null) {
            $this->authorizeBookedCouple($vendor->id, $clientId);
        }

        $event = VendorCalendarEvent::create([
            'vendor_id' => $vendor->id,
            'client_id' => $clientId,
            'title' => $clientId !== null ? null : $validated['title'],
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'notes' => $validated['notes'] ?? null,
        ]);
        $event->load('client');

        return response()->json(['event' => $this->formatEvent($event)]);
    }

    public function update(Request $request, int $event): JsonResponse
    {
        $validated = $request->validate([
            'client_id' => ['nullable', 'integer'],
            'title' => ['nullable', 'string', 'max:255', 'required_without:client_id'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $vendor = $request->user();
        $clientId = $validated['client_id'] ?? null;
        if ($clientId !== null) {
            $this->authorizeBookedCouple($vendor->id, $clientId);
        }

        $row = VendorCalendarEvent::where('id', $event)->where('vendor_id', $vendor->id)->first();
        abort_unless($row, 404);

        $row->update([
            'client_id' => $clientId,
            'title' => $clientId !== null ? null : $validated['title'],
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'notes' => $validated['notes'] ?? null,
        ]);
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
        if ($client) {
            $partnerOne = trim($client->first_name . ' ' . ($client->last_name ?? ''));
            $partnerTwo = trim(($client->fiance_first_name ?? '') . ' ' . ($client->fiance_last_name ?? ''));
            $coupleName = $partnerTwo !== '' ? $partnerOne . ' ♥ ' . $partnerTwo : $partnerOne;
        } else {
            $coupleName = $event->title ?? 'Event';
        }

        return [
            'id' => $event->id,
            'client_id' => $event->client_id,
            'title' => $event->title,
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
            'notes' => $event->notes,
            'source' => 'manual',
        ];
    }

    // Meetings (consultations, weddings, etc. booked through WIN) only carry a
    // single point-in-time `date`, so they get a synthetic 1-hour block here
    // purely for calendar display — they aren't editable/deletable through the
    // manual event CRUD endpoints since no VendorCalendarEvent row backs them.
    private function formatMeetingEvent(Meeting $meeting): array
    {
        // Meeting's FK column is literally named "client" (not "client_id"),
        // which collides with the client() relation method — Eloquent always
        // resolves the raw int column for ->client, never the relation. Pull
        // the eager-loaded relation explicitly instead.
        $client = $meeting->getRelation('client');
        $partnerOne = trim($client->first_name . ' ' . ($client->last_name ?? ''));
        $partnerTwo = trim(($client->fiance_first_name ?? '') . ' ' . ($client->fiance_last_name ?? ''));
        $coupleName = $partnerTwo !== '' ? $partnerOne . ' ♥ ' . $partnerTwo : $partnerOne;

        $start = Carbon::parse($meeting->date);

        return [
            'id' => 'meeting-' . $meeting->id,
            'client_id' => $meeting->client,
            'coupleName' => $coupleName,
            'startsAt' => $start->format('Y-m-d\TH:i:s'),
            'endsAt' => $start->copy()->addHour()->format('Y-m-d\TH:i:s'),
            'notes' => ucfirst($meeting->type) . ' booked through WIN',
            'source' => 'meeting',
        ];
    }
}
