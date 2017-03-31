<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Events Score Board</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>
    <link rel="stylesheet" type="text/css" href="<?=_HTTP_CF_ROOT;?>/css/me_customfieldsnew.min.css.gz" />
<?php include '../includes/include_js_css.php'; ?>
<link rel="stylesheet" type="text/css" href="<?=_HTTP_CF_ROOT;?>/css/rating_style.css" media="all">
<script language="javascript" type="text/javascript" src="<?=_HTTP_CF_ROOT;?>/scripts/me_customfields.min.js.gz"></script>

<script language="javascript" type="text/javascript" src="<?=_HTTP_CF_ROOT;?>/scripts/livevalidation.min.js.gz"></script>
<script language="javascript" type="text/javascript" src="<?=_HTTP_SITE_ROOT?>/ajaxdaddy/prototype.ja"></script>
<script language="javascript" type="text/javascript" src="<?=_HTTP_SITE_ROOT?>/ajaxdaddy/effects.ja"></script>
<script language="javascript" type="text/javascript" src="<?=_HTTP_SITE_ROOT?>/ajaxdaddy/window.js"></script>
<script language="javascript" type="text/javascript" src="<?=_HTTP_SITE_ROOT?>/ajaxdaddy/window_effects.js"></script>
<script language="javascript" type="text/javascript" src="<?=_HTTP_CF_ROOT;?>/scripts/datetimepicker_css.min.js.gz"></script>
<script language="javascript" type="text/javascript" src="<?=_HTTP_CF_ROOT;?>/scripts/calendar.min.js.gz"></script>
 <script>
 function orgdisp(val)
 {
 var profile_pstate=document.getElementById('profile_pstate').value;
 var profile_pcity=document.getElementById('profile_pcity').value;
 var startdate=document.getElementById('startdate').value;
 var enddate=document.getElementById('enddate').value;
  window.location="admindashboard.php?profile_pstate="+profile_pstate+"&profile_pcity="+profile_pcity+"&SerEventName="+val;
 }
 function getCity(val)
 {
 var SerEventName=document.getElementById('SerEventName').value;
 var profile_pcity=document.getElementById('profile_pcity').value;
 var startdate=document.getElementById('startdate').value;
 var enddate=document.getElementById('enddate').value;
  window.location="admindashboard.php?profile_pstate="+val+"&profile_pcity=&SerEventName="+SerEventName;
 }
 function getGraph(val)
 {
 var profile_pstate=document.getElementById('profile_pstate').value;
 var SerEventName=document.getElementById('SerEventName').value;
 var profile_pcity=document.getElementById('profile_pcity').value;
 var startdate=document.getElementById('startdate').value;
 var enddate=document.getElementById('enddate').value;
 window.location="admindashboard.php?profile_pstate="+profile_pstate+"&profile_pcity="+val+"&SerEventName="+SerEventName;
 }
 </script>
<script type="text/JavaScript">

