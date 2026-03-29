<section class="section-sm bg-mx-dark">
  <div class="page-container max-w-3xl">

    <div class="section-divider"></div>
    <h2 class="section-title mb-6">Proč nakoupit právě u nás?</h2>

    {{-- Alpine.js accordion --}}
    <div x-data="{ open: null }" class="space-y-2">

      @php
        $items = [
          [
            'title' => 'Nikdo nezná tyhle motorky lépe než my',
            'body'  => 'Prodáváme jen motorky na kterých denně jezdíme my, naše děcka a naši svěřenci. Dokážeme vám tak nejlépe poradit s výběrem, údržbou i s vhodným vylepšením.',
          ],
          [
            'title' => 'Vše, co potřebujete na jednom místě',
            'body'  => 'Nabízíme pitbike pro nejmenší děti, dorostence i pro dospělé. Provádíme autorizovaný záruční i pozáruční servis. Mnoho náhradních dílů máme skladem a vše ostatní bleskově dovezeme.',
          ],
          [
            'title' => 'Bonusy zdarma',
            'body'  => 'Zdarma 2,5 hodinový program MX GO, který vám dá potřebné základy pro bezproblémový start v terénu. A 10 vstupů na profesionální závodní trať ve formě volných jízd.',
          ],
        ];
      @endphp

      @foreach($items as $i => $item)
      <div class="border border-mx-gray2 rounded-lg overflow-hidden">
        <button
          @click="open = open === {{ $i }} ? null : {{ $i }}"
          class="w-full flex items-center justify-between px-5 py-4 text-left text-sm font-semibold text-mx-white hover:bg-mx-gray transition-colors duration-200 focus:outline-none"
          :aria-expanded="open === {{ $i }}"
        >
          <span>{{ $item['title'] }}</span>
          <i class="fa-solid fa-chevron-down text-mx-orange text-xs transition-transform duration-300"
             :class="open === {{ $i }} ? 'rotate-180' : ''"></i>
        </button>
        <div
          class="grid transition-[grid-template-rows] duration-300 ease-in-out"
          :class="open === {{ $i }} ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'"
        >
          <div class="overflow-hidden">
            <div class="px-5 pb-4 pt-1 text-sm text-gray-400 leading-relaxed">
              {{ $item['body'] }}
            </div>
          </div>
        </div>
      </div>
      @endforeach

    </div>
  </div>
</section>
