import axios from 'axios';
import Swal from 'sweetalert2';

// Formuláře: Kontaktní (/o-nas) + MotoShop (/moto-shop)
// reCaptcha je načtena přes CDN v blade šablonách (google recaptcha API)

const MySwal = Swal;

document.addEventListener('DOMContentLoaded', () => {

  // ── Detekce formuláře dle pathname ──────────────────────────
  const path = window.location.pathname;
  let form = null;

  if (path === '/o-nas') {
    form = document.getElementById('contact_form');
  } else {
    form = document.getElementById('motoshop_form');
  }

  if (!form) return;

  // ── reCaptcha flow ───────────────────────────────────────────
  let really = false;

  form.addEventListener('submit', (e) => {
    if (really) return;
    e.preventDefault();
    grecaptcha.reset();
    grecaptcha.execute();
  });

  window.onSubmitContactForm = () => { really = true; submitContactForm(); };
  window.onSubmitMotoShop    = () => { really = true; submitMotoShop(); };

  // ── Pomocné funkce ───────────────────────────────────────────
  function loaderStart(loader) { loader.value = 'moment ...'; loader.disabled = true; }
  function loaderStop(loader)  { loader.value = 'Odeslat';    loader.disabled = false; }

  // ── Kontaktní formulář (/o-nas) ──────────────────────────────
  function submitContactForm() {
    const data = new FormData();
    const loader = document.getElementById('loading_contact_form');
    loaderStart(loader);
    data.append('token',                form.querySelector('[name="_token"]').value);
    data.append('name',                 form.querySelector('#contact-name').value);
    data.append('email',                form.querySelector('#contact-email').value);
    data.append('phone',                form.querySelector('#contact-phone').value);
    data.append('message',              form.querySelector('#contact-message').value);
    data.append('g-recaptcha-response', grecaptcha.getResponse());
    axios.post('/o-nas', data)
      .then(r  => { MySwal.fire({ text: r.data.message, icon: 'success' }); form.reset(); loaderStop(loader); })
      .catch(e => { MySwal.fire({ title: e.response.data.message, text: String(e), icon: 'error' }); loaderStop(loader); });
  }

  // ── MotoShop formulář (/moto-shop) ───────────────────────────
  function submitMotoShop() {
    const data = new FormData();
    const loader = document.getElementById('loading_motoshop_form');
    loaderStart(loader);
    data.append('token',                form.querySelector('[name="_token"]').value);
    data.append('name',                 form.querySelector('#contact-name').value);
    data.append('email',                form.querySelector('#contact-email').value);
    data.append('phone',                form.querySelector('#contact-phone').value);
    data.append('model',                form.querySelector('#contact-model').value);
    data.append('message',              form.querySelector('#contact-message').value);
    data.append('g-recaptcha-response', grecaptcha.getResponse());
    axios.post('/moto-shop', data)
      .then(r  => { MySwal.fire({ text: r.data.message, icon: 'success' }); form.reset(); loaderStop(loader); })
      .catch(e => { MySwal.fire({ title: e.response.data.message, text: String(e), icon: 'error' }); loaderStop(loader); });
  }

});
