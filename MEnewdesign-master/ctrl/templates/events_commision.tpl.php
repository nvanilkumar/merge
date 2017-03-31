<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - Events of the Month</title>
	<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
	<script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
        <script src="<?php echo  _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
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
<!-------------------------------Events of the Month PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans6').style.display='block';
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript"  src="<?php echo _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>                            
<script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
<script language="javascript">
	function SEdt_validate()
	{
		var strtdt = document.frmEofMonth.txtSDt.value;
		var enddt = document.frmEofMonth.txtEDt.value;
		/*if(strtdt == '')
		{
			alert('Please select Start Date');
			document.frmEofMonth.txtSDt.focus();
			return false;
		}
		else if(enddt == '')
		{
			alert('Please select End Date');
			document.frmEofMonth.txtEDt.focus();
			return false;
		}*/
                if(strtdt != '' && enddt == '')
		{
			alert('Please select End Date');
			document.frmEofMonth.txtEDt.focus();
			return false;
		}
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
                        var eventid = document.getElementById('EventId').value;
                        if (eventid>0){
                            $.get('includes/ajaxSeoTags.php', {eventIDChk: 0, eventid: eventid}, function (data) {
                                if (data == "error")
                                {
                                    alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
                                    document.getElementById('EventId').focus();
                                    return false;
                                }
                            });
                        }
                return true;
	}

function checkper(f,eventId)
{
 var cardperc = f.cardperc.value;
 var codperc = f.codperc.value;
 var counterperc = f.counterperc.value;
 var paypalperc = f.paypalperc.value;
 var overall = f.overall.value;
var mesales=f.meeffortperc.value;
var eventId=f.PEventId.value;
var type=f.Save.value;
//var mobikwikperc=f.mobikwikperc.value;
//var paytmperc=f.paytmperc.value;

//if(cardperc== "" || codperc== "" || counterperc== "" || paypalperc== "" || mobikwikperc=="" || paytmperc=="" || overall== "" || mesales== "" || isNaN(cardperc) || isNaN(codperc)|| isNaN(counterperc) || isNaN(paypalperc) || isNaN(mobikwikperc) || isNaN(paytmperc) || isNaN(overall) || isNaN(mesales)){
if(cardperc== "" || codperc== "" || counterperc== "" || paypalperc== "" || overall== "" || mesales== "" || isNaN(cardperc) || isNaN(codperc)|| isNaN(counterperc) || isNaN(paypalperc) || isNaN(overall) || isNaN(mesales)){   
        alert("Commission values cannot be empty.Please enter 0 if you dont want to set the commission!!!")
}else{
    //var newdata='call=addOrUpdateCommission&EventId='+eventId+'&Save='+type+'&cardperc='+cardperc+'&codperc='+codperc+'&counterperc='+counterperc+'&paypalperc='+paypalperc+'&mobikwikperc='+mobikwikperc+'&paytmperc='+paytmperc+'&overall='+overall+'&meeffortperc='+mesales;
   var newdata='call=addOrUpdateCommission&EventId='+eventId+'&Save='+type+'&cardperc='+cardperc+'&codperc='+codperc+'&counterperc='+counterperc+'&paypalperc='+paypalperc+'&overall='+overall+'&meeffortperc='+mesales+'&adminId='+'<?=$uid?>'
;
            $.ajax({
       type:'POST',
       url:'<?php echo _HTTP_SITE_ROOT?>/ctrl/ajax.php',
       data:newdata,
       success:function(res){
           var newRes=$.parseJSON(res);
           if(newRes['status']){
               document.getElementById('Save'+eventId).value='Save';
               alert("Upadated commisions successfully");
           }else{
               alert("Something went wrong!!!");
           }
       }
    });
}
 return false;
 }

</script>
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Events Commission Percentages</div>
<div align="center" style="width:100%">&nbsp;</div>
<form action="<?php echo ($_POST['EventId'] == '') ? htmlspecialchars($_SERVER["PHP_SELF"]) : htmlspecialchars($_SERVER["PHP_SELF"]).'?EventId='.$eventId; ?>" method="post" name="frmEofMonth" enctype="multipart/form-data">
<table width="80%" align="center" class="tblcont">
	<tr>
            <td align="left" valign="middle">Event Id:&nbsp;<input type="text" name="EventId"  id="EventId" value="<?php echo $eventId; ?>"/></td>
            <td align="left" valign="middle">Start Date:&nbsp;<input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
            <td align="left" valign="middle">End Date:&nbsp;<input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
            <td  align="left" valign="middle"><input type="submit" name="submit" value="Show Events" onclick="return SEdt_validate();" /></td>
            <td></td>
        </tr>
</table>
</form>




