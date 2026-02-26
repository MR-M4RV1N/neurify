import { initImageUpload } from './init-image-upload';

export function initSimpleForm() {
    initImageUpload({
        deleteUrlPrefix: '/cpanel/simple/images/',
        uploadUrl: '/cpanel/simple/dropzone',
    });
}