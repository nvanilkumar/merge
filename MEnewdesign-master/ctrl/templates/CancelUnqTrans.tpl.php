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
	window.location="CancelTrans.php?EventId="+EventId;
	}
	
	function TransStatus(val,sId)
	{
	window.location="CancelTrans.php?value="+val+"&sId="+sId;
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
        <option value="0">Select Event</option>
        <?
		$TotalEventQueryRES = count($EventQueryRES);

		for($i=0; $i < $TotalEventQueryRES; $i++)
		{
		?>
         <option value="<?=$EventQueryRES[$i]['EventId'];?>" <? if($EventQueryRES[$i]['EventId']==$_REQUEST[EventId]){?> selected="selected" <? }?>><?=$EventQueryRES[$i]['Details'];?></option>
         <? }?>
      </select>
      </div> </td>
      </tr>
      <tr>
        <td colspan="2" align="left"><table width="100%" align="center" border="1" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-3 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating"><!-- class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-3 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating"-->
        <thead>
        <tr>
          <td colspan="9"></td>
        </tr>
          <tr bgcolor="#CCCCCC">
			<td class="tblinner" valign="middle" width="6%" align="center">Sr. No.</td>
		    <td class="tblinner" valign="middle" width="16%" align="center">FirstName</td>
			<td class="tblinner" valign="middle" width="5%" align="center">LastName</td>
			<td class="tblinner" valign="middle" width="5%" align="center">Mobile</td>
            <td class="tblinner" valign="middle" width="5%" align="center">Email</td>
			
          </tr>
        </thead>
		<?php
		$cntCanTranRES = 1;
		$TotalCanTranRES = count($CanTranRES);
		for($i = 0; $i < $TotalCanTranRES; $i++)
		{
		?>
		<tr>
			<td class="tblinner" valign="top" width="6%" align="left" ><font color="#000000"><?php echo $cntCanTranRES++; ?></font></td>
			<td class="tblinner" valign="top" width="8%" align="left"><font color="#000000"><?php echo $CanTranRES[$i]['FirstName']; ?></font></td>
			<td class="tblinner" valign="top" width="15%" align="left" ><font color="#000000"><?php echo $CanTranRES[$i]['LastName'];?></font></td>
			<td class="tblinner" valign="top" width="22%" align="left"><font color="#000000"><?php echo $CanTranRES[$i]['Mobile']; ?></font></td>
			<td class="tblinner" valign="top" width="16%" align="left"><font color="#000000"><?php echo $CanTranRES[$i]['Email']; ?></font></td>
		
        
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