function serializeDescription() {
    const dropPanel = document.querySelector('.drop-panel');
    const blocks = Array.from(dropPanel.children);

    let resultHTML = '';

    blocks.forEach(block => {
    // LISTA
    const list = block.querySelector('ul[data-as-html]');
    if (list) {
        let ulHTML = '<ul>';
        const items = list.querySelectorAll('li');
        items.forEach(li => {
            const input = li.querySelector('input');
            const tag = input?.dataset.asHtml || 'p';
            const value = input?.value || '';
            ulHTML += `<li><${tag}>${value}</${tag}></li>`;
        });

        ulHTML += '</ul>';
        resultHTML += ulHTML;
        return;
    }

    // POJEDYNCZY BLOK (np. p, note, quote)
    const input = block.querySelector('input');
    if (input) {
            const tag = input.dataset.asHtml || 'p';
            const className = input.dataset.class || '';
            const value = input.value || '';
            const classAttr = className ? ` class="${className}"` : '';
            resultHTML += `<${tag}${classAttr}>${value}</${tag}>`;
        }
    });

    return resultHTML;
}

const form = document.getElementById('edit-perks');
const test = document.querySelector('.change-perk-btn');
form.addEventListener('submit', () => {
    const html = serializeDescription();
    document.getElementById('perk-desc').value = html;
});