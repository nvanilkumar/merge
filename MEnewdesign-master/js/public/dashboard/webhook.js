$( document ).ready(function() {
        $('#webhookUrlForm').validate({
        rules:{
            webhookUrl:{
                required:true,
                url:true
            }
        },
        messages:{
            webhookUrl:{
                required:"Please enter web hook url",
                url:"Enter valid url"
            }
        },
        submitHandler: function(form){
            form.submit();
        }
    });
});

