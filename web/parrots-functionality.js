(function (p_oWindow, p_oDocument) {
    var oPrefix, oSubject, oInput, oForm;

    /* Convert URL from `?subject=foo` to `/foo` */
    oForm = p_oDocument.getElementById('JS_subject-form');
    if (oForm !== null) {
        oInput = p_oDocument.querySelector('input[name="subject"]') ;  
        oInput.focus();
        oForm.onsubmit = function (p_oEvent) {
            p_oEvent.preventDefault();
            p_oWindow.location = './'  + oInput.value;
        }
    }
    
    /* Convert available emoji characters to images*/
    oPrefix = p_oDocument.querySelector('.prefix') ;  
    oSubject = p_oDocument.querySelector('.subject') ;  

    oSubject.innerHTML = twemoji.parse(oSubject.textContent, {
        folder: 'svg',
        ext: '.svg'
    });
    
}(window, document));

