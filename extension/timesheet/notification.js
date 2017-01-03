var hostUrl;

$(document).ready(function () {
    $("#timesheet_page, #error_div").hide();
    
    $("#dashboard").hide();
    $("#login").click(function () {
        var username=$("#username").val();
        var password=$("#password").val();
        $.ajax({
            url: "http://localhost/timesheet/web/app_dev.php/api/v1/adminlogin",
            type: "POST",
            data: "username=" + username + "&password=" + password,
            success: function (response)
            {
                console.log(response);
                if (response.status == "success")
                {
//                    $("#login_form_div").hide();
                    
                    document.getElementById("timesheet_page").click();
                    $("#timesheet_page").click();
                }else{
                    $("#error_div").show();
                }

            }});


    });
});
 