import { ApplicationController, useDebounce } from 'stimulus-use'

export default class extends ApplicationController {
    static debounces = ['add']

    connect() {
        useDebounce(this, { wait: 300 });
    }

    add(event) {
        event.target.form.submit.click();
    }
}
