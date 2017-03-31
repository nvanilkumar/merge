angular.module('eventModule').directive('tickets', function() {

    return {
        scope: {
          countVal:'=',
          callCounter:'&',
          modelName:'&',
          lastValue:'=',
          registrationType:'=',
          jqNowDate:'&',
          jqTicketDefaultDate:'&',
          ticketData:"&"
        },
        link: function(scope, element, attrs) {
//console.log('@@');
//console.log(scope.countVal);
//console.log(scope.ticketData(scope.countVal));
//           scope.ticketOldData= scope.ticketDetails[0];
           
           
            var radioButton= element.find('.soldout');
            $(radioButton).each(function () {
                $(this).wrap("<span class='custom-checkbox'></span>");
                if ($(this).is(':checked')) {
                    $(this).parent().addClass("selected");
                }
                $(this).bind("click",function () {
                    $(this).parent().toggleClass("selected");
                    
                });
            });
            
            
        },
        templateUrl: '/dashboard/event/ticket_view'
    };
});


angular.module('eventModule').directive("ngFileSelect",function(){
  return {
    link: function($scope,el,attr){
      el.bind("change", function(e){
        $scope.fileId = e.currentTarget.id;
        $scope.file = (e.srcElement || e.target).files[0];
        if ($scope.fileId == 'bannerImage') {
            $scope.getBannerFile();
        } else {
            $scope.getThumbFile();
        }
        
      });
    }
  }
});


angular.module('eventModule').directive('jqdatepicker', function($timeout) {
    var linker = function(scope, element, attrs) {
        $timeout( function(){
            element.datepicker({
                    minDate: "0",
                    changeMonth: true,
                    numberOfMonths: 1,
                    dateFormat: "mm/dd/yy",
                    onClose: function(selectedDate) {
                        if(attrs.startdate){
                            var endDateId = element.attr('id').replace('startdate', '');
                            $("#enddate"+endDateId).datepicker("option", "minDate", selectedDate);
                            
                            
                        }
                    }
                });
        });
    }

    return {
        restrict: 'A',
        link: linker,
    };
});

angular.module('eventModule').directive('jqtimepicker',function($timeout){
    var linker = function(scope, element, attrs) {
            $timeout( function(){
                element.timepicker({
            });
        });
    }
    return {
        restrict: 'A',
        link: linker,
    }
    
});