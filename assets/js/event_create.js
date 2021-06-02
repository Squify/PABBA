import 'jquery-datetimepicker/';
import moment from 'moment';

$(function () {
    let eventAt = $("#event_eventAt");
    eventAt.datetimepicker({
        format: 'd/m/Y H:i',
        minDate: moment()
    })

    eventAt.on('change', ()=>{
        let now = moment ()

        // Si la date entrée est antérieure à maintenant on la remplace par maintenant
        if (moment(eventAt.val(), "DD/MM/YYYY HH:mm").isBefore(now)) {
            eventAt.val(now.format("DD/MM/YYYY HH:mm"))
        }
    })
})
