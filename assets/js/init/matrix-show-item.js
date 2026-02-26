export function initMatrixShowItem() {
    const table = document.querySelector('.devstyle-table');
    if (!table) return;

    table.addEventListener('click', function (e) {
        const cell = e.target.closest('.clickable-cell');
        if (!cell || !cell.dataset.id) return;

        const imageUrl = cell.dataset.imageUrl;
        const descriptionHtml = cell.dataset.description;
        const editLinkUrl = cell.dataset.editUrl;

        showItemModal({
            imageUrl,
            descriptionHtml,
            editLinkUrl
        });
    });
}

function decodeJsEscapedString(str) {
    try {
        return JSON.parse(`"${str}"`);
    } catch (e) {
        console.error("Ошибка декодирования строки:", str);
        return str;
    }
}

export function showItemModal({ imageUrl, descriptionHtml, editLinkUrl }) {
    const image = document.getElementById('image');
    const loader = document.getElementById('imageLoader');
    const description = document.getElementById('divDescription');
    const editLink = document.getElementById('editLink');

    // Сброс
    image.classList.add('d-none');
    loader.style.display = 'block';
    image.src = '';
    description.innerHTML = '';
    editLink.href = '#';

    // Декодирование и вставка описания
    const decodedHtml = decodeJsEscapedString(descriptionHtml);
    description.innerHTML = decodedHtml;

    // Обновление ссылки на редактирование
    if (editLinkUrl) editLink.href = editLinkUrl;

    // Обработка загрузки изображения
    image.onload = () => {
        loader.style.display = 'none';
        image.classList.remove('d-none');
    };

    image.onerror = () => {
        console.warn("Ошибка загрузки изображения:", imageUrl);
        loader.style.display = 'none';
        image.src = '/cpanel/images/maps/default.jpg'; // Показываем default, но без рекурсии
        image.classList.remove('d-none');
    };

    image.src = imageUrl;

    $('#itemShowModal').modal('show');
}