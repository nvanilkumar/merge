<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - Event Search Management</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>
    <script>	
    function EventTrans(EventId)
	{
	
	window.location="eventreminder.php?EventId="+EventId;
	}
	  function EventStatus(status)
	{
	var EventId=document.getElementById('EventId').value;
	window.location="CancelTrans.php?EventId="+EventId+"&Status="+status;
	}
	
	function TransStatus(val,sId)
	{
	window.location="CancelTrans.php?value="+val+"&sId="+sId+"&EventId=<?=$_REQUEST[EventId];?>";
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
	<table width="102%" cellpadding="0" cellspacing="0" style="width:100%; height:495px;">
		<tr>
			<td width="150" style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
				<?php include('templates/left.tpl.php'); ?>			</td>
			<td width="1001" style="vertical-align:top">
				<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------DISPLAY ALL EVENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans6').style.display='block';
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.min.js.gz"></script>
<div align="center" style="width:100%">
   <h1>Event Reminders</h1>
	<table width="100%" >
      <tr>
        <td colspan="2" align="left" class="headtitle">  <div >Select an Event <select name="EventId" id="EventId" onChange="EventTrans(this.value);">
        <option value="">Select Event</option>
        <?
		$TotalEventQueryRES = count($EventQueryRES );

		for($i=0; $i < $TotalEventQueryRES; $i++)
		{
		?>
         <option value="<?=$EventQueryRES[$i]['Id'];?>" <? if($EventQueryRES[$i]['Id']==$_REQUEST[EventId]){?> selected="selected" <? }?>><?=$EventQueryRES[$i]['Title'];?></option>
         <? }?>
      </select>
      Event ID:<input type="text" name="eventIdSrch" id="eventIdSrch"> <input type="button" name="submitfrm" id="submitfrm" value="Submit" onclick="EventTrans(document.getElementById('eventIdSrch').value);">
      </div> </td> 
      </tr>
      <tr>
        <td colspan="2" align="left"><table width="100%" align="center" border="1" >
            <thead>
              <tr align="center">
               <td height="30"  class="font11">&nbsp; <strong>Name</strong> </td>
               <td height="30"  class="font11">&nbsp; <strong>Email</strong> </td>
                <td height="30"  class="font11">&nbsp; <strong>Event Title</strong> </td>
               <td  height="30"  class="font11">&nbsp; <strong>Days prior to Event</strong></td>
               <td  height="30"  class="font11">&nbsp; <strong>Till the Event end</strong></td>
                <td  height="30"  class="font11">&nbsp; <strong>Options</strong> </td>
              </tr>
            </thead>
                              <tbody>
                                <?
					 $Totalreminder=count($reminderArr);
					for($i=0; $i<$Totalreminder; $i++)
					{
										
					//$sql_q = "select Title from events where id='".$bookmark[$i]['EventId']."'";

					//echo $bookmarkArr[$i]['EventId']."eventID";
					$Events = new cEvents($reminderArr[$i]['EventId']);
						if($Events->Load())
						{
							?>
                                <tr align="center">
                                  <td height="38"  class="font11">&nbsp;<a href="PreviewEvent.php?EventId=<?=$Events->Id;?>" class="click2bookmark-row-link" style="text-decoration:none;"><strong>
                                    <?=$reminderArr[$i][Name];?>
                                    </strong> </a></td>
                                      <td height="38"  class="font11">&nbsp;<a href="PreviewEvent.php?EventId=<?=$Events->Id;?>" class="click2bookmark-row-link" style="text-decoration:none;"><strong>
                                    <?=$reminderArr[$i][email];?>
                                    </strong> </a></td>
                                  <td height="38"  class="font11">&nbsp;<a href="PreviewEvent.php?EventId=<?=$Events->Id;?>" class="click2bookmark-row-link" style="text-decoration:none;"><strong>
                                    <?=$Events->Title;?>
                                    </strong> </a></td>
                                     <td height="38"  class="font11">&nbsp;<?=$reminderArr[$i][days];?></td>
                                     <? if($reminderArr[$i][daily]==0){ $daily="No"; } else { $daily="Yes"; } ?>
                                     <td height="38"  class="font11">&nbsp;<?=$daily;?></td>
                                     <?
									 if($_REQUEST[EventId]!="")
									 {
									 $con="&EventId=".$_REQUEST[EventId];
									 }?>
                                  <td height="32" style="text-decoration:none"  class="font11"> (<a href="eventreminder.php?DelId=<?=$reminderArr[$i]['Id']?><?=$con;?>" onclick="return confirm('Are you sure to delete this Reminder for event?');"><font color="#000000">Delete</font></a>)</td>
                                </tr>
                                <?
						}
					} 
					?>
                              </tbody>
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