function timedRefresh(timeoutPeriod) {
	//setTimeout("location.reload(true);",timeoutPeriod);
}
</script>
<script language="javascript" type="text/javascript">
function valDashboardDts()
{
	var sdate = document.getElementById('startdate').value;
	var edate = document.getElementById('enddate').value;
	
	var arraySDate = new Array(); 
	arraySDate = sdate.split(" ");
	
	var splarraySDate = arraySDate[0].split("-");
	var newSDate = splarraySDate[2]+'/'+splarraySDate[1]+'/'+splarraySDate[0];
			
	var arrayEDate = new Array(); 
	arrayEDate = edate.split(" ");
	
	var splarrayEDate = arrayEDate[0].split("-");
	var newEDate = splarrayEDate[2]+'/'+splarrayEDate[1]+'/'+splarrayEDate[0];

	var parseSDate = Date.parse(newSDate);
	var parseEDate = Date.parse(newEDate);

	var today = new Date();
	var parseToday = Date.parse(today);

	if(isNaN(parseSDate))
	{
		alert("Please enter Start Date");
		document.getElementById('startdate').focus();
		return false;
	}
	
	else if(isNaN(parseEDate))
	{
		alert("Please enter End Date");
		document.getElementById('enddate').focus();

		return false;
	}
	else if(parseEDate < parseSDate)
	{
		alert("Start Date should be less than End Date");
		document.getElementById('startdate').focus();
		return false;
	}
}
</script>
</head>	
<body onload="JavaScript:timedRefresh(180000);" style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
	<?php include('templates/header.tpl.php'); ?>				
	</div>
	<table style="width:100%; height:495px;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
				<?php include('templates/left.tpl.php'); ?>
			</td>
			<td style="vertical-align:top">
            <div align="center">State <select tabindex="13" name="profile_pstate" style="width:150px;" class="form-select required" id="profile_pstate" onChange="getCity(this.value)">
							<option value="" >--Select State--</option>
							<?php 
				
								$selState = "SELECT Id, State FROM States WHERE CountryId='14'";
														$States = $Global->SelectQuery($selState);
			
								for($i = 0; $i < count($States); $i++)
								{
							?>
								<option value="<?=$States[$i]['Id']?>" <?php if($_REQUEST['profile_pstate']==$States[$i]['Id']) { ?> selected="selected" <?php } ?>><?=$States[$i]['State']?></option>
							<?php
								}
							
							?>
								</select> &nbsp; &nbsp; City <select tabindex="14" name="profile_pcity" style="width:150px;" class="form-select required" id="profile_pcity" onchange="getGraph(this.value)">
								   <option value="" >--Select City--</option>
								<?php 
							    if($_REQUEST[profile_pstate]!="")
								{
								 $stateid=$_REQUEST[profile_pstate];
								 }else{
								  $stateid=1;
								 }
								$selCity = "SELECT Id, City FROM Cities WHERE StateId=".$stateid;
								$Cities = $Global->SelectQuery($selCity);
			
								for($i = 0; $i < count($Cities); $i++)
								{
							?>
								<option value="<?=$Cities[$i]['Id']?>" <?php if($_REQUEST['profile_pcity']==$Cities[$i]['Id']) { ?> selected="selected" <?php } ?>><?=$Cities[$i]['City']?></option>
							<?php
								}
							
							?>
								  </select>&nbsp;&nbsp;  ByOrganizer   <select tabindex="86" name="SerEventName" id="SerEventName" onchange="orgdisp(this.value);" class="adTextFieldd" style="width:250px;">
				<option value="">Select Organizer Name</option>	
				<?php 
				$SelectOrgNames1="SELECT orgDispName, Id FROM orgdispname where Active=1  ORDER BY orgDispName ASC";
                $OrgNames1=$Global->SelectQuery($SelectOrgNames1);
                $TotalOrgNames1=count($OrgNames1);
                for($i=0;$i<$TotalOrgNames1;$i++)
                {
                ?>
                <option value="<?php echo $OrgNames1[$i]['Id'];?>" <?php if($OrgNames1[$i]['Id'] == $_REQUEST['SerEventName']) { ?> selected="selected" <?php } ?>><?php echo $OrgNames1[$i]['orgDispName']; ?></option>
                <?php 
                } 
                ?>     
			
			</select></div><div>&nbsp;</div>
                                  <div> <form name="frmDashboard" method="post" action="" onSubmit="return valDashboardDts()" >
 <table width="50%" align="center">
 <tr>
 <td width="7%" height="60"> From</td>
  <td width="24%" height="60"><input tabindex="3" type="Text" id="startdate" value="<?php echo $_POST['startdate']; ?>" maxlength="25" size="25" name="startdate" readonly="readonly" />
					&nbsp; <a href="javascript:NewCssCal('startdate','ddmmyyyy','dropdown',true ,12)"><img src="<?=_HTTP_CF_ROOT;?>/ctrl/images/cal.gif" width="16" height="16" border="0px" alt="Pick a date"></a></td>
			  <td width="7%" height="60">To</td>
  <td width="32%" height="60"><input tabindex="4" type="Text" id="enddate" value="<?php echo $_POST['enddate']; ?>" maxlength="25" size="25" name="enddate" readonly="readonly" />
					&nbsp; <a href="javascript:NewCssCal('enddate','ddmmyyyy','dropdown',true , 12)"><img src="<?=_HTTP_CF_ROOT;?>/ctrl/images/cal.gif" width="16" height="16" border="0px" alt="Pick a date"></a>&nbsp;<input type="Submit" name="Submitdate" value="Submit" /></td>
                    </tr></table>
                    </form>
                    </div>
				<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------DISPLAY ALL EVENT PAGE STARTS HERE--------------------------------------------------------------->
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.min.js.gz"></script>
<div align="center">
<?

	
    
   $graph = new BAR_GRAPH("vBar");
