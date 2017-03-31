$(document).ready(function () {
    $('#customfieldsType').on('change', function () {
        if (this.value == 'ticket') {
            $('#ticketsDiv').show();
            showCustomFields($('.tickets').val());
        } else {
            $('#ticketsDiv').hide();
            showCustomFields(0);
        }
    });
    //Add custom fields tag click
    $('#add_custom_field').click(function () {
        var eventId = $('#eventId').val();
        var url = dasboard_configureAddcustomfields+'/' + eventId;
        if ($('#customfieldsType').val() === 'ticket') {
            var ticketId = $("#ticketsDiv input[type='radio']:checked").val();
            url = url + '/' + ticketId;
        }
        window.location = url;
    });
});
function showCustomFields(ticketid) {
    $('#customFieldsDataTable tr.customfieldRow').remove();
    var eventId = $('#eventId').val();
    if(ticketid){
        var input = 'eventId=' + eventId + '&ticketid=' + ticketid + '&allfields=1';
    }else{
        var input = 'eventId=' + eventId + '&allfields=1';
    }
    var pageUrl = api_configureGetDashboardEventCustomFields;
    var method = 'POST';
    var dataFormat = 'JSON';
    function callbackSuccess(result) {
        if (result.response.total > 0) {
            //var rowCount = $('#customFieldsDataTable tr').length;
            //var trClass = 'class="customfieldRow"';
            var count = 1;
            $.each(result.response.customFields, function (key, value) {
                 ++count;
                var mandatory = '', displayonticket = '';
                if (value['fieldmandatory'] == 1) {
                    mandatory = 'checked="checked"';
                }
                if (value['fieldname'] == 'Full Name' || value['fieldname'] == 'Email Id'  || value['fieldname'] == 'Mobile No') {
                    mandatory = 'checked="checked" disabled="disabled"';
                }
//                if (value['displayonticket'] == 1) {
//                    displayonticket = 'checked="checked"';
//                }
                if (value['fieldname'] !== 'Full Name' && value['displayonticket'] == 1) {
                    displayonticket = 'checked=checked';
                }
                if (value['fieldname'] == 'Full Name') {
                    displayonticket = 'checked="checked" disabled="disabled"';
                }
                var className = 'orangrBtn';
                var buttonValue = 'hide';
                if (value['displaystatus'] == 1) {
                    if(value['fieldname'] == 'Full Name' || value['fieldname'] == 'Email Id' ||value['fieldname'] == 'Mobile No' ){
                      var lable = "no";
                    }else{
                        className = 'greenBtn';
                        buttonValue = 'show';
                    }
                   
                }
//                    if(classname!=''){
//                       key++; 
//                    }
                if (count % 2 != 0) {
                    trClass = 'class="customfieldRow odd"';
                }else{
                    trClass = 'class="customfieldRow"';
                }
                    
                var editButton = '';
                if (value['commonfieldid'] == 0) {
                    editButton = '&nbsp;<button onclick="editCustomField(' + value['id'] + ')" id="editCustomField' + value['id'] + '" type="button" class="editCustomField btn blueBtn"> Edit</button>';
                }
                var data = '<tr ' + trClass + '>' +
                        '<td>' + value['fieldname'] + '</td>' +
                        '<td>' + value['fieldtype'] + '</td>' +
                        '<td><input class="mandatory" type="checkbox" name="sport1[]" value="' + value['id'] + '" ' + mandatory + '>' +
                        '</td><td><input class="displayonticket" type="checkbox" name="sport1[]" value="' + value['id'] + '" ' + displayonticket + '>' +
                        '</td>';
                         if(lable){
                           data  += '<td>&nbsp;</td>';
                         }else{
                    data  += '<td><button type="button" id="displaystatus'+ value['id'] +'" value="' + value['id'] +'" class="displaystatus btn ' + className + '">' + buttonValue + '</button>' + editButton + '</td>'; }
                    data += '<td>' + value['ticketName'] + '</td>' +
                        '<td><input id="ordervalue' + value['id'] + '" type="text" value="' + value['order'] + '" class="CustomOrder" disabled><span id="' + value['id'] + '" class="orderIcon icon-edit"></span></td>';
                $('#customFieldsDataTable tr:last').after(data);
            });
            customCheckbox("sport1[]");
        }
    }
    function callbackFailure(result) {
        alert(result.responseJSON.response.messages.message[0]);
    }
    getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
}
function editCustomField(customfieldid) {
    var eventId = $('#eventId').val();
    window.location = site_url + 'dashboard/configure/editcustomfield/' + eventId + '/' + customfieldid;
}

