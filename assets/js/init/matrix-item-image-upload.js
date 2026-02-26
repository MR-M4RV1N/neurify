export function initMatrixItemImageUpload() {
    const fileInput = document.getElementById('item-image-file');
    const previewImage = document.getElementById('blah');

    if (!fileInput || !previewImage) return;

    fileInput.addEventListener('change', function (evt) {
        const [file] = fileInput.files;
        if (file) {
            previewImage.src = URL.createObjectURL(file);
        }
    });
}