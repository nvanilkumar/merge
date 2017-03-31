angular.module('homeModule').controller("filterController", ['$scope', '$http', function ($scope, $http) {
        $scope.selectedCityId = 0;
        $scope.splcitystateId = 0;
        $scope.selectedCountryId = 0;
        if (typeof (defaultCityId) != "undefined" && defaultCityId != null) {
            $scope.selectedCityId = defaultCityId;
        }
        if (typeof (defaultSplCityStateId) != "undefined" && defaultSplCityStateId != null) {
            $scope.splcitystateId = defaultSplCityStateId;
        }
        if (typeof (defaultCountryId) != "undefined" && defaultCountryId != null) {
            $scope.selectedCountryId = defaultCountryId;
        }

        $scope.selectedCityName = '';
        $scope.selectedCategoryId = '';
        $scope.selectedCustomFilterId = '';
        $scope.selectedSubcategoryId = '';

        $scope.typeValue = '';

        //Work around to fix the dayType value
        $scope.selectedCustomFilterName = '';
        $scope.cityList = '';
        $scope.categoryList = "";
        $scope.customFilterList = "";

        $scope.customDateValue = '';

        $scope.pageValue = 1;
        $scope.limitValue = 12;

        $scope.init = function (data, type) {
            if (type == 'city')
                $scope.cityList = data;
            if (type == 'category')
                $scope.categoryList = data;
            if (type == 'customFilter') {
                $scope.customFilterList = data;
                $scope.customDateValue = '';
                $scope.$watch('customDateValue', function () {

                });
            }
            if (type == 'subCategoryList')
                $scope.subCategoryList = data;

        }

        $scope.getSubCategoryCount = function () {
            $("#isSubCatClosed").val("1");
            var url = '';
            var data = {};
            data.countryId = $scope.selectedCountryId;
            if ($scope.selectedCityId > 0) {
                if ($scope.splcitystateId > 0) {
                    data.stateId = $scope.splcitystateId;
                } else {
                    data.cityId = $scope.selectedCityId;
                }
            }
            data.categoryId = $scope.selectedCategoryId;
            $scope.init('', 'subCategoryList');
            url = api_subcategoryEventsCount;
            data.eventType = 'nonwebinar';
            $("#dvLoading").show();
            $http({
                method: 'GET',
                url: url,
                params: data,
                async: false,
                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}
            }).success(function (data, status, headers, config) {

                $scope.init(data.response.count, 'subCategoryList');
                $("#dvLoading").hide();

            }).error(function (data, status, headers, config) {

                window.location.href = site_url;
            });
        }
        var cookieName = '', cookieValue = '';
        var cookieName1 = '', cookieValue1 = '';
        $scope.setFilter = function (type, filterId, filterName, onSearch, splcitystateid, functionName) {

            $("#dvLoading").show();
            if (type == 'city') {
                if (onSearch != 1) {
                    $scope.$broadcast('angucomplete-alt:clearInput', 'ex5');
                }
                $scope.selectedCityId = filterId;
                $scope.splcitystateId = splcitystateid || 0;
                if (filterName === "All Cities") {
                    filterName = defaultCountryName;
                }
                $scope.selectedCityName = filterName;
                cookieName = 'cityId';
                cookieValue = filterId;
                cookieName1 = 'splCityStateId';
                cookieValue1 = splcitystateid || 0;


                $('.filterdiv').slideUp();
                $(".showSubCategories").html("Show Sub Categories");
                $("#showSubCategoriesAnchor").html("Show Sub Categories");
                var input = {names: [cookieName, cookieName1], values: [cookieValue, cookieValue1]};
                if (functionName != 'reset') {
                    updateCookieservice(input);
                }
            }
            if (type == 'category') {
                $scope.selectedCategoryId = filterId;
                $scope.selectedSubcategoryId = "";
                if ($scope.selectedCategoryId == 0) {
                    $('#subcat').css('display', 'none');
                    $('#mobsubcat').css("cssText", "display: none !important;");
                    $('#subcat2').css('display', 'none');
                    $('#subcat1').css('display', 'none');
                } else {
                    $('#subcat').css('display', '');
                    $('#mobsubcat').css('display', '');
                    $('#subcat2').css('display', '');
                    $('#subcat1').css('display', '');
                }
                if (onSearch != 1) {
                    $scope.$broadcast('angucomplete-alt:clearInput', 'subCat');
                }
                $(".showSubCategories").html("Show Sub Categories");
                $("#showSubCategoriesAnchor").html("Show Sub Categories");
                $('.filterdiv').slideUp();
                var input = {names: ['subCategoryId', 'categoryId'], values: [0, filterId]};
                if (functionName != 'reset') {
                    updateCookieservice(input);
                }
            }
            if (type == 'CustomFilter')
            {
                $scope.selectedCustomFilterId = filterId;
                //Work around to fix the dayType value
                $scope.selectedCustomFilterName = filterName;
                if (onSearch != 1) {
                    $('.custom_date').val("");
                }
                cookieName = 'dayFilter';
                cookieValue = filterId;
                $(".showSubCategories").html("Show Sub Categories");
                $("#showSubCategoriesAnchor").html("Show Sub Categories");
                $('.filterdiv').slideUp();
                var input = {names: ['dayFilter'], values: [filterId]};
                if (functionName != 'reset') {
                    updateCookieservice(input);
                }
            }

            if (type == 'Subcategory') {
                if (onSearch != 1) {
                    $scope.$broadcast('angucomplete-alt:clearInput', 'subCat');
                }
                $scope.selectedSubcategoryId = filterId;
                $('.filterdiv').slideUp();
                var input = {names: ['subCategoryId'], values: [filterId]};
                if (functionName != 'reset') {
                    updateCookieservice(input);
                }
                $('.filterdiv .subcategorysearch #subCat_value').val(filterName);
            }
            if (filterName != 'Custom Date') {
                $('.' + type + 'Class').html(filterName);
            }

            if (filterName == 'All Categories') {
                $('.' + type + 'Class').html('All');
            }
            //Call reder functions here
            if (type == 'city' && functionName != 'reset') {
                $scope.getEventsHappeningCount($scope.selectedCountryId, $scope.selectedCityId, functionName);
            }
            $scope.pageValue = 1;
            $scope.limitValue = 12;
            //alert(type);
            if (type != 'CustomFilter' && type != 'Subcategory' && functionName != 'reset')
            {
                $scope.getTopBannersList($scope.selectedCountryId, $scope.selectedCityId, $scope.selectedCategoryId, functionName);
                $scope.getBottomBannersList($scope.selectedCountryId, $scope.selectedCityId, $scope.selectedCategoryId);
            }
            if (functionName != 'reset') {
                $scope.getEventList($scope.selectedCountryId, $scope.selectedCityId, $scope.selectedCategoryId, $scope.selectedSubcategoryId, $scope.selectedCustomFilterId, $scope.typeValue, $scope.pageValue, $scope.limitValue, $scope.splcitystateId, functionName);
            }
            if ($('#viewMoreCat').attr('aria-expanded')) {
                $scope.viewMoreSubcategories('filter');
            }
        };

        // subcategory List in View More

        $scope.viewMoreSubcategories = function (sourceAction) {
            //if($('#viewMoreCat').attr('aria-expanded')=='false'){
            var city = $scope.selectedCityId;
            var country = $scope.selectedCountryId;
            if (sourceAction != 'filter') {
                $("#dvLoading").show();
            }

            var inputData = '?countryId=' + country + '&major=0&eventType=nonwebinar';
            if (city != 0 && city != '') {
                if ($scope.splcitystateId > 0) {
                    inputData += '&stateId=' + $scope.splcitystateId;
                } else {
                    inputData += '&cityId=' + city;
                }
            }
            inputData += '&eventMode=0';
            $http({
                method: 'GET',
                url: api_subcategoryEventsCount + inputData,
                async: false,
                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}  // set the headers so angular passing info as form data (not request payload)
            }).success(function (data, status, headers, config) {
                //console.dir(data);
                $("#dvLoading").hide();
                $scope.subcatErrorMessage = '';
                var totalSubcats = data.response.count.length;
                if (totalSubcats == 0) {
                    $scope.subcatErrorMessage = data.response.messages[0];
                }
                var subcatEvents = [];
                //console.log(data.response);
                var subcatData = '';
                $.each(data.response.count, function (key, value) {
                    subcatEvents.push(value);
                });
                $scope.subcatEventsData = subcatEvents;
            }).error(function (data, status, headers, config) {
                $("#dvLoading").hide();
                $scope.subcatErrorMessage = data.response.messages[0];
                window.location.href = site_url;
            });
            //}
        }

        $scope.reset = function () {
            $("#dvLoading").show();

            $scope.pageValue = 1;
            $scope.limitValue = 12;
            $scope.selectedCityId = 0;
            $scope.selectedCategoryId = 0;
            $scope.selectedCustomFilterId = 6;
            $scope.selectedSubcategoryId = 0;
            $scope.splcitystateId = 0;
            $('.custom_date').val("");
            $('.CustomFilterClass').html('All Time');
            $(".showSubCategories").html("");
            $("#showSubCategoriesAnchor").html("");
            $('#subcat').css('display', 'none');
            $('#mobsubcat').css("cssText", "display: none !important;");
            $('#subcat2').css('display', 'none');
            $('#subcat1').css('display', 'none');
            $('.categoryClass').html('All');
            $('.cityClass').html(defaultCountryName);
            $scope.selectedCityName = 'All Cities';
            $scope.selectedCustomFilterName = 'All Time';

            $scope.$broadcast('angucomplete-alt:clearInput', 'ex5');
            $scope.$broadcast('angucomplete-alt:clearInput', 'subCat');
            $scope.$broadcast('angucomplete-alt:clearInput', 'subCat');

            var input = {names: ['cityId', 'categoryId', 'dayFilter', 'subCategoryId', 'splCityStateId'], values: [$scope.selectedCityId, $scope.selectedCategoryId, $scope.selectedCustomFilterId, $scope.selectedSubcategoryId, $scope.splcitystateId]};
            updateCookieservice(input);
            $('.filterdiv').hide();
            $scope.getEventList($scope.selectedCountryId, $scope.selectedCityId, $scope.selectedCategoryId, 0, $scope.selectedCustomFilterId, 0, $scope.pageValue, $scope.limitValue, 0, 'reset');

            //Work around to fix the dayType value
            $scope.getEventsHappeningCount($scope.selectedCountryId, 0);
            $scope.getTopBannersList($scope.selectedCountryId, '', '');
            $scope.getBottomBannersList($scope.selectedCountryId, '', '', 'reset');
            
            if ($('#viewMoreCat').attr('aria-expanded')) {
                $scope.viewMoreSubcategories('filter');
            }

        };

        $scope.indexedArray = function (arrayObject, type) {
            var obj = {};
            angular.forEach(arrayObject.response.count, function (value, key) {


                if (type == 'city')
                    var key = value.type + '-' + value.id;
                if (type == 'category')
                    var key = value.id;
                if (type == 'customFilter')
                    var key = value.id;
                obj[key] = value.count;
            });
            return obj;
        }


        $scope.updateCityCount = function (arrayObject, obj) {
            angular.forEach(arrayObject, function (item) {

                var typeVal = 'city';
                var itemId = item.id;
                if (item.splcitystateid > 0)
                {
                    var typeVal = 'state';
                    var itemId = item.splcitystateid;
                }
                var keyVal = typeVal + '-' + itemId;

                if (obj[keyVal] > 0)
                {
                    item.eventCount = "(" + obj[keyVal] + ")";
                    $('#' + item.id + '_city').css('display', 'inline-block');
                    $('#' + item.id + '_mobcity').css('display', 'inline-block');
                    $('#' + item.id + '_scrollcity').css('display', 'inline-block');
                } else {
                    $('#' + item.id + '_city').css('display', 'none');
                    $('#' + item.id + '_mobcity').css("cssText", "display: none !important;");
                    $('#' + item.id + '_scrollcity').css('display', 'none');
                }
            });
        }


        $scope.updateCount = function (arrayObject, obj, type) {
            angular.forEach(arrayObject, function (item) {

                if (obj.hasOwnProperty(item.id))
                {
                    if (obj[item.id] > 0) {
                        item.eventCount = "(" + obj[item.id] + ")";
                        $('#' + item.id + '_category').css('display', 'inline-block');
                        $('#' + item.id + '_mobcategory').css('display', 'inline-block');
                        $('#' + item.id + '_scrollctg').css('display', 'inline-block');
                        if (type == 'customFilter') {
                            $('#' + item.id + '_dates').css('display', 'inline-block');
                            $('#' + item.id + '_scrolldt').css('display', 'inline-block');
                        }
                    }
                    else {
                        if (type == 'customFilter') {
                            $('#' + item.id + '_dates').css("cssText", "display: none !important;");
                            $('#' + item.id + '_scrolldt').css('display', 'none');
                        }
                        $('#' + item.id + '_category').css('display', 'none');
                        $('#' + item.id + '_mobcategory').css("cssText", "display: none !important;");
                        $('#' + item.id + '_scrollctg').css('display', 'none');

                    }
                } else if (type == 'customFilter' && item.id == 6)
                {
                    item.eventCount = "";
                } else {
                    $('#' + item.id + '_category').css('display', 'none');
                    $('#' + item.id + '_mobcategory').css("cssText", "display: none !important;");
                    $('#' + item.id + '_scrollctg').css('display', 'none');
                }
                //item.eventCount = "(0)";
            });
        }

        $scope.defaultFilter = function () {
            $scope.selectedCityId = defaultCityId;
            $scope.selectedCountryId = defaultCountryId;
            var cityName = "";
            if (defaultCityName === "All Cities") {
                cityName = defaultCountryName;
            } else {
                cityName = defaultCityName;
            }
            $scope.selectedCityName = cityName;
        }
        $scope.getEventCount = function (input, filterType, functionName) {
            var url = '';
            var data = {};
            data.countryId = $scope.selectedCountryId;
            $("#dvLoading").show();
            if (filterType == 'city')
            {
                $(".showSubCategories").html("Show Sub Categories");
                $("#showSubCategoriesAnchor").html("Show Sub Categories");

                url = api_cityEventsCount;
                if ($scope.selectedCategoryId > 0)
                    data.categoryId = $scope.selectedCategoryId;
                if ($scope.selectedSubcategoryId > 0)
                    data.subcategoryId = $scope.selectedSubcategoryId;
                if ($scope.selectedCustomFilterId > 0) {
                    data.day = $scope.selectedCustomFilterId;
                    if ($scope.selectedCustomFilterId == 7)
                        data.dateValue = $('#datepicker').val();
                }
            }

            if (filterType == 'category')
            {
                $(".showSubCategories").html("Show Sub Categories");
                $("#showSubCategoriesAnchor").html("Show Sub Categories");

                url = api_categoryEventsCount;
                data.major = 0;
                if ($scope.selectedCityId > 0) {
                    if ($scope.splcitystateId > 0) {
                        data.stateId = $scope.splcitystateId;
                    } else {
                        if ($scope.splcitystateId > 0) {
                            data.stateId = $scope.splcitystateId;
                        } else {
                            data.cityId = $scope.selectedCityId;
                        }
                    }
                }
                if ($scope.selectedCategoryId > 0)
                    data.categoryId = $scope.selectedCategoryId;
                if ($scope.selectedCustomFilterId > 0)
                {
                    data.day = $scope.selectedCustomFilterId;
                    if ($scope.selectedCustomFilterId == 7)
                        data.dateValue = $('#datepicker').val();
                }
            }
            if (filterType == 'customFilter')
            {
                $(".showSubCategories").html("Show Sub Categories");
                $("#showSubCategoriesAnchor").html("Show Sub Categories");

                url = api_filterEventsCount;
                if ($scope.selectedCityId > 0) {
                    if ($scope.splcitystateId > 0) {
                        data.stateId = $scope.splcitystateId;
                    } else {
                        data.cityId = $scope.selectedCityId;
                    }
                }
                if ($scope.selectedCategoryId > 0)
                    data.categoryId = $scope.selectedCategoryId;
            }

            data.eventType = 'nonwebinar';
            data.eventMode = 0;
            $http({
                method: 'GET',
                url: url,
                params: data,
                async: false,
                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}
            }).success(function (data, status, headers, config) {


                var obj = $scope.indexedArray(data, filterType);
                if (filterType == 'city') {
                    $scope.updateCityCount($scope.cityList, obj);
                    $scope.allCityCount = "(" + data.response.allCount + ")";
                }
                else if (filterType == 'category') {
                    $scope.updateCount($scope.categoryList, obj, filterType);
                    $scope.allCategoryCount = "(" + data.response.allCount + ")";
                }
                else if (filterType == 'customFilter') {
                    $scope.updateCount($scope.customFilterList, obj, filterType);
                }

                if (functionName != 'reset') {
                    $("#dvLoading").hide();
                }

                //  location.reload();
            }).error(function (data, status, headers, config) {
                $("#dvLoading").hide();
                console.log('getEventCount Something went wrong please try again');
            });

        }

        $scope.getEventsHappeningCount = function (country, city) {
            var inputData = '?countryId=' + country + '&major=0&eventType=nonwebinar';
            if (city != 0 && city != '') {
                if ($scope.splcitystateId > 0) {
                    inputData += '&stateId=' + $scope.splcitystateId;
                } else {
                    inputData += '&cityId=' + city;
                }
            }
            inputData += '&eventMode=0';
            //getting category Events
            $http({
                method: 'GET',
                url: api_categorycityEventsCount + inputData,
                async: false,
                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}  // set the headers so angular passing info as form data (not request payload)
            }).success(function (data, status, headers, config) {
                var arr = [];
                $.each(data.response.count, function (key, value) {
                    arr.push(value);
                });
                $scope.eventsData = arr;
                $('#totalCount').text(data.response.total);
                if (data.response.total == 0) {
                    $('.eventsCat').css('display', 'none');
                } else {
                    $('.eventsCat').css('display', 'block');
                }
            }).error(function (data, status, headers, config) {
                window.location.href = site_url;
            });
            /*// on click of view more subcategory events       
             */
        }
        $scope.getTopBannersList = function (country, city, category) {
            $('.carousel-inner').hide();
            var inputData = '?countryId=' + country;

            if (city > 0)
                inputData += '&cityId=' + city;
            if (category > 0)
                inputData += '&categoryId=' + category
            inputData += '&type=1';
            $http({
                method: 'GET',
                url: api_bannerList + inputData,
                async: false,
                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}  // set the headers so angular passing info as form data (not request payload)
            }).success(function (data, status, headers, config) {
                $('.carousel-inner').show();
                if (data.response.total > 0) {
                    var totalTopBanners = data.response.total;
                    // $('.carousel-indicators').empty();
                    $('.carousel-inner .item').remove();
                    for (var bLoop = 0; bLoop < totalTopBanners; bLoop++) {
                        var bannerSlide = data.response.bannerList[bLoop];
                        var bannerTitle = bannerSlide.title;
                        var bannerImage = bannerSlide.bannerImage || 0;
                        var bannerUrl = bannerSlide.url;
                        if (typeof (bannerImage) != 'undefined' && bannerImage.length > 0) {
                            $('<div class="item"><a href="' + bannerUrl + '" target="_self"><img src="' + bannerImage + '" title="' + bannerTitle + '" alt="' + bannerTitle + '" target="_self" style="width:100% !important" ></a><div class="carousel-caption"></div></div>').appendTo('.carousel-inner');
                        }

                    }
                    $('.item').first().addClass('active');
                    $('.carousel-indicators > li').first().addClass('active');
                    $('#carousel-example-generic').carousel();
                }
                else
                {
                    $('.carousel-inner').hide();
                }
            }).error(function (data, status, headers, config) {
                window.location.href = site_url;
            });
        }
        $scope.getBottomBannersList = function (country, city, category, functionName) {
            var inputData = '?countryId=' + country;
            if (city > 0)
                inputData += '&cityId=' + city;
            if (category > 0)
                inputData += '&categoryId=' + category

            inputData += '&type=2';
            $http({
                method: 'GET',
                url: api_bannerList + inputData,
                async: false,
                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}  // set the headers so angular passing info as form data (not request payload)
            }).success(function (data, status, headers, config) {

                var totalBottomBanners = data.response.total;

                if (totalBottomBanners > 0) {
                    var htmlData = '';
                    var bottomBannerTitle = data.response.bannerList[0].title;
                    var bottomBannerImage = data.response.bannerList[0].bannerImage;
                    var bottomBannerUrl = data.response.bannerList[0].url;
                    var bottomBannerTarget = "_self";
                    htmlData += '<a href="' + bottomBannerUrl + '" target="' + bottomBannerTarget + '"> <img src="' + bottomBannerImage + '" title="' + bottomBannerTitle + '" alt="' + bottomBannerTitle + '"></a>';
                    $('#bottomBanner').show();
                    $('#bottomBanner').html(htmlData);

                }
                else
                {
                    $('#bottomBanner').hide();
                }

                if (functionName == 'reset') {
                    setTimeout(function () {
                        $("#dvLoading").hide();
                    }, 2000);
                }

            }).error(function (data, status, headers, config) {
                window.location.href = site_url;
            });
        };
        $scope.getEventList = function (country, city, category, subcategory, day, type, page, limit, splcitystateid, functionName) {
            var inputData = '';
            var styleval = 'none';
            inputData += '?countryId=' + country;
            if (city != 0) {
                if (splcitystateid != 0) {
                    inputData += '&stateId=' + splcitystateid;
                } else {
                    inputData += '&cityId=' + city;
                }
            } else if (city == 0) {
                styleval = 'block';
            }
            if (category != 0)
                inputData += '&categoryId=' + category;
            if (subcategory != 0)
                inputData += '&subcategoryId=' + subcategory;
            if (day != 0)
                inputData += '&day=' + day;
            if (day == 7) {
                inputData += '&dateValue=' + $('#datepicker').val();
            }
            if (type != 0)
                inputData += '&type=' + type;

            inputData += '&page=' + page + '&limit=' + limit;
            inputData += '&eventMode=0';
            inputData += '&eventType=nonwebinar';
            $("#dvLoading").show();
            //alert(inputData);
            $http({
                method: 'GET',
                url: api_eventList + inputData,
                async: false,
                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}  // set the headers so angular passing info as form data (not request payload)
            }).success(function (data, status, headers, config) {
                var htmlData = '';
                //alert(data.response.total);
                $("#totalResultCount").val("0");
                if (data.response.total > 0) {
                    $("#totalResultCount").val(data.response.total);
                                       
                    // True Semantic Code
                    if (typeof tsa  !== typeof undefined) {
                        var customFilterName = $('.CustomFilterClass').html();
                        var filter = new Array (
                                        customFilterName.charAt(0).toUpperCase() + customFilterName.slice(1)
                                    );
                        console.log($("#totalResultCount").val());
                        console.log($('.cityClass').html());
                        console.log($('.categoryClass').html());
                        console.log(filter);
                        tsa.activity_data = {
                            "sresults":$("#totalResultCount").val(),
                            "scity":$('.cityClass').html(),
                            "scategory":$('.categoryClass').html(),    
                            "ssubcategory":"",
                            "sfilter":filter,
                        };
                        if(typeof window.ts !== typeof undefined) {
                            ts.save_parameters();
                        }
                    }
                    // True Semantic Code Ends Here
                    
                    var totalEvents = data.response.eventList.length; //durgesh
                    for (var i = 0; i < totalEvents; i++)
                    {
                        var event = data.response.eventList[i];
                        var eventURL=event.eventUrl;
                        if(event.eventExternalUrl!=undefined){
                            eventURL=event.eventExternalUrl;
                        }
                        
                        htmlData += '<li class="col-xs-12 col-sm-6 col-md-4 col-lg-4 thumbBlock">';
                        htmlData += '<a href="' + eventURL + '" class="thumbnail">';
                        htmlData += '<div class="eventImg">';
                        htmlData += '<img src="' + event.thumbImage + '" width="" height="" alt="' + event.title + ' " title="' + event.title + '" '
                        htmlData += ' onError="this.src=\'' + event.defaultThumbImage + '\'"'
                        htmlData += ' />';
                        htmlData += '</div><h2>';
                        htmlData += '<span class="eveHeadWrap">' + event.title + '</span>'; /*<span id="saveEvent" class="icon-fave"></span>';*/
                        htmlData += '</h2><div class="info">';
                        htmlData += '<span>' + convertDate(event.startDate) + '</span> ';
                        htmlData += '</div>';
                        htmlData += '<div class="eventCity" style="display:' + styleval + '">';
                        htmlData += '<span>' + event.cityName + '</span></div>';
                        htmlData += '<div class="overlay"><div class="overlayButt"><div class="overlaySocial">';
                        htmlData += '<span class="icon-fb"></span> <span class="icon-tweet"></span>';
                        htmlData += '<span class="icon-google"></span></div></div></div>';
                        htmlData += '</a> <a href="' + eventURL + '" class="category">';
                        htmlData += '<span class="icon1-' + event.categoryName.toLowerCase().replace(" ", "") + ' col' + event.categoryName.toLowerCase().replace(" ", "") + '"></span>';
                        htmlData += '<span class="catName"><em>' + event.categoryName + '</em></span> </a> </li>';
                    }

                    $('ul#eventThumbs').html(htmlData);
                    $('#noRecords').html("");
                    if (data.response.nextPage == false) {
                        // hiding the view more button and showing no more events text
                        $('#viewMoreEvents').css('display', 'none');

                        $('#noMoreEvents').css('display', 'block');
                    } else {
                        $scope.pageValue++; //durgesh   check to abort previous request when sending new request.
                        $('#viewMoreEvents').css('display', 'inline-block');
                        $('#noMoreEvents').css('display', 'none');

                    }
                }
                else
                {
                    if (page == 1) {
                        $('ul#eventThumbs').html(htmlData);
                        $('#noRecords').html(data.response.messages);
                        $('#noMoreEvents').css('display', 'none');
                    }

                    $('#viewMoreEvents').css('display', 'none');
                }
                if (functionName != 'reset') {
                    $("#dvLoading").hide();
                    $('html,body').animate({
                        scrollTop: 0
                    }, 1000);
                }

            }).error(function (data, status, headers, config) {
                window.location.href = site_url;
            });
        }



        $scope.getMoreEvents = function () {

            $("#viewMoreEvents").addClass("loading");

            if ($scope.pageValue == 1)
                $scope.pageValue = 2;

            var inputData = '';
            var styleval = 'none';
            inputData += '?countryId=' + $scope.selectedCountryId;
            if ($scope.selectedCityId > 0) {
                if ($scope.splcitystateId > 0) {
                    inputData += '&stateId=' + $scope.splcitystateId;
                } else {
                    inputData += '&cityId=' + $scope.selectedCityId;
                }
            } else if ($scope.selectedCityId == 0) {
                styleval = 'block';
            }

            if ($scope.selectedCategoryId > 0)
                inputData += '&categoryId=' + $scope.selectedCategoryId;
            if ($scope.selectedSubcategoryId > 0)
                inputData += '&subcategoryId=' + $scope.selectedSubcategoryId;
            if ($scope.selectedCustomFilterId > 0)
                inputData += '&day=' + $scope.selectedCustomFilterId;
            if ($scope.typeValue != 0)
                inputData += '&type=' + $scope.typeValue;
            if ($scope.selectedCustomFilterId == 7) {
                inputData += '&dateValue=' + $('#datepicker').val();
            }

            inputData += '&page=' + $scope.pageValue + '&limit=' + $scope.limitValue; //durgesh
            inputData += '&eventMode=0';
            $http({
                method: 'GET',
                url: api_eventList + inputData,
                async: false,
                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}  // set the headers so angular passing info as form data (not request payload)
            }).success(function (data, status, headers, config) {

                if (data.response.total > 0) {
                    var htmlData = '';
                    var totalEvents = data.response.eventList.length;
                    for (var i = 0; i < totalEvents; i++)
                    {
                        var event = data.response.eventList[i]; //durgesh
                        var eventURL=event.eventUrl
                        if(event.eventExternalUrl!=undefined){
                            eventURL=event.eventExternalUrl;
                        }
                        htmlData += '<li class="col-xs-12 col-sm-6 col-md-4 col-lg-4 thumbBlock">';
                        htmlData += '<a href="' + eventURL + '" class="thumbnail">';
                        htmlData += '<div class="eventImg">';
                        htmlData += '<img src="' + event.thumbImage + '" width="" height="" alt="' + event.title + '" title="' + event.title + '"';
                        htmlData += ' onError="this.src=\'' + event.defaultThumbImage + '\'"'
                        htmlData += ' />';
                        htmlData += '</div><h2>';
                        htmlData += '<span class="eveHeadWrap">' + event.title + '</span>'; /*<span id="saveEvent" class="icon-fave"></span>'; */
                        htmlData += '</h2><div class="info">';
                        htmlData += '<span>' + convertDate(event.startDate) + '</span> ';
                        htmlData += '</div>';
                        htmlData += '<div class="eventCity" style="display:' + styleval + '">';
                        
                        if (event.cityName != '' && event.cityName != 'undefined') {
                            htmlData += '<span>' + event.cityName + '</span>';
                        }
                        
                        htmlData += '</div><div class="overlay"><div class="overlayButt"><div class="overlaySocial">';
                        htmlData += '<span class="icon-fb"></span> <span class="icon-tweet"></span>';
                        htmlData += '<span class="icon-google"></span></div></div></div>';
                        htmlData += '</a> <a href="' + eventURL + '" class="category">';
                        htmlData += '<span class="icon1-' + event.categoryName.toLowerCase().replace(" ", "") + ' col' + event.categoryName.toLowerCase().replace(" ", "") + '"></span>';
                        htmlData += '<span class="catName"><em>' + event.categoryName + '</em></span> </a> </li>';
                    }

                    $('ul#eventThumbs').append(htmlData);
                    $scope.pageValue++; //durgesh
                    $("#viewMoreEvents").removeClass("loading");
                    //$('#currentLimit').val(data.response.limit); //durgesh

                    if (data.response.nextPage == false) {
                        // hiding the view more button and showing no more events text
                        $('#viewMoreEvents').css('display', 'none');
                        $('#noMoreEvents').css('display', 'block');

                    }
                    else if (data.response.nextPage == true)
                    {
                        $('#viewMoreEvents').css('display', 'inline-block');
                        $('#noMoreEvents').css('display', 'none');
                    }

                } // end of data.response.total condition
                else
                {
                    if ($scope.pageValue == 1)
                        $('ul#eventThumbs').append(htmlData);

                    $('#viewMoreEvents').css('display', 'none');
                    $('#noMoreEvents').css('display', 'block');
                }


            }).error(function (data, status, headers, config) {
                window.location.href = site_url;
            });
        }

        $scope.citySearchValue = '';
        $scope.subCatSearchValue = '';
        $scope.cityChanged = function (str) {
            $scope.citySearchValue = str;
        }
        $scope.remoteRequestCity = function (str) {
            return {major: 0, keyWord: str, countryId: $scope.selectedCountryId, limit: 5};

        }
        $scope.citySelected = function (selected) {
            $('#ex5 div input').val(selected.title);
            $scope.setFilter('city', selected.originalObject.id, selected.title, 1, 0);

        };
        $scope.subcategorySelected = function (selected) {
            $scope.setFilter('Subcategory', selected.originalObject.id, selected.title, 1, 0);
        };
        $scope.remoteRequestSubCat = function (str) {
            return {major: 1, keyword: str, categoryId: $scope.selectedCategoryId, limit: 5};

        };
        $scope.subCatChanged = function (str) {
            // alert('test');
            $scope.subCatSearchValue = str;
        };
    }]);