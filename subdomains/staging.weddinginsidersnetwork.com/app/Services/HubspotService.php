<?php

namespace App\Services;

use App\Models\Vendor;
use Rossjcooper\LaravelHubSpot\Facades\HubSpot;
use Illuminate\Support\Facades\App;

class HubspotService {
    public static function createVendor($vendor, $profile){
        if (App::environment('staging')) {
            return;
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
        if (App::environment('staging')) {
            return;
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
        $response = $hubspot->apiRequest([
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
        return $response;
    }
}