<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Events Score Board</title>
	<link href="css/menus.css" rel="stylesheet" type="text/css">
	<link href="css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="css/sortable.js"></script>	
	<script language="javascript" src="css/sortpagi.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo _HTTP_SITE_ROOT;?>/css/me_customfieldsnew.css" />

<link rel="stylesheet" type="text/css" href="<?php echo _HTTP_SITE_ROOT;?>/css/rating_style.css" media="all">
<script language="javascript" type="text/javascript" src="<?php echo _HTTP_SITE_ROOT;?>/scripts/me_customfields.js"></script>

<script language="javascript" type="text/javascript" src="<?php echo _HTTP_SITE_ROOT;?>/scripts/livevalidation.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo _HTTP_SITE_ROOT;?>/ajaxdaddy/prototype.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo _HTTP_SITE_ROOT;?>/ajaxdaddy/effects.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo _HTTP_SITE_ROOT;?>/ajaxdaddy/window.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo _HTTP_SITE_ROOT;?>/ajaxdaddy/window_effects.js"></script>
 <script>
 function EventStatus(val)
 {
 window.location="orgscore.php?Status="+val;
 }
 </script>
  <script type="text/JavaScript">

function timedRefresh(timeoutPeriod) {
	//setTimeout("location.reload(true);",timeoutPeriod);
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
				<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------DISPLAY ALL EVENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans6').style.display='block';
</script>
<link rel="stylesheet" type="text/css" media="all" href="css/pagi_sort.css" />
<script type="text/javascript" language="javascript" src="includes/javascripts/sortpagi.js"></script>
<div align="center" style="width:100%">

	<table width="100%" >
    <tr>
        <td colspan="2" align="left" class="headtitle">Select Status: 
            <select name="Status" id="Status" onChange="EventStatus(this.value);">
                <option value=" ">Select Status</option>
                <option value="Registred" <?php if($_REQUEST['Status']=="Registred"){?> selected="selected" <?php }?>>Registered</option>
                <option value="All" <?php if($_REQUEST['Status']=="All"){?> selected="selected" <?php }?>>All</option>
                <option value="Famous" <?php if($_REQUEST['Status']=="Famous"){?> selected="selected" <?php }?>>Famous</option>
            </select>
        </td> 
    </tr>
    <tr>
        <td colspan="2" align="left">
        <table width="100%" align="center" border="1" ><!-- class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-3 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating"-->
        <thead>
          <tr bgcolor="#CCCCCC">
            <td class="tblinner" valign="middle" width="8%" align="center">Sr. No.</td>
            <td class="tblinner" valign="middle" width="23%" align="center">Event Name</td>
            <td class="tblinner" valign="middle" width="19%" align="center">Event Date</td>
            <td class="tblinner" valign="middle" width="23%" align="center">Organizer Details</td>
            <!--<td class="tblinner" valign="middle" width="15%" align="center">View Count</td>-->
            <td class="tblinner" valign="middle" width="15%" align="center">SalesPerson</td>
            <td class="tblinner" valign="middle" width="12%" align="center">Attendes Registered</td>
          </tr>
        </thead>
            <?php
            $cntCanTranRES = 1;
            $TotalCanTranRES = count($EventQueryRES);
            for($i = 0; $i < $TotalCanTranRES; $i++)
            {
                $CountAttendeesQuery="select Sum(AttendeesRegistered) "
                        . "FROM "
                            . "(SELECT count(a.id) as AttendeesRegistered "
                            . "FROM attendee as a,eventsignup as s, discount ds "
                            . "WHERE a.eventsignupid=s.id AND ds.eventid=s.eventid AND s.eventid= ".$EventQueryRES[$i]['id']." "
                            . "AND (s.paymenttransactionid != 'A1' OR  ds.code != 'X') "
                        . "UNION All "
                            . "SELECT count(a.id) as AttendeesRegistered "
                            . "FROM attendee as a,eventsignup as s, "
                            . "chequepayments ch "
                            . "WHERE a.eventsignupid=s.id AND ch.eventsignupid=s.id "
                            . "AND s.EventId= ".$EventQueryRES[$i]['id']." "
                            . "AND (s.paymentgatewayid='1' AND "
                            . "s.paymenttransactionid='A1' AND "
                            . "s.paymentmodeid=2))x";
                $cntAttendeesRES=$Global->SelectQuery($CountAttendeesQuery);
                //echo "<br> New Count <br>";
                // print_r($cntAttendeesRES);
                $cntAttendees=$cntAttendeesRES[0]['Sum(AttendeesRegistered)'];
                
                if(($_REQUEST['Status']=="Registred" && $cntAttendees >0) || $_REQUEST['Status']=="" || $_REQUEST['Status']=="All" || $_REQUEST[Status]=="Famous")  { 
            ?>
	<tr>
            <td class="tblinner" valign="top" width="8%" align="left" ><font color="#000000"><?php echo $cntCanTranRES++; ?></font></td>
            <td class="tblinner" valign="top" width="23%" align="left"><font color="#000000"><?php echo $EventQueryRES[$i]['title']; ?></font></td>
            <td class="tblinner" valign="top" width="19%" align="left"><font color="#000000"><?php echo date('F j, Y, g:i a',strtotime($EventQueryRES[$i]['startdatetime'])); ?> To<br> <?php echo date('F j, Y, g:i a',strtotime($EventQueryRES[$i]['enddatetime'])); ?></font></td>
            <td class="tblinner" valign="top" width="23%" align="left">
		<font color="#000000">
		<?php 
                echo $EventQueryRES[$i]['name'].'<br />';
                echo $EventQueryRES[$i]['email'].'<br />';
                if(isset($EventQueryRES[$i]['phone']) && $EventQueryRES[$i]['mobile'])
                echo $EventQueryRES[$i]['phone'].' / '.$EventQueryRES[$i]['mobile'];
                else
                echo $EventQueryRES[$i]['phone'].$EventQueryRES[$i]['mobile'];
		?>
		</font>
            </td>
            <!--<td class="tblinner" valign="top" width="15%" align="left">
                <font color="#000000"><?php echo $EventQueryRES[$i]['viewCount']; ?></font>
            </td>-->
            <td class="tblinner" valign="top" width="15%" align="left">
                <font color="#000000"><?php echo $EventQueryRES[$i]['salesname']; ?></font>
            </td>
            <td class="tblinner" valign="top" width="12%" align="right">
                <font color="#000000">Attendee<?php echo "(".$cntAttendees.")";?></font>
            </td>
           
          </tr>
        <?php
		} 
            }
	?>
      </table></td>
      </tr>
      </table>
  
</div>
<!-------------------------------DISPLAY ALL EVENT PAGE ENDS HERE--------------------------------------------------------------->
				</div>
                  <script type="text/javascript" src="<?php echo _HTTP_SITE_ROOT;?>/lightbox/lightbox.js"></script>
	<link type="text/css" rel="stylesheet" href="<?php echo _HTTP_SITE_ROOT;?>/lightbox/lightbox.css" media="screen,projection" />
			</td>
		</tr>
	</table>
</div>	
</body>
</html>