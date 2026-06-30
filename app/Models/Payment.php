<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'vendor_id',
        'price',
        'purchase_date',
        'expiry_date',
        'confirmed'
    ];

    protected function stripeRequest()
    {
        return Http::withBasicAuth(config('services.stripe.secret'), '');
    }

    public function createPaymentAttempt(){
        $response = $this->stripeRequest()->asForm()->
            post('https://api.stripe.com/v1/payment_links', [
                'line_items' => [
                    [
                        'price' => config('services.stripe.payment_link_price'),
                        'quantity' => 1,
                    ],
                ],
                'after_completion' => [
                    'type' => 'redirect',
                    'redirect' => [
                        'url' => config('services.stripe.checkout_success_url')
                    ]
                ],
            ]);
        return $response->body();
    }

    public function sessionStatusPaid($session_id){
        $response = $this->stripeRequest()->
            get('https://api.stripe.com/v1/checkout/sessions/' . $session_id);
        $body = json_decode($response->body());
        if($body->payment_status == "paid"){
            return true;
        }
        return false;

    }
    
}
