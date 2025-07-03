const formatBtns = document.querySelectorAll('.format-options .format-element');
const perkNameInput = document.querySelector('#perk-name');

let lastFocusedInput = null;

function encodePerkName(perkName) {
    const spaceToUnderscore = perkName.replace(/ /g, '_');
    const encoded = encodeURIComponent(spaceToUnderscore);
    return encoded;
};

function wrapInputSelection(input, tag, className) {
    const start = input.selectionStart;
    const end = input.selectionEnd;
    const value = input.value;

    if(start === end) return; // Nothing was selected

    const beforeText = value.slice(0, start);
    const selected = value.slice(start, end);
    const afterText = value.slice(end);

    const perkName = encodePerkName(perkNameInput.value);
    const href = tag === 'a' ? ` href="https://deadbydaylight.wiki.gg/wiki/${perkName}"` : '';
    const target = tag === 'a' ? ' target="_blank"' : '';

    const wrapped = `<${tag}${href} class="${className}"${target}>${selected}</${tag}>`;
    input.value = beforeText + wrapped + afterText;

    input.focus();
    input.setSelectionRange(start, start + wrapped.length);
    input.dispatchEvent(new Event('input'));
}

document.addEventListener('focusin', (e) => {
    if (e.target.tagName === 'INPUT') {
        lastFocusedInput = e.target;
    };
});

formatBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        if(!lastFocusedInput) return;

        const tag = btn.classList.contains('link') ? 'a' : 'span';
        const className = [...btn.classList].find(cls => cls !== 'format-element');

        wrapInputSelection(lastFocusedInput, tag, className);
    });
});