<?php

namespace App\Mail;

use App\Models\License;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendActivationTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public License $license,
        public string $activationToken
    ) {}

    public function build(): self
    {
        return $this->subject('Your Activation Token')
            ->view('emails.activation-token');
    }
}