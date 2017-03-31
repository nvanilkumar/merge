$(document).ready(function () {
    $('#addGlobalPromoter').click(function () {
        var code=$('#globalPromoterCode').val();
        if($.trim(code)==''){
            $('#messagePTag').show();
            $('#errorMessage').html('Please enter code');
            return false;
        }else  if(code.length<6){
            $('#messagePTag').show();
            $('#errorMessage').html("Code should be atleast 6 character's");
            return false;
        }
        var pageUrl = api_globalPromoter, method = 'POST', input = {code: $('#globalPromoterCode').val()}, dataFormat = 'JSON', callbackSuccess = function (result) {
            $('#messagePTag').hide();
            $('#successMessage').show();
            $('#globalPromoterCode').attr('disabled','disabled');
            $('#addGlobalPromoter').hide()
        }, callbackFailure = function (result) {
            $('#messagePTag').show();
            $('#errorMessage').html(result.responseJSON.response.messages);
        };
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
    });
    $('#checkCodeAvailability').click(function () {
        $('#codeError').text('');
        var code = $('#globalPromoterCode').val();
        if (code == '') {
            $('#codeError').removeClass().addClass('error');
            $('#codeError').text('Code cannot be empty');
            return false;
        } else if ($.inArray(code, notAllowedCodes) != '-1') {
            $('#codeError').removeClass().addClass('error');
            $('#codeError').text('Organizer, meraevents are predefined words, you cant use them');
            return false;
        }
        var pageUrl = api_checkGlobalCodeAvailability, method = 'POST', input = {promoterCode: code, type: 'global'}, dataFormat = 'JSON', callbackSuccess = function (result) {
            if (result.response.total > 0) {
                $('#codeError').removeClass().addClass('error');
                $('#codeError').text('This code is not available.');
            } else {
                $('#codeError').removeClass().addClass('success');
                $('#codeError').text('This code is available.');
            }
        }, callbackFailure = function (result) {
            $('#messagePTag').show();
            $('#errorMessage').html(result.responseJSON.response.messages);
        };
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
    });
});