<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - Organizer Login</title>
	<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	
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
				<div id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------Events of the Month PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans9').style.display='block';
</script>
<!--<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.min.css.gz" />-->
<!--<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.min.js.gz"></script>-->
 <script language="javascript" src="<?=_HTTP_SITE_ROOT;?>/js/public/jQuery.js"></script>    
<script language="javascript">
	function SEdt_validate()
	{
		var EventId = document.frmEofMonth.EventId.value;
		
		if(EventId.trim() == '')
		{
                    alert('Please Enter Event-Id');
                    document.frmEofMonth.EventId.focus();
                    return false;
                }else if(isNaN(EventId.trim())){
                    alert('Please Enter vaild Event-Id');
                    document.frmEofMonth.EventId.focus();
                    return false;
                }  
	}
        $(document).ready(function(){
            $('#frmEofMonth').on('submit',function(e){
                e.preventDefault();
                var EventId = document.frmEofMonth.EventId.value;
                $.ajax({
                    data:'call=isEventIdExists&event_id='+EventId.trim(),
                    url:'<?=_HTTP_SITE_ROOT?>/ctrl/ajax.php',
                    type:'POST',
                    success:function(res){
                        var newRes=$.parseJSON(res);
                        if(!newRes['status']){
                            alert("Invalid EventId!!!");
                           return false;
                        }else{
                          $('#frmEofMonth').unbind().submit();
                        }
                    }
                });
            });
            $('#dispalyOnHome').click(function(){
                $.ajax({
                    data:'call=updateNoTck&EventId='+'<?=$editEventRes[0]['id'];?>'+'&adminId='+'<?=$uid?>',
                    url:'<?=_HTTP_SITE_ROOT?>/ctrl/ajax.php',
                    type:'POST',
                    success:function(res){
                        var newRes=$.parseJSON(res);
                        if(newRes['status']){
                            alert(newRes['status']);
                            window.location.href="<?=_HTTP_SITE_ROOT?>/ctrl/login_org_event.php?EventId="+'<?=$editEventRes[0]['id'];?>';
                        }
                    }
                });
            });
            $('#taxonbaseprice').click(function(){
                $.ajax({
                    data:'call=taxonbaseprice&EventId='+'<?=$editEventRes[0]['id'];?>'+'&adminId='+'<?=$uid?>',
                    url:'<?=_HTTP_SITE_ROOT?>/ctrl/ajax.php',
                    type:'POST',
                    success:function(res){
                        var newRes=$.parseJSON(res);
                        if(newRes['status'] && newRes['response']['total']>0){
                            alert(newRes['response']['messages'][0]);
                            window.location.href="<?=_HTTP_SITE_ROOT?>/ctrl/login_org_event.php?EventId="+'<?=$editEventRes[0]['id'];?>';
                        }else{
                            alert(newRes['response']['messages'][0]);
                        }
                    }
                });
            });
            $('#discountaftertax').click(function(){
                $.ajax({
                    data:'call=discountaftertax&EventId='+'<?=$editEventRes[0]['id'];?>'+'&adminId='+'<?=$uid?>',
                    url:'<?=_HTTP_SITE_ROOT?>/ctrl/ajax.php',
                    type:'POST',
                    success:function(res){
                        var newRes=$.parseJSON(res);
                        if(newRes['status'] && newRes['response']['total']>0){
                            alert(newRes['response']['messages'][0]);
                            window.location.href="<?=_HTTP_SITE_ROOT?>/ctrl/login_org_event.php?EventId="+'<?=$editEventRes[0]['id'];?>';
                        }else{
                            alert(newRes['response']['messages'][0]);
                        }
                    }
                });
            });
            $('#sendFeedback').click(function(){
                $.ajax({
                    data:'call=updateSendFeedback&EventId='+'<?=$editEventRes[0]['id'];?>'+'&adminId='+'<?=$uid?>',
                    url:'<?=_HTTP_SITE_ROOT?>/ctrl/ajax.php',
                    type:'POST',
                    success:function(res){
                        var newRes=$.parseJSON(res);
                        if(newRes['status']){
                            alert(newRes['status']);
                            window.location.href="<?=_HTTP_SITE_ROOT?>/ctrl/login_org_event.php?EventId="+'<?=$editEventRes[0]['id'];?>';
                        }
                    }
                });
            });
            $('#unpublishEvent').click(function(){
                $.ajax({
                    data:'call=unpublishEvent&EventId='+'<?=$editEventRes[0]['id'];?>'+'&adminId='+'<?=$uid?>',
                    url:'<?=_HTTP_SITE_ROOT?>/ctrl/ajax.php',
                    type:'POST',
                    success:function(res){
                        var newRes=$.parseJSON(res);
                        if(newRes['status']){
                            alert(newRes['status']);
                            location.href="<?=_HTTP_SITE_ROOT?>/ctrl/login_org_event.php?EventId="+'<?=$editEventRes[0]['id'];?>';
                        }
                    }
                });
            });
            $('#deleteEvent').click(function(){
                if(window.confirm("Are you sure you want to delete this event?")){
                    $.ajax({
                        data:'call=deleteEvent&EventId='+'<?=$editEventRes[0]['id'];?>',
                        url:'<?=_HTTP_SITE_ROOT?>/ctrl/ajax.php',
                        type:'POST',
                        success:function(res){
                            var newRes=$.parseJSON(res);
                            if(newRes['status']){
                                alert("Successfully deleted the event");
                                location.href="<?=_HTTP_SITE_ROOT?>/ctrl/login_org_event.php";
                            }else{
                                alert("Deletion failed something went wrong!!!");
                            }
                        }
                    });
                }
            });
                
        });

