<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
		<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
        <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>	
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
<script language="javascript">
	
	
function ClickHereToPrintpass()
{
	var DocumentContainer = document.getElementById('PrintTable');
	var WindowObject = window.open('PrintWindow',null,'width=900,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes');
	WindowObject.document.writeln(DocumentContainer.innerHTML);
	WindowObject.document.close();
	WindowObject.focus();
	WindowObject.print();
	WindowObject.close();
}

	</script>
	</head>	
<body style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
	<?php include('templates/header.tpl.php'); ?>				
</div>
	<table width="103%" cellpadding="0" cellspacing="0" style="width:100%; height:495px;">
  <tr>
	<td width="150" style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
		<?php include('templates/left.tpl.php'); ?>	</td>
	<td width="848" style="vertical-align:top">
    <form action="" method="post" name="frmEofMonth">
    <table width="70%" align="center" class="tblcont">
 <tr><td align="center">Mobikwik Settlement Report</td></tr>
	<tr>
	  <td width="35%" align="left" valign="middle">Deposited Date:&nbsp;<input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	
	 
	<tr>
 
    <!--tr><td colspan="3">Select an Event <select name="EventId" id="EventId" >
        <option value="">Select Event</option>
        <?php
		$TotalEventQueryRES = count($EventQueryRES);

		for($i=0; $i < $TotalEventQueryRES; $i++)
		{
		?>
         <option value="<?php echo $EventQueryRES[$i]['eventid']; ?>" <?php if($EventQueryRES[$i]['eventid']==$EventId){?> selected="selected" <?php }?>><?php echo $EventQueryRES[$i]['Details'];?></option>
         <?php }?>
      </select></td></tr-->
      <tr><td>Event Id:&nbsp;<input type="text" name="eventIdSrch" id="eventIdSrch" value="<?php echo $EventId; ?>"></td></tr>
        <tr><td colspan="3"></td></tr>
		<tr><td colspan="3">Select Organizer <select name="SerEventName" id="SerEventName" >
       <option value="">Select Organizer Name</option>	
				<?php 
				$SelectOrgNames1="SELECT name as orgDispName, id as Id FROM organization where status=1  ORDER BY name ASC";
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
      <tr> <td width="30%" colspan="2" style="padding:10px;" align="center" valign="middle"><input type="submit" name="submit" value="Show Report" onclick="return SEdt_validate();" /></td></tr>
</table>
</form>
	<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans4').style.display='block';
</script>

