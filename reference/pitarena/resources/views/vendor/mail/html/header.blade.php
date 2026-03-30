@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
{{-- @if (trim($slot) === 'PIT ARENA') --}}
<img src="https://pitarena.cz/images/logo/pitarena_logo_grey_74x74.png" class="logo" alt="Pitarena Logo">
{{-- @else --}}
{{ ' - ' . $slot }}
{{-- @endif --}}
</a>
</td>
</tr>
