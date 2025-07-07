import { DescriptionManager } from '../admin_panel/DescriptionManager.js';

const descriptionManager = new DescriptionManager();

// Main ajax request sending function
function ajaxRequest(sourceOrName, target, file, callback = null) {
    let dataString;
    
    if (typeof sourceOrName === 'object' && sourceOrName !== null && 'value' in sourceOrName && 'getAttribute' in sourceOrName) {
        // If is DOM element
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

// Listen to character type select in add builds, return corresponding characters
const charTypeSelect = document.querySelector('#build-inserter #select-chars');
const charNameSelect = document.querySelector('#build-inserter #chars-and-names');
if (charTypeSelect && charNameSelect) charTypeSelect.addEventListener('change', () => ajaxRequest(charTypeSelect, charNameSelect, 'getCharacters.php'));

// Listen to character type select in remove builds, return corresponding builds
const charTypeBuildSelect = document.querySelector('#build-remover #builds-select');
const tBody               = document.querySelector('#build-table-holder table tbody');
if (charTypeBuildSelect && tBody) charTypeBuildSelect.addEventListener('change', () => ajaxRequest(charTypeBuildSelect, tBody, 'getBuilds.php'));

// Listen to perk select in modify perks, return details about current perk
const perkSelect            = document.querySelector('#perk-details');
const perkNameInput         = document.querySelector('#perk-name');
const perkObtainmentInput   = document.querySelector('#perk-obtainment');

const perkDetailsPreview    = document.querySelector('.details-preview');
const perkObtainmentPreview = perkDetailsPreview.querySelector('h3');
const perkNamePreview       = perkDetailsPreview.querySelector('.about-perk h2');
const perkImagePreview      = perkDetailsPreview.querySelector('.perk-details img');

function loadPerkDetails() {
    if (!perkSelect.value) return clearPerkDetails();

    ajaxRequest(perkSelect, null, 'getPerkDetails.php', (response) => {
        try {
            const data = JSON.parse(response);
            if(!data || typeof data !== 'object') {
                console.error('Invalid response format');
                return;
            }

            if (perkNameInput) perkNameInput.value = data.name || '';
            if (perkObtainmentInput) perkObtainmentInput.value = data.obtainment || '';
            if (perkObtainmentPreview) perkObtainmentPreview.textContent = `This perk is obtained from ${data.obtainment}`;
            if (perkNamePreview) perkNamePreview.textContent = data.name;

            if (perkImagePreview) {
                const replaceFor = ["'", " ", ":"];
                let imgName = (data.name).replace(/(?:^|\s)\w/g, char => char.toUpperCase());

                replaceFor.forEach(symbol => {
                    imgName = imgName.replaceAll(symbol, "");
                });
                console.log(imgName);
                perkImagePreview.src = `/dbd/assets/images/perk_icons/${imgName}.png`;
            };

            if (descriptionManager && data.description) {
                descriptionManager.reconstructFromHTML(data.description);
            } else if (!data.description) {
                descriptionManager.clearBlocks();
            }

        } catch (error) {
            console.error('Error parsing perk details:', error);
            clearPerkDetails();
        }
    });
}

function clearPerkDetails() {
    if (perkNameInput) perkNameInput.value = '';
    if (perkObtainmentInput) perkObtainmentInput.value = '';
    if (descriptionManager) descriptionManager.clearBlocks();
}

if (perkSelect && perkNameInput && perkObtainmentInput) {
    if (perkSelect.value) loadPerkDetails();
    perkSelect.addEventListener('change', loadPerkDetails);
}