<div id="PrintTable">
<table width='100%' border='1' cellpadding='0' cellspacing='0'  >
			<thead>
                            <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='3%' align='center'>Sr. No.</td>
			

            <td class='tblinner' valign='middle' width='46%' align='center'>Event Details</td>
            <td class='tblinner' valign='middle' width='7%' align='center'>Event Id</td>
             <td class='tblinner' valign='middle' width='14%' align='center'>OrgName</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>Reg No.</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>TotalTransAmount </td>
            <td class='tblinner' valign='middle' width='5%' align='center'>RefundedAmount </td>            
             <td class='tblinner' valign='middle' width='5%' align='center'>VerifiedAmount </td>
             <td class='tblinner' valign='middle' width='5%' align='center'>DepositedAmount </td>
            
         
            
          </tr>
        </thead>
        
        <?php	
		 $totTransAmt=NULL;
                 $totRefndAmt=NULL;
                 $totVrfdAmt=NULL;
                 $totDepAmt=NULL;
                 
		for($i = 0; $i < count($TransactionRES); $i++)
	{ ?>
		<tr>
			<td class='tblinner' valign='middle' width='3%' align='center' ><font color='#000000'><?php echo $cntTransactionRES;?></font></td>
			<td class='tblinner' valign='middle' width='15%' align='center' ><font color='#000000'><?php echo $Global->GetSingleFieldValue("select title from event where deleted=0 and id='".$TransactionRES[$i]['EventId']."'");?></font></td>
            <td class='tblinner' valign='middle' width='10%' align='center' ><font color='#000000'><?php echo $TransactionRES[$i]['EventId'];?></font></td>
			<td class='tblinner' valign='middle' width='14%' align='left'><font color='#000000'><?php echo $Global->GetSingleFieldValue("select u.company from user as u,event as e where u.id=e.ownerid and e.id='".$TransactionRES[$i]['EventId']."'");?></font></td>
            <?php
		$TotaltAmountcard=NULL;
		$TotalrAmountcard=NULL;
		$TotalAmountverified=NULL;
                $TotalAmountdeposited=NULL;
		$Totalreg=0;
		$Totalqty=0;
                                                    
		$CountQueryt = "SELECT s.id AS Id,s.signupdate AS SignupDt,s.quantity Qty, (s.totalamount/s.quantity) AS Fees, s.eventid AS EventId, c.code AS currencyCode FROM eventsignup as s INNER JOIN currency c ON c.id=s.fromcurrencyid where 1  and s.depositdate!='0000-00-00 00:00:00'  AND  s.paymenttransactionid != 'A1' AND s.paymentgatewayid='5' $dates and s.eventid='".$TransactionRES[$i]['EventId']."'"; 
            $CountQueryRESt=$Global->SelectQuery($CountQueryt); 
            for($j = 0; $j < count($CountQueryRESt); $j++)
            {
               $currencyCode=$CountQueryRESt[$j]['currencyCode'];
               $Totalreg=$Totalreg+1;
               $Totalqty=$Totalqty+$CountQueryRESt[$j][Qty];
               $TotaltAmountcard[$currencyCode]+=($CountQueryRESt[$j][Qty]*$CountQueryRESt[$j][Fees]);
               $totTransAmt[$currencyCode]+=($CountQueryRESt[$j][Qty]*$CountQueryRESt[$j][Fees]);
            }
            //Refunded entries we check the settlement date
            $dates2=" and date(s.settlementdate) = '".$yesterdaySDate."' ";
            $CountQueryr = "SELECT s.id AS Id,s.signupdate AS SignupDt,s.quantity Qty, (s.totalamount/s.quantity) AS Fees, s.eventid AS EventId, c.code AS currencyCode  FROM eventsignup as s INNER JOIN currency c ON c.Id=s.fromcurrencyid where 1 and s.depositdate!='0000-00-00 00:00:00' AND s.paymentgatewayid='5' $dates2  AND (s.totalamount != 0 AND s.paymenttransactionid != 'A1'   and s.paymentstatus = 'Refunded') and s.eventid='".$TransactionRES[$i]['EventId']."'"; 
	    $CountQueryRESr=$Global->SelectQuery($CountQueryr); 
            for($j = 0; $j < count($CountQueryRESr); $j++)
            { 
                 $currencyCode=$CountQueryRESr[$j]['currencyCode'];
                 $TotalrAmountcard[$currencyCode]+=($CountQueryRESr[$j][Qty]*$CountQueryRESr[$j][Fees]);
                 $totRefndAmt[$currencyCode]+=($CountQueryRESr[$j][Qty]*$CountQueryRESr[$j][Fees]);
           }
	
           $CountQueryVer = "SELECT s.id AS Id,s.signupdate AS SignupDt,s.quantity Qty, (s.totalamount/s.quantity) AS Fees, s.eventid AS EventId, c.code AS currencyCode FROM eventsignup as s INNER JOIN currency c ON c.Id=s.fromcurrencyid where 1 and s.depositdate!='0000-00-00 00:00:00'  $dates  AND (s.totalamount != 0 AND s.paymenttransactionid != 'A1' AND s.paymentgatewayid='5' and s.paymentstatus = 'Verified') and s.eventid='".$TransactionRES[$i]['EventId']."'"; 
	   $CountQueryRESVer=$Global->SelectQuery($CountQueryVer); 
           for($j = 0; $j < count($CountQueryRESVer); $j++)
           {
                $currencyCode=$CountQueryRESVer[$j]['currencyCode'];
                $TotalAmountverified[$currencyCode]+=($CountQueryRESVer[$j][Qty]*$CountQueryRESVer[$j][Fees]);
                $totVrfdAmt[$currencyCode]+=($CountQueryRESVer[$j][Qty]*$CountQueryRESVer[$j][Fees]);
           }
	
            foreach($TotalAmountverified as $currencyCode=>$value){
               $TotalAmountdeposited[$currencyCode]=round($value-($value*0.0238),2);
               $totDepAmt[$currencyCode]+=round($value-($value*0.0238),2);
            }
			?>
			<td class='tblinner' valign='middle' width='4%' align='center'><font color='#000000'><?php echo $Totalreg;?></font></td>     		
			<td class='tblinner' valign='middle' width='5%' align='center'><font color='#000000'><?php echo $Totalqty;?></font></td>
                        <td class='tblinner' valign='middle' width='10%' align='right'><font color='#000000'> <?php echo  totalStrWithCurrencies($TotaltAmountcard);?></font></td>
            <td class='tblinner' valign='middle' width='15%' align='right'><font color='#000000'> <?php echo totalStrWithCurrencies($TotalrAmountcard);?></font></td>
            
            <td class='tblinner' valign='middle' width='12%' align='right'><font color='#000000'> <?php echo totalStrWithCurrencies($TotalAmountverified);?></font></td>
            <td class='tblinner' valign='middle' width='12%' align='right'><font color='#000000'> <?php echo totalStrWithCurrencies($TotalAmountdeposited);?></font></td>
            
            
          
           
          </tr>
          <?php
        $cntTransactionRES++;
	}
        $totTransAmtStr=  totalStrWithCurrencies($totTransAmt);
        $totRefndAmtStr=  totalStrWithCurrencies($totRefndAmt);
        $totVrfdAmtStr=  totalStrWithCurrencies($totVrfdAmt);
        $totDepAmtStr=  totalStrWithCurrencies($totDepAmt);
        ?>
	<tr><td colspan="6" style="line-height:30px;"><strong>Total  Transactions Amount:</strong></td><td  align='right'><font color='#000000'> <?php echo $totTransAmtStr;?></font></td><td  align='right'><font color='#000000'> <?php echo $totRefndAmtStr;?></font></td><td  align='right'><font color='#000000'> <?php echo $totVrfdAmtStr;?></font></td><td  align='right'><font color='#000000'> <?php echo $totDepAmtStr;?></font></td></tr>
    
     
