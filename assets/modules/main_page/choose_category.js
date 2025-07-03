const btns = document.querySelectorAll('#categories-list .category-select');
const hdr = document.querySelector('#hdr');
const categories = document.querySelectorAll('.category-section');

btns.forEach((btn, i) => {
    btn.addEventListener('click', () => {
        gsap.to(hdr, {
            clipPath: 'polygon(0 0, 100% 0, 100% 0%, 0 0%)'
        });
        categories.forEach((category, j) => {
            i === j ? category.style.display = 'grid' : category.style.display = 'none';
        });
    });
});