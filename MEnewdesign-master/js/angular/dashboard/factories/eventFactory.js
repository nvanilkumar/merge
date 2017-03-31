angular.module('eventModule').factory('eventFactory', ['$http',function ($http) {
        var factory = {};
        factory.getInfo = function (evntId,resourceName) {
            var getData = { eventId: evntId };
            return $http({
                method: 'GET',
                url:api_path + resourceName,//'event/detail',
                params: getData,
                headers: {'Content-Type':
                            'application/x-www-form-urlencoded',
                    'Authorization': 'bearer '+client_ajax_call_api_key} 
            });
        };
        return factory;
    }]);

