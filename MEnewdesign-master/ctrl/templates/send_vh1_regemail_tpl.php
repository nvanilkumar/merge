<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - Send registration mails</title>
	<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	
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
				<div id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------Events of the Month PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans5').style.display='block';
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
 <script language="javascript" src="<?php echo _HTTP_SITE_ROOT;?>/js/jquery.1.7.2.min.js"></script>    
<script language="javascript">
	function SEdt_validate()
	{
		var esid = document.frmEofMonth.esid.value;
		var delEmail=document.frmEofMonth.delEmail.value;
                 var pattern=/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		if(esid.trim() == '')
		{
                    alert('Please Enter registration number');
                    document.frmEofMonth.esid.focus();
                    return false;
                }else if(isNaN(esid.trim())){
                    alert('Please Enter vaild registration number');
                    document.frmEofMonth.esid.focus();
                    return false;
                }
                
                if(delEmail.trim()==''){
                    alert('Please Enter email id');
                    document.frmEofMonth.delEmail.focus();
                    return false;
                }else if(!pattern.test(delEmail.trim())){
                    alert('Please Enter vaild email id');
                    document.frmEofMonth.delEmail.focus();
                    return false;
                }
                
	}
        $(document).ready(function(){
                  
        });

</script>
 
<div align="center" style="width:100%" class="headtitle">Re-send Vh1 Registration mails</div>
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%"><?php if(isset($_SESSION['status'])){ echo $_SESSION['status'];unset($_SESSION['status']);}?></div>
<form action="" method="post" name="frmEofMonth" id="frmEofMonth">
<table width="50%" align="center" class="tblcont">
	<tr>
	  <td width="35%" align="left" valign="middle">Registration number:&nbsp;<input type="text" name="esid" id="esid" value="<?php echo $esid; ?>"  /></td>
           <td width="35%" align="left" valign="middle">Email Id:&nbsp;<input type="text" name="delEmail" id="delEmail" value="<?php echo $delEmail; ?>"  /></td>
          <td width="30%" align="left" valign="middle"><input type="submit" name="sendRegMails" id="sendRegMails" value="Submit" onclick="return SEdt_validate();" /></td>
	<tr>
</table>

</form>
<div align="center" style="width:100%">&nbsp;</div>
<!-------------------------------Events of the Month PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>