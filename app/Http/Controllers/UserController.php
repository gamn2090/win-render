<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Pairing;
use App\Models\VendorTypes;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\CreateClientRequest;
use Chat;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use App\Services\VendorService;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct(
        protected VendorService $vendorService,
    ) {}
    public function createConnection(Request $request){
        $user = $request->user();
        $vendor = Vendor::where('id', $request->vendor_id)->first();
        $res = [
            'status' => false,
            'msg' => '' 
        ];
        if(!$vendor){
            $res['msg'] = "Sorry, this vendor was not found!";
            return $res;
        }
        //if(!$request->user()->hasRoomForType($aff_vendor->type)){
        //    $res['msg'] = "Sorry, you don't have room for that type of vendor right now! You can only have up to 2 vendors of each type.";
        //    return $res;
        //}
        if(Pairing::where('vendor_id', $vendor->id)->where('client_id', $user->id)->first() != null){
            $res['msg'] = "You already have this vendor added to your profile!";
            return $res;
        }
        $res['msg'] = "You have added " . $vendor->first_name . " " . $vendor->last_name . " to your bookings!";
        $res['status'] = true;
        $newConnection = new Pairing();
        $newConnection->vendor_id = $vendor->id;
        $newConnection->client_id = $user->id;
        $newConnection->approved = true;
        if(!$user->hasMainConnection()){
            $newConnection->approved = false;
            $newConnection->main_connection = true;
            $user->in_network = true;
            $user->save();
            $res['msg'] = "You have requested to add " . $vendor->first_name . " " . $vendor->last_name . " to your bookings! If your request is accepted, you will unlock their in-network discounts!";
        } else {
            $newConnection->discount_eligible = true;
        }
        $newConnection->save();
        return $res;
    }

    public function searchVendorsPage(Request $request){
        $client = $request->user();
        if($client){
            return view('client.search_vendors', [
                'vendor_types' => VendorTypes::orderBy('priority', 'asc')->get(),
                'requestedTypes' => $client->requestedVendorTypes()->orderBy('priority', 'asc')->get(),
                'vendors' => $this->vendorService->getVendorsByRank($requestType)->paginate(20),
                'selected_type' => VendorTypes::where('id', $request->type)->first(),
                'page' => 'search_vendors'
            ]);
        } else {
            return view('login');
        }
    }

    public function searchVendorType(Request $request){
        $client = $request->user();
        $requestType = $request->type;
        $requestedVendorTypes = $client->getRequestedVendorTypeModels();
        if($requestType == null){
            $requestType = $client->getRequestedVendorTypeModels()[0]->vendor_type;
        }
        if($client){
            return view('client.search_vendors', [
                'vendor_types' => VendorTypes::orderBy('priority', 'asc')->get(),
                'requestedTypes' => $client->requestedVendorTypes()->orderBy('priority', 'asc')->get(),
                'vendors' => $this->vendorService->getVendorsByRank($requestType)->paginate(20),
                'selected_type' => VendorTypes::where('id', $requestType)->first(),
                'page' => 'search_vendors'
            ]);
        } else {
            return view('login');
        }
    }

    public function searchVendorTypeGuest(Request $request){
        $requestType = $request->type;
        if($requestType == null){
            $requestType = 1;
        }
        return view('guest_search_vendors', [
            'vendor_types' => VendorTypes::orderBy('priority', 'asc')->get(),
            'requestedTypes' => VendorTypes::orderBy('priority', 'asc')->get(),
            'vendors' => $this->vendorService->getVendorsByRank($requestType)->paginate(20),
            'selected_type' => VendorTypes::where('id', $requestType)->first(),
            'page' => 'search_vendors'
        ]);
    }

    public function vendorList(Request $request){
        $data = [
            "vendors" => []
        ];
        $data["vendors"] = $request->user()->vendors();
        return view('vendor.vendor_list', ['data' => $data, 'page' => 'vendor_list']);
    }

    //MESSAGES
    public function inbox(Request $request){
        $data = [
            "conversations" => null
        ];  
        $data["conversations"] = $request->user()->getAllConversations();
        return view('chat.client_inbox', ['data' => $data, 'page' => 'inbox']);
    }

    public function message(Request $request){
        $recip = Vendor::where('id', $request->id)->first();
        $conversationID = $request->user()->initiateDirectMessage($recip);
        return $conversationID;
    }

    public function getMessages(Request $request){
        $convoID = $request->convoID;
        $user = $request->user();
        $messages = $user->getMessages($convoID);
        \App\Models\ChatMessageAttachment::enrichMessages($messages->items());
        $res = [
            "messages" => $messages
        ];
        return json_encode($res);
    }

    public function sendMessage(Request $request){
        $convoID = $request->conversation;
        $msg = $request->message;
        $vendor = $request->user();
        $vendor->sendMessage($msg, $convoID);
    }

    public function toggleFavoriteVendor(Request $request){
        $vendor_id = $this->vendorService->uuidToID($request->vendor_uuid);
        if(!$vendor_id){
            return ["status" => false, "message" => "Vendor not found."];
        }
        $fav = Favorite::where('vendor_id', $vendor_id)->where('user_id', Auth::user()->id)->first();
        if($fav){
            $fav->delete();
        } else {
            Favorite::create(['vendor_id' => $vendor_id, 'user_id' => Auth::user()->id]);
        }
        return ["status" => true];
    }

    public function removePairing(Request $request){
        $vendor_id = $this->vendorService->uuidToID($request->vendor_uuid);
        if(!$vendor_id){
            return ["status" => false, "message" => "Vendor not found"];
        }
        $pairing = $request->user()->pairingWith($vendor_id);
        if(!$pairing){
            return ["status" => false, "message" => "Pairing not found"];
        }
        $pairing->delete();
        return ["status" => true];
    }
}