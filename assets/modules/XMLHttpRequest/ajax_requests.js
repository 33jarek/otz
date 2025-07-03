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

// LISTEN TO KILLERS/SURVS SELECT AND RETRIEVE ALL CHARACTERS
const selectChars = document.querySelector('#build-inserter #select-chars');
const charNames = document.querySelector('#build-inserter #chars-and-names');
if(selectChars && charNames) selectChars.addEventListener('change', () => ajaxRequest(selectChars, charNames, 'getCharacters.php'));

// LISTEN TO KILLERS/SURVS SELECT AND RETRIEVE ALL BUILDS
const selectBuilds = document.querySelector('#build-remover #builds-select');
const tBody = document.querySelector('#build-table-holder table tbody');
if(selectBuilds && tBody) selectBuilds.addEventListener('change', () => ajaxRequest(selectBuilds, tBody, 'getBuilds.php'));

const selectPerk = document.querySelector('#perk-details');
const inputName = document.querySelector('#perk-name');
const inputObtainment = document.querySelector('#perk-obtainment');
if(selectPerk, inputName, inputObtainment) {
    selectPerk.addEventListener('change', () => {
        ajaxRequest(selectPerk, null, 'getPerkDetails.php', (response) => {
            const data = JSON.parse(response);
            inputName.value = data.name;
            inputObtainment.value = data.obtainment;
            inputName.dispatchEvent(new Event('input'));
            inputObtainment.dispatchEvent(new Event('input'));
        });
    });
};