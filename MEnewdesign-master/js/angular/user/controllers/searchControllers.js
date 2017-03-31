angular.module('searchModule').controller("filterController", ['$scope', '$http', function ($scope, $http) {
        $scope.selectedCityId = defaultCityId;
        $scope.splcitystateId = defaultSplCityStateId;
        $scope.selectedCityName = '';
        $scope.selectedCategoryName = defaultCategoryName;
        if (getCookie('subCategoryName') == '') {
            defaultSubCategoryName = 'SubCategories';
        }
        $scope.selectedSubCategoryName = defaultSubCategoryName;
        $scope.selectedCategoryClass = defaultCategoryName.replace(" ", "");
        $scope.selectedCategoryId = defaultCategoryId;
        $scope.selectedCustomFilterId = '';
        $scope.selectedSubcategoryId = defaultSubCategoryId;
        $scope.selectedCountryId = defaultCountryId;
        $scope.typeValue = '';
        $scope.typeName = 'All';
        $scope.keyword = $("#searchId").val();
        $scope.freeCount = 0;
        $scope.paidCount = 0;
        $scope.noRegCount = 0;
        $scope.allReg = 0;
        $scope.webinarCount = 0;
        $scope.totalResultCount = totalResultCount;
        $scope.matchingKeyword = defaultMatchingKeyword;
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
            var subtotalcount = 0;
            $.each(data, function (key, value) {
                subtotalcount += value.count;
            });
            $scope.allsubCategoryCount = subtotalcount;
        };

        $scope.reset = function () {

            $("#dvLoading").show();
            $scope.pageValue = 1;
            $scope.limitValue = 12;
            $scope.selectedCityId = 0;
            $scope.selectedCategoryId = 0;
            $scope.selectedCustomFilterId = 6;
            $scope.selectedSubcategoryId = 0;
            $scope.splcitystateId = 0;
            $scope.typeValue = '';
            $scope.typeName = 'All';
            $('.custom_date').val("");
            $('.CustomFilterClass').html('Time');
            $(".showSubCategories").html("");
            $("#showSubCategoriesAnchor").html("");
            $('#subcat').css("cssText", "display: none !important;");
            $('#subcat2').css('display', 'none');
            $('#subcat1').css('display', 'none');
            $('.categoryClass').html('All Categories');
            $scope.selectedCategoryName = 'All categories';
            $scope.selectedCategoryClass = 'AllCategories';
            $('.subCategoryClass').text('Subcategories');
            $('.cityClass').text(defaultCountryName);
            $scope.selectedCityName = 'All Cities';
            $scope.selectedCustomFilterName = 'Time';

            $scope.$broadcast('angucomplete-alt:clearInput', 'ex5');
            $scope.$broadcast('angucomplete-alt:clearInput', 'subCat');
            $scope.$broadcast('angucomplete-alt:clearInput', 'subCat');

            var input = {names: ['cityId', 'categoryId', 'dayFilter', 'subCategoryId', 'splCityStateId'], values: [$scope.selectedCityId, $scope.selectedCategoryId, $scope.selectedCustomFilterId, $scope.selectedSubcategoryId, $scope.splcitystateId]};
            updateCookieservice(input);
            $('.filterdiv').hide();
            $scope.keyword = $("#searchId").val();
            $scope.getEventList($scope.selectedCountryId, $scope.selectedCityId, $scope.selectedCategoryId,
                    $scope.selectedSubcategoryId, $scope.selectedCustomFilterId, $scope.typeValue,
                    $scope.pageValue, $scope.limitValue, $scope.typeName, $scope.keyword, $scope.splcitystateId);
        };

        $scope.getEventRegCount = function () {
            var url = '';
            var data = {};
            var keyword = $("#searchId").val();
            data.countryId = $scope.selectedCountryId;
            if ($scope.selectedCityId != 0) {
                if ($scope.splcitystateId != 0) {
                    data.stateId = $scope.splcitystateId;
                } else {
                    data.cityId = $scope.selectedCityId;
                }
            }
            data.categoryId = $scope.selectedCategoryId;

            if ($scope.selectedSubcategoryId > 0)
                data.subcategoryId = $scope.selectedSubcategoryId;

            if (keyword != '' && keyword != 0 && typeof (keyword) != "undefined") {
                data.keyword = keyword;
            }
            if ($scope.selectedCustomFilterId > 0) {
                data.day = $scope.selectedCustomFilterId;
                if ($scope.selectedCustomFilterId == 7) {
                    data.dateValue = $('#datepicker').val();
                }
            }
            url = api_eventEventsCount;
            $http({
                method: 'GET',
                url: url,
                params: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}
            }).success(function (data, status, headers, config) {
                var countData = data.response.eventCountByRegTypeList;
                $scope.freeCount = countData[1];
                $scope.paidCount = countData[2];
                $scope.noRegCount = countData[3];
                $scope.webinarCount = countData[4];
                $scope.allReg = countData[5];
                for (var j = 1; j <= 4; j++) {
                    if (countData[j] == 0) {
                        $('#' + j + '_type').css('display', 'none');
                    } else {
                        $('#' + j + '_type').css('display', 'inline-block');
                    }
                }
            }).error(function (data, status, headers, config) {

                window.location.href = site_url;
            });
        };

        $scope.getSubCategoryCount = function () {
            $("#isSubCatClosed").val("1");
            var url = '';
            var data = {};
            data.countryId = $scope.selectedCountryId;
            if ($scope.selectedCityId != 0) {
                if ($scope.splcitystateId != 0) {
                    data.stateId = $scope.splcitystateId;
                } else {
                    data.cityId = $scope.selectedCityId;
                }
            }
            data.categoryId = $scope.selectedCategoryId;
            if ($scope.typeName != '' && $scope.typeName != 'All') {
                data.eventType = $scope.typeName;
            }
            var keyword = $("#searchId").val();
            if (keyword != '' && keyword != 0 && typeof (keyword) != "undefined") {
                data.keyword = keyword;
            }
            if ($scope.selectedCustomFilterId > 0) {
                data.day = $scope.selectedCustomFilterId;
                if ($scope.selectedCustomFilterId == 7) {
                    data.dateValue = $('#datepicker').val();
                }
            }
            url = api_subcategoryEventsCount;
            $http({
                method: 'GET',
                url: url,
                params: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}
            }).success(function (data, status, headers, config) {
                $scope.init(data.response.count, 'subCategoryList')

            }).error(function (data, status, headers, config) {
                window.location.href = site_url;
            });
        }
        var cookieName = '', cookieValue = '';
        var cookieName1 = '', cookieValue1 = '';
        $scope.setFilter = function (type, filterId, filterName, onSearch, splcitystateid) {
            if (type == 'city') {
                if (onSearch != 1) {
                    $scope.$broadcast('angucomplete-alt:clearInput', 'ex5');
                }
                $scope.selectedCityId = filterId;
                $scope.splcitystateId = splcitystateid;
                if (filterName === "All Cities") {
                    filterName = defaultCountryName;
                }
                $scope.selectedCityName = filterName;
                cookieName = 'cityId';
                cookieValue = filterId;
                cookieName1 = 'splCityStateId';
                cookieValue1 = splcitystateid;

                $('.filterdiv').slideUp();
                $(".showSubCategories").html("Show Sub Categories");
                $("#showSubCategoriesAnchor").html("Show Sub Categories");
                var input = {names: [cookieName, cookieName1], values: [cookieValue, cookieValue1]};
                updateCookieservice(input);
            }
            if (type == 'category') {
                $scope.selectedCategoryId = filterId;
                $scope.selectedSubcategoryId = "";
                $scope.selectedSubCategoryName = "SubCategories";
                $scope.selectedCategoryClass = filterName.replace(" ", "");
                $scope.selectedCategoryName = filterName;

                if ($scope.selectedCategoryId == 0) {
                    $('#subcat').css("cssText", "display: none !important;");
                    $('#subcat1').css('display', 'none');
                } else {
                    $('#subcat').css('display', '');
                    $('#subcat1').css('display', '');
                }
                if (onSearch != 1) {
                    $scope.$broadcast('angucomplete-alt:clearInput', 'subCat');
                }
                $(".showSubCategories").html("Show Sub Categories");
                $('.subCategoryClass').html(' Subcategories ');
                //     $scope.getSubCategoryCount();
                $("#showSubCategoriesAnchor").html("Show Sub Categories");
                $('.filterdiv').slideUp();
                var input = {names: ['subCategoryId', 'categoryId'], values: [0, filterId]};
                updateCookieservice(input);
            }
            if (type == 'CustomFilter')
            {
//              console.dir($scope);
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
                updateCookieservice(input);
            }

            if (type == 'Subcategory') {
                if (onSearch != 1) {
                    $scope.$broadcast('angucomplete-alt:clearInput', 'subCat');
                }
                $scope.selectedSubcategoryId = filterId;
                $scope.selectedSubCategoryName = filterName;
                $('.filterdiv').slideUp();
                $('.subCategoryClass').text(filterName);
                var input = {names: ['subCategoryId'], values: [filterId]};
                updateCookieservice(input);

            }
            if (type == 'freepaid') {
                $scope.typeValue = filterId;
                $scope.typeName = filterName.charAt(0).toUpperCase() + filterName.slice(1);
                $('.filterdiv').slideUp();
            }
            if (filterName != 'Custom Date') {
                $('.' + type + 'Class').html(filterName);
            }
            $scope.pageValue = 1;
            $scope.limitValue = 12;
            var keyword = $("#searchId").val();
            if (keyword != '' && keyword != 0 && typeof (keyword) != "undefined") {
                $scope.getEventList($scope.selectedCountryId, $scope.selectedCityId, $scope.selectedCategoryId, $scope.selectedSubcategoryId, $scope.selectedCustomFilterId, $scope.typeValue, $scope.pageValue, $scope.limitValue, $scope.typeName, keyword, $scope.splcitystateId);
            } else {
                $scope.getEventList($scope.selectedCountryId, $scope.selectedCityId, $scope.selectedCategoryId, $scope.selectedSubcategoryId, $scope.selectedCustomFilterId, $scope.typeValue, $scope.pageValue, $scope.limitValue, $scope.typeName, keyword, $scope.splcitystateId);
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
        };

        $scope.getEventCount = function (input, filterType) {

            var url = '';
            var keyword = $("#searchId").val();
            var data = {};
            data.countryId = $scope.selectedCountryId;
            if (filterType == 'city')
            {
                $(".showSubCategories").html("Show Sub Categories");
                $("#showSubCategoriesAnchor").html("Show Sub Categories");
                if ($scope.typeName != '' && $scope.typeName != 'All') {
                    data.eventType = $scope.typeName;
                }

                url = api_cityEventsCount;
                if ($scope.selectedCategoryId > 0) {
                    data.categoryId = $scope.selectedCategoryId;
                }
                if ($scope.selectedSubcategoryId > 0) {
                    data.subcategoryId = $scope.selectedSubcategoryId;
                }
                if ($scope.selectedCustomFilterId > 0) {
                    data.day = $scope.selectedCustomFilterId;
                    if ($scope.selectedCustomFilterId == 7) {
                        data.dateValue = $('#datepicker').val();
                    }
                }
                if (keyword != '' && keyword != 0 && typeof (keyword) != "undefined") {
                    data.keyword = keyword;
                }
            }

            if (filterType == 'category')
            {
                $(".showSubCategories").html("Show Sub Categories");
                $("#showSubCategoriesAnchor").html("Show Sub Categories");

                url = api_categoryEventsCount;
                if ($scope.typeName != '' && $scope.typeName != 'All') {
                    data.eventType = $scope.typeName;
                }
                data.major = 0;
                if ($scope.selectedCityId > 0) {
                    if ($scope.splcitystateId != 0) {
                        data.stateId = $scope.splcitystateId;
                    } else {
                        data.cityId = $scope.selectedCityId;
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
                if (keyword != '' && keyword != 0 && typeof (keyword) != "undefined") {
                    data.keyword = keyword;
                }
            }
            if (filterType == 'customFilter')
            {
                $(".showSubCategories").html("Show Sub Categories");
                $("#showSubCategoriesAnchor").html("Show Sub Categories");
                if ($scope.typeName != '' && $scope.typeName != 'All') {
                    data.eventType = $scope.typeName;
                }
                url = api_filterEventsCount;
                if ($scope.selectedCityId > 0) {
                    if ($scope.splcitystateId != 0) {
                        data.stateId = $scope.splcitystateId;
                    } else {
                        data.cityId = $scope.selectedCityId;
                    }
                }
                if ($scope.selectedCategoryId > 0)
                    data.categoryId = $scope.selectedCategoryId;

                if ($scope.selectedSubcategoryId > 0)
                    data.subcategoryId = $scope.selectedSubcategoryId;

                if (keyword != '' && keyword != 0 && typeof (keyword) != "undefined") {
                    data.keyword = keyword;
                }
            }
            $http({
                method: 'GET',
                url: url,
                params: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}
            }).success(function (data, status, headers, config) {

                var obj = $scope.indexedArray(data, filterType);
                if (filterType == 'city') {
                    $scope.updateCityCount($scope.cityList, obj);
                    $scope.allCityCount = "(" + data.response.allCount + ")";
                } else if (filterType == 'category') {
                    $scope.updateCount($scope.categoryList, obj, filterType);
                    $scope.allCategoryCount = "(" + data.response.allCount + ")";
                }
                else if (filterType == 'customFilter') {
                    $scope.updateCount($scope.customFilterList, obj, filterType);
                }
            }).error(function (data, status, headers, config) {
                window.location.href = site_url;
            });
        };

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
                if (obj.hasOwnProperty(keyVal))
                {
                    if (obj[keyVal] > 0) {
                        item.eventCount = "(" + obj[keyVal] + ")";
                        $('#' + item.id + '_city').css('display', 'inline-block');
                        $('#' + item.id + '_mobcity').css('display', 'inline-block');
                    }
                    else {
                        $('#' + item.id + '_city').css('display', 'none');
                        $('#' + item.id + '_mobcity').css("cssText", "display: none !important;");
                    }
                }
            });
        };
        $scope.updateCount = function (arrayObject, obj, type) {
            angular.forEach(arrayObject, function (item) {
                if (obj.hasOwnProperty(item.id))
                {
                    if (obj[item.id] > 0) {
                        item.eventCount = "(" + obj[item.id] + ")";
                        $('#' + item.id + '_category').css('display', 'inline-block');
                        $('#' + item.id + '_mobcategory').css('display', 'inline-block');
                        if (type == 'customFilter') {
                            $('#' + item.id + '_dates').css('display', 'inline-block');
                            $('#' + item.id + '_mobdates').css('display', 'inline-block');
                        }
                    }
                    else {
                        if (type == 'customFilter') {
                            $('#' + item.id + '_dates').css('display', 'none');
                            $('#' + item.id + '_mobdates').css("cssText", "display: none !important;");
                        }
                        $('#' + item.id + '_category').css('display', 'none');
                        $('#' + item.id + '_mobcategory').css("cssText", "display: none !important;");
                    }
                } else if (type == 'customFilter' && item.id == 6)
                {
                    item.eventCount = "";
                } else {
                    $('#' + item.id + '_category').css('display', 'none');
                    $('#' + item.id + '_mobcategory').css("cssText", "display: none !important;");

                }
            });
        };

        $scope.defaultFilter = function () {
            $scope.selectedCityId = defaultCityId;
            $scope.selectedCountryId = defaultCountryId;
            if (defaultCityName === "All Cities") {
                cityName = defaultCountryName;
            } else {
                cityName = defaultCityName;
            }
            $scope.selectedCityName = cityName;
        };

        $scope.getEventList = function (country, city, category, subcategory, day, type, page, limit, typeName, keyword, splcitystateid) {
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
            if (type != '' && type != 0) {
                if (type == 4) {
                    inputData += '&eventMode=1';
                } else {
                    inputData += '&registrationType=' + type;
                }
            }
            var searchPath = api_eventList;

            if (keyword != '' && keyword != 0 && typeof (keyword) != "undefined") {
                inputData += '&keyWord=' + keyword;
                searchPath = api_searchSearchEvent;
            }
            inputData += '&page=' + page + '&limit=' + limit;

            $http({
                method: 'GET',
                async: false,
                url: searchPath + inputData,
                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}  // set the headers so angular passing info as form data (not request payload)
            }).success(function (data, status, headers, config) {
                var htmlData = '';
                $scope.totalResultCount = data.response.total;
                
                // True Semantic Code
                if (typeof tsa  !== typeof undefined) {
                    var subcategory = $('.subCategoryClass').html().trim();
                    console.log(subcategory);
                    console.log("HERE IT IS ABOVE");
                    if (subcategory == 'SubCategories' || subcategory == 'Subcategories' ){
                        subcategory = "";
                    }
                    var customFilterName = $('.CustomFilterClass').html();
                    var customFreepaid = $('.freepaid').html();
                    var filter = new Array (
                                        customFilterName.charAt(0).toUpperCase() + customFilterName.slice(1),
                                        customFreepaid.charAt(0).toUpperCase() + customFreepaid.slice(1)    
                                    );
                    console.log($scope.keyword);
                    console.log($scope.totalResultCount);
                    console.log($('.cityClass').html());
                    console.log($('.categoryClass').html());
                    console.log(subcategory);
                    console.log(filter);
                    tsa.activity_data = {
                        "skeyword":$scope.keyword,
                        "sresults":$scope.totalResultCount,
                        "scity":$('.cityClass').html(),
                        "scategory":$('.categoryClass').html(),    
                        "ssubcategory":subcategory,
                        "sfilter":filter,
                    };            
                    if(typeof window.ts !== typeof undefined) {
                        console.log("SAVING");
                        ts.save_parameters();
                    }
                }
                // True Semantic Code Ends Here
                
                if (data.response.total > 0) {
                     $('#nosearchresults').css('display', 'none');
                     //$('#searchres-show').show();
                    var totalEvents = data.response.eventList.length; //durgesh
                    for (var i = 0; i < totalEvents; i++)
                    {
                        var event = data.response.eventList[i];
                        var cityName = event.cityName;

                        if (typeof (cityName) == 'undefined') {
                            cityName = '';
                        }
                        var eventURL=event.eventUrl;
                        if(event.eventExternalUrl!=undefined){
                            eventURL=event.eventExternalUrl;
                        }
                        htmlData += '<li class="col-xs-12 col-sm-6 col-md-4 col-lg-4 thumbBlock">';
                        htmlData += '<a href="' + eventURL + '" class="thumbnail">';
                        htmlData += '<div class="eventImg">';
                        htmlData += '<img src="' + event.thumbImage + '" width="" height="" alt="' + event.title + ' " title="' + event.title + '" onError="this.src=\'' + event.defaultThumbImage + '\'" />';
                        htmlData += '</div><h2>';
                        htmlData += '       <span class="eveHeadWrap">' + event.title + '</span>';
                        htmlData += '   </h2>';
                        htmlData += '   <div class="info">';
                        htmlData += '       <span>' + convertDate(event.startDate) + '</span> <span>at</span> <span>Hardrock';
                        htmlData += '               Cafe</span>';
                        htmlData += '   </div>';
                        htmlData += '<div class="eventCity" style="display:' + styleval + '">';
                        htmlData += '<span>' + cityName + '</span></div>';
                        htmlData += '   <div class="overlay">';
                        htmlData += '       <div class="overlayButt">';
                        htmlData += '           <div class="overlaySocial">';
                        htmlData += '               <span class="icon-fb"></span> <span class="icon-tweet"></span>';
                        htmlData += '               <span class="icon-google"></span>';
                        htmlData += '           </div>';
                        htmlData += '       </div>';
                        htmlData += '   </div>';
                        htmlData += '</a> <a href="' + eventURL + '" class="category">';
                        htmlData += '<span class="icon-' + event.categoryName.toLowerCase().replace(" ", "") + ' col' + event.categoryName.toLowerCase().replace(" ", "") + '"></span> <span class="catName"><em>' + event.categoryName + '</em></span>';
                        htmlData += '</a>';
                        htmlData += '</li>';
                    }
                    $('ul#eventThumbs').html(htmlData);
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
                    }
                    //$('#searchres-show').css('display', 'none');
                    $('#viewMoreEvents').css('display', 'none');
                    $('#noMoreEvents').css('display', 'none');
                    $('#nosearchresults').css('display', 'block');
                }

                $("#dvLoading").hide();
            }).error(function (data, status, headers, config) {
                window.location.href = site_url;
            });

            if ($scope.keyword != '') {
                $scope.matchingKeyword = "matching '" + $scope.keyword + "'";
                var redirectUrl = 'search?keyword=' + encodeURIComponent($scope.keyword);
            } else {
                $scope.matchingKeyword = '';
                var redirectUrl = 'search';
            }

            var redirectPage = 'search';
            var obj = {Page: redirectPage, Url: redirectUrl};
            history.pushState(obj, obj.Page, obj.Url);
        };

        $scope.getMoreEvents = function () {

            if ($scope.pageValue == 1)
                $scope.pageValue = 2;
            var inputData = '';
            var styleval = 'none';
            inputData += '?countryId=' + $scope.selectedCountryId;
            if ($scope.selectedCityId != 0) {
                if ($scope.splcitystateId != 0) {
                    inputData += '&stateId=' + $scope.splcitystateId;
                } else {
                    inputData += '&cityId=' + $scope.selectedCityId;
                }
            } else if ($scope.selectedCityId == 0) {
                styleval = 'block';
            }

            if ($scope.selectedCategoryId != 0)
                inputData += '&categoryId=' + $scope.selectedCategoryId;
            if ($scope.selectedSubcategoryId != 0)
                inputData += '&subcategoryId=' + $scope.selectedSubcategoryId;
            if ($scope.selectedCustomFilterId != 0)
                inputData += '&day=' + $scope.selectedCustomFilterId;
            if ($scope.typeValue != 0) {
                if ($scope.typeValue == 4) {
                    inputData += '&eventMode=1';
                } else {
                    inputData += '&registrationType=' + $scope.typeValue;
                }

            }
            if ($scope.selectedCustomFilterId == 7) {
                inputData += '&dateValue=' + $('#datepicker').val();
            }//

            var searchPath = api_eventList;
            var keyword = $("#searchId").val();
            $scope.keyword = keyword;
            if ($scope.keyword != '' && $scope.keyword != 0 && typeof ($scope.keyword) != "undefined") {
                inputData += '&keyWord=' + $scope.keyword;
                searchPath = api_searchSearchEvent;
            }

            if ($scope.pageValue != 0) {
                inputData += '&page=' + $scope.pageValue + '&limit=' + $scope.limitValue;
            }
            $http({
                method: 'GET',
                url: searchPath + inputData,
                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}  // set the headers so angular passing info as form data (not request payload)
            }).success(function (data, status, headers, config) {
                //console.log(data);
                if (data.response.total > 0) {
                    var htmlData = '';
                    var totalEvents = data.response.eventList.length;
                    for (var i = 0; i < totalEvents; i++)
                    {
                        var event = data.response.eventList[i]; //durgesh
                        var eventURL=event.eventUrl;
                        if(event.eventExternalUrl!=undefined){
                            eventURL=event.eventExternalUrl;
                        }
                        htmlData += '<li class="col-xs-12 col-sm-6 col-md-4 col-lg-4 thumbBlock">';
                        htmlData += '<a href="' + eventURL + '" class="thumbnail">';
                        htmlData += '<div class="eventImg">';
                        htmlData += '<img src="' + event.thumbImage + '" width="" height="" alt="' + event.title + '" title="' + event.title + '" onError="this.src=\'' + event.defaultThumbImage + '\'" />';
                        htmlData += '</div><h2>';
                        htmlData += '<span class="eveHeadWrap">' + event.title;/* + '</span><span id="saveEvent" class="icon-fave"></span>'*/
                        htmlData += '</h2><div class="info">';
                        htmlData += '<span>' + convertDate(event.startDate) + '</span> ';
                        htmlData += '</div>';
                        htmlData += '<div class="eventCity" style="display:' + styleval + '">';
                        htmlData += '<span>' + event.cityName + '</span></div>';
                        htmlData += '<div class="overlay"><div class="overlayButt"><div class="overlaySocial">';
                        htmlData += '<span class="icon-fb"></span> <span class="icon-tweet"></span>';
                        htmlData += '<span class="icon-google"></span></div></div></div>';
                        htmlData += '</a> <a href="' + eventURL + '" class="category">';
                        htmlData += '<span class="icon-' + event.categoryName.toLowerCase().replace(" ", "") + ' col' + event.categoryName.toLowerCase().replace(" ", "") + '"></span>';
                        htmlData += '<span class="catName"><em>' + event.categoryName + '</em></span> </a> </li>';
                    }

                    $('ul#eventThumbs').append(htmlData);
                    $scope.pageValue++; //durgesh

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
                    if ($scope.pageValue == 1) {
                        $('ul#eventThumbs').append(htmlData);
                    }

                    $('#viewMoreEvents').css('display', 'none');
                    $('#noMoreEvents').css('display', 'block');
                }


            }).error(function (data, status, headers, config) {
                window.location.href = site_url;
            });
        };

        $scope.citySearchValue = '';
        $scope.subCatSearchValue = '';
        $scope.cityChanged = function (str) {
            $scope.citySearchValue = str;
        };
        $scope.remoteRequestCity = function (str) {
            return {major: 0, keyWord: str, countryId: $scope.selectedCountryId, limit: 5};

        };
        $scope.citySelected = function (selected) {
            $scope.setFilter('city', selected.originalObject.id, selected.title, 1, 0);

        };
        $scope.subcategorySelected = function (selected) {
            $scope.setFilter('Subcategory', selected.originalObject.id, selected.title, 1, 0);
        };
        $scope.remoteRequestSubCat = function (str) {
            return {major: 1, keyword: str, categoryId: $scope.selectedCategoryId, limit: 5};
        };
        $scope.subCatChanged = function (str) {
            $scope.subCatSearchValue = str;
        };

        $scope.keywordSearch = function (keyCode) {
            $scope.pageValue = 1;
            $scope.limitValue = 12;
            if (keyCode == 13) {

                $('#ui-id-2').hide();
                $('.icon-me_search').blur();
                $scope.keyword = $("#searchId").val();

                if ($.isNumeric($scope.keyword)) {
                    $.ajax({
                        type: 'GET',
                        url: api_searchSearchEventAutocomplete + "?term=" + $scope.keyword,
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'
                        },
                        success: function (response) {
                            if (response.length == 1) {
                                window.location.href = site_url + 'event/' + response[0].url;
                            }
                        },
                        error: function (response) {

                        }
                    });
                }
                $scope.getEventList($scope.selectedCountryId, $scope.selectedCityId,
                        $scope.selectedCategoryId, $scope.selectedSubcategoryId,
                        $scope.selectedCustomFilterId, $scope.typeValue, 1,
                        $scope.limitValue, $scope.typeName, $scope.keyword, $scope.splcitystateId);
            }
        };
    }]);