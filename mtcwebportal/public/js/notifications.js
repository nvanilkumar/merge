$(function () {
    
    $('#create_notification').validate({
        ignore: [],
        rules: {
            message: {
                required: true
            },
            "group_list[]": {
                SaveCheck: true,                 
            },
            "users_list[]": {
                SaveCheck: true,                 
            }
           
        },
        messages: {
            message: {
                required: "Please enter the message"
            }
        },
        errorElement: "div",
        errorPlacement: function (error, element) {
            $(element).addClass("errorTxt2");
            error.appendTo(element.parent());
        }
    });
    jQuery.validator.addMethod("SaveCheck", function SaveCheck() {
        var flag = false;
        $('.selectOne :selected').each(function() {
            if($(this).val() !== "") {
                flag = true;
                return false;
            }
        });
        return flag;
    }, "Please select anyone groups or users");         
});