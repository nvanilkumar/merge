tinymce.init({
    // General options
    mode: "specific_textareas",
    editor_selector: "mceEditor",
    selector: "textarea#tncDescription",
    // General options
    width: "100%",
    // ===========================================
    // INCLUDE THE PLUGIN
    // ===========================================

    plugins: [
        "advlist autolink lists link charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime paste"
    ],
    // ===========================================
    // PUT PLUGIN'S BUTTON on the toolbar
    // ===========================================

    toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
    // ===========================================
    // SET RELATIVE_URLS to FALSE (This is required for images to display properly)
    // ===========================================

    relative_urls: false

});

tinymce.init({
    // General options
    mode: "specific_textareas",
    editor_selector: "mceEditor",
    selector: "textarea#emailAttendeeMessage",
    // General options
    width: "100%",
    // ===========================================
    // INCLUDE THE PLUGIN
    // ===========================================

    plugins: [
        "advlist autolink lists link charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime paste"
    ],
    // ===========================================
    // PUT PLUGIN'S BUTTON on the toolbar
    // ===========================================

    toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
    // ===========================================
    // SET RELATIVE_URLS to FALSE (This is required for images to display properly)
    // ===========================================

    relative_urls: false

});
 //termas and conditions validation related js
//$('#tncForm').validate({
//    submitHandler: function (form) {
//        if (tinymce.get('tncDescription').getContent()) {
//            $("#tncDiscriptionError").text("");
//            form.submit();
//        } else {
//            $("#tncDiscriptionError").text("Please enter Terms and Conditions");
//        }
//    }
//});

$('#tncForm').validate({
    submitHandler: function (form) {
       // if (tinymce.get('tncDescription').getContent()) 
            form.submit();
    }
});

$('#emailAttendeesSendTestMail,#emailAttendeesSendMail').click(function(){
        if (tinymce.get('emailAttendeeMessage').getContent()) {
            $("#messageError").text("");
        } else {
            $("#messageError").text("Please enter message");
        }
    });

