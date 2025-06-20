const btns = document.querySelectorAll('#nav-bar .btns button');
const tabs = document.querySelectorAll('.form-panel');

btns.forEach((btn, i) => {
    btn.addEventListener('click', () => {
        tabs.forEach((tab, j) => {
            if(i === j) {
                tab.style.display = 'flex';
                btns[j].classList.add('selected-tab');
            } else {
                tab.style.display = 'none';
                btns[j].classList.remove('selected-tab');
            }
        });
    });
});