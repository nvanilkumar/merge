$(function () {

    $('.form-datepicker').datepicker({
        changeMonth: true,
        changeYear: true, yearRange: "-100:+20"});

    $('.localityAutoComplete').keypress(function (e) {
        var thisId = $(this).attr('id');
        var idArr = thisId.split('_');

        $('#Country_' + idArr[1]).val('');
        $('#State_' + idArr[1]).val('');
        $('#City_' + idArr[1]).val('');
    });

    $('.localityAutoComplete').each(function () {
        var thisId = $(this).attr('id');
        var idArr = thisId.split('_');

        initialize(idArr[1]);
    });

    /* Event that happened when user choose the payment gateway starts here */
    $('#paynow').click(function () {

        $("#dvLoading").show();
        var paymentGateWay = $('input[name="paymentGateway"]:checked').val();

        // Wizrocket Code
        wizrocket.event.push("Payment Selected", {"Payment Mode": paymentGateWay});

        $('#paymentGateway').val(paymentGateWay);

        var gateWayId = $('input[name="paymentGateway"]:checked').attr('id');
        $('#paymentGatewayId').val(gateWayId);

        $('#booking_message_div').empty();
        if ($("#ticket_registration").valid()) {

            var formData = new FormData($('#ticket_registration')[0]);
            $.ajax({
                url: booking_saveData,
                type: 'POST',
                data: formData,
                headers: {'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'},
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                async: true,
                beforeSend: function () {
                    $("#dvLoading").show();
                },
                success: function (retData) {
                    $('.userIdTextbox').val(retData.userId);
                    $('.eventsignupTextbox').val(retData.eventSignupId);

                    if (retData.status) {
                        console.dir(retData.userDetails);
                        var username = retData.userDetails.name;
                	var useremail = retData.userDetails.email;
                	var usermobile = retData.userDetails.mobile||retData.userDetails.phone;
                        var phoneCode = decodeURIComponent(getCookie('phoneCode'))||'+91';
                        var fbId = retData.userDetails.facebookid||'';
                        // Wizrocket Push
                        if(retData.signup == 1){
                            wizrocket.event.push("User Signedup",{});
                               wizrocket.profile.push(
                		        {
                		        "Site": {
                		        "Name": username,
                		        "Email": useremail, 
                                        "Phone": phoneCode+ usermobile,
                		        "FBID":fbId    
                		        }});
                            
                            
                        }else{
                            wizrocket.profile.push(
	     	      		        {
	     	      		        "Site": {
	     	      		        "Name": username,
	     	      		        "Email": useremail,
	     	      		        "Phone": phoneCode+ usermobile,
                                         "FBID":fbId    
	     	      		        }});
                                                       
                        }
//                        name = $('input[name="FullName1"]').val();
//                        email = $('input[name="EmailId1"]').val();
//                        mobile = $('input[name="MobileNo1"]').val();
//                        address = $('input[name="Address1"]').val();
//                        state = $('input[name="State1"]').val();
//                        city = $('input[name="City1"]').val();
//                        pincode = $('input[name="PinCode1"]').val();
//
//                        address = (typeof address != "undefined") ? address : '';
//                        state = (typeof state != 'undefined') ? state : '';
//                        city = (typeof city != 'undefined') ? city : '';
//                        pincode = (typeof pincode != 'undefined') ? pincode : '';

                        //addressStr = name + '@@' + email + '@@' + mobile + '@@' + address + '@@' + state + '@@' + city + '@@' + pincode;
                        var addressStr = retData.addressStr;
                        $('.primaryAddress').val(addressStr);
                        if (retData.totalPurchaseAmount == 0) {
                            var orderId = retData.orderId;
                            window.location.href = site_url + 'confirmation?orderid=' + orderId;
                        } else if (retData.seatingLayout) {
                            var orderId = retData.orderId;
                            window.location.href = site_url + 'seating?orderid=' + orderId;
                        } else {
                            $('#' + paymentGateWay + '_frm').submit();
                        }
                    } else {
                        $('#booking_message_div').text(ret.message);
                        $("html, body").animate({scrollTop: 0}, "slow");
                        $("#dvLoading").hide();
                        return false;
                    }
                },
                error: function (result) {
                    var messages = result.responseJSON.response.messages;
                    $('#booking_message_div').text(messages);
                    $("html, body").animate({scrollTop: 0}, "slow");
                    $("#dvLoading").hide();
                }
            });
        } else {
            $('html,body').animate({
                scrollTop: $('.error:visible').eq(0).offset().top - 30}, 'slow');
            $("#dvLoading").hide();
            return false;
        }
    });
    /* Event that happened when user choose the payment gateway ends here */

    /*===========================================================================================================================*/

    /* Common fields validations starts here */

    /* Custom validator methods starts here */

    $.validator.addMethod("alphabetsOnly", function (value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z]+$/);
    });

    $.validator.addMethod("alphaNumericOnly", function (value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z0-9]+$/);
    });

    /* Custom validator methods ends here */

    var rulesObj = [];
    var messagesObj = [];


    $('.customValidationClass').each(function () {
        rulesObj[this.name] = new Object();
        messagesObj[this.name] = new Object();
    });

    /* Adding validation for mandatory fields starts here */

    $('.mandatory_class').each(function () {

        var idInputArr = $(this).attr('id').split('_');
        var originalName = $(this).attr('data-originalname');
        
        var fieldName = idInputArr[0];
        if (fieldName == '') {
            fieldName = 'This field';
        }
        var fieldNameLower = fieldName.toLowerCase();
        if (fieldNameLower == 'emailid') {

            rulesObj[this.name] = {required: true, email: true};
            messagesObj[this.name] = {required: originalName + ' is required', email: 'Please enter valid email'};
        } else if (fieldNameLower == 'mobileno' || fieldNameLower == 'phoneno') {

            rulesObj[this.name] = {required: true, number: true, minlength: 10, maxlength: 10};
            messagesObj[this.name] = {required: originalName + ' is required', number: 'Please enter numbers only', minlength: 'Please enter valid number', maxlength: 'Please enter maximum 10 numbers only'};
        } else if (fieldNameLower == 'toastmaster' || fieldNameLower == 'clubnameapplicableonlyformembers') {

            rulesObj[this.name] = {required: true};
            messagesObj[this.name] = {required: 'Please Choose Club Name Applicable only for Members'};

        } else {

            rulesObj[this.name] = {required: true};
            messagesObj[this.name] = {required: originalName + ' is required'};
        }
    });

    /* Adding validation for mandatory fields ends here */

    // Bookdate validation for Deltin goa
    var EventId = parseInt($('#eventId').val());
    if ($.inArray(EventId, customValidationEventIds) != -1) {
        var bookdateConf = '<div class="form-group"><p><label><input type="checkbox" class="" name="checkboxvalid" id="checkboxvalid" > I agree with the selected booking date </label><span class="checkboxvalid_Err" style="clear:both;display:block"></span></p></div>';
        $('#ticket_registration').append(bookdateConf);
        rulesObj['checkboxvalid'] = {required: true};
        messagesObj['checkboxvalid'] = {required: "Confirm the Booking Dates"};

    }


    /* Adding the custom validations based on the Custom Field Id starts here */

    $('.customValidationClass').each(function () {
        var customFieldId = $(this).attr('data-customFieldId');

        var idInputArr = $(this).attr('id').split('_');
        var fieldName = idInputArr[0];
        if (fieldName == '') {
            fieldName = 'This field';
        }

        if (customFieldId == 21) {
            rulesObj[this.name] = {required: true};
            messagesObj[this.name] = {required: fieldName + " is required"};
        }

        //Event specific validations start here
        var customvalidationFunction = $(this).attr('data-customvalidation');
        if (customvalidationFunction === "ageValidation") {
            rulesObj[this.name] = {required: true, min: 12, number: true};
            messagesObj[this.name] = {required: fieldName + " is required"};
        }
        if (customvalidationFunction === "age5Validation") {
            rulesObj[this.name] = {required: true, min: 5, number: true};
            messagesObj[this.name] = {required: fieldName + " is required"};
        }
        if (customvalidationFunction === "sprintDobValidation") {
            $(".form-datepicker").datepicker("destroy");
            $(".form-datepicker").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd/mm/yy',
                yearRange: '1950:2003',
                defaultDate: '01/01/2003'
            });
            $(".form-datepicker").attr('readOnly', 'true');
        }
        if (customvalidationFunction === "bibMaxLength") {
            rulesObj[this.name] = {required: true, maxlength: 20};
            messagesObj[this.name] = {required: fieldName + " is required"};
        }
        if (customvalidationFunction === "check_unique_mobile_emrg") {
            rulesObj[this.name] = {required: true, check_unique_mobile_emrg: true};
            messagesObj[this.name] = {required: fieldName + " is required",
                check_unique_mobile_emrg: "Mobile number and Emergency contact number should not be same"};
        }

        if (customvalidationFunction === "check_unique_mobile_alternate") {
            rulesObj[this.name] = {required: true, check_unique_mobile_alternate: true};
            messagesObj[this.name] = {required: fieldName + " is required",
                check_unique_mobile_alternate: "Mobile number and alternate contact number should not be same"};
        }
        if (customvalidationFunction === "chkPromoCode") {
            rulesObj[this.name].chkPromoCode = true;
            messagesObj[this.name].chkPromoCode = "Invalid Promo code";
        }

        if (customvalidationFunction === "pmiValidation") {
            rulesObj[this.name].pmiValidation = true;
            messagesObj[this.name].pmiValidation = "Invalid Membership code";
        }
		
		if (customvalidationFunction === "tedx2016Validation") {
            rulesObj[this.name].tedx2016Validation = true;
            //messagesObj[this.name].tedx2016Validation = "Please enter your registered email id to proceed further";
        }
		
		
        if (customvalidationFunction === "toastmasterraido") {
            rulesObj[this.name] = {required: true, };
            messagesObj[this.name] = "Please choose one option";

        }
        if (customvalidationFunction === "validate_promocode") {
            rulesObj[this.name].validate_promocode = true;
            messagesObj[this.name].validate_promocode = "Invalid promo code";
        }
        //Event specific validations ends here
    });
    //Toas master validaton for CORONATION 2016 event
    //toastmasterraido
    $("*[data-customvalidation='toastmasterraido']").click(function () {
        var this_val = $(this).val();

        if ($.trim(this_val) == "Toastmaster") {
            $(this).parents('.form-group').next().find('select[data-customvalidation="toasmasterlist"]').parent().show();
            $(this).parents('.form-group').next().find('select[data-customvalidation="toasmasterlist"]').addClass('required error');

        } else if ($.trim(this_val) == "Guest") {

            var selectBox = $(this).parents('.form-group').next().find('select[data-customvalidation="toasmasterlist"]');
            selectBox.parent().hide();

            selectBox.rules("remove", "required");
            selectBox.removeClass('error required');
            selectBox.val("");


        }


    });


    //checking the promocodes
    $.validator.addMethod("chkPromoCode", function (value, element) {

        var isSuccess = false;
        if (value.length > 6) {
            var method = "POST";
            var url = site_url + 'api/delegate_validations/checkPromoCode';
            var data = "checkPromoCode=1&pcode=" + value;
            var response = sendAjaxRequest(url, data, method);

            if (response.response == 'valid') {
                isSuccess = true;
            }
        }
        return isSuccess;
    });
    //checking the promocodes


    //To validate the unique mobile numbers in delegate page
    $.validator.addMethod("check_unique_mobile_emrg", function (value, element) {
        var original_value = $.trim(value);
        var this_id = $(element).attr('id');
        var success = true;
        var uniqueMobEmrg = new Array;
        $('.customValidationClass').each(function (index, item) {
            var customvalidationFunction = $(item).attr('data-customvalidation');
            if (($(item).attr('id') != this_id) && (customvalidationFunction == "check_unique_mobile_emrg")) {
                var itemId = $(item).attr('id');
                uniqueMobEmrg.push($("#" + itemId).val());
            }
        });

        for (var i in uniqueMobEmrg) {
            if (uniqueMobEmrg[i] == original_value) {
                success = false;
            }
        }
        uniqueMobEmrg.length = 0;
        return success;
    });

    //To validate the mobile number and alternative mobile number 
    //should not be same
    $.validator.addMethod("check_unique_mobile_alternate", function (value, element) {
        var original_value = $.trim(value);
        var success = true;
        var parnid = $(element).parents().eq(1).attr('class');
        var uniq_mob_emrg = $('.' + parnid).find('input[data-customvalidation="check_unique_mobile_emrg"]').val();

        if (uniq_mob_emrg == original_value) {
            success = false;
        }
        return success;
    });



    //Pmi validations starts here
    $.validator.addMethod("pmiValidation", function (value, element) {

        var isSuccess = false;
        var eventId = $("#eventId").val();
        var membershipId = value;
        var original_value = $.trim(value);
        var this_id = $(element).attr('id');

        //    checking the valid numeric value or not
        if (isNaN(membershipId)) {
            return isSuccess;
        }
        //to check valid booking id repeated or not
        var pmi_all_similar = false;

        $('.customValidationClass').each(function (index, item) {
            var customvalidationFunction = $(item).attr('data-customvalidation');
            if (($(item).attr('id') != this_id) && (customvalidationFunction == "pmiValidation")) {
                var this_val = $(this).val();
                if ($.trim(this_val) == original_value) {
                    pmi_all_similar = true;
                }
            }
        });

        if (pmi_all_similar) {
            return isSuccess;
        }

        //check the valid pmi id on server side
        if (membershipId.length > 5)
        {
            $.ajax({
                url: site_url + 'api/delegate_validations/checkPmiMemership',
                type: 'POST',
                data: "eventId=" + eventId + "&membershipId=" + membershipId,
                async: false,
                beforeSend: function () {
                    $("#dvLoading").show();
                },
                success: function (retData) {
                    $("#dvLoading").hide();
                    if (retData.response.total == 1) {
                        isSuccess = true;
                    }
                },
                error: function (result) {
                    $("#dvLoading").hide();
                }
            });
        }
        return isSuccess;
    }, "Please enter valid booking id");
	
	
	
	//tedX 2016 validations starts here
    $.validator.addMethod("tedx2016Validation", function (value, element) {
		$.validator.messages.tedx2016Validation = 'Please enter your registered email id to proceed further';
        var isSuccess = false;
        var eventid = $("#eventId").val();
        var email = $.trim(value);
        var this_id = $(element).attr('id');
		
        //checking the valid emailid or not
        if (validateEmail(email)) {
            $.ajax({
                url: site_url + 'api/delegate_validations/checkTedX2016Email',
                type: 'POST',
                data: "eventid=" + eventid + "&email=" + email,
                async: false,
                beforeSend: function () {
                    $("#dvLoading").show();
                },
                success: function (retData) {
                    $("#dvLoading").hide();
                    if (retData.status == 'error') {
                        isSuccess = false;
						$.validator.messages.tedx2016Validation = retData.response.message;
						//console.log($.validator.messages.tedx2016Validation);
                    }
					else if (retData.status == 'success') {
                        isSuccess = true;
						tedXErrMsg = '';
                    }
                },
                error: function (retData) {
					$.validator.messages.tedx2016Validation = retData.response.message;
                    $("#dvLoading").hide();
                }
            });
        
			
            return isSuccess;
        }
        
        return isSuccess;
    }, $.validator.messages.tedx2016Validation );  
	
	
	
	
    $.validator.addMethod("validate_promocode", function (value, element) {
        if(value!=''){
//            var pageUrl=api_eventPromoCodes, method="POST", input={'eventid':$('#eventId').val(),'promocode':value}, dataFormat='JSON',
//            callbackSuccess=function(result){
//                if(result.response.total>0){
//                    if(result.response.eventPromoCodesList[0].remainingquantity>=totalSaleTickets){
//                        return true;
//                    }else{
//                        return false;
//                    }
//                }else{
//                    return false;
//                }
//            }, callbackFailure=function(response){
//                return false;
//            };
//            getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
        var response = sendAjaxRequest(api_eventPromoCodes, {'eventid':$('#eventId').val(),'promocode':value}, 'POST');
        var isSuccess=false;
        if(response.response.total>0){
            if (response.response.eventPromoCodesList[0].remainingquantity>=totalSaleTickets) {
                isSuccess = true;
            }
        }
            return isSuccess;
        }else{
            return true;
        }
    });

    /* Adding the custom validations based on the Custom Field Id ends here */

    /* Common fields validations ends here */

    $("#ticket_registration").validate({
        ignore: [],
        rules: rulesObj,
        messages: messagesObj,
        errorPlacement: function (error, element) {
            var inputId = element.attr('id');
            var idInputArr = inputId.split('_');
            var localityArr = ['Country', 'State', 'City'];

            if ($.inArray(idInputArr[0], localityArr) !== -1 && $('.Locality_' + idInputArr[1] + 'Err')[0]) {
                $('.Locality_' + idInputArr[1] + 'Err').html('<label for="Locality_' + idInputArr[1] + '" generated="true" class="error">Please enter valid Location</label>');

            } else if (idInputArr[0] == 'Locality') {
                if ($('#' + inputId).val() == '') {
                    $('.Locality_' + idInputArr[1] + 'Err').empty();
                    error.appendTo($('.' + idInputArr[0] + '_' + idInputArr[1] + 'Err'));
                }

            } else if (idInputArr[0] == 'checkboxvalid') {
                error.appendTo('.checkboxvalid_Err');
            } else {
                error.appendTo($('.' + idInputArr[0] + '_' + idInputArr[1] + 'Err'));
            }

        }
    });

    /*===========================================================================================================================*/

    var inputCssArr = ["checkbox", "radio"];
    $(inputCssArr).each(function (index, inputType) {
        $('.paymentmode-section1 input[type="' + inputType + '"]').each(function () {
            $(this).wrap("<span class='custom-" + inputType + "'></span>");
            if ($(this).is(':checked')) {
                $(this).parent().addClass("selected");
            }
        });

        $('.paymentmode-section1 input[type="' + inputType + '"]').click(function () {
            if (inputType == 'radio') {
                $(".custom-" + inputType).removeClass("selected");
            }
            $(this).parent().toggleClass("selected");
        });
    });
});

