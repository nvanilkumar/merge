$(document).ready(function () {
//**********************   Start of Affiliate Marketing related js   **********************
var codeCheck=false;

$('#promoButton').click(function(e){
   $('#addPromoterForm').validate();
   if($('#addPromoterForm').valid()){
       codeCheck=true;
       $('#addPromoterForm').submit();
   }else{
       return false;
   }
});
    $('#addPromoterForm').validate({
        rules: {
            promoterName: {
                required: true,
                promoterName: true,
                maxlength: 25
            },
            promoterEmail: {
                required: true,
                email: true
            },
            promoterCode: {
                required: true,
                defaultCheck: true,
                maxlength: 25
            },
			orgPromoteURL: {
                required: false,
                url:true
            }

        },
        messages: {
            promoterName: {
                required: "Please enter promoter name",
                maxlength: "Please enter not more than 25 characters"
            },
            promoterEmail: {
                required: "Please enter promoter email",
                email: "Please enter valid email"
            },
            promoterCode: {
                required: "Please enter promoter code",
                maxlength: "Please enter not more than 25 characters"
            },
			orgPromoteURL: {
                url: "Please enter valid URL"
            }
        }
//        submitHandler: function (form) {
//            if ($(form).valid() && codeCheck)
//            {
//                    form.submit();
//            }
//            return false;
//        }

    });
    
    $('#promoterCode').change(function(){
                var promoterCodeVal=$('#promoterCode').val();               
                if(promoterCodeVal==0 && promoterCodeVal!=''){
                    $('#codeError').text('Promoter code should not be zero');
                    codeCheck=false;
                    return false;
                }
//                else if(promoterCodeVal==''){
//                    $('#codeError').text('Please enter promoter code');
//                    codeCheck=false;
//                    return false;
//                }
                else{
                    $('#codeError').text(' ');
                    codeCheck=true;                    
                }  
    });
    $.validator.addMethod("promoterName", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
    }, "Letters and numbers are allowed");
    
    $.validator.addMethod("defaultCheck", function (value, element){
        var check = false;
        if (value.toLowerCase() == "organizer" || value.toLowerCase() == "meraevents")
        {
            check = false;
        }
        else
        {
            check = true;
        }
        return this.optional(element) || check;
    }, "Organizer, meraevents are predefined words, you cant use them");
    var $time = 1;
    $("#promoterCode,#orgPromoteURL").blur(function(){
        updateLinks();
    });
    $("#promoterCode, #orgPromoteURL").keyup(function () {
        updateLinks();
    });  
        $('.read-more').click(function(){
        $('#moreContent').css('display','block');
        $('.read-more').css('display','none');
        $('.view-less').css('display','block');
    });
    $('.view-less').click(function(){
        $('#moreContent').css('display','none');
        $('.read-more').css('display','block');
        $('.view-less').css('display','none');        
    });
    
    
});


function updateLinks()
{
	
	
	var promoterCode = $("#promoterCode").val(); 
	var orgPromoteURL = $("#orgPromoteURL").val();
	
	 
	if(promoterCode==null || promoterCode.length=='')
	{
		$("#promoterUrl").text(eventURL);
		$("#iframeCode").text("<iframe width=\"100%\" height=\"600px\" src=\""+iframeURL+"\"></iframe>");
	}
	else
	{
		//iframeURL = iframeURL+'?ucode='+promoterCode;
		
		if(orgPromoteURL.length >0){
			
			var URLSeperater = "&";
			console.log(orgPromoteURL.indexOf("?"));
			if(orgPromoteURL.indexOf("?") < 0){
				URLSeperater = '?';
			}
			
			promoterUrl = orgPromoteURL+URLSeperater+'meprcode='+promoterCode;
			promoterUrl = promoterUrl+'\n(or)\n';
			promoterUrl = promoterUrl+eventURL+'?ucode='+promoterCode;
			
			
		}
		else{
			promoterUrl = eventURL+'?ucode='+promoterCode;
		}
		iframeCode = iframeURL+'&ucode='+promoterCode;
		
		$("#promoterUrl").text(promoterUrl);
		$("#iframeCode").text('<iframe width="100%" height="600px" src='+iframeCode+'></iframe>');
	}
}


