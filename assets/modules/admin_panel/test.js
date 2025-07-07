export function loadDescriptionFromHTML(htmlString) {
    const parser = new DOMParser();
    const doc = parser.parseFromString(htmlString, 'text/html');
    const body = doc.body;

    const dropPanel = document.querySelector('.drop-panel');
    dropPanel.innerHTML = ''; // wyczyść panel

    function parseNode(node, previewTarget = null, parentContainer = dropPanel) {
        if (node.nodeType !== Node.ELEMENT_NODE) return;

        const tag = node.tagName.toLowerCase();
        const className = node.className || '';

        if (tag === 'p') {
            let type = 'new-line';
            if (className === 'note') type = 'note';
            if (className === 'quote') type = 'quote';

            const content = createElementContent(type, previewTarget);
            content.element.value = node.textContent;

            const dropElement = createDropElement(type, content.preview);
            if (className) dropElement.classList.add(className);
            dropElement.appendChild(content.element);
            parentContainer.appendChild(dropElement);
        }

        else if (tag === 'ul') {
            const content = createElementContent('list', previewTarget);
            const ul = content.element;
            const ulPreview = content.preview;

            parentContainer.appendChild(createDropElement('list', ulPreview).appendChild(ul).parentElement);

            const lis = node.querySelectorAll(':scope > li');
            lis.forEach(liNode => {
                const li = document.createElement('li');
                const liPreview = document.createElement('li');

                ul.appendChild(li);
                ulPreview.appendChild(liPreview);

                // Odtwarzamy wnętrze li
                [...liNode.childNodes].forEach(child => {
                    parseNode(child, liPreview, li);
                });
            });
        }
    }

    // Przechodzimy przez wszystkie główne elementy
    [...body.children].forEach(child => {
        parseNode(child);
    });
}
