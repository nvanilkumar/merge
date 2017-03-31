$(document).ready(function () {
    var today = new Date();
    var todayDate = (today.getMonth() + 1) + '/' + (today.getDate()) + '/' + (today.getFullYear());
    todayDate = new Date(todayDate);
    var startDate = true;
    var startTime = true;
    var endDate = true;
    var endTime = true;
    var endStartTime = true;
    var startEndTime = true;
    var ticketsUpto = true;
    var ticketsRange = true;
    var valid = true;
    $('#discountStartDate').datepicker({
        minDate: "0",
        changeMonth: true,
        numberOfMonths: 1,
        dateFormat: "mm/dd/yy",
        onClose: function (selectedDate) {
            $('#discountStartDate').datepicker("setDate", selectedDate);
            $('#discountEndDate').datepicker("option", "minDate", selectedDate);
        }
    });
    $('#discountEndDate').datepicker({
        minDate: "0",
        changeMonth: true,
        numberOfMonths: 1,
        dateFormat: "mm/dd/yy",
        onClose: function (selectedDate) {
            $('#discountEndDate').datepicker("setDate", selectedDate);
        }
    });
    $("#discountStartTime").timepicker();
    $("#discountEndTime").timepicker();

    $("#discountEndTime").change(function () {
        validateTime();
    });

    $("#discountStartTime").change(function () {
        validateTime();
    });

    function validateTime() {
        var discountEndDate = new Date($('#discountEndDate').val());
        var discountStartDate = new Date($('#discountStartDate').val());
        var discountStartTime = $("#discountStartTime").val();
        var discountEndTime = $("#discountEndTime").val();
        if (discountEndDate.valueOf() == discountStartDate.valueOf()) {
            if(new Date("November 13, 1993 " + discountEndTime)>new Date("November 13, 1993 " + discountStartTime)){
                $("#dateTimeError").text(" ");
                endStartTime = true;
            } else {
                $("#dateTimeError").text("End time should be greater than start time");
                endStartTime = false;
                return false;
            }
        }
        //It is used when end time should be greater than current time
//            if(discountEndDate.valueOf()==todayDate.valueOf()){
//                if(Date.parse("1-1-2000 " + $("#discountEndTime").val()) > Date.parse("1-1-2000 " + finalTime())){
//                    $("#endTimeError").text(" ");
//                    endTime=true;                    
//                }else{
//                    $("#endTimeError").text("End time should be greater than current time");   
//                    endTime=false;return false;
//                }
//            }            
    }

    if ($('#hiddenEventEndDate').val() != 0) {//means only in adding the discount
        $('#discountStartDate').datepicker('setDate', new Date());
        $('#discountEndDate').val($('#hiddenEventEndDate').val());
        $('#discountStartTime').val(finalTime());
        $('#discountEndTime').val('06:00 PM');
    }

    if ($('#discountStartDate').change(function () {
        if ($('#hiddenEventEndDate').val() != 0) {
            var discountStartDate = new Date($('#discountStartDate').val());
            if (discountStartDate >= todayDate) {
                $("#dateTimeError").text(" ");
                startDate = true;
            } else {
                $("#dateTimeError").text("Start date should be greater than or equal to current date");
                startDate = false;
            }
        }
        if ($('#discountEndDate').val() == $('#discountStartDate').val()) {
            validateTime();
        }
    }))
        ;

    $('#discountEndDate').change(function () {
        var discountEndDate = new Date($('#discountEndDate').val());
        if (discountEndDate >= todayDate) {
            $("#dateTimeError").text(" ");
            endDate = true;
        } else {
            $("#dateTimeError").text("End date should be greater than or equal to current date");
            endDate = false;
        }
        if (discountEndDate < discountStartDate) {
            $("#dateTimeError").text("End date should be greater than equal to the start date");
            endDate = false;
        } else {
            $("#dateTimeError").text(" ");
            endDate = true;
        }
        if ($('#discountEndDate').val() == $('#discountStartDate').val()) {
            validateTime();
        }

    });

    $('#addDiscountForm').on('submit', function (e) {
        e.preventDefault();
        $('.child').each(function () {
            if (!$('[type="checkbox"]:checked', this).length) { // If tickets are there and no ticket is selected
                valid = false;
            }
            else {
                valid = true;
            }
        });
        if (valid) {
            $("#checkboxErrorDiv #ticketCheckboxError").text(" ");
        } else {
            $('#checkboxErrorDiv').css('display', 'block');
            $("#checkboxErrorDiv #ticketCheckboxError").text("Please select atleast one ticket");
            return false;
        }
    });
    $('#ticketsUpto,#ticketsFrom').change(function () {
        if ($('#ticketsUpto').val().length > 0) {
            if ($('#ticketsUpto').val() <= 0) {
                $("#ticketsUptoError").text("Tickets Upto value should be greater than zero");
                ticketsUpto = false;
            } else {
                $("#ticketsUptoError").text(" ");
                ticketsUpto = true;
            }

            var uptoQuantity = parseInt($('#ticketsUpto').val(), 10);
            var fromQuantity = parseInt($('#ticketsFrom').val(), 10);

            if (uptoQuantity <= fromQuantity && uptoQuantity > 0) {
                $("#ticketsRangeError").text("Tickets From quantity should be lesser than Tickets Up To quantity");
                ticketsRange = false;
            } else {
                $("#ticketsRangeError").text(" ");
                ticketsRange = true;
            }
        } else if (!$('#ticketsUpto').val().length) {
            $("#ticketsUptoError").text(" ");
            $("#ticketsRangeError").text(" ");
            ticketsUpto = true;
            ticketsRange = true;
        }
    });

    $.validator.addMethod('positiveNumber',
            function (value) {
                return Number(value) > 0;
            }, 'Please enter positive value');

    $.validator.addMethod("specialName", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9_\- ]+$/.test(value);
    }, "Letters, numbers, hyphens and underscores are allowed");
    $.validator.addMethod('valueCheck',function (value) {
        var success = true;
                if($('input[type="radio"]:checked').val() == "percentage" && Number(value) > 100){
                    success = false;
                }
                return success;
            });
    var codeCheck=true;
    $('#discountCode').keyup(function(){
                var discountCodeVal=$('#discountCode').val();
                if(discountCodeVal==0 && discountCodeVal!=''){
                    $('#codeError').text('Discount code should not be zero');
                    codeCheck=false;
                    return false;
                }
//                else if(discountCodeVal==''){
//                    $('#codeError').text('Please enter discount code');
//                    codeCheck=false;
//                    return false;
//                }
                else{
                    $('#codeError').text(' ');
                    codeCheck=true;                    
                }      
    });
    $('#addDiscountForm').validate({
        rules: {
            discountCode: {
                required : true,
                maxlength: 25
            },
            discountName: {
                required: true,
                maxlength: 25,
                specialName: true
            },
            discountValue: {
                required: true,
                number: true,
                maxlength: 6,
                positiveNumber: true,
                valueCheck : true
            },
            usageLimit: {
                required: true,
                number: true,
                maxlength: 4,
                positiveNumber: true
            },
            ticketsFrom: {
                required: true,
                number: true,
                positiveNumber: true
            },
            ticketsUpto: {
                number: true
            },
        },
        messages: {
            discountCode: {
                required: "Please enter discount code",
                maxlength: "Please enter not more than 25 characters"

            },
            discountName: {
                required: "Please enter discount name",
                maxlength: "Please enter not more than 25 characters"
            },
            discountValue: {
                required: "Please select discount value",
                number: "Discount amount value should be a number",
                positiveNumber: "Discount amount value should be greater than zero",
                maxlength: "Please enter not more than 6 characters",
                valueCheck: "Discount should not be greater than 100%"
            },
            usageLimit: {
                required: "Please enter usage limit",
                number: "Usage limit should be a number",
                maxlength: "Please enter not more than 4 characters",
                positiveNumber: "Usage limit should be greater than zero",
            },
            ticketsFrom: {
                required: "Please enter tickets from value",
                number: "Tickets from value should be a number",
                positiveNumber: "Tickets From value should be greater than zero",
            },
            ticketsUpto: {
                number: "Tickets upto value should be a number"
            },
        },
        submitHandler: function (form) {
            $('#addDiscountError').text("");
            if ($(form).valid() && valid && startDate && endDate && ticketsUpto && ticketsRange && endTime && endStartTime && startTime && startEndTime &&codeCheck) {
                form.submit();
            }

        }

    });

    //Has to be placed after (intailization time of add discount,ie.current time) bcz this will be overriden by now.
    if ($('#hiddenStartDate').val() != 0) {
        $('#discountStartDate').val($('#hiddenStartDate').val());
    }
    if ($('#hiddenStartTime').val() != 0) {
        $('#discountStartTime').val($('#hiddenStartTime').val());
    }
    if ($('#hiddenEndDate').val() != 0) {
        $('#discountEndDate').val($('#hiddenEndDate').val());
    }
    if ($('#hiddenEndTime').val() != 0) {
        $('#discountEndTime').val($('#hiddenEndTime').val());
    }

});

function finalTime() {
    var d = new Date();
    var hh = d.getHours();
    var m = d.getMinutes();
    var dd = "AM";
    var h = hh;
    if (h >= 12) {
        h = hh - 12;
        dd = "PM";
    }
    if (h == 0) {
        h = 12;
    }
    m = m < 10 ? "0" + m : m;
    h = h < 10 ? "0" + h : h;
    var finalTime = h + ":" + m + " " + dd;
    return finalTime;
}

