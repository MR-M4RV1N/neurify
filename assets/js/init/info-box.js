export function initInfoBox() {
    // Это всё нужно на app_account_list - Блок для отображения количества записей над картинками
    const infoBox = document.getElementById("infoBox");

    if (!infoBox) return;

    // Заменим элемент на клон, чтобы очистить старые обработчики (если они вдруг есть)
    const newInfoBox = infoBox.cloneNode(true);
    infoBox.parentNode.replaceChild(newInfoBox, infoBox);

    const targetUrl = newInfoBox.dataset.url;

    if (targetUrl) {
        newInfoBox.addEventListener("click", () => {
            window.location.href = targetUrl;
        });
    }
}