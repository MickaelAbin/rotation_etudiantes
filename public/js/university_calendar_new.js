const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
    const item = document.createElement('div');
    const div = document.createElement('div');

    div.classList.add('row');
    item.classList.add('mb-3');

    item.innerHTML = collectionHolder.dataset.prototype.replace(/__name__/g, collectionHolder.dataset.index);


    div.appendChild(item);
    collectionHolder.appendChild(div);
    collectionHolder.dataset.index++;
}

document.querySelectorAll('.add_item_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormToCollection);
    })