</table>
</div>
<table width='90%'>
 <tr><td colspan="9" align="center">
<input src="<?php echo _HTTP_CF_ROOT;?>/ctrl/images/print.jpg" title="Print" name="button" value="Print" type="image" onclick="ClickHereToPrintpass()"></td></tr>
</table>
<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	<?php
        
        	if($_REQUEST['txtSDt']!=""){
                        $TAmount=$TDeposit=NULL;
        
                        if(strlen(trim($EventId))>0)
                        {
                            $eventcondition=" and s.eventid=".$EventId ; //adding condition to query related to Event
                        }

                        $DepositQuery = "SELECT s.id AS Id,s.signupdate AS SignupDt,s.quantity Qty, (s.totalamount/s.quantity) AS Fees, s.eventid AS EventId, c.code AS currencyCode FROM eventsignup AS s INNER JOIN currency c ON c.id=s.fromcurrencyid where 1 and s.depositdate!='0000-00-00 00:00:00'  AND  s.paymenttransactionid != 'A1' and s.paymentstatus!='Refunded' AND s.paymentgatewayid='5' ". $dates . $eventcondition  ." order by s.eventid DESC"; 
                        $DepositRES=$Global->SelectQuery($DepositQuery);
                        if(count($DepositRES)>0)
                        {
                                ?>
                        <p>&nbsp;</p>
                                <table width='90%' border='1' cellpadding='0' cellspacing='0' >
                                                <thead>
                                    <tr bgcolor='#94D2F3'>
                                                <td class='tblinner' valign='middle' width='3%' align='center'>Sr. No.</td>
                                                        <td class='tblinner' valign='middle' width='3%' align='center'>Receipt No.</td>
                                      <td class='tblinner' valign='middle' width='12%' align='center'>RegDt.</td>
                                    <td class='tblinner' valign='middle' width='15%' align='center'>Event Details</td>
                                     <td class='tblinner' valign='middle' width='8%' align='center'>OrgName</td>
                                    <td class='tblinner' valign='middle' width='6%' align='center'>Amount</td>
                                    <td class='tblinner' valign='middle' width='6%' align='center'>Deposited</td>





                                  </tr>
                                </thead>

                                <?php	

                                        for($i = 0; $i < count($DepositRES); $i++)
                                { 
                                $am=$DepositRES[$i]['Fees']*$DepositRES[$i]['Qty'];
                                $currencyCode=$DepositRES[$i]['currencyCode'];
                                ?>
                                        <tr>
                                                <td class='tblinner' valign='middle' width='3%' align='center' ><font color='#000000'><?php echo $i+1;?></font></td>
                                    <td class='tblinner' valign='middle' width='3%' align='center' ><font color='#000000'><?php echo $DepositRES[$i]['Id'];?></font></td>
                                    <td class='tblinner' valign='middle' width='3%' align='center' ><font color='#000000'>
                                         <?php  echo $common->convertTime($DepositRES[$i]['SignupDt'],DEFAULT_TIMEZONE,TRUE); ?>
                                        </font></td>
                                                <td class='tblinner' valign='middle' width='15%' align='center' ><font color='#000000'><?php echo $Global->GetSingleFieldValue("select title from event where deleted=0 and id='".$DepositRES[$i]['EventId']."'");
                                                echo " (".$DepositRES[$i]['EventId'].")";
                                                ?></font></td>
                                                <td class='tblinner' valign='middle' width='8%' align='left'><font color='#000000'><?php echo $Global->GetSingleFieldValue("select u.company from user as u, event as e where u.id=e.ownerid and e.id='".$DepositRES[$i]['EventId']."'");?></font></td>


                                                <td class='tblinner' valign='middle' width='6%' align='right'><font color='#000000'><?php echo $currencyCode." ".$am;?></font></td> 
                                   <?php if($DepositRES[$i][SignupDt] > '2012-03-31 11:59:59')
                                                {  ?>
                                                 <td class='tblinner' valign='middle' width='6%' align='right'><font color='#000000'><?php echo $currencyCode." ".round($am-($am*0.0241),2);?></font></td>    	
                                                <?php }else{ ?>
                                                 <td class='tblinner' valign='middle' width='6%' align='right'><font color='#000000'><?php echo $currencyCode." ".round($am-($am*0.0238),2);?></font></td>    	
                                        <?php	} ?>






                                  </tr>
                                  <?php
                                $TAmount[$currencyCode]+=$am;
                                         if($DepositRES[$i][SignupDt] > '2012-03-31 11:59:59')
                                                { 
                                        $TDeposit[$currencyCode]+=round($am-($am*0.0241),2);
                                        }else{
                                        $TDeposit[$currencyCode]+=round($am-($am*0.0238),2);
                                        }
                                }
                                  $TAmountStr=  totalStrWithCurrencies($TAmount);
                                  $TDepositStr=  totalStrWithCurrencies($TDeposit);
                                ?>
                                <tr><td colspan="5" style="line-height:30px;"><strong>Total   Amount:</strong></td><td  align='right'><font color='#000000'> <?php echo $TAmountStr;?></font></td><td  align='right'><font color='#000000'> <?php echo $TDepositStr;?></font></td></tr>


                        </table>



                            <?php } } ?>
	</div>
	</td>
  </tr>
</table>
	</div>	
</body>
</html>