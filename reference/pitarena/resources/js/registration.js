/**
 * Registration Cup – Vanilla JS (jQuery → Vanilla migration)
 * Pricing logic preserved exactly from registration_cup.js
 */
document.addEventListener('DOMContentLoaded', () => {

    /* AKCE */
    const ACTION_ENABLED = true; // ← TÍMTO VYPNEŠ / ZAPNEŠ AKCI
    const dnes = new Date();
    const aktualniRok = dnes.getFullYear();
    const limitniDatum = new Date(aktualniRok, 2, 15); // Březen je index 2
    const overlay = document.getElementById('popup-overlay');
    const SEASON_RACES_COUNT = 5; // uprav, když přidáš/ubereš závod

    // -- CENÍK (lze měnit bez hrabání v logice) --
    const PRICING = {
        default: { normal: 1200, action: 1000 },
        mini:    { normal: 650,  action: null }, // action: null = žádná akční cena
    };
    // Kategorie MINI
    const MINI_CATEGORY_NAME = 'MINI 50';

    function isMiniCategorySelected() {
        const sel = document.querySelector('#kategorie');
        if (!sel) return false;
        const text = (sel.options[sel.selectedIndex]?.text || '').trim().toUpperCase();
        return text === MINI_CATEGORY_NAME.toUpperCase();
    }

    manageActionPopup(dnes, aktualniRok, limitniDatum, overlay);

    const kategorieEl = document.querySelector('#kategorie');
    if (kategorieEl) {
        kategorieEl.addEventListener('change', () => updateSeasonLabels());
    }

    /* registration */
    manegeAttributes(dnes, limitniDatum);
    recalcPrice(dnes, limitniDatum);

    document.querySelectorAll('.sezona-details input').forEach(el => {
        el.addEventListener('change', () => manegeAttributes(dnes, limitniDatum, true));
    });
    document.querySelectorAll('.licence-details input, [name="zavod[]"]').forEach(el => {
        el.addEventListener('change', () => manegeAttributes(dnes, limitniDatum, false));
    });

    document.querySelectorAll('.licence-details input, .sezona-details input, .user-details .checkboxes input, [name="zavod[]"]').forEach(el => {
        el.addEventListener('change', () => {
            document.querySelectorAll('.invalidCombination_1').forEach(e => { e.hidden = true; });
            recalcPrice(dnes, limitniDatum);
        });
    });

    if (kategorieEl) {
        kategorieEl.addEventListener('change', () => {
            document.querySelectorAll('.invalidCombination_1').forEach(e => { e.hidden = true; });
            recalcPrice(dnes, limitniDatum);
        });
    }

    function manegeAttributes(dnes, limitniDatum, resetZavody = false) {
        const disablingElements = document.querySelectorAll('.disabling');
        const disablingElementsOposite = document.querySelectorAll('.disablingOposite');

        const sezonaChecked = document.querySelector('.sezona-details input[name="sezona"]:checked');

        if (sezonaChecked?.value === 'cela-sezona-ANO') {
            disablingElements.forEach(el => { el.checked = true; el.disabled = true; });
            disablingElementsOposite.forEach(el => { el.disabled = false; });
        } else {
            disablingElements.forEach(el => { if (resetZavody) el.checked = false; el.disabled = false; });
            disablingElementsOposite.forEach(el => { el.disabled = true; el.value = ''; });
        }

        /* pokud je platná akce a je zaškrtnut alespon 1 zavod, tak povolit startovní číslo */
        const licenceChecked = document.querySelector('input[name="licence"]:checked');
        const zavodCheckedCount = document.querySelectorAll('[name="zavod[]"]:checked').length;

        if (
            dnes <= limitniDatum &&
            ['licence-YCF', 'licence-Moravia'].includes(licenceChecked?.value) &&
            zavodCheckedCount > 0
        ) {
            document.querySelectorAll('[name="startovni_cislo"]').forEach(el => { el.disabled = false; });
        }
    }

    function recalcPrice(dnes, limitniDatum) {
        let licenceRok = 0;
        let licenceDen = 0;
        let sezona = 0;
        let pocetZavodu = 0;
        const isActionActive = ACTION_ENABLED && (dnes <= limitniDatum);

        const isMini = isMiniCategorySelected();
        const base = isMini ? PRICING.mini : PRICING.default;

        // default popisek podle vybrané kategorie
        let cenaZaZavodSlovne = `Jednotlivé závodní dny ${base.normal.toLocaleString('cs-CZ')} Kč /závod`;

        if (!isMini) {
            cenaZaZavodSlovne += ` <small>(650 Kč pro kategorii ${MINI_CATEGORY_NAME})</small>`;
        }

        // výchozí (bez akce)
        let cenaZaZavod = base.normal;

        const infoAkce = document.getElementById('info-akce');
        if (isActionActive && infoAkce) {
            infoAkce.innerHTML = 'Kupte online alespoň 1 závod a mějte platnou licenci (YCF nebo Moravia) – získáte slevu 200 Kč na každý závod. <small>Neplatí pro kategorii MINI 50 (je již zlevněna).</small><br><strong>POZOR AKCE končí ' + limitniDatum.toLocaleDateString('cs-CZ') + '!</strong>';
        }

        const licenceChecked = document.querySelector('input[name="licence"]:checked');

        if (licenceChecked?.value === 'licence-YCF' || licenceChecked?.value === 'licence-Moravia') {
            licenceRok = licenceChecked.value === 'licence-Moravia' ? 0 : 500;

            if (isActionActive && base.action !== null) {
                cenaZaZavod = base.action;
            }
            if (isActionActive && base.action !== null) {
                cenaZaZavodSlovne = `Jednotlivé závodní dny <s>${base.normal.toLocaleString('cs-CZ')}</s> <span class="highlighted">${base.action.toLocaleString('cs-CZ')} Kč</span> /závod
                    <small class="highlighted">SLEVA je platná pro všechny závody včetně platby na místě, pouze pokud nyní zaškrtnete nejméně 1 ze závodů.</small>`;
            } else {
                cenaZaZavodSlovne = `Jednotlivé závodní dny ${base.normal.toLocaleString('cs-CZ')} Kč /závod`;
                if (!isMini) {
                    cenaZaZavodSlovne += ` <small>(650 Kč pro kategorii ${MINI_CATEGORY_NAME})</small>`;
                }
            }
        } else if (licenceChecked?.value === 'licence-jednodenni') {
            licenceDen = 100;
            cenaZaZavod = cenaZaZavod + licenceDen;
        }

        const akceActivated = document.getElementById('akce-activated');
        if (akceActivated && String(akceActivated.value) === 'true' && base.action !== null) {
            cenaZaZavod = base.action;
        }

        const sezonaChecked = document.querySelector('.sezona-details input[name="sezona"]:checked');
        const dveSplatkyEl = document.getElementById('dve_splatky');

        if (sezonaChecked?.value === 'cela-sezona-ANO') {

            if (dveSplatkyEl) dveSplatkyEl.disabled = false;

            const wantsInstallments = dveSplatkyEl?.checked || false;

            if (isMini) {
                const seasonBase = PRICING.mini.normal * SEASON_RACES_COUNT;
                sezona = wantsInstallments ? Math.round(seasonBase / 2) : seasonBase;
                pocetZavodu = 0;
            } else {
                sezona = wantsInstallments ? 2500 : 5000;
                pocetZavodu = 0;
            }

            if (licenceChecked?.value === 'licence-jednodenni') {
                alert('Zvolili jste neplatnou kombinaci. Jednodenní licence nelze kombinovat se zakoupením celé sezóny.\n\nZměňte licenci na YCF-cup, nebo Moravia-cup.\n\n!! Jinak bude vaše objednávka vyhodnocena administrací jako neplatná.');
                document.querySelectorAll('.invalidCombination_1').forEach(el => { el.hidden = false; });
            }

        } else {

            if (dveSplatkyEl) dveSplatkyEl.disabled = true;

            sezona = 0;

            pocetZavodu = document.querySelectorAll('[name="zavod[]"]:checked').length;
            if (pocetZavodu >= SEASON_RACES_COUNT) {
                const celaSezonaANO = document.querySelector('input[value="cela-sezona-ANO"]');
                if (celaSezonaANO && !celaSezonaANO.checked) {
                    celaSezonaANO.checked = true;
                    manegeAttributes(dnes, limitniDatum, false);
                    recalcPrice(dnes, limitniDatum);
                    return;
                }
            }
            if (pocetZavodu > 0) {
                const celaSezonaNE = document.querySelector('input[value="cela-sezona-NE"]');
                if (celaSezonaNE) celaSezonaNE.checked = true;
            }
        }

        const total = licenceRok + sezona + (pocetZavodu * cenaZaZavod);

        const submitBtn = document.getElementById('loading_cup_registration_form');
        if (submitBtn) submitBtn.value = 'Objednat za ' + total + ',-Kč';

        const submitBtnUpdate = document.getElementById('loading_cup_registration_form_update');
        if (submitBtnUpdate) submitBtnUpdate.value = 'Objednat za ' + total + ',-Kč';

        const zaplatitEl = document.getElementById('zaplatit');
        if (zaplatitEl) zaplatitEl.value = total;

        const cenaZaZavodEl = document.getElementById('cenaZaZavodSlovne');
        if (cenaZaZavodEl) cenaZaZavodEl.innerHTML = cenaZaZavodSlovne;
    }


    /* overovaci kod jiz registrovaneho jezdce */
    const getVerCodeBtn = document.getElementById('get_verification_cup_code');
    if (getVerCodeBtn) {
        getVerCodeBtn.addEventListener('click', () => getVerificationCode());
    }

    function getVerificationCode() {
        const select = document.getElementById('select_rider');
        const url = '/cup/get-verification-cup-code/' + (select?.value || '');

        fetch(url, { method: 'GET', headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(() => {
                alert('Odeslali jsme zprávu s ověřovacím kódem na email, který byl zadán při registraci vybraného jezdce. \n\n ');
            })
            .catch(() => {
                alert('Nepodarilo se odeslat ověřovací kód. \n\n Zkontrolujte, že jste správně zadali výběr jezdce. \n\n Pokud se chyba opakuje dejte nám o tom prosím vědět na ycf-cup@pitarena.cz');
            });
    }


    /* doobjednavka jiz registrovaneho jezdce */
    const getRiderDataBtn = document.getElementById('get_rider_data');
    if (getRiderDataBtn) {
        getRiderDataBtn.addEventListener('click', () => getRiderData());
    }

    function getRiderData() {
        const enteredVerificationCode = document.getElementById('verification_code')?.value || '';
        const url = '/cup/get-rider-data/' + enteredVerificationCode;

        fetch(url, { method: 'GET', headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                if (data !== '') {
                    alert('Data byla úspěšně načtena do formuláře.');
                    manageFixDataForm(data);
                    manageOrderForm(data);
                } else {
                    alert('nic k načtení');
                }
            })
            .catch(() => {
                alert('Nepodařilo se načíst data o jezdci s kódem ' + enteredVerificationCode + '. \n Prosím zkontrolujte, zadaný ověřovací kód. \n\n V případě potřeby odešlete nový ověřovací kód. Platnost kódu je neomezena - tedy do doby než odešlete nový.');
            });
    }

    /* Slouží k načtení dat o stavu objednávky */
    function manageFixDataForm(data) {
        const set = (id, val) => { const el = document.getElementById(id); if (el) el.value = val ?? ''; };

        set('jmeno_jezdce_fix', data['jmeno_jezdce']);
        set('datum_narozeni_fix', data['datum_narozeni']);
        set('email_jezdce_fix', data['email_jezdce']);
        set('telefon_jezdce_fix', data['telefon_jezdce']);

        const foto = document.getElementById('foto_jezdce_fix');
        if (foto) foto.src = '../storage/riders/' + data['foto_jezdce'];

        set('fakturacni_jmeno_fix', data['fakturacni_jmeno'] != null ? data['fakturacni_jmeno'] : data['jmeno_jezdce']);
        set('ulice_fix', data['ulice']);
        set('obec_fix', data['obec']);
        set('psc_fix', data['psc']);
        set('firm_name_fix', data['firm_name']);

        if (data['licence'] === 'licence-Moravia') {
            set('licence_fix', 'licence YCF nezakoupena');
        } else {
            set('licence_fix', data['licence']);
        }

        const sezonaFix = document.getElementById('sezona_fix');
        if (sezonaFix) {
            sezonaFix.textContent = data['sezona'] === 'cela-sezona-ANO'
                ? 'Zakoupeny závody pro celou sezónu'
                : 'Zakoupeny tyto závody: ' + data['zavod'];
        }

        set('dve_splatky_fix', data['dve_splatky'] ? 'ANO' : 'NE');

        const categoryIDName = '[name="categoryId_' + data['rider_category_id'] + '"]';
        document.querySelectorAll('.rider_category_name').forEach(el => { el.hidden = true; });
        const catEl = document.querySelector(categoryIDName);
        if (catEl) catEl.hidden = false;

        set('startovni_cislo_fix', data['startovni_cislo']);
        set('moto_team_fix', data['moto_team']);
        set('moto_brand_fix', data['moto_brand']);
        set('moto_volume_fix', data['moto_volume']);
        set('moto_kola_fix', data['moto_kola']);

        const poznamkaFix = document.getElementById('poznamka_fix');
        if (poznamkaFix) poznamkaFix.textContent = data['poznamka'];

        set('zaplaceno_fix', data['zaplaceno'] ? 'ANO' : 'NE');
        set('schvalen_fix', data['schvalen'] ? 'ANO' : 'zatím NE');
    }


    function manageOrderForm(data) {
        const action = '../cup/objednavka-cup/' + data['id'];
        const updateForm = document.getElementById('registrationCup_form_update');
        if (updateForm) updateForm.action = action;

        document.querySelectorAll('.no-data-loaded-yet').forEach(el => { el.hidden = true; });

        const loadingBtnUpdate = document.getElementById('loading_cup_registration_form_update');
        if (loadingBtnUpdate) loadingBtnUpdate.hidden = false;

        if (data['licence'] !== 'licence-YCF') {
            const licenceDetails = document.getElementById('licence_details');
            if (licenceDetails) licenceDetails.hidden = false;
        }

        if (data['sezona'] !== 'cela-sezona-ANO' && data['zavod'].length !== 6) {
            ['sezona_details', 'splatky_detail', 'zavody_detail', 'startovni_cislo_title', 'startovni_cislo_section'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.hidden = false;
            });
        }

        // nejprve zobrazim pro pripad kdyby nekdo prohlizel predtim jezdce ktery mel neco skryto
        document.querySelectorAll('.licence-jednodenni, .licence-moravia').forEach(el => { el.hidden = false; });
        if (data['sezona'] === 'cela-sezona-ANO' || data['zavod'].length === 6) {
            document.querySelectorAll('.licence-jednodenni, .licence-moravia').forEach(el => { el.hidden = true; });
        }

        const checks = {
            check_1: '11.04.2026 Pravice',
            check_2: '30.05.2026 Smrk',
            check_3: '27.06.2026 Vranov',
            check_4: '26.09.2026 Miroslav',
            check_5: '03.10.2026 Pravice',
            check_6: '',
        };

        // nejprve všechny zobrazim
        Object.keys(checks).forEach(cls => {
            document.querySelectorAll('.' + cls).forEach(el => { el.hidden = false; });
        });

        // pak skryju závody které má jezdec jiz zakoupeny
        Object.entries(checks).forEach(([cls, val]) => {
            if (val && data['zavod'].includes(val)) {
                document.querySelectorAll('.' + cls).forEach(el => { el.hidden = true; });
            }
        });

        // nejprve hlasku skryju pro pripad kdyby nekdo prohlizel predtim jezdce ktery ji mel zobrazeno
        document.querySelectorAll('.no-more-items-available').forEach(el => { el.hidden = true; });

        // pokud ma jiz YCF cup licenci celorocni i zaplacenou celou sezonu nebo objednane vsechny zavody
        if (
            (data['licence'] === 'licence-YCF' && data['sezona'] === 'cela-sezona-ANO') ||
            (data['licence'] === 'licence-YCF' && data['zavod'].length === 6)
        ) {
            document.querySelectorAll('.no-more-items-available').forEach(el => { el.hidden = false; });
            if (loadingBtnUpdate) loadingBtnUpdate.hidden = true;
        }

        const akceActivated = document.getElementById('akce-activated');
        if (akceActivated) {
            akceActivated.value = data['akce'] ? data['akce'] : 'false';
        }
    }

    /* ─── Validace registračního formuláře ─── */
    const regForm = document.getElementById('registrationCup_form');
    if (regForm) {
        regForm.addEventListener('submit', function (e) {
            const errors = [];

            const sezonaChecked = regForm.querySelector('input[name="sezona"]:checked');
            const licenceChecked = regForm.querySelector('input[name="licence"]:checked');

            // Jednotlivé závody → musí být vybrán alespoň 1
            if (sezonaChecked?.value === 'cela-sezona-NE') {
                if (regForm.querySelectorAll('input[name="zavod[]"]:checked').length === 0) {
                    errors.push('Vyberte prosím alespoň jeden závod.');
                }
            }

            // Neplatná kombinace jednodenní + celá sezóna
            if (sezonaChecked?.value === 'cela-sezona-ANO' && licenceChecked?.value === 'licence-jednodenni') {
                errors.push('Neplatná kombinace: Jednodenní licenci nelze kombinovat s celou sezónou. Změňte licenci.');
            }

            if (errors.length > 0) {
                e.preventDefault();
                showRegFormErrors(errors);
            } else {
                const errDiv = document.getElementById('reg-form-errors');
                if (errDiv) errDiv.hidden = true;
            }
        });
    }

    function showRegFormErrors(errors) {
        let errDiv = document.getElementById('reg-form-errors');
        if (!errDiv) {
            errDiv = document.createElement('div');
            errDiv.id = 'reg-form-errors';
            const submitBar = regForm.querySelector('.sticky.bottom-4');
            if (submitBar) submitBar.before(errDiv);
        }
        errDiv.hidden = false;
        errDiv.className = 'mb-4 p-4 rounded-lg border border-red-600 bg-red-950 text-red-200 text-sm';
        errDiv.innerHTML = '<strong class="block mb-2">Před odesláním opravte následující:</strong>'
            + '<ul class="list-disc list-inside space-y-1">'
            + errors.map(msg => `<li>${msg}</li>`).join('')
            + '</ul>';
        errDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    /* ─── Validace update formuláře ─── */
    const updateForm = document.getElementById('registrationCup_form_update');
    if (updateForm) {
        updateForm.addEventListener('submit', function (e) {
            const errors = [];

            // Licence
            const licenceSection = document.getElementById('licence_details');
            if (licenceSection && !licenceSection.hidden) {
                if (!updateForm.querySelector('input[name="licence"]:checked')) {
                    errors.push('Vyberte prosím typ licence.');
                }
            }

            // Sezóna
            const sezonaSection = document.getElementById('sezona_details');
            if (sezonaSection && !sezonaSection.hidden) {
                const sezonaChecked = updateForm.querySelector('input[name="sezona"]:checked');
                if (!sezonaChecked) {
                    errors.push('Vyberte prosím možnost sezóny (celá sezóna nebo jednotlivé závody).');
                } else {
                    // Jednotlivé závody → musí být vybrán alespoň 1
                    if (sezonaChecked.value === 'cela-sezona-NE') {
                        const zavodySection = document.getElementById('zavody_detail');
                        if (zavodySection && !zavodySection.hidden) {
                            if (updateForm.querySelectorAll('input[name="zavod[]"]:checked').length === 0) {
                                errors.push('Vyberte prosím alespoň jeden závod.');
                            }
                        }
                    }
                    // Neplatná kombinace jednodenní + celá sezóna
                    const licenceChecked = updateForm.querySelector('input[name="licence"]:checked');
                    if (sezonaChecked.value === 'cela-sezona-ANO' && licenceChecked?.value === 'licence-jednodenni') {
                        errors.push('Neplatná kombinace: Jednodenní licenci nelze kombinovat s celou sezónou. Změňte licenci.');
                    }
                }
            }

            if (errors.length > 0) {
                e.preventDefault();
                showUpdateFormErrors(errors);
            } else {
                // Skrýt chyby při úspěšném submitu
                const errDiv = document.getElementById('update-form-errors');
                if (errDiv) errDiv.hidden = true;
            }
        });
    }

    function showUpdateFormErrors(errors) {
        let errDiv = document.getElementById('update-form-errors');
        if (!errDiv) {
            errDiv = document.createElement('div');
            errDiv.id = 'update-form-errors';
            const submitBtn = document.getElementById('loading_cup_registration_form_update');
            if (submitBtn) submitBtn.before(errDiv);
        }
        errDiv.hidden = false;
        errDiv.className = 'mb-4 p-4 rounded-lg border border-red-600 bg-red-950 text-red-200 text-sm';
        errDiv.innerHTML = '<strong class="block mb-2">Před odesláním opravte následující:</strong>'
            + '<ul class="list-disc list-inside space-y-1">'
            + errors.map(msg => `<li>${msg}</li>`).join('')
            + '</ul>';
        errDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    /* AKCE */
    function manageActionPopup(dnes, aktualniRok, limitniDatum, overlay) {
        const popupStorageKey = 'popup_' + aktualniRok + '_hidden';
        const hideForeverCheckbox = document.getElementById('popup-hide-forever');
        const popup = document.getElementById('akce-popup');

        if (popup && overlay) {
            const akcePopupContent = document.getElementById('akce-popup-content');
            const akcePopupHtml = '<p>Registruj se online do závodů YCF CUP, kup nejméně 1 závod a získáš <strong class="highlighted">slevu 200 kč na každý závod</strong>.' +
                                    '<br><span class="highlighted">Platí jen do ' + limitniDatum.toLocaleDateString('cs-CZ') + '!</span></p>' +
                                    '<i class="highlighted">• Sleva zůstane platná pro celou sezónu<br>• Platí i pro následné platby na místě</i>';
            if (akcePopupContent) akcePopupContent.innerHTML = akcePopupHtml;

            function closePopup() {
                if (hideForeverCheckbox && hideForeverCheckbox.checked) {
                    localStorage.setItem(popupStorageKey, 'true');
                }
                popup.style.display = 'none';
                overlay.style.display = 'none';
            }

            if (
                ACTION_ENABLED === true &&
                dnes <= limitniDatum &&
                !localStorage.getItem(popupStorageKey) &&
                !sessionStorage.getItem('popup_' + aktualniRok + '_shown')
            ) {
                popup.style.display = 'block';
                overlay.style.display = 'block';
                sessionStorage.setItem('popup_' + aktualniRok + '_shown', 'true');
            }

            document.querySelectorAll('.popup-close').forEach(button => {
                button.addEventListener('click', () => closePopup());
            });

            overlay.addEventListener('click', () => closePopup());
        }
    }

    function updateSeasonLabels() {
        const isMini = isMiniCategorySelected();

        const defaultFull = 5000;
        const defaultHalf = 2500;

        const miniFull = PRICING.mini.normal * SEASON_RACES_COUNT;      // 650 * 5 = 3250
        const miniHalf = Math.round(miniFull / 2);                       // 1625

        const full = isMini ? miniFull : defaultFull;
        const half = isMini ? miniHalf : defaultHalf;

        const seasonPriceLabel = document.getElementById('season-price-label');
        if (seasonPriceLabel) seasonPriceLabel.textContent = full.toLocaleString('cs-CZ') + ' Kč';

        const seasonInstLabel = document.getElementById('season-installments-label');
        if (seasonInstLabel) seasonInstLabel.textContent = '2× ' + half.toLocaleString('cs-CZ') + ' Kč při volbě na splátky níže)';
    }

});
