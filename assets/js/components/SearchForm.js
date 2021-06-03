import axios from 'axios';

export default class {

    constructor(url, baseName, initForm = () => {}) {
        this.url = url;
        this.baseName = baseName;
        this.initForm = initForm
    }

    loadData(form) {
        axios.post(this.url, form).then((response) => {
            document.querySelector(
                `#${this.baseName}-list`).innerHTML = response.data.content;
            document.querySelector(
                '#filter-content').innerHTML = response.data.form;
            this.initForm();
            let form = document.querySelector(`form[name="${this.baseName}_search"]`);
            if (form.length > 0) {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.loadData(new FormData(form));
                    document.querySelector('#close-filter').click();
                }).catch((err) => {});
            }
        }).catch((err) => {});
    }

    initForm() {

    }
}
