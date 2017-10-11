/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {
    
    $('#event_name').characterCounter();
 
    //date validations
    $.validator.addMethod("checkGraterDate", function(value, element) {
    
           if ($.datepicker.parseDate("mm/dd/yy", $("#event_start_date").val()) <= $.datepicker.parseDate("mm/dd/yy", value)) {
                return true;
            }
           
            return false;
   
    }, "event end date should be greater than the event start date ");
    
    $('.mtcevents').validate({
        rules: {
            event_name: {
                required: true,
                maxlength: 100
            },
            event_description: {
                required: true,
            },
            event_start_date: {
                required: true,
                 
            },
            event_end_date: {
                required: true,
                checkGraterDate:true
              
            }
        },
        messages: {
            event_name: {
                required: "Please enter the event name",
                maxlength: "Event name should not exceed more than 100 characters"
            },
            event_description: {
                required: "Please enter the event description",
             
            },
             event_start_date: {
                required: "Please Select the event start date"
            },
            event_end_date: {
                required: "Please Select the event end date"
            }

        },
        errorElement: "div",
        errorPlacement: function (error, element) {
            $(element).addClass("errorTxt2");
            error.appendTo(element.parent());
        }
    });

    $("#event_start_date, #event_end_date").datetimepicker({
        minDate: 0,
        format: 'm/d/Y H:i'
    });
    

});
