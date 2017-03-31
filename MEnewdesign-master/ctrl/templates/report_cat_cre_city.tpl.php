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
	if(strtdt != '' && enddt != '')
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
    <link href="css/jq.css" rel="stylesheet">

	<!-- jQuery: required (tablesorter works with jQuery 1.2.3+) -->
	<script src="css/jquery.min.js"></script>

	<!-- Pick a theme, load the plugin & initialize plugin -->
	<link href="css/theme.default.css" rel="stylesheet">
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
    <form action="" method="post" name="frmEofMonth" onsubmit="return SEdt_validate()">
    <table width="60%" align="center" class="tblcont">
    <tr><td align="center" style="line-height:30px; padding:11px 0 0 141px; text-decoration:underline" >Events Created Count By Category</td></tr>
	<tr>
	  <td width="33%" align="left" valign="middle">Start Date:&nbsp;
	    <input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="28%" align="left" valign="middle">End Date:&nbsp;
	    <input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
    	  
	</tr>
    <tr>
			  
				<td align="left" valign="middle" class="tblcont">City :
					<select name="selCity" id="selCity">
					<option value="">All Cities</option>
			
            <option value="37" <?php if($_REQUEST['selCity']==37) {?> selected="selected" <?php }?>>Bengaluru</option>
                <option value="39" <?php if($_REQUEST['selCity']==39) {?> selected="selected" <?php }?>>Chennai</option>
            
                <option value="NewDelhi" <?php if($_REQUEST['selCity']=="NewDelhi") {?> selected="selected" <?php }?>>Delhi / NCR</option>
                <option value="47"  <?php if($_REQUEST['selCity']==47) {?> selected="selected" <?php }?> >Hyderabad</option>
                <option value="14" <?php if($_REQUEST['selCity']==14) {?> selected="selected" <?php }?>>Mumbai</option>
                
                <option value="77" <?php if($_REQUEST['selCity']==77) {?> selected="selected" <?php }?>>Pune</option>
                <option value="Other" <?php if($_REQUEST['selCity']=="Other") {?> selected="selected" <?php }?>>Other Cities</option>
					</select>
				</td>
                
                <td align="left" valign="middle" class="tblcont">Sales Person  : 
      <select name="SalesId" id="SalesId" >
        <option value="">Select</option>
        <?php
		$TotalSalesQueryRES = count($SalesQueryRES);

		for($i=0; $i < $TotalSalesQueryRES; $i++)
		{
		?>
         <option value="<?php echo $SalesQueryRES[$i]['SalesId'];?>" <?php if($SalesQueryRES[$i]['SalesId']==$_REQUEST[SalesId]){?> selected="selected" <?php }?>><?php echo $SalesQueryRES[$i]['SalesName'];?></option>
         <?php }?>
      </select>
    </td>
			</tr>

    <tr><td>&nbsp;</td><td width="25%" align="left" valign="middle"><input type="submit" name="submit" value="Show Report"  /></td></tr>
</table>
</form>
	<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans22').style.display='block';
</script>

<table     border='1' cellpadding='0' cellspacing='0' >
 <tr><td style="line-height:30px; padding:5px;  width:100px;"><strong>CategoryName</strong></td>
 <?php
 
 foreach($CategoriesRES as $catid=>$catName)
	    { ?>
        <td style="line-height:30px; padding:5px; width:100px;"><?php echo $catName;?></td>
        <?php }?>
        <td style="line-height:30px; padding:5px; width:100px;">Other</td><!-- events that have categoryid=0-->
        </tr>
  <tr><td style="line-height:30px; padding:5px;"><strong>TotalByCategory</strong></td>
  <?php
  $tot=0;
 foreach($CategoriesRES as $key=>$val)
	    {
			if(array_key_exists($val,$TransactionRES)) {$bookedCount=$TransactionRES[$val];}else{$bookedCount=0;}
			?>
        	<td  style="line-height:30px; padding:5px;"><?php echo $bookedCount;?></td>
        	<?php
			$tot=$tot+$bookedCount;		 
	}?>
                <td  style="line-height:30px; padding:5px;"><?php echo $OtherCategoriesCount;?></td>
        </tr>
         <tr><td style="line-height:30px; padding:5px;"><strong>OverAll Total</strong></td>
         <td colspan="<?php echo count($CategoriesRES)?>">&nbsp;</td>
         <td style="line-height:30px; padding:5px;"><?php echo $tot+$OtherCategoriesCount;?></td>
         </tr>
  </table>
<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	
	
	</div>
	</td>
  </tr>
  
</table>
	</div>	
</body>
</html>
<?php
function getorg($oId)
{
	include_once("MT/cGlobal.php");
    $Global = new cGlobal();
	$usql="select UserName,Company from user where Id='".$oId."'";
	$reqRes=$Global->SelectQuery($usql);
	echo $reqRes[0]['Company']."<br/>(".$reqRes[0]['UserName'].")";
}
?>