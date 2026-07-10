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
use Carbon\Carbon;

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
        if($requestType == null){
            $requestedTypeModels = $client->getRequestedVendorTypeModels();
            $requestType = $requestedTypeModels[0]->vendor_type ?? null;
        }
        return redirect()->route('search.vendors', array_filter(['type' => $requestType]));
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
        $pairings = collect($request->user()->vendorsWithStatus());
        $bookedVendors = $pairings->filter(fn ($p) => $p->status == 3 && $p->vendor);

        return view('couple.my_vendors', [
            'bookedVendors' => $bookedVendors,
            'page' => 'vendor_list',
        ]);
    }

    public function myProfile(Request $request){
        $user = $request->user();

        $weddingDateDisplay = '—';
        if ($user->wedding_date) {
            try {
                $weddingDateDisplay = Carbon::parse($user->wedding_date)->format('m-d-Y');
            } catch (\Exception $e) {
                $weddingDateDisplay = $user->wedding_date;
            }
        }

        $venueName = 'Name of Venue';
        $venueLocation = 'City, State';
        $bioText = $user->bio ?? '';

        if (preg_match('/Wedding venue:\s*([^,\n]+)(?:,\s*([^\n]+))?/i', $bioText, $venueMatch)) {
            $venueName = trim($venueMatch[1]);
            if (!empty($venueMatch[2])) {
                $venueLocation = trim($venueMatch[2]);
            } elseif ($user->wedding_location) {
                $venueLocation = $user->wedding_location;
            }
        } elseif ($user->wedding_location) {
            $venueName = $user->wedding_location;
            $venueLocation = $user->wedding_location;
        }

        $bioWithoutVenue = trim(preg_replace('/Wedding venue:.*$/im', '', $bioText));

        $bookingWindow = $user->booking_date ?: '0-3 Months';

        $answers = json_decode($user->questions ?? '[]');
        if (!is_array($answers)) {
            $answers = [null, null, null, null];
        }
        $answers = array_pad($answers, 4, null);

        $pairings = collect($user->vendorsWithStatus());
        $bookedVendors = $pairings->filter(fn ($p) => $p->status == 3 && $p->vendor);

        return view('couple.my_profile', [
            'weddingDateDisplay' => $weddingDateDisplay,
            'venueName' => $venueName,
            'venueLocation' => $venueLocation,
            'bioWithoutVenue' => $bioWithoutVenue,
            'bookingWindow' => $bookingWindow,
            'answers' => $answers,
            'vendor_types' => VendorTypes::orderBy('priority', 'asc')->get(),
            'searching_for' => $user->getRequestedVendorTypes(),
            'bookedVendors' => $bookedVendors,
            'page' => 'edit_profile',
        ]);
    }

    public function favorites(Request $request){
        $typeIds = $request->input('type');
        if (is_array($typeIds)) {
            $typeIds = array_values(array_filter($typeIds, fn ($v) => $v !== null && $v !== ''));
        } else {
            $typeIds = $typeIds ? [$typeIds] : [];
        }

        $query = $request->user()->favoritedVendors();
        if (!empty($typeIds)) {
            $query->whereIn('vendors.type', $typeIds);
        }

        $vendors = $query->paginate(12)->appends($request->query());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('couple.partials.vendor-cards', ['vendors' => $vendors])->render(),
                'has_more' => $vendors->hasMorePages(),
                'next_page' => $vendors->currentPage() + 1,
            ]);
        }

        return view('couple.favorites', [
            'vendors' => $vendors,
            'vendor_types' => VendorTypes::orderBy('priority', 'asc')->get(),
            'selected_type_ids' => $typeIds,
            'page' => 'favorites',
        ]);
    }

    //MESSAGES
    public function inbox(Request $request){
        return view('couple.inbox', [
            'conversations' => $request->user()->getAllConversations(),
            'page' => 'inbox',
        ]);
    }

    public function message(Request $request){
        $recip = Vendor::where('id', $request->id)->first();
        $conversationID = $request->user()->initiateDirectMessage($recip);
        return redirect()->route('get.client.conversation', $conversationID);
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