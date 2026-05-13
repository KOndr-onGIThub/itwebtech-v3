{{--
    Booking widget — Reservanto
    ───────────────────────────
    Per OND-102 (A3) / OND-116 (T15): sekundární CTA „Domluvit konzultaci".
    Konfigurace: config/site.php → 'booking' (env SITE_BOOKING_*).

    Skript je načítaný jednou v resources/views/layouts/app.blade.php.
    Reservanto JS najde všechny .reservanto-widget elementy a vyrenderuje
    klikatelný button. Po kliknutí otevře booking flow v česky.

    Použití:
        <x-booking.reservanto-widget />
        <x-booking.reservanto-widget ctaText="Domluvit konzultaci" />
--}}
@props([
    'ctaText' => null,
    // Analytics event jméno (OND-122). Per kontextu volajícího:
    //   hero      → 'hero_cta_secondary_click'
    //   final_cta → 'final_cta_secondary_click'
    // Atribut se přidá na wrapping div; klik na vykreslený button bubble-uje.
    'analyticsEvent' => null,
])
@php($booking = config('site.booking'))
@if ($booking['enabled'] ?? false)
    <div
        @if ($analyticsEvent) data-analytics="{{ $analyticsEvent }}" @endif
        class="reservanto-widget"
        data-text="{{ $ctaText ?? $booking['cta_text'] }}"
        data-id="{{ $booking['widget_id'] }}"
        data-resourceid="{{ $booking['resource_id'] }}"
        data-color-text="#313131"
        data-color-text-shadow="transparent"
        data-color-bg="#ffcc00"
        data-color-bg-hover="#ffdf00"
        data-color-boxshadow="#c29b00"></div>
@endif
