<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Chat;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Meeting;
use Carbon\Carbon;
use App\Services\BookingService;
use App\Services\VendorService;
use App\Jobs\SendEmail;

class BookingFlowController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected VendorService $vendorService,
    ) {}
    //
    public function requestMeeting(Request $request){
        $user = Auth::user();
        $vendor = $this->vendorService->getVendorByID($request->vendor_id);
        $meetingDate = Carbon::createFromFormat('Y-m-d H:i', $request->date)->format('Y-m-d H:i');
        $this->bookingService->requestMeeting($user, $vendor, $meetingDate);
        SendEmail::dispatch(\Config::get('hubspot.emails.consultation_requested_client'), $user->email, [
            'couple_name' => $user->first_name . ' & ' . $user->fiance_first_name
        ]);
        $data = [
            "status" => true
        ];
        return $data;
    }

    public function clientSendInquiry(Request $request){
        $user = Auth::user();
        $vendor = $this->vendorService->getVendorByID($request->vendor_id);
        if(!$vendor){
            return ["status" => false];
        }
        $this->bookingService->sendInquiry($user, $vendor);
        return ["status" => true];
    }

    //vendor answer meeting request from client
    public function answerMeetingRequest(Request $request){
        $validated = $request->validate([
            'answer' => 'required|integer|between:-1,1',
            'meeting_id' => 'required',
        ]);
        $answer = $request->answer;
        $accepted = $answer == 1;
        $vendor = $request->user();
        $meeting = Meeting::where(['uuid' => $request->meeting_id, 'vendor' => $vendor->id])->first();
        if(!$meeting){
            return ["status" => false];
        }
        $meeting->update(['approved' => $accepted]);
        $client = $meeting->client()->first();

        $conversation = $vendor->getDirectMessagesWith($client) ?: $vendor->createDirectMessageWith($client);
        Chat::message($accepted ? 'Consultation accepted' : 'Consultation declined')
            ->type('consultation-response')
            ->data(['accepted' => $accepted, 'meeting_uuid' => $meeting->uuid, 'meeting_date' => $meeting->date])
            ->from($vendor)
            ->to($conversation)
            ->send();

        if($accepted){
            SendEmail::dispatch(\Config::get('hubspot.emails.consultation_scheduled'), $vendor->email, [
                'vendor_name' => $vendor->business_name,
                'couple_name' => $client->first_name,
                'consultation_time' => $meeting->readableHour(),
                'consultation_date' => $meeting->readableDate()
            ]);
            SendEmail::dispatch(\Config::get('hubspot.emails.consultation_scheduled_client'), $client->email, [
                'couple_name' => $client->first_name . ' & ' . $client->fiance_first_name
            ]);
        } else {
            SendEmail::dispatch(\Config::get('hubspot.emails.consultation_declined_client'), $client->email, [
                'couple_name' => $client->first_name . ' & ' . $client->fiance_first_name
            ]);
        }

        return ["status" => true, "accepted" => $accepted];
    }

    // Vendor declines the requested time but proposes a different one, instead
    // of leaving the couple with just a bare "declined" and no next step.
    public function suggestNewMeetingTime(Request $request){
        $validated = $request->validate([
            'meeting_id' => 'required',
            'new_date' => 'required|date',
        ]);
        $vendor = $request->user();
        $meeting = Meeting::where(['uuid' => $validated['meeting_id'], 'vendor' => $vendor->id])->first();
        if(!$meeting){
            return ["status" => false];
        }
        $meeting->update(['approved' => false]);
        $client = $meeting->client()->first();
        $conversation = $vendor->getDirectMessagesWith($client) ?: $vendor->createDirectMessageWith($client);

        Chat::message('Consultation declined')
            ->type('consultation-response')
            ->data(['accepted' => false, 'meeting_uuid' => $meeting->uuid, 'meeting_date' => $meeting->date])
            ->from($vendor)
            ->to($conversation)
            ->send();

        $newMeeting = Meeting::create([
            'client' => $client->id,
            'vendor' => $vendor->id,
            'date' => Carbon::parse($validated['new_date'])->format('Y-m-d H:i'),
            'type' => 'consultation',
            'approved' => 0,
        ]);

        Chat::message('Suggested a new consultation time')
            ->type('consultation-suggestion')
            ->data([
                'vendor_name' => $vendor->business_name,
                'meeting_date' => $newMeeting->date,
                'meeting_uuid' => $newMeeting->uuid,
            ])
            ->from($vendor)
            ->to($conversation)
            ->send();

        return ["status" => true];
    }

    // Couple accepts/declines a new time the vendor suggested (see suggestNewMeetingTime()).
    public function answerConsultationSuggestion(Request $request){
        $validated = $request->validate([
            'meeting_id' => 'required',
            'answer' => 'required|integer|between:0,1',
        ]);
        $accepted = (bool) $validated['answer'];
        $client = Auth::user();
        $meeting = Meeting::where(['uuid' => $validated['meeting_id'], 'client' => $client->id])->first();
        if(!$meeting){
            return ["status" => false];
        }
        $meeting->update(['approved' => $accepted]);
        $vendor = $meeting->vendor()->first();

        $conversation = $client->getDirectMessagesWith($vendor) ?: $client->createDirectMessageWith($vendor);
        Chat::message($accepted ? 'Accepted the suggested time' : 'Declined the suggested time')
            ->type('consultation-suggestion-response')
            ->data(['accepted' => $accepted, 'meeting_uuid' => $meeting->uuid, 'meeting_date' => $meeting->date])
            ->from($client)
            ->to($conversation)
            ->send();

        return ["status" => true, "accepted" => $accepted];
    }

    public function markVendorBooked(Request $request){
        $validated = $request->validate([
            'vendor_uuid' => 'required',
        ]);
        $vendor = $this->vendorService->getVendorByUUID($request->vendor_uuid);
        $user = Auth::user();
        if(!$vendor){
            return ["status" => false];
        }
        if($user->bookedVendorsCount() == 0){
            SendEmail::dispatch(\Config::get('hubspot.emails.first_booking_client'), $user->email, [
                'couple_name' => $user->first_name . ' & ' . $user->fiance_first_name,
                'vendor_name' => $vendor->business_name
            ]);
        } else {
            SendEmail::dispatch(\Config::get('hubspot.emails.subsequent_booking_client'), $user->email, [
                'couple_name' => $user->first_name . ' & ' . $user->fiance_first_name,
            ]);
        }
        $this->bookingService->markBooked($user, $vendor);
        return ["status" => true];
    }
}