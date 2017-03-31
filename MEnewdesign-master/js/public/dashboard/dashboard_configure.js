$(document).ready(function () {	
//webhook related js    
    $('#webhookUrlForm').validate({
        rules: {
            webhookUrl: {
                required: true,
                weburl: true
            }
        },
        messages: {
            webhookUrl: {
                required: "Please enter web hook url",
                weburl: "Enter valid url"
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
//seo related js    
    $('#seoForm').validate({
        rules: {
            conanicalurl: {
                required: false,
                weburl: true
            }
        },
        messages: {
            conanicalurl: {
                required: "Please enter Conanical Url",
                weburl: "Enter valid url"
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    jQuery.validator.addMethod("seotitle", function (value, element) {
        // allow any non-whitespace characters as the host part
        return this.optional(element) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@(?:\S{1,63})$/.test(value);
    }, 'Please enter a valid email address.');
    
     jQuery.validator.addMethod("weburl", function (value, element) {
        // allow http://,https://,www
        return this.optional(element) || /(https?:\/\/(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,}|www\.[^\s]+\.[^\s]{2,})/.test(value);
    }, 'Please enter a valid url.');

// ticket widget
    $('#ticketwidget').validate({
        rules: {
            redirect_url: {
                weburl: true
            }
        },
        messages: {
            redirect_url: {
                weburl: "Enter valid url"
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
//ticket_widget related js
    $('#widget_button').click(function () {
        var event_title_color = $('#event_title_color').val();
        var heading_bg_color = $('#heading_bg_color').val();
        var ticket_txt_color = $('#ticket_txt_color').val();
        var book_bt_color = $('#book_bt_color').val();
        var iframe_height = $('#iframe_height').val();
        var redirect_url = $('#redirect_url').val();
        var iframe_url = $('#url_val').val();
        if (!ifr_height(iframe_height))
        {
            alert("Please enter valid iframe height value");
            $('#iframe_height').focus();
        } else {
            //checking redirect url
//	    if(redirect_url.length > 0)
//             {
//		if(!is_valid_url(redirect_url))
//		{
//                    alert("Please enter valid redirect url");
//                    $('#redirect_url').focus(); 
//                    return false;
//		}
//            }
            //prepraing the color code variables
            var wcode = iframe_url + "&wcode=" + event_title_color + "-" + heading_bg_color + "-" + ticket_txt_color + "-" + book_bt_color + "-&widgetRedirectUrl=" + encodeURIComponent(redirect_url);
            var text_area_value = ' <iframe src="' + wcode + ' " width="100%" height="' + iframe_height + '" ></iframe>'
            $('#text_area').val(text_area_value);
            $("#widget_frame").removeAttr('height');
            $("#widget_frame").css('height', iframe_height).attr("src",wcode);
        }
    });

    function ifr_height(frame_height)
    {
        if ((!isNaN(frame_height)) || (frame_height.length == 0))// height value is numeric
        {
            if ((frame_height > 400) && (frame_height < 2000))// valid iframe height range 400 - 2000
                return true;
            else
                return false;
        } else { //height value is non numeric
            return false;
        }
    }

//    function is_valid_url(url)
//    {
//	return url.match(/^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/);
//    } 
/// Common fields validations ends here /

    //Email Attendees related js
    $('#emailAttendeesForm').validate({
        rules: {
            userName: {
                required: true
            },
            replyEmail: {
                required: true,
                email: true
            },
            subject: {
                required: true,
            }
        },
        messages: {
            userName: {
                required: "Please enter user name"
            },
            replyEmail: {
                required: "Please enter reply email",
                email: "Please enter email"
            },
            subject: {
                required: "Please enter subject"
            }
        },
        submitHandler: function (form) {
            if ($(form).valid()) {                
                if($("#messageError").text() || $("#testMailError").text()) {
                    return false;
                } else {
                    form.submit();
                }               
            }
        }
    });

    $('#emailAttendeesSendTestMail').click(function () {
        if ($('#testMail').val()) {
            $("#testMailError").text("");            
        } else {
            $("#testMailError").text("Please enter test mail");
        }
    });
    
    $('#emailAttendeesSendMail').click(function () {
            $("#testMailError").text("");
    });
    //Gallery related js
   $('#gallerySubmit').click(function(){
        if($('#eventGallery').val()){
            $('#galleryNoSelectError').text(' ');
        }else{
           $('#galleryNoSelectError').text('Please select atleast one image');
           return false;
        }
   });
      $('#eventGallery').click(function(){
            $('#galleryNoSelectError').text(' ');     
   });
   

 // <-- time in milliseconds
    //Contact information related js
    $("#eventContactInfoForm").validate({
		onkeyup: function(element) { $(element).valid(); },
		rules: {
			namesPhoneEmail:{
                            required:true,
                            minlength:5
                        },
                        eventWebUrl: {
                        weburl: true
                            },
                             eventFBUrl: {
                        weburl: true
                            }
		},
		messages:{
                eventWebUrl: {
                                weburl: "Enter valid url"
                            },
                              eventFBUrl: {
                                weburl: "Enter valid url"
                            }
		},
		submitHandler: function(form) {  
		if ($(form).valid()) 
		{
			form.submit();			
		}
		return false; // prevent normal form posting
		}		
	});

//payment mode related js
$('#paymentSubmit').click(function(){
    $('#paymentErrorMessage').text(' ');
    var n=$('input:checked').length;
    if(n==0){
        $('#paymentErrorMessage').text('Select atleast one payment gateway');
        return false;
    }
});
});

