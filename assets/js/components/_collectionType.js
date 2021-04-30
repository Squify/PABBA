let deleteFormOfCollection = (container, index) => {
    let deleteDiv = document.createElement('div');
    deleteDiv.classList.add('col-1')

    let deleteBtn = document.createElement('button');
    deleteBtn.classList.add('btn','btn-danger','js-delete-row')
    deleteBtn.dataset.id = index

    let i = document.createElement('i');
    i.classList.add('fas','fa-trash')
    i.dataset.id = index

    deleteBtn.appendChild(i)
    deleteDiv.appendChild(deleteBtn)
    container.appendChild(deleteDiv);

    deleteBtn.addEventListener('click', (e) => {
        container.remove()
        e.preventDefault()
    })
}

export default (collectionHolderId) => {
    let collectionHolder = document.querySelector(`#${collectionHolderId}`)

    let {prototype, index, identifier} = collectionHolder.dataset;

    index = parseInt(index) + 1;

    let newForm = prototype;
    newForm = newForm.replace(/__name__/g, Date.now());
    collectionHolder.dataset.index = parseInt(index);

    let newFormRow = document.createElement('div');
    newFormRow.classList.add('row','p-2','rounded','mb-1')
    newFormRow.id = `${identifier}_${index}`

    let inputDiv = document.createElement('div');
    inputDiv.classList.add('col-10')
    inputDiv.innerHTML = newForm;

    newFormRow.appendChild(inputDiv);

    newFormRow.classList.add('form-floating', 'form-group')

    deleteFormOfCollection(newFormRow, index)

    collectionHolder.appendChild(newFormRow);

}
