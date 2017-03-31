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
	var status=document.getElementById('Status').value;
	window.location="CancelTrans.php?EventId="+EventId+"&Status="+status;
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
	<table style="width:100%; height:495px;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
				<?php include('templates/left.tpl.php'); ?>
			</td>
			<td style="vertical-align:top">
				<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------DISPLAY ALL EVENT PAGE STARTS HERE--------------------------------------------------------------->
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.min.js.gz"></script>
<div align="center" style="width:100%">

	<table width="100%" >
      <tr>
        <td colspan="2" align="left" class="headtitle">  <div >Select an Event <select name="EventId" id="EventId" onChange="EventTrans(this.value);">
        <option value="">Select Event</option>
        <?
		$TotalEventQueryRES = count($EventQueryRES);

		for($i=0; $i < $TotalEventQueryRES; $i++)
		{
		?>
         <option value="<?=$EventQueryRES[$i]['EventId'];?>" <? if($EventQueryRES[$i]['EventId']==$_REQUEST[EventId]){?> selected="selected" <? }?>><?=$EventQueryRES[$i]['Details'];?></option>
         <? }?>
      </select>&nbsp; Select Status <select name="Status" id="Status" onChange="EventStatus(this.value);">
        <option value=" ">Select Status</option>
        <option value="Open" <? if($_REQUEST[Status]=="Open"){?> selected="selected" <? }?>>Open</option>
        <option value="Closed" <? if($_REQUEST[Status]=="Closed"){?> selected="selected" <? }?>>Closed</option>
        </select>
      </div> </td> 
      </tr>
         <tr><td colspan="2" align="left"><form action="" method="post">
          
            <input type="submit" name="export"  value="ExportCancelTrans" />
            </form></td></tr>
      <tr>
        <td colspan="2" align="left"><table width="100%" align="center" border="1" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-3 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating"><!-- class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-3 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating"-->
        <thead>
        <tr>
          <td colspan="9"></td>
        </tr>
          <tr bgcolor="#CCCCCC">
			<td class="tblinner" valign="middle" width="4%" align="center">Sr. No.</td>
			<td class="tblinner" valign="middle" width="6%" align="center">Receipt No.</td>
			<td class="tblinner" valign="middle" width="10%" align="center">Date</td>
			<td class="tblinner" valign="middle" width="21%" align="center">Event Details</td>
			<td class="tblinner" valign="middle" width="13%" align="center">Contact Details</td>
            <td class="tblinner" valign="middle" width="9%" align="center">City</td>
			<td class="tblinner" valign="middle" width="6%" align="center">Qty</td>
			<td class="tblinner" valign="middle" width="6%" align="center">Amount (Rs.)</td>

            <td class="tblinner" valign="middle" width="9%" align="center">Status</td>
			<td class="tblinner" valign="middle" width="16%" align="center">Action</td>
          </tr>
        </thead>
		<?php
		$cntCanTranRES = 1;
		$TotalCanTranRES = count($CanTranRES);
		for($i = 0; $i < $TotalCanTranRES; $i++)
		{
		?>
		<tr>
			<td class="tblinner" valign="top" width="4%" align="left" ><font color="#000000"><?php echo $cntCanTranRES++; ?></font></td>
			<td class="tblinner" valign="top" width="6%" align="left"><font color="#000000"><?php echo $CanTranRES[$i]['Id']; ?></font></td>
			<td class="tblinner" valign="top" width="10%" align="left" ><font color="#000000"><?php echo $CanTranRES[$i]['SignupDt'];?></font></td>
			<td class="tblinner" valign="top" width="21%" align="left"><font color="#000000"><?php echo $CanTranRES[$i]['Title']; ?></font></td>
			<td class="tblinner" valign="top" width="13%" align="left">
			<font color="#000000">
			<?php 
				$selDelgDtls = "SELECT u.Email, u.FirstName, u.MiddleName, u.LastName, u.Phone, u.Mobile, u.CityId FROM user AS u, EventSignup AS esu WHERE esu.EventId='".$CanTranRES[$i]['EventId']."' AND esu.UserId=u.Id AND esu.Id='".$CanTranRES[$i]['Id']."'";
				$dtlDelgEvents = $Global->SelectQuery($selDelgDtls);
				
				echo $dtlDelgEvents[0]['FirstName'].' '.$dtlDelgEvents[0]['MiddleName'].' '.$dtlDelgEvents[0]['LastName'].'<br />';
				echo $dtlDelgEvents[0]['Email'].'<br />';
				echo $dtlDelgEvents[0]['Phone'].' / '.$dtlDelgEvents[0]['Mobile'];
			?>
			</font>			</td>
            <td class="tblinner" valign="top" width="9%" align="left"><font color="#000000"><?php echo $Global->GetSingleFieldValue("select City from Cities where Id='".$dtlDelgEvents[0]['CityId']."'"); ?></font></td>
			<td class="tblinner" valign="top" width="6%" align="left"><font color="#000000"><?php echo $CanTranRES[$i]['Qty']; ?></font></td>
			<td class="tblinner" valign="top" width="6%" align="right"><font color="#000000"><?php echo $CanTranRES[$i]['Fees'] * $CanTranRES[$i]['Qty']; ?></font></td>
            <td class="tblinner" valign="middle" width="9%" align="center"><select name="eStatus" id="eStatus" onChange="TransStatus(this.value,<?=$CanTranRES[$i]['Id']?>);">
        <option value="Open" <?php if($CanTranRES[$i]['eStatus']=="Open"){?> selected="selected" <? } ?>>Open</option>
         <option value="Closed" <?php if($CanTranRES[$i]['eStatus']=="Closed"){?> selected="selected" <? } ?>>Closed</option>
       
      </select></td>
			<td class="tblinner" valign="middle" width="16%" align="center">
            <!--<form action="" method="post">
            <input type="hidden" name="transid" value="<?=$CanTranRES[$i]['Id']?>" />
            <input type="submit" name="DeleTrans"  value="Delete" />
            </form>-->
             <? $sqlcancom="select count(CanTransId) as commentcount from CancelTransComments where EventSIgnupId=".$CanTranRES[$i]['Id'];
			 $commentcount=$Global->SelectQuery($sqlcancom);
			 ?>
           <a class="lbOn" href="addcomment.php?TransId=<?=$CanTranRES[$i]['Id'];?>&pagename=CancelTrans">ViewComments(<?=$commentcount[0][commentcount];?>)</a>
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