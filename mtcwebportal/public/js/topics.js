$(function () {
    $('#create_topic').validate({
         ignore: [],
        rules: {
            category_id: {
                required: true
            },
            topic_title: {
                required: true
            },
            topic_description: {
                required: true
            }
        },
        messages: {
            category_id: {
                required: "Please select the category name"
            },
            topic_title: {
                required: "Please enter the topic title"
            },
            topic_description: {
                required: "Please enter the topic description"
            }

        },
        errorElement: "div",
        errorPlacement: function (error, element) {
            $(element).addClass("errorTxt2");
            error.appendTo(element.parent());
        }
    });
    
    //Edit user related form fields information
    if (typeof (topicDetails) != "undefined" && topicDetails !== "") {
        
        $("#category_id").val(topicDetails.category_id);
        $("#topic_title").val(topicDetails.topic_title);
        $("#topic_description").html(topicDetails.topic_description);

 
    }


});
 
