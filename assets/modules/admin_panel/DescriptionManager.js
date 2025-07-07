// ===================
// DESCRIPTION MANAGER
// ===================

export class DescriptionManager {
    constructor() {
        this.dropPanel = document.querySelector('.drop-panel');
        this.formatPanel = document.querySelector('.format-menu');
        this.openFormatPanel = document.querySelector('.add-drop-item');
        this.droppables = document.querySelectorAll('.drop-element');
        this.previewContainer = document.querySelector('.details-holder');
        
        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    // EVENT LISTENERS
    setupEventListeners() {
        // Format panel button
        if (this.openFormatPanel) {
            this.openFormatPanel.addEventListener('click', () => {
                this.formatPanel?.classList.toggle('opened');
            });
        }

        this.setupDragAndDrop(); // Drag and drop system

        // Form submission
        const form = document.getElementById('edit-perks');
        if (form) {
            form.addEventListener('submit', () => {
                const html = this.serializeDescription();
                const perkDescField = document.getElementById('perk-desc');
                if (perkDescField) perkDescField.value = html;
            });
        }
    }

    setupDragAndDrop() {
        // Setup draggable elements
        this.droppables.forEach((elm) => {
            elm.addEventListener('dragstart', (e) => {
                e.dataTransfer.setData('text/plain', elm.id);
            });
        });

        // Setup drop zones
        this.setupDropZone(this.dropPanel, (e) => this.handleMainDrop(e));
    }

    setupDropZone(element, dropHandler) {
        if (!element) return;

        element.addEventListener('dragover', (e) => {
            e.preventDefault();
            element.style.backgroundColor = 'hsl(0, 0%, 18%)';
        });

        element.addEventListener('dragleave', () => {
            element.style.backgroundColor = element === this.dropPanel ? 'hsl(0, 0%, 15%)' : '';
        });

        element.addEventListener('drop', dropHandler);
    }

    // BLOCK CREATION AND MANAGEMENT
    createBlock(type, previewTarget = null, initialValue = '') {
        const target = previewTarget || this.previewContainer;
        const blockData = this.getBlockConfig(type);
        
        const content = this.createBlockContent(blockData, target);
        const wrapper = this.createBlockWrapper(type, content.preview);
        
        // Set initial value if provided
        if (initialValue && content.element) {
            content.element.value = initialValue;
            content.preview.innerHTML = initialValue;
        }
        
        wrapper.appendChild(content.element);
        return { wrapper, content };
    }

    getBlockConfig(type) {
        const configs = {
            'new-line': { tag: 'p', class: '', element: 'input' },
            'note': { tag: 'p', class: 'note', element: 'input' },
            'quote': { tag: 'p', class: 'quote', element: 'input' },
            'list': { tag: 'ul', class: '', element: 'ul' }
        };
        return configs[type] || configs['new-line'];
    }

    createBlockContent(config, previewTarget) {
        if (config.element === 'input') {
            return this.createInputBlock(config, previewTarget);
        } else if (config.element === 'ul') {
            return this.createListBlock(previewTarget);
        }
    }

    createInputBlock(config, previewTarget) {
        const input = document.createElement('input');
        input.type = 'text';
        input.dataset.asHtml = config.tag;
        if (config.class) input.dataset.class = config.class;

        const preview = document.createElement(config.tag);
        if (config.class) preview.classList.add(config.class);
        previewTarget.appendChild(preview);

        this.syncInputToPreview(input, preview);
        return { element: input, preview: preview };
    }

    createListBlock(previewTarget) {
        const ul = document.createElement('ul');
        ul.dataset.asHtml = 'ul';

        const ulPreview = document.createElement('ul');
        previewTarget.appendChild(ulPreview);

        const controls = this.createListControls(ul, ulPreview);
        ul.appendChild(controls.container);

        return { element: ul, preview: ulPreview, controls: controls };
    }

    createListControls(ul, ulPreview) {
        const container = document.createElement('div');
        container.classList.add('btns-holder');

        let count = 0;
        const limit = 10;
        const counter = document.createElement('span');
        counter.classList.add('list-counter');
        counter.textContent = '0';

        const btnRemove = this.createButton('<i class="ri-subtract-line"></i>', () => {
            if (count > 0) {
                ul.querySelector('li:last-child')?.remove();
                ulPreview.querySelector('li:last-child')?.remove();
                counter.textContent = --count;
            }
        });

        const btnAdd = this.createButton('<i class="ri-add-large-line"></i>', () => {
            if (count < limit) {
                const { li, liPreview } = this.createListItem(ul, ulPreview);
                counter.textContent = ++count;
            }
        });

        container.append(btnRemove, counter, btnAdd);
        return { container, counter, btnAdd, btnRemove };
    }

    createListItem(ul, ulPreview) {
        const li = document.createElement('li');
        const liPreview = document.createElement('li');

        ul.appendChild(li);
        ulPreview.appendChild(liPreview);

        this.setupDropZone(li, (e) => this.handleListItemDrop(e, li, liPreview));
        return { li, liPreview };
    }

    createButton(innerHTML, clickHandler) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.innerHTML = innerHTML;
        btn.addEventListener('click', clickHandler);
        return btn;
    }

