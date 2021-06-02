import axios from 'axios';
import 'jquery-datetimepicker/';
import moment from 'moment';

let initForm = () => {
    let eventAt = $('#event_search_eventAt');
    eventAt.datetimepicker({
        format: 'd/m/Y',
        minDate: moment()
    });

    eventAt.on('change', () => {
        let now = moment();

        // Si la date entrée est antérieure à maintenant on la remplace par maintenant
        if (moment(eventAt.val(), 'DD/MM/YYYY').isBefore(now)) {
            eventAt.val(now.format('DD/MM/YYYY'));
        }
    });
};

let loadData = (form = null) => {
    axios.post('/evenement/search', form).then((response) => {
        document.querySelector(
            '#events-list').innerHTML = response.data.content;
        document.querySelector(
            '#filter-content').innerHTML = response.data.form;
        initForm();
        let form = document.querySelector('form[name="event_search"]');
        if (form.length > 0) {
            form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    loadData(new FormData(form));
                    document.querySelector('#close-filter').click()
                }).
                catch((err) => {
                });
        }
    }).catch((err) => {
    });
};

document.addEventListener('DOMContentLoaded', () => {
    loadData();
});
