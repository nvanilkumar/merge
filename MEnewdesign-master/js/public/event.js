
$('div.locSearchContainer').hide();

$('.event_tnc').click(function (e) {
    e.preventDefault();
    $('#event_tnc').modal();
});

resetValues();
function resetValues() {
    HideDiscountCodePopup();
    $('#promo_code').val('');
    $("#accrdn_1 .selectpicker,.selectNo").find('option:first-child').prop('selected', true);
}
function addFixedHeader() {
    if ($(window).width() <= 768) {
        return false;
    }
    var top = $(document).scrollTop();

    function addClass() {
        $('#event_div').addClass('fixto-fixed');
        $('.event_toggle').css('top', '200px');
    }
    function removeClass() {
        $('#event_div').removeClass('fixto-fixed');
        $('.event_toggle').css('top', '0');
    }
    top = 390 ? addClass() : removeClass();

}
// $(window).on('scroll', addFixedHeader);

if (window.matchMedia('(min-width: 1060px)').matches) {
    //$('#event_div').fixTo('body', {top: 80, zIndex: 99});
    //$('#event_div').fixTo('destroy');  
    $('#event_div').stick_in_parent({offset_top: 80});
}
$("#datepick").datepicker();

$('html, body').css({
    'overflow': 'auto',
    'height': 'auto'
});
$("#earlyEntry .btn").click(function () {

    var time = 500;
    if ($('.viewEarlyEntryTicket').is(":visible")) {
        $('.viewEarlyEntryTicket').slideUp(time);
        $(this).text('View More');
    } else {
        $('.viewEarlyEntryTicket').slideDown(time);
        $(this).text('View Less');
    }
    return false;
});
$("#vipTicket .btn").click(function () {
    var time = 500;
    if ($('.viewVipTicket').is(":visible")) {
        $('.viewVipTicket').slideUp(time);
        $(this).text('View More');
    } else {
        $('.viewVipTicket').slideDown(time);
        $(this).text('View Less');
    }
    return false;
});
function calculate() {
    var limitSingleTicketType = $('#limitSingleTicketType').val();

    var ticketObj = {};
    var donationObj = {};
    var eventId = $('#eventId').val();
    var referralCode = $('#referralCode').val();
    var discountCode = $('#promo_code').val();
    var totalSelectedQty = 0;
    if (limitSingleTicketType != 1) {
        var ticketCurrency = '';
        $('.ticket_selector').each(function () {
            if (ticketCurrency != '') {
                return false;
            } else if (((this.type === 'text' && $(this).val() != '') || parseInt($(this).val()))) {
                ticketCurrency = $(this).attr('currencyCode');
            }

        });
        $('.ticket_selector').each(function () {
            if ($(this).attr('currencyCode') != '' && ticketCurrency != '' && $(this).attr('currencyCode') != ticketCurrency) {
                $(this).prop('disabled', true);
            } else {
                $(this).prop('disabled', false);
            }
        });
    }

    $('.ticket_selector').each(function () {
        var ticketIdStr = $(this).attr('id');
        if (this.type === 'text') {
            var amount = ($(this).val() != '') ? $(this).val() : 0;
            if (parseInt(amount) > 0) {
                totalSelectedQty += 1;
                donationObj[ticketIdStr] = amount;
            }
        } else {
            var qty = $(this).val();
            ticketObj[ticketIdStr] = qty;
            totalSelectedQty += parseInt(qty);
        }
    });

    if (totalSelectedQty === 0) {
        alert("Please select ticket quantity");
        $('#promo_code').val('');
        $('#apply_discount_btn').show();
        $('#calucationsDiv').hide();
        return false;
    }
    var inputData = {eventId: eventId};
    if (Object.keys(ticketObj).length) {
        inputData.ticketArray = ticketObj;
    }
    if (Object.keys(donationObj).length) {
        inputData.donateTicketArray = donationObj;
    }
    if ((referralCode) !== '') {
        inputData.referralCode = referralCode;
    }
    if ((discountCode) !== '') {
        inputData.discountCode = discountCode;
    }

    var pageUrl = api_getTicketCalculation;
    var method = 'POST';
    //var call = 'reports';
    var dataFormat = 'JSON';
    function callbackSuccess(calculationObj) {
        var data = calculationObj.response.calculationDetails;
        var otherCurrencyTickets = data.otherCurrencyTickets;

        $(otherCurrencyTickets).each(function (index, value) {
            $('#' + value).attr('disabled', 'disabled');
        });

        var currencyCodeStr = data.currencyCode;
        if (data.totalPurchaseAmount > 0) {
            $('.currencyCodeStr').html('(' + currencyCodeStr + ')');
        } else {
            $('.currencyCodeStr').html('');
        }

        $('#discountTbl').hide();
        $('#bulkDiscountTbl').hide();
        $('#referralDiscountTbl').hide();
        $('#extraChargeTbl').hide();
        if (data.totalCodeDiscount == 0 && discountCode != '') {
            alert("Discount code you entered is not applicable to this Ticket Or Exceeded Limit");
            $('#promo_code').removeAttr('readonly');
            $('#promo_code').focus();
            $('#calucationsDiv').hide();
            $('#apply_discount_btn').show();
            //return false;
        }
        if (data.totalCodeDiscount > 0) {
            $('#discount_amount').html(data.totalCodeDiscount);
            $('#discountTbl').show();
        } else {
            $('#promo_code').val('');
            $('#promo_code').focus();
            $('#apply_discount_btn').show();
        }
        if (data.totalBulkDiscount > 0) {
            $('#bulkDiscount_amount').html(data.totalBulkDiscount);
            $('#bulkDiscountTbl').show();
        }
        if (data.totalReferralDiscount > 0) {
            $('#referralDiscount_amount').html(data.totalReferralDiscount);
            $('#referralDiscountTbl').show();
        }
        var tax = '';
        $.each(data.totalTaxDetails, function (key, value) {
            tax += '<table width="100%" class="table_cont table_cont_1">' +
                    '<tbody>' +
                    '<tr>' +
                    '<td class="table_left_cont">';
            if (data.totalPurchaseAmount > 0) {
                tax += value.label + '(' + currencyCodeStr + ')</td>';
            } else {
                tax += value.label + '</td>';
            }

            tax += '<td class="table_ryt_cont">' + value.taxAmount + '</td>' +
                    '</tr>' +
                    '</tbody>' +
                    '</table>';
        });
        $('#taxesDiv').html(tax);
        
        var extraTxt = '';
        if (data.extraCharge.length > 0) {
            $.each(data.extraCharge, function(extraKey, extraVal){
                
                if (extraVal.totalAmount > 0) {
                    extraTxt += '<tbody>'+
                            '<tr>'+
                                '<td id="extrachargeLabel" class="table_left_cont">'+extraVal.label+'('+extraVal.currencyCode+')</td>'+
                                '<td class="table_ryt_cont" id="extracharge_amount">'+extraVal.totalAmount+'</td>'+
                            '</tr>'+
                        '</tbody>';    
                }
            });
            $('#extraChargeTbl').html(extraTxt);
            $('#extraChargeTbl').show();
        }
        
        /*if (data.extraCharge.totalAmount > 0) {
            $('#extrachargeLabel').html(data.extraCharge.label + '(' + currencyCodeStr + ')')
            $('#extracharge_amount').html(data.extraCharge.totalAmount);
            $('#extraChargeTbl').show();
        }*/
        $('#roundOfValue').html(data.roundofvalue);
        $('#total_amount').html(data.totalTicketAmount);
        $('#purchase_total').html(data.totalPurchaseAmount);
        $('#calucationsDiv').show();
        $('html, body').stop().animate({'scrollTop': $("div.book").offset().top - 200}, 400, 'swing');
        $("#dvLoading").hide();
    }
    function callbackFailure(result) {
        var data1 = result.responseJSON.response;
        var otherCurrencyTickets = data1.calculationDetails.otherCurrencyTickets;

        $(otherCurrencyTickets).each(function (index, value) {
            $('#' + value).attr('disabled', 'disabled');
        });
        alert(data1.messages);
        $("#dvLoading").hide();
        if (typeof data1.calculationDetails != 'undefined') {
            var data = data1.calculationDetails;
            var currencyCodeStr = data.currencyCode;
            if (currencyCodeStr != '') {
                $('.currencyCodeStr').html('(' + currencyCodeStr + ')');
            } else {
                $('.currencyCodeStr').html('');
            }

            $('#discountTbl').hide();
            $('#bulkDiscountTbl').hide();
            $('#referralDiscountTbl').hide();
            if (data.totalCodeDiscount > 0) {
                $('#discount_amount').html(data.totalCodeDiscount);
                $('#discountTbl').show();
            } else {
                $('#promo_code').val('');
                $('#apply_discount_btn').show();
                $('#promo_code').focus();
            }
            if (data.totalBulkDiscount > 0) {
                $('#bulkDiscount_amount').html(data.totalBulkDiscount);
                $('#bulkDiscountTbl').show();
            }
            if (data.totalReferralDiscount > 0) {
                $('#referralDiscount_amount').html(data.totalReferralDiscount);
                $('#referralDiscountTbl').show();
            }
            var tax = '';
            $.each(data.totalTaxDetails, function (key, value) {
                tax += '<table width="100%" class="table_cont table_cont_1">' +
                        '<tbody>' +
                        '<tr>' +
                        '<td class="table_left_cont">';
                if (value.taxAmount > 0) {
                    tax += value.label + '(' + currencyCodeStr + ')</td>';
                } else {
                    tax += value.label + '</td>';
                }

                tax += '<td class="table_ryt_cont">' + value.taxAmount + '</td>' +
                        '</tr>' +
                        '</tbody>' +
                        '</table>';
            });
            $('#taxesDiv').html(tax);
            if (data.extraCharge.totalAmount > 0) {
                $('#extrachargeLabel').html(data.extraCharge.label)
                $('#extracharge_amount').html(data.extraCharge.totalAmount);
                $('#extraChargeTbl').show();
            }
            $('#roundOfValue').html(data.roundofvalue);
            $('#total_amount').html(data.totalTicketAmount);
            $('#purchase_total').html(data.totalPurchaseAmount);
        } else {
            $("#dvLoading").show();
            $('#calucationsDiv').hide();
            location.reload(true);
        }
        $('#promo_code').removeAttr('readonly');
    }
    getPageResponse(pageUrl, method, inputData, dataFormat, callbackSuccess, callbackFailure);
}
$(".ticket_selector").on('change', function ()
{

    $("#dvLoading").show();
    if (this.type == "text" && isNaN(this.value)) {
        alert("Please enter valid amount");
        $('#' + this.id).val('');
        $('#calucationsDiv').hide();
        $("#dvLoading").hide();
        $('.ticket_selector').prop('disabled', false);
        return false;
    } else if (this.type == "text" && parseInt(this.value) <= 0) {
        alert("Please enter amount greater than zero");
        $('#' + this.id).val('');
        $('#calucationsDiv').hide();
        $("#dvLoading").hide();
        $('.ticket_selector').prop('disabled', false);
        return false;
    } else {
        var limitSingleTicketType = $('#limitSingleTicketType').val();

        if (limitSingleTicketType > 0) {

            if ($(this).val() == '0' || $(this).val() == '') {
                $('.ticket_selector').prop('disabled', false);
            } else {
                $('.ticket_selector').prop('disabled', 'disabled');
                //$("input.ticket_selector").val('0');
                $(this).prop('disabled', false);
            }
        } else {
            $('.ticket_selector').prop('disabled', false);
        }
        var totalSelectedQty = 0
        $('.ticket_selector').each(function () {
            // $(this).removeAttr('disabled');
            if (this.type === 'text') {
                var amount = ($(this).val() != '') ? $(this).val() : 0;
                if (parseInt(amount) > 0) {
                    totalSelectedQty += 1;
                }
            } else {
                var qty = $(this).val();
                totalSelectedQty += parseInt(qty);
            }
        });

        if (totalSelectedQty == 0) {
            $('#calucationsDiv').hide();
            HideDiscountCodePopup();
            $('#apply_discount_btn').show();
            $('#total_amount, #purchase_total, .table_ryt_cont').text(0);
            $('#promo_code').val('');
            $('#promo_code').removeAttr('readonly');
            $("#dvLoading").hide();
        } else {
            calculate(this);
        }
    }
});