</script>
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Enter  Event-Id</div>
<div align="center" style="width:100%">&nbsp;</div>
<form action="" method="post" name="frmEofMonth" id="frmEofMonth">
<table width="50%" align="center" class="tblcont">
	<tr>
	  <td width="35%" align="left" valign="middle">Event-Id:&nbsp;<input type="text" name="EventId" id="EventId" value="<?php echo $EventId; ?>"  /></td>
          <td width="30%" align="left" valign="middle"><input type="submit" name="getEveDtls" id="getEveDtls" value="Submit" onclick="return SEdt_validate();" /></td>
	<tr>
</table>

<?php if(count($ResOrgQuery) > 0) { ?>
<table width="100%" align="center" class="sortable">
	<tr>
		
		<td width="40%" align="left" valign="middle" class="tblcont1">Full Name</td>
        <td width="15%" align="left" valign="middle" class="tblcont1">UserName</td>
		<td width="15%" align="left" valign="middle" class="tblcont1">Reg Date</td>
		<td width="10%" align="left" valign="middle" class="tblcont1">Action</td>
	
    </tr>
	<?php 
		$cnt=1;
		for($i = 0; $i < count($ResOrgQuery); $i++)
		{
	?>
	<tr>
		
		<td align="left" valign="middle" class="helpBod"><?=$ResOrgQuery[$i]['name']; ?></td> 	
      	<td align="left" valign="middle" class="helpBod"><?=$ResOrgQuery[$i]['username'];?>	</td>
		<td align="left" valign="middle" class="helpBod">
                <?php   echo $commonFunctions->convertTime($ResOrgQuery[$i]['cts'],DEFAULT_TIMEZONE,TRUE); ?>
                </td>
        <?php
		//."&eventid=".$EventId
		$window_url =  _HTTP_SITE_ROOT."/api/user/adminSession?organizerId=".$ResOrgQuery[$i]['id'].'&adminId='.$uid;	

		?>
		<td align="left" valign="middle" class="helpBod"><a href="#" onclick="window.open('<?=$window_url?>','mywindow','menubar=1,width=900,height=600,resizable=yes,scrollbars=yes');">Edit</a> <?php if($commonFunctions->isSuperAdmin()){ echo '<a href="#" name="deleteEvent" id="deleteEvent">Delete</a>';}?></td>
	
	</tr>
	<?php 
	} //ends for loop
	?>
</table>
<?php 
	} //ends if condition
	else if(count($ResDelQuery) > 0)
	{
?>
	<table width="90%" align="center">
		<tr>
		  <td width="100%" align="center" valign="middle">This Email Id is Registred as Delegate. <a href="<?php echo _HTTP_SITE_ROOT;?>/change-password?UserType=Delegate&uid=<?php echo $ResDelQuery[0]['id'];?>" target="_blank">click here to login</a></td>
		 </tr>
	</table>
<?php
	}
	else 
	{
?>
	<table width="90%" align="center">
		<tr>
		  <td width="100%" align="center" valign="middle">No match record found.</td>
		 </tr>
	</table>
<?php
	}