<?php if(count($EventsOfMonth) > 0) { ?>

<table style="width:100%" align="center" class="sortable">
	<tr>
		<td width="5%" align="left" valign="middle" class="tblcont1">Sr. No.</td>
		<td width="30%" align="left" valign="middle" class="tblcont1">Event Name</td>
        <td width="2%" align="left" valign="middle" class="tblcont1">Event-Id</td>
		<td width="15%" align="left" valign="middle" class="tblcont1">Start Date</td>
	  	<td width="15%" align="left" valign="middle" class="tblcont1">End Date</td>
        <td width="3%" align="left" valign="middle" class="tblcont1">OverAll%</td>
        <td width="3%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Card%</td>
        <td width="3%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">COD%</td>
        <td width="3%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Counter%</td>
        <td width="3%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Paypal%</td>
<!--        <td width="3%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Mobikwik%</td>
        <td width="3%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Paytm%</td>-->
        <td width="3%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">ME Sales%</td>
        <td width="10%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Extra <br />Charge</td>
        <!--td width="10%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Contract <br />Doc</td-->
		<td width="5%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Action</td>
                <td width="5%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Verified By</td>
    </tr>
	<?php 
		$cnt=1;
		for($i = 0; $i < count($EventsOfMonth); $i++)
		{
	?>
	<tr>
		<td align="left" valign="middle" class="helpBod" height="25"><?php echo $cnt++?></td>
		<td align="left" valign="middle" class="helpBod"><?php echo stripslashes($EventsOfMonth[$i]['Title']); ?></td> 	
        <td align="left" valign="middle" class="helpBod"><?php echo $EventsOfMonth[$i]['Id'];?></td> 	
		<td align="left" valign="middle" class="helpBod">
		<?php 
		$StartDt=$EventsOfMonth[$i]['StartDt'];
		$StartDtExplode = explode(" ", $StartDt);//remove time
		$StartDt = $StartDtExplode[0];
		
		$StartDtExplode = explode("-", $StartDt);
		$StartDt = $StartDtExplode[2].'-'.$StartDtExplode[1].'-'.$StartDtExplode[0];
		echo $StartDt; 
		?>
		</td>
	  <td align="left" valign="middle" class="helpBod">
		<?php
		$EndDt=$EventsOfMonth[$i]['EndDt'];
		$EndDtExplode = explode(" ", $EndDt);//remove time
		$EndDt = $EndDtExplode[0];
		
		$EndDtExplode = explode("-", $EndDt);
		$EndDt = $EndDtExplode[2].'-'.$EndDtExplode[1].'-'.$EndDtExplode[0];
		echo $EndDt; 
		
		
		
		//chekcing, if any extracharge available
		 $exQuery = "SELECT id 'exId',type 'exType',`value` 'exAmount' FROM eventextracharge WHERE eventid=".$EventsOfMonth[$i]['Id']." and status=1"; //using 4/6
		$ResExQuery = $Global->SelectQuery($exQuery);
		
		$commQuery = "SELECT * FROM commission WHERE eventid=".$EventsOfMonth[$i]['Id']; //using 4/6
		$RescommQuery = $Global->SelectQuery($commQuery);
		//echo count($RescommQuery);
		$comcount=count($RescommQuery);
		if($comcount>0)
		{
                  
                    foreach ($RescommQuery as $eCommission) {
                        switch ($eCommission['type']) {
                            case "1":
                                $cardperc=$eCommission['value'];
                                break;
                            case "2":
                                $codperc=$eCommission['value'];
                                break;
                            case "3":
                                $counterperc=$eCommission['value'];
                                break;
                            case "4":
                                $paypalperc=$eCommission['value'];
                                break;
                            case "5":
                                $mobikwikperc=$eCommission['value'];
                                break;
                            case "6":
                                $paytmperc=$eCommission['value'];
                                break;
                            case "11":
                                $meeffortperc=$eCommission['value'];
                                break;
                           
                        }
                        $ContractDoc  = $eCommission['contractdocument'];
                    }
                    $commId=$RescommQuery[0]['eventid'];
                    /*$cardperc=$RescommQuery[0]['Card'];
                    $codperc=$RescommQuery[0]['Cod'];
                    $counterperc=$RescommQuery[0]['Counter'];
                    $paypalperc=$RescommQuery[0]['Paypal'];
                    $mobikwikperc=$RescommQuery[0]['Mobikwik'];
                    $paytmperc=$RescommQuery[0]['Paytm'];
                    $meeffortperc=$RescommQuery[0]['MEeffort'];
                    $ContractDoc=$RescommQuery[0]['ContractDoc'];*/
			
		}
		else
		{
                    $cardperc=$Globalcardperc;
                    $codperc=$Globalcodperc;
                    $counterperc=$Globalcounterperc;
                    $paypalperc=$Globalpaypalperc;
                    $mobikwikperc=$Globalmobikwikperc;
                    $paytmperc=$Globalpaytmperc;
                    $meeffortperc=$Globalmeeffortperc;
                    $ContractDoc=$ContractDocDB;
		}
		// echo $cardperc;
		
		?>
	  </td>
		
        <form name="frmperc" action="" method="post" enctype="multipart/form-data" onsubmit="return checkper(this,<?php echo $EventsOfMonth[$i]['Id'];?>);">
          <input type="hidden" name="PEventId" value="<?php echo $EventsOfMonth[$i]['Id'];?>"  />
           <td align="left" valign="middle" class="helpBod" ><input size="6" type="text" name="overall" value="<?php echo $EventsOfMonth[$i]['perc'];?>" id="overall"  />   </td>
		
     <td align="left" valign="middle" class="helpBod" ><input size="6" type="text" name="cardperc" value="<?php echo $cardperc; ?>" id="cardperc"  />   </td>
       <td align="left" valign="middle" class="helpBod" ><input size="6" type="text" name="codperc" value="<?php echo $codperc; ?>" id="codperc"  />   </td>
         <td align="left" valign="middle" class="helpBod" ><input size="6" type="text" name="counterperc" value="<?php echo $counterperc; ?>" id="counterperc"  />   </td>
           <td align="left" valign="middle" class="helpBod" ><input size="6" type="text" name="paypalperc" value="<?php echo $paypalperc; ?>" id="paypalperc"  />   </td>
<!--           <td align="left" valign="middle" class="helpBod" ><input size="6" type="text" name="mobikwikperc" value="<?php echo $mobikwikperc; ?>" id="mobikwikperc"  />   </td>
           
           <td align="left" valign="middle" class="helpBod" ><input size="6" type="text" name="paytmperc" value="<?php echo $paytmperc; ?>" id="paytmperc"  />   </td>-->
           
            <td align="left" valign="middle" class="helpBod" ><input size="6" type="text" name="meeffortperc" value="<?php echo $meeffortperc; ?>" id="meeffortperc"  />   </td>
           <td align="left" valign="middle" class="helpBod" >
           <?php
		   if(count($ResExQuery)>0){
		   for($i=0;$i<count($ResExQuery);$i++){
			
			   ?><a href="extracharges_edit.php?id=<?php echo $ResExQuery[$i]['exId'];?>" target="_blank" title="Click here to update Extra Charge for this Event"><?php echo $ResExQuery[$i]['exAmount']." ("; ?>
               <?php echo ($ResExQuery[$i]['exType']==1)?" % ":" Flat "; echo " )";?></a>,
            <?php 
		    }
		   }else
		   {
			   ?><a href="addextracharges.php?EventId=<?php echo $EventsOfMonth[$i]['Id'];?>" target="_blank" title="Click here to Add Extra Charge for this Event">N/A</a> <?php
		   }
		   ?>
           </td>
           
           
           <!--td align="left" valign="middle" class="helpBod" >
           <?php
		   /*
		   if(strlen($ContractDoc)>0)
		   {
			   if(strcmp($hostname,'www.meraevents.com')==0 || strcmp($hostname,'meraevents.com')==0)
			   {
					if($commId>616){$serverPath=_HTTP_CF_ROOT;}else{$serverPath=_HTTP_SITE_ROOT;}
			   }
			   else
			   {
				    if($commId>78){$serverPath=_HTTP_CF_ROOT;}else{$serverPath=_HTTP_SITE_ROOT;}
			   }
			   ?><a href="<?php echo $serverPath.'/content/'.$ContractDoc; ?>" target="_blank" title="Click to View the Document">View</a> <?php
		   }
		   else
		   {
			   
			  if($comcount>0){ $commIdParam='&commId='.$commId; }else{$commIdParam=NULL;} 
			   ?><a href="contract-doc.php?EventId=<?php echo $EventsOfMonth[$i]['Id'].$commIdParam;  ?>" title="Click to upload the Contract document for this Event">Add Doc</a> <?php
		   }*/
		   ?>
           </td-->
           
           
           
       <td align="left" valign="middle" class="helpBod" >
           <?php  if($comcount>0){ ?><input type="submit" id='Save<?php echo $EventsOfMonth[$i]['Id'];?>' name="Save" value="Save" /> <?php  }else{ ?> <input type="submit" name="Save" id='Save<?php echo $EventsOfMonth[$i]['Id'];?>' value="Add" /><?php  }?>
       </td>
            <td align="left" valign="middle" class="helpBod" >
                <?php if($EventsOfMonth[$i]['SalesId'] != '') {
                    echo $EventsOfMonth[$i]['SalesName'];
                } else {
                    echo "None";
                } ?>
            </td>
        </form>
	</tr>
	<?php 
	} //ends for loop
	?>
</table>
<?php 
	} //ends if condition
	else if(count($EventsOfMonth) == 0)
	{
?>
	<table width="90%" align="center">
		<tr>
		  <td width="100%" align="left" valign="middle">No match record found.</td>
	  </tr>
	</table>
<?php
	}
?>



<!-------------------------------Events of the Month PAGE ENDS HERE---------------------------------------------------------------></td>
		</tr>
	</table>
</div>	
</body>
</html>
