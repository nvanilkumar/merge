<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
		<link href="css/menus.css" rel="stylesheet" type="text/css">
		<link href="css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="css/sortable.js"></script>	
        <script language="javascript" src="css/sortpagi.js"></script>	
        <link rel="stylesheet" type="text/css" media="all" href="css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="includes/javascripts/CalendarControl.js"></script>
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
    
    <form action="" method="post" name="frmEofMonth">
    <h3 align="center"> Sales Report by City</h3>
    <table width="60%" align="center" class="tblcont">
	<tr>
	  <td width="33%" align="left" valign="middle">Start Date:&nbsp;
	    <input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="28%" align="left" valign="middle">End Date:&nbsp;
	    <input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
        </tr>
        <tr>
  <td width="28%" align="left" valign="middle">
   Sales Person  : 
      <select name="SalesId" id="SalesId" >
        <option value="">Select</option>
        <?php
		$TotalSalesQueryRES = count($SalesQueryRES);

		foreach($salesPersons as $sales)
		{
		?>
         <option value="<?=$sales['SalesId'];?>" <?php if($sales['SalesId']==$_REQUEST['SalesId']){?> selected="selected" <?php }?>><?=$sales['SalesName'];?></option>
         <?php }?>
      </select></td>
      
      <td>Include Extra Charge <input type="checkbox" name="ExtraCharge" id="ExtraCharge" <?php if(isset($_REQUEST['ExtraCharge'])){ ?> checked="checked" <?php }?> value="1"/> </td>
      
       <td width="33%" align="left" valign="middle">
  <input type="submit" name="submit" value="Show Report" onclick="return SEdt_validate();" /><input type="hidden" name="formSubmit" value="1" /></td>
    </tr>
</table>
</form>
	<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans22').style.display='block';
</script>

<table width="100%">
	<tr><td><?php echo $tableData; ?></td></tr>
    <tr><td><br /><br /></td></tr>
</table>

<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
<div align="center" >
<div id="pieimg" style="float:left"  >
<img style="background-color:#eef2fe" style="-webkit-user-select: none" src="pie.php?hyd=<?php echo round($finalArr['Hyderabad']['totalCardTrAmt'])+round($finalArr['Hyderabad']['totalPACTrAmt'])+round($finalArr['Hyderabad']['totalCODTrAmt'])+round($finalArr['Hyderabad']['totalChqTrAmt']); ?>&mum=<?php echo round($finalArr['Mumbai']['totalCardTrAmt'])+round($finalArr['Mumbai']['totalPACTrAmt'])+round($finalArr['Mumbai']['totalCODTrAmt'])+round($finalArr['Mumbai']['totalChqTrAmt']); ?>&pune=<?php echo round($finalArr['Pune']['totalCardTrAmt'])+round($finalArr['Pune']['totalPACTrAmt'])+round($finalArr['Pune']['totalCODTrAmt'])+round($finalArr['Pune']['totalChqTrAmt']); ?>&bang=<?php echo round($finalArr['Bengaluru']['totalCardTrAmt'])+round($finalArr['Bengaluru']['totalPACTrAmt'])+round($finalArr['Bengaluru']['totalCODTrAmt'])+round($finalArr['Bengaluru']['totalChqTrAmt']); ?>&chen=<?php echo round($finalArr['Chennai']['totalCardTrAmt'])+round($finalArr['Chennai']['totalPACTrAmt'])+round($finalArr['Chennai']['totalCODTrAmt'])+round($finalArr['Chennai']['totalChqTrAmt']); ?>&del=<?php echo round($finalArr['Delhi-NCR']['totalCardTrAmt'])+round($finalArr['Delhi-NCR']['totalPACTrAmt'])+round($finalArr['Delhi-NCR']['totalCODTrAmt'])+round($finalArr['Delhi-NCR']['totalChqTrAmt']); ?>&oth=<?php echo round($finalArr['Others']['totalCardTrAmt'])+round($finalArr['Others']['totalPACTrAmt'])+round($finalArr['Others']['totalCODTrAmt'])+round($finalArr['Others']['totalChqTrAmt']); ?>&rep=tamt">
</div>

<div id="pieimg1"  >
<img style="background-color:#eef2fe" style="-webkit-user-select: none" src="pie.php?hyd=<?php echo round($finalArr['Hyderabad']['totalEvents']); ?>&mum=<?php echo round($finalArr['Mumbai']['totalEvents']); ?>&pune=<?php echo round($finalArr['Pune']['totalEvents']); ?>&bang=<?php echo round($finalArr['Bengaluru']['totalEvents']); ?>&chen=<?php echo round($finalArr['Chennai']['totalEvents']); ?>&del=<?php echo round($finalArr['Delhi-NCR']['totalEvents']); ?>&oth=<?php echo round($finalArr['Others']['totalEvents']); ?>&rep=tevents">
</div>
<div id="pieimg1"  >
<img style="background-color:#eef2fe" style="-webkit-user-select: none" src="pie.php?hyd=<?php echo round($finalArr['Hyderabad']['totalSignedUpUsers']); ?>&mum=<?php echo round($finalArr['Mumbai']['totalSignedUpUsers']); ?>&pune=<?php echo round($finalArr['Pune']['totalSignedUpUsers']); ?>&bang=<?php echo round($finalArr['Bengaluru']['totalSignedUpUsers']); ?>&chen=<?php echo round($finalArr['Chennai']['totalSignedUpUsers']); ?>&del=<?php echo round($finalArr['Delhi-NCR']['totalSignedUpUsers']); ?>&oth=<?php echo round($finalArr['Others']['totalSignedUpUsers']); ?>&rep=deleg">
</div>
	</div>
	</div>
	</td>
  </tr>
</table>

	</div>	
</body>
</html>