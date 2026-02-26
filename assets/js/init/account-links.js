export function initAccountLinks() {
    // Это всё нужно на app_account_list - Чтобы по категорям кликать
    const bookmarkLink = document.getElementById("bookmarkLink");
    if (bookmarkLink) {
        bookmarkLink.addEventListener("click", () => {
            window.location.href = bookmarkLink.dataset.url;
        });
    }

    const defaultLink = document.getElementById("defaultLink");
    if (defaultLink) {
        defaultLink.addEventListener("click", () => {
            window.location.href = defaultLink.dataset.url;
        });
    }

    const featuredLink = document.getElementById("featuredLink");
    if (featuredLink) {
        featuredLink.addEventListener("click", () => {
            window.location.href = featuredLink.dataset.url;
        });
    }

    document.querySelectorAll(".clickable").forEach(cell => {
        cell.addEventListener("click", () => {
            const url = cell.getAttribute("data-url");
            if (url) {
                window.location.href = url;
            }
        });
    });
}
