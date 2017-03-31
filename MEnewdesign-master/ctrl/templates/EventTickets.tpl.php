<?php
function displayStatus($status)
{
	switch($status)
	{
		case 0: echo "Inactive"; break;
		case 1: echo "Active"; break;
		case 2: echo "Sold Out"; break;
	}
}

?>

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
    function EventTrans(EventId)
	{
	status=document.getElementById('status').value;
	window.location="EventTickets.php?EventId="+EventId+"&past="+status;
	}
	 
	function EventStatus(status)
	{
            	var EventId=document.getElementById('EventId').value;
                            if (EventId.length == 0)
                            {
                                alert("Please enter a Event Id");
                                document.getElementById('EventId').focus();
                                return false;
                            } else if (isNaN(EventId) || EventId <= 0) {
                                alert("Please enter valid Event Id");
                                document.getElementById('EventId').focus();
                                return false;
                            }else{		
                                $.get('includes/ajaxSeoTags.php',{eventIDChk:0,eventid:EventId}, function(data){
			if(data=="error")
			{
				alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
				document.getElementById('EventId').focus();
				return false;
				
			}else{
                            	window.location="EventTickets.php?EventId="+EventId+"&past="+status;

                        }
		});
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
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.min.js.gz"></script>
<div align="center" style="width:100%">

	<table width="100%" >
      <tr>
        <td colspan="2" align="left" class="headtitle">  
            <form action="" method="GET" name="frmSrch">
        <div >Select Status <select name="status" id="status" onChange="EventStatus(this.value);">
        <option value="Open">Open</option>
        <option value="Closed" <?php if($_REQUEST[past]=="Closed"){?> selected="selected" <?php } ?>>Closed</option>
        </select>&nbsp;&nbsp;&nbsp;
        
<!--        Select an Event <select name="EventId" id="EventId" onChange="EventTrans(this.value);">
        <option value="">Select Event</option>
        <?php
//		$TotalEventQueryRES = count($EventQueryRES);
//
//		for($i=0; $i < $TotalEventQueryRES; $i++)
//		{
		?>
        
         <?php // }?>
      </select>-->
        Event ID:<input type="text" name="EventId" id="EventId" value="<?php echo $eventId;?>"> <input type="button" name="submitfrm" id="submitfrm" value="Submit" onclick="EventStatus(document.getElementById('status').value);">
      </div></form></td> 
      </tr>
      <tr><td><p>&nbsp;</p>
      <tr>
        <td colspan="2" align="left">   <?php
             
            if($eventId > 0 && !$outputTicket){
                $SelTickets = "SELECT * FROM ticket WHERE eventid='".$eventId."'";
		$ResTickets = $Global->SelectQuery($SelTickets);               
            }
							 
							  ?>
      <table width="70%" border="0" cellspacing="0" cellpadding="0">
  								
								<tr>
									<td align="left" colspan="2"  id="showtickets">
                                	<div id="allTickets" style="padding:0px; margin:0px;">
									<?php if(count($ResTickets)>0) { ?>
										<form action="" method="post">
                                        <table width="100%" border="1" cellspacing="2" cellpadding="2" style="border:solid 1px #CCCCCC;">
<tr bgcolor="#F0F0F0">
									 <tr bgcolor="#ebeae9">
                                              <td align="left" class="graybox"><strong>Name</strong></td>
										      <td align="left" class="graybox"><strong>Price Rs/-</strong></td>
										      <td align="left" class="graybox"><strong>Dates</strong></td>
											  <td align="left" class="graybox"><strong>Inclusive of ST</strong></td>
											  <td align="left" class="graybox"><strong>Tickets Sold</strong></td>
                                              <td align="left" class="graybox"><strong>Status</strong></td>
										     
								          </tr>
											<?php
											$paid=0;
											$free=0;
											for($cntTkts = 0; $cntTkts < count($ResTickets); $cntTkts++)
											{
											if($ResTickets[$cntTkts]['price']==0)
											{
											$free++;
											}else{
											$paid++;
											}
											?>
											<tr>
                                              <td align="left" valign="middle" bgcolor="#F3F3F3" class="td_pad" style="font-size:small;"><?=$ResTickets[$cntTkts]['name']?></td>
                                              <td align="left" valign="middle" bgcolor="#F3F3F3" class="td_pad" style="font-size:small;"> <?=$ResTickets[$cntTkts]['price']?></td>
                                              <td align="left" valign="middle" bgcolor="#F3F3F3" class="td_pad" style="font-size:small;">Start :
                                                  <?=date('d-m-Y', strtotime($ResTickets[$cntTkts]['startdatetime']))?>
                                                <br />
                                                End :
                                                <?=date('d-m-Y', strtotime($ResTickets[$cntTkts]['enddatetime']))?></td>
												<td align="left" valign="middle" bgcolor="#F3F3F3" class="td_pad" style="font-size:small;"><?php if($ResTickets[$cntTkts]['ServiceTax']==0) { echo "Yes"; } else { echo "No"; }?></td>
												<td align="left" valign="middle" bgcolor="#F3F3F3" class="td_pad" style="font-size:small;"><?=$ResTickets[$cntTkts]['totalsoldtickets']?></td>
                                                 <td align="left" valign="middle" bgcolor="#F3F3F3" class="td_pad" style="font-size:small;"><?=displayStatus($ResTickets[$cntTkts]['status'])?></td>
                                              
                                            </tr>
											<?php
											}
											?>	
                                            	<tr>
                                             <td colspan="6" align="center" valign="middle" bgcolor="#F3F3F3" class="td_pad" style="font-size:small;"><br/><input type="submit" value="SendEmail" name="TicketEmail" id="signin_submit" /><?php if($paid>0){?>&nbsp;<!--input type="submit" value="Exportcard" name="Exportcard" id="signin_submit" />&nbsp;<input type="submit" value="ExportChq" name="ExportChq" id="signin_submit" /><?php } ?>&nbsp;<?php if($free>0){?><input type="submit" value="ExportFree" name="ExportFree" id="signin_submit" />&nbsp;<?php } ?><input type="submit" value="ExportAll" name="ExportAll" id="signin_submit" /--></td>
                                              
                                            </tr>
										</table>
                                        </form>
								 	  <?php } ?> 
									  </div>								
                                  </td>
								</tr>
							</table>
                            <p>&nbsp;</p></td>
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