
function ToggleCalendarDiv(id)
{
    if (id != null)
    {
        if (document.getElementById(id).style.display == "none")
        {
            document.getElementById(id).style.display = "block";
        }
        else
        {
            document.getElementById(id).style.display = "none";
        }
    }
}

var eventId = $('#eventId').val();
//function CngClass1(obj, qty1, seatId, eventsignupId) {
//
//
//    if (obj.className == 's bl')
//    {
//        if (sqty1 <= qty1)
//        {
//            sqty1 = sqty1 + 1;
//            obj.className = 's bl process';
//            var pageUrl = api_updateSeats, method = 'POST', input = {venueseatid: seatId, type: 'add', eventsignupid: eventsignupId, eventid: eventId}, dataFormat = 'JSON',
//                    callbackSuccess = function (result) {
//                        if (result.response.total > 0) {
//                            obj.className = 'r gr1';
//
//                        } else {
//                            alert("Oops Sorry, You just missed it, check back again for more seats!!!");
//                            obj.className = 'bl x';
//                            sqty1 = sqty1 - 1;
//                        }
//                    }, callbackFailure = function (result) {
//                alert(result.responseJSON.response.messages[0]);
//            };
//            getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
//
//        } else {
//            alert("Max Qty Reached!!!!");
//        }
//    } else if (obj.className == 'r gr1') {
//        sqty1 = sqty1 - 1;
//        obj.className == 'r gr1 process';
//        var pageUrl = api_updateSeats, method = 'POST', input = {venueseatid: seatId, type: 'remove', eventsignupid: eventsignupId, eventid: eventId}, dataFormat = 'JSON',
//                callbackSuccess = function (result) {
//                    if (result.response.total > 0) {
//                        obj.className = 's bl';
//
//                    } else {
//                        alert("Oops Sorry, You just missed it, check back again for more seats!!!");
//                        obj.className = 'bl x';
//                        sqty1 = sqty1 + 1;
//                    }
//                }, callbackFailure = function (result) {
//            alert(result.responseJSON.response.messages[0]);
//        };
//        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
//
//    }
//}
var iQty1 = 1;
var iQty2 = 1;
var iQty3 = 1;
var iQty4 = 1;

var sqty1 = 1;
var sqty2 = 1;
var sqty3 = 1;

function CngClass1(obj, qty1, SeatId, sId) {
    if (obj.className == 's bl')
    {
        if (iQty1 <= qty1) {
            iQty1 += 1;
            obj.className = 's bl process';
            this.CngClass1Help(obj, SeatId, sId);
        } else {
            alert("Max Qty Reached!!!!");
        }
    } else if (obj.className == 'r gr1')
    {
        iQty1 -= 1;
        obj.className = 'r gr1 process';
        this.CngClass1Help(obj, SeatId, sId);
    }
}

function CngClass1Help(obj, seatId, eventsignupId) {

    if (obj.className == 's bl process')
    {
        var pageUrl = api_updateSeats, method = 'POST', input = {venueseatid: seatId, type: 'add', eventsignupid: eventsignupId, eventid: eventId}, dataFormat = 'JSON',
                callbackSuccess = function (result) {
                    if (result.response.total > 0) {
                        obj.className = 'r gr1';
                        sqty1 = sqty1 + 1;
                    } else {
                        alert("Sorry the booked seat is not avaliable, Please book another Seat!!!");
                        obj.className = 'bl x';
                    }
                }, callbackFailure = function (result) {
            alert(result.responseJSON.response.messages[0]);
        };
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);

    } else if (obj.className == 'r gr1 process') {
        var pageUrl = api_updateSeats, method = 'POST', input = {venueseatid: seatId, type: 'remove', eventsignupid: eventsignupId, eventid: eventId}, dataFormat = 'JSON',
                callbackSuccess = function (result) {
                    if (result.response.total > 0) {
                        obj.className = 's bl';
                        sqty1 = sqty1 - 1;
                    } else {
                        alert("Removing failed");
                        //   obj.className = 'bl x';
                        // sqty1 = sqty1 + 1;
                    }
                }, callbackFailure = function (result) {
            alert(result.responseJSON.response.messages[0]);
        };
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);

    }
}

function CngClass2(obj, qty2, SeatId, sId) {
    if (obj.className == 's bl')
    {
        if (iQty2 <= qty2) {
            iQty2 += 1;
            obj.className = 's bl process';
            this.CngClass2Help(obj, SeatId, sId);
        } else {
            alert("Max Qty Reached!!!!");
        }
    } else if (obj.className == 'r gr1')
    {
        iQty2 -= 1;
        obj.className = 'r gr1 process';
        this.CngClass2Help(obj, SeatId, sId);
    }
}

