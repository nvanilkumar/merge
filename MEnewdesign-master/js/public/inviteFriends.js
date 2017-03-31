$(function(){   

$('.invitefirends').click(function(e){
		e.preventDefault();
                $("#capthaSrcUrl").attr('src',capthaUrl);
		$('#InviteModal').modal();
	});

$("#invitationFriends").validate({ 
    rules:{
        from_name:{
            required:true
        },
        from_email:{
            required:true,
        },
        to_email:{
            required:true
        },
        captchatext:{
            required:true,
        }
     
    },
     messages: {
            from_name:{
                required : "Can't be empty!"
            },
            from_email: {
                    required: "Can't be empty!",
            },
            to_email:{
                  required: "Can't be empty!",
            },
            captchatext:{
                required: "Can't be empty!",
            }
    }
});

$('#signin_submit').click(function(){
    if($("#invitationFriends").valid() == true){
	var from_name=$('#from_name').val();
	var from_email=$('#from_email').val();
	var to_email=$('#to_email').val();
	var EventId=$('#EventId').val();
     var EventName=$('#EventName').val();
     var EventUrl=$('#EventUrl').val();
     var message = $('#message').val();
	var captchatext=$('#CaptchaText').val();
	var FullAddress=$('#FullAddress').val();
     var DateTime=$('#DateTime').val();
     var refcode = $('input[name="refcode"]').val();
     url = api_eventMailInvitations;
	jQuery.ajax({
      type: "POST",
	 url: url,
	 data:{from_name:from_name,from_email:from_email,to_email:to_email,EventId:EventId,EventName:EventName,EventUrl:EventUrl,message:message,FullAddress:FullAddress,DateTime:DateTime,captchatext:captchatext,refcode:refcode},
	 headers: {'Content-Type': 'application/x-www-form-urlencoded',
		   'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'},
      statusCode: {
         412: function() {
                 $('#errormessages').html("Wrong Captcha" );
         },
         200: function() {
                 $('#messages').html( "Email Sent Successfully" );
               setTimeout(function(){ $('.modal-backdrop').click();},2000);
         }
     }
     
     }); 
   }
       
 });
 $('.orgContact').click(function(e){
		e.preventDefault();
		$('#ContactOrgModal').modal();
	});
 $("#organizerContact").validate({ 
    rules:{
        name:{
            required:true
        },
        email:{
            required:true,
        },
        mobile:{
            required:true,
            number: true
        },
        description:{
            required:true,
        },
        captchatext:{
            required:true,
        }
     
    },
     messages: {
            name:{
                required : "Can't be empty!"
            },
            email: {
                    required: "Can't be empty!",
            },
            mobile:{
                  required: "Can't be empty!",
            },
            description:{
                  required: "Can't be empty!",
            },
            captchatext:{
                required: "Can't be empty!",
            }
    }
});
$('#orgcontact_submit').click(function(){
    if($("#organizerContact").valid()){
        var id = $('#organization').val();
        var name=$('#name').val();
	var email=$('#email').val();
        var mobile=$('#mobile').val();
        var description = $('#description').val();
	var captchatext=$('#CaptchaText').val();
        url = api_getOrgContacts;
        jQuery.ajax({
        type: "POST",
	url: url,
	data:{id:id,from_name:name,from_email:email,mobile:mobile,message:description,captchatext:captchatext,contactorg:1},
	headers: {'Content-Type': 'application/x-www-form-urlencoded',
                   'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'},
        statusCode: {
        412: function() {
                $('#errormessages').html("Wrong Captcha" );
        },
        200: function() {
                $('#messages').html( "Your message has been sent.." );
              setTimeout(function(){ $('.modal-backdrop').click();},2000);
        }
     }
     }); 
    }
});

});