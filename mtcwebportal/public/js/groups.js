$(function () {
    
    $(":input").bind('focusout', function(){
         $(this).val( $.trim($(this).val())) ;
    });
/**
 * Form Vaidations
 */
 $('#create_group').validate({
        rules: {
            group_name: {
                required: true,
                nowhitespace: true,
                remote: {
                    url: mtcBaseUrl+"/groups/groupnamecheck",
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        groupname: function () {
                            return $("#group_name").val();
                        }
                    }
                }
            },
 
        },
        messages: {
            group_name: {
                required: "Please enter the group name",
                remote:"Duplicate group name"
            }

        },
        errorElement: "div",
        errorPlacement: function (error, element) {
            $(element).addClass("errorTxt2");
            error.appendTo(element.parent());
        }
    });
    
//UPdate group validations
 $('#update_group').validate({
        rules: {
            group_name: {
                required: true,
                remote: {
                    url: mtcBaseUrl+"/groups/groupnamecheck",
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        groupname: function () {
                            return $("#group_name").val();
                        },
                        group_id_not_equal: function () {
                            return $("#group_id").val();
                        }
                    }
                }
            },
 
        },
        messages: {
            group_name: {
                required: "Please enter the group name",
                remote:"duplicate group name"
            }

        },
        errorElement: "div",
        errorPlacement: function (error, element) {
            $(element).addClass("errorTxt2");
            error.appendTo(element.parent());
        }
    });
    
    //edit form 
    //Edit user related form fields information
    if (typeof (groupDetails) != "undefined" && groupDetails !== "") {
        $("#group_name").val(groupDetails.group_name);
        $("#group_users").hide();
    }
    
    //Cancel form click event
    $("#cancel").click(function () {
        formId = $(this).closest("form").attr('id');
        clearForm(formId);
    });

});