$(document).on('click', ".displaystatus", function () {
        var msg = "Are you sure you want to add this field? If you click on OK, this field will be added on the registration form";
        if ($(this).text() == 'show') {
            msg = "Are you sure you want to remove this field? If you click on OK, this field will be removed from the registration form but the information already collected for this field will be retained."
        }
        if (confirm(msg))
        {
            var id = this.value;
            $('#displaystatus' + id).prop('disabled', true);
            var eventId = $('#eventId').val();
            var input = 'eventid=' + eventId + '&customfieldid=' + this.value + '&field=displaystatus';
            var pageUrl = api_configureUpdateStatus;
            var method = 'POST';
            var dataFormat = 'JSON';
            function callbackSuccess(result) {
                console.log(result);
                if (result.response.total > 0) {

                    if (result.response.updateCustomFieldResponse.value == 1) {
                        $('#displaystatus' + id).text('show');
                        $('#displaystatus' + id).removeClass('orangrBtn');
                        $('#displaystatus' + id).addClass('greenBtn');
                    } else {
                        $('#displaystatus' + id).text('hide');
                        $('#displaystatus' + id).removeClass('greenBtn');
                        $('#displaystatus' + id).addClass('orangrBtn');
                    }
                }
                $('#displaystatus' + id).prop('disabled', false);
            }
            function callbackFailure(result) {
                alert(result.responseJSON.response.messages.message[0]);
                $('#displaystatus' + id).prop('disabled', false);
            }
            getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
        }
    });
    
    
$(document).on('click',".displayonticket", function () {
// var id = this.value;
// $('#displaystatus' + id).prop('disabled', true);
 var dispTckt = $('.displayonticket:checked').length;
    if(dispTckt > 4){
       alert("Sorry, you can select only Four fields to display on print pass");
        $(this).prop('checked',false);
        $(this).parent().removeClass('selected');
             return false;
    }else{
        var eventId = $('#eventId').val();
        var input = 'eventid=' + eventId + '&customfieldid=' + this.value + '&field=displayonticket';
        var pageUrl = api_configureUpdateStatus;
        var method = 'POST';
        var dataFormat = 'JSON';
        function callbackSuccess(result) {
        }
        function callbackFailure(result) {
            alert(result.responseJSON.response.messages.message[0]);
        }
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
    }
     });
        $(document).on('change',".mandatory", function () {
        //var id = this.value;
        // $('#mandatory' + id).prop('disabled', true);
        var eventId = $('#eventId').val();
        var input = 'eventid=' + eventId + '&customfieldid=' + this.value + '&field=mandatory';
        var pageUrl = api_configureUpdateStatus;
        var method = 'POST';
        var dataFormat = 'JSON';
        function callbackSuccess(result) {
            console.log(result);
//            if (result.response.updateCustomFieldResponse.value == 1) {
//                $('#mandatory' + id).prop('checked', true);
//            } else {
//                $('#mandatory' + id).prop('checked', false);
//            }
//            $('#mandatory' + id).prop('disabled', false);
//            if (result.response.total > 0) {
//                
//            }
        }
        function callbackFailure(result) {
            alert(result.responseJSON.response.messages.message[0]);
            //$('#mandatory' + id).prop('disabled', false);
        }
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
    });
    
        $(document).on('click', ".orderIcon", function () {
        var id = this.id;
        if (this.className.indexOf('icon-edit') != '-1') {
            $(this).removeClass('icon-edit');
            $(this).addClass('icon-publish mar10');
            $('#ordervalue' + id).prop('disabled', false);
            $('#ordervalue' + id).focus();
        } else {
            var eventId = $('#eventId').val();
            var value = $('#ordervalue' + this.id).val();
            if (isNaN(value) || value<=0) {
                alert("Please enter valid number");
            } else {
                //var id = this.id;
                var input = 'eventid=' + eventId + '&customfieldid=' + id + '&field=order&value=' + value;
                var pageUrl = api_configureUpdateStatus;
                var method = 'POST';
                var dataFormat = 'JSON';
                function callbackSuccess(result) {
                    console.log(result);
                    $('#' + id).removeClass('icon-publish mar10');
                    $('#' + id).addClass('icon-edit');
                    $('#ordervalue' + id).prop('disabled', true);
                }
                function callbackFailure(result) {
                    alert(result.responseJSON.response.messages.message[0]);
                    //$('#mandatory' + id).prop('disabled', false);
                }
                getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
            }
        }
    });
