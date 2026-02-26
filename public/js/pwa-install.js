(function () {
    // --- state ---
    var deferredPrompt = null;
    var installing = false;
    var suppressStatusUntil = 0; // "тихий режим" (ms since epoch)
    var DIAG_DELAY_MS = 2500;
    var diagTimer = null;

    // --- dom helpers ---
    function $(id) { return document.getElementById(id); }
    function show(el, yes) { if (el) el.style.display = yes ? '' : 'none'; }
    function setStatus(msg) {
        if (installing || Date.now() < suppressStatusUntil) msg = '';
        if (isStandalone()) msg = '';
        var s = $('pwaStatus');
        if (!s) return;
        s.textContent = msg || '';
        show(s, !!msg);
    }

    // --- env helpers ---
    function isIOS() {
        var ua = navigator.userAgent;
        var touchMac = navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1;
        return /iPad|iPhone|iPod/i.test(ua) || touchMac;
    }
    function isAndroidChrome() {
        var ua = navigator.userAgent;
        return /Android/i.test(ua) && /Chrome\/|CriOS\//i.test(ua);
    }
    function isMobileDevice() {
        var ua = navigator.userAgent;
        return /Android|iPhone|iPad|iPod|IEMobile|Opera Mini/i.test(ua);
    }
    function isStandalone() {
        if (window.navigator.standalone) return true; // iOS Safari
        if (window.matchMedia && window.matchMedia('(display-mode: standalone)').matches) return true;
        return false;
    }

    // --- UI ---
    function updateUI() {
        var block = $('pwaInstallBlock');
        var btn   = $('pwaInstallBtn');
        var ios   = $('pwaIosHint');
        var done  = $('pwaInstalledMsg');

        if (!block) return;

        // по умолчанию блок видим
        show(block, true);

        var installed = isStandalone();

        if (!isMobileDevice()) {
            if (ios) show(ios, false);
            if (done) show(done, false);
            if (btn) { btn.disabled = false; btn.style.display = ''; }
            setStatus('Установка доступна на мобильных устройствах.');
            return;
        }

        if (installed) {
            if (ios) show(ios, false);
            if (btn) btn.style.display = 'none';
            if (done) show(done, true);
            setStatus('');
            return;
        }

        if (isIOS()) {
            show(block, false);
            if (ios) show(ios, true);
            if (done) show(done, false);
            setStatus('');
            return;
        }

        // Android/Chromium
        if (btn) { btn.disabled = false; btn.style.display = ''; }
        if (ios) show(ios, false);
        if (done) show(done, false);

        if (deferredPrompt) setStatus('');
    }

    // --- диагностика (только реальные проблемы, вне standalone) ---
    async function diagnoseInstallability() {
        if (isStandalone() || installing || deferredPrompt || Date.now() < suppressStatusUntil) { setStatus(''); return; }

        var httpsOk = (location.protocol === 'https:') || location.hostname === 'localhost';
        var manifestOk = false, iconsOk = false, swReg = false, swCtrl = false;

        try {
            var link = document.querySelector('link[rel="manifest"]');
            if (link) {
                var res = await fetch(link.href, { cache: 'no-store' });
                manifestOk = (res.status === 200);
                if (manifestOk) {
                    var m = await res.json();
                    var sizes = (m.icons || []).map(function (i) { return i.sizes; });
                    iconsOk = sizes.indexOf('192x192') !== -1 && sizes.indexOf('512x512') !== -1;
                }
            }
        } catch (_) {}

        if ('serviceWorker' in navigator) {
            try {
                var reg = await navigator.serviceWorker.getRegistration();
                swReg = !!reg;
                swCtrl = !!navigator.serviceWorker.controller;
            } catch (_) {}
        }

        var msgs = [];
        if (!httpsOk) msgs.push('нет HTTPS');
        if (!manifestOk) msgs.push('manifest не найден/ошибка');
        if (manifestOk && !iconsOk) msgs.push('иконки 192/512 отсутствуют');
        if (!swReg) msgs.push('SW не зарегистрирован');
        if (swReg && !swCtrl) msgs.push('страница не контролируется SW');

        if (msgs.length) {
            setStatus('Установка недоступна: ' + msgs.join(', ') + '. Откройте меню ⋮ → «Установить приложение».');
        } else {
            setStatus(''); // без "Подготовка..." — чтобы не мешать UX
        }
    }

    function scheduleDiagnosis() {
        if (diagTimer) { clearTimeout(diagTimer); diagTimer = null; }
        if (isStandalone()) return;
        diagTimer = setTimeout(function () {
            if (!deferredPrompt && !installing && Date.now() >= suppressStatusUntil && !isStandalone()) {
                diagnoseInstallability();
            }
        }, DIAG_DELAY_MS);
    }

    // --- события платформы ---
    window.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault();
        deferredPrompt = e;
        setStatus('');
        if (diagTimer) { clearTimeout(diagTimer); diagTimer = null; }
        updateUI();
    });

    window.addEventListener('appinstalled', function () {
        installing = false;
        try { localStorage.setItem('pwaInstalled', '1'); } catch (_) {}
        deferredPrompt = null;
        setStatus('');
        updateUI();
    });

    // --- обработчик КНОПКИ (без делегирования на документ!) ---
    async function onInstallClick(e) {
        var t = e.currentTarget;

        // если уже standalone — ничего не делаем
        if (isStandalone()) { setStatus(''); return; }

        // ПК → модалка
        if (!isMobileDevice()) {
            if (window.jQuery && jQuery.fn && jQuery.fn.modal) jQuery('#pwaDesktopModal').modal('show');
            else alert('Установка доступна только на мобильных устройствах.');
            return;
        }

        // iOS — кнопки нет
        if (isIOS()) return;

        // ANDROID / CHROMIUM
        if (deferredPrompt) {
            installing = true;
            suppressStatusUntil = Date.now() + 20000; // 20 сек "тихий режим"
            if (diagTimer) { clearTimeout(diagTimer); diagTimer = null; }
            setStatus('');
            t.disabled = true;

            try {
                deferredPrompt.prompt();
                var choice = await deferredPrompt.userChoice;
                installing = false;
                if (choice && choice.outcome === 'accepted') {
                    try { localStorage.setItem('pwaInstalled', '1'); } catch (_) {}
                    setStatus(''); // ждём appinstalled
                } else {
                    setStatus('Установку отменили. Можно попробовать позже через меню ⋮ → «Установить приложение».');
                }
            } catch (_) {
                installing = false;
                setStatus('Не удалось открыть установку. Попробуйте позже.');
            } finally {
                deferredPrompt = null;
                t.disabled = false;
                updateUI();
                if (!installing && Date.now() >= suppressStatusUntil) scheduleDiagnosis();
            }
            return;
        }

        // Промпт недоступен — вежливый fallback + тихая диагностика
        setStatus('Похоже, приложение уже установлено или браузер не предлагает установку.');
        scheduleDiagnosis();
    }

    // --- старт ---
    document.addEventListener('DOMContentLoaded', function () {
        // сброс флажка в браузерном режиме
        try { if (!isStandalone()) localStorage.removeItem('pwaInstalled'); } catch (_) {}

        // страховка: убрать возможный inline display:none
        try { var block = $('pwaInstallBlock'); if (block) block.style.removeProperty('display'); } catch (_) {}

        // привязать обработчик ТОЛЬКО к кнопке (без делегирования)
        var btn = $('pwaInstallBtn');
        if (btn) btn.addEventListener('click', onInstallClick);

        // почистить залипший backdrop, если вдруг был
        if (window.jQuery && jQuery.fn && jQuery.fn.modal) {
            jQuery('#pwaDesktopModal').on('hidden.bs.modal', function () {
                jQuery('body').removeClass('modal-open');
                jQuery('.modal-backdrop').remove();
            });
        }

        updateUI();
        scheduleDiagnosis();
    });

    // чтобы ошибки были видны
    window.addEventListener('error', function (e) {
        setStatus('Ошибка скрипта: ' + (e && e.message ? e.message : 'неизвестно'));
    });
})();