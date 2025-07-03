const newCursor = document.createElement('div');
const cursorText = document.createElement('p');
newCursor.id = "dynamic-cursor";
newCursor.appendChild(cursorText);
document.body.appendChild(newCursor);

function calculateCursorPos(e) {
    const rect = e.target.getBoundingClientRect();
    gsap.to(newCursor, {
        x: e.clientX + 10,
        y: e.clientY + 10,
        duration: .1,
        ease: "power3.out"
    });
}
window.addEventListener('mousemove', calculateCursorPos);

function altImgText(text) {
    return text.replace(' icon', '');
}
const perkIcons = gsap.utils.toArray('.perk-icon');
perkIcons.forEach((perkIcon) => {
    Observer.create({
        target: perkIcon,
        type: 'pointer',
        onHover: (e) => {
            if(e.target === perkIcon) cursorText.innerText = altImgText(perkIcon.alt);
            newCursor.classList.add('shown');
        },
        onHoverEnd: () => {
            newCursor.classList.remove('shown');
        },
    });
})