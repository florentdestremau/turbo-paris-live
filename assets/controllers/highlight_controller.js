import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static values = {search: String}

    connect() {
        if (this.searchValue) {
            const elementText = this.element.textContent || this.element.innerText;
            const re = new RegExp(this.searchValue, "ig");
            this.element.innerHTML = elementText.replace(re, (match) => {
                const startIndex = elementText.toLowerCase().indexOf(match.toLowerCase());
                const endIndex = startIndex + match.length;
                const originalText = elementText.substring(startIndex, endIndex);
                return `<mark>${originalText}</mark>`;
            });

        }
    }
}
