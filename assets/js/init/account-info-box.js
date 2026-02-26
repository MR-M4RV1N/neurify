export function initAccountInfoBox() {
    function initScrollContainer() {
        const scrollContainer = document.getElementById('scrollContainer');
        if (scrollContainer) {
            scrollContainer.scrollTo({
                left: 60,
                behavior: 'auto' // ← мгновенно, без анимации
            });
        }
    }

    window.addEventListener('load', initScrollContainer);
    document.addEventListener('turbo:load', initScrollContainer);
}