$graph->values = count($Restotalevents).",".count($Restotalpevents ).",".count($Restotalfevents ).",".count($Restotalpaidevents ).",".count($Restotalfreeevents ).",".count($ResRegevents).",".count($ResRegusers).",".count($ResToalat).",".($TotalCardAmount+$TotalCounterAmount+$TotalChequeAmount).",".$TotalCardAmount.",".$TotalChequeAmount.",".$TotalCounterAmount;
$graph->labels = "TotalEvents,PastEvents,FutureEvents,PaidEvents,FreeEvents,RegEvents,RegMembers,TotalAteendes,TotalAmount,CardAmount,ChqAmount,CounterAmount";
$graph->showValues = 2;
$graph->barWidth = 20;
$graph->barLength = 2.0;
$graph->labelSize = 12;
$graph->absValuesSize = 12;
$graph->percValuesSize = 12;
$graph->graphPadding = 10;
$graph->graphBGColor = "#ABCDEF";
$graph->graphBorder = "1px solid blue";
$graph->barColors = "#A0C0F0";
$graph->barBGColor = "#E0F0FF";
$graph->barBorder = "2px outset white";
$graph->labelColor = "#000000";
$graph->labelBGColor = "#C0E0FF";
$graph->labelBorder = "2px groove white";
$graph->absValuesColor = "#000000";
$graph->absValuesBGColor = "#FFFFFF";
$graph->absValuesBorder = "2px groove white";
echo $graph->create();
 ?>      
  
</div>

<!-------------------------------DISPLAY ALL EVENT PAGE ENDS HERE--------------------------------------------------------------->
				</div>
                <div><p>&nbsp;</p></div>
                <div>
                <table width="70%" border="1" align="center">
                <tr><td align="center"><strong>TotalEvents</strong></td>
                <td align="center"><strong>PastEvents</strong></td>
                <td align="center"><strong>FutureEvents</strong></td>
                <td align="center"><strong>PaidEvents</strong></td>
                <td align="center"><strong>FreeEvents</strong></td>
                <td align="center"><strong>RegEvents</strong></td>
                <td align="center"><strong>RegMembers</strong></td>
                  <td align="center"><strong>TotalAttendes</strong></td>
                 <td align="center"><strong>TotalAmount</strong></td>
                  <td align="center"><strong>CardAmount</strong></td>
                   <td align="center"><strong>ChqAmount</strong></td>
                    <td align="center"><strong>CounterAmount</strong></td>
                </tr>
                <tr><td align="center"><?=count($Restotalevents);?></td>
                <td align="center"><?=count($Restotalpevents );?></td>
                <td align="center"><?=count($Restotalfevents );?></td>
                <td align="center"><?=count($Restotalpaidevents);?></td>
                <td align="center"><?=count($Restotalfreeevents);?></td>
                <td align="center"><?=count($ResRegevents);?></td>
                <td align="center"><?=count($ResRegusers);?></td>
                 <td align="center"><?=count($ResToalat);?></td>
                 <td align="center"><?=$TotalCardAmount+$TotalCounterAmount+$TotalChequeAmount;?></td>
                  <td align="center"><?=$TotalCardAmount;?></td>
                   <td align="center"><?=$TotalChequeAmount;?></td>
                    <td align="center"><?=$TotalCounterAmount;?></td>
                </tr>
</table>
</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>
 