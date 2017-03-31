var oldSubcat = [];
var oldCity = '';
var urlAvailable = true;
$(document).ready(function () {
    $(window).keydown(function (event) {
        if (event.keyCode == 13 && !$('.createeventbuttons').is(':focus') && !$('.bootstrap-tagsinput').children().is(':focus')) {
            event.preventDefault();
            return false;
        }
    });
});

$(function () {
    oldCity = $('#city').val();
    customRadio("registrationType");
    customRadio("private");
    additionalCommission();
    initialize();
    //    customCheckbox();
    if ($('#eventId').val() == 0) {
        //To check the default values
        $("#registrationType2").prop("checked", true);
        $("#registrationType2").parent().addClass("selected");
        $("#private0").prop("checked", true);
        $("#private0").parent().addClass("selected");
    }
    $('#paidevent').click(function () {
        $('#div_webinar').show('fast');
        $('#div_ticketwidget').show('fast');
        $('#div_tickettype').show('fast');

    });

    $('#freeevent').click(function () {
        $('#div_webinar').show('fast');
        $('#div_ticketwidget').show('fast');
        $('#div_tickettype').hide('fast');

    });

    $('#noregistration').click(function () {
        $('#div_webinar').show('fast');
        $('#div_ticketwidget').hide('fast');
        $('#div_tickettype').show('fast');
    });



    $('.dropdown-inverse li > a').click(function (e) {
        $('.status').html(this.innerHTML);
    });

    $('div.locSearchContainer').hide();




    $('.dropdown-menuP a').on('click', function () {
        $('.dropdown-toggleP').html($(this).html() + '<span class="caret"></span>');
    });


    $("body").on("click", ".settingsIcosn", function (e) {

        var countryName = $('#country').val();
        var stateName = $('#state').val();
        var cityName = $('#city').val();
        $(this).parents("ul").find("div.setting_content").slideToggle('slow');
    });

    //checkbox on change
    var soldOutHiddenId = $('<input>', {"type": 'hidden', "name": "soldOut[]",
        "value": 0});
    var nottodisplay = $('<input>', {"type": 'hidden', "name": "nottodisplay[]",
        "value": 1});

    $('#div_ticketwidget').on('change', '.soldoutCheckbox', function () {

        if ($(this).is(':checked')) {
            $(this).next().remove();
            $(this).parent().addClass("selected");
        } else {
            $(this).parent().removeClass("selected");
            var elementType = $(this).next().attr('type');
        }

    });
    $('#div_ticketwidget').on('change', '.nottodisplayCheckbox', function () {
        if ($(this).is(':checked')) {
            $(this).next().remove();
            $(this).parent().addClass("selected");
        } else {
            $(this).parent().removeClass("selected");
            var elementType = $(this).next().attr('type');

        }

    });
    $('#div_ticketwidget').on('change', '.taxCheckBox', function () {
        if (typeof ($(this).attr('ticketsold')) == 'undefined') {
            if ($(this).is(':checked')) {
                $(this).attr('checked', 'checked');
                $(this).parent().addClass("selected");
            } else {
                $(this).removeAttr('checked');
                $(this).parent().removeClass("selected");
                return false;
            }
        } else {
            return false;
        }
    });

    //setting the tags on changes of subcategory, city
    $("#subCategoryName").blur(function () {

        if ($(this).val() != '') {
            var subcatNewTitleArray = [$.trim($(this).val())];
//            if ($(this).val().indexOf('-') > -1) {
//                subcatNewTitleArray = $(this).val().split("-");
//            } else 
            if ($(this).val().indexOf('&') > -1 || $(this).val().indexOf(',') > -1) {
                //subcatNewTitleArray = $(this).val().split("&");
                var subcatNewTitleArray = [];
                $.each($(this).val().replace(",", "&").split("&"), function (key, value) {
                    if (value.indexOf(',') > -1) {
                        $.each(value.split(","), function (key1, value1) {
                            subcatNewTitleArray.push($.trim(value1));
                        });
                    } else {
                        subcatNewTitleArray.push($.trim(value));
                    }
                });
            }
            var oldSubcatLength = oldSubcat.length;
            for (var osc = 0; osc < oldSubcatLength; osc++) {
                if ($('#addedCategories').val() != oldSubcat) {
                    $('#event_tags').tagsinput('remove', oldSubcat[osc].toLowerCase());
                }
            }
            if ($.isArray(subcatNewTitleArray)) {
                var tagLength = subcatNewTitleArray.length;
                for (var tg = 0; tg < tagLength; tg++) {
                    $('#event_tags').tagsinput('add', subcatNewTitleArray[tg].toLowerCase());
                }
            } else {
                $('#event_tags').tagsinput('add', subcatNewTitleArray.toLowerCase());
            }
            oldSubcat = subcatNewTitleArray;
            var readonly = $('#eventUrl').attr('readonly');
            if (typeof readonly === typeof undefined && readonly === false) {
                $('#eventUrl').focus();
            } else {

            }

        }
    });
    $('#eventUrl').keyup(function () {
        $(this).valid();
        if ($(this).hasClass('error')) {
            $('#checkUrlAvail').hide();
        }
    });
    $('#city').blur(function () {
        var countryValid = $('#country').valid();
        var stateValid = $('#state').valid();
        if ($(this).val().toLowerCase().length > 0) {
            $('#event_tags').tagsinput('remove', oldCity.toLowerCase());
            $('#event_tags').tagsinput('remove', oldCity.toLowerCase());
            $('#event_tags').tagsinput('add', $(this).val().toLowerCase());
        }
        oldCity = $(this).val();
        if ($('#country').val() == '') {
            $('#state').val();
            $(this).val('');
            $('#country').focus();
        } else if ($('#state').val() == '') {
            $(this).val('');
            $('#state').focus();
        } else {
            if ($(this).val() != '') {
                $('#pincode').val('');
                $('#pincode').focus();
            }
        }
        /*if ($('#start_date').is(':disabled')) {
         $('#end_date').focus();
         } else {
         $('#start_date').focus();
         }*/
    });

    $("#subCategoryName").keyup(function (e) {
        var subCatvValu = $(this).val();
        subCatvValu = subCatvValu.replace(/[0-9]/g, "");
        subCatvValu = subCatvValu.replace(/[!@#$^#()$~%'":*?<>{}=]/g, '');
        $('#subCategoryName').val(subCatvValu);

    });

    if ($('#eventId').val() == 0) {
        var eventDate = jqNowDate();
        $('#start_date').val(eventDate);
        $('#end_date').val(eventDate);
    }

    //$('#event_tags').tagsinput();
});//Main function

function customCheckbox() {
    var checkBox = $('.soldout');
    $(checkBox).each(function () {
        $(this).wrap("<span class='custom-checkbox'></span>");
        if ($(this).is(':checked')) {
            $(this).parent().addClass("selected");
        }
    });
    $(checkBox).click(function () {
        $(this).parent().toggleClass("selected");
    });
}

function customRadio(radioName) {
    var radioButton = $('input[name="' + radioName + '"]');
    $(radioButton).each(function () {
        $(this).wrap("<span class='custom-radio'></span>");
        if ($(this).is(':checked')) {
            $(this).parent().addClass("selected");
        }
    });
    $(radioButton).click(function () {
        if ($(this).is(':checked')) {
            $(this).parent().addClass("selected");
        }
        $(radioButton).not(this).each(function () {
            $(this).parent().removeClass("selected");
        });
    });
}
        function additionalCommission(){
        if ($('#acceptmeeffortcommission').is(':checked')) {
            $("#acceptmeeffortcommission").val('1');
            $("#acceptmeeffortcommission").parent().addClass("selected");
            $("#private0").removeAttr('disabled');
            $("#private1").removeAttr('disabled');
        } else {
            $('#acceptmeeffortcommission').val('0');
            $("#acceptmeeffortcommission").attr("checked", false);

            $("#acceptmeeffortcommission").parent().removeClass("selected");
            $("#private1").prop("checked", true);
            $("#private1").parent().addClass("selected");

            $("#private0").attr("checked", false);
            $("#private0").parent().removeClass("selected");
            $("#private0").attr('disabled', 'true');
            $("#private1").attr('disabled', 'true');
            
        }
        }

/* Functions for Filling google address */

var placeSearch, autocomplete;
var componentForm = {
    neighborhood: 'long_name',
    premise: 'long_name',
    route: 'long_name',
    sublocality_level_1: 'long_name',
    sublocality_level_2: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'long_name',
    country: 'long_name'
};

function initialize() {
    // Create the autocomplete object, restricting the search
    // to geographical location types.

    var map = new google.maps.Map(document.getElementById('eventVenue'), {
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var input = (document.getElementById('eventVenue'));

    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var autocomplete = new google.maps.places.Autocomplete((input));

    /* autocomplete = new google.maps.places.Autocomplete(
     (document.getElementById('locationInputField')),
     { types: ['geocode'] });*/
    // When the user selects an address from the dropdown,
    // populate the address fields in the form.
    google.maps.event.addListener(autocomplete, 'place_changed', function () {

        var place = autocomplete.getPlace();
        fillInAddress(place);
        setTimeout(function () {
            $('#address').show();
            $('.addAdd').html('-');
            $('#eventAddress1').focus();

        }, 500);
    });

}

// [START region_fillform]
function fillInAddress(place) {
    $('.add_address').toggle('fast');
    $('.setting_content').hide();
    if ($('.addAdd').html() == '+') {
        $('.addAdd').html('-');
    } else {
        $('.addAdd').html('+');
    }

    for (var component in componentForm) {
        //console.log(component);
        if ($.inArray(component, ignoreArray) > -1) {
            document.getElementById(component).value = '';
            document.getElementById(component).disabled = false;
        }
    }
    if (place.length == 0) {
        return;
    }
    var ignoreArray = ['route', 'sublocality_level_2', 'premise'];

    // Get each component of the address from the place details
    // and fill the corresponding field on the form.
    var pincode = place.address_components[place.address_components.length - 1].long_name;
    var adddressObj = {};
    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            adddressObj[addressType] = val;
        }
    }
//    console.dir(adddressObj);
    var venue = '';
    var addr1 = '';
    var addr2 = '';
    var state = '';
    var city = '';
    venue = place.name;
    if (adddressObj.administrative_area_level_1) {
        state = adddressObj.administrative_area_level_1;
    }

    if (adddressObj.locality) {
        city = adddressObj.locality;
    }

    if (adddressObj.premise && venue == '') {
        venue = adddressObj.premise;
    }

    if (adddressObj.route) {
        addr1 += adddressObj.route + ', ';
        if (venue == '') {
            venue = adddressObj.route;
        }
    }
    if (adddressObj.neighborhood) {
        addr1 += adddressObj.neighborhood + ', ';
        if (venue == '') {
            venue = adddressObj.neighborhood;
        }
    }

    if (adddressObj.sublocality_level_1) {
        addr2 += adddressObj.sublocality_level_1 + ', ';
        if (venue == '') {
            venue = adddressObj.sublocality_level_1;
        }
    }
    if (adddressObj.sublocality_level_2) {
        addr2 += adddressObj.sublocality_level_2 + ', ';
        if (venue == '') {
            venue = adddressObj.sublocality_level_2;
        }
    }

    if (addr1.length > 0) {
        addr1 = addr1.substr(0, addr1.length - 2);
    }
    if (addr2.length > 0) {
        addr2 = addr2.substr(0, addr2.length - 2);
    }

    if ($.trim(venue) == '') {
        venue = city;
        if ($.trim(addr1) != '') {
            venue = addr1;
        }
    }
    if ($.trim(addr1) == '') {
        addr1 = city;
        if ($.trim(addr2) != '') {
            addr1 = addr2;
            addr2 = "";
        }
    }
    if (pincode && !isNaN(pincode)) {
        document.getElementById('pincode').value = pincode;
    }
    document.getElementById('eventVenue').value = venue;
    document.getElementById('eventAddress1').value = addr1;
    document.getElementById('eventAddress2').value = addr2;
//    document.getElementById('city_value').value = city;
//    document.getElementById('state_value').value = state;
//    document.getElementById('country_value').value = adddressObj.country;
    document.getElementById('city').value = city;
    document.getElementById('state').value = state;
    document.getElementById('country').value = adddressObj.country;
    $('#event_tags').tagsinput('remove', oldCity.toLowerCase());
    $('#event_tags').tagsinput('add', city.toLowerCase());
    $('#eventAddress1').focus();
    $('#city').valid();
    $('#country').valid();
    $('#state').valid();
    var latitude = place.geometry.location.lat();
    var longitude = place.geometry.location.lng();
    $('#latitude').val(latitude);
    $('#longitude').val(longitude);

    oldCity = city;
    var country = adddressObj.country;
    var data = {};
    data.keyWord = country;
    url = api_countrySearch;
    $.ajax({
        method: 'GET',
        url: url,
        data: data,
        headers: {'Content-Type': 'application/x-www-form-urlencoded',
            'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}
    }).success(function (data) {
        gettaxes();
        if (data.response.total > 0) {
            var countryId = data.response.countryList[0].id;
            var data = {};
            data.countryId = countryId;
            url = api_countryDetails;
            $.ajax({
                method: 'GET',
                url: url,
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}
            }).success(function (data) {
                var timeZoneId = data.response.timezoneId;
                if (timeZoneId) {
                    $("#timeZoneId > [value=" + timeZoneId + "]").attr("selected", "true");
                } else {
                    alert('No TimeZone');
                }

            }).error(function (data) { 
                alert('Something went wrong please try again');
            });
        } else {
            alert('No Country');
        }
    }).error(function (data) { 
        alert('Something went wrong please try again');
    });



}
// [END region_fillform]

// [START region_geolocation]
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var geolocation = new google.maps.LatLng(
                    position.coords.latitude, position.coords.longitude);
            var circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
        });
    }
}



