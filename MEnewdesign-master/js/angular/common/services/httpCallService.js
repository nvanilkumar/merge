
angular.module('httpCallModule').service('httpCallService',['$http',function($http){
        
        var httpObj={};
        

        
                    // (postData);
         httpObj.callUrl= function(method,url,data,headers){ 
             

                 
         var returnResponse=    $http({
                method: method,
                url: url,
                data: data,
                headers: {'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}// set the headers 
            });
            
            
            return returnResponse;
         };
         
         return httpObj;
         
}
    ]);