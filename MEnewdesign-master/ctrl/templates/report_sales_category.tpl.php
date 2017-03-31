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
    <h3 align="center"> Sales Report by Category</h3>
    <table width="60%" align="center" class="tblcont">
	<tr>
	  <td width="33%" align="left" valign="middle">Start Date:&nbsp;
	    <input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="28%" align="left" valign="middle">End Date:&nbsp;
	    <input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
		 <td align="left" valign="middle" class="tblcont">Event City :
					<select name="selCity" id="selCity">
				<option value="">All Cities</option>
                <?php
				foreach($ctrlCities as $cityData)
				{
					list($cityk,$cityv)=$cityData;
					?>
					<option value="<?php echo $cityk; ?>"  <?php if($_REQUEST['selCity']==$cityk) {?> selected="selected" <?php }?> >
					<?php echo $cityv; ?></option><?php			}
				?>
                <option value="Other" <?php if($_REQUEST['selCity']=="Other") {?> selected="selected" <?php }?>>Other Cities</option>
				</select>
				</td>
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
<div id="pieimg" style="align:center"  >
<img style="background-color:#eef2fe" style="-webkit-user-select: none" src="piecat.php?ent=<?php echo round($finalArr['Entertainment']['totalCardTrAmt']); ?>&pro=<?php echo round($finalArr['Professional']['totalCardTrAmt']); ?>&tra=<?php echo round($finalArr['Training']['totalCardTrAmt']); ?>&cam=<?php echo round($finalArr['Campus']['totalCardTrAmt']); ?>&spi=<?php echo round($finalArr['Spiritual']['totalCardTrAmt']); ?>&trad=<?php echo round($finalArr['Trade Shows']['totalCardTrAmt']); ?>&spo=<?php echo round($finalArr['Sports']['totalCardTrAmt']); ?>&rep=tamt">
</div>

	</div>
	</div>
	</td>
  </tr>
</table>

	</div>	
</body>
</html>