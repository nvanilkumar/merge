$('#resetpwdForm').validate({ 
    
    rules: {
        forgotuniqueEmail: {
            required: true,
            uniqueEmailValidate:true,
            uniqueEmail:true
        }
    },
    messages: {
        forgotuniqueEmail:{
            required: 'please enter uniqueEmail',
            uniqueEmailValidate:'please enter valid uniqueEmail',
           uniqueEmail:'Please enter registered uniqueEmail'
        }
    }
  });
  
$('#ResetPassword').click(function(e){
    if ($("#resetpwdForm").valid() == true ) {
      
        $('#message').text('Mail Sent Successfully. Please check your inbox').css('color', 'green');
    }
}); 
 

$.validator.addMethod("uniqueEmail", function(value, element) {
     var isSuccess = false;
    $.ajax({
        type: "POST",
        url: api_userGetUserData,
        headers: {'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'},        
        data: "uniqueEmail=" + value,
        cache: false,
        async:false,
        dataType: 'json',
        success: function(result)
        {
            if(msg === true || msg == 1) { 
                isSuccess  =  true;
            
            } else {
                isSuccess = false; 
            }
               
        }
    });
   // console.log(isSuccess);
    return isSuccess;
});

  $.validator.addMethod("uniqueEmailValidate", function(value,element) {
    var uniqueEmailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    //console.log(uniqueEmailPattern.test(value));
    return uniqueEmailPattern.test(value); 
});