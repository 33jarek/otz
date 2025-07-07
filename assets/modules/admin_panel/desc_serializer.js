function serializeDescription() {
    const dropPanel = document.querySelector('.drop-panel');
    const blocks = Array.from(dropPanel.querySelectorAll(':scope > .dropped-holder'));

    let resultHTML = '';

    blocks.forEach(block => {
        resultHTML += serializeBlock(block);
    });

    return resultHTML;
}

function serializeBlock(block) {

    const ul = block.querySelector(':scope > ul[data-as-html]');
    if (ul) {
        return serializeList(ul);
    }

    const input = block.querySelector('input');
    if (input) {
        const tag = input.dataset.asHtml || 'p';
        const className = input.dataset.class || '';
        const value = input.value || '';
        const classAttr = className ? ` class="${className}"` : '';
        return `<${tag}${classAttr}>${value}</${tag}>`;
    }

    return '';
}

function serializeList(ulElement) {
    let ulHTML = '<ul>';

    const items = ulElement.querySelectorAll(':scope > li');
    items.forEach(li => {
        let liHTML = '';

        const innerBlocks = li.querySelectorAll(':scope > .dropped-holder');
        innerBlocks.forEach(innerBlock => {
            liHTML += serializeBlock(innerBlock);
        });

        ulHTML += `<li>${liHTML}</li>`;
    });

    ulHTML += '</ul>';
    return ulHTML;
}

const form = document.getElementById('edit-perks');
form.addEventListener('submit', () => {
    const html = serializeDescription();
    document.getElementById('perk-desc').value = html;
});