//**********************   End of Affiliate Marketing related js   **********************    
    // viral ticket realted js
    function getcheckedcommissions(){
    	 var rules = new Object();
         var messages = new Object();
        var newmessagevalid=[];       
    	$('.enableDisable').each(function(){
    		var salesDone= $(this).attr('salesDone');
            if(!$(this).is(':checked') || salesDone ==1){
            	var $inputs = $(this).parents('tr').find('input[type="text"]');
    		$.each($inputs,function(key,val){
    			if(val.value.length <=0 || salesDone==1){
    				$(val).attr('disabled','disabled');
    			}else{
    				$(val).removeAttr('disabled');
    			}
    		});
    		if(salesDone == 1){
    		 $(this).parents('tr').find('input[type="radio"]').attr('disabled','disabled');
    		}
    		
    	}else{
    		$(this).parents('tr').find('input[type="text"]').removeAttr('disabled');
    		$(this).parents('tr').find('input[type="radio"]').removeAttr('disabled');
    	}
    		var thisname = $(this).attr('name');
			thisname = thisname.replace('status','');
			var checkname = parseInt(thisname,10);
    		if($(this).is(':checked')){
        		var refcom = $('#referrercommission'+checkname).val();
        		var refname = $('#referrercommission'+checkname).attr('name');
        		var reccom = $('#receivercommission'+checkname).val();
        		var recname = $('#receivercommission'+checkname).attr('name');
       			newmessagevalid.push(refname);
       			newmessagevalid.push(recname);
                } 
    	});       
    	  // Common fields validations starts here /
        $('.mandatory_class').each(function () {
        	var mn = this.name;
        	if ($.inArray(mn, newmessagevalid) != -1){
        		rules[this.name] = { 
                            required: true,
                            number:true,
                            checkComm : true
                        };
                        messages[this.name] = {min: "Commission value should be greater than zero",required: "Commission value is Required",checkComm:"Commission should not be greater than 100%"};
        	}
        });
        $.validator.addMethod('checkComm',function (value,element) {
            var success = true;
            var ticketId = Number(element.id.match(/\d+/));
            var refcom = $('#referrercommission'+ticketId).val();
            var reccom = $('#receivercommission'+ticketId).val();
                if($('input[name="type'+ticketId+'"]:checked').val() == "percentage" && (Number(refcom) + Number(reccom)) > 100){
                    success = false;
                }
                return success;
            });
    	var form = $('#viralTicket').get(0);
    	$.removeData(form,'validator');
        $("#viralTicket").validate({
            rules: rules,
            messages: messages
        });       
    } 
    getcheckedcommissions();  
    $('.enableDisable').click(function(){
    	 getcheckedcommissions();    	
    });
    function changeStatus(id)
    {
    var url = api_promotesetStatus;
    $.ajax({
        url: url,
        type: "POST",
        data: {id: id},
        headers: {'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'},
        cache: false,
        dataType: 'json',
        success:
                function (data, status, x) {
                    var promoterStatusValue = data.response.promoterStatus;
                    console.log(promoterStatusValue);
                    if (promoterStatusValue == 1) {
                        $("#" + id).removeClass('btn orangrBtn');
                        $("#" + id).addClass('btn greenBtn');
                        $("#" + id).text('active');
                    } else {
                        $("#" + id).removeClass('btn greenBtn');
                        $("#" + id).addClass('btn orangrBtn');
                        $("#" + id).text('inactive');
                    }
                },
        error: function (data, x, y) {
            console.log(data);
        }
    });
    }
    
    function deleteDiscount(Url)
    {
	var r = confirm("Are you sure you want to delete this discount!!!");
	if(r == true)
	{
		window.location=Url;	
	}
    }
$(function(){
   var idslist = JSON.parse($('#radioFieldname').val());
$.each(idslist, function (key2, custid){
    customRadio("type"+custid);
}); 
});
