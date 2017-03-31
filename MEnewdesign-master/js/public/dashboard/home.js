
$(document).ready(function () {
    $('#dateReportType,#ticketType').on('change', function () {
        var eventId = $('#eventId').val();
        var dateReportType = $('#dateReportType').val();
        var ticketType = $('#ticketType').val();
        //var currencyId = ($('#currencyid').val() !== undefined) ? $('#currencyid').val() : '1';
//        var url = site_url + "dashboard/home/" + eventId + '/' + dateReportType;
//        if (ticketType > 0) {
//            url += '/' + ticketType;
//        }
//        window.location = url;
        var input = 'eventid=' + eventId + '&filtertype=' + dateReportType;
        if(ticketType>0){
            input+= '&ticketid=' + ticketType;
        }
        var pageUrl = api_reportsGetWeekwiseSales;
        var method = 'POST';
        var dataFormat = 'JSON';
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
        function callbackFailure(result) {
            alert(result.response.messages);
        }
        function callbackSuccess(result) {
            if (result.response.total > 0) {
                var txnData = result.response.weekWiseData;
                var totalSaleAmountArray = result.response.totalTransactionsData;
                var quantityArray = txnData.quantity;
                data=[{"label": "0", "value": "0"}];
                $.each(quantityArray, function (key, value) {
                    var obj = {};
                    obj.label = key;
                    obj.value = value;
                    data.push(obj);
                });
                var totalSaleTicket = totalSaleAmountArray.totalquantity;
                var totalSaleAmount = totalSaleAmountArray.totalpaid;
                $('#ticketSoldTotal').html(totalSaleTicket);
                $('#ticketAmountDiv').empty();
                var amountData = '';
                if(Object.keys(totalSaleAmount).length == 1){
                    $.each(totalSaleAmount, function (key, value) {
                        amountData += '<h4>total amount';
                        if (key != '') {
                            amountData += '(' + key + ')';
                   }
                        amountData += '</h4><span class="green">' + value + '</span>';
                });
                }else {
                    $.each(totalSaleAmount, function (key, value) {
                        if (key != '') {
                            amountData += '<h4>total amount';
                            amountData += '(' + key + ')';
                        }
                        if(value != 0){
                            amountData += '</h4><span class="green">' + value + '</span>';
                        }
                    });
                }
                $('#ticketAmountDiv').html(amountData);
               // fustionData.dataSource.chart.subCaption = dateReportType.toUpperCase();
               // fustionData.dataSource.chart.numberPrefix = currencyCode;
                fustionData.dataSource.data = data;
                var salesChart = new FusionCharts(fustionData).render();
            }
        }
    });
//    $('#ticketType').on('change', function () {
//        var eventId = $('#eventId').val();
//        var dateReportType = $('#dateReportType').val();
//        var ticketType = this.value;
//        var url = site_url + "dashboard/home/" + eventId + '/' + dateReportType;
//        if (ticketType > 0) {
//            url += '/' + ticketType;
//        }
//        window.location = url;
//    });
    $('#salesType,#menulist').on('change', function () {
        var eventId = $('#eventId').val();
        var salesType = $('#salesType').val();
        var filtertype = $('#menulist').val();
        //salesEffortTickets
        var input = 'eventid=' + eventId + '&filtertype=' + filtertype;
        var pageUrl = api_reportsSalesEffortReports;
        var method = 'POST';
        var dataFormat = 'JSON';
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
        function callbackFailure(result) {
            alert(result.response.messages);
        }
        function callbackSuccess(result) {
            if (result.response.total > 0) {
                var data = result.response.salesEffortResponse;
                var row = '';
                $.each(data, function (key, value) {
                    if (key == salesType) {
                        $('#salesEffortTickets').html(value.totalquantity);
                        if (typeof value.totalamount === "object") {
                            $.each(value.totalamount, function (key1, value1) {
                                row += '<div class="priceDiv"><h4>total amount (' + key1 + ')</h4><span class="gray">' + value1 + '</span> </div>';
                            });
                        } else {
                            row += '<div class="priceDiv"><h4>total amount</h4><span class="gray">0</span> </div>';
                        }
                        return false;
                    }
                });
                $('#salesEffortAmounts').html(row);
            }
        }
        $('.fs-Box1-content').matchHeight();
    });
});
var data = [{"label": "0", "value": "0"}];
var weekWiseData = $.parseJSON(weekWiseJSONData);
var quantityArray = weekWiseData.quantity;
var amountArray = weekWiseData.amount;

$.each(quantityArray, function (key, value) {
    var obj = {};
    obj.label = key;
    obj.value = value;
//    $.each(value, function (currencyCode, amountVal) {
//        obj.value = amountVal;
//    });
    data.push(obj);
});
var fustionData = {
    type: 'area2d',
    renderAt: 'chartContainer',
    width: '100%',
    height: '306',
    dataFormat: 'json',
    dataSource: {
        "chart": {
            "caption": "Ticket Sales",
            //"subCaption": filterType,
            "xAxisName": "Day",
            "yAxisName": "Ticket Quantity",
            "numberPrefix": "",
            "paletteColors": "#0075c2",
            "connectNullData": "1",
            "loadMessage": "Loading...",
            "valueHoverAlpha": "20",
            "formatNumberScale": "0",
            "labelBorderPadding": "10",
            "labelPadding": "10",
            "bgColor": "#ffffff",
            "showBorder": "0",
            "showCanvasBorder": "0",
            "plotBorderAlpha": "10",
            "usePlotGradientColor": "0",
            "plotFillAlpha": "50",
            "showXAxisLine": "1",
            "axisLineAlpha": "25",
            "divLineAlpha": "10",
            "showValues": "1",
            "showAlternateHGridColor": "0",
            "captionFontSize": "14",
            "subcaptionFontSize": "14",
            "subcaptionFontBold": "0",
            "toolTipColor": "#ffffff",
            "toolTipBorderThickness": "0",
            "toolTipBgColor": "#000000",
            "toolTipBgAlpha": "80",
            "toolTipBorderRadius": "2",
            "toolTipPadding": "5"
        },
        "data": data
    }
};
FusionCharts.ready(function () {
    var salesChart = new FusionCharts(fustionData).render();
});