<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - Organizer Login</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	
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
  	document.getElementById('ans7').style.display='block';
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.min.js.gz"></script>
 <script language="javascript" src="<?=_HTTP_SITE_ROOT;?>/js/public/jQuery.js"></script>    
<script language="javascript">
	function SEdt_validate()
	{
		var EventId = document.frmEofMonth.EventId.value;
		
		if(EventId.trim() == '')
		{
                    alert('Please Enter Event-Id');
                    document.frmEofMonth.EventId.focus();
                    return false;
                }else if(isNaN(EventId.trim())){
                    alert('Please Enter vaild Event-Id');
                    document.frmEofMonth.EventId.focus();
                    return false;
                }
	}
        $(document).ready(function(){
                  
        });

</script>
 
<div align="center" style="width:100%" class="headtitle">Enter  Event-Id</div>
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%"><?php if(isset($_SESSION['not_sent']) && strlen($_SESSION['not_sent'])>0){ unset($_SESSION['not_sent']);echo "Mail not sent to ".substr($_SESSION['not_sent'],0, -1).".";}else if(isset($_SESSION['not_sent'])){ unset($_SESSION['not_sent']);echo "Sent mail successfully";}?></div>
<form action="" method="post" name="frmEofMonth" id="frmEofMonth">
<table width="50%" align="center" class="tblcont">
	<tr>
	  <td width="35%" align="left" valign="middle">Event-Id:&nbsp;<input type="text" name="EventId" id="EventId" value="<?php echo $EventId; ?>"  /></td>
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