// Tickets Adding functionality in Create Event Page


$(document).on('click', '.add_ticket', function () {
    $('.setting_content').hide();
    var ticket_html = $('#dummy-ticket').html();
    var ticketscount = $("#ticketscount").val();
    ticketscount = parseInt(ticketscount);
    $("#ticketscount").val(ticketscount);
    var maxArrIndex = $("#maxArrIndex").val();
    maxArrIndex = parseInt(maxArrIndex);
    $("#maxArrIndex").val(maxArrIndex);
    $('#div_ticketwidget').find('.create_eve_tickets').append(ticket_html);
    $('#div_ticketwidget').find('.eventtickettype').find('.add_ticket').hide();
    $('.create_eve_tickets .eventtickettype:last').find('.event_start_date').attr('id', 'startdate' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.event_end_time').attr('id', 'endtime' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.event_end_date').attr('id', 'enddate' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.event_start_time').attr('id', 'starttime' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.settingsIcon').attr('id', 'settingicon' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.settingsIcon').attr('id', 'settingicon' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.ticketType').attr('id', 'ticketType' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.ticketpricespan').attr('id', 'ticketpricespan' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.ticketType').attr('id', 'ticketType' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.ticketType').attr('ticketcount', ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.setting_content').attr('id', 'setting_content' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.ticketprice').attr('id', 'price' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.currencyType').attr('id', 'currencyType' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.ticketpricespan').attr('id', 'ticketpricespan' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.ticketName').attr('id', 'ticketName' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.ticketNameError').attr('id', 'ticketNameError' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.ticketDescription').attr('id', 'ticketDescription' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.ticketDescriptionError').attr('id', 'ticketDescriptionError' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.ticketOrder').attr('id', 'order' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.ticketOrderError').attr('id', 'ticketOrderError' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.minquantity').attr('id', 'minquantity' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.minQtyError').attr('id', 'minQtyError' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.maxquantity').attr('id', 'maxquantity' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.priceError').attr('id', 'priceError' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.maxQtyError').attr('id', 'maxQtyError' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.minQtyError').attr('id', 'minQtyError' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.ticketquantity').attr('id', 'quantity' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('.ticketQtyError').attr('id', 'ticketQtyError' + ticketscount);
    $('.create_eve_tickets .eventtickettype:last').find('#ticketpricespan' + ticketscount).hide();
    $('.create_eve_tickets .eventtickettype:last').find('#price' + ticketscount).show();
    $('.create_eve_tickets .eventtickettype:last').find('#price' + ticketscount).val('');
    $('.create_eve_tickets .eventtickettype:last').find('#ticketName' + ticketscount).val('');
    maxArrIndex = $("#maxArrIndex").val();
    $('.create_eve_tickets .eventtickettype:last').find('.ticketArrIndex').attr('value', maxArrIndex);
    $('.create_eve_tickets .eventtickettype:last').find('.indexedTicketArr').attr('value', ticketscount);
    maxArrIndex = parseInt(maxArrIndex) + 1;
    $('#startdate' + ticketscount).removeClass('hasDatepicker');
    $('#enddate' + ticketscount).removeClass('hasDatepicker');
    $("#order" + ticketscount).val(ticketscount + 1);
    //removed this and kept as a defaultDates function
//    var actdate = new Date();
//    //changed to current time as per req
//    //var dtct = new Date(actdate.getTime() + 15 * 60000);
//    var dtct = new Date(actdate.getTime());
//    var dtsh = dtct.getHours(), dtsm = dtct.getMinutes();
//    var defaultstarttime = (dtsh >= 12) ? ((((dtsh - 12) < 9) ? ('0' + (dtsh - 12)) : dtsh - 12) + ':' + dtsm + ' PM') : (dtsh + ':' + dtsm + ' AM');
//    //changed to current time as per req
//    //var dtet = new Date(actdate.getTime() + 30 * 60000);
//    var dtet = new Date(actdate.getTime());
//    var dteh = dtet.getHours(), dtem = dtet.getMinutes();
//    var defaultendtime = (dteh >= 12) ? (dteh - 12 + ':' + dtem + ' PM') : (dteh + ':' + dtem + ' AM');
//    //var currentdate = actdate.getMonth() + 1 + '/' + actdate.getDate() + '/' + actdate.getFullYear();
    var defDates = defaultDates();
    var currentdate = defDates.currentdate;
    var defaultstarttime = defDates.defaultstarttime;
    var defaultendtime = defDates.defaultendtime;
    
    var $datepicker = $('#startdate' + ticketscount);
    var $enddatepicker = $('#enddate' + ticketscount);
    var eventstartdate = $('#start_date').val();
    var eventenddate = $('#end_date').val();
    $enddatepicker.datepicker();
    $enddatepicker.datepicker("option", "minDate", new Date());
    $enddatepicker.datepicker("option", "maxDate", eventenddate);
    //checking event startdatetime with current 
    //updating ticket enddatetime to event enddatetime if it is less than current
    var eventstartdatetime = new Date(Date.parse($('#start_date').val() + " " + $('#event_start').val()));
    var eventenddatetime = new Date(Date.parse(currentdate + " " + defaultstarttime));
    if (eventstartdatetime <= eventenddatetime) {
        //if ((new Date($('#start_date').val()) <= new Date(currentdate)) && ($('#event_start').val() < defaultstarttime)) {
        $enddatepicker.datepicker("setDate", eventenddate);
        defaultendtime = $('#event_end').val();
    } else {
        $enddatepicker.datepicker("setDate", eventstartdate);
        defaultendtime = $('#event_start').val();
    }
    $datepicker.datepicker();
    $datepicker.datepicker('option', 'minDate', new Date());
    $datepicker.datepicker('setDate', new Date());
    $datepicker.datepicker('option', 'onClose', function (selectedDate) {
        $datepicker.datepicker("setDate", selectedDate);
        $enddatepicker.datepicker("option", "minDate", selectedDate);
    });

    $('#starttime' + ticketscount).timepicker({defaultTime: defaultstarttime});
    $('#endtime' + ticketscount).timepicker({defaultTime: defaultendtime});


    //Hide the price field
    if ($("#registrationType1").prop("checked")) {

        $('#ticketpricespan' + ticketscount).show();
        $('#ticketpricespan' + ticketscount).html(0);
        $('#priceError' + ticketscount).hide();
        $('#price' + ticketscount).hide();
        $('#currencyType' + ticketscount).parent().parent().hide();
        $('#ticketType' + ticketscount + ' option[value="1"]').prop('selected', true);
        $('#ticketpricespan' + ticketscount).attr('disabled', 'disabled');
    }
    if ($("#registrationType2").prop("checked")) {
        $('#currencyType' + ticketscount).show();
        $('#currencyType' + ticketscount).parent().parent().show();
    }
    ticketscount = parseInt(ticketscount) + 1;
    $("#ticketscount").val(ticketscount);
    $("#maxArrIndex").val(maxArrIndex);

    var currentWindowWidth = $(window).width();
    var scrollTopHeight = 100;
    if (currentWindowWidth < 750) {
        scrollTopHeight = 480;
    }

    $('html, body').animate({scrollTop: $('#addnewticket').offset().top - scrollTopHeight}, 'slow');

});

$(document).on('click', '.deletTicket', function () {
    $(this).parents('.eventtickettype').remove();
    if ($('.create_eve_tickets').find('.eventtickettype').length == 0) {
        $("#ticketscount").val(0);
        $("#maxArrIndex").val(0);

    }
    //var ticketscount = parseInt($('#ticketscount').val()) - 1;
    //$('#ticketscount').val(ticketscount);
});

$(document).on('click', '.settingsIcon', function () {
    var olddata = $(this).attr('data-old');
    if (olddata == 1) {
        $(this).parents('.eventtickettype').find('.setting_content').slideToggle('slow');
        return false;
    }
    var id = $(this).attr('id');
    var counterValue = $(this).attr('id').replace('settingicon', '');
    $('.eventtickettype').attr("id", '');
    $(this).parents('.eventtickettype').attr("id", 'activeticket');
    if ($('#activeticket').find('.setting_content').is(":visible")) {
        $(this).parents('.eventtickettype').find('.setting_content').slideToggle('slow');
    } else {
        var countryName = $.trim($('#country').val());
        var stateName = $.trim($('#state').val());
        var cityName = $.trim($('#city').val());
        var ticketType = $('#activeticket').find('.ticketType').val();
        if (ticketType != 2) {
            $('#activeticket').find('.taxList_div').hide();
            $('#activeticket').find('.taxList_ul').empty();
        }
        else if ((countryName != '' || stateName != '' || cityName != '') && ($.trim($('#activeticket').find('.taxList_ul').html()) == "")) {
            $.ajax({
                url: api_ticketCalculateTaxes,
                type: 'POST',
                data: {countryName: countryName, stateName: stateName, cityName: cityName, status: 1},
                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'},
                success: function (data) {
                    var resultCount = data.response.total;
                    var taxString = '';
                    if (resultCount > 0) {
                        var ticketid = ($('#activeticket').find('.ticketArrIndex').attr('value') != '') ? $('#activeticket').find('.ticketArrIndex').attr('value') : 0;
                        taxString += '<span class="pull-left addTaxes" data_value="' + id + '">Add Taxes?</span><div class="add_taxes add_taxes_' + id + '"><input type="hidden" name="taxmappingcount[' + ticketid + ']" value="' + counterValue + '" >';
                        $.each(data.response.taxList, function (index, resString) {
                            taxString +=
                                    '<li class="TaxField">' +
                                    '<span class="custom-checkbox">' +
                                    '<input type="checkbox" name="taxArray[' + ticketid + '][]" value="' + resString.id + '" id="serviceTax' + index + '" class="taxCheckBox">' +
                                    '</span>' +
                                    '<h5>' + resString.label + '</h5>' +
                                    '</li>' +
                                    '<li class="TaxField_width">' +
                                    '<ul style="margin: 0;">' +
                                    '<li>' +
                                    '<input type="text" disabled class="form-control eventFields TaxField_width" value="' + resString.value + '">' +
                                    '</li>' +
                                    '</ul>' +
                                    '</li>';
                        });
                        taxString += '</div>';
                        if ($('#activeticket').find('.taxList_ul').text() == "" && $('#activeticket').find('.ticketType').val() == 2) {
                            $('#activeticket').find('.taxList_ul').html(taxString);
                            $('.taxList_div').show();
                            // $('.add_taxes').show();
                        }
                        else {
                            if ($('#eventId').val() == 0) {
                                $('.taxList_div').hide();
                                $('.taxList_ul').empty();
                            }
                        }

                    }
                }
            });
        }
        $(this).parents('.eventtickettype').find('.setting_content').slideToggle('slow');
    }


});

$(document).on('blur', '#city', function () {
    gettaxes();
});
var currentRequest = null;
function gettaxes() {
    var countryName = $.trim($('#country').val());
    var stateName = $.trim($('#state').val());
    var cityName = $.trim($('#city').val());
    if (countryName != '' || stateName != '' || cityName != '') {
        currentRequest = $.ajax({
            url: api_ticketCalculateTaxes,
            type: 'POST',
            data: {countryName: countryName, stateName: stateName, cityName: cityName, status: 1},
            headers: {'Content-Type': 'application/x-www-form-urlencoded',
                'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'},
            beforeSend: function () {
                if (currentRequest != null) {
                    currentRequest.abort();
                }
            },
            success: function (data) {
                var resultCount = data.response.total;
                if (resultCount > 0) {
                    $('.eventtickettype').each(function (key, value) {
                        var taxString = '';
                        var ticketid = $(value).find('.ticketArrIndex').attr('value');
                        var counterValue = $(value).find('.settingsIcon').attr('id');
                        counterValue = counterValue.replace('settingicon', '');
                        taxString += '<input type="hidden" name="taxmappingcount[' + ticketid + ']" value="' + ticketid + '" >';
                        $.each(data.response.taxList, function (index, resString) {
                            taxString +=
                                    '<li class="TaxField">' +
                                    '<span class="custom-checkbox">' +
                                    '<input type="checkbox" name="taxArray[' + ticketid + '][]" value="' + resString.id + '" id="serviceTax' + counterValue + '" class="taxCheckBox">' +
                                    '</span>' +
                                    '<h5>' + resString.label + '</h5>' +
                                    '</li>' +
                                    '<li class="TaxField_width">' +
                                    '<ul style="margin: 0;">' +
                                    '<li>' +
                                    '<input type="text" disabled class="form-control eventFields TaxField_width" value="' + resString.value + '">' +
                                    '</li>' +
                                    '</ul>' +
                                    '</li>';
                        });
                        if ($(value).parent().attr('id') != 'dummy-ticket' && $(value).find('.ticketType').val() == 2) {
                            if ($('#eventId').val() > 0) {
                                $(value).find('.availableTaxes').html(taxString);
                            } else {
                                $(value).find('.add_taxes').html(taxString);
                            }

                        }

                    });
                }
            }
        });


    }
}


$(document).on('change', '.ticketType', function () {
    var ticketCount = $(this).attr('ticketcount');
    if ($('#setting_content' + ticketCount).is(":visible")) {
        $('#setting_content' + ticketCount).slideToggle('slow');
    }
    var seltickid = $(this).attr('id');
    var thenum = seltickid.replace(/^\D+/g, '');
    var selval = $(this).val();
    if (selval == 1 || selval == 3 || selval == 4) {
        $('#price' + thenum).hide();
        $('#price' + thenum).val('');
        $('#setting_content' + ticketCount).find('.change_currency').hide();
        //taxCheckBox
        var taxesCheckboxes = $('#setting_content' + ticketCount).find('.taxCheckBox:checked');
        $.each(taxesCheckboxes, function (k, v) {
            $(v).trigger('click');
        });
        if (selval == 1) {
            $(this).parents('.eventtickettype').find('.create_ticket_order').show();
            $('#ticketpricespan' + thenum).show();
            $('#ticketpricespan' + thenum).html(0);
            $('#priceError' + thenum).hide();
            $('#ticketpricespan' + thenum).attr('disabled', 'disabled');
            $('#currencyType' + thenum).parent().parent().hide();
            $('#qtyDiv' + thenum).show();
            $('#ticketpricespan' + thenum).siblings('label').show();
        } else if (selval == 3) {
            $(this).parents('.eventtickettype').find('.create_ticket_order').hide();
            $('#ticketpricespan' + thenum).html('');
            $('#ticketpricespan' + thenum).hide();
            $('#priceError' + thenum).hide();
            $('#currencyType' + thenum).parent().parent().show();
            $(this).parents().find('.freeticketcurrency').remove();
            $('#qtyDiv' + thenum).hide();
            $('#ticketpricespan' + thenum).siblings('label').hide();
        } else if (selval == 4) {
            $(this).parents('.eventtickettype').find('.create_ticket_order').show();
            $('#price' + thenum).show();
            //$('#price' + thenum).val('');
            $('#ticketpricespan' + thenum).removeAttr('disabled');
            $('#currencyType' + thenum).parent().parent().show();
            $(this).parents().find('.freeticketcurrency').remove();
            $('#qtyDiv' + thenum).show();
            $('#ticketpricespan' + thenum).siblings('label').show();
        }
    } else {
        $(this).parents('.eventtickettype').find('.create_ticket_order').show();
        $('#setting_content' + ticketCount).find('.change_currency').show();
        $('#price' + thenum).show();
        $('#price' + thenum).val('');
        $('#currencyType' + thenum).parent().parent().show();
        $('#ticketpricespan' + thenum).hide();
        $('#ticketpricespan' + thenum).removeAttr('disabled');
        $('#qtyDiv' + thenum).show();
        $('#ticketpricespan' + thenum).siblings('label').show();
    }
    return false;
});
$(function () {

    $("#editurl").hide();
    $("#eventVenue").delay(800).css({backgroundColor: 'none !important;'});

    $('#start_date').datepicker({
        minDate: new Date(),
        changeMonth: true,
        numberOfMonths: 1,
        dateFormat: "mm/dd/yy",
        onClose: function (selectedDate) {
            $('.setting_content').hide();
            $('#start_date').datepicker("setDate", selectedDate);
            $('#end_date').datepicker("option", "minDate", selectedDate);
            $('.event_end_date,.edit_event_end_date').datepicker('option', 'maxDate', $('#end_date').val());
            changeTicketEndDate(selectedDate);
        }
    });
    $('#end_date').datepicker({
        minDate: new Date(),
        defaultDate: "0",
        changeMonth: true,
        numberOfMonths: 1,
        dateFormat: "mm/dd/yy",
        onClose: function (selectedDate) {
            $('#end_date').datepicker("setDate", selectedDate);
            $('.event_end_date,.edit_event_end_date').datepicker('option', 'maxDate', selectedDate);
            var defdate = defaultDates();
            var eventstartdatetime = new Date(Date.parse($('#start_date').val() + " " + $('#event_start').val()));
            var eventenddatetime = new Date(Date.parse(defdate.currentdate + " " + defdate.defaultstarttime));
            if(eventstartdatetime < eventenddatetime){
                changeTicketEndDate(selectedDate);
            }
        }
    });

    if ($('#eventId').val() == 0) {
        initailizeDates();
        $('#event_start').timepicker().on('changeTime.timepicker', function (e) {
            $('.event_end_time').timepicker('setTime', e.time.value);
        });
        $('.ticketType option[value="2"]').attr('selected', 'selected');

    }
    //edit event
    if ($('#eventId').val() > 0) {
        if($('#userType').val() !== 'undefined' && $('#userType').val() != "superadmin"){ 
            $("#end_date").datepicker("option", "minDate", new Date());
        }
        $("#event_end, .event_end_time, #event_start,.event_start_time").timepicker();
        /* //change ticket end time based on event start time 
         $('#event_start').timepicker().on('changeTime.timepicker', function (e) {
         $('.event_end_time').timepicker('setTime', e.time.value);
         });*/
        $('.event_start_date').datepicker({
            minDate: new Date(),
            defaultDate: new Date(),
            onClose: function (selectedDate) {
                var id = $(this).attr('id');
                $('#' + id).datepicker("setDate", selectedDate);
                var enddateId = id.split('startdate');
                $('#enddate' + enddateId[1]).datepicker("option", "minDate", selectedDate);
            }
        });
        $(".edit_event_end_date").datepicker({minDate: new Date(), defaultDate: new Date(), maxDate: $('#end_date').val()});
        var eventstartdate = $('#start_date').val();
        //   var eventstartTime = $('#event_start').val();
        if (new Date(eventstartdate) < new Date()) {
            $("#start_date").datepicker("option", "minDate", eventstartdate);
        }
        // $("#start_date").datepicker('setDate', eventstartdate);
        //  $("#event_start").val(eventstartTime);

        //  var eventenddate = $('#end_date').val();
        // var eventendtime = $('#event_end').val();
        // $("#end_date").datepicker('setDate', eventenddate);
        //   $("#event_end").val(eventendtime);


        if (eventStatus == 1) {
            $('input[name="url"]').attr("readonly", "readonly");
            $("#editurl").show();
        }
//        $('.event_start_date').datepicker('option', 'onClose', function (selectedDate) {
//            var id = $(this).attr('id');
//            $('#' + id).datepicker("setDate", selectedDate);
//            var enddateId = id.split('startdate');
//            $('#enddate' + enddateId[1]).datepicker("option", "minDate", selectedDate);
//        });

        $('.event_type .custom-radio').each(function () {
            if ($(this).hasClass('selected')) {
                var seltypeval = $(this).children(".selecteventtype").val();
                if (seltypeval == 3) {
                    $("#div_ticketwidget").hide();
                    $("#ticketsalebtn").hide();
                    $('#addnewticket').hide();
                } else {
                    $("#div_ticketwidget").show();
                    $("#ticketsalebtn").show();
                    $('#addnewticket').show();
                }
                if (seltypeval == 1) {
                    $(".taxCheckBox").hide();
                } else {
                    $(".taxCheckBox").show();
                }
            }
        });
        $('#subCategoryName').trigger('blur');
        //edit event set event min end date to event start date 
        $('#end_date').datepicker('option', 'minDate', eventstartdate);
    }


});
function initailizeDates() {

    $("#event_start").timepicker({
        defaultTime: "9:00 AM",
    });
    $("#event_end").timepicker({
        defaultTime: "6:00 PM"
    });

    var $datepicker = $('#startdate0');
    var eventstartdate = $('#start_date').val();
    //var d = new Date();
    $('#enddate0').datepicker();
    $('#enddate0').datepicker("option", "minDate", new Date());
    $('#enddate0').datepicker('setDate', eventstartdate);
    $datepicker.datepicker();
    $datepicker.datepicker('option', 'minDate', new Date());
    $datepicker.datepicker('setDate', new Date());
    $datepicker.datepicker('option', 'onClose', function (selectedDate) {
        //  $('.setting_content').hide();
        $('#startdate0').datepicker("setDate", selectedDate);
        $('#enddate0').datepicker("option", "minDate", selectedDate);
        // changeTicketEndDate(selectedDate);
    });
    $('#starttime0').timepicker();
    $('#endtime0').timepicker();
}
$('#startdate0').removeClass("hasDatepicker");
$('#enddate0').removeClass("hasDatepicker");
//$(window).bind("load", function () {
//
//});
$(document).on('click', '#editurl', function () {

    $('input[name="url"]').removeAttr("readonly");
});
function deleteticket(ticketId, eventId) {

    $.ajax({
        url: api_ticketDelete,
        method: 'POST',
        headers: {'Authorization': 'bearer ' + client_ajax_call_api_key},
        data: {ticketId: ticketId, eventId: eventId},
        success: function (res) {
            alert(' Ticket deleted successfully');
        }
    });
}

//Ticket section validations
function ticketValidations() {
    //   var isValid = true;
    var isValid = true;
    var validmsgs = [];
    $('.ticketName').each(function () {
        var tktId = $(this).attr('id').replace('ticketName', '');
        if (tktId.length > 0) {
            var tktName = $.trim($('#ticketName' + tktId).val());
            var alphaNumeric = /^[0-9a-zA-Z \$\%\#\&\_\-\*\@\+\,\(\)]+$/;
            //ticket name validation
            if (tktName == '') {
                isValid = false;
                $('#ticketNameError' + tktId).text('Name is required.');
                $('#ticketNameError' + tktId).show();
                $('#ticketName' + tktId).focus();
                $('html, body').animate({scrollTop: $('#ticketName' + tktId).offset().top - 100}, 'slow');
                validmsgs.push(isValid)
            } else if (tktName.length < 2) {
                isValid = false;
                $('#ticketNameError' + tktId).text('Name should be at least 2 characters');
                $('#ticketNameError' + tktId).show();
                $('#ticketName' + tktId).focus();
                $('html, body').animate({scrollTop: $('#ticketName' + tktId).offset().top - 100}, 'slow');
                validmsgs.push(isValid)
            } else if (tktName.length > 75) {
                isValid = false;
                $('#ticketNameError' + tktId).text('Name should be at most 75 characters');
                $('#ticketNameError' + tktId).show();
                $('#ticketName' + tktId).focus();
                $('html, body').animate({scrollTop: $('#ticketName' + tktId).offset().top - 100}, 'slow');
                validmsgs.push(isValid)
            } else if (!alphaNumeric.test(tktName)) {
                isValid = false;
                $('#ticketNameError' + tktId).text('Name can contain a-z,A-Z,0-9, _,-,*,@,+,(,),&,%,$,# and comma characters');
                $('#ticketNameError' + tktId).show();
                $('#ticketName' + tktId).focus();
                $('html, body').animate({scrollTop: $('#ticketName' + tktId).offset().top - 100}, 'slow');
                validmsgs.push(isValid)
            } else {
                $('#ticketNameError' + tktId).hide();
            }

            //ticket order validation
            if (!/^[0-9]+$/.test(($('#order' + tktId).val())) || (parseInt($('#order' + tktId).val()) <= 0 && $('#order' + tktId).val() != '0')) {
                isValid = false;
                $('#ticketOrderError' + tktId).text('Ticket order must be a numeric and greater than or equal to zero');
                $('#ticketOrderError' + tktId).show();
                $('#order' + tktId).focus();
                $('html, body').animate({scrollTop: $('#order' + tktId).offset().top - 100}, 'slow');
                validmsgs.push(isValid)
            } else {
                $('#ticketOrderError' + tktId).hide();
            }

            if ($('#ticketDescription' + tktId).val().length > 300) {
                isValid = false;
                $('#ticketDescriptionError' + tktId).text('Ticket description should be at most 300 characters');
                $('#ticketDescriptionError' + tktId).show();
                $('#setting_content' + tktId).slideDown('slow');
                $('#ticketDescription' + tktId).focus();
                $('html, body').animate({scrollTop: $('#ticketDescription' + tktId).offset().top - 100}, 'slow');
                validmsgs.push(isValid)
            } else {
                $('#ticketDescriptionError' + tktId).hide();
            }
            //ticket price validation
            if ($.trim($('#price' + tktId).val()) == '' && ($('#ticketType' + tktId).val() == '2' || $('#ticketType' + tktId).val() == '4')) {
                isValid = false;
                $('#priceError' + tktId).text('Ticket price is required');
                $('#priceError' + tktId).show();
                $('#price' + tktId).focus();
                $('html, body').animate({scrollTop: $('#price' + tktId).offset().top - 100}, 'slow');
                validmsgs.push(isValid)
            } else if (!/^[0-9]+$/.test(($('#price' + tktId).val())) && ($('#ticketType' + tktId).val() == '2' || $('#ticketType' + tktId).val() == '4')) {
                isValid = false;
                $('#priceError' + tktId).text('Ticket Price must be number');
                $('#priceError' + tktId).show();
                $('#price' + tktId).focus();
                $('html, body').animate({scrollTop: $('#price' + tktId).offset().top - 100}, 'slow');
                validmsgs.push(isValid)
            } else if (parseInt($('#price' + tktId).val()) <= 0 && ($('#ticketType' + tktId).val() == '2' || $('#ticketType' + tktId).val() == '4')) {
                isValid = false;
                $('#priceError' + tktId).text('Ticket Price must be greater than zero');
                $('#priceError' + tktId).show();
                $('#price' + tktId).focus();
                $('html, body').animate({scrollTop: $('#price' + tktId).offset().top - 100}, 'slow');
                validmsgs.push(isValid)
            } else {
                $('#priceError' + tktId).hide();
            }

            //ticket quantity validation
            if (!/^[0-9]+$/.test(($('#quantity' + tktId).val())) && $('#ticketType' + tktId).val() != '3') {
                isValid = false;
                $('#ticketQtyError' + tktId).text('Ticket quantity must be a numeric value');
                $('#ticketQtyError' + tktId).show();
                $('#quantity' + tktId).focus();
                if (!$(this).parents('.eventtickettype').find('.setting_content').is(":visible")) {
                    $(this).parents('.eventtickettype').find('.setting_content').slideToggle('slow');
                }
                validmsgs.push(isValid)
            } else {
                $('#ticketQtyError' + tktId).hide();
            }

            //ticket min qty validation
            if (!/^[0-9]+$/.test(($('#minquantity' + tktId).val())) && $('#ticketType' + tktId).val() != '3') {
                isValid = false;
                $('#minQtyError' + tktId).text('Ticket min quantity must be a numeric value');
                $('#minQtyError' + tktId).show();
                //  $('#settingicon' + tktId).trigger('click');
                if (!$(this).parents('.eventtickettype').find('.setting_content').is(":visible")) {
                    $(this).parents('.eventtickettype').find('.setting_content').slideToggle('slow');
                }
                $('#minquantity' + tktId).focus();
                // $('html, body').animate({scrollTop: $('#minquantity' + tktId).offset().top - 100}, 'slow');
                validmsgs.push(isValid)
            } else {
                $('#minQtyError' + tktId).hide();
            }

            //ticket max qty validation
            if (!/^[0-9]+$/.test(($('#maxquantity' + tktId).val())) && $('#ticketType' + tktId).val() != '3') {
                isValid = false;
                $('#maxQtyError' + tktId).text('Ticket max quantity must be a numeric value');
                $('#maxQtyError' + tktId).show();
                if (!$(this).parents('.eventtickettype').find('.setting_content').is(":visible")) {
                    $(this).parents('.eventtickettype').find('.setting_content').slideToggle('slow');
                }
                //  $('#settingicon' + tktId).trigger('click');
                $('#maxquantity' + tktId).focus();
                //  $('html, body').animate({scrollTop: $('#maxquantity' + tktId).offset().top - 100}, 'slow');
                validmsgs.push(isValid)
            } else if (parseInt($('#quantity' + tktId).val()) < $('#maxquantity' + tktId).val()) {
                isValid = false;
                $('#maxQtyError' + tktId).text('Max quantity must be a less than qty value');
                $('#maxQtyError' + tktId).show();
                if (!$(this).parents('.eventtickettype').find('.setting_content').is(":visible")) {
                    $(this).parents('.eventtickettype').find('.setting_content').slideToggle('slow');
                }
                // $('#settingicon' + tktId).trigger('click');
                $('#maxquantity' + tktId).focus();
                //$('html, body').animate({scrollTop: $('#maxquantity' + tktId).offset().top - 100}, 'slow');
                validmsgs.push(isValid)
            } else if (parseInt($('#maxquantity' + tktId).val()) < parseInt($('#minquantity' + tktId).val())) {
                isValid = false;
                $('#maxQtyError' + tktId).text('Max quantity must be a less than min qty value');
                $('#maxQtyError' + tktId).show();
                if (!$(this).parents('.eventtickettype').find('.setting_content').is(":visible")) {
                    $(this).parents('.eventtickettype').find('.setting_content').slideToggle('slow');
                }
                //  $('#settingicon' + tktId).trigger('click');
                $('#maxquantity' + tktId).focus();
                //  $('html, body').animate({scrollTop: $('#maxquantity' + tktId).offset().top - 100}, 'slow');
                validmsgs.push(isValid)
            } else {
                $('#maxQtyError' + tktId).hide();
            }

        }

    });
    if ($.inArray('false', validmsgs) && validmsgs.length > 0) {
        return false;
    } else {
        return isValid;
    }

}

//To change the ticket end date on change of event start date
function  changeTicketEndDate(selectedDate) {
    $('.event_end_date').datepicker('setDate', selectedDate);
}
var submitValue = 'save';
//$('#event-desc').on('keyup', function () {
//    tinymce.triggerSave();
//});
$(document).ready(function () {
    $('#createEventForm').validate({
        ignore: [],
        rules: {
            title: {required: true, titlePattern: true, notonlySpecialChars: true, minlength: 5, maxlength: 255},
            description: {required: true, minlength: 50},
            order: {required: true, min: 1},
            categoryId: {required: true},
            subCategoryName: {required: true},
            startDate: {required: true},
            startTime: {required: true},
            endDate: {required: true},
            endTime: {required: true},
            url: {required: true, urlPattern: true},
            //tags: {required: true},
            venueName: {required: true, maxlength: 255, venuePattern: true},
            country: {required: true},
            state: {required: true},
            city: {required: true},
            venueaddress1: {maxlength: 150, venuePattern: true},
            venueaddress2: {maxlength: 150},
            pincode: {maxlength: 6, number: true},
            bannerImage: {accept: 'image/*', extension: 'jpeg|jpg|png', filesize: 2000000},
            thumbImage: {accept: 'image/*', extension: 'jpeg|jpg|png', filesize: 500000},
            //"ticketName[]": {required: true, ticketNamePattern: true, minlength: 4, maxlength: 75}
        },
        messages: {
            title: {required: "Event title is required.", notonlySpecialChars: "Event title can not have only special charcters", titlePattern: "Event title can contain letters,numbers,space,comma,@, ( , ) , - , _ , ' , + , = , | , : , ; , . , /", minlength: "Event title should be at least 5 character's", maxlength: "Event title should not be more than 255 character's"},
            description: {required: "Event Description is required.", minlength: "Event Description should be at least 50 character's"},
            categoryId: {required: "Event category is required."},
            subCategoryName: {required: "Event subcategory is required."},
            url: {required: "Event Url is required.", urlPattern: "Event Url can contain  a-z , A-Z , - , _ and number's only"},
            //tags: {required: "Event Tags is required."},
            venueName: {required: "Event Venue is required.", maxlength: "Event Venue is of maximum 100 characters", venuePattern: "At least one alpha numeric characters required."},
            venueaddress1: {maxlength: "Event Address1 is of maximum 100 characters", venuePattern: "Atleast one alpha numeric characters required."},
            venueaddress2: {maxlength: "Event Address2 is of maximum 100 characters"},
            startDate: {required: "Event State Date is required."},
            startTime: {required: "Event Start Time is required."},
            endDate: {required: "Event End Date is required."},
            endTime: {required: "Event End Time is required."},
            country: {required: "Country is required."},
            state: {required: "State is required."},
            city: {required: "City is required."},
            pincode: {maxlength: "Pincode is  maximum 6 characters.", number: "Pincode should be a number."},
            bannerImage: {required: "Event banner is required", accept: 'choose file of type image', extension: "choose image of type jpg,jpeg,png", filesize: "Image size should be less than 2 MB"},
            thumbImage: {required: "Event thumb is required", accept: 'choose file of type image', extension: "choose image of type jpg,jpeg,png", filesize: "Image size should be less than 500 KB"},
            //"ticketName[]":{required: "Ticket name is required", ticketNamePattern: "Name can contain a-z,A-Z,0-9, _,-,*,@,+,(,),&,%,$,# and comma characters", minlength: "Name should be at least 4 characters", maxlength: "Name should be at most 75 characters"},
        },
        errorPlacement: function (error, element) {
            if (element.attr('id') == 'event_tags') {
                $('#event_tags_error').html(error);
            } else if (element.attr('id') == 'categoryId') {
                $('#categoryError').html(error);
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            if ($('#' + form.id).find('.create-event-error').is(':visible').length > 0) {
                return false;
            }
            //alert(form);
            // check to make sure the form is completely valid
            var eventType = $('input[name="registrationType"]:checked').val();
            //var eventaction = $('#eventedit').val();
//                var errorMessage = "";
//                var bannerErrorMessage = "";
//                var logoErrorMessage = "";
//                var bannerError = 0;
//                var logoError = 0;
            var isValid = true;
            //   if (eventType != 3) {
            isValid = ticketValidations();
            if (isValid == false) {
                return false;
            }
            //}
            if (!urlAvailable) {
                $('#eventUrl').focus();
                return false;
            }
            if ($(form).valid() && isValid && urlAvailable)
            {
                // $scope.submitForm = function (isValid, submitValue) {
                $('#' + submitValue).addClass("loading");
                $('.createeventbuttons').attr("disabled", "disabled");
                $('#' + submitValue).attr("disabled", "disabled");
                $('#eventDataErrors').html('');
                $('#eventDataSuccess').html('');
                var subCategoryName = $.trim($('input[name="subCategoryName"]').val());

                var thumbSrc = $('#thumbImage').attr('thumb-theme-src');

                var bannerSrc = $('#bannerImage').attr('banner-theme-src');

                $('#submitValue').val(submitValue);
                //removing dummy ticket count;
                $('#ticketCount').val($('input[name^="ticketName"]').length - 1);

                var fd = new FormData();
                fd.append('acceptmeeffortcommission', $('#acceptmeeffortcommission').val());
                fd.append('private', $('input[name=private]:checked').val());
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
                var description = $('#event-desc').val();
                fd.append("subCategoryName", subCategoryName);
                fd.append("subcategoryId", subCategoryName);
                //Pick a theme related funcitons
                var bannerSrc = $('#bannerImage').attr('banner-theme-src');
                var thumbSrc = $('#thumbImage').attr('thumb-theme-src');
                if (bannerSrc !== undefined) {
                    fd.append('bannerSource', bannerSrc);
                }
                if (thumbSrc !== undefined) {
                    fd.append('thumbSource', thumbSrc);
                }
                var bannerRemove =$('#bannerImage').attr('remove-banner');
                var thumbRemove =$('#thumbImage').attr('remove-thumb');
                if(bannerRemove == 1) {
                    fd.append('removebannerSource', bannerRemove);
                }
                 if(thumbRemove == 1) {
                    fd.append('removethumbSource', thumbRemove);
                }
                var tags = $("#event_tags").tagsinput('items');
                var submitURL = api_eventCreate;
                if ($('#eventId').val() > 0) {
                    submitURL = api_eventEdit;
                }
                var url = submitURL;
                
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
                            return false;
                            //  window.location.reload();
                        }
                    } else if (submitValue == 'golive') {
                        /* Start of Wizrocket Push Code*/
                        var eventTypeVal = $('.event_type .selected input[name="registrationType"]').val();
                        var eventIdVal = data.response.id;
                        var privateval = $("#private1").val() || 0;
                        var eventTitleVal = $('#eventTitle').val();
                        var categoryVal = $('.selectCategory').text();
                        var subcategoryVal = $('#subCategoryName').val();
                        var webinarval = $("#webinar").val() || 0;
                        var cityVal = $('#city').val();
                        var countryVal = $('#country').val();
                        var stateVal = $('#state').val();
                        var pincodeVal = '';
                        wizrocket.event.push("Event Created", {
                            "Event Id": eventIdVal,
                            "Event Type": eventTypeVal,
                            "Event Title": eventTitleVal,
                            "Private Event": privateval,
                            "Category": categoryVal,
                            "Sub Category": subcategoryVal,
                            "Is Webinar": webinarval,
                            "City": cityVal,
                            "Country": countryVal,
                            "State": stateVal

                        });
                        /* End of Wizrocket Push Code*/
                        window.location.href = site_url + 'dashboard';
                    } else if (submitValue == 'preview') {
                        url = site_url + 'previewevent?view=preview&eventId=' + data.response.id;
                        var editurl = site_url + 'dashboard/event/edit/' + data.response.id;
                        //var win = window.open(url, '_blank');
                        //win.focus();
                        // $('#previewEventURL').attr('href', url);
                        // $('#previewEventURL')[0].click();
                        var previewWindow = window.open(url, 'mywindow', 'menubar=1,width=1100,height=600,resizable=yes,scrollbars=yes');
                        previewWindow.focus();
                        $('#' + submitValue).removeAttr("disabled");
                        window.location.href = editurl;

                    } else {
                        window.location.href = site_url;
                    }


                }).error(function (data, status, headers, config) {
                    $('#' + submitValue).removeClass("loading");
                    $('#' + submitValue).removeAttr("disabled");
                    $('.createeventbuttons').removeAttr("disabled");
                    $('#eventDataErrors').html('');
                    $('#eventDataSuccess').html('');
                    if (typeof data.responseJSON.response.messages !== 'undefined') {
                    $.each(data.responseJSON.response.messages, function (key, value) {
                        $('#eventDataErrors').append("<li>" + value + "</li>");
                    });
                        $('html, body').animate({
                            scrollTop: 0}, 100);
                    } else if (typeof data.responseJSON.response.ticketmessages !== 'undefined') {
                        $('html, body').animate({scrollTop: $('.create_eve_tickets').eq(0).position().top}, 'slow');
                        var openTabNotReq=["ticketType","ticketName","order","price"];
                        $.each(data.responseJSON.response.ticketmessages, function (key, value) {
                            $.each(value, function (key1, value1) {
                                $('.' + key1 + 'Error').eq(key).text(value1);
                                $('.'+key1+'Error').eq(key).show();
                                if($.inArray(key1,openTabNotReq) == -1 && !$('#setting_content'+key).is(":visible")){
                                    $('#setting_content'+key).slideToggle('slow')
                                }
                            });
                            //  $('#eventDataErrors').append("<li>" + value + "</li>");
                        });
                    }
                    $("#dummy-ticket").html(dummyhtml);

                });
            }
            return false; // prevent normal form posting
        },
        invalidHandler: function (form, validator) {
            var errors = validator.numberOfInvalids();
            var errs = [];
            $.each($(validator.errorList), function (k, v) {
                errs.push(v.element.id);
                if (v.element.id == 'state' || v.element.id == 'city' || v.element.id == 'country') {
                    $('#address').css('display', 'block');
                }

            });
            if (errors) {
                var firstInvalidElement = $(validator.errorList[0].element);
                if (firstInvalidElement.attr('name') == 'categoryId') {
                    firstInvalidElement = $(validator.errorList[1].element);
                }
                if (firstInvalidElement.attr('name') == 'description') {
                    tinyMCE.activeEditor.focus();
                    $('html, body').animate({scrollTop: $('#eventTitle').position().top}, 'slow');
                } else if (firstInvalidElement.attr('name') == 'title') {
                    //tinyMCE.activeEditor.focus();
                    $('html, body').animate({scrollTop: $('#eventTitle').position().top}, 'slow');
                    firstInvalidElement.focus();
                } else if (firstInvalidElement.attr('name') == 'city' || firstInvalidElement.attr('name') == 'state' || firstInvalidElement.attr('name') == 'country') {
                    //tinyMCE.activeEditor.focus();
                    $('#address').css('display', 'block');
                    var errorId = $('.error:visible:first').siblings('input').attr('id');
                    $('#' + errorId).focus();
                    firstInvalidElement.focus();
                    $('html, body').animate({scrollTop: $('#address').position().top}, 'slow');
                } else {
                    if (firstInvalidElement.attr('name') == 'url') {
                        $('#checkUrlAvail').hide();
                    }
                    //   var errorId = $('.error:visible:first').attr('id');
                    $('html, body').animate({scrollTop: $('#' + firstInvalidElement.attr('id')).offset().top - 100}, 'slow');
                    /*     var errorId = $('.error:visible:first').siblings('input').attr('id');
                     $('#'+errorId).focus();
                     $('html, body').animate({scrollTop: $('#' + errorId).offset().top - 100}, 'slow');*/
                }


            }
        },
    });

    $.validator.addMethod("titlePattern", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9@()-\\'_+=\s|:;./]+$/.test(value);
    }, 'Please enter a valid value.');

    $.validator.addMethod("urlPattern", function (value, element) {
        return this.optional(element) || /^[0-9a-zA-Z\_\-]+$/.test(value);
    }, 'Please enter a valid value.');

    $.validator.addMethod("venuePattern", function (value, element) {
        return this.optional(element) || /^(?=.*[a-zA-z0-9]).+$/.test(value);
    }, 'Please enter a valid value.');

    $.validator.addMethod('filesize', function (value, element, param) {
        // param = size (en bytes) 
        // element = element to validate (<input>)
        // value = value of the element (file name)
        return this.optional(element) || (element.files[0].size <= param);
    });
    //descRequired
    $.validator.addMethod('ticketNamePattern', function (value, element) {
        return this.optional(element) || /^[0-9a-zA-Z \$\%\#\&\_\-\*\@\+\,\(\)]+$/.test(value);
    });

    //only special chars not allowed in textbox/textarea
    $.validator.addMethod("notonlySpecialChars", function (value, element) {
        if (value == '') {
            return true;
        } else {
            var regEx = /[0-9a-zA-Z]/;
            if (regEx.test(value)) {
                return true;
            } else {
                return false;
            }
        }
    }, "Please enter valid input");



    $('#acceptmeeffortcommission').change(function () {
        additionalCommission();
});
});

