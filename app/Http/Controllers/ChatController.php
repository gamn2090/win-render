<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vendor;
use App\Services\VendorService;
use App\Services\ClientService;
use App\Support\VendorMessagesPresenter;
use Chat;

class ChatController extends Controller
{
    public function __construct(
        protected VendorService $vendorService,
        protected ClientService $clientService,
    ) {}
    public function vendorViewConversation(Request $request){
        $vendor = $request->user();
        $conversationId = (int) $request->id;

        $openConversation = VendorMessagesPresenter::forConversation($vendor, $conversationId);
        if ($openConversation === null) {
            return redirect()->route('vendor.inbox');
        }

        Chat::conversation(Chat::conversations()->getById($conversationId))
            ->setParticipant($vendor)
            ->readAll();

        $messages = VendorMessagesPresenter::forVendor($vendor);

        return view('vendor.messages', [
            'newInquiries' => $messages['newInquiries'],
            'inbox' => $messages['inbox'],
            'page' => 'inbox',
            'openConversation' => $openConversation,
        ]);
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
        if($authed && $other_participant){
            Chat::conversation($conversation)->setParticipant($request->user())->readAll();
            return view('couple.conversation', [
                "chat_id" => $request->id,
                "vendor" => $other_participant->messageable,
                "page" => 'inbox',
            ]);
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