    createBlockWrapper(type, previewRef) {
        const wrapper = document.createElement('div');
        wrapper.classList.add('dropped-holder');

        const name = document.createElement('p');
        name.innerText = type;

        const closeBtn = this.createButton('<i class="ri-close-large-fill"></i>', () => {
            wrapper.remove();
            previewRef?.remove();
        });
        closeBtn.classList.add('holder-close-btn');

        wrapper.append(name, closeBtn);
        return wrapper;
    }

    syncInputToPreview(input, preview) {
        input.addEventListener('input', () => {
            preview.innerHTML = input.value;
        });
    }

    // DROP HANDLERS
    handleMainDrop(e) {
        e.preventDefault();
        e.stopPropagation();

        const { type, dropClass } = this.getDropData(e);
        const { wrapper } = this.createBlock(type);
        
        if (dropClass) wrapper.classList.add(dropClass);
        this.dropPanel.appendChild(wrapper);
        this.dropPanel.style.backgroundColor = 'hsl(0, 0%, 15%)';
    }

    handleListItemDrop(e, li, liPreview) {
        e.preventDefault();
        e.stopPropagation();

        const { type, dropClass } = this.getDropData(e);
        const { wrapper } = this.createBlock(type, liPreview);
        
        if (dropClass) wrapper.classList.add(dropClass);
        li.appendChild(wrapper);
        li.style.backgroundColor = '';
    }

    getDropData(e) {
        const id = e.dataTransfer.getData('text/plain');
        const dragged = document.getElementById(id);
        return {
            type: dragged.dataset.elmName,
            dropClass: dragged.dataset.elmClass || null
        };
    }

    // SERIALIZATION
    serializeDescription() {
        const blocks = Array.from(this.dropPanel.querySelectorAll(':scope > .dropped-holder'));
        return blocks.map(block => this.serializeBlock(block)).join('');
    }

    serializeBlock(block) {
        const ul = block.querySelector(':scope > ul[data-as-html]');
        if (ul) return this.serializeList(ul);

        const input = block.querySelector('input');
        if (input) return this.serializeInput(input);

        return '';
    }

    serializeInput(input) {
        const tag = input.dataset.asHtml || 'p';
        const className = input.dataset.class || '';
        const value = input.value || '';
        const classAttr = className ? ` class="${className}"` : '';
        return `<${tag}${classAttr}>${value}</${tag}>`;
    }

    serializeList(ulElement) {
        const items = ulElement.querySelectorAll(':scope > li');
        const itemsHTML = Array.from(items).map(li => {
            const innerBlocks = li.querySelectorAll(':scope > .dropped-holder');
            const innerHTML = Array.from(innerBlocks).map(block => this.serializeBlock(block)).join('');
            return `<li>${innerHTML}</li>`;
        }).join('');
        
        return `<ul>${itemsHTML}</ul>`;
    }

    // RECONSTRUCTION FROM HTML
    clearBlocks() {
        if (this.dropPanel) this.dropPanel.innerHTML = '';
        if (this.previewContainer) this.previewContainer.innerHTML = '';
    }

    reconstructFromHTML(htmlString) {
        this.clearBlocks();
        
        const tempContainer = document.createElement('div');
        tempContainer.innerHTML = htmlString;
        
        Array.from(tempContainer.children).forEach(element => {
            this.reconstructBlock(element);
        });
    }

    reconstructBlock(element, parentContainer = null, parentPreview = null) {
        const tagName = element.tagName.toLowerCase();
        const className = element.className;
        const innerHTML = element.innerHTML;
        
        const target = parentPreview || this.previewContainer;
        const container = parentContainer || this.dropPanel;
        
        let blockType = this.getBlockTypeFromElement(tagName, className);
        
        if (tagName === 'ul') {
            const { wrapper, content } = this.createBlock(blockType, target);
            container.appendChild(wrapper);
            
            // Reconstruct list items
            const listItems = Array.from(element.querySelectorAll('li'));
            listItems.forEach(li => {
                const btnAdd = content.controls.btnAdd;
                btnAdd.click(); // Create new list item
                
                const newLi = content.element.querySelector('li:last-child');
                const newLiPreview = content.preview.querySelector('li:last-child');
                
                Array.from(li.children).forEach(childElement => {
                    this.reconstructBlock(childElement, newLi, newLiPreview);
                });
            });
        } else {
            const { wrapper, content } = this.createBlock(blockType, target, innerHTML);
            container.appendChild(wrapper);
        }
    }

    getBlockTypeFromElement(tagName, className) {
        if (tagName === 'p') {
            if (className.includes('note')) return 'note';
            if (className.includes('quote')) return 'quote';
            return 'new-line';
        }
        if (tagName === 'ul') return 'list';
        return 'new-line';
    }
}