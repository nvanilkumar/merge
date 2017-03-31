angular.module('filterModule').filter('range', function(){
  return function(input, total) {
    total = parseInt(total);
    for (var i=1; i<=total; i++)
      input.push(i);
//  $scope.array=input;
    return input;
  };
});
