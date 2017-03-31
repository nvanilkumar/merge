
var app =angular.module('Homepage', ['ngRoute']);

app.controller('BannerController', function($scope, $http) {
  
  $http.get("js/bannner.json")
  .success(function (response) {
      
      $scope.banners = response.baneerImages;
      });
});

app.controller('PopularEventController', function($scope, $http) {
  
  $http.get("js/popularevents.json")
  .success(function (response) {
    
      $scope.popularevents = response.popularEvents;
      });
});


 app.controller('EventDetailViewController', function($scope,$http) {
      $http.get("js/eventDetail.json")
  .success(function (response) {
      $scope.eventdetails = response;
      });
 });
 
  app.controller('MainpageController', function($scope,$http) {
    
 });

 
 app.config(function($routeProvider, $locationProvider) {
     console.log($routeProvider);
  $routeProvider
   .when('/eventdetailview', {
    templateUrl: 'index.php/home/eventdetailview',
    controller: 'EventDetailViewController'
   
  }) 
          .when('/homepage', {
    templateUrl: 'index.php/home/homepage',
    controller: 'MainpageController'
   
  })
          .otherwise({
              redirectTo:'/homepage'
          });
  // configure html5 to get links working on jsfiddle
  $locationProvider.html5Mode(true);
});
