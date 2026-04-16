<?php

namespace App\Console\Commands;

use App\Mail\LicenseRenewalReminderMail;
use App\Models\License;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;



class SendLicenseRenewalReminders extends Command
{
    protected $signature = 'licenses:send-renewal-reminders';

    protected $description = 'Send renewal reminder emails for licenses expiring within 60 days';

    public function handle(): int
{
    $licenses = License::with(['licenseRequest', 'installation'])
        ->where('status', 'active')
        ->whereNotNull('expires_at')
        ->whereNull('renewal_reminder_sent_at')
        ->where('expires_at', '<=', now()->addDays(60))
        ->where('expires_at', '>=', now())
        ->get();

    $this->info('Found licenses: ' . $licenses->count());

    foreach ($licenses as $license) {
        $email = $license->licenseRequest?->email;

        $this->info('Checking license ID: ' . $license->id);
        $this->info('Email: ' . ($email ?? 'NULL'));
        $this->info('Expires at: ' . $license->expires_at);

        if (! $email) {
            $this->warn('Skipped because email is missing.');
            continue;
        }

        $daysRemaining = now()->diffInDays($license->expires_at, false);

        try {
            Mail::to($email)->send(
                new LicenseRenewalReminderMail($license, $daysRemaining)
            );

            $license->update([
                'renewal_reminder_sent_at' => now(),
            ]);

            $this->info("Mail sent to: {$email}");
        } catch (\Throwable $e) {
            $this->error("Failed to send to {$email}: " . $e->getMessage());
        }
    }

    $this->info('Done.');

    return self::SUCCESS;
}
}