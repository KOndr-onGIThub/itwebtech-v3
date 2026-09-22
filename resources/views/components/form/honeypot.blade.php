@props(['id' => 'website-url'])

{{--
    OND-280: Past na spamboty. Viz app/Support/Honeypot.php.

    Pole je pro člověka neviditelné a nedosažitelné — mimo obrazovku, mimo
    tabulátor (`tabindex="-1"`), skryté pro čtečky obrazovky (`aria-hidden`).
    Kdo ho vyplní, je program.

    Schválně to NENÍ `display:none` ani `type="hidden"` — obojí boti poznají
    a pole přeskočí. Styl je zapsaný přímo tady, ne ve stylopisu, aby past
    fungovala i ve chvíli, kdy se styl webu nenačte.
--}}
<div aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden">
    <label for="{{ $id }}">Toto pole nevyplňujte</label>
    <input
        type="text"
        id="{{ $id }}"
        name="{{ \App\Support\Honeypot::FIELD }}"
        value=""
        tabindex="-1"
        autocomplete="off"
    >
</div>
