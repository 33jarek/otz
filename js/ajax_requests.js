
// Main request sender function
function requestXMLHttp(name, target, file, callback) {
    const XMLreq = new XMLHttpRequest();
    XMLreq.open('POST', `./AJAX/${file}`, true);
    XMLreq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    XMLreq.onload = () => {
        target.innerHTML = XMLreq.responseText;
        if(typeof callback === 'function') callback();
    };
    XMLreq.send(`name=${encodeURIComponent(name)}`);
}

