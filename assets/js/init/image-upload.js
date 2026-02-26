export function initImageUpload(options = {}) {
    const {
        deleteUrlPrefix = '/cpanel/editor/events/images/',
        dropzoneUrl = '/cpanel/editor/events/dropzone',
    } = options;

    initImageDelete(deleteUrlPrefix);
    initDropzone(dropzoneUrl);
}

function initImageDelete(deleteUrlPrefix) {
    const buttons = document.querySelectorAll('.devstyle-remove-image-btn');

    buttons.forEach(button => {
        if (button.dataset.bound === 'true') return;
        button.dataset.bound = 'true';

        button.addEventListener('click', function () {
            const imageId = this.dataset.id;
            if (!confirm('Вы уверены, что хотите удалить изображение?')) return;

            fetch(`${deleteUrlPrefix}${imageId}/delete`, {
                method: 'DELETE',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(response => {
                    if (response.ok) {
                        this.closest('.col-md-4')?.remove();
                    } else {
                        alert('Ошибка при удалении изображения');
                    }
                })
                .catch(() => {
                    alert('Ошибка при удалении изображения');
                });
        });
    });
}

function initDropzone(dropzoneUrl) {
    if (typeof Dropzone === 'undefined') {
        console.warn('Dropzone не загружен');
        return;
    }

    Dropzone.autoDiscover = false;

    const dropzoneEl = document.getElementById("file-dropzone");
    if (!dropzoneEl || dropzoneEl.dropzone) return;

    // На случай повторных инициализаций
    if (Dropzone.instances.length > 0) {
        Dropzone.instances.forEach(instance => instance.destroy());
    }

    new Dropzone(dropzoneEl, {
        url: dropzoneUrl,
        paramName: "images",
        maxFiles: 6,
        maxFilesize: 5,
        acceptedFiles: "image/*",
        addRemoveLinks: true,
        dictDefaultMessage: "Перетащите файлы сюда или кликните для загрузки",
        dictRemoveFile: "Удалить файл",
        dictMaxFilesExceeded: "Вы можете загрузить не более 6 файлов",
        init: function () {
            this.on("success", (file, response) => {
                console.log("Файл загружен:", response);
            });
            this.on("error", (file, errorMessage) => {
                console.error("Ошибка загрузки:", errorMessage);
            });
        }
    });
}