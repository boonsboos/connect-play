// Find elment with ID "form-box" and listen to event "submit"
document.getElementById('form-box').addEventListener('submit', function (event) {
    // Get all inputfields and textareafields
    let inputFields = this.querySelectorAll('#form-box input, #form-box textarea');
    
    // create a boolean for the check
    let valueFields = true;

    for (let i = 0; i < inputFields.length; i++) {
        // Delete all class emptyField
        inputFields[i].classList.remove('emptyField');
        
        // Check if let inputFields is empty. If empty, add class emptyField
        // set valueFields to false
        if (inputFields[i].value === "") {
            inputFields[i].classList.add('emptyField');
            valueFields = false;
        }
    }

    // Check if valueFields is false
    if (!valueFields) {
        event.preventDefault();
        alert("Vul alle velden in.");
    } else {
        alert("Formulier succesvol ingediend!");
    }
});