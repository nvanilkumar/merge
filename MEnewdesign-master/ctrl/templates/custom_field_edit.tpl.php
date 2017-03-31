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
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.min.js.gz"></script>
<script language="javascript">
	function SEdt_validate()
	{
		var EventId = document.frmEofMonth.EventId.value;
		
		if(EventId == '')
		{
			alert('Please Enter Event-Id');
			document.frmEofMonth.EventId.focus();
			return false;
		}
		
	}


</script>
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Enter  Event-Id</div>
<div align="center" style="width:100%">&nbsp;</div>
<form action="" method="post" name="frmEofMonth">
<table width="50%" align="center" class="tblcont">
<?php
	
   for ($cus = 0; $cus < count($ResCustval1); $cus++) 
		{
			$seperator=$cus+1;
		?>
		<tr>
	  <td  align="right" valign="middle">
  <?=$Global->GetSingleFieldValue("SELECT fieldname FROM customfield WHERE id=".$ResCustval1[$cus][customfieldid]);?>
:</td><td><input type="text" height="30" size="50" name="<?=$ResCustval1[$cus]['customfieldid'];?>-<?=$ResCustval1[$cus]['attendeeid'];?>" id="<?=$ResCustval1[$cus]['customfieldid'];?>-<?=$ResCustval1[$cus]['attendeeid'];?>" value="<?=$ResCustval1[$cus]['value'];?>"  /></td>
      </tr>      
	  <?php
	  //echo $seperator."#".$totalCustFileds."#".$seperator%$totalCustFileds."<br>";
	  if(($seperator%$totalCustFileds)==0)
	  {
		 ?><tr><td colspan="2"><hr /></td></tr><?php 
	  }
	  ?>
	  
	  
	  <?php } ?>
    
      <tr>
	  <td  colspan="2" align="center" valign="middle"><input type="submit" name="submit" value="Update"  />&nbsp;<input type="submit" name="Cancel" value="Cancel"  /></td>
	<tr>
</table>




<div align="center" style="width:100%">
<?php
if(count($TransactionRES)>0){ ?>
<?php } ?>
</div>
</form>
<!-------------------------------Events of the Month PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>