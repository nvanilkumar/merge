//Search function
function securityRemoveCharactes(data) {
    $.each(data, function (key, value) {
        var regExp = /[^\w\d_ -]+/gi;
        if ($.isArray(value)) {
            $.each(value, function (inputKey, inputData) {
                if (regExp.test(inputData)) {
                    //deprecated 
                    inputData = inputData.replace(/[^a-zA-Z0-9_@,\s-\.\(\):\/\+;|=]+/gi, '');
                    //return preg_replace_callback('/[^a-zA-Z0-9_@,\s-\.\(\):\/\+;|]/s',function(){return '';},variable);
                } else {
                    inputData = inputData.replace(/\s/, '');
                }
                data[inputKey] = inputData;
            });
        } else {
            if (regExp.test(value)) {
                //deprecated 
                value = value.replace(/[^a-zA-Z0-9_@,\s-\.\(\):\/\+;|=]+/gi, '');
                //return preg_replace_callback('/[^a-zA-Z0-9_@,\s-\.\(\):\/\+;|]/s',function(){return '';},variable);
            } else {
                value = value.replace(/\s/, '');
            }
            data[key] = value;
        }
    });
    return data;
}
if ($('.searchExpand')[0] || $('#searchId')[0]) {
    $('.searchExpand, #searchId').autocomplete({
        //source : eventsList
        source: api_searchSearchEventAutocomplete,
        select: function (event, ui) {
            window.location.href = site_url + 'event/' + ui.item.url;
        }
    });

}


function getProfileLink(pageCall) {
    //alert(pageCall);
    $('.profile-dropdown').html('<br>'+$('#menudvLoading').html());
    if((pageCall == 'event_header' && !$('.afterlogindiv').hasClass('active')) ||
       (pageCall == 'header' && !$('.dropdown').hasClass('open'))) {
        $.ajax({
            type: 'GET',
            async:false,
            url: api_getProfileDropdown,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'
            },
            success: function (response) {
                $('.profile-dropdown').html(response);
            }
        });
    } else {
        $('.profile-dropdown').empty();
    }
}


$('.searchExpand').keyup(function (e) {
    if (e.keyCode == 13) {
        var key = $(this).val();
        var data = {};
        data.keyword = key;
        var response = securityRemoveCharactes(data)
        key = response['keyword'];

        $.ajax({
            type: 'GET',
            url: api_searchSearchEventAutocomplete + "?term=" + key,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'
            },
            success: function (response) {
                if (response.length == 1 && !isNaN(key)) {
                    $('#ui-id-1').find('li').click();

                    //window.location.href = site_url + 'event/' + response[0].url;
                } else {
                    window.location.href = site_url + 'search?keyword=' + key;
                }
            }
        });
    }

});
$(window).load(function () {
    setTimeout(function () {
        $('.hide').slideUp('slow');
    }, 5000);
    setTimeout(function () {
        $('.flashHide').slideUp('slow');
    }, 5000);
});


//Convert the date from (2015-08-24 17:16:00)format to (July 24, 2015)format
function convertDate(inputDate) {

    inputDate = Date.convertStringToTime(inputDate);

    //To get the names of the month
    Date.prototype.monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    Date.prototype.getMonthName = function () {
        return this.monthNames[this.getMonth()];
    };

    var formattedDate = inputDate.getMonthName() + " " + inputDate.getDate() + ", " + inputDate.getFullYear();

    //returning the formatted date
    return formattedDate;

}

//To convert string to javascript date format
Date.convertStringToTime = function (inputDate) {
    var splittedDate = inputDate.split(/[- :]/);
    return new Date(splittedDate[0], splittedDate[1] - 1, splittedDate[2], splittedDate[3] || 0, splittedDate[4] || 0, splittedDate[5] || 0);
};


function getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure)
{
    $.ajax({
        type: method,
        url: pageUrl,
        datatype: dataFormat,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'
        },
        data: input,
        success: function (response) {
            callbackSuccess(response);
        },
        error: function (response) {
            callbackFailure(response);
        }
    });
}
$(document).scroll(function () {
    $('.filterScrollSearch .filterdiv').hide();
});


$(document).ready(function(){
    if($('#affiliateHeaderDiv').length>0){
            setTimeout(function() {
             //$('#affiliateHeaderDiv').fadeOut('fast');
         }, 10000);
    }
    $('#closeAffiliateHeaderDiv').click(function(){
        $('#affiliateHeaderDiv').fadeOut('fast');
    });
});