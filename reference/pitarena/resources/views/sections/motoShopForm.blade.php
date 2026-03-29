<section class="section bg-mx-gray">
  <div class="page-container max-w-2xl">

    <div class="text-center mb-8">
      <div class="section-divider section-divider-center"></div>
      <h2 class="section-title">Nezávazně poptat</h2>
      <p class="text-gray-400 mt-3 text-sm">
        <i class="fa-regular fa-calendar text-mx-orange mr-1"></i>
        Obvyklá dostupnost je 3–7 dní.<br>
        Napište nám a domluvíme se na prohlídce, koupi, nebo vám poskytneme další informace.
      </p>
    </div>

    <form id="motoshop_form" method="post" action="/moto-shop" class="space-y-4">
      @csrf

      <div class="form-field">
        <label class="form-label" for="contact-name">Jméno *</label>
        <input class="form-input" id="contact-name" type="text" name="name"
               placeholder="Vaše jméno" value="{{ old('name') }}" required minlength="3">
      </div>

      <div class="form-field">
        <label class="form-label" for="contact-email">E-mail *</label>
        <input class="form-input" id="contact-email" type="email" name="email"
               placeholder="Vaše emailová adresa" value="{{ old('email') }}" required>
      </div>

      <div class="form-field">
        <label class="form-label" for="contact-phone">Telefon *</label>
        <input class="form-input" id="contact-phone" type="text" name="phone"
               placeholder="Vaše telefonní číslo" value="{{ old('phone') }}" required minlength="9">
      </div>

      <div class="form-field">
        <label class="form-label" for="contact-model">Model</label>
        <input class="form-input bg-mx-black/30 cursor-not-allowed" id="contact-model" type="text" name="model"
               value="{{ ' model '. request()->segment(count(request()->segments())) }}" disabled>
      </div>

      <div class="form-field">
        <label class="form-label" for="contact-message">Vzkaz</label>
        <textarea class="form-textarea" id="contact-message" name="message"
                  placeholder="Můžete přidat vzkaz ...">{{ old('message') }}</textarea>
      </div>

      <div class="flex items-center justify-between pt-2">
        <span class="text-xs text-gray-400">* vyžadované pole</span>
        <div>
          <input id="loading_motoshop_form"
                 class="btn-primary cursor-pointer"
                 type="submit" value="Odeslat" />
          <div class="g-recaptcha"
               data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"
               data-size="invisible"
               data-callback="onSubmitMotoShop">
          </div>
        </div>
      </div>

    </form>
  </div>
</section>

{{-- reCaptcha + form dependencies --}}
<script src="https://www.google.com/recaptcha/api.js"></script>
