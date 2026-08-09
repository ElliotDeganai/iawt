<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'InAfrikaWeTrust')</title>
</head>
<body style="margin:0;padding:0;background-color:#f7f3ee;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f3ee;">
        <tr>
            <td align="center" style="padding:30px 20px;">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

                    {{-- HEADER --}}
                    <tr>
                        <td style="background-color:#3a0f17;border-radius:12px 12px 0 0;padding:28px 32px;text-align:center;">
                            @php
                                $logo = \App\Models\Setting::get('logo', '/images/logo-default.svg');
                                $logoUrl = str_starts_with($logo, 'http') ? $logo : url($logo);
                                $siteName = \App\Models\Setting::get('site_name', 'InAfrikaWeTrust');
                            @endphp
                            <img src="{{ $logoUrl }}" alt="{{ $siteName }}" width="48" height="48" style="width:48px;height:48px;border-radius:50%;object-fit:cover;margin-bottom:12px;" />
                            <div style="font-size:20px;font-weight:bold;color:#ffffff;font-family:Georgia,'Times New Roman',serif;">
                                {{ $siteName }}
                            </div>
                            <div style="font-size:11px;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:2px;margin-top:4px;">
                                Incubateur · Tourisme · Agriculture
                            </div>
                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="background-color:#ffffff;padding:32px;">
                            @yield('content')
                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="background-color:#4f151f;border-radius:0 0 12px 12px;padding:20px 32px;text-align:center;">
                            <p style="margin:0;font-size:12px;color:rgba(255,255,255,0.45);line-height:1.6;">
                                © {{ date('Y') }} {{ $siteName }} — Tous droits réservés.
                            </p>
                            <p style="margin:8px 0 0;font-size:11px;">
                                <a href="{{ url('/mentions-legales') }}" style="color:rgba(255,255,255,0.35);text-decoration:none;">Mentions légales</a>
                                &nbsp;·&nbsp;
                                <a href="{{ url('/politique-de-confidentialite') }}" style="color:rgba(255,255,255,0.35);text-decoration:none;">Confidentialité</a>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>