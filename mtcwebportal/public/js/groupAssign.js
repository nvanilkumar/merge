$(function () {
    
    //Form validations for the group assign
    $("#assign_group").submit(function (e) {
        e.preventDefault();


        if ($("#group_id").val() == "")
        {
            var errorDiv = '<div id="group_id-error" class="error">Please select the group name</div>';

            if ($("#group_id").next("div").length == 0) {
                $("#group_id").parent().append(errorDiv);
            }
            return;
        }

        var selectedUsersCount = $('input:hidden[name="user_ids[]"]').length;

        if (selectedUsersCount == 0)
        {
            window.location.replace($("#cancel_button").attr('href'));
            return;
        }
        
        //submit the form after all validations are success
        document.getElementById("assign_group").submit();


    });

    //Removeing the error div
    $("#group_id").change(function () {
        if ($(this).val() > 0) {

            $(this).next().remove();
        }
    });
});