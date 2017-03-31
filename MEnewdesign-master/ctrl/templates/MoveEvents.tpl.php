<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - Event Search Management</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" language="javascript"  src="<?php echo _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>
    <script>	
        function validateEvent(){
            var eventid=document.getElementById('eventid').value;
                         if(eventid>0){		
                                $.get('includes/ajaxSeoTags.php',{eventIDChk:0,eventid:eventid}, function(data){
			if(data=="error")
			{
				alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
				document.getElementById('eventid').focus();
				return false;
				
			}
		});
        }}

    function EventTrans(EventId)
	{
	var status=document.getElementById('Status').value;
	window.location="OnlyCancelTrans.php?EventId="+EventId+"&Status="+status;
	}
	  function EventStatus(status)
	{
	var EventId=document.getElementById('EventId').value;
	window.location="OnlyCancelTrans.php?EventId="+EventId+"&Status="+status;
	}
	
	function TransStatus(val,sId)
	{
	window.location="OnlyCancelTrans.php?value="+val+"&sId="+sId+"&EventId=<?=$_REQUEST[EventId];?>";
	}
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
  	document.getElementById('ans6').style.display='block';
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.css" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.js"></script>
<div align="center" style="width:100%">

	<table width="100%" >
    <form action="" method="get" >
    <tr><td>Event Title <input type="text" name="event" id="event" value="<?=$_REQUEST[event];?>" /> &nbsp; Event-Id <input type="text" name="eventid" id="eventid" value="<?=$_REQUEST[eventid];?>" />&nbsp;<input type="submit" name="submit" id='submit' value="Search" onclick='return validateEvent()' /></td></tr>
    
    </form>
    
   <tr><td>
   <?php
   if(isset($_REQUEST['Msg']) && $_REQUEST['Msg']=="Done"){?>
   <font color="#009900"><strong>Successfully Modified</strong></font>
   <?php }  if(isset($_REQUEST['Msg']) && $_REQUEST['Msg']=="Fail"){?>
   <font color="#FF0000"><strong>Unable to Modify</strong></font>
   <?php } ?> 
   </td></tr>
      <tr>
        <td colspan="2" align="left"><table width="100%" align="center" border="1" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-3 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating"><!-- class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-3 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating"-->
        <thead>
        <tr>
          <td colspan="9"></td>
        </tr>
          <tr bgcolor="#CCCCCC">
			<td class="tblinner" valign="middle" width="6%" align="center">Sr. No.</td>
			<td class="tblinner" valign="middle" width="31%" align="center">Event Name</td>
			<td class="tblinner" valign="middle" width="14%" align="center">date</td>
            <td class="tblinner" valign="middle" width="22%" align="center">City</td>
			<td class="tblinner" valign="middle" width="19%" align="center">UserId</td>
			<td class="tblinner" valign="middle" width="8%" align="center">Action</td>
          </tr>
        </thead>
		<?php
		$cntCanTranRES = 1;
		$TotalCanTranRES = count($EventQueryRES);
		for($i = 0; $i < $TotalCanTranRES; $i++)
		{
		?>
		<tr>
			<td class="tblinner" valign="top" width="6%" align="left" ><font color="#000000"> <?php echo $cntCanTranRES++; ?></font></td>
			<td class="tblinner" valign="top" width="31%" align="left"><font color="#000000"> <?php echo $EventQueryRES[$i]['title']; ?></font></td>
			<td class="tblinner" valign="top" width="14%" align="left" ><font color="#000000"> <?php echo $EventQueryRES[$i]['startdatetime'];?></font></td>
			<td class="tblinner" valign="top" width="22%" align="left"><font color="#000000"><?=$City=$Global->GetSingleFieldValue("select name from city where id='".$EventQueryRES[$i]['cityid']."'");?></font></td>
            <td class="tblinner" valign="top" width="19%" align="left"><font color="#000000"><?=$Username=$Global->GetSingleFieldValue("select username from user where id='".$EventQueryRES[$i]['ownerid']."'");?><br/>
              (<?=$EventQueryRES[$i]['ownerid'];?>)</font></td>
             <td class="tblinner" valign="top" width="8%" align="center"><font color="#000000"><a href="MoveEventsedit.php?EventId=<?=$EventQueryRES[$i]['id'];?>">edit</a></font></td>
			
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