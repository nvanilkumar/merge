angular.module('homeModule').filter('slice', function () {
    return function (arr, start, end) {
        return (arr || []).slice(start, end);
    };
});
angular.module('homeModule').filter('replaceSpaceFilter', function(){
      return function(text) {
            if(undefined !== text && text.length > 0){
                text=text.replace(/ /g,'');
                return text;
            }
            return text;
            
           
      };
});