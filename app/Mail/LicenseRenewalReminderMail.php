<?php

namespace App\Mail;

use App\Models\License;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LicenseRenewalReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public License $license,
        public int $daysRemaining
    ) {}

    public function build(): self
    {
        return $this->subject('Subscription Renewal Reminder')
            ->view('emails.license-renewal-reminder');
    }
}