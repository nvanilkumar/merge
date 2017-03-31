function gPOnLoad(){
    // G+ api loaded
	if($('#gp_login').is(':visible')){
    document.getElementById('gp_login').style.display = 'block';
	}else{
		document.getElementById('gp_loginResponcive').style.display = 'block';
	}
}
function googleAuth() {
   gapi.auth.signIn({
       callback: gPSignInCallback,
       clientid: GOOGLE_APP_ID,
       cookiepolicy: "single_host_origin",
       requestvisibleactions: "http://schema.org/AddAction",
       scope: "https://www.googleapis.com/auth/plus.login email"
   })
}

function gPSignInCallback(e) {
   if (e["status"]["signed_in"]) {
       gapi.client.load("plus", "v1", function() {
           if (e["access_token"] && e['status']['method'] == 'PROMPT') {
        	   sendData(e["access_token"], 'google');
        	   return false;
           } else if (e["error"]) {
           }
       })
   } else {
   }
}
(function() {
   var e = document.createElement("script");
   e.type = "text/javascript";
   e.async = true;
   e.src = "https://apis.google.com/js/client:platform.js?onload=gPOnLoad";
   var t = document.getElementsByTagName("script")[0];
   t.parentNode.insertBefore(e, t)
})()





