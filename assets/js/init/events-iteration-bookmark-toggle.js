export function initBookmarkToggle() {
    const confirmModalEl = document.getElementById("confirmModal");
    const confirmButton = document.getElementById("confirmAccept");
    const modalTitle = document.getElementById("confirmModalLabel");
    const modalBody = document.querySelector("#confirmModal .modal-body");

    if (!confirmModalEl || !confirmButton || !modalTitle || !modalBody) {
        console.warn("❗ Элементы модального окна для закладок не найдены.");
        return;
    }

    const confirmModal = new bootstrap.Modal(confirmModalEl);

    function handleBookmarkClick(event) {
        event.preventDefault();

        const buttonContainer = event.currentTarget.closest('.bookmark-button, .bookmark-remove');
        const eventId = buttonContainer.dataset.eventId;
        const csrfToken = buttonContainer.dataset.csrfToken;
        const isRemoveButton = event.currentTarget.classList.contains("bookmark-remove");
        const isAccepted = buttonContainer.classList.contains("accepted");

        modalTitle.textContent = isRemoveButton || isAccepted
            ? "Хотите отказаться от вызова?"
            : "Принятие вызова";

        modalBody.textContent = isRemoveButton || isAccepted
            ? "Вы действительно хотите отказаться от вызова?"
            : "Вы хотите принять вызов?";

        confirmButton.dataset.eventId = eventId;
        confirmButton.dataset.csrfToken = csrfToken;
        confirmButton.dataset.isRemoveButton = isRemoveButton;
        confirmButton.dataset.isAccepted = isAccepted;

        confirmModal.show();
    }

    document.querySelectorAll('.bookmark-toggle, .bookmark-remove').forEach(button => {
        button.addEventListener('click', handleBookmarkClick);
    });

    confirmButton.addEventListener("click", async function () {
        const eventId = this.dataset.eventId;
        const csrfToken = this.dataset.csrfToken;
        const isRemoveButton = this.dataset.isRemoveButton === "true";
        const isAccepted = this.dataset.isAccepted === "true";

        const url = isAccepted || isRemoveButton
            ? `/cpanel/bookmark/remove/${eventId}`
            : `/cpanel/bookmark/add/${eventId}`;
        const method = isAccepted || isRemoveButton ? 'DELETE' : 'POST';

        try {
            const response = await fetch(url, {
                method,
                headers: {
                    'X-CSRF-Token': csrfToken,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`Ошибка ${response.status}: ${response.statusText}`);
            }

            if (isRemoveButton) {
                window.location.href = "/cpanel/bookmark/list"; // Можно и через `path()` вставить через Twig
                return;
            }

            const buttonContainer = document.querySelector(`.bookmark-button[data-event-id="${eventId}"]`);
            const icon = buttonContainer?.querySelector(".bookmark-icon");
            const countSpan = buttonContainer?.querySelector(".bookmark-count");

            if (icon && countSpan) {
                let currentCount = parseInt(countSpan.textContent, 10) || 0;
                icon.classList.toggle('text-primary', !isAccepted);
                icon.classList.toggle('text-muted', isAccepted);
                buttonContainer.classList.toggle("accepted", !isAccepted);
                countSpan.textContent = isAccepted ? Math.max(0, currentCount - 1) : currentCount + 1;
            }

        } catch (error) {
            console.error('Ошибка при изменении закладки:', error);
            alert(`Произошла ошибка: ${error.message}`);
        }

        confirmModal.hide();
    });
}