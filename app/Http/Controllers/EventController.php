<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Tag;
use App\Models\TagType;


class EventController extends Controller
{
    //
    public function joinEventWithCode(Request $request){
        $vendor = $request->user();
        $event = Event::where('code', $request->event)->first();
        if(!$event){
            return response()->json(['message' => 'Event not found'], 404);
        }
        if($event->end_date && $event->end_date < now()){
            return response()->json(['message' => 'Event has ended'], 403);
        }
        $vendor->updateTag("Event", ["Event" => $event->id]);
    }
}
