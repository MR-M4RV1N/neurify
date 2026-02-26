const SW_VERSION = 'v1';

self.addEventListener('install', (event) => {
    // сразу активируем новый SW
    self.skipWaiting();
    console.log('[SW]', SW_VERSION, 'installed');
});

self.addEventListener('activate', (event) => {
    // берём под контроль все открытые клиенты
    event.waitUntil(self.clients.claim());
    console.log('[SW]', SW_VERSION, 'activated & claimed');
});

// Pass-through: можно оставить как есть, SW всё равно будет контролировать страницу
self.addEventListener('fetch', (event) => {
    // при желании: добавить офлайн-страницу/кэширование
    event.respondWith(fetch(event.request).catch(() => fetch(event.request)));
});