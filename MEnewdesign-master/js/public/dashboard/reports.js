$(document).ready(function () {
    customRadio("reportType");
    $('#selectTransType').on('change', function () {
        var eventId = $('#eventId').val();
        var reportType = "summary";
        var qString = $('#qString').val();
        if (this.value != 'incomplete' && this.value != 'cancel') {
            var reportTypeSel = $('input:radio[name=reportType]:checked');
            if (reportTypeSel.length > 0) {
                reportType = reportTypeSel.val();
            }
        } else if (this.value == 'cancel') {
            reportType = "detail";
        }

        var ticketId = $('#selectTicketType').val();
        var url = url_dashboardReports + '/' + eventId + '/' + reportType + '/' + this.value + '/1';
        if (ticketId > 0 && qString != "") {
            url += '?ticketid=' + ticketId + '&' + qString;
        } else if (ticketId > 0) {
            url += '?ticketid=' + ticketId;
        } else if (qString != "") {
            url += '?' + qString;
        }
        window.location = url;
    });
    $('#downloadAll').on('click', function () {
        var pageUrl = api_reportsDownloadImages;
        var eventId = $('#eventId').val();
        var transType = $('#selectTransType').val();
        var reportTypeSel = $('input:radio[name=reportType]:checked');
        var reportType = "";
        if (reportTypeSel.length > 0) {
            reportType = reportTypeSel.val();
        }
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
        input += '&downloadall=1';
        var method = 'POST';
        var dataFormat = 'json';
        $("#download_files").html('<img id="success-img" src="' + site_url + '../images/me-loading.gif">');
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
        function callbackFailure(result) {
            alert(result.responseJSON.response.messages);
            $("#sendsuccess").html(result.responseJSON.response.messages);
            setTimeout(function () {
                $("#sendsuccess").html('');
            }, 2000);
        }
        function callbackSuccess(result) {
            console.dir(result.response.filepath);
            if (typeof (result.response) != 'undefined') {
                var dimages = downloadAllImages(result.response.filepath);
                if (dimages == true) {
                    $("#download_files").html('Files Downloaded');
                    setTimeout(function () {
                        $("#download_files").html('');
                    }, 2000);
                }
                // window.location.href = site_url+result.response.filepath;
            } else {
                $("#download_files").html('Error In File Downloading.Try Again');
                setTimeout(function () {
                    $("#download_files").html('');
                }, 2000);
            }
        }
    });
    $('#selectTicketType').on('change', function () {
        var eventId = $('#eventId').val();
        var reportType = "";
        var reportTypeSel = $('input:radio[name=reportType]:checked');
        if (reportTypeSel.length > 0) {
            reportType = reportTypeSel.val();
        }
        var transType = $('#selectTransType').val();
        var qString = $('#qString').val();
        var ticketId = this.value;
        var url = url_dashboardReports + '/' + eventId + '/' + reportType + '/' + transType + '/1';
        if (ticketId > 0 && qString != "") {
            url += '?ticketid=' + ticketId + '&' + qString;
        } else if (ticketId > 0) {
            url += '?ticketid=' + ticketId;
        } else if (qString != "") {
            url += '?' + qString;
        }
        window.location = url;
    });
    $('input[type=radio][name=reportType]').on('change', function () {
        var eventId = $('#eventId').val();
        var transType = $('#selectTransType').val();
        var qString = $('#qString').val();
        var url = url_dashboardReports + '/' + eventId + '/' + this.value + '/' + transType + '/1';
        var ticketId = $('#selectTicketType').val();
        if (ticketId > 0 && qString != "") {
            url += '?ticketid=' + ticketId + '&' + qString;
        } else if (ticketId > 0) {
            url += '?ticketid=' + ticketId;
        } else if (qString != "") {
            url += '?' + qString;
        }
        window.location = url;
    });
    $('#loadMoreTransactions').on('click', function () {
        $('#loadMoreTransactions').prop('disabled', true);
        $('#loadMoreTransactions').text('processing...');
        var eventId = $('#eventId').val();
        var transType = $('#selectTransType').val();
        var qString = $('#qString').val();
        var reportTypeSel = $('input:radio[name=reportType]:checked');
        var reportType = "";
        if (reportTypeSel.length > 0) {
            reportType = reportTypeSel.val();
        }
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
                if(typeof $('#custIds').val() !== 'undefined'){
                    var custIdArray = JSON.parse($('#custIds').val());
                }
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
                                if (custIdArray != null && typeof custIdArray !== 'undefined' && custIdArray.length > 0) {
                                    $.each(custIdArray, function (key2, custid) {
                                        var path = '';
                                        if (ticket.customfields[custid] !== 'undefined' && custid in ticket.customfields) {
                                            path = ticket.customfields[custid].value;
                                        }
                                        if (path !== "") {
                                            trData += '<td><a href="' + path + '" target="_blank">View</a> | <br/><a href="' + site_url + 'home/download?filePath=' + path + '">Download</a></td>';
                                        } else {
                                            trData += '<td>&nbsp;</td>';
                                        }
                                    });
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
                        $.each(value['ticketDetails'], function (key3, ticket) {
                            var snoText = (loop == 1) ? (sno++) : '';
                            trData += ('<tr><td>' + snoText + '</td>');
                            if (transType != 'incomplete') {
                                var regnoText = (loop == 1) ? value['id'] : '';
                                trData += ('<td>' + regnoText + '</td>');
                            }
                            var regdtText = (loop == 1) ? value['signupDate'] : '';
                            trData += ('<td>' + regdtText + '</td>');
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
                            if (custIdArray != null && typeof custIdArray !== 'undefined' && custIdArray.length > 0 && (bookedTicketsCount == loop)) {
                                $.each(custIdArray, function (key4, id) {
                                    var path = '';
                                    if (typeof value['customfields'] !== 'undefined') {
                                        path = value['customfields'][id];
                                    }
                                    if (path.length > 0) {
                                        trData += '<td><a href="' + path + '" target="_blank">View</a> | <br/><a href="' + site_url + 'home/download?filePath=' + path + '">Download</a></td>';
                                    } else {
                                        trData += '<td>&nbsp;</td>';
                                    }
                                });
                            }
                            trData += ('</tr>');
                            $('#transactionTable tr:last').after(trData);
                            trData = '';
                            loop++;
                        });
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
    $('#exportReports').on('click', function () {
        $('#exportReports').prop('disabled', true);
        var eventId = $('#eventId').val();
        var transType = $('#selectTransType').val();
        var reportTypeSel = $('input:radio[name=reportType]:checked');
        var reportType = "";
        if (reportTypeSel.length > 0) {
            reportType = reportTypeSel.val();
        }
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
        window.location.href=pageUrl;
        $('#exportReports').prop('disabled', false);
    });
    $('#emailAttachedReports').on('click', function () {
        var eventId = $('#eventId').val();
        var transType = $('#selectTransType').val();
        var reportTypeSel = $('input:radio[name=reportType]:checked');
        var reportType = "";
        if (reportTypeSel.length > 0) {
            reportType = reportTypeSel.val();
        }
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
        var pageUrl = api_reportsEmailTransactions;
        var method = 'POST';
        var dataFormat = 'JSON';
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
        function callbackFailure(result) {
            alert(result.responseJSON.response.messages);
        }
        function callbackSuccess(result) {
            if (result.response.total > 0) {
                alert(result.response.email);
            } else {
                alert(result.response.messages);
            }
        }
    });
});

//  For Downloading all the Custom File Images 
function downloadAllImages(imgLinks) {
    var zip = new JSZip();
    var deferreds = [];
    for (var i = 0; i < imgLinks.length; i++)
    {
        deferreds.push(addToZip(zip, imgLinks[i], i));
    }
    $.when.apply(window, deferreds).done(generateZip, true);
    return true;
}
function generateZip(zip)
{
    var content = zip.generate({type: "blob"});
    // see FileSaver.js
    var eventId = $('#eventId').val();
    saveAs(content, eventId + "_CustomfieldImages.zip");
}
function addToZip(zip, imgLink, i) {
    var deferred = $.Deferred();

    JSZipUtils.getBinaryContent(imgLink, function (err, data) {
        if (err) {
            // alert("Problem happened when download img: " + imgLink);
            console.log("Problem happened when download img: " + imgLink);
            deferred.resolve(zip); // ignore this error: just logging
            // deferred.reject(zip); // or we may fail the download
        } else {

            var fileName = imgLink.substr(imgLink.lastIndexOf("/") + 1);
            zip.file(fileName, data, {binary: true});
            deferred.resolve(zip);
        }
    });
    return deferred;
}
