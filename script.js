document.getElementById('form-box').addEventListener('submit', function (event) {
    let inputFields = this.querySelectorAll('#form-box input, #form-box textarea');
    let valueFields = true;

    for (let i = 0; i < inputFields.length; i++) {
        inputFields[i].classList.remove('emptyField');

        if (inputFields[i].value === "") {
            inputFields[i].classList.add('emptyField');
            valueFields = false;
        }
    }
    if (!valueFields) {
        event.preventDefault();
        alert("Vul alle velden in.");
    } else {
        alert("Formulier succesvol ingediend!");
    }
});