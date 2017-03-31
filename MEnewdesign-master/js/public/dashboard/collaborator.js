$(document).ready(function () {
    customCheckbox("module[]");
    $('button[name="cancel"]').on('click', function (e) {
        e.preventDefault();
        window.location.href = site_url + 'dashboard/collaborator/collaboratorlist/' + $('#eventId').val();
    });
//Promoter related js   

    $('#addCollaborator').validate({
        rules: {
            name: {
                required: true,
                validateName: true,
                maxlength: 25
            },
            email: {
                required: true,
                email: true
            },
             mobile: {
                number: true,
                minlength: 10,
                maxLen: 10
            },
            'module[]': {required: true}

        },
        messages: {
            name: {
                required: "Please enter name",
                maxlength: "Please enter not more than 25 characters"
            },
            email: {
                required: "Please enter email",
                email: "Please enter valid email"
            },
            mobile: {
                required: "Please enter valid phone number"
            },
            'module[]': {required: "Please choose atleast one module"}
        },
        errorElement: 'label',
        errorPlacement: function (error, element) {
            if (element.attr("name") == "module[]") {
                $("#modulesErr").html(error);
            }
            else
                error.insertAfter(element);
        },
        submitHandler: function (form) {
            if ($(form).valid())
            {
                var eventId = $('#eventId').val();
                var collaboratorId = $('#collaboratorId').val();
                var input ='';
              input = 'eventid=' + eventId + '&name=' + form.name.value + '&email=' + form.email.value + '&mobile=' + encodeURIComponent(form.mobile.value);
                var mod = $(".module");
                $.each(mod,function(key,e) {
                    //console.dir(e);
                    if (e.checked == true) {
                        input += '&' + e.value + '=1';

                        // modules[this.value] = 1;
                    } else {
                        input += '&' + e.value + '=0';
                        //modules[this.value] = 0;
                    }
                });
               // console.log(input);return false;
                var pageUrl = api_collaboratorAdd;
                if (collaboratorId > 0) {
                    input += '&collaboratorid=' + collaboratorId;
                    pageUrl = api_collaboratorUpdate;
                }
                //console.log(modules);

                var method = 'POST';
                var dataFormat = 'JSON';
                function callbackSuccess(result) {
                	if(result.response.collaboratorData && result.response.collaboratorData.addedStatus ){
                		 window.location.href = site_url + 'dashboard/collaborator/collaboratorlist/' + eventId;
                	}
                	else if (result.response.collaboratoraccessData && result.response.collaboratoraccessData.updateStatus) {
                        window.location.href = site_url + 'dashboard/collaborator/collaboratorlist/' + eventId;
                    } else {
                        alert("Something went wrong.");
                    }
                }
                function callbackFailure(result) {
                    //alert(result.response.messages[0]);
                    //alert(result.responseJSON.response.messages);
                    $('#collaborator_email').text(result.responseJSON.response.messages).css('display','block').slideUp(10000);
                }
                getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
            }else{
            return false;
        }
        }

    });
         jQuery.validator.addMethod("maxLen", function (value, element, param) {
        if ($(element).val().length > param) {
            return false;
        } else {
         return true;
        }
    }, "Mobile number cannot be more than 10 characters");
    $.validator.addMethod("validateName", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\s.]+$/.test(value);
    }, "Letters and numbers are allowed");
    
//    $.validator.addMethod("validatePhone", function (value, element) {
//        return this.optional(element) || /^(\+91)?\d{10}$/.test(value);
//    }, "Letters and numbers are allowed");    
    
    $.validator.addMethod("moduleSelected", function (value, elem, param) {
        if ($(".module:checkbox:checked").length > 0) {
            return true;
        } else {
            return false;
        }
    }, "You must select at least one module");
    
    $('.status').on('click', function () {
        var currentElement = this;
        $(currentElement).prop('disabled', true);
        var eventId = $('#eventId').val();
        var input = 'eventid=' + eventId + '&collaboratorid=' + this.collaboratorId + '&field=status';
        var pageUrl = api_collaboratorUpdateStatus;
        var method = 'POST';
        var dataFormat = 'JSON';
        function callbackSuccess(result) {
            console.log(result);
            if (result.response.total > 0) {
                if (result.response.collaboratorData.value == 1) {
                    $(currentElement).text('show');
                    $(currentElement).removeClass('orangrBtn');
                    $(currentElement).addClass('greenBtn');
                } else {
                    $(currentElement).text('hide');
                    $(currentElement).removeClass('greenBtn');
                    $(currentElement).addClass('orangrBtn');
                }
            }
            $(currentElement).prop('disabled', false);
        }
        function callbackFailure(result) {
            alert(result.responseJSON.response.messages.message[0]);
            $(currentElement).prop('disabled', false);
        }
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
    });
});
$('.module').on('change', function () {
    //customCheckbox("module[]");
        if (this.checked && this.value == "manage" ) {
            $('.module[value="promote"]').prop('checked',true);
            //$('.module[value="promote"]').attr('disabled', 'disabled');
            $('.module[value="promote"]').parent().addClass("selected");
            $('.module[value="report"]').prop('checked',true);
            //$('.module[value="report"]').attr('disabled', 'disabled');
            $('.module[value="report"]').parent().addClass("selected");
//            $('.module[value="manage"]').attr('checked', 'checked');
//            $('.module[value="manage"]').parent().addClass("selected");
           
        }else{
            if(!this.checked){
             $('.module[value="manage"]').prop('checked',false);
            $('.module[value="manage"]').parent().removeClass("selected");
        }
        }
       
    });

