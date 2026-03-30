(function () {
  var KEY          = 'pitarena_cookies';
  var ACCEPT_DAYS  = 365;
  var REJECT_DAYS  = 14;

  function daysToMs(d) { return d * 24 * 60 * 60 * 1000; }

  function loadGTM() {
    if (window.__gtmLoaded) return;
    window.__gtmLoaded = true;
    (function (w, d, s, l, i) {
      w[l] = w[l] || [];
      w[l].push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
      var f = d.getElementsByTagName(s)[0],
          j = d.createElement(s),
          dl = l !== 'dataLayer' ? '&l=' + l : '';
      j.async = true;
      j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
      f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-TM2HSSH');
  }

  function shouldShowModal() {
    var raw = localStorage.getItem(KEY);
    if (!raw) return true;
    var parts = raw.split(':');
    var status = parts[0];
    var ts     = parseInt(parts[1], 10);
    var age    = Date.now() - ts;
    if (status === 'accepted') return age > daysToMs(ACCEPT_DAYS);
    if (status === 'rejected') return age > daysToMs(REJECT_DAYS);
    return true;
  }

  function hideModal() {
    var overlay = document.getElementById('cookie-overlay');
    if (!overlay) return;
    overlay.classList.remove('cookie-overlay--visible');
    setTimeout(function () { overlay.remove(); }, 350);
  }

  // Pokud má uložený souhlas (a ještě nevypršel) → načti GTM a skonči
  var raw = localStorage.getItem(KEY);
  if (raw && raw.indexOf('accepted:') === 0) {
    if (!shouldShowModal()) { loadGTM(); return; }
  }
  if (raw && raw.indexOf('rejected:') === 0) {
    if (!shouldShowModal()) return;
  }

  // Jinak zobraz modal
  document.addEventListener('DOMContentLoaded', function () {
    var overlay = document.getElementById('cookie-overlay');
    if (!overlay) return;

    setTimeout(function () {
      overlay.classList.add('cookie-overlay--visible');
    }, 800);

    // Přijmout
    document.getElementById('cookie-accept').addEventListener('click', function () {
      localStorage.setItem(KEY, 'accepted:' + Date.now());
      loadGTM();
      hideModal();
    });

    // Odmítnout
    document.getElementById('cookie-reject').addEventListener('click', function () {
      localStorage.setItem(KEY, 'rejected:' + Date.now());
      hideModal();
    });

    // Zavřít (X) — nic neukládá, zobrazí se znovu příště
    document.getElementById('cookie-close').addEventListener('click', hideModal);

    // Klik mimo modal — zavře bez uložení
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) hideModal();
    });
  });
})();
