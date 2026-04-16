<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscription Renewal Reminder</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, Helvetica, sans-serif; background-color:#bdd8e9;">
    <div style="max-width:600px; margin:30px auto; background:#ffffff; border-radius:16px; overflow:hidden; border:1px solid #7bbde8;">

        <!-- Header -->
       <!-- Header -->
<div style="background:#001d39; padding:38px 32px 30px; text-align:center;">

    <!-- Dots decoration -->
    <div style="display:flex; justify-content:center; gap:6px; margin-bottom:20px;">
        <div style="width:6px; height:6px; border-radius:50%; background:#0a4174;"></div>
        <div style="width:6px; height:6px; border-radius:50%; background:#49769f;"></div>
        <div style="width:6px; height:6px; border-radius:50%; background:#6ea2b3;"></div>
        <div style="width:6px; height:6px; border-radius:50%; background:#7bbde8;"></div>
        <div style="width:6px; height:6px; border-radius:50%; background:#bdd8e9;"></div>
    </div>

    <!-- Icon -->
    <div style="width:56px; height:56px; margin:0 auto 16px; background:rgba(123,189,232,0.1); border:1.5px solid #4e8ea2; border-radius:14px; display:flex; align-items:center; justify-content:center;">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" stroke="#7bbde8" stroke-width="1.5" stroke-linejoin="round"/>
        </svg>
    </div>

    <!-- Name -->
    <div style="margin-bottom:6px;">
        <span style="font-size:26px; font-weight:bold; color:#bdd8e9; letter-spacing:4px;">ONSET WAY</span>
    </div>

    <!-- Divider -->
    <div style="width:48px; height:1.5px; background:#4e8ea2; margin:10px auto 12px;"></div>

    <!-- Subtitle pill -->
    <span style="display:inline-block; font-size:11px; color:#6ea2b3; letter-spacing:2.5px; text-transform:uppercase; background:rgba(78,142,162,0.12); padding:5px 16px; border-radius:20px; border:1px solid #0a4174;">
        ERP Subscription Reminder
    </span>
</div>

        <!-- Accent bar -->
        <div style="height:4px; background:linear-gradient(90deg,#001d39,#0a4174,#49769f,#4e8ea2,#7bbde8,#bdd8e9);"></div>

        <!-- Body -->
        <div style="padding:36px 32px;">
            <p style="font-size:16px; color:#001d39; margin-bottom:20px;">
                Hello <strong style="color:#0a4174;">{{ $license->licenseRequest->owner_name ?? 'Client' }}</strong>,
            </p>

            <p style="font-size:15px; color:#49769f; line-height:1.8; margin-bottom:20px;">
                Your ERP subscription will expire in
                <strong style="color:#001d39;">{{ $daysRemaining }} day(s)</strong>.
                Please renew your subscription to avoid any service interruption.
            </p>

            <!-- Expiry date -->
            <div style="display:flex; justify-content:space-between; align-items:center; background:#f0f7fb; border-radius:10px; padding:14px 18px; border:1px solid #bdd8e9; margin-bottom:20px;">
                <span style="font-size:13px; color:#49769f;">Expiry Date</span>
                <strong style="font-size:13px; color:#0a4174;">
                    {{ optional($license->expires_at)->format('Y-m-d h:i A') }}
                </strong>
            </div>

            <!-- Warning -->
            <div style="background:#eaf3f8; border-radius:10px; border-left:4px solid #4e8ea2; padding:14px 18px; display:flex; gap:10px; align-items:flex-start;">
                <div style="width:20px; height:20px; min-width:20px; border-radius:50%; background:#0a4174; display:flex; align-items:center; justify-content:center; margin-top:1px;">
                    <span style="color:#bdd8e9; font-size:12px; font-weight:bold;">!</span>
                </div>
                <p style="font-size:13px; color:#0a4174; line-height:1.7; margin:0;">
                    If you do not plan to renew, please make a backup of your data before the system stops working.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div style="background:#001d39; padding:22px 24px; text-align:center; border-top:2px solid #0a4174;">
            <div style="width:60px; height:1px; background:linear-gradient(90deg,transparent,#4e8ea2,transparent); margin:0 auto 14px;"></div>
            <p style="margin:0; font-size:12px; color:#6ea2b3; letter-spacing:1px;">
                © 2026 by <span style="color:#7bbde8; font-weight:bold;">Onset Way L.L.C</span> — All rights reserved.
            </p>
        </div>

    </div>
</body>
</html>