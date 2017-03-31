var valid = true;
$('#offlinePromoter').on('submit', function (e) {
    e.preventDefault();
    $('.child').each(function () {
        if (!$('[type="checkbox"]:checked', this).length) { // no box checked
            valid = false;
        } else {
            valid = true;
        }
    });

    if (valid) {
        $("#checkboxErrorDiv #ticketCheckboxError").text(" ");
    } else {
        $("#checkboxErrorDiv #ticketCheckboxError").text("Please select atleast one ticket");
    }
});

//offline promoter  Details  related js    
$('#offlinePromoter').validate({
    rules: {
        promoterName: {
            required: true
        },
        promoterEmail: {
            email: 100,
            required: true
        },
        promoterMobile: {
            required: true,
            number: true,
            minlength: 10,
            maxLen: 10
        },
        ticketslimit:{
            number: true
        }
    },
    messages: {
        promoterName: {
            required: "Please enter  Name"
        },
        promoterEmail: {
            required: "Please enter valid Email."
        }, 
        promoterMobile: {
            required: "Please enter Mobile Number."
        },
        ticketslimit: {
            required: "Please enter Number."
        }
        
    },
    submitHandler: function (form) {
        if ($('#offlinePromoter').valid() && valid) {
            form.submit();
        }

    }
});
     jQuery.validator.addMethod("maxLen", function (value, element, param) {
        if ($(element).val().length > param) {
            return false;
        } else {
         return true;
        }
    }, "Mobile number cannot be more than 10 characters");
// get tickets based on eventid
$('#event').change(function () {
      $("#dvLoading").show();
    var eventId = $(this).val();
    var promoterId = $('option:selected', this).attr('promoterId');
    var url = api_promoteofflineTickets;
    var request = $.ajax({
        url: url,
        headers: {
            'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'
        },
        type: "GET",
        data: {
            id: promoterId,
            eventId:eventId
        },
        dataType: "json",
        cache: false
    });
    request.done(function (result) {
         $("#dvLoading").hide();
        var ticket = result.response.eventTickets;
        $('#ticket').empty();
        if (ticket.length > 0) {          
            $('#ticket')
            .append($("<option></option>")
                .attr("value", "")
                .text("Select Ticket"));
            ticket.forEach(function (eachticket) {
                $('#ticket')
                .append($("<option></option>")
                    .attr("value", eachticket.ticketid)
                    //.attr("eventid", eachticket.eventid)
                    .text(eachticket.ticketname) );
            });
        }
    }); 
    request.fail(function(result){
         $("#dvLoading").hide();
       // alert('There Is No Tickets For This Event');
        $('html, body').animate({ scrollTop: 0 }, 0);
        $("#offlineFlashError").text('There Is No Tickets For This Event');
        $("#offlineFlashError").css('display','block').delay(5000).fadeOut('slow');
        $('#ticket').empty();
        $('#ticket')
        .append($("<option></option>")
            .attr("value", "")
            .text("Select Ticket"));
        $('#quantity').empty();
        $("#total").html('');
        $('#quantity')
        .append($("<option></option>")
            .attr("value", "")
            .text("Select Quantity"));
              
    });
});
// get ticket quantity based on tickets
$('#ticket').change(function () {
     $("#dvLoading").show();
    var promoterId = $('#event option:selected').attr('promoterId');
    var eventId = $("#event").val()
    var ticketId = $(this).val();
    var url = api_promoteticketsData;
    var request = $.ajax({
        url: url,
        headers: {
            'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'
        },
        type: "GET",
        data: {
            id: promoterId,
            ticketId: ticketId,
            eventId:eventId
        },
        dataType: "json",
        cache: false
    });
    request.done(function (result) {
         $("#dvLoading").hide();
        var MinQunatity = result.response.ticketName['0']['minorderquantity'];
        var MaxQunatity = result.response.ticketName['0']['maxorderquantity']; 
        var Qty=result.response.ticketName['0']['finalQty'];
        var price = result.response.ticketName['0']['price'];
        var q;
        $('#quantity').empty();
        $("#total").html('');
        $('#quantity')
        .append($("<option></option>")
            .attr("value", "")
            .text("Select Quantity"));
        for(q=MinQunatity;q<=Qty;q++)
        {
            $('#quantity')
            .append($("<option></option>")
                .attr("price", price)
                .attr("value", q)
                .text(q) );
        }
        if(Qty == '0'){
            $('html, body').animate({ scrollTop: 0 }, 0);
            $("#offlineFlashError").text('There Is No Tickets For This Event');
            $("#offlineFlashError").css('display','block').delay(5000).fadeOut('slow');
        }
     
    });  
    
});

//offline booking validations
$('#offlinebooking').validate({
    rules: {
        name: {
            required: true
        },
        email: {
            required: true,
            email: true
        },
        mobile: {
            required: true,
            number: true,
            minlength: 10,
            maxLen: 10
        },
        eventId: {
            required: true
        },
        ticketId: {
            required: true
        },
        quantity: {
            required: true     
        }

    },
    messages: {
        name: {
            required: "Please enter  name"
        },
        email: {
            required: "Please enter  email",
            email: "Please enter valid email"
        },
        mobile: {
            required: "Please enter mobile number",
            maxlength: "Please enter valid mobile number" 
        },
        eventId: {
            required: "Please select event"
        },
        ticketId: {
            required: "Please select ticket"
        },
        quantity: {
            required: "Please select quantity"
        }
    },
    submitHandler: function (form) {
        offlineBooking();
         
    }

});

