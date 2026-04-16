<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activation Token</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, Helvetica, sans-serif; background-color:#bdd8e9;">
    <div style="max-width:600px; margin:30px auto; background:#ffffff; border-radius:16px; overflow:hidden; border:1px solid #7bbde8;">

        <!-- Header -->
        <div style="background:#001d39; padding:30px 24px; text-align:center;">
            <span style="font-size:22px; font-weight:bold; color:#bdd8e9; letter-spacing:3px;">ONSET WAY</span>
            <p style="margin:6px 0 0; font-size:12px; color:#6ea2b3; letter-spacing:2px; text-transform:uppercase;">ERP System Activation</p>
        </div>

        <!-- Accent bar -->
        <div style="height:4px; background:linear-gradient(90deg,#001d39,#0a4174,#49769f,#4e8ea2,#7bbde8);"></div>

        <!-- Body -->
        <div style="padding:36px 32px;">
            <p style="font-size:16px; color:#001d39; margin-bottom:10px;">
                Hello <strong style="color:#0a4174;">{{ $license->licenseRequest->owner_name ?? 'Client' }}</strong>,
            </p>

            <p style="font-size:14px; color:#49769f; line-height:1.8; margin-bottom:28px;">
                Your activation token has been generated successfully.
                Please use the following token to activate your ERP system.
            </p>

            <!-- Token -->
            <div style="text-align:center; margin:28px 0;">
                <p style="font-size:11px; color:#6ea2b3; letter-spacing:2px; text-transform:uppercase; margin-bottom:12px; font-weight:600;">Activation Token</p>
                <div style="display:inline-block; font-size:26px; letter-spacing:8px; font-weight:bold; background:#bdd8e9; color:#001d39; padding:18px 32px; border-radius:12px; border:2px dashed #7bbde8;">
                    {{ $activationToken }}
                </div>
            </div>

            <div style="border-top:1px solid #bdd8e9; margin:28px 0;"></div>

            <!-- Info rows -->
          <!-- Info rows -->
            <!-- Info rows -->
            <div style="display:flex; flex-direction:column; gap:24px; margin-top: 24px;">
                <!-- الصف الأول: المدة -->
                <div style="display:flex; justify-content:space-between; align-items:center; background:#f0f7fb; border-radius:10px; padding:13px 18px; border:1px solid #bdd8e9;">
                    <span style="font-size:13px; color:#49769f;">Subscription Duration</span>
                    <strong style="font-size:13px; color:#0a4174;">
                        {{ $license->duration_type === 'fourteen_days' ? '14 Days' : '1 Year' }}
                    </strong>
                </div>

                <!-- الصف الثاني: انتهاء التوكن -->
                <div style="display:flex; justify-content:space-between; align-items:center; background:#f0f7fb; border-radius:10px; padding:13px 18px; border:1px solid #bdd8e9;margin-left:38px;">
                    <span style="font-size:13px; color:#49769f;">Token Expiry</span>
                    <strong style="font-size:13px; color:#0a4174;">
                        {{ optional($license->token_expires_at)->format('Y-m-d h:i A') }}
                    </strong>
                </div>
            </div>

            <!-- Warning -->
            <div style="margin-top:24px; background:#eaf3f8; border-radius:10px; border-left:4px solid #4e8ea2; padding:14px 18px; display:flex; gap:10px; align-items:flex-start;">
                <p style="font-size:13px; color:#0a4174; line-height:1.7; margin:0;">
                    ⚠️ Please keep this token private. Do not share it with anyone.
                </p>
            </div>
        </div>

       <!-- Footer -->
<div style="background:#001d39; padding:28px 24px; text-align:center; border-top:2px solid #0a4174; font-family: Arial, Helvetica, sans-serif;">

    <!-- Logo + Name -->
    <div style="display:flex; align-items:center; justify-content:center; gap:10px; margin-bottom:10px;">
       
    </div>

    <!-- Divider -->
    <div style="width:60px; height:1.5px; background:linear-gradient(90deg,transparent,#4e8ea2,transparent); margin:0 auto 12px;"></div>

    <!-- Copyright -->
    <p style="margin:0; font-size:12px; color:#6ea2b3; letter-spacing:1px;">
        © 2026 by <span style="color:#7bbde8; font-weight:bold;">Onset Way L.L.C</span> — All rights reserved.
    </p>

</div>
    </div>
</body>
</html>