<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents - Compare Attendees & Invoice</title>
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
    
    <style>
	.com_table  tr td,th{
		line-height:30px; padding:5px; width:100px;
	}
	
	.com_table .diff{
		background-color:#CCC;
	}
	</style>

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
    <form action="" method="" name="frmEofMonth">
    <table width="60%" align="center" class="tblcont">
    <tr><td align="center" style="line-height:30px; padding:11px 0 0 141px; text-decoration:underline" >Compare Attendees and Invoice</td></tr>

    <tr>
			  
				<td align="left" valign="middle" class="tblcont">Select Time Frame :
					<select name="com_month" id="com_month">
                    <option value="">Select</option>
		<?php
		for($i=1;$i<=12;$i++)
		{
			?>
			<option value="<?php echo $i;?>" <?php if(isset($_GET['com_month']) && $_GET['com_month']==$i){ echo "selected";} ?>><?php echo $i.' month'; ?></option>
			<?php
		}
		?>
					</select>
				</td>
                
                <td align="left" valign="middle" class="tblcont">
                
                <input type="submit" name="submit" value="Show Report"  />
    </td>
			</tr>

    
</table>
</form>
	<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans2').style.display='block';
</script>

<?php if(is_array($newarray) && count($newarray)>0) { ?>

<table     border='1' cellpadding='0' cellspacing='0' class="com_table" style="margin-bottom:100px;" >
 <tr><th>Sno.</th><th>EventId</th><th colspan="2"><table  border='1'><tr><th colspan="2">Attendees</th></tr>
 <tr><th>Qty</th><th>Amt</th></tr></table></th><th colspan="2"><table  border='1'><tr><th colspan="2">Payment Invoice</th></tr>
 <tr><th>Qty</th><th>Amt</th></tr></table></th></tr>
  

<?php
$count=0;
foreach($newarray as $key=>$value)
{
$diff=NULL;
if(isset($value['diff'])){
$diff="class='diff'";
$count++;
}
else
    continue;
echo "<tr $diff><td>$count</td>
		  <td>$key</td>
		  <td>".$value['attendeesqty']."</td>
		  <td>".$value['attendeesamt']."</td>
		  <td>".$value['invoiceqty']."</td>
		  <td>".$value['invoiceamt']."</td> </tr>";	
	
}
?>
  </table>
  
  <?php } ?>
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