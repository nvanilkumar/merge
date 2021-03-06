var controllers = {};



controllers.validateController = function ($scope, $http, fileReader, $filter, eventFactory, $q) {
    $scope.categoryList = "";
    $scope.selectedCategoryId = 0;
    $scope.addedCategories = '';
//    $scope.tags="city,category,subcategory";

    $scope.tinymceOptions = {
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table textcolor  contextmenu paste jbimages textcolor"
        ],
        toolbar1: "insertfile undo redo |  bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image ",
        toolbar2: "jbimages | formatselect | fontselect | fontsizeselect | forecolor backcolor",
        relative_urls: false,
        resize: false
    };

    $scope.eventDetails = {};
//         $scope.bannerImageStyle={
//             background:
//         };
    $scope.getEvent = function (eventId) {
        eventFactory.getInfo(eventId, "event/detail").success(function (data, status, headers, config) {
            eventDetails = data.response.details;
            $scope.eventId = eventDetails.id;
            $scope.title = eventDetails.title;
            $scope.description = eventDetails.description;
            $scope.url = eventDetails.url;
            $scope.categoryId = eventDetails.categoryId;
            $scope.subcategoryName = eventDetails.subCategoryName;
            $scope.categoryName = eventDetails.categoryName;
            $scope.addedCategories = eventDetails.categoryName; 
            $scope.categoryChanged($scope.categoryId, $scope.categoryName, false);

            $scope.registrationType = eventDetails.registrationType;
            if ($scope.registrationType == 3) {
                $('#div_ticketwidget').hide();
            }
            $("#registrationType2").prop("checked", false);
            $("#registrationType2").parent().removeClass("selected");
            $("#registrationType" + $scope.registrationType).prop("checked", true);
            $("#registrationType" + $scope.registrationType).parent().addClass("selected");

            $scope.private = eventDetails.private;
            $("#private0").prop("checked", false);
            $("#private0").parent().removeClass("selected");
            $("#private" + $scope.private).prop("checked", true);
            $("#private" + $scope.private).parent().addClass("selected");

            $scope.venueName = eventDetails.location.venueName;
            $scope.venueaddress1 = eventDetails.location.address1;
            $scope.venueaddress2 = eventDetails.location.address2;
            $('#country_value').val(eventDetails.location.countryName);
            $('#state_value').val(eventDetails.location.stateName);
            $('#city_value').val(eventDetails.location.cityName);
            $("#subCategoryName_value").val(eventDetails.subCategoryName);
            $scope.thumbImageSrc = eventDetails.thumbnailPath;
            $scope.bannerImageSrc = eventDetails.bannerPath;
            $scope.tags = eventDetails.tags;
            $('#event_tags').tagsinput('removeAll');
            $('#event_tags').tagsinput('add', eventDetails.tags);
            $scope.bannerfileid = eventDetails.bannerfileid;
            $scope.thumbnailfileid = eventDetails.thumbnailfileid;
            $scope.saleButtonTitle = eventDetails.eventDetails.bookButtonValue;
            if($scope.bannerfileid > 0){
             $scope.bannerImageChoosen = true;   
            }
            if($scope.thumbnailfileid > 0){
             $scope.thumbImageChoosen = true;   
            }
        }).error(function (data, status, headers, config) {


        });
    };

    $scope.categoryList = "";
    $scope.init = function (data, type) {
        if (type == 'category')
            $scope.categoryList = data;
        if (type == 'subCategoryList')
            $scope.subCategoryList = data;
        if (type == 'saleButtonTitleList') {
            $scope.saleButtonTitleList = data;
            $scope.saleButtonTitle = $scope.saleButtonTitleList[0].name;
        }

    };
    //Set the title of the page
    $scope.setUrl = function () {
        if ($scope.eventId == 0) {
            var url = $scope.title;
            url = $filter('replaceFilter')(url);
            $scope.url = url;
        }

    };

    $scope.initDate = function (eventId) {
        $scope.appUrl = "api/event/create";
        $scope.endDate = $scope.startDate = $scope.jqNowDate();
        $scope.thumbImageChoosen = $scope.bannerImageChoosen = false;
        $scope.eventId = 0;
        if (eventId > 0) {
            $scope.getEvent(eventId);
            $scope.appUrl = "api/event/edit";
        }

    };
    //returns the current date in mm/dd/yy
    $scope.jqNowDate = function () {
        var d = new Date();
        d.setDate(d.getDate() + 2); 
        var month = d.getMonth() + 1;
        var day = d.getDate();
        var formatDate =
                (('' + month).length < 2 ? '0' : '') + month + '/' +
                (('' + day).length < 2 ? '0' : '') + day + '/' +
                d.getFullYear();
        return formatDate;
    };
    //seting Ticket Start date default value 
    $scope.jqTicketDefaultDate = function () {
        var nowDate = $scope.jqNowDate();
        var eventStartDate = $scope.startDate;
        if (eventStartDate > nowDate) {
            return eventStartDate;
        }
        return nowDate;

    };
    //$scope.addedCategories = '';
    $scope.categoryChanged = function (catId, catName, catstatus) {
        $scope.selectedCategoryId = catId;
        $scope.categoryId = catId;

        var result = $filter('filter')($scope.categoryList, catName);
        var themeColor = result[0].themecolor;


        $('#categoryError').text('');
        $('#categoryId').val(catId);
        $('#event_tags').tagsinput('add', catName);
        if ($scope.addedCategories != '') {
            $('#event_tags').tagsinput('remove', $scope.addedCategories);
            $('#event_tags').tagsinput('remove', $scope.addedCategories.toLowerCase());
        }
        $scope.addedCategories = catName;
        $('.subselectCategory').text('Select a Sub Category').append('<span class="icon-downArrow"></span>');
        $('.selectCategory').text(catName).append('<span class="icon-downArrow"></span>');
        var ele = $('.create_eve_dropdowns a.dropdown-togglep');
        ele.css('background', themeColor);
        var bg = $('.design_event .Upload_Thumb').css('background-image');
        bg = bg.replace('url(', '').replace(')', '');
        if ($('.design_event .Upload_Thumb').css('background-image') == "none" || bg == window.location.href) {
            var ele = $('.design_event .Upload_Thumb');
            ele.css('background', themeColor);
        }
        var bg = $('.design_event .upload').css('background-image');
        bg = bg.replace('url(', '').replace(')', '');
        if ($('.design_event .upload').css('background-image') == "none" || bg == window.location.href) {
            var ele = $('.design_event .upload');
            ele.css('background', themeColor);
        }


        var url = '';
        var data = {};
        data.categoryId = catId;
        url = api_subcategoryList;
        $http({
            method: 'GET',
            url: url,
            params: data,
            headers: {'Content-Type': 'application/x-www-form-urlencoded',
                'Authorization': 'bearer ' + client_ajax_call_api_key}
        }).success(function (data, status, headers, config) {

            $scope.init(data.response.subCategoryList, 'subCategoryList');

            if (catstatus) {
                $scope.subcategoryName = "";
                $("#subCategoryName_value").val("");

            }

        }).error(function (data, status, headers, config) {

            alert('Something went wrong please try again');
        });
    };

    $scope.addedSubcategories = [];
    $scope.subcategoryChanged = function (subcatName) {

        $scope.subcategoryName = subcatName;
//        $('#event_tags').tagsinput('remove', subcatName);
//        $('#subCategoryName_value').focus();
        if ($scope.addedSubcategories.length > 0) {
            for (var tg = 0; tg < $scope.addedSubcategories.length; tg++) {
                $('#event_tags').tagsinput('remove', $scope.addedSubcategories[tg]);
            }
            $('#subCategoryName_value').focus();
            $scope.addedSubcategories = [];
        }
        $('#categoryError').text('');
    };
    $scope.selectedSubcategory = function (subcatName) {
        if (subcatName != undefined) {
            $scope.subcategoryName = subcatName.title;

            //To add the subcategoryname as a tag
            $('#event_tags').tagsinput('remove', $('#subCategoryName_value').val());
            var subcatNewTitleArray = subcatName.title.split("&");
            var tagLength = subcatNewTitleArray.length;
            for (var tg = 0; tg < tagLength; tg++) {
                $('#event_tags').tagsinput('add', subcatNewTitleArray[tg]);
            }
            $('#subCategoryName_value').focus();
            $scope.addedSubcategories = subcatNewTitleArray;
            // $('#event_tags').tagsinput('add', subcatName.title);
        }

    };
    $scope.remoteRequestSubcat = function (str) {
        return {keyword: str, categoryId: $scope.selectedCategoryId, limit: 5};
    };
    $scope.subcategoryErrors = function (error) {
        $('#categoryError').text(error.response.messages[0]);
    };
    // Code to configure the Country, State and City autocompletes starts here
    $scope.remoteRequestCountry = function (str) {

        $('.setting_content').slideUp('slow');
        return {major: 0, keyWord: str, limit: 5};
    }
    $scope.remoteRequestState = function (str) {

        var $element = $('#country_value');
        var scope = angular.element($element).scope();
        var country = scope.searchStr;
        $('.setting_content').slideUp('slow');

        return {major: 0, keyWord: str, limit: 5, countryName: country};
    }
    $scope.countrySelected = function (str) {
        var countryId = str.originalObject.id;
        var data = {};
        data.countryId = countryId;
        url = api_countryDetails;
        $http({
            method: 'GET',
            url: url,
            params: data,
            headers: {'Content-Type': 'application/x-www-form-urlencoded',
                'Authorization': 'bearer ' + client_ajax_call_api_key}
        }).success(function (data, status, headers, config) {
            var timeZoneId = data.response.timezoneId;
            $("#timeZoneId > [value=" + timeZoneId + "]").attr("selected", "true");
        }).error(function (data, status, headers, config) {
            alert('Something went wrong please try again');
        });
    }
    $scope.addEventRequestCity = function (str) {
        var country = $('#country_value').val();
        var state = $('#state_value').val();
        $('.setting_content').slideUp('slow');

        return {major: 0, keyWord: str, countryName: country, addEventCheck: true, stateName: state, limit: 5};
    };
    // Code to configure the Country, State and City autocompletes ends here

    // For validations
    $scope.eventType = '2';
    $scope.modal = {
        description: '',
    }

    $scope.webinarChange = function (status) {
        var webinarElement = $('<input>', {"type": 'hidden', "name": "iswebinar",
            "value": 0});
        if (status) {
         //   $('#div_webinar').hide('fast');
            $("#webinar").parent().addClass("selected");
            $("#webinar").next().remove();
        } else {
        //    $('#div_webinar').show('fast');
            $("#webinar").parent().removeClass("selected");
            var elementType = $("#webinar").next().attr('type');
            if (elementType != "hidden") {
                webinarElement.insertAfter($("#webinar"));
            }
        }
    };
    $scope.submitForm = function (isValid, submitValue) {
        $('#'+submitValue).addClass("loading");
        
            $('.createeventbuttons').attr("disabled", "disabled");
        
         $('#'+submitValue).attr("disabled","disabled");
        
        // check to make sure the form is completely valid
        var eventType = $('input[name="registrationType"]:checked').val();
        var eventaction = $('#eventedit').val();
        var errorMessage = "";
        var bannerErrorMessage = "";
        var logoErrorMessage = "";
        var bannerError = 0;
        var logoError = 0;
        $('#eventDataErrors').html('');
        $('#eventDataSuccess').html('');


        //event log & banner related validation
        if ($scope.thumbImageSizeError) {
            isValid = false;
            errorMessage = "";
            logoErrorMessage  = "Invalid Logo data";
            logoError =1;
        }

        if ($scope.bannerImageSizeError) {
            isValid = false;
            errorMessage = "";
            bannerErrorMessage  = "Invalid Banner data";
            bannerError =1;
        }

        $scope.subcategoryNameErr = false;

        var subCategoryName = $.trim($('input[name="subCategoryName"]').val());
        if (subCategoryName == '') {
            isValid = false;
            $scope.subcategoryNameErr = true;
        }

        if (eventType != '3' && isValid) {
            if (submitValue === 'golive' || submitValue === 'preview' || submitValue === 'save') {
            isValid = ticketValidations();
            }
        }
        //Upload Image is mandatory
        if (submitValue === 'golive') {
            //check the file choosen or not
            var bannerfileid = $("#bannerfileid").val();
            var thumbnailfileid = $("#thumbnailfileid").val();
            var thumbSrc = $('#thumbImage').attr('thumb-theme-src');

            if ((!$scope.thumbImageChoosen) && (typeof thumbSrc == "undefined") && thumbnailfileid == "0") {
                isValid = false;
                errorMessage = "";
                logoErrorMessage = "Please upload valid event logo image";
                logoError =1;
            }

            var bannerSrc = $('#bannerImage').attr('banner-theme-src');
            if ((!$scope.bannerImageChoosen) && (typeof bannerSrc == "undefined") && bannerfileid == "0") {
                isValid = false;
                errorMessage = "";
                bannerErrorMessage ="Please upload event banner image"; 
                bannerError =1;
            }

        }

        if (isValid) {
            $('#submitValue').val(submitValue);
            $('#ticketCount').val($('input[name^="ticketName"]').length);

            var fd = new FormData();

            $.each($('#thumbImage')[0].files, function (i, file) {
                fd.append('thumbImage', file);
            });
            $.each($('#bannerImage')[0].files, function (i, file) {
                fd.append('bannerImage', file);
            });
            var dummyhtml = $("#dummy-ticket").html();
            //Remove the dummy ticket details
            $("#dummy-ticket").html('');

            var other_data = $('#createEventForm').serializeArray();
            $.each(other_data, function (key, input) {
                fd.append(input.name, input.value);
            });
            fd.append("submitValue", submitValue);
            //Pick a theme related funcitons
            var bannerSrc = $('#bannerImage').attr('banner-theme-src');
            var thumbSrc = $('#thumbImage').attr('thumb-theme-src');
            if (bannerSrc !== undefined) {
                fd.append('bannerSource', bannerSrc);
            }
            if (thumbSrc !== undefined) {
                fd.append('thumbSource', thumbSrc);
            }

            var tags = $("#event_tags").tagsinput('items');

            var url = site_url + $scope.appUrl;
            $.ajax({
                method: 'POST',
                url: url,
                data: fd,
                headers: {'Authorization': 'bearer ' + client_ajax_call_api_key},
                cache: false,
                contentType: false,
                processData: false,
                async: false,
            }).success(function (data, status, headers, config) {
                if (submitValue == 'save') {

                    $('#eventDataErrors').html('');
                    $("#dummy-ticket").html(dummyhtml);
                    if (typeof (data) != 'undefined') {
                    //    $('#eventDataSuccess').html(data.response.messages[0] || '');
                    }
                    if (typeof (data.response.id) != 'undefined') {
//                    	window.location.href = site_url+'dashboard/event/edit/' +data.response.id;
                        window.location.href = site_url + 'dashboard';
                    } else {
                        window.location.reload();
                    }
                } else if (submitValue == 'golive') {
                    window.location.href = site_url + 'dashboard';
                } else if (submitValue == 'preview') {
                    url = site_url + 'previewevent?view=preview&eventId=' + data.response.id;
                   var editurl = site_url + 'dashboard/event/edit/' + data.response.id;
                    var win = window.open(url, '_blank');
                    
                    if($('#eventId').val() > 0){ 
                        $('#'+submitValue).removeAttr("disabled"); 
                        window.location.href = editurl;
                    }else{
                    window.location.href = editurl; }
                    win.focus();
                    window.reload();
                    

                } else {
                    window.location.href = site_url;
                }


            }).error(function (data, status, headers, config) {
                $('#'+submitValue).removeClass("loading");
                $('#'+submitValue).removeAttr("disabled");
                $('.createeventbuttons').removeAttr("disabled"); 
                $('#eventDataErrors').html('');
                $('#eventDataSuccess').html('');
                $.each(data.responseJSON.response.messages, function () {
                    $('#eventDataErrors').append("<li>" + this + "</li>");
                });
                $("#dummy-ticket").html(dummyhtml);

            });

        } else {
            $('#'+submitValue).removeClass("loading");
            $('#'+submitValue).removeAttr("disabled");
            $('.createeventbuttons').removeAttr("disabled"); 
            $('#eventDataSuccess').html('');
            $('#eventDataErrors').html('');
            $('#eventBannerErrors').html('');
            $('#eventLogoErrors').html('');
            var message = '';
            var bannermessage ='';
            var logomessage = '';
            if (errorMessage.length > 0) {
                message = errorMessage;
            }
            if (bannerErrorMessage.length > 0) {
                bannermessage = bannerErrorMessage;
            }
            if (logoErrorMessage.length > 0) {
                logomessage = logoErrorMessage;
            }
            if(bannerError==1){
              $('#eventBannerErrors').append("<li> " + bannermessage + "</li>");  
            }
            if(logoError==1){
              $('#eventLogoErrors').append("<li> " + logomessage + "</li>");  
            }else{
              $('#eventDataErrors').append("<li> " + message + "</li>");  
            }
             $('#eventDataErrors').append("<li> Some fields are required</li>");  
            $scope.submitted = true;

        }
        $('html, body').animate({
            scrollTop: 0}, 100);
    };

    $scope.counts = 0;
    $scope.arrayCount = 1;

    $scope.counter = function () {
        $scope.arrayCount++;
    };
    $scope.checkUrlExists = function () {
        if ($scope.url != undefined) {
            var url = api_path + '/api_checkUrlExists';
            var data = {};
            data.eventUrl = $scope.url;
            data.eventId = $scope.eventId;
            $http({
                method: 'GET',
                url: url,
                params: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'bearer ' + client_ajax_call_api_key}
            }).success(function (data, status, headers, config) {
                $('#checkUrlAvail').text(data.response.messages);
                $('#checkUrlAvail').css('color', 'rgb(0,204,7)');
                $('#checkUrlAvail').show();
                setTimeout(function(){
                  $('#checkUrlAvail').hide();
                  }, 8000);
                
            }).error(function (data, status, headers, config) {
                $('#checkUrlAvail').text(data.response.messages);
                $('#checkUrlAvail').css('color', 'red');
                $('#checkUrlAvail').show();
                setTimeout(function(){
                  $('#checkUrlAvail').hide();
                  }, 5000);
            });
        } else {
            $('#checkUrlAvail').text("URL is required");
            $('#checkUrlAvail').css('color', 'red');
            $('#checkUrlAvail').show();
             setTimeout(function(){
                  $('#checkUrlAvail').hide();
                  }, 5000);
        }
    };

    // Getting the Banner image file to validate for Dimentions and Size
    $scope.getBannerFile = function () {
        fileReader.readAsDataUrl($scope.file, $scope)
                .then(function (result) {
                    $scope.bannerImageSrc = '';
                    $scope.bannerImageChoosen = $scope.bannerImageSizeError = false;
                    var fileSize = ($scope.file.size) / 1024;
                    var fileSizeInKb = Math.ceil(fileSize);
                    // If the image Size is Greaterthan `2Mb` then throw the error
                    if (fileSizeInKb > 2048) {
                        $scope.bannerImageSizeError = true;
                    } else {
                        $scope.bannerImageSrc = result;
                        $scope.bannerImageChoosen = true;
                    }
                });
    };

    // Getting the Thumb image file to validate for Dimentions and Size
    $scope.getThumbFile = function () {
        fileReader.readAsDataUrl($scope.file, $scope)
                .then(function (result) {
                    $scope.thumbImageSrc = '';
                    $scope.thumbImageChoosen = $scope.thumbImageSizeError = false;
                    var fileSize = ($scope.file.size) / 1024;
                    var fileSizeInKb = Math.ceil(fileSize);
                    // If the image Size is Greaterthan `500kb` then throw the error
                    if (fileSizeInKb > 500) {
                        $scope.thumbImageSizeError = true;
                    } else {
                        $scope.thumbImageSrc = result;
                        $scope.thumbImageChoosen = true;
                    }
                });
    };
};

controllers.eventController = function ($scope) {

    $scope.counts = 0;
    $scope.arrayCount = 1;

    $scope.counter = function () {
        $scope.arrayCount++;
    }

}

angular.module('eventModule').controller('eventController', ['$scope', controllers.eventController]);

angular.module('eventModule').controller('validateController', ['$scope', '$http', 'fileReader', '$filter', 'eventFactory', '$q', controllers.validateController]);
angular.module('eventModule').filter('replaceFilter', function () {
    return function (text) {
        if (undefined !== text && text.length > 0) {
            text = text.replace(/[^A-Za-z0-9\-]/g, " ");
            text = text.replace(/ /g, '-');
            return text ? text.replace(/-+/g, '-') : '-';
        } else {
            return text;
        }


    };
});










