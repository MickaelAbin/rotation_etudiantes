const addTagFormDeleteLink = (item) => {

    const faIcon = document.createElement('i')
    faIcon.classList.add('fa-lg', 'fa-solid', 'fa-xmark')
    faIcon.setAttribute('title', 'Supprimer')

    const removeFormButton = document.createElement('button')
    removeFormButton.appendChild(faIcon)
    removeFormButton.classList.add('btn', 'btn-danger', 'rounded')

    const col = document.createElement('div')
    col.appendChild(removeFormButton)
    col.classList.add('col-1')

    item.appendChild(col);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault()
        item.remove()
    })
}

const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass)
    const item = document.createElement('div')

    item.innerHTML = collectionHolder.dataset.prototype.replace(/__name__/g, collectionHolder.dataset.index)
    item.classList.add('row', 'gx-2')

    const col10 = item.firstChild
    col10.classList.add('row', 'col-11', 'gx-1')

    addTagFormDeleteLink(item)
    collectionHolder.appendChild(item)
    collectionHolder.dataset.index++
}

document.querySelectorAll('.add_item_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormToCollection)
    })
document.querySelectorAll('div.noRotationPeriods div.gx-2, div.publicHolidays div.gx-2')
    .forEach((element) => {
        addTagFormDeleteLink(element)
    })