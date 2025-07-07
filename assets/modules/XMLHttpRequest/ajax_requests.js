// Main ajax request sending function
function ajaxRequest(sourceOrName, target, file, callback = null) {
    let dataString;
    // If is DOM element
    if (typeof sourceOrName === 'object' && sourceOrName !== null && 'value' in sourceOrName && 'getAttribute' in sourceOrName) {
        const value = sourceOrName.value;
        const name = sourceOrName.getAttribute('name');
        dataString = `${name}=${encodeURIComponent(value)}`;
    } else {
        // Else as direct string
        dataString = `xml=${encodeURIComponent(sourceOrName)}`;
    }
    const XMLreq = new XMLHttpRequest();
    XMLreq.open('POST', `/dbd/assets/modules/XMLHttpRequest/${file}`, true);
    XMLreq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    XMLreq.onload = () => {
        if (target) target.innerHTML = XMLreq.responseText;
        if (typeof callback === 'function') callback(XMLreq.responseText);
    };
    XMLreq.send(dataString);
}

function clearDropPanel() {
    const dropPanel = document.querySelector('.drop-panel');
    const perkDetails = document.querySelector('.details-holder');
    
    if (dropPanel) {
        dropPanel.innerHTML = '';
    }
    if (perkDetails) {
        perkDetails.innerHTML = '';
    }
}

function reconstructBlocksFromHTML(htmlString) {
    const dropPanel = document.querySelector('.drop-panel');
    const perkDetails = document.querySelector('.details-holder');
    
    if (!dropPanel || !perkDetails) return;
    
    clearDropPanel();
    
    const tempContainer = document.createElement('div');
    tempContainer.innerHTML = htmlString;
    
    Array.from(tempContainer.children).forEach(element => {
        reconstructBlock(element, dropPanel, perkDetails);
    });
}

function reconstructBlock(element, dropPanel, previewContainer) {
    const tagName = element.tagName.toLowerCase();
    const className = element.className;
    const innerHTML = element.innerHTML;
    
    let blockType, content;
    
    if (tagName === 'p') {
        if (className.includes('note')) {
            blockType = 'note';
        } else if (className.includes('quote')) {
            blockType = 'quote';
        } else {
            blockType = 'new-line';
        }
        
        content = createElementContent(blockType, previewContainer);
        
        content.element.value = innerHTML;
        content.preview.innerHTML = innerHTML;
        
    } else if (tagName === 'ul') {
        blockType = 'list';
        content = createElementContent(blockType, previewContainer);
        
        const listItems = Array.from(element.querySelectorAll('li'));
        listItems.forEach(li => {
            const btnAdd = content.element.querySelector('.btns-holder button:last-child');
            if (btnAdd) {
                btnAdd.click(); // This will create a new li element
                
                const newLi = content.element.querySelector('li:last-child');
                const newLiPreview = content.preview.querySelector('li:last-child');
                
                Array.from(li.children).forEach(childElement => {
                    reconstructBlock(childElement, newLi, newLiPreview);
                });
            }
        });
    }
    
    if (content) {
        const dropElement = createDropElement(blockType, content.preview);
        dropElement.appendChild(content.element);
        dropPanel.appendChild(dropElement);
    }
}

// LISTEN TO KILLERS/SURVS SELECT AND RETRIEVE ALL CHARACTERS
const selectChars = document.querySelector('#build-inserter #select-chars');
const charNames = document.querySelector('#build-inserter #chars-and-names');
if(selectChars && charNames) selectChars.addEventListener('change', () => ajaxRequest(selectChars, charNames, 'getCharacters.php'));

// LISTEN TO KILLERS/SURVS SELECT AND RETRIEVE ALL BUILDS
const selectBuilds = document.querySelector('#build-remover #builds-select');
const tBody = document.querySelector('#build-table-holder table tbody');
if(selectBuilds && tBody) selectBuilds.addEventListener('change', () => ajaxRequest(selectBuilds, tBody, 'getBuilds.php'));

function loadPerkDetails() {
    ajaxRequest(selectPerk, null, 'getPerkDetails.php', (response) => {
        const data = JSON.parse(response);
        inputName.value = data.name;
        inputObtainment.value = data.obtainment;
        
        reconstructBlocksFromHTML(data.description);
        
        inputName.dispatchEvent(new Event('input'));
        inputObtainment.dispatchEvent(new Event('input'));
    });
}

const selectPerk = document.querySelector('#perk-details');
const inputName = document.querySelector('#perk-name');
const inputObtainment = document.querySelector('#perk-obtainment');
if(selectPerk && inputName && inputObtainment) {
    if (selectPerk.options.length > 0) { // Load for the first option
        selectPerk.selectedIndex = 0;
        loadPerkDetails();
    }
    
    selectPerk.addEventListener('change', loadPerkDetails);
}