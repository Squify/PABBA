import 'jquery-datetimepicker/';
import moment from 'moment';

$(function () {

    let rentAt = $("#item_borrow_rentAt")
    let returnAt = $("#item_borrow_returnAt")

    let dateReturnAt = null
    let dateRentAt = null

    rentAt.datetimepicker({
        format: 'd/m/Y H:i',
        minDate: moment()
    });
    returnAt.datetimepicker({
        format: 'd/m/Y H:i',
        minDate: moment()
    });

    rentAt.on('change', (e) => {
        let now = moment();
        dateRentAt = moment(rentAt.val(), "DD/MM/YYYY HH:mm")

        // Si la date entrée est antérieure à maintenant on la remplace par maintenant
        if (dateRentAt.isBefore(now)) {
            rentAt.val(now.format("DD/MM/YYYY HH:mm"))
        }

        // Si la date entrée est supérieure à la date de retour actuelle on change cette dernière
        if (dateReturnAt != null && dateReturnAt.isBefore(dateRentAt)) {
            returnAt.val(dateRentAt.format("DD/MM/YYYY HH:mm"))
        }

    });


    returnAt.on('change', (e) => {
        dateReturnAt = moment(returnAt.val(), "DD/MM/YYYY HH:mm")
        // dateReturnAt = moment(returnAt.val(), "DD/MM/YYYY HH:mm").format("YYYY/MM/DD HH:mm")

        // Si la date entrée est antérieure à la date de prêt saisie on la remplace par cette dernière
        if (dateRentAt != null && dateReturnAt.isBefore(dateRentAt)) {
            returnAt.val(dateRentAt.format("DD/MM/YYYY HH:mm"))
        }

    });

})