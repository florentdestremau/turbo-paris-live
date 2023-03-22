import { ApplicationController, useDebounce } from 'stimulus-use'

export default class extends ApplicationController {
    static debounces = ['submit']

    connect() {
        useDebounce(this, { wait: 300 });
    }

    submit(event) {
        event.target.form.submit.click();
    }
}