$('#eventTitle').on('keyup', function () {
    var eventId = $('#eventId').val();
    if (eventId == 0 || (eventId > 0 && eventStatus != 1)) {
        var title = $.trim(this.value);
        var urlStr = title.replace(/[^A-Za-z0-9\-]/g, ' ');
        urlStr = urlStr.replace(/ /g, '-');
        urlStr = urlStr ? urlStr.replace(/-+/g, '-') : '';
        $('#eventUrl').val(urlStr.toLowerCase());
    }
});
$('#eventTitle').on('blur', function () {
    $('#eventTitle').trigger('keyup');
    checkUrlExists();
});
$('#save,#preview').on('click', function (e) {
    //if (submitValue != 'save') {
    tinymce.triggerSave();
    removeRules(removeSaveExit);
    checkBannerAndthumbImg();
    submitValue = this.id;
});
$('#golive').on('click', function (e) {
    tinymce.triggerSave();
    addRules(removeSaveExit);
    submitValue = this.id;
    checkBannerAndthumbImg();
});
$('#bannerImage').on('click', function () {
    this.files = null;
    this.value = '';
    // $("#bannerImage").removeAttr('banner-theme-src');
    setTimeout(function () {
        $(this).trigger('change');
    }, 10);

});
$('#thumbImage').on('click', function () {
    // $("#bannerImage").removeAttr('banner-theme-src');
    this.files = null;
    this.value = '';
    setTimeout(function () {
        $(this).trigger('change');
    }, 10);
    ;

});
$('#bannerImage').on('change', function () {
    var files = !!this.files ? this.files : [];
    if (!files.length || !window.FileReader) {
        return; // no file selected, or no FileReader support
    }
    if (/^image/.test(files[0].type)) {
        // only image file
        if (files[0].type == 'image/jpeg' || files[0].type == 'image/jpg' || files[0].type == 'image/png') {
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file

            reader.onloadend = function () { // set image data as background of div
                //	alert('hgsfhg');
                $("#bannerImageDiv").css("background-image", "url(" + this.result + ")");
                $("#bannerImage").removeAttr('banner-theme-src');
                $("#bannerImage").removeAttr('remove-banner');
                $('.upload_image,.upload_image_text').hide();
                $('#bannerImage').valid();
            };
            //   $(this).removeAttr('banner-theme-src');
            $('#eventBannerErrors').hide();
        }

    }

});
$('#thumbImage').on('change', function () {
    var files = !!this.files ? this.files : [];
    if (!files.length || !window.FileReader)
        return; // no file selected, or no FileReader support

    if (/^image/.test(files[0].type)) { // only image file
        if (files[0].type == 'image/jpeg' || files[0].type == 'image/jpg' || files[0].type == 'image/png') {
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file

            reader.onloadend = function () { // set image data as background of div
                $("#thumbImageDiv").css("background-image", "url(" + this.result + ")");
                $("#thumbImage").removeAttr('thumb-theme-src');
                $("#thumbImage").removeAttr('remove-thumb');
                $('.upload_image2,.upload_image_text2').hide();
                $('#thumbImage').valid()
            };
            // $(this).removeAttr('thumb-theme-src');
            $('#eventLogoErrors').hide();
        }
    }
    //$('#thumbImageDiv').css('background-image', "url(" + this.value + ")");
});
var categoryId = 0;
function checkUrlExists() {
    if ($("input[name = 'url']").valid()) {
        var url = $('#eventUrl').val();
        var eventId = $('#eventId').val();
        var pageUrl = api_checkUrlExists;
        var dataFormat = 'JSON';
        var method = 'GET';
        var input = {};
        input.eventUrl = url;
        input.eventId = eventId;
        var callbackSuccess = function (data) {
            $('#checkUrlAvail').text(data.response.messages);
            $('#checkUrlAvail').css('color', 'rgb(0,204,7)');
            $('#checkUrlAvail').show();
            urlAvailable = true;
            setTimeout(function () {
                $('#checkUrlAvail').hide();
            }, 8000);
        };
        var callbackFailure = function (data) {
            $('#checkUrlAvail').text(data.responseJSON.response.messages[0]);
            $('#checkUrlAvail').css('color', 'red');
            $('#checkUrlAvail').show();
            $('html,body').animate({
                scrollTop: $('#subCategoryName').offset().top
            });
            $('#eventUrl').focus();
            urlAvailable = false;
//            setTimeout(function () {
//                $('#checkUrlAvail').hide();
//            }, 5000);
        };
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
    }
}

