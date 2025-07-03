const powrot = document.querySelector('#powrot');
powrot.addEventListener('click', () => {
    gsap.to(hdr, {
        clipPath: 'polygon(0 0, 100% 0, 100% 100%, 0 100%)',
    });
});