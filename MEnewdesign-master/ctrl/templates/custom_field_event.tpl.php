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
<script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.min.js.gz"></script>
<script language="javascript">
	function SEdt_validate()
	{
		var EventId = document.frmEofMonth.EventId.value;
		var regno = document.frmEofMonth.regno.value;
		
		if(EventId == '')
		{
			alert('Please Enter Event-Id');
			document.frmEofMonth.EventId.focus();
			return false;
		}
                else if($.isNumeric(EventId) & EventId >= 0) {
            $.get('includes/ajaxSeoTags.php', {eventIDChk: 0, eventid: EventId}, function (data) {
                                        if (data == "error")
                                        {
                                            alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
                                            document.getElementById('EventId').focus();
                                            return false;

                }
        });}
		if(regno == '')
		{
			alert('Please Enter Registration Number');
			document.frmEofMonth.regno.focus();
			return false;
		}
		
	}


</script>
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Enter  Event-Id</div>
<div align="center" style="width:100%">&nbsp;</div>
<form action="" method="post" name="frmEofMonth">
<table width="29%" align="center" class="tblcont">
	<tr>
	  <td width="35%" align="left" valign="middle">Event-Id:&nbsp;    <input type="text" name="EventId" id="EventId" value="<?php echo $_REQUEST['EventId']; ?>"  /></td>
      </tr><tr>
      <td width="35%" align="left" valign="middle">Regno:&nbsp;<input type="text" name="regno" id="regno" value="<?php echo $_REQUEST['regno']; ?>"  /></td>
      </tr><tr>
	  <td width="30%" align="center" valign="middle"><input type="submit" name="submit" value="Submit" onclick="return SEdt_validate();" /></td>
	<tr>
</table>




<div align="center" style="width:100%">
<?php 
if(isset($_REQUEST['submit'])|| $_REQUEST['export']|| $_REQUEST['EventId']!="")
{
	if(count($finalArray)>0){ ?>
	  <table width="100%" border="1" cellspacing="2" cellpadding="2">
		<tr>
		  <th>Receipt No</th>
		  <th>Signup Date</th>
		   <th>Ticket Type</th>
		  <th>Transaction/Cheque No.</th>
		  <th>PromotionCode</th>
		  <th>PaymentStatus</th>
		  <th>Name</th>
		  <th>Email</th>
			<th>Company</th>
			<th>Phone No.</th>
			<th>Amount</th>
			<th>Paid</th>
			<th>Action</th>
		<?php
		 for($cou = 0; $cou < count($finalArray); $cou++)
			{ 
			if($cou%2==0){ $cl="Even"; } else { $cl="Odd";}
			?>
				<tr class="OrganizaerTableTd<?=$cl;?>">
					<td><?php echo $finalArray[$cou]['EventSIgnupId']; ?></td>
					<td><?php echo $finalArray[$cou]['SignupDt']; ?></td>
					<td><?php echo $finalArray[$cou]['TicketType'];?></td>
					<td><?php echo $finalArray[$cou]['PaymentTransId']; ?></td>
					<td><?php echo $finalArray[$cou]['PromotionCode']; ?></td>
					<td><?php echo $finalArray[$cou]['eChecked']; ?></td>
					<td><?php echo $finalArray[$cou]['Name']; ?></td>
					<td><?php echo $finalArray[$cou]['Email']; if($_REQUEST['EventId']==22357){ echo $finalArray[$cou]['field1']."<br/>".$finalArray[$cou]['field2']."<br/>".$finalArray[$cou]['field3']."<br/>".$finalArray[$cou]['field4'] ; } ?></td>
					<td><?php echo $finalArray[$cou]['Company']; ?></td>
					<td><?php echo $finalArray[$cou]['Phone']; ?></td>
					<td><?php echo round($finalArray[$cou]['Amount'],2); ?></td>
					<td><?php echo round($finalArray[$cou]['Paid'],2); ?></td>
					<td><a href="custom_field_edit.php?EventSignupId=<?=$finalArray[$cou]['EventSIgnupId'];?>&EventId=<?=$eventId;?>&regno=<?=$lastRegNo;?>">Edit</a></td>
					 </tr>
			  <?php  
			  }
	?>
	
		<tr><td>
		   <input type="submit" name="export" value="Export"  onclick="return SEdt_validate();" />
		
		</td></tr>
	  </table>
	  <?php }
	  else{
		  ?>
		  <table width="100%" border="1" cellspacing="2" cellpadding="2">
			  <tr>
				<th>Sorry, No records found.</th>
			  </tr>
		  </table>
		  <?php
	  }
}
?>
  </div> </form>
<!-------------------------------Events of the Month PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>