function webinarChange(obj) {
    var status;
    /* var webinarElement = $('<input>', {"type": 'hidden', "name": "iswebinar",
     "value": 0}); */
    if (obj.value == 0) {
        status = 1;
    } else {
        status = 0;
    }
    $("#webinar").val(status);
    if (status) {
        //   $('#div_webinar').hide('fast');
        $("#webinar").parent().addClass("selected");
        $("#webinar").next().remove();
    } else {
        //    $('#div_webinar').show('fast');
        $("#webinar").parent().removeClass("selected");

    }
}
var removeSaveExit = {
    //tags: {required: true},
    venueaddress1: {maxlength: 150, venuePattern: true},
    venueaddress2: {maxlength: 150}
};

function addRules(rulesObj) {
    for (var item in rulesObj) {
        $('[name=' + item + ']').rules('add', rulesObj[item]);
    }
}

function removeRules(rulesObj) {
    for (var item in rulesObj) {
        $('[name=' + item + ']').rules('remove');
    }
}

function jqNowDate() {
    var d = new Date();
    d.setDate(d.getDate() + 2);
    var month = d.getMonth() + 1;
    var day = d.getDate();
    var formatDate =
            (('' + month).length < 2 ? '0' : '') + month + '/' +
            (('' + day).length < 2 ? '0' : '') + day + '/' +
            d.getFullYear();
    return formatDate;
}


