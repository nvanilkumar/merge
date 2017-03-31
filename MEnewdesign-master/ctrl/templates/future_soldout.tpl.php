<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - Future Event With Sold Out Tickets</title>
	<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
	<script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>	
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
<!-------------------------------Events of the Month PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans6').style.display='block';
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
<script language="javascript">
	function SEdt_validate()
	{
            var strtdt = document.frmEofMonth.txtSDt.value;
            var enddt = document.frmEofMonth.txtEDt.value;
            if(strtdt == '')
            {
                alert('Please select Start Date');
                document.frmEofMonth.txtSDt.focus();
                return false;
            }
            else if(enddt == '')
            {
                alert('Please select End Date');
                document.frmEofMonth.txtEDt.focus();
                return false;
            }
            else //if(strtdt != '' && enddt != '')
            {   
                var startdate=strtdt.split('/');
                var startdatecon=startdate[2] + '/' + startdate[1]+ '/' + startdate[0];

                var enddate=enddt.split('/');
                var enddatecon=enddate[2] + '/' + enddate[1]+ '/' + enddate[0];

                if(Date.parse(enddatecon) < Date.parse(startdatecon))
                {
                        alert('End Date must be greater then Start Date.');
                        document.frmEofMonth.txtEDt.focus();
                        return false;
                }
            }
	}
</script>
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Future Sold Out Events</div>
<div align="center" style="width:100%">&nbsp;</div>
<form action="" method="post" name="frmEofMonth">
<table width="50%" align="center" class="tblcont">
	<tr>
	  <td width="35%" align="left" valign="middle">Start Date:&nbsp;<input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="35%" align="left" valign="middle">End Date:&nbsp;<input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="30%" align="left" valign="middle"><input type="submit" name="submit" value="Show Events" onclick="return SEdt_validate();" /></td>
	<tr>
</table>

<?php if(count($EventsOfMonth) > 0) { ?>
<table width="100%" align="center" >
	<tr>
		<td width="5%" align="left" valign="middle" class="tblcont1">Sr. No.</td>
		<td width="40%" align="left" valign="middle" class="tblcont1">Event Name</td>
        <td width="2%" align="left" valign="middle" class="tblcont1">Event-Id</td>
		<td width="15%" align="left" valign="middle" class="tblcont1">Start Date</td>
		<td width="15%" align="left" valign="middle" class="tblcont1">End Date</td>
             <td width="10%" align="left" valign="middle" class="tblcont1">Checked</td>
		<td width="20%" align="left" valign="middle" class="tblcont1">Action</td>
		
    </tr>
	<?php 
		$cnt=1;
		for($i = 0; $i < count($EventsOfMonth); $i++)
		{
	?>
	<tr>
		<td align="left" valign="middle" class="helpBod" height="25"><?php echo $cnt++?></td>
		<td align="left" valign="middle" class="helpBod"><?php echo stripslashes($EventsOfMonth[$i]['Title']); ?></td> 	
        <td align="left" valign="middle" class="helpBod"><?php echo $EventsOfMonth[$i]['Id'];?></td> 	
		<td align="left" valign="middle" class="helpBod">
		<?php 
		$StartDt=$EventsOfMonth[$i]['StartDt'];
		$StartDtExplode = explode(" ", $StartDt);//remove time
		$StartDt = $StartDtExplode[0];
		
		$StartDtExplode = explode("-", $StartDt);
		$StartDt = $StartDtExplode[2].'-'.$StartDtExplode[1].'-'.$StartDtExplode[0];
		echo $StartDt; 
		?>
		</td>
		<td align="left" valign="middle" class="helpBod">
		<?php
		$EndDt=$EventsOfMonth[$i]['EndDt'];
		$EndDtExplode = explode(" ", $EndDt);//remove time
		$EndDt = $EndDtExplode[0];
		
		$EndDtExplode = explode("-", $EndDt);
		$EndDt = $EndDtExplode[2].'-'.$EndDtExplode[1].'-'.$EndDtExplode[0];
		echo $EndDt; 
		
		//START window.open URL creation code
		//Please be sure to do any changes.		
		$UserID = $EventsOfMonth[$i]['UserID'];
		//$window_url = _HTTP_SITE_ROOT."/addevent?EventId=".$EventsOfMonth[$i]['Id']."&xy=edit&uid=".$UserID;
		$window_url =  _HTTP_SITE_ROOT."/api/user/adminSession?organizerId=".$UserID."&eventid=".$EventsOfMonth[$i]['Id'].'&adminId='.$uid;
		$eventUrl=_HTTP_SITE_ROOT."/event/".$EventsOfMonth[$i]['URL'];
		//END window.open URL creation code
		?>
		</td>
        <td align="left" valign="middle" class="helpBod"><?php echo $EventsOfMonth[$i]['eChecked']==1?"Checked":"Not checked";?></td>
		<td align="left" valign="middle" class="helpBod"><a href="#" onclick="window.open('<?php echo $window_url;?>','mywindow','menubar=1,width=900,height=600,resizable=yes,scrollbars=yes');">Edit</a> &nbsp; <a href="<?php echo $eventUrl;?>" target="_blank" >Preview</a></td>
		
	</tr>
	<?php 
	} //ends for loop
	?>
</table>
<?php 
	} //ends if condition
	else if(count($EventsOfMonth) == 0)
	{
?>
	<table width="90%" align="center">
		<tr>
		  <td width="100%" align="left" valign="middle">No match record found.</td>
		 </tr>
	</table>
<?php
	}
?>
<script language="javascript" type="text/javascript">
	function updateIsFamous(eId,nStatus)
	{
		var strtdt = document.frmEofMonth.txtSDt.value;
		var enddt = document.frmEofMonth.txtEDt.value;
		window.location = 'events_of_month.php?EventId='+eId+'&newStatus='+nStatus+'&txtSDt='+strtdt+'&txtEDt='+enddt;
	}
</script>
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