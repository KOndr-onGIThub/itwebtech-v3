<section id="cisla" class="section {{ isset($background) ? $background : 'bg-mx-dark' }}">
  <div class="page-container">

    <div class="text-center mb-8">
      <div class="section-divider section-divider-center"></div>
      <h2 class="section-title text-balance">Přehled obsazených startovních čísel</h2>
    </div>

    <div class="overflow-x-auto">
      <table class="table-mx">
        <thead>
          <tr>
            <th>Kategorie</th>
            <th>Startovní čísla</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($riderCategories as $riderCategory)
          <tr>
            <td class="font-semibold text-mx-white">{{ $riderCategory->name }}</td>
            <td>
              @forelse ($registratedNumbers as $registratedNumber)
                @if ($registratedNumber->rider_category_id == $riderCategory->id)
                  <span class="badge-gold m-1">{{ $registratedNumber->startovni_cislo }}</span>
                @endif
              @empty
              @endforelse
            </td>
          </tr>
          @empty
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-4 text-xs text-gray-400 space-y-1">
      <p>* V každé kategorii smí být každé startovní číslo pouze jednou!</p>
      <p>* Pokud dojde k tomu, že si více jezdců zaregistruje stejné startovní číslo v jedné kategorii, bude mít nárok na číslo ten, kdo podal registraci dříve.</p>
    </div>

  </div>
</section>