$("#datepicker").datepicker();
$("#datepicker2").datepicker();
$("#viewMoreEvents").click(function () {
    $(".eventThumbs:first").clone().insertAfter(".eventThumbs:last").hide();
    $(".eventThumbs:last").fadeIn(500);
    return false;
});
$("#viewMoreCat").click(function () {
    if ($(this).attr("aria-expanded") === "false")
        $(this).text("View Less");
    else
        $(this).text("View More");
});

$("#settingsBtn").click(function () {
    if ($("#locationContainer").is(':hidden')) {
        $("#locationContainer").show();
        $("#locationContainer").css('position', 'fixed');
        $("#locationContainer").css('background', '#f5f6f7');
        $(window).off('scroll', onwindowscroll)

    } else {
        $("#locationContainer").hide();
        $("#locationContainer").css('position', 'relative');
        $(window).on('scroll', onwindowscroll)
    }

});

function onwindowscroll() {
    var top = $(document).scrollTop();
    if (top >= 180) {
        $("#locationContainer").hide();
        $("#settingsBtn").show();
    } else {
        $("#settingsBtn").hide();
        $("#locationContainer").show();
    }
}
$(window).on('scroll', onwindowscroll);
$("#scrollnavtoggle").on("click", function () {
    $(this).toggleClass("active");
});

