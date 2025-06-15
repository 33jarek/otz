
// Main request sender function
function requestXMLHttp(source, target, file) {
    const value = source.value;
    const name = source.getAttribute('name');
    const XMLreq = new XMLHttpRequest();
    XMLreq.open('POST', `./AJAX/${file}`, true);
    XMLreq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    XMLreq.onload = () => target.innerHTML = XMLreq.responseText;
    XMLreq.send(`${name}=${encodeURIComponent(value)}`);
}

// LISTEN TO KILLERS/SURVS SELECT AND RETRIEVE ALL CHARACTERS
const selectChars = document.querySelector('#build-inserter #select-chars');
const charNames = document.querySelector('#build-inserter #chars-and-names');
selectChars.addEventListener('change', () => requestXMLHttp(selectChars, charNames, 'return_chars.php'));

// LISTEN TO KILLERS/SURVS SELECT AND RETRIEVE ALL CHARACTERS
const selectBuilds = document.querySelector('#build-remover #builds-select');
const tBody = document.querySelector('#build-table-holder table tbody');
selectBuilds.addEventListener('change', () => requestXMLHttp(selectBuilds, tBody, 'return_builds.php'));