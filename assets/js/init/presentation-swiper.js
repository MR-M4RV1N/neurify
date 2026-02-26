export function initPresentationSwiper() {
    const swiperEl = document.querySelector('.swiper-container');

    // Проверка, нужен ли запуск (чтобы не дублировать инициализацию)
    if (!swiperEl || swiperEl.swiper) return;

    const currentStepEl = document.getElementById('presentation-current-step');

    new Swiper(swiperEl, {
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        loop: true,
        on: {
            slideChange: function () {
                if (currentStepEl) {
                    currentStepEl.textContent = this.realIndex + 1;
                }
            }
        }
    });
}