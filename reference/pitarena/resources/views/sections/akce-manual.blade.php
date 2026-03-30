{{--
  Popup: Dovolená
  Konfigurace (active, from, to, popupId) je v resources/js/popup.js → dovolePopup
--}}
<div
  x-data="dovolePopup"
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

    <h3 class="text-xl font-semibold text-mx-white mb-3">🏖️ Dovolená 🏖️</h3>

    <div class="text-sm text-gray-300 space-y-2">
      <p>Omezený provoz od 4.8. do 11.8. z důvodu dovolené.</p>
      <p><strong class="text-mx-white">Volné jízdy jsou v provozu.</strong> Info k volným jízdám na tel.
        <a href="tel:+420723350752" class="text-mx-gold hover:underline">723 350 752</a> u Aleše.
      </p>
      <p>Info k dalším programům na tel.
        <a href="tel:+420728697712" class="text-mx-gold hover:underline">728 697 712</a> u Ondry.
      </p>
    </div>

    <label class="flex items-center gap-2 mt-5 text-sm text-gray-400 cursor-pointer select-none">
      <input type="checkbox" id="popup-hide-forever-manual" class="rounded border-mx-gray2 bg-mx-gray text-mx-orange focus:ring-mx-orange">
      Už nezobrazovat tuto zprávu
    </label>
  </div>
</div>
