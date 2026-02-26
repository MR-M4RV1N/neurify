let listenersAttached = false;

export function initEventsFeed() {
    // Инициализируем делегирование только один раз
    if (!listenersAttached) {
        initLikeButtonsDelegated();
        listenersAttached = true;
    }

    initBookmarkModal();
    initCommentToggles();
}

// --- Лайки (Делегирование) ---
function initLikeButtonsDelegated() {
    document.addEventListener("click", (event) => {
        const button = event.target.closest(".like-button");
        if (!button) return;

        // Предотвращаем всплытие, если нужно (обычно для кнопок не обязательно, но полезно)
        // event.preventDefault(); 

        const eventId = button.dataset.eventId;
        const csrfToken = button.dataset.csrfToken;
        const heartIcon = button.querySelector(".heart-icon");

        // Не отправляем запрос, если уже идет обработка (опционально, можно добавить состояние loading)
        if (button.classList.contains("is-loading")) return;
        button.classList.add("is-loading");

        fetch(`/cpanel/like/${eventId}`, {
            method: "POST",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "Content-Type": "application/json",
                "X-CSRF-Token": csrfToken
            }
        })
            .then(res => res.json())
            .then(data => {
                if (data.result) {
                    heartIcon.classList.add("text-primary");
                    heartIcon.classList.remove("text-muted");
                } else {
                    heartIcon.classList.remove("text-primary");
                    heartIcon.classList.add("text-muted");
                }
            })
            .catch(error => console.error(`Like error for event ${eventId}:`, error))
            .finally(() => {
                button.classList.remove("is-loading");
            });
    });
}

// --- Закладки + модалка ---
function initBookmarkModal() {
    const modalEl = document.getElementById("confirmModal");
    const confirmModal = modalEl ? new bootstrap.Modal(modalEl) : null;
    const confirmBtn = document.getElementById("confirmAccept");
    const titleEl = document.getElementById("confirmModalLabel");
    const bodyEl = modalEl?.querySelector(".modal-body");

    if (!confirmModal || !confirmBtn) return;

    document.querySelectorAll(".bookmark-toggle, .bookmark-remove").forEach(button => {
        button.addEventListener("click", e => {
            e.preventDefault();
            const container = e.currentTarget.closest(".bookmark-button, .bookmark-remove");

            const eventId = container.dataset.eventId;
            const csrfToken = container.dataset.csrfToken;
            const isRemove = e.currentTarget.classList.contains("bookmark-remove");
            const isAccepted = container.classList.contains("accepted");

            titleEl.textContent = isRemove || isAccepted ? "Хотите отказаться от вызова?" : "Принятие вызова";
            bodyEl.textContent = isRemove || isAccepted
                ? "Вы действительно хотите отказаться от вызова?"
                : "Вы хотите принять вызов?";

            confirmBtn.dataset.eventId = eventId;
            confirmBtn.dataset.csrfToken = csrfToken;
            confirmBtn.dataset.isRemove = isRemove;
            confirmBtn.dataset.isAccepted = isAccepted;

            confirmModal.show();
        });
    });

    confirmBtn.addEventListener("click", async () => {
        const eventId = confirmBtn.dataset.eventId;
        const csrfToken = confirmBtn.dataset.csrfToken;
        const isRemove = confirmBtn.dataset.isRemove === "true";
        const isAccepted = confirmBtn.dataset.isAccepted === "true";

        const url = isAccepted || isRemove
            ? `/cpanel/bookmark/remove/${eventId}`
            : `/cpanel/bookmark/add/${eventId}`;
        const method = isAccepted || isRemove ? "DELETE" : "POST";

        try {
            const response = await fetch(url, {
                method,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-Token": csrfToken
                }
            });

            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const container = document.querySelector(`.bookmark-button[data-event-id="${eventId}"]`);
            const icon = container?.querySelector(".bookmark-icon");
            const countSpan = container?.querySelector(".bookmark-count");

            if (isRemove) {
                window.location.href = "/cpanel/bookmark/list";
                return;
            }

            if (icon && countSpan) {
                const currentCount = parseInt(countSpan.textContent, 10) || 0;
                icon.classList.toggle("text-primary", !isAccepted);
                icon.classList.toggle("text-muted", isAccepted);
                container.classList.toggle("accepted", !isAccepted);
                countSpan.textContent = isAccepted ? Math.max(0, currentCount - 1) : currentCount + 1;
            }

        } catch (error) {
            console.error("Bookmark error:", error);
            alert(`Ошибка: ${error.message}`);
        }

        confirmModal.hide();
    });
}

// --- Комментарии (скролл к якорю) ---
function initCommentToggles() {
    document.querySelectorAll('.comment-toggle').forEach(button => {
        button.addEventListener('click', event => {
            event.preventDefault();
            const path = button.closest('.comment-button')?.dataset.eventPath;
            if (path) {
                window.location.href = `${path}#target-element-id`;
            }
        });
    });
}