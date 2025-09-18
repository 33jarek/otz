const perkNameFromInput = document.querySelector('#perk-name');
const perkObtainmentFromInput = document.querySelector('#perk-obtainment');

const previewPanel = document.querySelector('.details-preview');
const obtainmentText = previewPanel.querySelector('h3');
const perkNameText = previewPanel.querySelector('.about-perk h2');
const perkDetails = previewPanel.querySelector('.details-holder');
const perkImage = previewPanel.querySelector('.perk-details img');

const displayFields = [obtainmentText, perkNameText, perkDetails, perkImage];
const inputFields = [perkObtainmentFromInput, perkNameFromInput];

inputFields.forEach((field, i) => {
    field.addEventListener('input', () => {
        if(i === 0) obtainmentText.textContent = 'This perk is obtained from '+field.value;
        if(i === 1) perkNameText.textContent = field.value;
    });
    field.dispatchEvent(new Event('input'));
});