<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Vendor;
use App\Models\Pairing;
use App\Models\Inquiry;
use App\Models\VendorTypes;
use Musonza\Chat\Traits\Messageable;
use Musonza\Chat\Models\MessageNotification;
use Chat;
use App\Models\Favorite;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Database\Factories\VendorFactory;
use DB;

class User extends Authenticatable
{
    use Messageable, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'wedding_date',
        'wedding_location',
        'fiance_first_name',
        'image',
        'fiance_last_name',
        'in_network',
        'bio',
        'questions',
        'booking_date',
        'phone',
        'ref_source',
        'event'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function boot(){
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function isInNetwork(){
        if($this->in_network == 1){
            return true;
        }   
        $pair = $this->pairing->where('approved', true)->where('main_connection', true)->count();
        if($pair > 0){
            return true;
        }
        return false;
    }

    public function userType(){
        return 'client';
    }

    public function pairing() : HasMany {
        return $this->hasMany(Pairing::class, 'client_id');
    }

    public function inquiries() : HasMany {
        return $this->hasMany(Inquiry::class, 'user_id');
    }

    public function vendors(){
        $data = [];
        $pairs = $this->pairing->pluck('vendor_id')->toArray();
        $data = Vendor::whereIn('id', $pairs)->get();
        return $data;
    }

    public function vendorsWithStatus(){
        return $this->pairing->load('vendor');
    }

    public function statusWith($id){
        $pair = $this->pairing->where('vendor_id', $id)->first();
        if($pair){
            return $pair->status;
        }
        return 0;
    }

    public function bookedVendors(){
        return $this->hasManyThrough(
            Vendor::class,
            Pairing::class,
            'vendor_id', // Foreign key on the environments table...
            'id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'client_id' // Local key on the environments table...
        );
    }

    public function daysUntilWedding(){
        $end = Carbon::parse($this->wedding_date);
        $now = Carbon::now();
        return $now->diffInDays($end);
    }

    public function vendorsCount(){
        return $this->pairing->where('approved', true)->count();
    }

    public function bookedVendorsCount(){
        return $this->pairing->where('approved', true)->where('status', 3)->count();
    }

    public function moneySaved($vendors){
        $sum = 0;
        foreach($vendors as $key=>$value){
            if(isset($value->discount) && $this->isDiscountEligible($value->id))
                $sum += $value->discount;
            }
        return $sum;
    }

    public function getMainVendor(){
        $pair = $this->pairing->where('main_connection', true)->first();
        if(!$pair){
            return null;
        }
        return Vendor::where('id', $pair->vendor_id)->first();
    }

    public function isAssociatedWith($vendorId){
        $pair = $this->pairing->where('vendor_id', $vendorId)->first();
        if(!$pair){
            return false;
        }
        return true;
    }

    public function pairingWith($vendorId){
        return $this->pairing->where('vendor_id', $vendorId)->first();
    }

    public function isDiscountEligible($vendorId){
        //$pair = $this->pairing->where('vendor_id', $vendorId)->first();
        //if(!$pair || $pair->discount_eligible == false){
        //    return false;
        //}
        return true;
    }

    public function hasMainConnection(){
        $pair = $this->pairing->where('main_connection', true)->first();
        if(!$pair){
            return false;
        }
        return true;
    }

    public function mainConnection(){
        $pair = $this->pairing->where('main_connection', true)->first();
        if(!$pair){
            return null;
        }
        return $pair;
    }

    public function profile(){
        $profile = $this->hasOne(Profile::class, 'belongs_to')->where('type', 'client')->exists();
        if(!$profile){
            $profile = Profile::create(['type' => 'client', 'belongs_to' => $this->id]);
        }
        return $this->hasOne(Profile::class, 'belongs_to')->where('type', 'client');
    }

    public function isLookingForVendorType($type){
        $pair = Inquiry::where('user_id', $this->id)->where('vendor_type', $type)->first();
        if(!$pair){
            return false;
        }
        return true;
    }

    public function getRequestedVendorTypes(){
        return $this->inquiries->pluck('vendor_type');
    }

    public function requestedVendorTypes(){
        return $this->hasManyThrough(
            VendorTypes::class,
            Inquiry::class,
            'user_id', // Foreign key on the environments table...
            'id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'vendor_type' // Local key on the environments table...
        );
    }

    public function requestedVendorProgress(){
        return $this->pairing->select('vendor_type', 'status');
    }

    public function getRequestedVendorCount(){
        return $this->inquiries->count();
    }

    public function getRequestedVendorTypeModels(){
        return $this->inquiries;
    }

    //MESSAGES

    public function getAllConversations(){
        $convos = Chat::conversations()->setPaginationParams(['sorting' => 'desc'])
            ->setParticipant($this)
            ->page(1)
            ->get();
        return $convos;
    }

    public function recentConversations($number){
        /*$notifs = MessageNotification::where([
            ['messageable_id', '=', $this->id],
            ['messageable_type', '=', 'App\Models\Vendor'],
            ['is_seen', '=', 0],
        ])->limit($number)->get();*/
        return Chat::conversations()->setPaginationParams(['sorting' => 'desc'])->setParticipant($this)->limit($number)->get();
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
    //TODO: replace with directMessageWith
    public function initiateDirectMessage($model){
        $conversation = Chat::conversations()->between($this, $model);
        if($conversation == null){
            $conversation = $this->createDirectMessageWith($model);
        }
        return $conversation->id;
    }

    public function directMessageWith($model){
        $conversation = Chat::conversations()->between($this, $model);
        if($conversation == null){
            $conversation = $this->createDirectMessageWith($model);
        }
        return $conversation;
    }

    public function getMessages($convoID){
        return Chat::conversation(Chat::conversations()->getById($convoID))->setParticipant($this)->getMessages();
    }

    public function sendMessage($msg, $convoID){
        $message = Chat::message($msg)
            ->from($this)
            ->to(Chat::conversations()->getById($convoID))
            ->send();
    }

    public function getUnreadMessagesCount(){
        $notifs = MessageNotification::where([
            ['messageable_id', '=', $this->id],
            ['messageable_type', '=', 'App\Models\User'],
            ['is_seen', '=', 0],
        ])->get();
        $data = [
            "vendor_notifs" => []
        ];
        foreach($notifs as $notif){
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

    public function hasFavorite($id){
        $fav = Favorite::where('vendor_id', $id)->where('user_id', $this->id)->first();
        if($fav){
            return true;
        }
        return false;
    }

    public function favorites(){
        $fav = Favorite::where('user_id', $this->id)->get();
        return $fav;
    }

    public function favoritedVendors(){
        return $this->hasManyThrough(
            Vendor::class,
            Favorite::class,
            'user_id', // Foreign key on the environments table...
            'id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'vendor_id' // Local key on the environments table...
        );
    }

    //meetings
    
    public function meetings() {
        return $this->hasMany(Meeting::class, 'client');
    }

    public function upcomingMeetings() {
        return $this->hasMany(Meeting::class, 'client')->where('date', '>=', Carbon::now())->where('approved', 1)->where('type', '!=', 'wedding')->orderBy('date');
    }

    public function profileURL() {
        return '/client/profile/' . $this->uuid;
    }
}
