var controllers = {};

controllers.loginController = function ($scope, md5, loginFactory, $window,$cookies,$location) {
    $scope.login = {email:'',password:''};
    $scope.loginErrorMsgs = ''
    if($cookies.email!==undefined){
        $scope.login.email=$cookies.email;
        if($cookies.password!==undefined){
            $scope.login.password=$cookies.password;
        }
    }

    $scope.submitForm = function (isValid, login) {
        
        if (isValid) {
        	userlogin={};
         	 if ($('.rember .custom-checkbox').hasClass('selected')) {
        		 userlogin.remember=true;
        		 userlogin.email=login.email;
        		 userlogin.password=login.password;
             }else{
            	 userlogin.email='';
        		 userlogin.password='';
            	 userlogin.remember=false;
             } 
        	 var input = {names: ['email', 'password','loginremember'], values: [userlogin.email, userlogin.password,userlogin.remember]};
        	 updateCookieservice(input);
            var loginData = {email: login.email, password: md5.createHash(login.password)};
            //login.password = md5.createHash(login.password);
            loginFactory.login(loginData).success(function (data, status, headers, config) {
                if (data.response.total > 0 && data.response.userData.id > 0) {
                	var username = data.response.userData.name;
                	var useremail = data.response.userData.email;
                	var usermobile = data.response.userData.mobile||data.response.userData.phone;
                        var phoneCode = decodeURIComponent(getCookie('phoneCode'))||'+91';
                	var fbId = data.response.userData.facebookid||'';
                	var twitterId = data.response.userData.twitterid||'';
                	var googleId = data.response.userData.googleid||'';
                	var redirectUrl = data.response.userData.redirectUrl||'';
                	 wizrocket.profile.push(
                		        {
                		        "Site": {
                		        "Name": username,
                		        "Email": useremail, 
                		        "Phone": phoneCode + usermobile,
                		        "FBID":fbId    
                		        }});
              
                    //get the redirect url
                    $.urlParam = function (name) {
                        var results = new RegExp('[\?&]' + name + '=([^#]*)').exec(window.location.href);
                        if (results == null) {
                            return null;
                        }
                        else {
                            return results[1] || 0;
                        }
                    }
                     var redirectUrl=$.urlParam('redirect_url')||data.response.userData.redirectUrl;
                     var redirectURI=$.urlParam('redirect_uri');
                    if(redirectURI!= null && redirectURI.length >0){ 
                        redirectUrl=site_url+redirectURI;
//+'?client_id='+$.urlParam('client_id')+'&response_type='+$.urlParam('response_type')+'&redirect_url='+$.urlParam('redirect_url')+'&state='+$.urlParam('state');
                        window.location.href = redirectUrl;
                    }else if(redirectUrl!= null && redirectUrl.length >0){ 
                        redirectUrl=site_url+redirectUrl;
                        $window.location.href = redirectUrl;
                    }else{ 
                        redirectUrl=site_url;
                        $window.location.href = redirectUrl;
                    }
                    
                } else {
                    $('#loginErrorMsgs').text(data.response.messages);
                    $('#loginErrorMsgs').show();
                    $('#loginErrorMsgs').slideUp(5000);
                    $('input[name="password"]').val('');
                }
            }).error(function (data, status, headers, config) {
                $scope.loginErrorMsgs = data.response.messages;
                $('input[name="password"]').val('');
            });
        }
    };


    $scope.hideErrorMsgs = function () {
        if ($scope.loginErrorMsgs !== '')
            $scope.loginErrorMsgs = '';
    };



};
angular.module('loginModule').controller('loginController', ['$scope', 'md5', 'loginFactory', '$window', '$cookies','$location', controllers.loginController]);
