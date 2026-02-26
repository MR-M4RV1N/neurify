export function initMatrixItemDeleteModal() {
    const buttons = document.querySelectorAll('.devstyle-delete-item-btn');

    buttons.forEach(button => {
        // чтобы не повесить несколько раз
        if (button.dataset.bound === "true") return;
        button.dataset.bound = "true";

        button.addEventListener('click', () => {
            const itemId = button.dataset.id;

            const modal = document.getElementById('itemDeletingModal');
            if (modal) {
                $(modal).modal('show');

                // Если у тебя есть input внутри модалки — тогда:
                const hiddenInput = document.getElementById('inputItemId');
                if (hiddenInput) {
                    hiddenInput.value = itemId;
                }

                // ИЛИ если ты хочешь менять href прямо в ссылке:
                const deleteLink = modal.querySelector('.btn-danger');
                if (deleteLink) {
                    deleteLink.setAttribute('href', `/cpanel/editor/item/delete/${itemId}`);
                }
            }
        });
    });
}
