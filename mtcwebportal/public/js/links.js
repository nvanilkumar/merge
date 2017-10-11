$(function () {

    $("#save_order, #cancel_order").hide();
    $('#create_link').validate({
        rules: {
            link_name: {
                required: true,
                nowhitespace: true
            },
            link_url: {
                required: true,
                url: true
            }
        },
        messages: {
            link_name: {
                required: "Please enter the link name"
            },
            link_url: {
                required: "Please enter the url",
                url: "Please enter the valid url"
            }

        },
        errorElement: "div",
        errorPlacement: function (error, element) {
            $(element).addClass("errorTxt2");
            error.appendTo(element.parent());
        }
    });

    //Edit user related form fields information
    if (typeof (linkDetails) != "undefined" && linkDetails !== "") {
        $("#link_name").val(linkDetails.link_name);
        $("#link_url").val(linkDetails.link_url);
    }

    //To show the save link
    $("#change_order").click(function () {
        $("#save_order, #cancel_order").parent().show();
        $(this).parent().hide();
        var links = $(".link_position");
        $(links).each(function (index, value) {

            var inputBox = $('<input/>').attr({type: 'text', name: 'test',
                class: 'postion_list', value: $(value).html()});
            $(value).html(inputBox);
        });
    });

    //To cancel the save menu posion save option
    $("#cancel_order").click(function () {

        $("#save_order, #cancel_order").parent().hide();
        $("#change_order").parent().show();

        var links = $(".link_position");
        $(links).each(function (index, value) {
            var ele = $(value).html();
            $(value).html($(ele).val());
        });

    });

    //To save the menu order
    $("#save_order").click(function () {
        var menuData = [];
        var links = $(".postion_list");
        var validationStatus = true;

        $(links).each(function (index, value) {
            var potionValueStatus = checkIntValue(value);
            if (!potionValueStatus) {
                validationStatus = false;
                return;
            }
            var ele = $(value).html();
            menuData[index] = {
                link_id: $(value).parent().data("link-id"),
                menu_position: $(value).val()
            };
        });

        if (validationStatus) {
            
            validationStatus = checkDuplicateInObject("menu_position", menuData);

            //send the server call
            //validationStatus need to contain FALSE value then no duplicate values exist in array
            if (!validationStatus) {
                menuData = JSON.stringify(menuData);
                sendMenuData(menuData);
            } else {
                $('#card-alert').children().first().html("Duplicate Link Order values exist");
                $('#card-alert').fadeIn("fast").delay(1000).fadeOut("fast");
               
                
            }
        }

        



    });

    $('body').on('focusout', '.postion_list', function () {
        if (!isNaN($(this).val())) {
            console.log("remove");
            if ($(this).next("div").length > 0) {
                $(this).next().remove();
            }
        }

        if (isNaN(parseInt($(this).val()))) {
            var spanEle = "<div class='error'> Invalid number</div>";
            if ($(this).next("div").length == 0) {
                $(this).parent().append(spanEle);
            }

            return false;
        }

    });


});


function checkIntValue(element)
{
    if (isNaN(parseInt($(element).val()))) {
        var spanEle = "<div class='error'> Invalid number</div>";
        if ($(element).next("div").length == 0) {
            $(element).parent().append(spanEle);
        }

        return false;
    }
    return true;
}

function sendMenuData(menuData)
{
    $.ajax({
        type: "POST",
        data: {data: menuData},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: mtcBaseUrl + "/links/save_menu_order",
        success: function (msg) {
            if (msg.success) {
                location.reload();
            } else {
                  $('#card-alert').children().first().prepend("Unable to update the values");
                  $('#card-alert').fadeIn("fast").delay(1000).fadeOut("fast");
            }

        },
        error: function (error) {
            $('#card-alert').children().first().prepend("Unable to update the values");
            $('#card-alert').fadeIn("fast").delay(1000).fadeOut("fast");

        }
    });
}
