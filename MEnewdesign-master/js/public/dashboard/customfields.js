// JavaScript Document
$(function () {
    customCheckbox("fieldMandatory");
    //Apply the validations to the form
    $('#customfields').validate({
        rules: {
            fieldName: {
                required: true
            },
            fieldType: {
                required: true
            },
            "customFieldValues[]": {
                required: true
            }

        },
        messages: {
            fieldName: {
                required: "Please enter the field name"
            },
            "fieldType": {
                required: "Please select the option"

            },
            "customFieldValues[]": {
                required: "Please enter the field value"
            }

        },
        errorPlacement: function (error, element) {
            console.log("err");
            error.insertAfter(element.next());

        }

    });

    //On change the select box    
    $("#fieldType").change(function () {
        optionDivStatus($(this).val());
    });
    optionDivStatus($("#fieldType").val());

    //Add option
    $("#addOptionClass").on("click", function () {
        var newOption = '<li>' +
                '<input   type="text" class="textfield addfield" name="customFieldValues[]">' +
                '<span style="cursor: pointer;" class="removeOption">Remove </span> </li>';
        $("#options_div ul").append(newOption);

    });
    //Remove Option
    $("#options_div").on("click", ".removeOption", function () {
        var optionsLength = $('[name="customFieldValues[]"]').length;
        if (optionsLength > 1) {
            $(this).parent().remove();
        }

    });
    //set the custom field type
    if($("#ticketId").val()>0){
        $("#fieldlevel").val('ticket');
    }else{
         $("#fieldlevel").val('event');
    }
    

});

function optionDivStatus(customFieldValue) {
    var compareArray = ["test", "checkbox", "dropdown", "radio"];
    if ($.inArray(customFieldValue, compareArray) > 0) {
        $("#options_div").show();
    } else {
        $("#options_div").hide();
    }

}