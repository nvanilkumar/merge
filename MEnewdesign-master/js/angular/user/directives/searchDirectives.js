angular.module('searchModule').directive('angDatepicker', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attrs, init) {
            element.datepicker({
                minDate: "0",
                //dateFormat: 'MM/DD/yyyy',
                onSelect: function (date) {
                    var id = element.attr('filter-id');
                    var name = element.attr('filter-name');

                    // console.log(id+'---'+name);
                    scope.customDateValue = date;
                    $('.CustomFilterClass').html(date);
                    $('.filterdiv').slideUp('slow');                    
                    $(".showSubCategories").html("Show Sub Categories");
                    $("#showSubCategoriesAnchor").html("Show Sub Categories");
                    $('.filterDate').show().contents().last()[0].textContent = date;
                    $('.custom_date').val(date);
                    scope.$apply();
                    // console.log(scope.customDateValue);
                    scope.setFilter('CustomFilter', id, name, 1);
                }
            });
        }
    };
});