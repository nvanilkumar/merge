<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>MeraEvents - Admin Panel - Manage SEO Data</title>
    <link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
    <link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
    <script language="javascript" src="<?php echo _HTTP_SITE_ROOT;?>/js/jquery.1.7.2.min.js"></script>    
    <script language="javascript" src="<?php echo _HTTP_SITE_ROOT;?>/js/jquery.validate.js"></script>
<script>
$(function(){
	$("#addorganizerForm").validate({
		rules: {
			emailId:{
				required:true,
				email:true
			}
		},
		messages:{
			emailId:{
				required:"please enter email id",
				email:"Please enter valid email id"
			}
		},
		errorPlacement: function(error, element) {
			var x=element.attr("name")+"Err";
			$("#"+x).html(error);
		}, 
		errorElement: "span",
		debug:true,
		submitHandler: function(form) {  
		if ($(form).valid()) 
		{
			var email=$("#emailId").val();
			$.ajax({
			url:'<?php echo _HTTP_SITE_ROOT;?>/processAjaxRequests.php',
			type:'POST',
			data:{
				email:email,
				call:'isOrganizer'
			},
			success: function(res){
				var newres=JSON.parse(res);
				if(!newres['isUser']){
					$("#emailIdErr").html("Please enter email id of existing user only");
				}else if(newres['isOrganizer']){
					$("#emailIdErr").html("This user is already an organizer");
				}else{
					form.submit();
				}
				
			}
		});		
		}
		return false; // prevent normal form posting
		}
	});
});
</script>   
</head>    
<body style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
    <?php include('templates/header.tpl.php'); ?>                
    </div>
    <table style="width:100%; height:495px;" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
                <?php include('templates/left.tpl.php'); ?>
            </td>
            <td style="vertical-align:top">
                <div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------SEO types PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
      document.getElementById('ans20').style.display='block';
</script>

<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Convert to organizer</div>
<div>
<?php if(isset($_SESSION['addStatus']) && $_SESSION['addStatus']){ 
	unset($_SESSION['addStatus']);
?>
<div style="color:#060">Added organizer successfully</div>
<?php } ?> 
<form action="#" name="addorganizerForm" id="addorganizerForm" method="post">
	<label for="emailId">Convert the Email Id to Organizer:</label>
    <input type="text" name="emailId" id="emailId" value="" />
    <input type="hidden" name="org" id="org" value="1" />
    <input type="submit" name="addOrganizer" value="Submit" />
   	<p style="font-size:12px; padding:0">
         <label style=" padding: 0 95px;"></label>
         <span style="color:#F00;" id="emailIdErr"></span>
    </p>
</form>
</div>

<div align="center" style="width:100%">&nbsp;</div>

<div align="center" style="width:100%">&nbsp;</div>
<!-------------------------------SEO types PAGE ENDS HERE--------------------------------------------------------------->
                </div>
            </td>
        </tr>
    </table>
</div>    
</body>
</html>
