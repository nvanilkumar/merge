$(function () {
    
    //Make materialize labels move out of input box when input box is filled via javascript
    Materialize.updateTextFields();
    
    $('#create_user').validate({
        rules: {
            first_name: {
                required: true
            },
            role_type: {required: true},
            username: {
                required: true,
                remote: {
                    url: userCheckUrl,
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        username: function () {
                            return $("#username").val();
                        }
                    }
                }
            },
            password: {
                required: true,
                minlength: 4,
                maxlength: 16,
            },
            email: {
                required: true,
                email: true
            },
            mobileno: {
                number: true
            }
        },
        messages: {
            first_name: {
                required: "Please enter the first name"
            },
            role_type: {
                required: "Please select the user type"
            },
            username: {
                required: "Please enter the username",
                remote: "user name already exist."
            },
            password: {
                required: "Please enter the password",
                minlength: 4,
                maxlength: 16,
            },
            email: {
                required: "Please enter the email",
                email: "Please enter the valid email id"
            },
            mobileno: {

                number: "Please enter the valid mobile number"
            }

        },
        errorElement: "div",
        errorPlacement: function (error, element) {
           
            if($(element).attr("name") == "role_type"){
                $(error).addClass("input-field");
            }
            $(element).addClass("errorTxt2");
            error.appendTo(element.parent());
        }
    });

    //Edit user related form fields information
    if (typeof (userDetails) != "undefined" && userDetails !== "") {
        
        $("#student").parent().parent().remove();
        $("#student").parent().remove();
        $("#password").parent().children().first().hide();
//        $("#password").parent().children()[1].hide();
        $("#first_name").val(userDetails.first_name);
        $("#last_name").val(userDetails.last_name);
        $("#username").val(userDetails.username);
        $("#email").val(userDetails.email);
        $("#mobileno").val(userDetails.mobileno);
        $("#address").val(userDetails.address);
        $("#country").val(userDetails.country);
        $("#pincode").val(userDetails.pincode);
 
    }else{
         $("#password").parent().children().first().next().next().hide();
    }
    
    $('#update_user').validate({
        rules: {
            first_name: {
                required: true
            },
            username: {
                required: true,
                remote: {
                    url: mtcBaseUrl+"/users/usernamecheck",
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        username: function () {
                            return $("#username").val();
                        },
                        user_id_not_equal: function () {
                            return $("#user_id").val();
                        }
                    }
                }
            },
            email: {
                required: true,
                email: true
            },
            mobileno: {
                number: true
            }
        },
        messages: {
            first_name: {
                required: "Please enter the first name"
            },
            
            username: {
                required: "Please enter the username",
                remote: "user name already exist."
            },
           
            email: {
                required: "Please enter the email",
                email: "Please enter the valid email id"
            },
            mobileno: {

                number: "Please enter the valid mobile number"
            }

        },
        errorElement: "div",
        errorPlacement: function (error, element) {
            $(element).addClass("errorTxt2");
            error.appendTo(element.parent());
        }
    });
    
    $("#change-pasword").click(function(){
           $("#password").parent().children().first().next().next().hide();
           $("#password").parent().children().first().show();
    });
});