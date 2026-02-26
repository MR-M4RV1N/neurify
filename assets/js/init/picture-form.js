import { initImageUpload } from './init-image-upload';

export function initPictureForm() {
    initImageUpload({
        deleteUrlPrefix: '/cpanel/picture/images/',
        uploadUrl: '/cpanel/picture/dropzone',
    });
}