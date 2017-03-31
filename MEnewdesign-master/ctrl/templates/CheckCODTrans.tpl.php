<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
        <link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.min.js.gz"></script>
<script src="<?=_HTTP_SITE_ROOT ?>/ctrl/includes/javascripts/jquery.1.7.2.min.min.js.gz"></script>
<script language="javascript">
	function SEdt_validate()
	{
		var strtdt = document.frmEofMonth.txtSDt.value;
		var enddt = document.frmEofMonth.txtEDt.value;
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
	function TransStatus(sId)
	{

    
	var Status=document.getElementById('Status').value;
	var selCodStatus=document.getElementById('selCodStatus'+sId).value;
	var selCodtStatus=document.getElementById('selCodtStatus'+sId).value;
	var depDt=document.getElementById('depDt'+sId).value;
	var strtdt = document.frmEofMonth.txtSDt.value;
	var enddt = document.frmEofMonth.txtEDt.value;
    var recptno = document.frmEofMonth.recptno.value;
	var EventId=document.frmEofMonth.EventId.value;
	
	window.location="CheckCODTrans.php?value=change&sId="+sId+"&selCodStatus="+selCodStatus+"&selCodtStatus="+selCodtStatus+"&txtSDt="+strtdt+"&txtEDt="+enddt+"&Status="+Status+"&recptno="+recptno+"&EventId="+EventId+"&depDt="+depDt;
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
    <form action="" method="post" name="frmEofMonth">
    <table width="80%" align="center" class="tblcont">
	<tr>
	  <td width="35%" align="left" valign="middle">Start Date:&nbsp;<input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="35%" align="left" valign="middle">End Date:&nbsp;<input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="30%" align="left" valign="middle"><input type="submit" name="submit" value="Show Report" onclick="return SEdt_validate();" /></td>
	<tr>
    <tr><td> Select Status:  <select name="Status" Id="Status">
    <option value="">Select</option>
    <option value="Verified" <? if($_REQUEST[Status]=="Verified"){?> selected="selected" <? }?>>Verified</option>
    <option value="NotVerified" <? if($_REQUEST[Status]=="NotVerified"){?> selected="selected" <? }?>>NotVerified</option>
    <option value="Refunded" <? if($_REQUEST[Status]=="Refunded"){?> selected="selected" <? }?>>Refunded</option>
    <option value="All" <? if($_REQUEST[Status]=="All"){?> selected="selected" <? }?>>All</option>

    </select> </td>
    <td>Receipt No. <input type="text" name="recptno" id="recptno" value="<?=$_REQUEST[recptno];?>" />
    </td><td>&nbsp;</td></tr>
    <tr><td colspan="3">Select an Event <select name="EventId" style="width:400px;" id="EventId" onChange="EventTrans(this.value);">
        <option value="">Select Event</option>
        <?
		$TotalEventQueryRES = count($EventQueryRES);

		for($i=0; $i < $TotalEventQueryRES; $i++)
		{
		?>
         <option value="<?=$EventQueryRES[$i]['EventId'];?>" <? if($EventQueryRES[$i]['EventId']==$EventId){?> selected="selected" <? }?>><?=$EventQueryRES[$i]['Details'];?></option>
         <? }?>
      </select></td></tr>
    <tr><td>Event Id:&nbsp;<input type="text" name="eventIdSrch" id="eventIdSrch" value="<?= $EventId; ?>"></td></tr>
        <tr><td colspan="3">
      ByOrganizer   <select tabindex="86" name="SerEventName" id="SerEventName"  class="adTextFieldd" style="width:250px;">
				<option value="">Select Organizer Name</option>	
				<?php 
				$SelectOrgNames1="SELECT orgDispName, Id FROM orgdispname where Active=1  ORDER BY orgDispName ASC";
                $OrgNames1=$Global->SelectQuery($SelectOrgNames1);
                $TotalOrgNames1=count($OrgNames1);
                for($i=0;$i<$TotalOrgNames1;$i++)
                {
                ?>
                <option value="<?php echo $OrgNames1[$i]['Id'];?>" <?php if($OrgNames1[$i]['Id'] == $_REQUEST['SerEventName']) { ?> selected="selected" <?php } ?>><?php echo $OrgNames1[$i]['orgDispName']; ?></option>
                <?php 
                } 
                ?>     
			
			</select></td></tr>
    
    <tr>
        <?php ?>
        <td colspan="3" style="padding:10px 10px; text-align: center; font-size:16px; color:#5DB901;">
            <?php if($_POST['submit'] == 'send email' || (isset($_GET['cancelSMS']))){ ?>
            <span id="notice">Message has been sent successfully </span>
            <script>
                $(function(){
                    $('#notice').show().fadeOut(4000);
                });
            </script>
            <?php }  ?>
        </td>
    </tr>
    
    
</table>
</form>
	<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans4').style.display='block';
</script>
<table width='95%' border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='3%' align='center'>Sr. No.</td>
			<td class='tblinner' valign='middle' width='12%' align='center'>Receipt No.</td>
            <td class='tblinner' valign='middle' width='10%' align='center'>Date</td>
            <td class='tblinner' valign='middle' width='20%' align='center'>Event Details</td>
            <td class='tblinner' valign='middle' width='6%' align='center'>Type</td>
            <td class='tblinner' valign='middle' width='6%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='11%' align='center'>Amount (Rs.)</td>
             <td class='tblinner' valign='middle' width='11%' align='center'>Payment Status</td>
             <td class='tblinner' valign='middle' width='12%' align='center'>Ticket Status</td>
             <td class='tblinner' valign='middle' width='12%' align='center'>DepositedDate</td>
             <td class='tblinner' valign='middle' width='9%' align='center'>Comment</td>
             <td class='tblinner' valign='middle' width='12%' align='center'>Warning Message</td>
             <td class='tblinner' valign='middle' width='9%' align='center'>Action</td>
          </tr>
        </thead>
        
        <?	
		$TotalAmountcard=0;
		$TotalAmountchk=0;
		$Totalchk=0;
		$Totalcard=0;
		for($i = 0; $i < count($TransactionRES); $i++)
	{ ?>
		<tr>
			<td class='tblinner' valign='middle' width='3%' align='center' ><font color='#000000'><?=$cntTransactionRES;?></font></td>
			<td class='tblinner' valign='middle' width='12%' align='center'><font color='#000000'><?=$TransactionRES[$i]['Id'];?></font></td>
			<td class='tblinner' valign='middle' width='10%' align='center' ><font color='#000000'><?=$TransactionRES[$i]['SignupDt'];?></font></td>
			<td class='tblinner' valign='middle' width='20%' align='left'><font color='#000000'><?=$TransactionRES[$i]['Title'];?></font></td>
			<td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'>COD</font></td>     		
			<td class='tblinner' valign='middle' width='6%' align='right'><font color='#000000'><?=$TransactionRES[$i]['Qty'];?></font></td>
			<td class='tblinner' valign='middle' width='11%' align='right'><font color='#000000'><?=$TransactionRES[$i]['Fees'] * $TransactionRES[$i]['Qty'];?></font></td>
            <td class='tblinner' valign='middle' width='11%' align='right'><font color='#000000'>
		<? $status=$Global->GetSingleFieldValue("SELECT Status FROM CODstatus WHERE EventSIgnupId='".$TransactionRES[$i]['Id']."'")?>
			<select name="selCodStatus<?=$TransactionRES[$i]['Id'];?>" id="selCodStatus<?=$TransactionRES[$i]['Id'];?>"  >
					<option value="Pending" <?php if($status == "Pending") { ?> selected="selected" <?php } ?>>Pending</option>
                    <option value="Delivered" <?php if($status == "Delivered") { ?> selected="selected" <?php } ?>>Success</option>
                    <option value="Refunded" <?php if($status == "Refunded") { ?> selected="selected" <?php } ?>>Refunded</option>
                    <option value="Canceled" <?php if($status == "Canceled") { ?> selected="selected" <?php } ?>>Canceled</option>
				
			</select>

			</font></td>
            <? if($TransactionRES[$i]['DepositedDate']!="0000-00-00 00:00:00"){
			$depdate=date('d/m/Y',strtotime($TransactionRES[$i]['DepositedDate']));
			}else{
			$depdate="";
			}
			
			?>
            <td class='tblinner' valign='middle' width='12%' align='right'>
            <? $tstatus=$Global->GetSingleFieldValue("SELECT tStatus FROM CODstatus WHERE EventSIgnupId='".$TransactionRES[$i]['Id']."'")?>
			<select name="selCodtStatus<?=$TransactionRES[$i]['Id'];?>" id="selCodtStatus<?=$TransactionRES[$i]['Id'];?>"  >
					<option value="Pending" <?php if($tstatus == "Pending") { ?> selected="selected" <?php } ?>>Pending</option>
                    <option value="InProcess" <?php if($tstatus == "InProcess") { ?> selected="selected" <?php } ?>>InProcess</option>
                    <option value="Delivered" <?php if($tstatus == "Delivered") { ?> selected="selected" <?php } ?>>Delivered</option>
                    <option value="Canceled" <?php if($tstatus == "Canceled") { ?> selected="selected" <?php } ?>>Canceled</option>
				
			   </select>

            </td>
             <td class='tblinner' valign='middle' width='11%' align='right'><input type="text" name="depDt<?=$TransactionRES[$i]['Id']?>" id="depDt<?=$TransactionRES[$i]['Id']?>" value="<?php echo $depdate; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
            <td class="tblinner" valign="top" width="9%" align="left">
                <font color="#000000"> 
                    <?php echo $Global->GetSingleFieldValue("select Comment from CancelTransComments where EventSIgnupId='".$TransactionRES[$i]['Id']."' order by CanTransId desc"); ?>
                </font>
                             <? $sqlcancom="select count(CanTransId) as commentcount from CancelTransComments where EventSIgnupId=".$TransactionRES[$i]['Id'];
			 $commentcount=$Global->SelectQuery($sqlcancom);
			 ?>
           <a class="lbOn" href="addcomment.php?TransId=<?=$TransactionRES[$i]['Id'];?>&pagename=CheckCODTrans&EventId=<?=$_REQUEST['eventIdSrch'];?>&Status=<?=$_REQUEST[Status];?>&txtSDt=<?=$_REQUEST['txtSDt'];?>&txtEDt=<?=$_REQUEST['txtEDt'];?>">ViewComments(<?=$commentcount[0][commentcount];?>)</a> <br/>
           <font color="#000000"> <?php echo $Global->GetSingleFieldValue("select SalesName from sales where SalesId='".$CanTranRES[$i]['SalesId']."'"); ?></font>
            </td>
            <td class='tblinner' valign='middle' width='12%' align='right' style="padding:4px 5px; text-align: center;">
                <?php 
                $event_signup_id= $TransactionRES[$i]['Id'] ;
                $user_id=$TransactionRES[$i]['user_id'];
                $status=check_event_signup_email_status($Global,$event_signup_id,$user_id,$cod_message_id);
                    $non_link_text="";
                    if($status == "true"){
                        $non_link_text="Message sent, <br/>";
                        $link_message="Re send";
                    }else{
                        $link_message="Send Email/SMS";
                    }
                    echo $non_link_text;
                ?>
 
                <a class="inline cboxElement" href="#inline_content"    
                  data-mobile="<?=$TransactionRES[$i]['Phone'];?>"   
                  data-event_user_email_id="<?=$TransactionRES[$i]['Email'];?>"   
                  data-event_user_id="<?=$user_id;?>"   
                  data-event_org_email="<?=$TransactionRES[$i]['OrgEmail'];?>"
                  data-event_signup_id="<?=$event_signup_id;?>"   
                  data-event_url="<?=$TransactionRES[$i]['URL'];?>"   
                  data-event-name="<?=$TransactionRES[$i]['Title'];?>"  ><?=$link_message ?></a>
              </td>
            <td class='tblinner' valign='middle' width='9%' align='right'><input type="button" name="changetrans" value="change" onclick="TransStatus(<?=$TransactionRES[$i]['Id']?>);"  /></td>
          </tr>
          <?
		$TotalAmountcard += $TransactionRES[$i]['Fees'] * $TransactionRES[$i]['Qty'];
		$Totalcard += $TransactionRES[$i]['Qty'];
		$cntTransactionRES++;
	}?>
	<tr><td colspan="4" style="line-height:30px;"><strong>Total COD Transactions Amount:</strong></td><td colspan='4' align='right'><font color='#000000'>Rs. <?=$TotalAmountcard;?></font></td></tr>
  

</table>
<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	
	
	</div>
	</td>
  </tr>
</table>
	</div>
 
    <!-- This contains the hidden content for inline calls -->
    <div style="display:none">
        <div id="inline_content" style="padding:10px; background:#fff;">
            <form action="" method="post" >
                <div>
                    <Label>Message:</Label>
                    <textarea name="email_message" id="email_message" 
                              cols="50" rows="20"> </textarea>
                    <br/>
                    <span style="margin-left:56px;">
                        <input type="hidden" name="event_signup_id" id="event_signup_id" value="0"/>
                        <input type="hidden" name="event_url" id="event_url" value="0"/>
                        <input type="hidden" name="user_email_id" id="user_email_id" value="0"/>
                        <input type="hidden" name="user_id" id="user_id" value="0"/>
                        <input type="hidden" name="user_mobile" id="user_mobile" value="0"/>
                        <input type="hidden" name="event_org_email" id="event_org_email" value="0"/>
                        <input type="submit" name="submit" value="send email"/>
                    </span>
                </div>
                
            </form>

        </div>
    </div>
    <!-- email message content -->
    <div style="display:none" id="email_message_content">
        <p> Dear Delegate,</p>

        <p>Greetings from Mera Events!</p>

        <p>We tried to reach you on your registered phone number : <span id="email_user_phone_no"> </span> for picking up the Cash to confirm your Registration for : <span id="email_user_event_name" style="font-weight:bold;"> </span> event/conference.</p>
        <p>Event Url</p>
        <p>Please do revert back to us or respond to our representative's call. In case we don't receive any response from your end by next business day, we would be forced to cancel your Registration for the event.</p>

        <p>Awaiting your answer,  </p>                                                                        

        <p>Team Mera Events</p>
        <p>Thank you for booking through Mera Events, your most trusted event ticketing company.</p>
    </div>
<link rel="stylesheet" href="<?=_HTTP_SITE_ROOT ?>/ctrl/css/colorbox.css" />

<script src="<?=_HTTP_SITE_ROOT ?>/ctrl/includes/javascripts/jquery.colorbox-min.js"></script>
<script>
    $(function(){
         
        $(".inline").colorbox({
            inline:true,
            width:"40%",
            height:"70%",
            onOpen:function(){
               var current_user=$(this);
               $("#event_signup_id").val(current_user.data("event_signup_id"));
               $("#event_url").val(current_user.data("event_url"));
               $("#event_org_email").val(current_user.data("event_org_email"));
               $("#user_email_id").val(current_user.data("event_user_email_id"));
               $("#user_id").val(current_user.data("event_user_id"));
               $("#user_mobile").val(current_user.data("mobile"));
               $("#email_user_phone_no").html(current_user.data("mobile"));
               $("#email_user_event_name").html(current_user.data("event-name"));
               $("#email_message").val($("#email_message_content").html());
            }
        });
    });
</script>    
</body>
</html>
