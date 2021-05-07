require('./../scss/moderation_chat.scss')
import axios from 'axios'

let form = document.querySelector('#moderation-message-form');
let input = form.querySelector('input')
let {moderation} = form.dataset;
let messages = document.querySelector('#messages');

form.addEventListener('submit', (e) => {
    e.preventDefault()

    axios.post(`/moderation/message/${moderation}`, new FormData(e.target)).then((response) => {
        messages.innerHTML = response.data
        input.value = null
        messages.scrollTop = messages.scrollHeight;
    })
})

setInterval(() => {
    axios.get(`/moderation/message/${moderation}/read`).then((response) => {
        messages.innerHTML = response.data
        messages.scrollTop = messages.scrollHeight;

    })
}, 10000)
