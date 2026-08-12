<?php

namespace App\Jobs;

use App\Services\KlaviyoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TrackKlaviyoEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $metric;
    protected $email;
    protected $properties;
    protected $profileProperties;

    /**
     * Create a new job instance.
     */
    public function __construct($metric, $email, $properties = [], $profileProperties = [])
    {
        $this->metric = $metric;
        $this->email = $email;
        $this->properties = $properties;
        $this->profileProperties = $profileProperties;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (! KlaviyoService::integrationsEnabled()) {
            return;
        }

        KlaviyoService::track($this->metric, $this->email, $this->properties, $this->profileProperties);
    }
}