function categoryChanged(catId, catName, catstatus, themeColor) {
    //   $scope.selectedCategoryId = catId;
    $('#categoryId').val(catId);
    // var result = $filter('filter')($scope.categoryList, catName);
    //     var themeColor = result[0].themecolor;
    $('#categoryError').text('');
    $('#categoryId').val(catId);
    $('#event_tags').tagsinput('add', catName.toLowerCase());
    var addedCategories = $('#addedCategories').val();
    if (addedCategories != '' && catName != addedCategories) {
        $('#event_tags').tagsinput('remove', addedCategories.toLowerCase());
        $('#event_tags').tagsinput('remove', addedCategories.toLowerCase());
    }
    addedCategories = catName;
    $('#addedCategories').val(addedCategories);
    $('.subselectCategory').text('Select a Sub Category').append('<span class="icon-downArrow"></span>');
    $('.selectCategory').text(catName).append('<span class="icon-downArrow"></span>');
    var ele = $('.create_eve_dropdowns a.dropdown-togglep');
    ele.css('background', themeColor);
    var bg = $('.design_event .Upload_Thumb').css('background-image');
    bg = bg.replace('url("', '').replace('")', '');
    if ($('.design_event .Upload_Thumb').css('background-image') == "none" || bg == window.location.href || bg == $('#cloudPath').val()) {
        var ele = $('.design_event .Upload_Thumb');
        ele.css('background', themeColor);
    }
    var bg = $('.design_event .upload').css('background-image');
    bg = bg.replace('url("', '').replace('")', '');
    if ($('.design_event .upload').css('background-image') == "none" || bg == window.location.href || bg == $('#cloudPath').val()) {
        var ele = $('.design_event .upload');
        ele.css('background', themeColor);
    }

    var oldSubcatLength = oldSubcat.length;
    for (var osc = 0; osc < oldSubcatLength; osc++) {
        $('#event_tags').tagsinput('remove', oldSubcat[osc].toLowerCase());
    }
    $('#subCategoryName').val('');
    $('#subCategoryName').focus();

}
$('.countryAutoComplete').autocomplete({
    source: function (request, response) {
        $.get(api_countrySearch, {keyWord: request.term}, function (data) {
            response(data.response.countryList);
        });
    },
    change: function () {

        if ($('#country').valid()) {
            $('#city').val('');
            $('#state').val('');
            $('#state').focus();
        } else {
            $('#country').focus();
        }
    }
});
$(document).on('blur', '#state', function () {
    if (!$('#country').valid()) {
        $('#country').focus();
    }

});
$('.stateAutoComplete').autocomplete({
    source: function (request, response) {
        var countryName = $.trim($('.countryAutoComplete').val());
        $.get(api_stateSearch, {keyWord: request.term, countryName: countryName}, function (data) {
            response(data.response.stateList);
        });
    },
    change: function () {
        if ($('#state').valid()) {
            $('#city').val('');
            $('#city').focus();
        } else {
            $('#state').focus();
        }

    }
});

