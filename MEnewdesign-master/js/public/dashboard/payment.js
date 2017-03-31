$(document).ready(function(){
    //To export the reports
        $('#exportReports').on('click', function () {
        $('#exportReports').prop('disabled', true);
        var eventId = $('#eventId').val();
        var transType = 'refund';
        var reportType = 'detail';
        var input = 'eventid=' + eventId + '&reporttype=' + reportType + '&transactiontype=' + transType;
        var pageUrl = api_reportsExportTransactions+"?"+input;
        window.location.href=pageUrl;
        $('#exportReports').prop('disabled', false);
    });
    
    $('#mailSent').delay(1000).fadeOut(400);
             
    //For email attachment
    $('#emailAttachedReports').on('click', function () {
        var eventId = $('#eventId').val();
        var transType = 'refund';
        var reportType = 'detail';
        var input = '&eventid=' + eventId + '&reporttype=' + reportType + '&transactiontype=' + transType;
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
