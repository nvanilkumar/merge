	  $(document).on('change','#printpass-form',function(e){
		  var targetfield = e.target.name;
		  var regno = $('#userreg_no').val();
		  var useremail = $('#userEmail').val();
		  var emailreg =  /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		  var numRegExp = /^[0-9]+$/;
		  var emailemptymsg = "Email is Required";
		  var emailvalidmsg = "Email is not valid ";
		  var regemptymsg = "Reg No. is required";
		  var regvalidmsg = "Reg No. is not Valid";
		 switch(targetfield){ 
		 case 'useremail':
		  if(useremail == ''){
			  $('.useremail').html(emailemptymsg);
		  }else if(!emailreg.test(useremail)){
			  $('.useremail').html(emailvalidmsg);
		  }else{
			  $('.useremail').html('');
		  }
		  break;
		 case 'regno':
		  if(regno == ''){
			  $('.regno').html(regemptymsg);
		  }else if(!numRegExp.test(regno)){
			  $('.regno').html(regvalidmsg);
		  }else{
			  $('.regno').html('');
		  }
		  break;
		  default:
			  break;
		 }
		
	  });
	  
	  $(document).on('submit','#printpass-form',function(e){
		  var regno = $('#userreg_no').val();
		  
		  var useremail = $('#userEmail').val();
		  var emailemptymsg = "Email is Required";
		  var regemptymsg = "Registration Number is required";
			  if( useremail == ''){
				  $('.useremail').html(emailemptymsg);
			  }
			  if(regno == ''){
				  $('.regno').html(regemptymsg);
			  }
			  if($('.regno').html() != '' ||  $('.useremail').html() != '' ){
				  return false;
			  }
			
		  
		  
	  });
	  
	  $(document).on('click','.senddelemail',function(e){
		 var eventsignupId = $('#userreg_no').val() || $("#regno_get").val();
		  var userEmail = $('#sentuseremail').val() || $("#email_get").val();
		  var emailreg =  /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		  if(userEmail == ''){
			  $('#sentuser').html("Please Enter the Email");
		  }else if(!emailreg.test(userEmail)){
			  $('#sentuser').html("Please Enter Valid Email");
		  }else{
			  $('#sentuser').html('');
		  }
		  if($('#sentuser').html() == '' ){
			  $("#sendsuccess").html('<img id="success-img" src="../images/me-loading.gif">');
		  $.ajax({
			 type:'get',
			 url:api_emailPrintpass,
			 data:{eventsignupId:eventsignupId,userEmail:userEmail},
			 success:function(res){
				 $("#sendsuccess").html(res.response.messages);
				 setTimeout(function(){$("#sendsuccess").html('');},20000);
			 },
			 error:function(res){
				 $("#sendsuccess").html(res.responseJSON.response.messages);
				 setTimeout(function(){$("#sendsuccess").html('');},20000);
			 },
		  });
		  }else{
			  return false;
		  }
		  
	  });
	  
	  