$(window).on('scroll', onwindowscroll);

$(".showMoreTickets").click(function () {
    var idArr = this.id.split('_');
    var tktId = idArr[1];
    if (!($("#show_" + tktId).is(":visible"))) {
        $(this).text('View Less');
        $("#show_" + tktId).show().slideDown();
    } else {
        $("#show_" + tktId).hide();
        $(this).text("View More");

    }
    return false;

});

$("#event_about .btn").click(function () {
    $('#viewMoreDetailss1').toggleClass("aboutdivmore");
    if ($(this).text() == 'View More')
    {
        $(this).text('View Less');
        $('#viewMoreDetailss1').slideDown(time);

    } else if ($(this).text() == 'View Less')
    {
        $(this).text('View More');
        $('#viewMoreDetailss1').slideUp(time);
    }
});
function ShowDiscountCodePopup() {

    var myElem1 = document.getElementById('showdis');
    if (myElem1 === null) {

    } else {
        myElem1.style.display = "none";
    }

    var myElem2 = document.getElementById('hidedis');
    if (myElem2 === null) {

    } else {
        myElem2.style.display = "";
        $('#promo_code').focus();
    }
}
function HideDiscountCodePopup() {

    var myElem1 = document.getElementById('showdis');
    if (myElem1 === null) {

    } else {
        myElem1.style.display = "";
    }

    var myElem2 = document.getElementById('hidedis');
    if (myElem2 === null) {

    } else {
        myElem2.style.display = "none";
    }
}
function clearDiscount()
{
    $('#promo_code').val('');
    $('#promo_code').focus();
    $('#promo_code').removeAttr('readonly');
    $('#apply_discount_btn').show();
    $("#dvLoading").show();
    calculate();
}
function applyDiscount() {

    $('#promo_code').removeAttr('readonly');
    if ($.trim($('#promo_code').val()) == '') {
        alert("Please enter valid discount code");
        $('#promo_code').focus();
    } else {
        $('#apply_discount_btn').hide();
        $('#promo_code').attr('readonly', true);
        $("#dvLoading").show();
        calculate();
    }
}
//});

