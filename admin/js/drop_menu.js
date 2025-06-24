const openFormatPanel = document.querySelector('.add-drop-item');
const formatPanel = document.querySelector('.format-menu');
const dropPanel = document.querySelector('.drop-panel');
const droppables = document.querySelectorAll('.drop-element');

openFormatPanel.addEventListener('click', () => {
    formatPanel.classList.toggle('opened');
});

function createPreviewElement(tag = 'p', className = '') {
    const elm = document.createElement(tag);
    if(className) elm.classList.add(className);
    return elm;
};

function syncInputToPreview(input, preview) {
    input.addEventListener('input', () => {
        preview.innerHTML = input.value;
    });
};

function createDropElement(label, previewRef) {
    const wrapper = document.createElement('div');
    wrapper.classList.add('dropped-holder');

    const name = document.createElement('p');
    name.innerText = label;

    const closeBtn = document.createElement('button');
    closeBtn.classList.add('holder-close-btn');
    closeBtn.type = 'button';
    closeBtn.innerHTML = '<i class="ri-close-large-fill"></i>';

    closeBtn.addEventListener('click', () => {
        wrapper.remove();
        previewRef?.remove();
    });

    wrapper.append(name, closeBtn);
    return wrapper;
};

function createElementContent(type, previewTarget = perkDetails) {
    if(type === 'new-line') {
        const input = document.createElement('input');
        input.type = 'text';
        input.dataset.asHtml = 'p';

        const preview = document.createElement('p');
        previewTarget.appendChild(preview);

        syncInputToPreview(input, preview);
        return { element: input, preview: preview };
    };

    if(type === 'note' || type === 'quote') {
        const input = document.createElement('input');
        input.type = 'text';
        input.dataset.asHtml = 'p';
        input.dataset.class = type;

        const preview = document.createElement('p');
        preview.classList.add(type);
        previewTarget.appendChild(preview);

        syncInputToPreview(input, preview);
        return { element: input, preview: preview };
    };

    if(type === 'list') {
        const ul = document.createElement('ul');
        ul.dataset.asHtml = 'ul';

        const btnsHolder = document.createElement('div');
        btnsHolder.classList.add('btns-holder');
        ul.appendChild(btnsHolder);

        const ulPreview = document.createElement('ul');
        previewTarget.appendChild(ulPreview);

        let count = 0, limit = 10;
        const counter = document.createElement('span');
        counter.classList.add('list-counter');
        counter.textContent = '0';

        const [btnRemove, btnAdd] = ['-', '+'].map(symbol => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.innerHTML = symbol === '+' ? '<i class="ri-add-large-line"></i>' : '<i class="ri-subtract-line"></i>';
            return btn;
        });

        btnRemove.addEventListener('click', () => {
            if(count > 0) {
                ul.querySelector('li:last-child')?.remove();
                ulPreview.querySelector('li:last-child')?.remove();
                counter.textContent = --count;
            };
        });
        btnAdd.addEventListener('click', () => {
            if(count < limit) {
                const li = document.createElement('li');
                const liPreview = document.createElement('li');

                ul.appendChild(li);
                ulPreview.appendChild(liPreview);

                li.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    li.style.backgroundColor = 'hsl(0, 0%, 18%)';
                });
                li.addEventListener('dragleave', () => {
                    li.style.backgroundColor = '';
                });
                li.addEventListener('drop', (e) => {
                    e.preventDefault();
                    e.stopPropagation();

                    const id = e.dataTransfer.getData('text/plain', e.id);
                    const dragged = document.getElementById(id);
                    const type = dragged.dataset.elmName;
                    const dropClass = dragged.dataset.elmClass ?? null;

                    const content = createElementContent(type, liPreview);
                    const dropElement = createDropElement(type, content.preview);
                    if (dropClass) dropElement.classList.add(dropClass);
                    dropElement.appendChild(content.element);

                    li.appendChild(dropElement);
                    li.style.backgroundColor = '';
                });

                counter.textContent = ++count;
            };
        });

        btnsHolder.append(btnRemove, counter, btnAdd);
        return { element: ul, preview: ulPreview };
    };

    // If nothing returns back
    return { element: document.createElement('div'), preview: null };
}

droppables.forEach((elm) => {
    elm.addEventListener('dragstart', (e) => {
        e.dataTransfer.setData('text/plain', elm.id);
    });
    elm.addEventListener('dragend', () => {
        // console.log('drag ended');
    });
});
dropPanel.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropPanel.style.backgroundColor = 'hsl(0, 0%, 18%)';
});
dropPanel.addEventListener('dragleave', () => {
    dropPanel.style.backgroundColor = 'hsl(0, 0%, 15%)';
});
dropPanel.addEventListener('drop', (e) => {
    e.preventDefault();
    e.stopPropagation();
    
    console.log('DROPPED');

    const id = e.dataTransfer.getData('text/plain', e.id);
    const dropped = document.getElementById(id);
    const type = dropped.dataset.elmName;
    const dropClass = dropped.dataset.elmClass || null;
    
    const content = createElementContent(type);
    const drop = createDropElement(type, content.preview);
    if(dropClass) drop.classList.add(dropClass);
    drop.appendChild(content.element);
    dropPanel.appendChild(drop);

    dropPanel.style.backgroundColor = 'hsl(0, 0%, 15%)';
});