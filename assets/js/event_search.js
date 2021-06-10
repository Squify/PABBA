import 'jquery-datetimepicker/';
import moment from 'moment';
import SearchForm from './components/SearchForm'

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
}

document.addEventListener('DOMContentLoaded', () => {
    let s = new SearchForm('/evenement/search', 'event', initForm)
    s.loadData();
});
