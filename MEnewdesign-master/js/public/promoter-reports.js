$(document).ready(function () {
    $('#exportReports').on('click', function () {
        $('#exportReports').prop('disabled', true);
        var eventId = $('#eventId').val();
        //var transType = $('#selectTransType').val();
        var transType = $('#transactionType').val();
        //var reportTypeSel = $('input:radio[name=reportType]:checked');
        var reportType = "summary";
//        if (reportTypeSel.length > 0) {
//            reportType = reportTypeSel.val();
//        }
        var currencyCode = $('#currencyCode').val();
        var promoterCode = $('#promoterCode').val();
        var ticketId = $('#selectTicketType').val();
        var input = 'eventid=' + eventId + '&reporttype=' + reportType + '&transactiontype=' + transType;
        if (currencyCode != '') {
            input += '&currencycode=' + currencyCode;
        }
        if (promoterCode != '') {
            input += '&promotercode=' + promoterCode;
        }
        if (ticketId > 0) {
            input += '&ticketid=' + ticketId;
        }
        
       var pageUrl = api_reportsExportTransactions+"?"+input;
       // var pageUrl = '/download/downloadCsv?'+input;
        window.location.href=pageUrl;
        $('#exportReports').prop('disabled', false);
    });
    $('#loadMoreTransactions').on('click', function () {
        $('#loadMoreTransactions').prop('disabled', true);
        $('#loadMoreTransactions').text('processing...');
        var eventId = $('#eventId').val();
        var transType = $('#transactionType').val();
        var qString = $('#qString').val();
        //var reportTypeSel = $('input:radio[name=reportType]:checked');
        var reportType = "summary";
//        if (reportTypeSel.length > 0) {
//            reportType = reportTypeSel.val();
//        }
        var page = $('#page').val();
        var ticketId = $('#selectTicketType').val();
        var transactionTotal = $('#totalTransactionCount').val();
        var displayLimit = $('#displaylimit').val();
        $('#page').val(++page);
        //var input={'page':page};
        var input = 'page=' + page + '&eventid=' + eventId + '&reporttype=' + reportType + '&transactiontype=' + transType;
        if (ticketId > 0 && qString != "") {
            input += '&ticketid=' + ticketId + '&' + qString;
        } else if (ticketId > 0) {
            input += '&ticketid=' + ticketId;
        } else if (qString != "") {
            input += '&' + qString;
        }
//        if (ticketId > 0) {
//            input += '&ticketId=' + ticketId;
//        }
        var pageUrl = api_reportsGetReportDetails;
        var method = 'POST';
        //var call = 'reports';
        var dataFormat = 'JSON';
        //var quantitySum = $('#quantitySum').val(), amountSum = $('#amountSum').val(), paidSum = $('#paidSum').val(), discountSum = $('#discountSum').val();
        function callbackSuccess(result) {
            if (result.response.total > 0) {
                var sno = $('#SNo').val();
                var trData = '';
                if (reportType == 'detail') {
                    $.each(result.response.transactionList, function (key1, transaction) {
                        $.each(transaction, function (key1, value) {
                            $.each(value.ticketDetails, function (key1, ticket) {
                                trData += '<tr><td>' + sno++ + '</td>' +
                                        '<td>' + value.id + '</td>' +
                                        '<td>' + value.signupDate + '</td>' +
                                        '<td>' + ticket.tickettype + '</td>';
                                if (transType == 'affiliate') {
                                    trData += '<td>' + ticket.promotercode + '</td>';
                                }
                                trData += '<td>' + value.contactDetails.name + '<br>' + value.contactDetails.email + '<br>' + value.contactDetails.phone;
                                trData += '<td>' + ticket.quantity + '</td>';
                                trData += '<td>' + ticket.amount + '</td>';
                                if (transType != 'incomplete') {
                                    trData += '<td>' + value.paid + '</td>';
                                    trData += '<td>' + value.discount + '</td>';
                                } else {
                                    trData += '<td>' + ticket.failedcount + '</td>';
                                    trData += '<td>' + value.comment + '</td>';
                                }
                                if (transType == 'cod') {
                                    trData += '<td>' + ticket.comment + '</td>';
                                    trData += '<td>' + ticket.status + '</td>';
                                    trData += '<td>' + ticket.deliverystatus + '</td>';
                                }
                                trData += '</tr>';
                                $('#transactionTable tr:last').after(trData);
                                trData = '';
                            });
                        });
                    });

                } else {
                    $.each(result.response.transactionList, function (key1, value) {
                        //$.each(transactionData, function (key2, value) {
                        var loop = 1, bookedTicketsCount = Object.keys(value['ticketDetails']).length;
                        var snoText = (loop == 1) ? (sno++) : '';
                        trData += ('<tr><td>' + snoText + '</td>');
                        if (transType != 'incomplete') {
                            var regnoText = (loop == 1) ? value['id'] : '';
                            trData += ('<td>' + regnoText + '</td>');
                        }
                        var regdtText = (loop == 1) ? value['signupDate'] : '';
                        trData += ('<td>' + regdtText + '</td>');
                        $.each(value['ticketDetails'], function (key3, ticket) {
                            // paid += (ticket['price'] - ticket['discount'] + ticket['taxesData']['totaltax']);
//                            discount += ticket['discount'];
//                            quantitySum += ticket['quantity'];
//                            amountSum += ticket['price'];
//                            if (bookedTicketsCount == loop) {
//                                paidSum += paid;
//                            }
                            // discountSum += ticket['discount'];
                            trData += ('<td>' + ticket['tickettype'] + '</td>' +
                                    '<td>' + value['contactDetails']['name'] + '<br>' + value['contactDetails']['email'] + '<br>' + value['contactDetails']['phone'] + '</td>' +
                                    '<td>' + ticket['quantity'] + '</td>' +
                                    '<td>' + ticket['amount'] + '</td>'
                                    );
                            if (transType != 'incomplete') {
                                var paidText = (bookedTicketsCount == loop) ? value['paid'] : '';
                                var discountText = (bookedTicketsCount == loop) ? value['discount'] : ''
                                trData += ('<td>' + paidText + '</td>');
                                trData += ('<td>' + discountText + '</td>');
                            } else {
                                trData += ('<td>' + ticket['failedcount'] + '</td>');
                                trData += ('<td>' + ticket['comment'] + '</td>');
                            }
                            loop++;
                        });
                        trData += ('</tr>');
                        $('#transactionTable tr:last').after(trData);
                        trData = '';
                        //    });

                    });
                }
//                $('#quantitySum').val(quantitySum);
//                $('#amountSum').val(amountSum);
//                $('#paidSum').val(paidSum);
//                $('#discountSum').val(discountSum);
                if (transactionTotal > (page * displayLimit)) {
                    $('#loadMoreTransactions').text('Load More');
                    $('#loadMoreTransactions').prop('disabled', false);
                } else {
                    $('#loadMoreTransactions').hide();
                }
                $('#SNo').val(sno);
            }
        }
        function callbackFailure(result) {
            alert(result.responseJSON.response.messages);
            $('#loadMoreTransactions').prop('disabled', false);
            $('#loadMoreTransactions').text('Load More');
        }
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
    });
});