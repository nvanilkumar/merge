angular.module('commonModule').service(['$http', '$window', function ($http, $window) {
        //var response = {};
        this.updateCookie = function (inputArray) {
        	
        	
        };
     /*       var postData = {call: 'updateCookie',
                cookieName: inputArray.names, cookieValue: inputArray.values};
            //    var inputData = 'input=' + JSON.stringify
            // (postData);
            $http({
                method: 'POST',
                url:
                	api_commonRequestProcessRequest,
                data: $.param(postData),
                headers: {'Content-Type':
                            'application/x-www-form-urlencoded',
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}  // set the headers so angular passing info as form data(not request payload)
            }).success(function (data, status, headers,
                    config) {
                //console.log(data + '--' + status);
                if (data.status && inputArray.names.indexOf("countryId") != -1) {
                    $window.location.href = site_url;
                }

            }).error(function (data, status, headers,
                    config) {
               //alert('Something went wrong please try again');
            });*/
       // };
        //return response;
    }]);

