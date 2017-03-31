<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - Event Search Management</title>
        <link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
            <script language="javascript" src="<?=_HTTP_SITE_ROOT;?>/js/public/jQuery.js"></script>    
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>
    <link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
    <script>	
	function SEdt_validate()
	{
            var eventid=document.getElementById('eventIdSrch').value;
            if(eventid>0){
		$.get('includes/ajaxSeoTags.php',{eventIDChk:0,eventid:eventid}, function(data){
			if(data=="error")
			{
				alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
				document.getElementById('eventIdSrch').focus();
				return false;
				
			}
		});
		
	}
            

		var strtdt = document.frmEofMonth.settleDt.value;
		var enddt = document.frmEofMonth.endDt.value;
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

    /*function deltrans(transId)
	{
	var EventId=document.getElementById('EventId').value;
	var type=document.getElementById('eventtype').value;
	var status=document.getElementById('Status').value;
	var settleDt=document.getElementById('settleDt').value;
	var endDt=document.getElementById('endDt').value;
	window.location="OnlyCancelTrans.php?EventId="+EventId+"&Status="+status+"&eventtype="+type+"&settleDt="+settleDt+"&endDt="+endDt+"&DeleTrans=Delete&transid="+transId;
	}*/
	
	
	
	  function EventStatus(status)
	{
	var type=document.getElementById('eventtype').value;
	var EventId=document.getElementById('EventId').value;
	var status=document.getElementById('Status').value;
	var settleDt=document.getElementById('settleDt').value;
	window.location="OnlyCancelTrans.php?EventId="+EventId+"&Status="+status+"&type="+type+"&settleDt="+settleDt;
	}
	  function EventDate(dt)
	{
alert(dt);
	var type=document.getElementById('eventtype').value;
	var EventId=document.getElementById('EventId').value;
	var status=document.getElementById('Status').value;
	window.location="OnlyCancelTrans.php?EventId="+EventId+"&Status="+status+"&type="+type+"&settleDt="+dt;
	}
	 function EventPast(type)
	{
	var EventId=document.getElementById('EventId').value;
	var status=document.getElementById('Status').value;
	var settleDt=document.getElementById('settleDt').value;
	window.location="OnlyCancelTrans.php?EventId="+EventId+"&Status="+status+"&type="+type+"&settleDt="+settleDt;
	}
	function TransStatus(val,sId)
	{
	var type=document.getElementById('eventtype').value;
	var settleDt=document.getElementById('settleDt').value;
	var endDt=document.getElementById('endDt').value;
	var Status=document.getElementById('Status').value;
	window.location="OnlyCancelTrans.php?value="+val+"&sId="+sId+"&EventId=<?=$_REQUEST[EventId];?>&eventtype="+type+"&settleDt="+settleDt+"&endDt="+endDt+"&Status="+Status;	}
    function delCancelledTrans(signUpId) 
	{
		var strURL="deleteCanTrans.php?signUpId="+signUpId;
		var req = getXMLHTTP();
	
		if (req) 
		{
			req.onreadystatechange = function()
			{
				if (req.readyState == 4)
				{
					// only if "OK"
					if (req.status == 200)
					{
						document.getElementById('cancelledTrans').innerHTML=req.responseText;
					}
					else
					{
						alert("Error connecting to network:\n" + req.statusText);
					}
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
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
				<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------DISPLAY ALL EVENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans4').style.display='block';
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.css" />
<div align="center" style="width:100%">

	<table width="100%" >
      <tr>
        <td colspan="2" align="left" class="headtitle"><form action="" method="get" name="frmEofMonth">  <div >
<!--                    Select an Event 
                    <select name="EventId" id="EventId" >
        <option value="">Select Event</option>
        <?php
		$TotalEventQueryRES = count($EventQueryRES);

		for($i=0; $i < $TotalEventQueryRES; $i++)
		{
		?>
         <option value="<?=$EventQueryRES[$i]['eventid'];?>" <?php if($EventQueryRES[$i]['eventid']==$eventIdSrch){?> selected="selected" <?php }?>><?=$EventQueryRES[$i]['Details'];?></option>
         <?php }?>
      </select>-->
                   &nbsp;Event Id: <input type="text" name="eventIdSrch" id="eventIdSrch" value="<?php echo $eventIdSrch; ?>" />
       &nbsp; Select  <select name="eventtype" id="eventtype" >
         <option value="Present" <?php if($_REQUEST[eventtype]=="Present"){?> selected="selected" <?php }?>>Present</option>
        <option value="Past" <?php if($_REQUEST[eventtype]=="Past"){?> selected="selected" <?php }?>>Past</option>
        </select>
        &nbsp; Select Status <select name="Status" id="Status" >
        <option value="">Select Status</option>
        <option value="Open" <?php if($_REQUEST[Status]=="Open"){?> selected="selected" <?php }?>>Open</option>
        <option value="Closed" <?php if($_REQUEST[Status]=="Closed"){?> selected="selected" <?php }?>>Closed</option>
        </select>
		
      </div>   Start Date <input type="text" name="settleDt" id="settleDt"  value="<?=$_REQUEST[settleDt];?>" size="8" onfocus="showCalendarControl(this);" /> &nbsp;&nbsp; End Date <input type="text" name="endDt" id="endDt"  value="<?=$_REQUEST[endDt];?>" size="8" onfocus="showCalendarControl(this);" /><input type="submit" name="submit" onclick="return SEdt_validate();" value="Submit" /></form></td>
      </tr>
      <tr><td  align="left"><form action="" method="post">
          
            <input type="submit" name="export"  value="ExportIncompleteTrans" />
            </form></td><td align="right"><div class="paging_bar"><?=$pagination;?></div></td></tr>
      <tr>
        <td colspan="2" align="left"><table width="100%" align="center" border="1" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-3 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating"><!-- class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-3 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating"-->
        <thead>
        <tr>
          <td colspan="9"></td>
        </tr>
          <tr bgcolor="#CCCCCC">
			<td class="tblinner" valign="middle" width="4%" align="center">Sr. No.</td>
			<!--<td class="tblinner" valign="middle" width="4%" align="center">Receipt No.</td>-->
			<td class="tblinner" valign="middle" width="10%" align="center">Date</td>
			<td class="tblinner" valign="middle" width="21%" align="center">Event Details</td>
			<td class="tblinner" valign="middle" width="13%" align="center">Contact Details</td>
            <td class="tblinner" valign="middle" width="10%" align="center">Failed Count</td>
           	<td class="tblinner" valign="middle" width="6%" align="center">Qty</td>
			<td class="tblinner" valign="middle" width="6%" align="center">Amount (Rs.)</td>
             <td class="tblinner" valign="middle" width="9%" align="center">Payment Status</td>
            <td class="tblinner" valign="middle" width="9%" align="center">Status</td>
             <td class="tblinner" valign="middle" width="9%" align="center">comment</td>
			<td class="tblinner" valign="middle" width="16%" align="center">Action</td>
          </tr>
        </thead>
		<?php
		$cntCanTranRES = 1;
		$TotalCanTranRES = count($CanTranRES);
		for($i = 0; $i < $TotalCanTranRES; $i++)
		{
			$previewUrl=_HTTP_SITE_ROOT.'/event/'.$Global->GetSingleFieldValue("select URL from event where deleted=0 and id='".$CanTranRES[$i]['eventid']."'");
		?>
		<tr>
			<td class="tblinner" valign="top" width="4%" align="left" ><font color="#000000"> <?php echo $cntCanTranRES++; ?></font></td>
			<!--<td class="tblinner" valign="top" width="6%" align="left"><font color="#000000"> <?php echo $CanTranRES[$i]['id']; ?></font></td>-->
			<td class="tblinner" valign="top" width="10%" align="left" ><font color="#000000"> <?php 
                        echo $common->convertTime($CanTranRES[$i]['signupdate'],DEFAULT_TIMEZONE,TRUE);
                        
                        ?></font></td>
			<td class="tblinner" valign="top" width="21%" align="left"><font color="#000000"><?=$Title=$Global->GetSingleFieldValue("select title from event where deleted=0 and id='".$CanTranRES[$i]['eventid']."'");?></font> - <a href="<?= $previewUrl;?>" target="_blank"><?=$CanTranRES[$i]['eventid']; ?></a></td>
			<td class="tblinner" valign="top" width="13%" align="left">
			<font color="#000000">
			<?php 
				
				
				echo $CanTranRES[$i]['name'].'<br />';
				echo $CanTranRES[$i]['email'].'<br />';
				echo $CanTranRES[$i]['mobile'].'<br/>';
				echo $Global->GetSingleFieldValue("select name from city where id='".$CanTranRES[$i]['cityid']."'");
			?>
			</font>			</td>
            <td class="tblinner" valign="top" width="6%" align="left"><font color="#000000"><?php echo $CanTranRES[$i]['fCount']; ?></font></td>
         	<td class="tblinner" valign="top" width="6%" align="left"><font color="#000000"><?php echo $CanTranRES[$i]['tktCount']; ?></font></td>
			<td class="tblinner" valign="top" width="6%" align="right"><font color="#000000"><?php echo round($CanTranRES[$i]['totalAmount'],2); ?></font></td>
            <td class="tblinner" valign="top" width="6%" align="right"><font color="#000000"><?php echo $CanTranRES[$i]['paymentstatus']; ?></font></td>
            <td class="tblinner" valign="middle" width="9%" align="center"><select name="eStatus" id="eStatus" onChange="TransStatus(this.value,<?=$CanTranRES[$i]['id']?>);">
        <option value="Open" <?php if($CanTranRES[$i]['supportstatus']=="0"){?> selected="selected" <?php } ?>>Open</option>
         <option value="Closed" <?php if($CanTranRES[$i]['supportstatus']=="1"){?> selected="selected" <?php } ?>>Closed</option>
       
      </select></td>
         <td class="tblinner" valign="top" width="9%" align="left"><font color="#000000"> <?php echo $Global->GetSingleFieldValue("select comment from comment where eventsIgnupid='".$CanTranRES[$i]['id']."' order by id desc"); ?></font></td>
			<td class="tblinner" valign="middle" width="16%" align="center"><!--<a href="#" onclick="deltrans(<?=$CanTranRES[$i]['id']?>);">Delete</a><br/>-->
             <?php $sqlcancom="select count(id) as commentcount from comment where eventsIgnupid=".$CanTranRES[$i]['id'];
			 $commentcount=$Global->SelectQuery($sqlcancom);
			 ?>
           <a class="lbOn" href="addcomment.php?TransId=<?=$CanTranRES[$i]['id'];?>&pagename=OnlyCancelTrans&EventId=<?=$_REQUEST[EventId];?>&Status=<?=$_REQUEST[Status];?>&settleDt=<?=$_REQUEST[settleDt];?>&endDt=<?=$_REQUEST[endDt];?>">ViewComments(<?=$commentcount[0][commentcount];?>)</a> <br/>
           <font color="#000000"> <?php
                  echo $Global->GetSingleFieldValue("select name from salesperson where id='".$CanTranRES[$i]['salespersonid']."'"); ?></font>
            </td>
          </tr>
		  <?php
		  }
		  ?>
      </table></td>
      </tr>
      </table>
  
</div>
<!-------------------------------DISPLAY ALL EVENT PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>
<script type="text/javascript" src="<?=_HTTP_SITE_ROOT?>/lightbox/prototype.min.js.gz"></script>
  <script type="text/javascript" src="<?=_HTTP_SITE_ROOT?>/lightbox/lightbox.min.js.gz"></script>
	<link type="text/css" rel="stylesheet" href="<?=_HTTP_SITE_ROOT?>/lightbox/lightbox.min.css.gz" media="screen,projection" />