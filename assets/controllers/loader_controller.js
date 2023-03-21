import {Controller} from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    show(event) {
        this.element.classList.add('overlay');
    }
}
