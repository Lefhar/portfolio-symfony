$(document).ready(function() {
    if ($('form').length) {
        // there is at least one element matching the selector
        console.log('action get');
        $.get( "../../pdfController/htmlToPDFsave");
    }
});
