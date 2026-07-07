<?php

namespace App\Services;

use App\Models\Vendor;
use Rossjcooper\LaravelHubSpot\Facades\HubSpot;
use Illuminate\Support\Facades\App;

class HubspotService {
    /**
     * HubSpot emails/API should not block registration when token is missing (e.g. Render dev).
     */
    public static function integrationsEnabled(): bool
    {
        if (App::environment(['local', 'staging'])) {
            return false;
        }

        return filled(config('hubspot.access_token'));
    }

    public static function createVendor($vendor, $profile){
        if (! static::integrationsEnabled()) {
            return null;
        }
        try{

            $contactInput = new \HubSpot\Client\Crm\Contacts\Model\SimplePublicObjectInputForCreate();
            $contactInput->setProperties([
                'email' => $vendor->email,
                'type' => 'vendor',
                'vendor_type' => $vendor->getType()->type,
                'company' => $vendor->business_name,
                'firstname' => $vendor->first_name,
                'lastname' => $vendor->last_name,
                'website' => $profile->business_link,
                'instagram' => $profile->instagram_link
            ]);
            
            $contact = Hubspot::crm()->contacts()->basicApi()->create($contactInput);
        } catch(\Exception $e){
            return $e;
        }
    }

    public static function createCouple($couple){
        if (! static::integrationsEnabled()) {
            return null;
        }
        try{

            $contactInput = new \HubSpot\Client\Crm\Contacts\Model\SimplePublicObjectInputForCreate();
            $contactInput->setProperties([
                'email' => $couple->email,
                'type' => 'couple',
                'firstname' => $couple->first_name,
                'lastname' => $couple->last_name,
                'wedding_date' => $couple->wedding_date
            ]);
            
            $contact = Hubspot::crm()->contacts()->basicApi()->create($contactInput);
        } catch(\Exception $e){
            return $e;
        }
    }

    public static function sendEmail($emailID, $properties, $to){
        if (! static::integrationsEnabled()) {
            return null;
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
            return $hubspot->apiRequest([
                'method' => 'POST',
                'path' => '/marketing/v3/transactional/single-email/send',
                'body' => [
                    'customProperties' => $properties,
                    'emailId' => $emailID,
                    'message' => [
                        'to' => $to,
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('HubspotService::sendEmail failed', [
                'emailID' => $emailID,
                'to' => $to,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }
}