/* Pre populate the form values from the selected attendee details starts here */
function autoFill(cnt) {
    var preFillId = $('#auto_fill_' + cnt).val();
    if (preFillId != '') {

        $('.registration_field_group_' + preFillId + ' input[type="text"], .registration_field_group_' + preFillId + ' textarea, .registration_field_group_' + preFillId + ' select').each(function () {
            var thisInputId = $(this).attr('id');
            var idInputArr = thisInputId.split('_');
            $('#' + idInputArr[0] + '_' + cnt).val($(this).val());
        });

        $('.registration_field_group_' + preFillId + ' input[type="checkbox"], .registration_field_group_' + preFillId + ' input[type="radio"]').each(function () {
            var thisInputId = $(this).attr('id');
            var idInputArr = thisInputId.split('_');
            $('#' + idInputArr[0] + '_' + cnt + '_' + idInputArr[2]).prop("checked", $(this).prop('checked'));
        });
    } else {
        $('.registration_field_group_' + cnt + ' input[type="text"], .registration_field_group_' + cnt + ' textarea, .registration_field_group_' + cnt + ' select').val('');
        $('.registration_field_group_' + cnt + ' input[type="checkbox"], .registration_field_group_' + cnt + ' input[type="radio"]').prop('checked', false);
    }
}
/* Pre populate the form values from the selected attendee details ends here */

