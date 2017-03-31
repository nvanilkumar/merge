var controllers = {};

controllers.changePasswordController = function ($scope, changePasswordFactory) {
    $scope.submitted = false;
    $scope.changeErrorMsgs = '';
    $scope.submitForm = function (isValid, change) {
        $scope.submitted = true;
        $scope.invalid=0;
      	if($scope.change.password!=$scope.change.confirmPassword){
 				$scope.changeErrorMsgs = "Confirm password must be equal to the password";
 				$scope.invalid=1;     		
      	}
        if (isValid && $scope.invalid!=1) {
        	       
            changePasswordFactory.send(change).success(function (data, status, headers, config) {          	     	                
				 if(status==201) {            	
                     $scope.changeSuccessMsgs = data.response.messages;
                     setTimeout(function(){window.location.href="/login";},2000)
                 }	else if(status==200) {            	
                     $scope.changeErrorMsgs = data.response.messages;
                    

                 }
                  $('input[name="password"]').val('');
                  $('input[name="confirmPassword"]').val('');


            }).error(function (data, status, headers, config) {
            	$scope.changeErrorMsgs = data.response.messages; 
            	if(status==400) {        
                     $scope.changeErrorMsgs = data.response.messages; 
                 }else if(status==412) {        	
                     $scope.changeErrorMsgs = data.response.messages;
                 }
            });
            ;
        }
    };
    $scope.hideErrorMsgs = function () {
        if ($scope.changeErrorMsgs !== '' || $scope.changeSuccessMsgs !== '')
            $scope.changeErrorMsgs = '';
            $scope.changeSuccessMsgs = '';
    };    
};

angular.module('changePasswordModule').controller('changePasswordController', ['$scope', 'changePasswordFactory', controllers.changePasswordController]);