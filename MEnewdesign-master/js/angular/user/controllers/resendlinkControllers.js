var controllers = {};
controllers.resendlinkController = function ($scope, resendlinkFactory, $timeout) {
    $scope.submitted = false;

    $scope.resendlink = {};
    $scope.resendlinkErrorMsgs = ''
    $scope.submitForm = function (isValid, resend) {
        $scope.submitted = true;
        $scope.isProcessing = false;
         var Email = $scope.resend.email;
        if (isValid) {
            $scope.isProcessing = true;
            resendlinkFactory.send(resend).success(function (data, status, headers, config) {
                var message = data.response.messages;
                $("#responseMsg").html(message);
                $("#responseMsg").css('color', 'green');
                $timeout(function () {
                        window.location = site_url + 'login?&email=' + Email;
                    }, 6000);
                //$('#verifyEmail').attr('disabled', false);
                $scope.isProcessing = false;
            }).error(function (data, status, headers, config) {
                var response = data.status;
                var message = data.response.messages;
                $("#responseMsg").html(message);
                $("#responseMsg").css('color', 'red');
                //$('#verifyEmail').attr('disabled', false);
                $scope.isProcessing = false;
            });
            ;
        }
    };
    $scope.hideErrorMsgs = function () {
        $("#responseMsg").html('');
    };

//   $scope.submitBtn = function () {
//$('#verifyEmail').attr('disabled', true);
//    };
};
angular.module('resendlinkModule').controller('resendlinkController', ['$scope', 'resendlinkFactory', '$timeout', controllers.resendlinkController]);