import toastr from 'toastr';
require ('../scss/_alert.scss')

document.querySelectorAll('.js-alert').forEach((alert) => {
    const {type, message} = alert.dataset;
    if (type === 'success'){
        toastr.success(message, null, {
            timeOut: 3000,
            position: 'toast-top-right'
        })
    }else if (type === 'error'){
        toastr.error(message, null, {
            timeOut: 3000,
            position: 'toast-top-right'
        })
    } else{
        toastr.info(message, null, {
            timeOut: 3000,
            position: 'toast-top-right'
        })
    }
})