function CngClass2Help(obj, seatId, eventsignupId) {

    if (obj.className == 's bl process')
    {
        var pageUrl = api_updateSeats, method = 'POST', input = {venueseatid: seatId, type: 'add', eventsignupid: eventsignupId, eventid: eventId}, dataFormat = 'JSON',
                callbackSuccess = function (result) {
                    if (result.response.total > 0) {
                        obj.className = 'r gr1';
                        // sqty1 = sqty1 + 1;
                    } else {
                        alert("Sorry the booked seat is not avaliable, Please book another Seat!!!");
                        obj.className = 'bl x';
                    }
                }, callbackFailure = function (result) {
            alert(result.responseJSON.response.messages[0]);
        };
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);

    } else if (obj.className == 'r gr1 process') {
        var pageUrl = api_updateSeats, method = 'POST', input = {venueseatid: seatId, type: 'remove', eventsignupid: eventsignupId, eventid: eventId}, dataFormat = 'JSON',
                callbackSuccess = function (result) {
                    if (result.response.total > 0) {
                        obj.className = 's bl';
                        // sqty2 = sqty1 - 1;
                    } else {
                        alert("Oops sorry, removing failed!!!");
                        //   obj.className = 'bl x';
                        // sqty1 = sqty1 + 1;
                    }
                }, callbackFailure = function (result) {
            alert(result.responseJSON.response.messages[0]);
        };
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);

    }
}
function CngClass3(obj, qty3, SeatId, sId) {
    if (obj.className == 's bl')
    {
        if (iQty3 <= qty3) {
            iQty3 += 1;
            obj.className = 's bl process';
            this.CngClass3Help(obj, SeatId, sId);
        } else {
            alert("Max Qty Reached!!!!");
        }
    } else if (obj.className == 'r gr1')
    {
        iQty3 -= 1;
        obj.className = 'r gr1 process';
        this.CngClass3Help(obj, SeatId, sId);
    }
}

function CngClass3Help(obj, seatId, eventsignupId) {

    if (obj.className == 's bl process')
    {
        var pageUrl = api_updateSeats, method = 'POST', input = {venueseatid: seatId, type: 'add', eventsignupid: eventsignupId, eventid: eventId}, dataFormat = 'JSON',
                callbackSuccess = function (result) {
                    if (result.response.total > 0) {
                        obj.className = 'r gr1';
                        // sqty1 = sqty1 + 1;
                    } else {
                        alert("Sorry the booked seat is not avaliable, Please book another Seat!!!");
                        obj.className = 'bl x';
                    }
                }, callbackFailure = function (result) {
            alert(result.responseJSON.response.messages[0]);
        };
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);

    } else if (obj.className == 'r gr1 process') {
        var pageUrl = api_updateSeats, method = 'POST', input = {venueseatid: seatId, type: 'remove', eventsignupid: eventsignupId, eventid: eventId}, dataFormat = 'JSON',
                callbackSuccess = function (result) {
                    if (result.response.total > 0) {
                        obj.className = 's bl';
                        // sqty1 = sqty1 - 1;
                    } else {
                        alert("Oops sorry, removing failed!!!");
                        //   obj.className = 'bl x';
                        // sqty1 = sqty1 + 1;
                    }
                }, callbackFailure = function (result) {
            alert(result.responseJSON.response.messages[0]);
        };
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);

    }
}
function CngClass4(obj, qty4, SeatId, sId) {
    if (obj.className == 's bl')
    {
        if (iQty4 <= qty4) {
            iQty4 += 1;
            obj.className = 's bl process';
            this.CngClass3Help(obj, SeatId, sId);
        } else {
            alert("Max Qty Reached!!!!");
        }
    } else if (obj.className == 'r gr1')
    {
        iQty4 -= 1;
        obj.className = 'r gr1 process';
        this.CngClass4Help(obj, SeatId, sId);
    }
}

function CngClass4Help(obj, seatId, eventsignupId) {

    if (obj.className == 's bl process')
    {
        var pageUrl = api_updateSeats, method = 'POST', input = {venueseatid: seatId, type: 'add', eventsignupid: eventsignupId, eventid: eventId}, dataFormat = 'JSON',
                callbackSuccess = function (result) {
                    if (result.response.total > 0) {
                        obj.className = 'r gr1';
                        // sqty1 = sqty1 + 1;
                    } else {
                        alert("Sorry the booked seat is not avaliable, Please book another Seat!!!");
                        obj.className = 'bl x';
                    }
                }, callbackFailure = function (result) {
            alert(result.responseJSON.response.messages[0]);
        };
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);

    } else if (obj.className == 'r gr1 process') {
        var pageUrl = api_updateSeats, method = 'POST', input = {venueseatid: seatId, type: 'remove', eventsignupid: eventsignupId, eventid: eventId}, dataFormat = 'JSON',
                callbackSuccess = function (result) {
                    if (result.response.total > 0) {
                        obj.className = 's bl';
                        // sqty1 = sqty1 - 1;
                    } else {
                        alert("Oops sorry, removing failed!!!");
                        //   obj.className = 'bl x';
                        // sqty1 = sqty1 + 1;
                    }
                }, callbackFailure = function (result) {
            alert(result.responseJSON.response.messages[0]);
        };
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);

    }
}
function validat_reg_form(eventsignupId, ticketquantity)
{
    var pageUrl = api_checkUpdateSeats, method = 'POST', input = {eventsignupid: eventsignupId, eventid: eventId, ticketquantity: ticketquantity}, dataFormat = 'JSON',
            callbackSuccess = function (result) {
                if (result.response.total > 0 && ticketquantity == result.response.total) {
                    $('#' + paymentGatewaySelected + '_frm').submit();
                } else {
                    alert("Please select " + ticketquantity + " Ticket/s");
                    return false;
                }
            }, callbackFailure = function (result) {
        alert(result.responseJSON.response.messages[0]);
    };
    getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
}
