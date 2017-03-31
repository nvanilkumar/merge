
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title>MeraEvents -Menu Content Management</title>
		<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
        <script language="javascript" src="<?=_HTTP_SITE_ROOT;?>/js/public/jQuery.js"></script> 
        <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
<script language="javascript">
        function SEdt_validate()
        {
            var strtdt = document.frmEofMonth.txtSDt.value;
            var enddt = document.frmEofMonth.txtEDt.value;
            var SalesId = document.frmEofMonth.SalesId.value;
		
            if(strtdt != '' && enddt != ''){   
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
        var EventId = document.getElementById('EventId').value;
        if(EventId>0){
        $.get('includes/ajaxSeoTags.php', {eventIDChk: 0, eventid: EventId}, function (data) {
            if (data == "error")
            {
                alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
                document.getElementById('EventId').focus();
                return false;

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
	
	<script language="javascript">
  	document.getElementById('ans13').style.display='block';
</script>


 
<form action="" method="post" name="frmEofMonth" >
<?php echo $msg;?>

<table width="98%" border="0" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="2"><strong>Quality Checking</strong> </td>
  </tr>
  
  
  <tr>
    <td> Select Sales Person :</td>
    <td width="81%"><label>
      <select name="SalesId" id="SalesId" >
        <option value="">Select</option>
        <?php
		$TotalSalesQueryRES = count($SalesQueryRES);

		for($i=0; $i < $TotalSalesQueryRES; $i++)
		{
		?>
         <option value="<?php echo $SalesQueryRES[$i]['SalesId'];?>" <?php if($SalesQueryRES[$i]['SalesId']==$_REQUEST[SalesId]){?> selected="selected" <?php }?>><?php echo $SalesQueryRES[$i]['SalesName'];?></option>
         <?php }?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td> EventId :</td>
    <td width="81%"><label>
    <input type="text" name="EventId" id="EventId" value="<?php echo $_REQUEST['EventId'];?>" />
    </label></td>
  </tr>
  <tr>
    <td> Category :</td>
    <td width="81%"><label>
    <select name="categoryid" id="categoryid" >
                <option value="" selected="selected">Select</option>
                <?php
                for ($i = 0; $i < count($categoryList); $i++) {
                    ?>
                    <option value="<?php echo $categoryList[$i]['id']; ?>"  <?php if($categoryList[$i]['id'] == $categoryId) { ?> selected="selected" <?php } ?>><?php echo $categoryList[$i]['name']; ?></option>
                    <?php
                }
                ?>
            </select>
    </label></td>
  </tr>
   <tr>
    <td> Select Status :</td>
    <td width="81%"><label>
      <select name="echk" id="echk" >
      <option value="NotChecked" <?php if($_REQUEST['echk']=="NotChecked"){?> selected="selected" <?php }?>>NotChecked</option>
      <option value="Checked" <?php if($_REQUEST['echk']=="Checked"){?> selected="selected" <?php }?>>Checked</option>
      <option value="All" <?php if($_REQUEST['echk']=="All"){?> selected="selected" <?php }?>>Select All</option>
        
      </select>
    </label></td>
  </tr>
  <tr>
    <td> Select Events Added by :</td>
    <td width="81%"><label>
      <select name="eadd" id="eadd" >
        <option value="">Both</option>
        <option value="Team" <?php if($_REQUEST['eadd']=="Team"){?> selected="selected" <?php }?>>Team</option>
         <option value="Organizer" <?php if($_REQUEST['eadd']=="Organizer"){?> selected="selected" <?php }?>>Organizer</option>
        
      </select>
    </label></td>
  </tr>
  <tr>
    <td> Select Paid/Free :</td>
    <td width="81%"><label>
      <select name="paid" id="paid" >
        <option value="" selected="selected">All</option>
        <option value="Paid" <?php if($_REQUEST['paid']=="Paid"){?> selected="selected" <?php }?>>Paid</option>
         <option value="Free" <?php if($_REQUEST['paid']=="Free"){?> selected="selected" <?php }?>>Free</option>
        
      </select>
    </label></td>
  </tr>
  <tr>
    <td colspan="2"><table width="50%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    
    <td colspan="2">More than 5days : <input type="checkbox" name="more5" value="1"  <?php if($_REQUEST['more5']==1){?> checked="checked" <?php } ?> /> </td>
    <td colspan="2">No Category : <input type="checkbox" name="nocat" value="1"  <?php if($_REQUEST['nocat']==1){?> checked="checked" <?php } ?> /> &nbsp;&nbsp;No Logo : <input type="checkbox" name="nologo" value="1"  <?php if($_REQUEST['nologo']==1){?> checked="checked" <?php } ?> />  </td>
    
  </tr>
</table>
 </td>
   
  </tr>
  <tr><td colspan="2"><table width="50%" align="left" class="tblcont">
	<tr>
	  <td width="35%" align="left" valign="middle">Start Date:&nbsp;<input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="35%" align="left" valign="middle">End Date:&nbsp;<input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	 
	<tr>
</table></td></tr>
<tr> <td width="19%" align="left" valign="middle"><input type="submit" name="submit" value="Show report" onclick="return SEdt_validate();" /></td><td><strong>Total Events</strong> :<?php echo $rows;?></td>
</tr>
  <tr>
    <td colspan="2"><table width="100%" border="1" cellspacing="2" cellpadding="2">
  <tr>
     <td width="5%" align="center"><strong>Event-Id</strong></td>
	<td width="44%" align="center"><strong>Event</strong></td>
    <td width="10%" align="center"><strong>Preview</strong></td>
	<td width="10%" align="center"><strong>Sub Category</strong></td>
    <td width="12%" align="center"><strong>Status</strong></td>
    <td width="18%" align="center"><strong>SalesPerson</strong></td>
    <td width="17%" align="center"><strong>Date</strong></td>
    <td width="9%" align="center"><strong>Action</strong></td>
  </tr>
    <?php
		$TotalEventsOfMonth = count($EventsOfMonth);
        
		for($i=0; $i < $TotalEventsOfMonth; $i++)
		{
		 
		//$window_url = _HTTP_SITE_ROOT."/dashboard-aevent?Ax=Yes&EventId=".$EventsOfMonth[$i]['Id']."&uid=".$EventsOfMonth[$i]['UserID']; 
		$window_url =  _HTTP_SITE_ROOT."/api/user/adminSession?organizerId=".$EventsOfMonth[$i]['UserID']."&eventid=".$EventsOfMonth[$i]['Id'].'&adminId='.$uid;	
	    $eventUrl=_HTTP_SITE_ROOT."/event/".$EventsOfMonth[$i]['URL'];
		 
		

	
		 if(substr_count($EventsOfMonth[$i]['OEmails'],"@meraevents.com")>=1)
		 {
		 $inner='bgcolor="#66FF66"';
		 }else{
		 $inner='';
		 }
        
		?>
  <tr <?php echo $inner;?>>
   <td align="center"><?php echo $EventsOfMonth[$i]['Id'];?></td>
    <td><a href="#" onclick="window.open('<?php echo $window_url;?>','mywindow','menubar=1,width=900,height=600,resizable=yes,scrollbars=yes');"><?php echo $EventsOfMonth[$i][Title];?></a></td>
    <td align="center"><a href="#" onclick="window.open('<?php echo $eventUrl;?>','mywindow1','menubar=1,width=900,height=600,resizable=yes,scrollbars=yes');">Clickhere</a></td>
	<td align="center"><?php echo $EventsOfMonth[$i]['Subcategory']; ?></td>
    <td align="center"><?php echo ($EventsOfMonth[$i]['eChecked']==0)?"Not Checked":"Checked";?></td>
    <td align="center"><?php echo $EventsOfMonth[$i]['salesName'];//$Global->GetSingleFieldValue("select `name` from salesperson where id='".$EventsOfMonth[$i]['QPid']."'");?></td>
    <td align="center"><?php echo $common->convertTime($EventsOfMonth[$i]['QDate'], DEFAULT_TIMEZONE,TRUE);?></td>
      <td align="center"><a href="qcheckingedit.php?eid=<?php echo $EventsOfMonth[$i]['Id'];?>">Edit</a></td>
  </tr>
  <?php }?>
  <tr><td colspan="7" align="right"><?php echo $pagination;?></td></tr>
  
</table>
</td>
  </tr>
 
</table>
</form>

<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	
	
	</div>
	</td>
  </tr>
</table>
	</div>	
</body>
</html>