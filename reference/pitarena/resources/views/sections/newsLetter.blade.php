{{-- newsletter --}}
{{--       <section class="section section-lg bg-accent context-dark text-center">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
              <h2>Neustále se snažíme přicházet s akcemi, které vás budou bavit.</h2>
              <h4>Dostávejte je v přehledném a krátkém e-mailu.</h4>

              <form id="news_letter_form" class="rd-mailform-inline-flex" data-form-output="form-output-global" data-form-type="subscribe" method="post" action="/news-letter">
                @csrf
                <div class="form-wrap">
                  <label class="form-label" for="newsLetterEmail">Zadejte svůj e-mail zde</label>
                  <input class="form-input text-center" id="newsLetterEmail" type="email" name="newsLetterEmail" value="{{ old('newsLetterEmail') }}" required>
                </div>
                <div class="form-button">
                  <button class="button button-sm button-gray-light-outline" type="submit">Odebírat</button>
                    <div class="g-recaptcha" 
                      data-sitekey="{{env('NOCAPTCHA_SITEKEY')}}"
                      data-size="invisible" 
                      data-callback='onSubmit'>
                    </div>
                </div>
              </form>
              <p>Žádný spam! Dostanete maximálně 1 email mesíčně.</p>
            </div>
          </div>
        </div>
      </section> --}}