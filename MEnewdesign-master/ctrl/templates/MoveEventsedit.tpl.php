<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - Event Search Management</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>
    <script>	
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
   
      <tr>
        <td colspan="2" align="left"><form action="" method="post">
        
        <table width="100%" align="center"  ><!-- class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-3 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating"-->
        <thead>
        <tr>
          <td colspan="9"></td>
        </tr>
         
        </thead>
	
		<tr>
			
			<td class="tblinner" valign="top" width="21%" align="left">Event Name :</td>
			<td class="tblinner" valign="top" width="79%" align="left"><font color="#000000"> <?php echo $EventQueryRES[0]['title']; ?></font></td>
            </tr><tr><td class="tblinner" valign="top" width="21%" align="left" >Start Date :</td>
			<td class="tblinner" valign="top" width="79%" align="left" ><font color="#000000"> <?php echo $EventQueryRES[0]['startdatetime'];?></font></td>
            </tr><tr><td class="tblinner" valign="top" width="21%" align="left">City :</td>
			<td class="tblinner" valign="top" width="79%" align="left"><font color="#000000"><?=$City=$Global->GetSingleFieldValue("select name from city where id='".$EventQueryRES[0]['cityid']."'");?></font></td>
            </tr><tr><td class="tblinner" valign="top" width="21%" align="left">Organizer Id :</td>
            <td class="tblinner" valign="top" width="79%" align="left">
			 <select name="orgid" id="orgid">
             <option value="0">Select OrgId</option>
             <?php
             for($i = 0; $i < count($OrgRes); $i++)
		     { ?>
             <option value="<?=$OrgRes[$i][id];?>" <?php if($EventQueryRES[0]['ownerid']==$OrgRes[$i]['id']){?> selected="selected" <?php } ?>><?=$OrgRes[$i][email];?></option>
             <?php }?>
			</select>
			
			</td>
            </tr>
            <tr><td class="tblinner" valign="top" colspan=2 align="left"><input type="submit" name="Submit" value="Submit" /></td></tr>
		
      </table>
        
        </form></td>
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