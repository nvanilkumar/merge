angular.module('changePasswordModule').factory('changePasswordFactory', ['$http',function ($http) {
        var factory = {};
        factory.send = function (inputArray) {
        	//console.log(inputArray);
        	inputArray['verificationString'] = $('input[name="token"]').val();
            return $http({
                method: 'POST',
                url: api_UserchangePassword,
                data: $.param(inputArray),
                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}  // set the headers so angular passing info as form data(not request payload)
            })
        };
        return factory;
    }]);