function offlineBooking(){
   // e.preventDefault();
     $("#dvLoading").show();
    var eventId = $('#event').val();
    var ticketId = $('#ticket').val();
    var promoterId = $('#event option:selected').attr('promoterId');
    var quantity = $('#quantity').val();
    var finalAmount = $('#total').val();
    var price=$("#quantity option:selected").attr("price");
    var name = $('#name').val();
    var email = $('#email').val();
    var mobile = $('#mobile').val();
    var discountCode = $('#discountCode').val();
    var total=(quantity * price);
    $.ajax({
        url   : api_bookingOfflineBooking,
        type  : 'POST',
        data: {
            eventId: eventId,
            price:price,
            ticketId:ticketId,
            totalamount:total,
            email:email,
            mobile:mobile,
            name:name,
            quantity:quantity,
            discountCode:discountCode,
            promoterId:promoterId
        },
        headers: {
            'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'
        },
        cache: false,
        dataType: 'json',
                             
        success : function (retData) {
           $("#dvLoading").hide();
            $('html, body').animate({ scrollTop: 0 }, 0);
           // $("#offlineFlashSuccess").text(retData.responseJSON.response.message[0]);
           $("#offlineFlashSuccess").text(retData.response.messages[0]);
            $("#offlineFlashSuccess").css('display','block').delay(5000).fadeOut('slow');
            $(".totalAmount").html('');
             document.getElementById("offlinebooking").reset();
//            var form = $('#offlinebooking').get(0);
//            $.removeData(form,'validator');
                                       
        },
        error: function (retData) {
           $("#dvLoading").hide();
            $('html, body').animate({ scrollTop: 0 }, 0);
           // $("#offlineFlashError").text(retData.responseJSON.response.message[0]);
           //$("#offlineFlashSuccess").text(retData.response.message[0]);
            $("#offlineFlashError").text(retData.responseJSON.response.messages[0]);
            $("#offlineFlashError").css('display','block').delay(5000).fadeOut('slow');
            $(".totalAmount").html('');
            document.getElementById("offlinebooking").reset();
              var form = $('#offlinebooking').get(0);
            $.removeData(form,'validator');
        }
    });        
}


$('#quantity').change(function () {
    offlineCalculate();
});
function offlineCalculate() {
  
    var ticketObj = {};
    var eventId = $('#event').val();
    var ticketId=$('#ticket').val();
    var quantity= $('#quantity').val();
    var ticketIdStr =$('#ticket').val();
    var discountCode = $('#discountCode').val();
    var promoterId = $('#event option:selected').attr('promoterId');
    ticketObj[ticketIdStr] = quantity;

    var inputData = {
        eventId: eventId
    };

    inputData.ticketArray = ticketObj;

    if ((discountCode) !== '') {
        inputData.discountCode = discountCode;
    }
    if ((promoterId) !== '') {
        inputData.promoterId = promoterId;
    }

    var pageUrl = api_getTicketCalculation;
    var method = 'POST';

    var dataFormat = 'JSON';
  if(quantity == ''){
     alert('Please choose ticket quantity');
     return false;
 }
    function callbackSuccess(calculationObj) {

        var data = calculationObj.response.calculationDetails;
        var totalAmount=data.totalPurchaseAmount;
        //remove the extracharge if the event contains any extra charge
        if(data.extraCharge.hasOwnProperty('totalAmount') ){
            var extraCharge=parseInt(data.extraCharge.totalAmount);

            if(extraCharge > 0 ){
                totalAmount =totalAmount-extraCharge;
            }

        }
        
        $('#total').html(totalAmount).append(".00 ").append(data.currencyCode);
        $('#total').show();
        
    }
    function callbackFailure(result) {
        callbackSuccess(result.responseJSON);
        $('#discountCode').val("");
        alert(result.responseJSON.response.messages);
    }
    getPageResponse(pageUrl, method, inputData, dataFormat, callbackSuccess, callbackFailure);
}
$('#cancel').click(function () {
    $("#discountCode").val(""); 
      offlineCalculate();
});
// guest list booking form validations
$('#guestlistbooking').validate({
    rules: {
        ticketId: {
            required: true
        },
        csvfile: {
            required: true     
        }
    },
    messages: {
        ticketId: {
            required: "Please Select TIcket"
        },
        csvfile: {
            required: "Please upload csv file"
        }
    },
    submitHandler: function (form) {
        if ($('#guestlistbooking').valid()) {
            form.submit();
        }
        return false;
    }
});

$(function(){
    
    $(".showOrHideDiscCodes").click(function () {
        var tktId = $(this).val();
        $("#dicountBox" + tktId).hide();

        if ($(this).prop('checked') == true) {
            $("#dicountBox" + tktId).show();
        } else {
            $("input[name='ticketDiscount" + tktId + "[]']").each(function ()
            {
                $(this).prop('checked', false);
            });
        }
    });
    
    //apply checkbox class to discount checkboxes
    customCheckbox("ticketDiscount");
    
    
    
});

function customCheckbox(checkboxName) {
    //Finds all inputs with an attribute name that starts with 'checkboxName' 
    var checkBox = $('input[name^="' + checkboxName + '"]');
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