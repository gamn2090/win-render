<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TimelineShared extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private readonly string $coupleName,
        private readonly string $vendorName,
        private readonly string $pdfBytes,
        private readonly string $fileName,
    ) {}

    public function build(): self
    {
        return $this
            ->subject($this->coupleName . ' shared their wedding timeline with you')
            ->view('emails.timeline-shared', [
                'coupleName' => $this->coupleName,
                'vendorName' => $this->vendorName,
            ])
            ->attachData($this->pdfBytes, $this->fileName, [
                'mime' => 'application/pdf',
            ]);
    }
}
