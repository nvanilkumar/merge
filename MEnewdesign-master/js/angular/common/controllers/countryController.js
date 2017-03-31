var controllers={};
controllers.CountryController=function ($scope) {
    //console.log('dsa');
    //$scope.data1={};
    $scope.updateCountryCookie = function (cookieName, cookieValue) {
        //alert(cookieName+"----"+cookieValue);
        var input = {names: [cookieName], values: [cookieValue]};
        updateCookieservice(input);
    };

};

angular.module('commonModule').controller('countryController',['$scope',controllers.CountryController]);