?>
      <div class="twelvecol">
            <table width="100%" border="0" cellspacing="0" cellpadding="5" id="OrganizaerTable">
	            <tr class="MyEventsTableHead">
                	<td style="padding:0;">
				<?php
				if($commonFunctions->isSuperAdmin() && ($_REQUEST['submit'] == 'Submit' || isset($_REQUEST['EventId']))) 
				{
				?>
                <table width="100%" border="1">
                    <tr>
                        <th colspan="6">Event name</th>
                        <th>Actions</th>
                    </tr>
                    <tr>
                    <td colspan="6" class="MyEventsTicketHead"><?php echo stripslashes($editEventRes[0]['title']); ?> (EventId : <?php echo $editEventRes[0]['id'];?>)</td>
					 <?php
	
		             $edit_url =  _HTTP_SITE_ROOT."/api/user/adminSession?organizerId=".$editEventRes[0]['ownerid']."&eventid=".$editEventRes[0]['id']."&userType=".$_SESSION['adminUserType'].'&adminId='.$uid;	

		              ?>

                                <td align="left" class="MyEventsBorderRight">
                                    <a href="<?php echo $edit_url; ?>">Edit</a><br/>
                                    <a style="text-decoration: underline;cursor: pointer;" name="unpublishEvent" id="unpublishEvent"><?php echo $editEventRes[0]['status']==0?'Click Here Publish':'Click Here Unpublish';?></a><br/>
                                    <a style="text-decoration: underline;cursor: pointer;" name="dispalyOnHome" id="dispalyOnHome">Display on Home</a>(Status-<?php echo $status=$editEventRes[0]['ticketsoldout']==0?'<span style="color:green;">Active</span>':'<span style="color:red;">Inactive</span>';?>)<br/>
                                    <a style="text-decoration: underline;cursor: pointer;" name="sendFeedback" id="sendFeedback"><?php echo $editEventRes[0]['sendfeedbackemails']==1?'De-ativate feedback email':'Activate feedback email';?></a>(Status-<?php echo $editEventRes[0]['sendfeedbackemails']==1?'<span style="color:green;">Active</span>':'<span style="color:red;">Inactive</span>';?>)<br/>
                                    <a style="text-decoration: underline;cursor: pointer;" name="taxonbaseprice" id="taxonbaseprice">All Taxes On Ticket Base Price</a>(Status-<?php echo $editEventRes[0]['calculationmode']=='onbaseprice'?'<span style="color:green;">Active</span>':'<span style="color:red;">Inactive</span>';?>)<br/>
                                    <!--<a style="text-decoration: underline;cursor: pointer;" name="discountaftertax" id="discountaftertax">Discount before tax</a>(Status-<?php //echo $editEventRes[0]['discountaftertax']?'<span style="color:green;">Active</span>':'<span style="color:red;">Inactive</span>';?>)-->
                                </td>
                    </tr>

            </table>
            	<?php
				}
				?>
            
                    </div><!--End of Form TwelveCol Div-->
</form>
<div align="center" style="width:100%">&nbsp;</div>
<!-------------------------------Events of the Month PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>