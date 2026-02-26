export function initComments() {
    document.querySelectorAll('.btn-reply').forEach(button => {
        button.addEventListener('click', function () {
            const commentId = this.getAttribute('data-comment-id');
            const commentText = this.closest('.blog-comments__content')
                .querySelector('p.comment-text')
                .textContent.trim();

            const form = this.closest('.card-body').querySelector('.comment-form');
            const replyContainer = form.querySelector('.reply-container');
            const parentInput = form.querySelector('#parentCommentId');

            // Очистка предыдущих ответов
            replyContainer.innerHTML = '';
            parentInput.value = commentId;

            // Добавление видимого input с текстом родительского комментария
            const replyInput = document.createElement('input');
            replyInput.type = 'text';
            replyInput.name = 'replyToCommentText';
            replyInput.value = `Ответ на комментарий: ${commentText}`;
            replyInput.readOnly = true;
            replyInput.classList.add('form-control');

            replyContainer.appendChild(replyInput);
        });
    });
}