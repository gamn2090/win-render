<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\VendorTypes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Profile;
use App\Models\Pairing;
use App\Models\User;
use App\Models\Payment;
use App\Models\Meeting;
use App\Models\Favorite;
use App\Models\Badge;
use App\Models\Endorsement;
use App\Models\Tag;
use Carbon\Carbon;
use Musonza\Chat\Traits\Messageable;
use Musonza\Chat\Models\MessageNotification;
use Chat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use DB;
use Laravel\Cashier\Billable;
use App\Models\VendorRanking;
use Rossjcooper\LaravelHubSpot\Facades\HubSpot;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

use GuzzleHttp\Psr7;
use App\Notifications\ResetPasswordNotification;

class Vendor extends Authenticatable
{    
    use Messageable, HasApiTokens, HasFactory, Notifiable, Billable;
    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
   protected $fillable = [
       'first_name',
       'last_name',
       "type",
       'email',
       'password',
       'image',
       'discount',
       'yearly_weddings',
       'business_name',
       'ref_by',
       'google_place_id',
       'location', 
       'service_radius',
       'avg_price'
   ];

    /**
        * The attributes that should be hidden for serialization.
        *
        * @var array<int, string>
        */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    protected static function boot(){
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    /**
     * Legacy vendors may lack a UUID; storefront links require one.
     */
    public function ensureUuid(): self
    {
        if (! empty($this->uuid)) {
            return $this;
        }

        $this->uuid = (string) Str::uuid();
        $this->save();

        return $this;
    }

    /**
     * Storefront expects a vendor profile row (portfolio, links, etc.).
     */
    public function ensureVendorProfile(): Profile
    {
        $profile = $this->profile;
        if ($profile !== null) {
            return $profile;
        }

        return Profile::create([
            'type' => 'vendor',
            'belongs_to' => $this->id,
        ]);
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $url = 'https://weddinginsidersnetwork.com/reset-password/'.$token;
    
        $this->sendEmail(185204647020, [
            'link' => $url
        ]);
    }

    public function sendEmail($emailId, $properties){
        if (App::environment('staging')) {
            return;
        }
        $handlerStack = \GuzzleHttp\HandlerStack::create();
        $handlerStack->push(
            \HubSpot\RetryMiddlewareFactory::createRateLimitMiddleware(
                \HubSpot\Delay::getConstantDelayFunction()
            )
        );

        $handlerStack->push(
            \HubSpot\RetryMiddlewareFactory::createInternalErrorsMiddleware(
                \HubSpot\Delay::getExponentialDelayFunction(2)
            )
        );

        $client = new \GuzzleHttp\Client(['handler' => $handlerStack]);

        $hubspot = \HubSpot\Factory::createWithAccessToken(\Config::get('hubspot.access_token'), $client);
        try {
            $response = $hubspot->apiRequest([
                'method' => 'POST',
                'path' => '/marketing/v3/transactional/single-email/send',
                'body' => [
                    'customProperties' => $properties,
                    'emailId' => $emailId,
                    'message' => [
                        'to' => $this->email,
                    ],
                ],
            ]);
            return $response;
        } catch(\GuzzleHttp\Exception\ClientException $e){
            return "error";
        }
        
    }



    public function vendor_ranking()
    {
        try {
            $rankingModel = $this->hasOne(VendorRanking::class)->first();
            if ($rankingModel == null) {
                $rankingModel = VendorRanking::create([
                    'vendor_id' => $this->id,
                ]);
            }

            return $rankingModel;
        } catch (\Throwable $e) {
            Log::warning('vendor_ranking fallback (schema or query failure)', [
                'vendor_id' => $this->id,
                'message' => $e->getMessage(),
            ]);

            return new VendorRanking([
                'vendor_id' => $this->id,
                'client_community' => 0,
                'vendor_community' => 0,
                'reviews' => 0,
                'endorsements' => 0,
                'badges' => 0,
            ]);
        }
    }

    public function userType(){
        return 'vendor';
    }

    public function meetings($lim = 100): HasMany {
        return $this->hasMany(Meeting::class, 'vendor');
    }

    public function endorsements($lim = 100): HasMany {
        return $this->hasMany(Endorsement::class, 'vendor_id');
    }

    public function topEndorsements(){
        return $this->hasMany(Endorsement::class, 'vendor_id')->select('type', DB::raw("count(type) as total_count"))->groupBy('type')->orderBy('total_count', 'DESC')->take(3)->get();
    }

    public function endorsementsFor($vendor): HasMany {
        return $this->hasMany(Endorsement::class, 'endorser')->where('vendor_id', $vendor->id);
    }

    public function isDateAvailable($date){
        $day = Carbon::parse($date)->format('Y-m-d');
        return $this->hasMany(Meeting::class, 'vendor')->where('date', $day)->count() == 0;
    }

    public function connections($lim = 100): HasManyThrough
    {
        return $this->hasManyThrough(
            Vendor::class,
            VendorConnection::class,
            'host_vendor', // Foreign key
            'id', // Foreign key 
            'id', // Local key 
            'aff_vendor' // Local key 
        )->where('approved', true)->limit($lim);
    }

    public function pendingConnectionsWhereHost(): HasManyThrough
    {
        return $this->hasManyThrough(
            Vendor::class,
            VendorConnection::class,
            'host_vendor', // Foreign key
            'id', // Foreign key
            'id', // Local key
            'id' // Local key 
        )->where('approved', false);
    }

    public function vendorPartnershipsCount(){
        return VendorConnection::where('approved', true)->where("aff_vendor", $this->id)->count();
    }

    public function getType(){
        return VendorTypes::where('id', $this->type)->first();
    }

    public function profile(){
        return $this->hasOne(Profile::class, 'belongs_to')->where('type', 'vendor');
    }

    public function numberOfClients(){
        return Pairing::where('vendor_id', $this->id)->where('status', 3)->count();
    }

    public function timesFavorited(){
        return Favorite::where('vendor_id', $this->id)->count();
    }

    //MESSAGES

    public function recentConversations($number){
        /*$notifs = MessageNotification::where([
            ['messageable_id', '=', $this->id],
            ['messageable_type', '=', 'App\Models\Vendor'],
            ['is_seen', '=', 0],
        ])->limit($number)->get();*/
        $recentConvos = Chat::conversations()->setPaginationParams(['sorting' => 'desc'])->setParticipant($this)->get()->filter(function ($value, $key) {
            return $value->conversation->last_message != null;
        })->take($number);
        return $recentConvos;
    }

    public function getAllConversations(){
        $convos = Chat::conversations()->setPaginationParams(['sorting' => 'desc'])
            ->setParticipant($this)
            ->page(1)
            ->get();
        return $convos;
    }

    public function getDirectMessagesWith($model){
        $conversation = Chat::conversations()->between($this, $model);
        return $conversation;
    }

    public function createDirectMessageWith($model){
        $participants = [$this, $model];
        $conversation = Chat::createConversation($participants)->makeDirect();
        return $conversation;
    }

    public function initiateDirectMessage($model){
        $conversation = Chat::conversations()->between($this, $model);
        if($conversation == null){
            $conversation = $this->createDirectMessageWith($model);
        }
        return $conversation->id;
    }

    public function sendMessage($msg, $convoID){
        $lastMSG = MessageNotification::where([
            ['conversation_id', '=', $convoID],
        ])->orderBy('message_id', 'desc')->take(2)->get();
        if($lastMSG != null){
            foreach($lastMSG as $message){
                if($message->messageable_id == $this->id && $message->is_sender == false){
                    $first = Carbon::parse($message->created_at)->subHours(12);
                    $second = Carbon::Now();
                    if($first->lessThan($second)){
                        $this->addResponse('fast');
                    } else{
                        $this->addResponse('slow');
                    }
                }
            }
        }
        $message = Chat::message($msg)
            ->from($this)
            ->to(Chat::conversations()->getById($convoID))
            ->send();
    }

    public function getMessages($convoID){
        return Chat::conversation(Chat::conversations()->getById($convoID))->setParticipant($this->setVisible(['id', 'first_name']))->getMessages();
    }

    public function getMessagesFromID($otherID, $userType){
        $otherUser = null;
        if($userType == 'vendor'){
            $otherUser = Vendor::where('uuid', $otherID)->first();
        } else if($userType == 'client'){
            $otherUser = User::where('uuid', $otherID)->first();
        } else {
            return null;
        }
        $conversation = Chat::conversations()->between($this, $otherUser);
        if($conversation == null){
            $conversation = $this->createDirectMessageWith($otherUser);
        }
        $conversation = $conversation->id;
        return ["id" => $conversation, "conversation" => Chat::conversation(Chat::conversations()->getById($conversation))->setParticipant($this->setVisible(['id', 'first_name']))->getMessages()];
    }

    public function getUnreadMessagesCount(){
        $notifs = MessageNotification::where([
            ['messageable_id', '=', $this->id],
            ['messageable_type', '=', 'App\Models\Vendor'],
            ['is_seen', '=', 0],
        ])->get();
        $data = [
            "client_notifs" => [],
            "vendor_notifs" => []
        ];
        foreach($notifs as $notif){
            array_push($data["vendor_notifs"], 1);
        }
        foreach($this->pendingConnectionsWhereAffiliate() as $pending_connection){
            array_push($data["vendor_notifs"], 1);
        }
        return $data;
    }

    public function unreadMessagesCount(){
        $notifs = MessageNotification::where([
            ['messageable_id', '=', $this->id],
            ['messageable_type', '=', 'App\Models\Vendor'],
            ['is_seen', '=', 0],
        ])->count();
        return $notifs;
    }

    //END MESSAGES

    public function getClients(){
        $data = [];        
        $pairs = Pairing::where('vendor_id', $this->id)->get();
        foreach($pairs as $pair){
            $user = User::where('id', $pair->client_id)->first();
            if($user){
                array_push($data, $user);
            }
        }
        return $data;
    }

    public function clients($max = 3){
        $data = [];        
        $pairs = Pairing::where('vendor_id', $this->id)->where('status', 3)->limit($max)->get();
        foreach($pairs as $pair){
            $user = User::where('id', $pair->client_id)->first();
            if($user){
                array_push($data, $user);
            }
        }
        return $data;
    }

    public function hasClient($id){
        $pair = Pairing::where('vendor_id', $this->id)->where('client_id', $id)->first();
        if($pair != null){
            return true;
        }
        return false;
    }

    public function isPendingWith($id){
        $pending = VendorConnection::where('host_vendor', $this->id)->where('aff_vendor', $id)->first();
        if($pending != null){
            return true;
        }
        return false;
    }

    public function pendingConnectionsWhereAffiliate(): HasManyThrough
    {
        //return $this->hasMany(VendorConnection::class, 'aff_vendor', 'id')->where('approved', false);
        return $this->hasManyThrough(
            Vendor::class,
            VendorConnection::class,
            'aff_vendor', // Foreign key
            'id', // Foreign key
            'id', // Local key
            'host_vendor' // Local key 
        )->where('approved', false);
    }

    public function hasRoomForType($type){
        $currentConnections = $this->connections();
        $count = 0;
        foreach($currentConnections as $connection){
            if($connection->type == $type){
                $count += 1;
            }
            if($count > 1){
                return false;
            }
        }
        return true;
    }

    public function isAffiliatedWith($id){
        $aff = VendorConnection::where('host_vendor', $id)->where('aff_vendor', $this->id)->where('approved', true)->first();
        if($aff != null){
            return true;
        }
        return false;
    }

    public function hasRequestFrom($id){
        $aff = VendorConnection::where('host_vendor', $id)->where('aff_vendor', $this->id)->where('approved', false)->first();
        if($aff != null){
            return true;
        }
        return false;
    }

    public function hasRequestFromClient($id){
        $aff = Pairing::where('client_id', $id)->where('vendor_id', $this->id)->where('approved', false)->first();
        if($aff != null){
            return $aff->id;
        }
        return null;
    }

    public function meetingsWith($id){
        return $this->hasMany(Meeting::class, 'vendor')->where('client', $id);
    }

    /*
    * VENDOR RANKINGS SECTION
    */

    public function calculateScore(){
        $ranks = $this->vendor_ranking();
        $score = ($ranks->client_community * .30) + ($ranks->reviews * .20) + ($ranks->vendor_community * .25) + ($ranks->endorsements * .15) + ($ranks->badges * .10);
        $this->score = $score;
        $this->save();
        return $score;
    }

    public function updateAllRankingScores(){
        $communityValue = $this->clientCommunityRankValue();
        $communityScore = ($communityValue['value'] / max((int) $communityValue['max'], 1)) * 100;
        $vendorCommunityValue = $this->vendorCommunityRankValue();
        $vcMax = max((int) $vendorCommunityValue['max'], 1);
        $vendorCommunityScore = ((($vendorCommunityValue['value'] / $vcMax) * 100) + (($this->quarterlyReferrals() / 3) * 100)) / 2;
        $reviewsScore = ($this->googleRating() / 5) * 100;
        $endorsementsScore = ($this->endorsementsScore() / 4) * 100;
        $badgesDecoded = json_decode($this->badges ?? '[]', true);
        $badgesScore = ((is_array($badgesDecoded) ? count($badgesDecoded) : 0) / 4) * 100;

        try {
            $rankingModel = VendorRanking::updateOrCreate(
                ['vendor_id' => $this->id],
                [
                    'client_community' => $communityScore,
                    'vendor_community' => $vendorCommunityScore,
                    'reviews' => $reviewsScore,
                    'endorsements' => $endorsementsScore,
                    'badges' => $badgesScore,
                ]
            );
            $score = ($rankingModel->client_community * .30) + ($rankingModel->reviews * .20) + ($rankingModel->vendor_community * .25) + ($rankingModel->endorsements * .15) + ($rankingModel->badges * .10);
        } catch (\Throwable $e) {
            Log::warning('VendorRanking updateOrCreate failed', [
                'vendor_id' => $this->id,
                'message' => $e->getMessage(),
            ]);
            $score = ($communityScore * .30) + ($reviewsScore * .20) + ($vendorCommunityScore * .25) + ($endorsementsScore * .15) + ($badgesScore * .10);
        }

        $this->score = $score;
        $this->save();

        return $score;
    }

    public function clientCommunityRankValue(){
        $currentClients = Pairing::where('vendor_id', $this->id)->where('main_connection', true)
            ->where('approved', true)->where('created_at', ">=", Carbon::now()->subMonths(1)->format('Y-m-d'))->count();
        $results = [
            "max" => 0,
            "value" => 0
        ];
        $uniqueTypes["max"] = 3;
        $uniqueTypes["value"] = max(0, min(3, $currentClients));
        return $uniqueTypes;
    }

    public function vendorCommunityRankValue(){
        $currentConnections = $this->connections();
        $uniqueTypes = [
            "max" => 0,
            "types" => [],
            "value" => 0
        ];
        foreach($currentConnections->get() as $connection){
            if(in_array($connection->type, $uniqueTypes["types"])){
                continue;
            }
            array_push($uniqueTypes["types"], $connection->type);
        }
        $uniqueTypes["max"] = VendorTypes::count();
        $uniqueTypes["value"] = count($uniqueTypes["types"]);
        return $uniqueTypes;
    }

    public function googleRating(){
        $profile = $this->profile;
        if ($profile === null || $profile->google_review_score === null) {
            return 0.0;
        }

        return (float) $profile->google_review_score;
    }

    public function getReviews(){
        return Review::where('vendor_id', $this->id)->get();
    }

    public function reviews(){
        return Review::where('vendor_id', $this->id);
    }

    public function quarterlyReferrals(){
        return Vendor::where('ref_by', $this->id)->where('created_at', ">=", Carbon::now()->subMonths(4)->format('Y-m-d'))->count();
    }

    public function vendorReferrals(){
        return Vendor::where('ref_by', $this->id)->count();
    }

    public function endorsementsScore(){
        return min(4, $this->endorsements()->distinct()->count('endorser'));
    }

    public function clientReferrals(){
        return Pairing::where('vendor_id', $this->id)->where('main_connection', true)
            ->where('approved', true)->where('created_at', ">=", Carbon::now()->subMonths(1)->format('Y-m-d'))->count();
    }
    /*
    * END VENDOR RANKINGS SECTION
    */

    /*
    * VENDOR PAYMENT SECTION
    */

    public function isSubscribed(){
        if($this->subscriptions()->active()->count() > 0){
            return true;
        }
        return false;
    }

    public function stripeSubscription(){
        try{
            return $this->subscription("WIN Subscription")->asStripeSubscription();
        } catch(\Throwable $e){
            return null;
        }
    }

    public function payment(){
        $payment = Payment::where('vendor_id', $this->id)->where('expiry_date', '>=', Carbon::now())->where('confirmed', true)->first();
        if($payment){
            return $payment;
        }
        return "ERR";
    }

    public function qualifiesForDiscount(){
        return false;    
    }

    /*
    * END PAYMENT SECTION
    */

    /*
    * VENDOR BADGES SECTION
    */

    public function addView(){
        $this->storefront_views = $this->storefront_views + 1;
        $this->save();
    }

    public function addResponse($type){
        if($type == 'fast'){
            $this->fast_responses = $this->fast_responses + 1;
        } else{
            $this->slow_responses = $this->slow_responses + 1;
        }
        $this->save();
    }

    public function badges(){
        $badges = Cache::remember('badges', 14400, function () {
            return Badge::all();
        });
        $earnedBadges = json_decode($this->badges ?? '[]', true);
        $earnedBadges = is_array($earnedBadges) ? $earnedBadges : [];
        $earnedBadgeModels = $badges->whereIn('id', $earnedBadges);

        return $earnedBadgeModels;
    }

    public function communityBuilderBadge(){
        $totalConnectionsCount = Cache::remember('vendor_connections_count', 120, function () {
            return VendorConnection::count();
        });
        $top_15_percent = max(1, ceil($totalConnectionsCount * 0.15));
        $connections = Cache::remember('last-3-months-connections-' . $this->type, 120, function () use ($top_15_percent) {
            return VendorConnection::where('approved', true)->where('aff_vendor_type', $this->type)
            ->select('aff_vendor', DB::raw('COUNT(*) AS cnt'))
            ->groupBy('aff_vendor')
            ->orderByRaw('COUNT(*) DESC')
            ->limit($top_15_percent)
            ->get();
        }); 
        foreach($connections->toArray() as $entry){
            if($entry["aff_vendor"] == $this->id){
                return true;
            }
        }
        return false;
    }

    public function trendingBadge(){
        $above = Vendor::where('storefront_views', '>', $this->storefront_views)->count();
        $totalCount = Cache::remember('vendors_count', 30, function () {
            return Vendor::count();
        });
        if ($totalCount === 0) {
            return false;
        }
        if ($above / $totalCount < 0.15) {
            return true;
        }
        return false;
    }

    public function earlyAdopterBadge(){
        $first = Carbon::parse($this->created_at)->subMonths(6);
        $second = Carbon::create(2024, 11, 29, 0, 0, 0, 'Europe/London');
        if($first->lte($second)){
            return true;
        }
        return false;
    }

    public function fastResponderBadge(){
        return $this->fast_responses > $this->slow_responses;
    }
    
    public function preferredPricing(){
        if($this->avg_price != null){
            switch($this->avg_price){
                case 2:
                    return "$500-$2,000";
                case 1:
                    return "<$500";
                case 3:
                    return "$2,000-$3,000";
                case 4:
                    return "$3,000-$5,000";
                case 5:
                    return "$5,000-$8,000";
                case 6:
                    return "$8,000-$10,000";
                case 7:
                    return "$10,000+";
                default:
                    return "<$500";
            }
        }
        switch($this->discount){
            case 50:
                return "$500-$2,000";
            case 50:
                return "<$500";
            case 75:
                return "$2,000-$3,000";
            case 100:
                return "$3,000-$5,000";
            case 150:
                return "$5,000-$8,000";
            case 200:
                return "$8,000-$10,000";
            case 250:
                return "$10,000+";
            default:
                return 0;
        }
    }

    public function preferredPricingEnum($value){
        if($value != null){
            switch($value){
                case "$500-$2,000":
                    return 2;
                case "<$500":
                    return 1;
                case "$2,000-$3,000":
                    return 3;
                case "$3,000-$5,000":
                    return 4;
                case "$5,000-$8,000":
                    return 5;
                case "$8,000-$10,000":
                    return 6;
                case "$10,000+":
                    return 7;
                default:
                    return 1; // Default to <$500 if not matched
            }
        }
        if($this->avg_price != null){
            switch($this->avg_price){
                case "$500-$2,000":
                    return 2;
                case "<$500":
                    return 1;
                case "$2,000-$3,000":
                    return 3;
                case "$3,000-$5,000":
                    return 4;
                case "$5,000-$8,000":
                    return 5;
                case "$8,000-$10,000":
                    return 6;
                case "$10,000+":
                    return 7;
                default:
                    return 1;
            }
        }
        switch($this->discount){
            case 50:
                return 2;
            case 50:
                return 1;
            case 75:
                return 3;
            case 100:
                return 4;
            case 150:
                return 5;
            case 200:
                return 6;
            case 250:
                return 7;
            default:
                return 1;
        }
    }

    public function upcomingMeetings() {
        return $this->hasMany(Meeting::class, 'vendor')->where('date', '>=', Carbon::now())->where('approved', 1)->where('type', '!=', 'manual')->orderBy('date');
    }

    public function unavailableDates() {
        return $this->hasMany(Meeting::class, 'vendor')->where('date', '>=', Carbon::now())->orderBy('date');
    }

    public function profileURL() {
        $this->ensureUuid();

        return '/vendor/profile/' . $this->uuid;
    }

    public function useContactCredit(){
        $this->decrement('contact_credits');
    }

    //TAGS
    public function tags() {
        return $this->hasMany(Tag::class, 'vendor_id');
    }
    public function tagValue($name) {
        return $this->hasMany(Tag::class, 'vendor_id')->where('name', $name)->first()->value ?? null;
    }

    public function events(){
        return $this->hasMany(Tag::class, 'vendor_id')->where('name', 'Event');
    }

    public function joinedEvents(){
        return $this->hasMany(Tag::class, 'vendor_id')->where('name', 'Event')->select('value');
    }

    //SCOPES
    public function scopeOrderByRank(Builder $query){
        return $query->orderBy('score', 'desc');
    }

    public function scopeWithTags(Builder $query, $filters){
        if(!$filters || !is_array($filters) || count($filters) == 0){
            return $query;
        }
        foreach($filters as $key => $value) {
            if($key == "Budget"){
                foreach (array_keys($value) as $budgetValue){
                    $value[$budgetValue] = $this->preferredPricingEnum($budgetValue); // This will convert the array of values to a simple array for whereIn
                }
                return $query->whereIn('avg_price', $value);
            }
            $allowedValue = TagType::where('name', $key)->first();
            if(!$allowedValue){
                continue;
            }
            if(!is_array($value)){
                $value = [$value];
            }
            $query = $query->whereHas('tags', function (Builder $query) use ($key, $value) {
                $value = array_keys($value);
                $query->whereIn('value', $value);
            });
        }
        return $query;
    }

    public function updateTag($tagName, $tagValue){
        $tagTypes = Cache::remember('tag_types', 14400, function () {
            return TagType::all();
        });
        if($tagTypes->where('name', $tagName)->first()->input_type == 'checkbox'){
            $deleteTags = $this->tags->where('name', $tagName)->whereNotIn('value', $tagValue)->each->delete();
            foreach($tagValue as $key => $value){
                Tag::updateOrCreate(['vendor_id' => $this->id, 'vendor_type_id' => $this->type, 'name' => $tagName, 'value' => $value]);
            }
        } else{
            Tag::updateOrCreate(['vendor_id' => $this->id, 'vendor_type_id' => $this->type, 'name' => $tagName], ['value' => $tagValue]);
        }
    }
}