$('.cityAutoComplete').autocomplete({
    source: function (request, response) {
        var countryName = $.trim($('.countryAutoComplete').val());
        var stateName = $.trim($('.stateAutoComplete').val());
        var addEventCheck = false;
        if (stateName != '') {
            addEventCheck = true;
        }
        $.get(api_citySearch, {keyWord: request.term, countryName: countryName, stateName: stateName, addEventCheck: addEventCheck}, function (data) {
            response(data.response.cityList);
        });
    }
});

$('#subCategoryName').autocomplete({
    source: function (request, response) {
        categoryId = $('#categoryId').val();
        /*      if (categoryId == 0) {
         $('#categoryError').html("Event category is required");
         $('#categoryError').show();
         $('#subCategoryName').val('');
         return false;
         }else{
         $('#categoryError').hide();
         $('#categoryError').html('');
         }*/
        if (categoryId > 0) {
            var input = {};
            input.categoryId = categoryId;
            input.keyword = request.term;
            input.limit = 5;
            input.getValue = true;
            $.get(api_subcategoryList, input, function (data) {
                response(data.response.subCategoryList);
            });
        }
    },
    select: function (event, ui) {
        $('#subCategoryName').val(ui.item.label);
        // $('#event_tags').tagsinput('add', ui.item.label);
    }, minLength: 0
}).focus(function () {
    $(this).autocomplete('search', $(this).val())
});
$('.theme_images ul li a img').click(function () {
    //removeRules(editbannerUploadRule);
    //removeRules(editthumbUploadRule);
    var thumbUrl = $(this).data('thumburl');
    var bannerUrl = $(this).data('bannerurl');

    $('#bannerImage').attr('banner-theme-src', bannerUrl);
    $('#thumbImage').attr('thumb-theme-src', thumbUrl);
    $('#bannerImage').removeAttr('remove-banner');
    $('#thumbImage').removeAttr('remove-thumb');
    // For Banner Image preview
    $('.upload_image, .upload_image_text').hide();
    $('.upload').css('background', "url('" + bannerUrl + "') no-repeat");
    $('.upload').css('background-size', '300px 100px');
    // For Thumb Image preview
    $('.upload_image2, .upload_image_text2').hide();
    $('.Upload_Thumb').css('background', "url('" + thumbUrl + "') no-repeat");
    $('.Upload_Thumb').css('background-size', '178px 103px');
    // For removing validation msg
    $("#thumbImage").removeClass('error');
    $("#thumbImage").siblings('label').removeClass('error');
    $("#thumbImage").siblings('label').hide();
    $("#bannerImage").removeClass('error');
    $("#bannerImage").siblings('label').removeClass('error');
    $("#bannerImage").siblings('label').hide();
    //$('#bannerImage').rules('remove');

    $('#eventBannerErrors').hide();
    $('#eventLogoErrors').hide();

});
// remove banner image
$('#removeBanner').click(function(){
 $("#bannerImage").removeAttr('banner-theme-src');
 $('#bannerImage').attr('remove-banner', 1);
 $('.upload').css('background', "url('') no-repeat");
 $('.upload_image,.upload_image_text').show();
 $('#bannerImageDiv').css("background",$('.selectCategory').css('background'));
});
// remove thumbnails
$('#removeThumb').click(function(){ 
$("#thumbImage").removeAttr('thumb-theme-src');
$('#thumbImage').attr('remove-thumb', 1);
$('.Upload_Thumb').css('background', "url('') no-repeat");
$('.upload_image2, .upload_image_text2').show();
$('#thumbImageDiv').css("background",$('.selectCategory').css('background'));
});
$(document).on('click', '#clearVenue', function (e) {
    e.preventDefault();
    var empty = ''
    document.getElementById('eventVenue').value = empty;
    document.getElementById('eventAddress1').value = empty;
    document.getElementById('eventAddress2').value = empty;
    document.getElementById('city').value = empty;
    document.getElementById('state').value = empty;
    document.getElementById('country').value = empty;
    $('#event_tags').tagsinput('remove', oldCity.toLowerCase());
    $('#eventVenue').focus();
    oldCity = '';
    if ($('.addAdd').html() == '-') {
        $('.addAdd').html('+');
        $('.add_address').toggle('fast');
    }
});
// Checking the Banner and thumb image src on submit
function checkBannerAndthumbImg() {
    var banthemesrc = $('#bannerImage').attr('banner-theme-src');
    if (banthemesrc != null && banthemesrc.length > 0) {
        $('#bannerImage').rules('remove');
    } else {
        $('#bannerImage').rules('add', {
            accept: 'image/*', extension: 'jpeg|jpg|png', filesize: 2000000,
            messages: {
                required: "Event Banner is required", accept: 'choose file of type image', extension: "choose image of type jpg,jpeg,png", filesize: "Image size should be less than 2 MB"
            }
        });
    }
    var thumbthemesrc = $('#thumbImage').attr('thumb-theme-src');
    if (thumbthemesrc != null && thumbthemesrc.length > 0) {
        $('#thumbImage').rules('remove');

    } else {
        $('#thumbImage').rules('add', {
            accept: 'image/*', extension: 'jpeg|jpg|png', filesize: 500000,
            messages: {
                required: "Event thumb is required", accept: 'choose file of type image', extension: "choose image of type jpg,jpeg,png", filesize: "Image size should be less than 500 KB"
            }
        });

    }
}

