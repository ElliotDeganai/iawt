<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nouvelle inscription</title>
</head>
<body style="margin:0;padding:0;background-color:#f7f3ee;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;-webkit-font-smoothing:antialiased;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f3ee;">
<tr><td style="padding:32px 16px;" align="center">
<table role="presentation" cellpadding="0" cellspacing="0" style="max-width:560px;width:100%;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.08);">

    {{-- ═══ HEADER ═══ --}}
    <tr>
        <td style="background-color:#3a0f17;padding:28px 32px;text-align:center;">
            <img src="{{ $logoUrl }}" alt="{{ $siteName }}" width="44" height="44" style="width:44px;height:44px;border-radius:50%;object-fit:cover;display:inline-block;vertical-align:middle;">
            <span style="display:inline-block;vertical-align:middle;margin-left:10px;font-family:Georgia,'Times New Roman',serif;font-size:20px;font-weight:bold;color:#ffffff;">{{ $siteName }}</span>
        </td>
    </tr>

    {{-- ═══ BODY ═══ --}}
    <tr>
        <td style="background-color:#ffffff;padding:32px;">

            <p style="margin:0 0 6px;font-family:Georgia,'Times New Roman',serif;font-size:22px;color:#3a0f17;">
                Nouvelle inscription
            </p>
            <p style="margin:0 0 24px;font-size:14px;color:#6b7280;line-height:1.5;">
                Bonjour {{ $admin->first_name }}, un nouvel utilisateur vient de créer un compte.
            </p>

            {{-- User card --}}
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;">
            <tr><td style="padding:20px;">
                <table role="presentation" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="48" valign="top">
                        <div style="width:44px;height:44px;border-radius:50%;background-color:#7a1f2b;color:#ffffff;font-size:15px;font-weight:bold;text-align:center;line-height:44px;font-family:Arial,sans-serif;">{{ strtoupper(mb_substr($newUser->first_name,0,1) . mb_substr($newUser->last_name,0,1)) }}</div>
                    </td>
                    <td style="padding-left:14px;vertical-align:top;">
                        <p style="margin:0;font-size:16px;font-weight:600;color:#1f2937;">{{ $newUser->first_name }} {{ $newUser->last_name }}</p>
                        <p style="margin:3px 0 0;font-size:13px;color:#6b7280;">{{ $newUser->email }}</p>
                        <p style="margin:3px 0 0;font-size:12px;color:#9ca3af;">Inscrit le {{ $newUser->created_at->format('d/m/Y à H:i') }}</p>
                    </td>
                </tr>
                </table>
            </td></tr>
            </table>

            {{-- CTA --}}
            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:28px auto 0;">
            <tr><td>
                <a href="{{ url('/admin/users') }}" style="display:inline-block;padding:12px 32px;background-color:#7a1f2b;text-decoration:none;border-radius:50px;"><span style="color:#ffffff;font-size:14px;font-weight:600;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">Voir les utilisateurs &rarr;</span></a>
            </td></tr>
            </table>

            <p style="margin:24px 0 0;font-size:12px;color:#9ca3af;line-height:1.5;text-align:center;">
                Vous recevez cet e-mail car vous êtes administrateur de la plateforme.
            </p>
        </td>
    </tr>

    {{-- ═══ FOOTER ═══ --}}
    <tr>
        <td style="background-color:#4f151f;padding:20px 32px;text-align:center;">
            <p style="margin:0;font-size:12px;color:rgba(255,255,255,0.4);line-height:1.6;">© {{ date('Y') }} {{ $siteName }} — Tous droits réservés.</p>
            <p style="margin:6px 0 0;font-size:11px;">
                <a href="{{ url('/mentions-legales') }}" style="color:rgba(255,255,255,0.3);text-decoration:none;">Mentions légales</a>
                <span style="color:rgba(255,255,255,0.2);margin:0 4px;">·</span>
                <a href="{{ url('/politique-de-confidentialite') }}" style="color:rgba(255,255,255,0.3);text-decoration:none;">Confidentialité</a>
            </p>
        </td>
    </tr>

</table>
</td></tr>
</table>

</body>
</html>