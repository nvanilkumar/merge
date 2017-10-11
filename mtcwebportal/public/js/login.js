$(function () {
    //Make materialize labels move out of input box 
    Materialize.updateTextFields();

    $("#forgot-page").hide();

    //Forgot Div
    $("#forgot-link").click(function () {

        $("#forgot-page").show();
        $("#login-page").hide();

    });

    //login check
    $("#login-link").click(function () {
        loginSubmit();
    });
    $('#password').keyup(function (e) {
        if (e.keyCode == 13)
        {
            loginSubmit();
        }
    });


    //forgot password link clicked
    $('body').on('click', '#forgot-link-submit', function () {
        
        forgotUserName = $('#forgot-username').val();
        if (forgotUserName == "") {
            $('#forgot-username').focus();
            $('#forgot-username').addClass("invalid");
        }

        if (forgotUserName != '') {
            $.ajax({
                type: "POST",
                url: forgotApiUrl,
                headers: {
                    Authorization: "Basic " + btoa(authorization),
                },
                data: {user_name: forgotUserName},
                dataType: 'json',
                success: function (response) {
                      console.log(4444);
                    console.log(response);
                      $("#forgot-status-message").html(response.message);
                },
                error: function (error) {
                    $("#forgot-status-message").html(error.responseJSON.message);
                    console.log(222);
                    console.log(error);
                }
            });
        }

    });

});


function loginSubmit()
{
    userName = $('#username').val();
    password = $('#password').val();
    LoginType = 'staff';


    if (userName == "") {
        $('#username').focus();
        $('#username').addClass("invalid");
    } else if (password == "") {
        $('#password').focus();
        $('#password').addClass("invalid");
    }

    if (userName != '' && password != '') {
        $.ajax({
            type: "POST",
            url: loginApiUrl,
            data: {username: userName, password: password, user_role: LoginType},
            dataType: 'json',
            success: function (response) {

                if (response.response.status == false) {
                    $("#errorMessage").html(response.response.message);
                } else if (response.response.status == true) {
                    window.location.href = mtcBaseUrl + "/dashboard";
                }

            },
            error: function (error) {

                $("#errorMessage").html(error.response.message);
            }
        });


    }
}