function loadtinyMce() {
    if (document.getElementById('createEventForm')) {
        setTimeout(function () {
            tinymce.init({
                selector: "textarea",
                plugins: [
                    'advlist autolink lists link charmap print preview anchor',
                    'searchreplace visualblocks visualchars code fullscreen',
                    'insertdatetime media save table contextmenu',
                    'paste jbimages  textcolor'
                ],
                toolbar1: 'insertfile undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                toolbar2: 'jbimages |  formatselect | fontselect | fontsizeselect | forecolor backcolor | print preview media',
                image_advtab: true,
                relative_urls: false,
                uploadUrl: uploadUrl,
                resize: false,
                setup: function (ed) {
                    ed.on('keyup', function (e) {
                        tinyMCE.triggerSave(); // this seems to trigger the <p>-inserting, whether or not we move back to the bookmark
                        $("#event-desc").valid();
                        // return true;
                    });
                    ed.on('init', function (e) {
                        // return true;
                    });
                }
            });
        }, 1000);
    }
}


function getTimestamp(myDate) {

    myDate = myDate.split("/");
    var newDate = myDate[1] + "/" + myDate[0] + "/" + myDate[2];
    return new Date(newDate).getTime();
}

function defaultDates(){
    var actdate = new Date();
    //changed to current time as per req
    //var dtct = new Date(actdate.getTime() + 15 * 60000);
    var dtct = new Date(actdate.getTime());
    var dtsh = dtct.getHours(), dtsm = dtct.getMinutes();
    var defaultstarttime = (dtsh >= 12) ? ((((dtsh - 12) < 9) ? ('0' + (dtsh - 12)) : dtsh - 12) + ':' + dtsm + ' PM') : (dtsh + ':' + dtsm + ' AM');
    //changed to current time as per req
    //var dtet = new Date(actdate.getTime() + 30 * 60000);
    var dtet = new Date(actdate.getTime());
    var dteh = dtet.getHours(), dtem = dtet.getMinutes();
    var defaultendtime = (dteh >= 12) ? (dteh - 12 + ':' + dtem + ' PM') : (dteh + ':' + dtem + ' AM');

    var currentdate = actdate.getMonth() + 1 + '/' + actdate.getDate() + '/' + actdate.getFullYear();
    var datesObj = {};
    datesObj['currentdate'] = currentdate;
    datesObj['defaultstarttime'] = defaultstarttime;
    datesObj['defaultendtime'] = defaultendtime;
    //console.log(datesObj);
    return datesObj;
}
$(document).on('click focus','.event_start_time',function(){
    $(this).siblings('.input-group-addon').trigger('click');
});
$(document).on('focus','.event_end_date, .event_start_date, #end_date, #start_date, #timeZoneId, .currencyType',function(){
 $('.bootstrap-timepicker-widget').removeClass('open'); 
    
});
$(document).on('click focus','.event_end_time',function(){
$(this).siblings('.input-group-addon').trigger('click');
    
});
$(document).on('click focus','#event_start',function(){
 $(this).siblings('.input-group-addon').trigger('click');
});

$(document).on('click focus','#event_end',function(){
  $(this).siblings('.input-group-addon').trigger('click');
 });