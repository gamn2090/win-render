<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rossjcooper\LaravelHubSpot\Facades\HubSpot;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emailId;
    protected $recipient;
    protected $properties;

    /**
     * Create a new job instance.
     */
    public function __construct($emailId, $recipient, $properties)
    {
        $this->emailId = $emailId;
        $this->recipient = $recipient;
        $this->properties = $properties;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (! \App\Services\HubspotService::integrationsEnabled()) {
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
                'customProperties' => $this->properties,
                'emailId' => $this->emailId,
                'message' => [
                    'to' => $this->recipient,
                ],
            ],
        ]);
    }
}
