  $(function(){
$.urlParam = function (name) {
                        var results = new RegExp('[\?&]' + name + '=([^#]*)').exec(window.location.href);
                        if (results == null) {
                            return null;
                        }
                        else {
                            return results[1] || 0;
                        }
                    }
  });

function showhide()
{
    var divSignUp = document.getElementById("signUpContainer");
    var divLogin = document.getElementById("loginContainer");
    if (divSignUp.style.display == "none") {
        divSignUp.style.display = "block";
        divLogin.style.display = "none";
    }
    else {
        divSignUp.style.display = "none";
        divLogin.style.display = "block";
    }
}


function showhideforgot()
{
    if ($('#activemessage').html() != '') {
        $('#activemessage').html('');
    }
    var divSignUp = document.getElementById("ForgotContainer");
    var divLogin = document.getElementById("loginContainer");
    if (divSignUp.style.display == "none") {
        divSignUp.style.display = "block";
        divLogin.style.display = "none";

        $("#forgotEmail").val($("#exampleInputEmail1").val());
        //ngModel listens for "input" event, so to "fix" your code you'd need to 
        //trigger that event after setting 
        $("#forgotEmail").trigger('input');

    }
    else {
        divSignUp.style.display = "none";
        divLogin.style.display = "block";
    }
}
function showhideactivation()
{
    var divSignUp = document.getElementById("ActivateContainer");
    var divLogin = document.getElementById("signUpContainer");
    if (divSignUp.style.display == "none") {
        divSignUp.style.display = "block";
        divLogin.style.display = "none";
    }
    else {
        divSignUp.style.display = "none";
        divLogin.style.display = "block";
    }
}



function customCheckbox(checkboxName) {
    var checkBox = $('input[name="' + checkboxName + '"]');
    $(checkBox).each(function () {
        $(this).wrap("<span class='custom-checkbox'></span>");
        if ($(this).is(':checked')) {
            $(this).parent().addClass("selected");
        }
    });
    $(checkBox).click(function () {
        $(this).parent().toggleClass("selected");
    });
}

customCheckbox("sport[]");
function sendData(accesstoken, type)
{
//        if (type == 'facebook')
//        {
//        var url = site_url+"api/user/login";
//        } else if (type == 'google')
//        {
//        var url = site_url+"api/user/login";
//        }
    var url = site_url + "api/user/login";
    $.ajax({
        type: "POST",
        url: url,
        data: {accessToken: accesstoken, type: type},
        headers: {'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'},
        cache: false,
        dataType: 'json',
        success:
                function (result) {
        	var username = result.response.userData.name;
        	var useremail = result.response.userData.email;
        	var usermobile = result.response.userData.mobile||result.response.userData.phone;
        	var fbId = result.response.userData.facebookid||'';
        	var twitterId = result.response.userData.twitterid||'';
        	var googleId = result.response.userData.googleid||'';
        	var redirectUrl = result.response.userData.redirectUrl||'';
                   var phoneCode = decodeURIComponent(getCookie('phoneCode'))||'+91';
         	 if(result.response.userData.socialLoginSignup == 1){
     			wizrocket.event.push("User Signedup",{});
     	      	 wizrocket.profile.push(
     	      		        {
     	      		        "Site": {
     	      		        "Name": username,
     	      		        "Email": useremail, 
     	      		        "Phone": phoneCode+usermobile,
     	      		        }});
     	 }
                    var response = result.response.userData;
                    if (response.id > 0)
                    {
                       // window.location = site_url + 'home';
                    	  redirectUrl=$.urlParam('redirect_url')||redirectUrl;
                           if(redirectUrl!= null && redirectUrl.length >0){ 
                               redirectUrl=site_url+redirectUrl;
                               window.location.href = redirectUrl;
                           }else{ 
                               redirectUrl=site_url;
                               window.location.href = redirectUrl;
                           }
                    }
                    else
                    {
                        alert(response.messages);
                    }
                },
        error: function (result) {
            alert(result.messages);
        }
    });
}
function redirectToTwitter() {
    window.location = site_url + 'twitter/redirect';
}


function checkURL() {
    //alert('in');
    var url = window.location.href;
    var tag = url.split('#')[1];
    if (tag == 'forgotDiv') {
        document.getElementById("loginContainer").style.display = "none";
        document.getElementById("ForgotContainer").style.display = "block";

    }else{
        document.getElementById("loginContainer").style.display = "block";
    }
}
checkURL();

$(function(){
    $(".resentLink").click(function(){
        var hashTagValue=$("#exampleInputEmail1").val();
        window.location.href = site_url+"resendActivationLink?&email="+hashTagValue;
    });
});


$('div.locSearchContainer,.search-container').hide();
$("#datepicker").datepicker();
