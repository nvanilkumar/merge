<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents - Admin -  Upcoming events report</title>
		<link href="css/menus.css" rel="stylesheet" type="text/css">
		<link href="css/style.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" language="javascript" src="<?= _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
        <!--<script language="javascript" src="css/sortable.js"></script>	
        <script language="javascript" src="css/sortpagi.js"></script>-->	
        <link rel="stylesheet" href="<?=_HTTP_SITE_ROOT;?>/css/public/jquery-ui.css" />
        <link rel="stylesheet" href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen" />
	



	<!-- Pick a theme, load the plugin & initialize plugin -->
	<link href="css/theme.default.css" rel="stylesheet">
    <script src="<?=_HTTP_CF_ROOT;?>/js/public/jQuery-ui.js"></script>
	
	
    
    
    
    <script language="javascript">
$(function(){
$('#txtSDt').datepicker({
				minDate: "0",
				defaultDate: "0",
				changeMonth: true,
				changeYear: true,
				numberOfMonths: 1,
				dateFormat: "dd-M-yy",
				onClose: function( selectedDate ) {
				//   $(".from").focus();
				$('#txtEDt').datepicker( "option", "minDate", selectedDate );
			}
		});
		$('#txtEDt').datepicker({
				minDate: "0",
				defaultDate: "0",
				changeMonth: true,
				changeYear: true,
				numberOfMonths: 1,
				dateFormat: "dd-M-yy",
				onClose: function( selectedDate ) {
				//   $(".from").focus();
				$('#txtSDt').datepicker( "option",  selectedDate );
			}
		});
	});

	function SEdt_validate()
	{
		var strtdt = document.frmEofMonth.txtSDt.value;
		var enddt = document.frmEofMonth.txtEDt.value;
		/*
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
		}*/  
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
	</script>
    <script src="css/jquery.tablesorter.min.js"></script>
	<script src="css/jquery.tablesorter.widgets.min.js"></script>
	<script>
	$(function(){
		$('table').tablesorter({
			widgets        : ['zebra', 'columns'],
			usNumberFormat : false,
			sortReset      : true,
			sortRestart    : true
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
    <form action="" method="post" name="frmEofMonth">
    <table width="80%" align="center" class="tblcont" cellpadding="5">
    <tr><td colspan="3" align="center"><h3 style="color:#960">Upcoming Events Report</h3></td></tr>
	<tr>
	  <td align="left" valign="middle">Event End Date from:&nbsp;
	    <input type="text" name="txtSDt" id="txtSDt" value="<?php echo $SDt; ?>"  /></td>
	  <td align="left" valign="middle">Event End Date to:&nbsp;
	    <input type="text" name="txtEDt" id="txtEDt" value="<?php echo $EDt; ?>"  /></td>
        
       <td align="left" valign="middle" class="tblcont">City :
			<select name="selCity" id="selCity">
				<option value="">All Cities</option>
            	<option value="37" <?php if($_REQUEST['selCity']==37) {?> selected="selected" <?php }?>>Bengaluru</option>
                <option value="39" <?php if($_REQUEST['selCity']==39) {?> selected="selected" <?php }?>>Chennai</option>
                <option value="NewDelhi" <?php if($_REQUEST['selCity']=="NewDelhi") {?> selected="selected" <?php }?>>Delhi / NCR</option>
                <option value="Hyderabad"  <?php if($_REQUEST['selCity']=='Hyderabad') {?> selected="selected" <?php }?> >Hyderabad</option>
                <option value="14" <?php if($_REQUEST['selCity']==14) {?> selected="selected" <?php }?>>Mumbai</option>
                <option value="77" <?php if($_REQUEST['selCity']==77) {?> selected="selected" <?php }?>>Pune</option>
                <option value="Goa" <?php if($_REQUEST['selCity']=='Goa') {?> selected="selected" <?php }?>>Goa</option>
                <option value="40" <?php if($_REQUEST['selCity']==40) {?> selected="selected" <?php }?>>Ahmedabad</option>
                <option value="42" <?php if($_REQUEST['selCity']==42) {?> selected="selected" <?php }?>>Kolkata</option>
                <option value="41" <?php if($_REQUEST['selCity']==41) {?> selected="selected" <?php }?>>Jaipur</option>
                <option value="Other" <?php if($_REQUEST['selCity']=="Other") {?> selected="selected" <?php }?>>Other Cities</option>
			</select>
		</td>
    	  
	</tr>
    
    
    <tr>
      <td  valign="middle" class="tblcont">Event Category :
			<select name="selCategory" id="selCategory">
				<option value="">All Categories</option>
                <?php
				foreach($dtlCat as $catKey => $catVal)
				{
					?><option value="<?php echo $catVal['id']; ?>" <?php if($_REQUEST['selCategory']==$catVal['id']) {?> selected="selected" <?php }?>><?php echo $catVal['name']; ?></option><?php
				}
				?>
			</select>
		</td>        
      <td  valign="middle" class="tblcont">Sales Person  : 
      <select name="SalesId" id="SalesId" >
        <option value="">Select</option>
        <?php
		$TotalSalesQueryRES = count($SalesQueryRES);

		for($i=0; $i < $TotalSalesQueryRES; $i++)
		{
		?>
         <option value="<?=$SalesQueryRES[$i]['id'];?>" <?php if($SalesQueryRES[$i]['id']==$_REQUEST[SalesId]){?> selected="selected" <?php }?>><?=$SalesQueryRES[$i]['name'];?></option>
         <?php }?>
      </select>
      </td>
      <td><input type="submit" name="submit" value="Show Report" onclick="return SEdt_validate();" /></td>
      
			</tr>

    <tr><td colspan="3"><br /></td></tr>
</table>
</form>
	<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans22').style.display='block';
</script>
<table align="center" class="tablesorter" style="margin: 10px 0 -1px; width:80%"  border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='5%' align='center'>Sr. No.</td>
			<td class='tblinner' valign='middle' width='25%' align='center'>Event Name & ID</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>Tck Qty</td>
            <td class='tblinner' valign='middle' width='10%' align='center'>Amount (Rs.)</td>
          
          </tr>
        </thead>
        
        <?php	
		$sno=1;	
		
		$totQty=$totAmt=0;
	
	foreach($TransactionRES as $key=>$value)
	{
		$totQty+=$value['Qty'];
		$totAmt+=$value['totalAmt'];
		?>
        <tr>
        	<td align="center"><?php echo $sno; ?></td>
            <td><?php echo $value['title']." (".$value['eventid'].")"; ?></td>
            <td align="center"><?php echo $value['Qty']; ?></td>
            <td align="center"><?php echo round($value['totalAmt'],2); ?></td>
        </tr>
        <?php
		$sno++;
	}

		
	?>
	


  <tr>


  <tr bgcolor="#FFFFFF"><td colspan="2" style="line-height:30px; padding:5px;"><strong>Total :</strong></td><td  width='15%'  align='center'><font color='#000000'><?php echo $totQty;?></font></td><td  align='center'><font color='#000000'><?php echo round($totAmt,2);?></font></td></tr>
  
  
  </table>
  <br /><br /><br />
<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	
	
	</div>
	</td>
  </tr>
  
</table>
	</div>	
</body>
</html>