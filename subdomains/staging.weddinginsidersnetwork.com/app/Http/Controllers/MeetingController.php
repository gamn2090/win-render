<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Meeting;
use Carbon\Carbon;

class MeetingController extends Controller
{
    //
    public function requestMeeting(Request $request){
        $user = Auth::user();
        $vendorID = $request->vendor_id;
        $meetingDate = Carbon::parse($request->date)->format('Y-m-d H:i');
        $meeting = Meeting::create([
            'client' => $user->id,
            'vendor' => $vendorID,
            'date' => $meetingDate
        ]);
        $data = [
            "status" => true
        ];
        return $data;
    }

    public function appointmentListPage(Request $request){
        $user = $request->user();
        if(!$user){
            return redirect('/');
        }
        $data = [
            "appointments" => $user->upcomingMeetings()->get(),
        ];
        return view('appointments', ['data' => $data, 'page' => 'appointments']);
    }
}