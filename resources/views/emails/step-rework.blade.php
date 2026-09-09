<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0;padding:0;background-color:#f7f3ee;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f3ee;">
<tr><td style="padding:32px 16px;" align="center">
<table role="presentation" cellpadding="0" cellspacing="0" style="max-width:560px;width:100%;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.08);">
    <tr><td style="background-color:#3a0f17;padding:28px 32px;text-align:center;">
        <img src="{{ $logoUrl }}" alt="{{ $siteName }}" width="44" height="44" style="width:44px;height:44px;border-radius:50%;object-fit:cover;display:inline-block;vertical-align:middle;">
        <span style="display:inline-block;vertical-align:middle;margin-left:10px;font-family:Georgia,'Times New Roman',serif;font-size:20px;font-weight:bold;color:#ffffff;">{{ $siteName }}</span>
    </td></tr>
    <tr><td style="background-color:#ffffff;padding:32px;">
        <p style="margin:0 0 6px;font-family:Georgia,'Times New Roman',serif;font-size:22px;color:#3a0f17;">Étape {{ $step }} à retravailler</p>
        <p style="margin:0 0 20px;font-size:14px;color:#6b7280;line-height:1.5;">Bonjour {{ $user->first_name }},</p>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#fef3c7;border:1px solid #fde68a;border-radius:10px;">
        <tr><td style="padding:16px 20px;">
            <p style="margin:0;font-size:14px;color:#92400e;font-weight:600;">Votre accompagnateur vous demande de retravailler l'étape {{ $step }}.</p>
            <p style="margin:10px 0 0;font-size:13px;color:#78350f;line-height:1.5;">Motif : {{ $reason }}</p>
        </td></tr>
        </table>
        <p style="margin:20px 0 0;font-size:13px;color:#374151;line-height:1.5;">Connectez-vous à votre tableau de bord pour modifier et soumettre à nouveau cette étape.</p>
        <table role="presentation" cellpadding="0" cellspacing="0" style="margin:24px auto 0;">
        <tr><td><a href="{{ $dashUrl }}" style="display:inline-block;padding:12px 32px;background-color:#7a1f2b;text-decoration:none;border-radius:50px;"><span style="color:#ffffff;font-size:14px;font-weight:600;">Retravailler l'étape &rarr;</span></a></td></tr>
        </table>
    </td></tr>
    <tr><td style="background-color:#4f151f;padding:20px 32px;text-align:center;"><p style="margin:0;font-size:12px;color:rgba(255,255,255,0.4);">© {{ date('Y') }} {{ $siteName }}</p></td></tr>
</table>
</td></tr>
</table>
</body>
</html>
