$(document).ready(function () {
    $('.status').on('click', function () {
        var currentElement = this;
        $(currentElement).prop('disabled', true);
        var eventId = $('#eventId').val();
        var input = 'eventid=' + eventId + '&collaboratorid=' + $(this).attr('collaboratorId') + '&field=status';
        var pageUrl = api_collaboratorUpdateStatus;
        var method = 'POST';
        var dataFormat = 'JSON';
        function callbackSuccess(result) {
            console.log(result);
            if (result.response.total > 0) {
                if (result.response.updatecollaboratorResponse.value == 1) {
                    $(currentElement).text('active');
                    $(currentElement).removeClass('orangrBtn');
                    $(currentElement).addClass('greenBtn');
                } else {
                    $(currentElement).text('inactive');
                    $(currentElement).removeClass('greenBtn');
                    $(currentElement).addClass('orangrBtn');
                }
            }
            $(currentElement).prop('disabled', false);
        }
        function callbackFailure(result) {
            alert(result.responseJSON.response.messages.message[0]);
            $(currentElement).prop('disabled', false);
        }
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
    });
});