/* common function to send Ajax requests*/
function sendAjaxRequest(url, params, method)
{
    var returnData = 0;
    $.ajax({
        type: method,
        url: url,
        async: false,
        data: params,
        success: function (response) {
            returnData = response;
        }
    });
    if (returnData != 0) {
        return returnData;
    }
}
/* common function to send Ajax requests*/


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
function initialize(index) {
    // Create the autocomplete object, restricting the search
    // to geographical location types.
    var map = new google.maps.Map(document.getElementById('Locality_' + index), {
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var input = (document.getElementById('Locality_' + index));
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var autocomplete = new google.maps.places.Autocomplete((input));
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var place = autocomplete.getPlace();
        fillInAddress(place, index);
    });

}

// [START region_fillform]
function fillInAddress(place, index) {

    for (var component in componentForm) {
        if ($.inArray(component, ignoreArray) > -1) {
            document.getElementById(component).value = '';
            document.getElementById(component).disabled = false;
        }
    }
    if (place.length == 0) {
        return;
    }
    var ignoreArray = ['route', 'sublocality_level_2', 'premise'];
    var adddressObj = {};
    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            adddressObj[addressType] = val;
        }
    }
    var state = '';
    var city = '';
    var country = adddressObj.country;

    if (adddressObj.administrative_area_level_1) {
        state = adddressObj.administrative_area_level_1;
    } else {
        state = country;
    }

    if (adddressObj.locality) {
        city = adddressObj.locality;
    } else if (state != '') {
        city = state;
    } else {
        city = country;
    }

    if (document.getElementById('City_' + index) != null) {
        document.getElementById('City_' + index).value = city;
    }
    if (document.getElementById('State_' + index) != null) {
        document.getElementById('State_' + index).value = state;
    }
    if (document.getElementById('Country_' + index) != null) {
        document.getElementById('Country_' + index).value = country;
    }
}
// [END region_fillform]



// Bookdate validation for Deltin goa
$(function () {
    $('.customValidationClass').each(function (index, item) {
        var customvalidationFunction = $(item).attr('data-customvalidation');
        if ((customvalidationFunction == "bookdatevalid")) {
            var tickid = parseInt($(this).attr('ticketid'));
            var bookdateid = $(this).attr('id');
            if ($.inArray(tickid, weekendTickets) != -1) {
                $(this).removeClass('hasDatepicker');
                $(this).datepicker({beforeShowDay:
                            function (dt)
                            {
                                return [dt.getDay() == 0 || dt.getDay() == 5 || dt.getDay() == 6, ""];
                            },
                    minDate: new Date()
                });

            } else if ($.inArray(tickid, weekdayTickets) != -1) {
                $(this).removeClass('hasDatepicker');
                $(this).datepicker({beforeShowDay:
                            function (dt)
                            {
                                return [dt.getDay() != 0 && dt.getDay() != 6 && dt.getDay() != 5, ""];
                            },
                    minDate: new Date()
                });

            } else {
                $(this).removeClass('hasDatepicker');
                $(this).datepicker({
                    minDate: new Date()
                });
            }
            }
    });

});


function validateEmail(email) {
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  	return filter.test(email)
}

