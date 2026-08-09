@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display:inline-block;">
@php
    $logo = \App\Models\Setting::get('logo', '/images/logo-default.svg');
    $logoUrl = str_starts_with($logo, 'http') ? $logo : url($logo);
@endphp
<img src="{{ $logoUrl }}" alt="" width="36" height="36" style="width:36px;height:36px;border-radius:50%;object-fit:cover;vertical-align:middle;margin-right:8px;">
{{ $slot }}
</a>
</td>
</tr>