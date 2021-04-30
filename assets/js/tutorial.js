import addFormToCollection from './components/_collectionType'

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.add_item_link').forEach((el) => {
        el.addEventListener('click', (e) => {
            addFormToCollection(e.target.dataset.collectionholderclass)
        })
    })
    document.querySelectorAll('.js-delete-row').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            let {id} = btn.dataset;
            document.querySelector(`#supply_${id}`).remove()
        })
    })
})

