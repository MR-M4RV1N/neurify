export function initCKEditor() {
    if (typeof CKEDITOR === 'undefined') {
        console.warn('CKEditor not loaded');
        return;
    }

    document.querySelectorAll('textarea[data-ckeditor]').forEach((textarea) => {
        const name = textarea.getAttribute('name');

        // Не дублируем, если уже инициализирован
        if (!textarea.dataset.ckeditorInitialized && name) {
            // Проверим, не был ли CKEditor уже прикреплён к name
            if (CKEDITOR.instances[name]) {
                CKEDITOR.instances[name].destroy(true);
            }

            CKEDITOR.replace(textarea);
            textarea.dataset.ckeditorInitialized = 'true';
        }
    });
}