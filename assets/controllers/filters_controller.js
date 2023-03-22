import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['form', 'content'];

    submit() {
        this.contentTarget.classList.add('overlay');
        window.history.pushState('', '', window.location.href.split('?')[0]);
        const entries = [...new FormData(this.formTarget).entries()].filter(([, v]) => v.length > 0);
        const params = entries.length > 0 ? '?' + entries.map(e => e.map(encodeURIComponent).join('=')).join('&') : '';
        window.history.pushState('', '', window.location.href + params);
    }
}
