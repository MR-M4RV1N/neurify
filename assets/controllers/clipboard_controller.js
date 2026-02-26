import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['source', 'button', 'feedback'];

    copy() {
        var text = (this.sourceTarget.innerText || this.sourceTarget.textContent || '').trim();
        var self = this;

        function ok() { self.showFeedback('Copied!'); }

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(ok).catch(function () {
                self.fallbackCopy(text, ok);
            });
        } else {
            this.fallbackCopy(text, ok);
        }
    }

    fallbackCopy(text, done) {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.setAttribute('readonly', '');
        ta.style.position = 'absolute';
        ta.style.left = '-9999px';
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); } catch (e) { /* ignore */ }
        document.body.removeChild(ta);
        if (typeof done === 'function') done();
    }

    showFeedback(message) {
        if (this.hasFeedbackTarget) {
            this.feedbackTarget.textContent = message;
            this.feedbackTarget.classList.remove('d-none');
            var self = this;
            setTimeout(function () {
                self.feedbackTarget.classList.add('d-none');
            }, 1500);
            return;
        }

        if (this.hasButtonTarget) {
            var original = this.buttonTarget.innerHTML;
            this.buttonTarget.innerHTML = '<i class="far fa-check-circle" aria-hidden="true"></i> ' + message;
            var self = this;
            setTimeout(function () {
                self.buttonTarget.innerHTML = original;
            }, 1500);
        }
    }
}