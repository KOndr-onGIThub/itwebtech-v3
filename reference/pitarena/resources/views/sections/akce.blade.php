{{--
  Popup: Akce
  Konfigurace (active, from, to, popupId) je v resources/js/popup.js → akcePopup
--}}
<div
  x-data="akcePopup"
  x-show="show"
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  class="popup-overlay"
  @click.self="close()"
>
  <div
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    class="popup-box"
    @click.stop
  >
    <button @click="close()" class="popup-close" aria-label="Zavřít">
      <i class="fa-solid fa-xmark text-xl"></i>
    </button>

    <h3 class="text-xl font-semibold text-mx-white mb-3">🔥 AKCE 🔥</h3>

    <div id="akce-popup-content" class="text-sm text-gray-300 mb-4"></div>

    @if (request()->path() !== 'cup/registrace-cup')
    <a href="{{ route('registrace-cup.index') }}" class="btn-primary btn-sm">Registrace</a>
    @endif

    <label class="flex items-center gap-2 mt-5 text-sm text-gray-400 cursor-pointer select-none">
      <input type="checkbox" id="popup-hide-forever" class="rounded border-mx-gray2 bg-mx-gray text-mx-orange focus:ring-mx-orange">
      Už nezobrazovat tuto akci
    </label>
  </div>
</div>
