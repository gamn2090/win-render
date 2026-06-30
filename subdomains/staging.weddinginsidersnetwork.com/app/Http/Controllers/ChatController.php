<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vendor;
use App\Services\VendorService;
use App\Services\ClientService;
use Chat;

class ChatController extends Controller
{
    public function __construct(
        protected VendorService $vendorService,
        protected ClientService $clientService,
    ) {}
    public function vendorViewConversation(Request $request){
        $other_participant = null;
        $authed = false;
        $conversation = Chat::conversations()->getById($request->id);
        $user = $request->user();
        foreach($conversation->participants as $participant){
            if($participant->messageable->id != $user->id){
               $other_participant = $participant;
            }
            else if($participant->messageable_type == 'App\Models\User'){
                $other_participant = $participant;
            } else if($participant->messageable->id == $user->id){
                $authed = true;
            }
        }
        $vendor_invite = null;
        if($other_participant->messageable_type == 'App\Models\Vendor'){
            if($user->hasRequestFrom($other_participant->messageable->id)){
                $vendor_invite = true;
            }
        }
        $client_invite = null;
        $meeting_request = null;
        if($other_participant->messageable_type == 'App\Models\User'){
            $client_invite = $user->hasRequestFromClient($other_participant->messageable->id);
            $meeting_request = $user->meetingsWith($other_participant->messageable->id)->where('approved', 0)->first();
        }
        $data = [
            "chat_id" => $request->id, 
            "participant" => $other_participant, 
            "vendor_invite" => $vendor_invite, 
            "client_invite" => $client_invite, 
            "page" => 'inbox',
            "meeting_request" => $meeting_request
        ];
        if($authed){
            Chat::conversation($conversation)->setParticipant($request->user())->readAll();
            return view('chat.chat', $data);
        }
        return redirect('/');
    }

    public function clientViewConversation(Request $request){
        $other_participant = null;
        $authed = false;
        $conversation = Chat::conversations()->getById($request->id);
        foreach($conversation->participants as $participant){
            if($participant->messageable_type == 'App\Models\Vendor'){
               $other_participant = $participant;
            } else if($participant->messageable->id == $request->user()->id){
                $authed = true;
            }
        }
        $vendor_invite = null;
        if($authed){
            Chat::conversation($conversation)->setParticipant($request->user())->readAll();
            return view('chat.user_chat', ["chat_id" => $request->id, "participant" => $other_participant, "vendor_invite" => $vendor_invite, "page" => 'inbox']);
        }
        return redirect('/');
    }

    public function messageVendor(Request $request){
        $recip = $this->vendorService->getVendorByUUID($request->vendor_uuid);
        if(!$recip){
            return null;
        }
        $user = $request->user('web');
        if(!$user){
            $user = $request->user('vendor');
        }
        if(!$user) { return; }
        $conversationID = $user->initiateDirectMessage($recip);
        return redirect()->route('get.' . $user->userType() . '.conversation', ['id' => $conversationID]);
    }

    public function messageClient(Request $request){
        $user = $request->user();
        $recip = $this->clientService->getByUUID($request->client_uuid);
        if(!$recip){
            return ["status" => false, "message" => "Client not found!" . $request->client_uuid];
        }
        $conversation = $user->getDirectMessagesWith($recip);
        if($conversation != null){
            //vendor has convo with client
            return ["status" => true, "c_id" => $conversation->id];
        }
        //vendor has no conversation with client
        if($user->contact_credits < 1){
            //vendor has no credits left
            return ["status" => false, "message" => "You don't have enough contact credits remaining!"];
        }
        $user->useContactCredit();
        $conversationID = $user->initiateDirectMessage($recip);
        return ["status" => true, "c_id" => $conversationID];
    }
    
}
