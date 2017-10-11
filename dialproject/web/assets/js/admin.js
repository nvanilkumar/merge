
$(function () {
    //Regular alert
    //  bootbox.alert("<h4 class='text-warning' > Test message</h4>", function () {
    //      console.log("Hello");
    // });

    // bootbox.confirm("<h4 class='text-info'>Are you sure, you want to delete this agent ?</h4>", function (result) {
    //   if (result == true)
    //   {
    //true event
    //    }
    //});
    jQuery('#date_start').datetimepicker({
        format: 'm/d/Y',
        onShow: function (ct) {
            this.setOptions({
                maxDate: get_date(jQuery('#date_end').val()) ? get_date(jQuery('#date_end').val()) : false,
                minDate: 0
            })
        },
        timepicker: false,
        todayButton: true
    });
    $("#date_start").on('change', function(ev){                 
        $('#date_start').datetimepicker('hide');
        });
    jQuery('#date_end').datetimepicker({
        format: 'm/d/Y',
        onShow: function (ct) {
            this.setOptions({
                minDate: get_date(jQuery('#date_start').val()) ? get_date(jQuery('#date_start').val()) : false
            })
        },
        timepicker: false
    });
    $("#date_end").on('change', function(ev){                 
        $('#date_end').datetimepicker('hide');
        });
    jQuery('#time_start').datetimepicker({
        format: 'H:i:s',
        onShow: function (ct) {
            this.setOptions({
                maxTime: jQuery('#time_end').val() ? jQuery('#time_end').val() : false
            })
        },
        datepicker: false
    });
    jQuery('#time_end').datetimepicker({
        format: 'H:i:s',
        onShow: function (ct) {
            this.setOptions({
                minTime: jQuery('#time_start').val() ? jQuery('#time_start').val() : false
            })
        },
        datepicker: false
    });
    //Date picker
    jQuery('.timepicker').datetimepicker({
        datepicker: false,
        format: 'H:i:s'
    });

    function get_date(input) {
        if (input == '') {
            return false;
        } else {
            // Split the date, divider is '/'
            var parts = input.match(/(\d+)/g);
            return parts[2] + '/' + parts[0] + '/' + parts[1];
        }
    }

    $("#myModal").on("show.bs.modal", function (e) {
        var link = $(e.relatedTarget);
        $(this).find(".modal-content").load(link.attr("href"));
    });

});

$(document).ready(function () {
    if (getctype) {
        $.ajax({
            url: $admin_get_campaign_type,
            type: "GET",
            data: "status=" + status,
            dataType: "json",
            success: function (response)
            {

                if (response.status == "success")
                {
                    console.log(response.response);
                }
                else
                {
                }

            }});

    }

    jQuery.validator.addMethod("noSpace", function (value, element) {
        return value.indexOf(" ") < 0 && value != "";
    }, "No space please and don't leave it empty");


    $("#form-admin-add-campaign").validate({
        rules: {
            campaign_name: {required: true, },
            campaign_type: {required: true, },
            from_date: {required: true, },
            to_date: {required: true, },
            from_time: {required: true, },
            to_time: {required: true, },
            retry_count: {required: true, },
            voice_file: {required: true, },
           // 'days[]': {required: true, },
            'agents[]': {required: true, },


        },
        messages: {
            campaign_name: {required: "Please enter campaign name", },
            campaign_type: {required: "Please choose campaign type", },
            from_date: {required: "Select from date", },
            to_date: {required: "Select to date", },
            from_time: {required: "Select from time", },
            to_time: {required: "Select to time", },
            retry_count: {required:  "Please enter retry count ", },
            voice_file: {required:  "Please upload a voice file", },
            //'days[]': {required: "Please select atleast one day", },
            'agents[]': {required: "Please select atleast one agent", },
    

        },
        errorPlacement: function (error, element) {
            //if (element.attr("type") == "checkbox") {
                /*
                if ($(element).attr('name') == 'agents[]')
                {

                    error.appendTo('#agents-error');
                }
                if ($(element).attr('name') == 'days[]')
                {

                   // error.appendTo('#days-error');
                }
                // error.insertAfter($(element).parents('div'));*/
           // } else {
                error.insertAfter($(element));
                // something else if it's not a checkbox
           // }
        }
    });
    $("#form-admin-edit-campaign").validate({
        rules: {
            campaign_name: {required: true, },
            campaign_type: {required: true, },
            from_date: {required: true, },
            to_date: {required: true, },
            from_time: {required: true, },
            to_time: {required: true, },
            retry_count: {required: true, },
                    // 'days[]': {required: true, },
            'agents[]': {required: true, },


        },
        messages: {
            campaign_name: {required: "Please enter campaign name", },
            campaign_type: {required: "Please choose campaign type", },
            from_date: {required: "Select from date", },
            to_date: {required: "Select to date", },
            from_time: {required: "Select from time", },
            to_time: {required: "Select to time", },
            retry_count: {required:  "Please enter retry count ", },
            voice_file: {required:  "Please upload a voice file", },
            //'days[]': {required: "Please select atleast one day", },
            'agents[]': {required: "Please select atleast one agent", },
    

        },
        errorPlacement: function (error, element) {
            //if (element.attr("type") == "checkbox") {
                /*
                if ($(element).attr('name') == 'agents[]')
                {

                    error.appendTo('#agents-error');
                }
                if ($(element).attr('name') == 'days[]')
                {

                   // error.appendTo('#days-error');
                }
                // error.insertAfter($(element).parents('div'));*/
           // } else {
                error.insertAfter($(element));
                // something else if it's not a checkbox
           // }
        }
    });
    $("#form-campaign-management").validate({
        rules: {
            action: {required: true, },
            'campaigns[]': {required: true, }

        },
        messages: {
            action: {required: "Please choose an action", },
            'campaigns[]': {required: "Please select atleast one campaign", }

        },
        errorPlacement: function (error, element) {
            if (element.attr("type") == "checkbox") {

                if ($(element).attr('name') == 'campaigns[]')
                {

                    error.appendTo('#campaigns-error');
                }
                // error.insertAfter($(element).parents('div'));
            } else {
                error.insertAfter($(element));
                // something else if it's not a checkbox
            }
        }
    });

});