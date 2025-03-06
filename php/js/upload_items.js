function addSerialNumberField() {
    const container = document.getElementById('serial_numbers_container');
    const serialNumberCount = container.getElementsByClassName('serial-number-input').length + 1;
    
    const newSerialNumberDiv = document.createElement('div');
    newSerialNumberDiv.className = 'serial-number-input mt-2';
    
    const newLabel = document.createElement('label');
    newLabel.htmlFor = `serial_numbers[]`;
    newLabel.textContent = `Serial Number ${serialNumberCount}:`;
    
    const newInput = document.createElement('input');
    newInput.type = 'text';
    newInput.name = 'serial_numbers[]';
    newInput.required = true;
    
    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.className = 'btn btn-danger btn-sm ml-2';
    removeButton.textContent = 'Remove';
    removeButton.onclick = function() {
        container.removeChild(newSerialNumberDiv);
        // Update the numbering of remaining serial number fields
        updateSerialNumberLabels();
    };
    
    newSerialNumberDiv.appendChild(newLabel);
    newSerialNumberDiv.appendChild(newInput);
    newSerialNumberDiv.appendChild(removeButton);
    
    container.appendChild(newSerialNumberDiv);
}

function updateSerialNumberLabels() {
    const container = document.getElementById('serial_numbers_container');
    const serialNumberDivs = container.getElementsByClassName('serial-number-input');
    
    for(let i = 0; i < serialNumberDivs.length; i++) {
        const label = serialNumberDivs[i].getElementsByTagName('label')[0];
        label.textContent = `Serial Number ${i + 1}:`;
    }
}
