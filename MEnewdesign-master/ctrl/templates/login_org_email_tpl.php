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
<script language="javascript">
	function SEdt_validate()
	{
		var Email = document.frmEofMonth.Email.value;
		
		if(Email == '')
		{
			alert('Please Enter Email-Id');
			document.frmEofMonth.Email.focus();
			return false;
		}
		
	}
	
	function conorg()
	{
	 doIt=confirm('Are you Sure you want to Convert?');
  if(doIt){
     email=document.getElementById('Email').value;
    window.location="login_org_email.php?convert=yes&Email="+email+"&submit=Submit&conid=1234";
  }
  	
	}


</script>
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Enter  Email-Id</div>
<div align="center" style="width:100%">&nbsp;</div>
<form action="login_org_email.php" method="post" name="frmEofMonth">
<table width="50%" align="center" class="tblcont">
	<tr>
	  <td width="35%" align="left" valign="middle">Email-Id:&nbsp;<input type="text" name="Email" id="Email" value="<?php echo $Email; ?>"  /></td>
	  <td width="30%" align="left" valign="middle"><input type="submit" name="submit" value="Submit" onclick="return SEdt_validate();" /></td>
	<tr>
</table>

<?php if(count($ResOrgQuery) > 0) { ?>
<table width="100%" align="center" class="sortable">
	<tr>
		
		<td width="40%" align="left" valign="middle" class="tblcont1">Full Name</td>
       	<td width="15%" align="left" valign="middle" class="tblcont1">UserName</td>
		<td width="15%" align="left" valign="middle" class="tblcont1">Reg Date</td>
		<td width="10%" align="left" valign="middle" class="tblcont1">Action</td>
	
    </tr>
	<?php 
		$cnt=1;
		for($i = 0; $i < count($ResOrgQuery); $i++)
		{
	?>
	<tr>
		
		<td align="left" valign="middle" class="helpBod"><?php echo $ResOrgQuery[$i]['name']; ?></td> 	
       	<td align="left" valign="middle" class="helpBod"><?php echo $ResOrgQuery[$i]['username'];?>	</td>
		<td align="left" valign="middle" class="helpBod">
                    <?php 
                        echo $commonFunctions->convertTime($ResOrgQuery[$i]['signupdate'],DEFAULT_TIMEZONE,TRUE);
                        
                        ?> </td>
        <?php
		$window_url =  _HTTP_SITE_ROOT."/api/user/adminSession?organizerId=".$ResOrgQuery[$i]['id'].'&adminId='.$uid;	

		?>
		
		<td align="left" valign="middle" class="helpBod"><a href="#" onclick="window.open('<?=$window_url?>','mywindow','menubar=1,width=900,height=600,resizable=yes,scrollbars=yes');">Edit</a></td>
	
	</tr>
	<?php 
	} //ends for loop
	?>
</table>
<?php 
	} //ends if condition
	else if(count($ResDelQuery) > 0)
	{
?>
	<table width="90%" align="center">
		<tr>
		  <td width="100%" align="center" valign="middle">This Email Id is Registred as Delegate. <a href="<?=_HTTP_SITE_ROOT;?>/change-password?UserType=Delegate&uid=<?=$ResDelQuery[0]['id'];?>&auth_code=<?=$ResDelQuery[0]['auth_code'];?>" target="_blank">click here to login</a> 
<br/><br/> </td>
		 </tr>
	</table>
<?php
	}

	else 
	{
?>
	<table width="90%" align="center">
		<tr>
		  <td width="100%" align="center" valign="middle">No match record found.</td>
		 </tr>
	</table>
<?php
	}
?>

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