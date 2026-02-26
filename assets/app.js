import './bootstrap';
import './styles/app.scss';
import '@hotwired/turbo';
import '@khmyznikov/pwa-install';

// INIT IMPORTS
import { initAccountLinks } from './js/init/account-links';
import { initInfoBox } from './js/init/info-box';
import { initBottomAddMenuToggle } from './js/init/bottom-add-menu';
import { initMatrixShowItem } from './js/init/matrix-show-item';
import { initCKEditor } from './js/init/ckeditor-init';
import { initMatrixItemImageUpload } from './js/init/matrix-item-image-upload';
import { initMatrixItemDeleteModal } from './js/init/matrix-item-delete';
import { initActivityChart } from './js/init/activity-chart';
import { initSmallStepsChart } from './js/init/small-steps-chart';
import { initWeeklyScoreChart } from './js/init/weekly-score-chart';
import { initTypewriter } from './js/init/typewriter';
import { initEventsFeed } from './js/init/events-feed';
import { initProfileChat } from './js/init/event-ensemble-profile-chat';
import { initComments } from './js/init/event-comments';
import { initEventForm } from './js/init/event-form';
import { initSimpleForm } from './js/init/simple-form';
import { initPictureForm } from './js/init/picture-form';
import { initChatModal } from './js/init/event-chat-init';
import { initUsernameAvailabilityCheck } from './js/init/profile-username-check';
import { initPresentationSwiper } from './js/init/presentation-swiper';
import { initProfileShare } from './js/init/users-profile-share';
import { initBookmarkToggle } from './js/init/events-iteration-bookmark-toggle';
import { initAccountInfoBox } from './js/init/account-info-box';
import { initIntro } from './js/init/intro-init';
import { initAddLuxMenu } from './js/init/add-lux-menu';
import { initShareButton } from './js/init/share-button';
import { initSaveButtonCountdown } from './js/init/init-save-button';
import { initGoalPercentCharts } from './js/init/goal-percent-charts';

// REACT
import React from 'react';
import { createRoot } from 'react-dom/client';
import App from './react/App';

// =======================
// REACT INIT
// =======================
const rootEl = document.getElementById('react-matrix-root');
if (rootEl) {
    const root = createRoot(rootEl);
    root.render(<App />);
}

// =======================
// CHAT AI INTRO TYPING & CLEANUP
// =======================
document.addEventListener('turbo:before-cache', () => {
    // Очистка чата (ваша текущая логика)
    const outputEl = document.getElementById('output');
    if (outputEl) {
        outputEl.textContent = '';
        outputEl.classList.remove('is-typing');
        delete outputEl.dataset.initialized;
    }

    // --- ДОБАВИТЬ ЭТО ---
    // Очистка тестовой кнопки оверлея
    const testBtn = document.getElementById('ai-overlay-test-btn');
    if (testBtn) {
        delete testBtn.dataset.initialized;
    }

    // Очистка самого оверлея (на всякий случай скрываем его перед кэшированием)
    const overlay = document.getElementById('ai-loading-overlay');
    if (overlay) {
        overlay.classList.add('d-none');
    }
});
function initChatIntroTyping() {
    const outputEl = document.getElementById('output');
    if (!outputEl || outputEl.dataset.initialized) return;

    outputEl.dataset.initialized = 'true';
    outputEl.classList.add('is-typing'); // ← ВАЖНО

    initTypewriter({
        selector: '#output',
        text: outputEl.dataset.text,
        speed: 80
    });
}

document.addEventListener('turbo:submit-start', (e) => {
    if (e.target.id === 'chat-form') {
        const overlay = document.getElementById('ai-loading-overlay');
        if (overlay) overlay.classList.remove('d-none');
    }
});
document.addEventListener('turbo:submit-end', () => {
    const overlay = document.getElementById('ai-loading-overlay');
    if (overlay) overlay.classList.add('d-none');
});

// =======================
// AI LOADING OVERLAY
// =======================
document.addEventListener('turbo:submit-start', (event) => {
    // Проверяем, что отправляется именно наша форма анализа
    if (event.target.id === 'ai-analysis-form') {
        const overlay = document.getElementById('ai-loading-overlay');
        if (overlay) {
            overlay.classList.remove('d-none');
        }
    }
});
// (Опционально) Скрывать оверлей, если сервер вернул ошибку или отправка завершилась без перехода
document.addEventListener('turbo:submit-end', (event) => {
    if (event.target.id === 'ai-analysis-form') {
        // Если успешный переход, Turbo сам заменит body и оверлей исчезнет.
        // Но если будет ошибка валидации (422) или редирект не сработал,
        // нужно скрыть оверлей вручную, чтобы интерфейс не завис.
        const overlay = document.getElementById('ai-loading-overlay');
        // Проверяем успех (event.detail.formSubmission.result.success), но для простоты можно просто скрыть:
        if (overlay && !event.detail.formSubmission.result.success) {
            overlay.classList.add('d-none');
        }
    }
});
// =======================
// MAIN INIT
// =======================
export function initAll() {
    initAccountLinks();
    initInfoBox();
    initBottomAddMenuToggle();
    initMatrixShowItem();
    initCKEditor();
    initMatrixItemImageUpload();
    initMatrixItemDeleteModal();
    initComments();
    initChatModal();
    initUsernameAvailabilityCheck();
    initPresentationSwiper();
    initProfileShare();
    initBookmarkToggle();
    initAccountInfoBox();
    initIntro();
    initAddLuxMenu();
    initShareButton();
    initActivityChart();
    initSmallStepsChart();
    initWeeklyScoreChart();
    initGoalPercentCharts();

    // ✅ AI INTRO (важно: ВНУТРИ initAll)
    initChatIntroTyping();

    const pictureForm = document.querySelector('form[data-form-type="picture"]');
    if (pictureForm) initPictureForm();

    const simpleForm = document.querySelector('form[data-form-type="simple"]');
    if (simpleForm) initSimpleForm();

    const journalForm = document.querySelector('form[data-form-type="journal"]');
    if (journalForm) initSimpleForm();

    const eventForm = document.querySelector('form[data-form-context="event"]');
    if (eventForm) initEventForm();

    initEventsFeed();

    const chatButton = document.getElementById('chatButton');
    if (chatButton) {
        const profileId = chatButton.dataset.profileId;
        initProfileChat(
            profileId,
            `/cpanel/chat/check/${profileId}`,
            `/cpanel/chat/new/${profileId}`
        );
    }

    initSaveButtonCountdown();
}

// =======================
// TURBO ENTRY POINT
// =======================
document.addEventListener('turbo:load', initAll);
