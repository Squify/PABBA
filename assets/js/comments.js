import axios from 'axios';
import toastr from 'toastr';

let CommentTutorial = class CommentTutorial {

    _handler(id){
        this.form.addEventListener('submit', (e) => {
            e.preventDefault()
            axios.post('/comment/tutorial/process' + (id ? `?id=${id}` : ''), new FormData(this.form)).then((response) => {
                document.querySelector('#tutorial_comments').innerHTML = response.data
                $('#modalComment').modal('hide')
                if (!id){
                    document.querySelector('#add_comment').remove()
                }
                listener()
                toastr.success("Votre commentaire a bien été enregistré", null, {
                    timeOut: 3000,
                    position: 'toast-top-right'
                })
            })
        })
    }

    loadForm(id = null, tutorial = null){
        axios.get('/comment/tutorial/form' + (id ? `?id=${id}` : '') + (tutorial ? `?tutorial=${tutorial}` : '')).then((response) => {
            document.querySelector('#comment-modal-body').innerHTML = response.data
            this.form = document.querySelector('#form_tutorial_comment');
            this._handler(id)
        })
    }




    // modal update Submit
    // prevent default
    // ajax update
    // update tout les commentaires
}

let listener = () => {
    document.querySelectorAll('.js-tutorial-comment-edit, #add_comment').forEach((el) => {
        let {id, tutorial} = el.dataset;
        el.addEventListener('click', () => {
            let commentTutorial = new CommentTutorial();
            commentTutorial.loadForm(id,tutorial)
        })
    })
}
document.addEventListener('DOMContentLoaded', function() {
   listener()
})


