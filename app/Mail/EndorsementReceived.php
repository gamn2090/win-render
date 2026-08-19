<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EndorsementReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private readonly string $vendorName,
        private readonly string $endorserName,
        private readonly array $typeNames,
    ) {}

    public function build(): self
    {
        return $this
            ->subject('Congratulations! ' . $this->endorserName . ' endorsed you on WIN')
            ->view('emails.endorsement-received', [
                'vendorName' => $this->vendorName,
                'endorserName' => $this->endorserName,
                'typeNames' => $this->typeNames,
            ]);
    }
}
