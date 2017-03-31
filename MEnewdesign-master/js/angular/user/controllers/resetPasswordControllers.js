var controllers = {};

controllers.resetPasswordController = function ($scope, resetPasswordFactory) {
    $scope.submitted = false;
    $scope.resetErrorMsgs = '';
    $scope.submitForm = function (isValid, reset) {
        $scope.submitted = true;
        if (isValid) {

            resetPasswordFactory.send(reset).success(function (data, status, headers, config) {          	     	                
                 if(status==200) {               	
                     $scope.resetSuccessMsgs = data.response.messages;
                 }
            }).error(function (data, status, headers, config) {     
                     $scope.resetErrorMsgs = data.response.messages;
            });
            ;
        }
    };
    $scope.hideErrorMsgs = function () {
        if ($scope.resetErrorMsgs !== '' || $scope.resetSuccessMsgs !== '')
            $scope.resetErrorMsgs = '';
            $scope.resetSuccessMsgs = '';
    };    
};

angular.module('loginModule').controller('resetPasswordController', ['$scope', 'resetPasswordFactory', controllers.resetPasswordController]);