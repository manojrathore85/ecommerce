function handleValidationErrorsControlsToast(errors) {
    console.log(errors);
    for (const fieldName in errors) {
        //alert('asdfas');
        const errorMessage = errors[fieldName][0];
        const formField = document.getElementById(fieldName);
        console.log(formField);
        formField.classList.add('is-invalid');
        
        let span = document.getElementById(formField.id + '-error');
        //console.log(span);
        if (!span) {
            span = document.createElement('span');
            span.id = formField.id + '-error';
            span.textContent = errorMessage;
            span.classList.add('text-danger');
            formField.insertAdjacentElement('afterend', span);
        }
        span.textContent = errorMessage;
        showToast(fieldName, errorMessage);
    }
}

function showToast(title, message) {
    // Clone the toast template
    var toast = document.querySelector('#liveToast').cloneNode(true);

    // Set toast content
    //toast.querySelector('.toast-header').innerText = title;
    toast.querySelector('.toast-body').innerText = message;

    // Append the toast to the toast container (usually a div with an ID)
    document.getElementById('toast-container').appendChild(toast);

    // Initialize the toast and show it
    var bootstrapToast = new bootstrap.Toast(toast);
    bootstrapToast.show();
}
$(document).ready(function(){
    $('.numericonly').each(function(){
        if(!$.isNumeric( $(this).val()) || $(this).val() == " " ){
            $(this).val(0);
        }
    });
    $('.numericonly').change(function(){
        if(!$.isNumeric( $(this).val()) || $(this).val() == " " ){
            $(this).val(0);
        }
    });
});