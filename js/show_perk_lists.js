const showFullListBtns = document.querySelectorAll('.btn-show-list');
const fullLists = document.querySelectorAll('.builds-list');
const dialogBtns = document.querySelectorAll('.build-list button');
const perkDetailsDialog = document.querySelector('.perk-display');
const dialogDetails = document.querySelector('.perk-display .dialog-form .form-content');


function showLoading(element) {
    element.innerHTML = 'LOADING...';
}
function showDialogWindow(element) {
    showLoading(dialogDetails);
    element.showModal();
}

document.addEventListener('click', (e) => {
    if(e.target.classList.contains('btn-show-list')) {
        const index = Array.from(showFullListBtns).indexOf(e.target);
        fullLists[index].showModal();

        fullLists[index].appendChild(newCursor);
    };
    if(e.target.classList.contains('close-dialog-btn')) {
        document.body.appendChild(newCursor);
    }
    if(e.target.classList.contains('perk-icon')) {
        requestXMLHttp(altImgText(e.target.alt), dialogDetails, 'return_perk_info.php', showDialogWindow(perkDetailsDialog));
    }
});