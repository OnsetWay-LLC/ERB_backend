<?php

namespace App\Modules\CentralDashboard\Services;

use App\Models\License;
use App\Models\LicenseLog;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendActivationTokenMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Models\LicenseRequest;
use App\Models\ClientInstallation;




class LicenseService
{
   public function generate(array $data): array
{
    $installation = ClientInstallation::findOrFail($data['client_installation_id']);

    if ((int) $installation->license_request_id !== (int) $data['license_request_id']) {
        throw ValidationException::withMessages([
            'client_installation_id' => ['This installation does not belong to the selected license request.'],
        ]);
    }

    $plainToken = $this->generateActivationToken();

    $license = License::create([
        'license_request_id'    => $data['license_request_id'],
        'client_installation_id'=> $data['client_installation_id'],
        'license_type'          => 'initial',
        'activation_token_hash' => Hash::make($plainToken),
        'activation_token_hint' => substr($plainToken, -3),
        'duration_type'         => $data['duration_type'],
        'token_expires_at'      => now()->addDays(7),
        'status'                => 'generated',
        'generated_by'          => $data['generated_by'] ?? null,
    ]);

    LicenseLog::create([
        'license_id' => $license->id,
        'action'     => 'generated',
        'note'       => 'Activation token generated successfully.',
        'created_by' => $data['generated_by'] ?? null,
        'created_at' => now(),
    ]);

    return [
        'license'     => $license->load(['licenseRequest', 'installation', 'generator']),
        'plain_token' => $plainToken,
    ];
}    private function generateActivationToken(): string
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $token = '';

        for ($i = 0; $i < 7; $i++) {
            $token .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $token;
    }
    public function sendToken(array $data): License
{
    $license = License::with('licenseRequest')->findOrFail($data['license_id']);

    Mail::to($license->licenseRequest->email)
        ->send(new SendActivationTokenMail($license, strtoupper(trim($data['activation_token']))));

    $license->update([
        'status'  => 'sent',
        'sent_at' => now(),
    ]);

    LicenseLog::create([
        'license_id' => $license->id,
        'action'     => 'sent',
        'note'       => 'Activation token sent successfully to client email.',
        'created_by' => null,
        'created_at' => now(),
    ]);

    $license->licenseRequest()->update([
        'status' => 'activation_sent',
    ]);

    return $license->fresh(['licenseRequest', 'generator']);
}
public function verifyToken(array $data): License
{
    $licenseRequest = LicenseRequest::where('email', $data['email'])->firstOrFail();

    $license = $licenseRequest->licenses()
        ->latest()
        ->first();

    if (! $license) {
        throw ValidationException::withMessages([
            'email' => ['No license found for this email.'],
        ]);
    }

    if ($license->used_at) {
        throw ValidationException::withMessages([
            'activation_token' => ['This activation token has already been used.'],
        ]);
    }

    if ($license->token_expires_at && now()->greaterThan($license->token_expires_at)) {
        $license->update([
            'status' => 'expired',
        ]);

        $licenseRequest->update([
            'status' => 'expired',
        ]);

        LicenseLog::create([
            'license_id' => $license->id,
            'action'     => 'expired',
            'note'       => 'Activation token expired before verification.',
            'created_by' => null,
            'created_at' => now(),
        ]);

        throw ValidationException::withMessages([
            'activation_token' => ['This activation token has expired.'],
        ]);
    }

    $isValid = Hash::check(
        strtoupper(trim($data['activation_token'])),
        $license->activation_token_hash
    );

    if (! $isValid) {
        throw ValidationException::withMessages([
            'activation_token' => ['Invalid activation token.'],
        ]);
    }

    $expiresAt = $license->duration_type === 'fourteen_days'
        ? now()->addDays(14)
        : now()->addYear();

    $license->update([
        'status'       => 'active',
        'activated_at' => now(),
        'used_at'      => now(),
        'starts_at'    => now(),
        'expires_at'   => $expiresAt,
    ]);

    $licenseRequest->update([
        'status' => 'activated',
    ]);

    LicenseLog::create([
        'license_id' => $license->id,
        'action'     => 'verified',
        'note'       => 'Activation token verified successfully.',
        'created_by' => null,
        'created_at' => now(),
    ]);

    return $license->fresh(['licenseRequest', 'generator']);
}
public function generateRenewal(array $data): array
{
    $installation = ClientInstallation::findOrFail($data['client_installation_id']);

    if ((int) $installation->license_request_id !== (int) $data['license_request_id']) {
        throw ValidationException::withMessages([
            'client_installation_id' => ['This installation does not belong to the selected license request.'],
        ]);
    }

    $latestLicense = License::where('client_installation_id', $data['client_installation_id'])
        ->latest('id')
        ->first();

    if (! $latestLicense) {
        throw ValidationException::withMessages([
            'client_installation_id' => ['No previous license found for this installation.'],
        ]);
    }

    // إذا الرخصة active، اسمحي بالتجديد فقط إذا بقي 60 يوم أو أقل
    if ($latestLicense->status === 'active' && $latestLicense->expires_at) {
        $daysRemaining = now()->diffInDays($latestLicense->expires_at, false);

        if ($daysRemaining > 60) {
            throw ValidationException::withMessages([
                'client_installation_id' => [
                    'Renewal is allowed only within the last 60 days before license expiry.'
                ],
            ]);
        }
    }

    $plainToken = $this->generateActivationToken();

    $license = License::create([
        'license_request_id'     => $data['license_request_id'],
        'client_installation_id' => $data['client_installation_id'],
        'license_type'           => 'renewal',
        'parent_license_id'      => $latestLicense->id,
        'activation_token_hash'  => Hash::make($plainToken),
        'activation_token_hint'  => substr($plainToken, -3),
        'duration_type'          => $data['duration_type'],
        'token_expires_at'       => now()->addDays(7),
        'status'                 => 'generated',
        'generated_by'           => $data['generated_by'] ?? null,
    ]);

    LicenseLog::create([
        'license_id' => $license->id,
        'action'     => 'generated',
        'note'       => 'Renewal activation token generated successfully.',
        'created_by' => $data['generated_by'] ?? null,
        'created_at' => now(),
    ]);

    return [
        'license' => $license->load(['licenseRequest', 'installation', 'generator', 'parentLicense']),
        'plain_token' => $plainToken,
    ];
}
}