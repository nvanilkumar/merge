$(function () {
/**
 * Form Vaidations
 */
 $('#create_category').validate({
        rules: {
            category_name: {
                required: true
            }
        },
        messages: {
            category_name: {
                required: "Please enter the category name"
            }

        },
        errorElement: "div",
        errorPlacement: function (error, element) {
            $(element).addClass("errorTxt2");
            error.appendTo(element.parent());
        }
    });
    
    
    //edit form 
    //Edit user related form fields information
    if (typeof (categoryDetails) != "undefined" && categoryDetails !== "") {
        $("#category_name").val(categoryDetails.category_name);
      
    }


});