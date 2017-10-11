/**
 * To reset the form data
 * @returns {undefined}
 */
function clearForm(formId)
{

    document.getElementById(formId).reset();
}


/**
 * List initialization
 */

function listInitialization()
{
    var table = $('#data-table').DataTable({
        "bPaginate": true,
        "bLengthChange": false,
        "bFilter": false,
        "bSort": true,
        "bInfo": false,
        "bAutoWidth": false,
        "order": []
    });

    //To Enable Model for delete Button
    $('.modal').modal();



    //delete link related code
    $('body').on('click', '.delete-link', function() {
        $("#delete-link-value").val($(this).data("link-url"));
    });

    $("#model-ok").on("click",function () {

        window.location.replace($("#delete-link-value").val());
    });
    disableMessage();
}

/**
 * Function to auto disable the success messages 
 * and close button click
 * @returns {undefined}
 */
function disableMessage()
{
    //auto remove
    $('#card-alert').delay(3000).fadeOut('fast');
    $('#card-alert').click(function () {
        $(this).fadeOut('fast');
    });
}

/**
 * To check the duplicate value in json array
 * @param {type} propertyName
 * @param {type} inputArray
 * @returns {Boolean}
 */
function checkDuplicateInObject(propertyName, inputArray) {
    var seenDuplicate = false,
            testObject = {};

    inputArray.map(function (item) {
        var itemPropertyName = item[propertyName];
        if (itemPropertyName in testObject) {
            testObject[itemPropertyName].duplicate = true;
            item.duplicate = true;
            seenDuplicate = true;
        } else {
            testObject[itemPropertyName] = item;
            delete item.duplicate;
        }
    });

    return seenDuplicate;
}

/**
 * Preparing the selected users list array
 * @returns {undefined}
 */
function selectdUsers()
{
 
    var ele = $(".selectedUsers");
    var usersData = [];
    var i = 0;
    $(ele).each(function (index, value) {

        //selected check box values only
        if ($(value).is(":checked")) {
            var ele = $(value).html();
            usersData[i] = {
                user_id: $(value).val(),
                user_name: $(value).data("user-name")
            };
            i++;
        }

    });
    
    //Not selected the users
    if(usersData.length == 0){
        console.log(888);
        $('#card-alert').children().first().html("Please Select the users");
        $('#card-alert').fadeIn("fast").delay(1000).fadeOut("fast");
        return true;
        
    }
    usersData = JSON.stringify(usersData);

    var f = document.createElement('form');
    f.action = mtcBaseUrl + '/users/group';
    f.method = 'POST';

    var i = document.createElement('input');
    i.type = 'hidden';
    i.name = 'userData';
    i.value = usersData;
    f.appendChild(i);

    var j = document.createElement('input');
    j.type = 'hidden';
    j.name = '_token';
    j.value = $('meta[name="csrf-token"]').attr('content');
    f.appendChild(j);

    document.body.appendChild(f);
    f.submit();
}