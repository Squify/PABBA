import axios from 'axios';

let CommentTutorial = class CommentTutorial {

    constructor(form) {
        this.form = form;
        this._handler()
    }

    _handler(){
        let id = document.querySelector('#comment_tutorial_id').value;
        this.form.addEventListener('submit', (e) => {
            e.preventDefault()
            axios.post('/comment/tutorial/process' + (id ? `?id=${id}` : ''), new FormData(this.form)).then((response) => {
                document.querySelector('#tutorial_comments').innerHTML = response.data
                $('#modalComment').modal('hide')
                document.querySelector('#add_comment').remove()
            })
        })
    }




    // modal update Submit
    // prevent default
    // ajax update
    // update tout les commentaires
}

document.addEventListener('DOMContentLoaded', function() {
   new CommentTutorial(document.querySelector('#form_tutorial_comment'))
})


