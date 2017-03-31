angular.module('loginModule').factory('resetPasswordFactory', ['$http',function ($http) {
        var factory = {};
        factory.send = function (inputArray) {
        	console.log(inputArray);
            return $http({
                method: 'POST',
                url: site_url + 'api/user/resetPassword',
                data: $.param(inputArray),
                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}  // set the headers so angular passing info as form data(not request payload)
            })
        };
        return factory;
    }]);

