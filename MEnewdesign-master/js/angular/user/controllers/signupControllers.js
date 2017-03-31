var controllers = {};

controllers.signupController = function ($scope,httpCallService,$q,md5,$timeout) {

  
        $scope.commonErrors=[];
        $scope.registeredEmail='';

            //for test
        $scope.signup={};
//        $scope.signup.name='durgesh';
//        $scope.signup.email='durgeshmishra2525@gmail.com';
//        $scope.signup.password='testing';
//        $scope.signup.phonenumber='9898989898';

    $scope.emailBlur=function(){
         
        var EmailName = $scope.signup.email;
        var data={};
         
        data.email=EmailName;
        if (EmailName.length > 1) {
             
            var result= httpCallService.callUrl('POST',api_UsersignupEmailCheck,data,'');
            result.then(function (jsonRes, status) {
                if (jsonRes.data.response.emailStatus){
                    
                    $scope.emailNameStatus=true;console.log($scope.emailNameStatus);
                    $timeout(function () {
                        window.location = site_url + 'login?&email=' + EmailName;
                    }, 3000);
//                    window.location = site_url + 'login?&email='+EmailName;
                } 
                
            }, function (jsonRes) {  // this will be executed if status!=200

                angular.forEach(jsonRes.data.response.messages, function (value, key) {
                    this.push(value);
                }, $scope.commonErrors);

                // error handler
            });
            
             
        }
    }
    

    

    
     $scope.submitSignup=function(signup){
        $scope.submitted=true;
                // check to make sure the form is completely valid
            if ($scope.signupForm.$valid) {
              var data={};
         //creating data variable   
       $scope.commonErrors=[];
        data.name=signup.name;
        data.email=signup.email;
        data.password=md5.createHash(signup.password);
        data.phonenumber=signup.phonenumber;
        data.countryId = signup.countryId;
        data.countryphoneCode = signup.countryphoneCode;
        $scope.registeredEmail=signup.email;
        
      var result= httpCallService.callUrl('POST',api_Usersignup,data,'');
      result.then(function(jsonRes,status){
    	var username = data.name;
      	var useremail = data.email;
      	var usermobile =  data.phonenumber ||'';
        var phoneCode = decodeURIComponent(getCookie('phoneCode'))||'+91';
      	wizrocket.event.push("User Signedup",{});
      	 wizrocket.profile.push(
      		        {
      		        "Site": {
      		        "Name": username,
      		        "Email": useremail, 
      		        "Phone": phoneCode+ usermobile,
      		        }});
         //$scope.signupForm.name.$error.required=true;

     

 //success condition displaying registration message
      // var sendActivationData={};
       //sendActivationData.email=$scope.registeredEmail;
 
      // var sendActivationMail= httpCallService.callUrl('POST',api_path+'user/resendActivationLink',data,'');
    $('#signUpContainer').hide();
    
    $('#ActivateContainer').show();




      }, function(jsonRes) {  // this will be executed if status!=200

angular.forEach(jsonRes.data.response.messages, function(value, key) {
  this.push( value);
}, $scope.commonErrors);

    // error handler
});
            }
            else
                {console.log('Form error');}
     
    }
    $scope.hideErrorMsgs=function(){
         $scope.commonErrors=[];
    };
    
};
angular.module('signupModule').controller('signupController', ['$scope', 'httpCallService','$q','md5','$timeout', controllers.signupController]);
