import axios from 'axios';
import toastr from 'toastr';

let CommentEvent = class CommentEvent {

    _handler(id){
        this.form.addEventListener('submit', (e) => {
            e.preventDefault()
            axios.post('/comment/event/process' + (id ? `?id=${id}` : ''), new FormData(this.form)).then((response) => {
                document.querySelector('#event_comments').innerHTML = response.data
                $('#modalComment').modal('hide')
                listener()
                toastr.success("Votre commentaire a bien été enregistré", null, {
                    timeOut: 3000,
                    position: 'toast-top-right'
                })
            })
        })
    }

    loadForm(id = null, event = null){
        axios.get('/comment/event/form' + (id ? `?id=${id}` : '') + (event ? `?event=${event}` : '')).then((response) => {
            document.querySelector('#comment-modal-body').innerHTML = response.data
            this.form = document.querySelector('#form_event_comment');
            this._handler(id)
        })
    }

}

let listener = () => {
    document.querySelectorAll('.js-event-comment-edit, #add_comment').forEach((el) => {
        let {id, event} = el.dataset;
        el.addEventListener('click', () => {
            let commentEvent = new CommentEvent();
            commentEvent.loadForm(id,event)
        })
    })
}
document.addEventListener('DOMContentLoaded', function() {
   listener()
})


