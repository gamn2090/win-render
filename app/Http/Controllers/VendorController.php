<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorTypes;
use App\Support\VendorMessagesPresenter;
use App\Support\VendorClientsPresenter;
use App\Support\VendorDemoClients;
use App\Support\VendorDemoConnections;
use App\Support\VendorNetworkPresenter;
use App\Models\VendorConnection;
use App\Models\Pairing;
use App\Models\Referral;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\CreateClientRequest;
use App\Models\Endorsement;
use App\Models\Inquiry;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Payment;
use App\Models\TagType;
use App\Models\Tag;
use Chat;
use Musonza\Chat\Models\Message;
use App\Services\VendorService;
use App\Services\HubspotService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function __construct(
        protected VendorService $vendorService,
        protected HubspotService $hubspotService,
    ) {}

    public function searchVendorType(Request $request){
        $requestedVendorTypes = VendorTypes::orderBy('priority', 'asc')->get();

        $requestType = $request->input('type');
        if (is_array($requestType)) {
            $requestType = array_values(array_filter($requestType, fn ($v) => $v !== null && $v !== ''));
        }
        $selectedTypeIds = is_array($requestType) ? $requestType : ($requestType ? [$requestType] : []);
        $primaryTypeId = $selectedTypeIds[0] ?? null;

        $filters = $request->input('filter');
        $searchTerm = trim((string) $request->input('q'));
        $vendorsQuery = $this->vendorService->getVendorsByRank($selectedTypeIds, $filters)->with('profile');
        if ($searchTerm !== '') {
            $vendorsQuery->where(function ($q) use ($searchTerm) {
                $q->where('business_name', 'like', "%{$searchTerm}%")
                    ->orWhere('first_name', 'like', "%{$searchTerm}%")
                    ->orWhere('last_name', 'like', "%{$searchTerm}%");
            });
        }

        $filterVendorTypeId = VendorTypes::where('type', 'Venue')->value('id');
        $allowedFilters = TagType::where('hidden', 0)
            ->where('vendor_type_id', $filterVendorTypeId)
            ->whereIn('name', ['Venue Type', 'Max Guest Capacity', 'Location', 'Budget'])
            ->get();

        $data = [
            'vendor_types' => $requestedVendorTypes,
            'allowedFilters' => $allowedFilters,
            'requestedTypes' => $requestedVendorTypes, //for parity with client search
            'selected_type' => $primaryTypeId ? VendorTypes::where('id', $primaryTypeId)->first() : null,
            'selected_type_ids' => $selectedTypeIds,
            'search_term' => $searchTerm,
            'page' => 'search_vendors'
        ];

        if (Auth::guard('web')->check()) {
            $data['vendors'] = $vendorsQuery->paginate(20)->appends($request->query());
            return view('couple.search_vendors', $data);
        }

        $authVendor = Auth::guard('vendor')->user();
        $vendorsQuery->where('id', '!=', $authVendor->id);
        $data['vendors'] = $vendorsQuery->paginate(20)->appends($request->query());
        $data['connectedVendorIds'] = VendorConnection::where('host_vendor', $authVendor->id)->pluck('aff_vendor')->all();

        return view('vendor.search_vendors', $data);
    }

    public function createClientInvite(Request $request){
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'personal_note' => ['string', 'max:250', 'nullable']
        ]);

        $vendor = $request->user();

        $user = new User([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'fiance_first_name' => $request->fiance_first_name,
            'fiance_last_name' => $request->fiance_last_name,
            'email' => Str::lower($request->email),
            'wedding_location' => $request->venue,
            'wedding_date' => $request->wedding_date
        ]);

        //check if referral already exists
        if(Referral::where('model_type', 'user')->where('ref_by', $vendor->id)->where('data', $user->toJson())->first() != null){
            return back()->withErrors(['Referral already exists!' => 'You have already attempted to refer this user!']);
        }
        if(Vendor::where('email', $request->email)->first() != null || User::where('email', $request->email)->first() != null){
            return back()->withErrors(['Not eligible!' => 'This email is not eligible to be referred!']);
        }
        
        $ref = Referral::create([
            'model_type' => "user",
            'data' => $user->toJson(),
            'ref_by' => $vendor->id
        ]);

        /*$pair = Pairing::create([
            'vendor_id' => $request->user()->id,
            'client_id' => $user->id,
            'main_connection' => true,
            'discount_eligible' => false,
            'approved' => true
        ]);*/
        $personalNote = $request->personal_note ? '<br> A personal note from ' . $vendor->business_name . ': <br> ' . $request->personal_note . '' : '';

        
        $this->hubspotService->sendEmail(186918097179, [
            'vendor_name' => $vendor->first_name . ' ' . $vendor->last_name,
            'couple_name' => $request->first_name . ' & ' . $request->fiance_first_name,
            'vendor_business_name' => $vendor->business_name,
            'link' => 'https://weddinginsidersnetwork.com/ref/c/' . urlencode($vendor->business_name),
            'personal_note' => $personalNote
        ], Str::lower($request->email));

        if ($request->input('return_to') === 'client_list') {
            return redirect('/vendor/client/list')->with('client_invite_success', true);
        }

        return view('vendor_create_client', ['linkID' => $ref->uuid, 'page' => 'client_invite']);
    }

    public function createVendorInvite(Request $request){
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['string', 'max:255', 'nullable'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.Vendor::class],
            'personal_note' => ['string', 'max:250', 'nullable']
        ]);
        $user = $request->user();
        $referredModel = new Vendor();
        $referredModel->first_name = $request->first_name;
        $referredModel->last_name = $request->last_name;
        $referredModel->email = $request->email;
        $personalNote = $request->personal_note ? '<br> A personal note from ' . $user->business_name . ': <br> ' . $request->personal_note . '' : '';
        //check if referral already exists
        //if(Referral::where('model_type', 'vendor')->where('ref_by', $user->id)->where('data', $referredModel->toJson())->first() != null){
        //    return back()->withErrors(['Referral already exists!' => 'You have already attempted to refer this vendor!']);
        //}
        if(!App::environment('staging') && (Vendor::where('email', $request->email)->first() != null || User::where('email', $request->email)->first() != null)){
            return back()->withErrors(['Not eligible!' => 'This email is not eligible to be referred!']);
        }
        
        $ref = Referral::create([
            'model_type' => "vendor",
            'data' => $referredModel->toJson(),
            'ref_by' => $user->id
        ]);
        $this->hubspotService->sendEmail(186561458518, [
            'ref_vendor_name' => $request->first_name,
            'vendor_name' => $user->first_name . ' ' . $user->last_name,
            'vendor_business_name' => $user->business_name,
            'link' => 'https://weddinginsidersnetwork.com/ref/v/' . urlencode($user->business_name),
            'personal_note' => $personalNote
        ], $request->email);
        return view('vendor_create_vendors', ['linkID' => $ref->id, 'page' => 'vendor_invite']);
    }

    public function vendorDashboard(Request $request){
        $vendor = $request->user();
        //TODO: move/have update elsewhere
        $this->vendorService->refreshEarnedBadges($vendor);

        $subscription_status = $request->payment;
        $first_login = false;

        $vendorSubscription = $vendor->stripeSubscription();

        if($subscription_status == 'awaiting_confirmation' || $vendorSubscription){
            $first_login = $request->session()->pull('first-login', false);
        }

        $data = [
            "clients" => $vendor->clients(5),
            "connections" => $vendor->connections(5)->get(),
            'score' => $vendor->updateAllRankingScores(),
            "recentConversations" => $vendor->recentConversations(20),
            "ranking" => $vendor->vendor_ranking(),
            "subscription_status" => $subscription_status,
            "first_login" =>  $first_login,
            'subscription' => $vendorSubscription,
            'activeBookings' => Pairing::where('vendor_id', $vendor->id)->where('status', 3)->count(),
            'newLeadsToday' => Pairing::where('vendor_id', $vendor->id)->whereDate('created_at', today())->count(),
            'newLeadsCount' => Pairing::where('vendor_id', $vendor->id)->whereIn('status', [1, 2])->count(),
        ];
        
        $placement = Vendor::where('type', $vendor->type)->where('score', '>', $vendor->score)->count();
        $data["placement"] = $placement;
        $data["sameTypeVendors"] = Vendor::where('type', $vendor->type)->count();
        return view('vendor.dashboard', ['data' => $data, 'page' => 'dashboard']);
    }

    public function insights(Request $request){
        $vendor = $request->user();
        $score = $vendor->updateAllRankingScores();
        $placement = Vendor::where('type', $vendor->type)->where('score', '>', $vendor->score)->count();
        $vendorTypeModel = $vendor->getType();

        $data = [
            'score' => $score,
            'ranking' => $vendor->vendor_ranking(),
            'placement' => $placement,
            'typeLabel' => $vendorTypeModel?->type ?? 'Vendor',
            'location' => $vendor->location ?: 'Your area',
            'vendorsReferred' => $vendor->vendorReferrals(),
            'clientsReferred' => $vendor->clientReferrals(),
            'storefrontViews' => $vendor->storefront_views ?? 0,
            'timesFavorited' => $vendor->timesFavorited(),
        ];

        return view('vendor.insights', ['data' => $data, 'page' => 'insights']);
    }

    public function vendorDashboardTest(Request $request){
        $vendor = $request->user();
        $data = [
            "clients" => [],
            "connections" => $vendor->connections,
            'score' => $vendor->updateAllRankingScores()
        ];     
        $placement = Vendor::where('type', $vendor->type)->where('score', '>', $vendor->score)->count();
        $data["placement"] = $placement;
        $data["sameTypeVendors"] = Vendor::where('type', $vendor->type)->count();
        $pairs = Pairing::where('vendor_id', $vendor->id)->where('active', 1)->get();
        foreach($pairs as $pair){
            $user = User::where('id', $pair->client_id)->first();
            if($user){
                array_push($data["clients"], $user);
            }
        }
        return view('vendor_dashboard_test', ['data' => $data, 'page' => 'dashboard']);
    }

    //show booked page
    public function booked(Request $request){
        $data = [
            "clients" => []
        ];        
        $pairs = Pairing::where('vendor_id', $request->user()->id)->get();
        foreach($pairs as $pair){
            $user = User::where('id', $pair->client_id)->first();
            if($user){
                array_push($data["clients"], $user);
            }
        }
        return view('vendor_booked', ['data' => $data]);
    }
    
    public function archive(Request $request){
        $data = [
            "clients" => []
        ];        
        $pairs = Pairing::where('vendor_id', $request->user()->id)->get();
        foreach($pairs as $pair){
            $user = User::where('id', $pair->client_id)->first();
            if($user){
                array_push($data["clients"], $user);
            }
        }
        return view('vendor_archive', ['data' => $data, 'page' => 'archive']);
    }

    public function vendorList(Request $request){
        $vendor = $request->user();
        VendorDemoConnections::ensureFor($vendor);
        $vendors = VendorNetworkPresenter::forVendor($vendor);

        return view('vendor.vendor_network', [
            'vendors' => $vendors,
            'page' => 'vendor_list',
        ]);
    }

    public function removeConnection(Request $request){
        $validated = $request->validate([
            'aff_vendor' => ['required', 'integer'],
        ]);

        $host = $request->user();
        VendorConnection::query()
            ->where('host_vendor', $host->id)
            ->where('aff_vendor', $validated['aff_vendor'])
            ->delete();

        return redirect()
            ->route('vendor.list')
            ->with('vendor_network_removed', true);
    }

    public function clientList(Request $request){
        $vendor = $request->user();
        VendorDemoClients::ensureFor($vendor);

        return view('vendor.client_list', [
            'activeClients' => VendorClientsPresenter::forVendor($vendor, true),
            'archivedClients' => VendorClientsPresenter::forVendor($vendor, false),
            'page' => 'client_list',
        ]);
    }

    public function exportClientList(Request $request){
        $vendor = $request->user();
        VendorDemoClients::ensureFor($vendor);
        $rows = VendorClientsPresenter::csvRowsForVendor($vendor);

        $filename = 'win-current-clients-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Expires' => '0',
            'Pragma' => 'public',
        ];

        $csvHeaders = ['Status', 'Client First Name', 'Fiance Name', 'Email', 'Wedding Location', 'Wedding Date'];

        $callback = static function () use ($rows, $csvHeaders): void {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, $csvHeaders);
            foreach ($rows as $row) {
                fputcsv($handle, array_values($row));
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
    
    public function archiveClient($id, $ven_id){
        $pairs = Pairing::where('vendor_id', $ven_id)->where('active', 1)->where('client_id', $id)->first();
        if($pairs){
            $pairs->active = 0;
            $pairs->save();
        }
        return redirect('/vendor/client/list')
            ->with('toast', ['message' => 'Client Archived.', 'type' => 'success']);
    }

    public function unarchiveClient($id, $ven_id){
        $pairs = Pairing::where('vendor_id', $ven_id)->where('active', 0)->where('client_id', $id)->first();
        if($pairs){
            $pairs->active = 1;
            $pairs->save();
        }
        return redirect()->route('vendor.client.list')
            ->with('toast', ['message' => 'Client Unarchived.', 'type' => 'success']);
    }

    public function getInquiries(Request $request){
        $data = [
            "conversations" => null
        ];
        $data["conversations"] = $request->user()->getAllConversations();
        return view('profile.inquiries', ['data' => $data, 'page' => 'inquiries']);
    }

    public function findCouples(Request $request){
        $data = [
            "clients" => []
        ];
        $user = $request->user();
        if($user->email == "info@melissalewisphotography.com"){
            $inquiries = Inquiry::select('user_id')->distinct()->get()->toArray();
        } else{
            $inquiries = $this->vendorService->inquiriesForType($user->type)->pluck('user_id')->toArray();
        }
        $joinedEvents = $user->joinedEvents()->get()->concat([null]);
        $searchResults = User::whereIn('id', $inquiries)->where(function ($query) use ($joinedEvents){
            $query->whereIn('event', $joinedEvents)->orWhereNull('event');
        })->where('created_at', '<=', Carbon::now()->subHours(24))->with('pairing', 'inquiries')->orderBy('first_name', 'asc')->orderBy('fiance_first_name', 'asc');
        if($request->wedding_date == 'false'){
            $searchResults = $searchResults->where('wedding_date', '!=', null);
        }
        $data["clients"] = $searchResults->paginate(12);
        return view('vendor.find_couples', ['data' => $data, 'vendor_types' => VendorTypes::all(), 'page' => 'find_couples']);
    }

    public function coupleProfile(Request $request, string $id)
    {
        $client = User::where('uuid', $id)->first();

        if (! $client) {
            return view('404');
        }

        $isBookedByCurrentVendor = Pairing::where('vendor_id', $request->user()->id)
            ->where('client_id', $client->id)
            ->where('status', 3)
            ->exists();

        return view('vendor.couple_profile', [
            'client' => $client,
            'vendor_types' => VendorTypes::orderBy('priority', 'asc')->get(),
            'searching_for' => $client->getRequestedVendorTypes(),
            'booked_vendors' => $client->bookedVendors()->get(),
            'isBookedByCurrentVendor' => $isBookedByCurrentVendor,
            'page' => 'find_couples',
        ]);
    }

    public function createConnectionRequest(Request $request){
        $host_vendor = $request->user();
        $aff_vendor = Vendor::where('id', $request->aff_id)->first();
        $res = [
            'status' => false,
            'msg' => '' 
        ];
        if(!$aff_vendor){
            $res['msg'] = "Sorry, this vendor was not found!";
            return $res;
        }
        if(!$request->user()->hasRoomForType($aff_vendor->type)){
            $res['msg'] = "Sorry, you don't have room for that type of vendor right now! You can only have up to 2 vendors of each type.";
            return $res;
        }
        if(VendorConnection::where('host_vendor', $host_vendor->id)->where('aff_vendor', $aff_vendor->id)->first() != null){
            $res['msg'] = "You already have this vendor added to your profile!";
            return $res;
        }
        $newConnection = new VendorConnection();
        $newConnection->host_vendor = $host_vendor->id;
        $newConnection->host_vendor_type = $host_vendor->type;
        $newConnection->aff_vendor = $aff_vendor->id;
        $newConnection->aff_vendor_type = $aff_vendor->type;
        $newConnection->approved = true;
        $newConnection->save();
        $conversation = Chat::conversations()->between($host_vendor, $aff_vendor);
        if($conversation == null){
            $conversation = $request->user()->initiateDirectMessage($aff_vendor);
            $conversation = Chat::conversations()->getById($conversation);
        }
        $message = Chat::message($host_vendor->first_name . ' has added you as a preferred vendor on their storefront!')
            ->from($request->user())
            ->to($conversation)
            ->send();
        $res['status'] = true;
        $res['msg'] = "You have added this vendor to your preferred vendors! They now show up on your storefront.";
        return $res;
    }

    public function answerConnectionRequest(Request $request){
        $aff_vendor = $request->user();
        $connection_request = VendorConnection::where('host_vendor', $request->host_id)->where('aff_vendor', $aff_vendor->id)->first();
        //dd($request->response);
        $response = $request->response;
        $res = [
            'show_msg' => true,
            'status' => false,
            'msg' => '' 
        ];
        if($response == 'true'){
            $connection_request->approved = true;
            $connection_request->save();
            $res["status"] = true;
            $res["msg"] = "You have accepted the invite! You will now appear with their affiliated vendors.";
            return $res;
        } else if($response == 'false'){
            $connection_request->delete();
            $res["status"] = true;
            $res["show_msg"] = false;
            return $res;
        } else {
            $res["msg"] = "Unknown";
            return $res;
        }
    }

    public function answerPairingRequest(Request $request){
        $connection_request = Pairing::where('id', $request->connection_id)->where('vendor_id', $request->user()->id)->first();
        $response = boolval($request->response);
        $res = [
            'show_msg' => true,
            'status' => false,
            'msg' => '' 
        ];
        if($response == true){
            $connection_request->approved = true;
            if($connection_request->main_connection == true){
                $connection_request->discount_eligible = false;
            } else {
                $connection_request->discount_eligible = true;
            }
            $connection_request->save();
            $res["status"] = true;
            $res["msg"] = "You have accepted the request! This client will now have access to in-network discounts!";
            return $res;
        } else if($response == false){
            $connection_request->delete();
            $res["status"] = true;
            $res["show_msg"] = false;
            return $res;
        } else {
            $res["msg"] = "Unknown";
            return $res;
        }
    }

    //messages
    public function inbox(Request $request){
        $vendor = $request->user();
        $messages = VendorMessagesPresenter::forVendor($vendor);

        return view('vendor.messages', [
            'newInquiries' => $messages['newInquiries'],
            'inbox' => $messages['inbox'],
            'page' => 'inbox',
        ]);
    }

    public function message(Request $request){
        //$data = [
        //    "conversations" => null,
        //    "activeConversation" => null
        //];
        //$data["conversations"] = $request->user()->getAllConversations();
        $recip = Vendor::where('id', $request->id)->first();
        $conversationID = $request->user()->initiateDirectMessage($recip);
        return $conversationID;
    }

    public function showVendorChat(Request $request){
        $recip = Vendor::where('id', $request->id)->first();
        $conversationID = $request->user()->initiateDirectMessage($recip);
        return redirect()->route('get.conversation', ['id' => $conversationID]);
    }

    public function messageClient(Request $request){
        $recip = User::where('id', $request->id)->first();
        $conversationID = $request->user()->initiateDirectMessage($recip);
        return redirect()->route('get.conversation', ['id' => $conversationID]);
    }

    public function messageVendor(Request $request){
        $recip = $this->vendorService->getVendorByUUID($request->vendor_uuid);
        $conversationID = $request->user()->initiateDirectMessage($recip);
        return redirect()->route('get.conversation', ['id' => $conversationID]);
    }

    public function sendMessage(Request $request){
        $convoID = $request->conversation;
        $msg = $request->message;
        $vendor = $request->user();
        $vendor->sendMessage($msg, $convoID);
    }

    public function unreadNotificationsCount(Request $request): \Illuminate\Http\JsonResponse
    {
        $vendor = $request->user();
        $data = $vendor->getUnreadMessagesCount();

        return response()->json([
            'count' => count($data['vendor_notifs'] ?? []),
            'message_count' => $vendor->unreadMessagesCount(),
        ]);
    }

    public function getMessages(Request $request){
        $convoID = $request->convoID;
        $vendor = $request->user();
        $conversation = Chat::conversations()->getById($convoID);
        if ($conversation) {
            Chat::conversation($conversation)->setParticipant($vendor)->readAll();
        }
        $messages = $vendor->getMessages($convoID);
        \App\Models\ChatMessageAttachment::enrichMessages($messages->items());
        $res = [
            "messages" => $messages
        ];
        return json_encode($res);
    }

    public function getMessagesFromID(Request $request){
        $otherID = $request->otherID;
        $otherType = $request->userType;
        $vendor = $request->user();
        $convo = $vendor->getMessagesFromID($otherID, $otherType);
        \App\Models\ChatMessageAttachment::enrichMessages($convo["conversation"]->items());
        $res = [
            "messages" => $convo["conversation"],
            "convoID" => $convo["id"]
        ];
        return json_encode($res);
    }

    //payment
    public function attemptPayment(Request $request){
        return $request->user()
        ->newSubscription('WIN Subscription', 'price_1QdHXkD7AsRDYMZziFTlqYpC')
        ->allowPromotionCodes()
        ->checkout([
            'success_url' => route('vendor.dashboard', ['payment' => 'awaiting_confirmation']),
            'cancel_url' => route('vendor.dashboard'),
            'subscription_data' => [
              'trial_period_days' => 60,
              'trial_settings' => ['end_behavior' => ['missing_payment_method' => 'pause']],
            ],
            'payment_method_collection' => 'if_required',
        ]);
    }

    public function confirmSubscription(Request $request){
        $session_id = $request->session_id;
        $payment = Payment::where('vendor_id', $request->user()->id)->where('confirmed', false)->first();
        $payment->confirmed = true;
        $payment->save();
        return redirect('/vendor/dashboard');
    }

    //endorsements
    public function endorse(Request $request){
        $vendor = $this->vendorService->getVendorByUUID($request->vendor_uuid);
        $endorsements = $request->input('endorsements');
        if($endorsements == null){
            return;
        }
        $newEndorsements = [];
        $existingEndorsements =  $vendor->endorsements()->where('endorser', $request->user()->id)->pluck('type')->toArray();
        foreach($endorsements as $endorsement){
            if(in_array(intval($endorsement), $existingEndorsements)){
                continue;
            }
            if((1 <= intval($endorsement)) && (intval($endorsement) <= 6)){
                array_push($newEndorsements, ['vendor_id' => $vendor->id, "type" => intval($endorsement), 'endorser' => $request->user()->id]);
            } else {
                unset($endorsements[$endorsement]);
            }
        }
        $vendor->endorsements()->where('endorser', $request->user()->id)->whereNotIn('type', $endorsements)->delete();
        Endorsement::upsert($newEndorsements, uniqueBy: ['endorser', 'type', 'vendor_id']);
    }

    //google link
    /*public function googleAuth(Request $request){
        $scopes = [
            "https://www.googleapis.com/auth/calendar.freebusy",
            "https://www.googleapis.com/auth/calendar.calendarlist.readonly"
        ];
        $parameters = [
            "access_type" => "offline",
            "prompt" => "consent select_account"
        ];
        return Socialite::driver('google')->scopes($scopes)->with($parameters)->stateless()->redirect();
    }

    public function googleCallback(Request $request){
        $authedUser = Socialite::driver('google')->stateless()->user();
        $vendor = Auth::guard('vendor')->user();
        $vendor->google_token = $authedUser->token;
        $vendor->google_refresh_token = $authedUser->refreshToken;
        $vendor->save();
        return redirect('/vendor/dashboard');
    }*/

    //preregistration
    public function preregister(){
        return view('pre.pre-register', ["vendorTypes" => VendorTypes::orderBy('priority', 'asc')->get()]);
    }
}
