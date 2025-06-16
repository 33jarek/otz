const showFullListBtns = document.querySelectorAll('.btn-show-list');
const fullLists = document.querySelectorAll('.builds-list');

showFullListBtns.forEach((btn, i) => {
    btn.addEventListener('click', () => {
        fullLists[i].classList.add('shown');
    });
});