<?php

namespace App\Services;

use App\Models\Vendor;
use App\Models\User;
use App\Models\Pairing;
use App\Models\Meeting;
use Chat;
use Carbon\Carbon;

class BookingService {
    public function sendInquiry(User $client, Vendor $vendor){
        $pair = $client->pairingWith($vendor->id);
        if($pair){
            return false;
        }
        $pairing = Pairing::create(
            ['vendor_id' => $vendor->id, 'client_id' => $client->id, 'status' => 1, 'approved' => 1, 'vendor_type' => $vendor->type]
        );
        $conversation = $client->directMessageWith($vendor);
        $message = Chat::message('Client Inquiry')
            ->type('inquiry')
            ->data(['first_name' => $client->first_name, 'fiance_first_name' => $client->fiance_first_name, 'wedding_date' => Carbon::parse($client->wedding_date)->format('Y-m-d')])
            ->from($client)
            ->to($conversation)
            ->send();
        return true;
    }

    public function requestMeeting(User $client, Vendor $vendor, $meetingDate) {
        $meeting = Meeting::create([
            'client' => $client->id,
            'vendor' => $vendor->id,
            'date' => $meetingDate
        ]);
        $pair = $client->pairingWith($vendor->id);
        if(!$pair){
            $pair = Pairing::create(
                ['vendor_id' => $vendor->id, 'client_id' => $client->id, 'status' => 2, 'approved' => 1, 'vendor_type' => $vendor->type]
            );
        } else {
            $pair->setStatus(2);
        }
        $conversation = $client->directMessageWith($vendor);
        $message = Chat::message('Consultation request')
            ->type('consultation-request')
            ->data(['first_name' => $client->first_name, 'fiance_first_name' => $client->fiance_first_name, 'meeting_date' => $meetingDate])
            ->from($client)
            ->to($conversation)
            ->send();
        return true;
    }

    public function markBooked(User $client, Vendor $vendor) {
        $pair = $client->pairingWith($vendor->id);
        if($pair->status > 2){
            //vendor is already booked
            Meeting::where('type', 'wedding')->where('client', $client->id)->where('vendor', $vendor->id)->delete();
            $pair->setStatus(2);
            return true;
        }
        $pair->setStatus(3);
        $meeting = Meeting::create([
            'client' => $client->id,
            'vendor' => $vendor->id,
            'date' => $client->wedding_date,
            'type' => 'wedding',
            'approved' => 1
        ]);
        if($client->in_network == 0){
            $client->in_network = 1;
            $client->save();
        }
        $conversation = $client->directMessageWith($vendor);
        $message = Chat::message('Booked!')
            ->type('booked')
            ->data(['first_name' => $client->first_name, 'fiance_first_name' => $client->fiance_first_name, 'wedding_date' => Carbon::parse($client->wedding_date)->format('Y-m-d')])
            ->from($client)
            ->to($conversation)
            ->send();
        
        SendEmail::dispatch(\Config::get('hubspot.emails.booking_confirmed'), $vendor->email, [
            'vendor_name' => $vendor->business_name
        ]);
        return true;
    }
}