function showhide()
{
    var divSignUp = document.getElementById("signUpContainer");
    var divLogin = document.getElementById("loginContainer");
    if (divSignUp.style.display == "none") {
        divSignUp.style.display = "block";
        divLogin.style.display = "none";
    }
    else {
        divSignUp.style.display = "none";
        divLogin.style.display = "block";
    }
}


// adding to order log 

function booknow()
{
    $("#dvLoading").show();
    var ticketObj = {};
    var donationObj = {};
    var eventId = $('#eventId').val();
    var totalamount = $('#total_amount').html();
    var discountCode = $('#promo_code').val();
    var referralCode = $('#referralCode').val();
    var pageType = $('#pageType').val();
    var addonCount = 0, nonaddonCount = 0;
    var addonArray = [];
    $('.ticket_selector').each(function () {
        var ticketIdStr = $(this).attr('id');
        if (this.type === 'text') {
            var amount = ($(this).val() != '') ? $(this).val() : 0;
            donationObj[ticketIdStr] = amount;
        } else {
            var qty = $(this).val();
            ticketObj[ticketIdStr] = qty;
        }
        if ($(this).hasClass("addon")) {
            addonCount += isNaN($(this).val()) ? 0 : eval($(this).val());
            addonArray.push(ticketIdStr);
        } else {
            nonaddonCount += (isNaN($(this).val()) || $(this).val() == 'undefined' || $(this).val() == '') ? 0 : eval($(this).val());
        }
    });

    if (addonCount > 0 && nonaddonCount == 0)
    {
        $("#dvLoading").hide();
        alert("Only Add-on tickets are not allowed, Please select atleast one regular ticket");
        return false;
    }
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    var widgetRedirectUrl = '';
    var ucode = '';
    var acode = '';
    for (var i = 0; i < sURLVariables.length; i++) {

        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == "widgetRedirectUrl") {
            widgetRedirectUrl = sParameterName[1];
        }
        if (sParameterName[0] == "ucode") {
            ucode = sParameterName[1];
        }
        if (sParameterName[0] == "acode") {
            acode = sParameterName[1];
    }
    }
	
	
	var meprcode = '';
	var url = (window.location != window.parent.location)
            ? document.referrer
            : document.location;
 	meprcode = getParamValueFromUrl('meprcode',url);
	
	if(meprcode == null || meprcode == ''){}
	else{	ucode = meprcode; 	}
	
	
    if (ucode == '') {
        ucode = $('input[name="promoterCode"]').val();
    }

    var inputData = {'ticketArray': ticketObj, 'eventId': eventId, discountCode: discountCode, 'donateTicketArray': donationObj,
        widgetRedirectUrl: widgetRedirectUrl, referralCode: referralCode, ucode: ucode,acode: acode, pageType: pageType};
    if (addonArray.length > 0) {
        inputData.addonArray = addonArray;
    }
    // Calling the Ajax to calculate the total amount of the selcted tickets and quantity
    $.ajax({
        type: "post",
        url: api_bookNow,
        datatype: 'json',
        headers: {'Content-Type': 'application/x-www-form-urlencoded',
            'Authorization': 'bearer ' + client_ajax_call_api_key
        },
        data: inputData,
        success: function (result) {
            var isInIframe = (window.location != window.parent.location) ? true : false;
            if (isInIframe) {
                window.top.location.href = site_url + 'payment?orderid=' + result.response.orderId;
            } else {
                window.location.href = site_url + 'payment?orderid=' + result.response.orderId;
            }
        },
        done: function () {
            setTimeout(function () {
                $("#dvLoading").hide();
            }, 2000);
        },
        error: function (result) {
            alert(result.responseJSON.response.messages[0]);
            $('#promo_code').removeAttr('readonly');
            $("#dvLoading").hide();
        }
    });
}



function getParamValueFromUrl( name, url ) {
  if (!url) url = location.href;
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( url );
  return results == null ? null : results[1];
}

$(document).ready(function(){
   document.getElementById("copyGlobalCodeButton").addEventListener("click", function() {
    copyToClipboard(document.getElementById("affiliate_link"));
});

function copyToClipboard(elem) {
	  // create hidden text element, if it doesn't already exist
    var targetId = "_hiddenCopyText_";
    var isInput =  elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        // can just use the original source element for the selection and copy
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
        // must use a temporary form element for the selection and copy
        target = document.getElementById(targetId);
        if (!target) {
            var target = document.createElement("textarea");
            target.style.position = "absolute";
            target.style.left = "-9999px";
            target.style.top = "0";
            target.id = targetId;
            document.body.appendChild(target);
        }
        target.textContent = elem.textContent;
    }
    // select the content
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);
    
    // copy the selection
    var succeed;
    try {
    	  succeed = document.execCommand("copy");
    } catch(e) {
        succeed = false;
    }
    // restore original focus
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }
    
    if (isInput) {
        // restore prior selection
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        // clear temporary content
        target.textContent = "";
    }
    return succeed;
} 
});