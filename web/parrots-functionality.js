(function (p_oWindow) {
    var oInput, oForm;

    oForm = document.getElementById('JS_subject-form');

    if (oForm !== null) {
        oInput = document.querySelector('input[name="subject"]') ;  
        oInput.focus();
        oForm.onsubmit = function (p_oEvent) {
            p_oEvent.preventDefault();
            p_oWindow.location = './'  + oInput.value;
        }
    }
}(window));

