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
<script>
function clearform(){
document.getElementById('email').value="";
document.getElementById('recptno').value="";
document.getElementById('transid').value="";

}

 function validateEventIDForm()
{
    var recptno = document.getElementById('recptno').value;
   
    if ((isNaN(recptno) || recptno <= 0) && recptno!=="") {
        
        alert("Please enter valid recptno ");
        document.getElementById('recptno').focus();
        return false;
    }
    //console.log();
 
    return true;
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
            <form action="" method="post" name="frmEofMonth" onsubmit="return validateEventIDForm();">
    <table width="90%" align="center" class="tblcont" cellpadding="3" cellspacing="3">
      <tr>
<td width="35%">Email-Id.
  <input type="text" name="email" id="email" value="<?php echo $_REQUEST[email];?>" /></td>
<td width="35%">Receipt No. <input type="text" name="recptno" id="recptno" value="<?php echo $_REQUEST['recptno'];?>" />    </td>
<td width="35%">Transaction-Id. <input type="text" name="transid" id="transid" value="<?php echo $_REQUEST['transid'];?>" />    </td></tr>
<tr><td colspan="3"> GharPay-Id. <input type="text" name="gharpayid" id="gharpayid" value="<?php echo $_REQUEST['gharpayid'];?>" />
    <tr><td colspan="3" align="center"><input type="submit" name="submit" value="Show Report"  />&nbsp;&nbsp;&nbsp; <input type="button" name="rest" value="Clear Search" onclick="clearform();" /> </td></tr>
</table>
</form>
	<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
	 <div align="center"><font color="#0000FF"><b><?php echo $_REQUEST[msg];?></b></font></div>
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans5').style.display='block';
</script>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
<?php  if($usrRES[0]['Name']!="" ){?>
<tr><td align="right"><table width="30%" border="1" cellspacing="2" cellpadding="2">
  <tr>
    <td><strong>Person Details</strong></td>
  </tr>
   
  <tr>
    <td class='tblinner' valign='middle' width='18%' align='left'><font color='#000000'>
        <?php  if($usrRES[0]['Name']!=""){ echo $usrRES[0]['Name']; }?> <br/>
             <?php if($usrRES[0]['EMail']!=""){ echo $usrRES[0]['EMail']; } 
              ?> <br/>
            <?php if($usrRES[0]['Phone']!=""){ echo $usrRES[0]['Phone']; }  ?> </font></td>
  </tr>
  <!--tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><a href="#">Click here to view complete Profile</a></td>
  </tr-->
</table>
</td></tr><?php }?>
<tr><td><strong>Successfull Card Transaction</strong></td>
</tr>
  <tr>
    <td><table width='100%' border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='3%' align='center'>Sr. No.</td>
			<td class='tblinner' valign='middle' width='8%' align='center'>Receipt No.</td>
            <td class='tblinner' valign='middle' width='10%' align='center'>Date</td>
           <td class='tblinner' valign='middle' width='17%' align='center'>Event Details</td>
            <td class='tblinner' valign='middle' width='10%' align='center'>Transaction No.</td>
            <td class='tblinner' valign='middle' width='10%' align='center'>Payment Gateway</td>
            <td class='tblinner' valign='middle' width='4%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='4%' align='center'>Paid</td>
            <td class='tblinner' valign='middle' width='8%' align='center'>Converted Amt<?php if(strlen($sameCurrencyEBS)>0){  echo "<span align=center><br>(".$sameCurrencyEBS.")</span>";}?></td>
             <td class='tblinner' valign='middle' width='8%' align='center'>Status</td>
            <td class='tblinner' valign='middle' width='22%' align='center'>Action</td>
            
          </tr>
        </thead>
        
        <?php	
		$cntTransactionRES=1;
		for($i = 0; $i < count($TransactionRES); $i++)
	{ 
                    $currencyCode=$TransactionRES[$i]['currencyCode']; 
                    if($TransactionRES[$i]['conversionRate']!=1 && $TransactionRES[$i]['currencyCode']!="INR"){
                         $currencyCode="INR";
                     }
                     if($TransactionRES[$i]['conversionRate']==1 && $TransactionRES[$i]['paypal_converted_amount'] > 0){
                         $currencyCode="USD";
                     }
        ?>
		<tr>
			<td class='tblinner' valign='middle' width='3%' align='center' ><font color='#000000'><?php echo $cntTransactionRES;?></font></td>
			<td class='tblinner' valign='middle' width='8%' align='center'><font color='#000000'><?php echo $TransactionRES[$i]['Id'];?></font></td>
			<td class='tblinner' valign='middle' width='10%' align='center' ><font color='#000000'>
                           <?php 
                        echo $common->convertTime($TransactionRES[$i]['SignupDt'],DEFAULT_TIMEZONE,TRUE);
                        
                        ?>     
                           </font></td>
           	<td class='tblinner' valign='middle' width='17%' align='left'><font color='#000000'><?php echo $TransactionRES[$i]['Title'];?></font></td>
			<td class='tblinner' valign='middle' width='10%' align='left'><font color='#000000'>TR:<?php echo $TransactionRES[$i]['PaymentTransId'];?></font></td>     		
                        <td class='tblinner' valign='middle' width='10%' align='center'><font color='#000000'><?php echo $TransactionRES[$i]['PaymentGateway'];?></font></td>          
			<td class='tblinner' valign='middle' width='4%' align='center'><font color='#000000'><?php echo $TransactionRES[$i]['Qty'];?></font></td>
            
            <td class='tblinner' valign='middle' width='8%' align='center'>
                <font color='#000000'><?php echo $TransactionRES[$i]['currencyCode']." ".($TransactionRES[$i]['Fees'] ); ?></font>
            </td>
            
        <td class='tblinner' valign='middle' width='8%' align='center'>
            <font color='#000000'>
                <?php
                if ($TransactionRES[$i]['conversionRate'] > 1) {
                    if ($TransactionRES[$i]['paypal_converted_amount'] > 0) {
                        $trConvertedAmt = round($TransactionRES[$i]['paypal_converted_amount'] *  $TransactionRES[$i]['conversionRate']*$TransactionRES[$i]['Qty'], 2);
                    } else {
                        $trConvertedAmt = $TransactionRES[$i]['Fees'] * $TransactionRES[$i]['conversionRate'];
                    }
                } else {
                    if ($TransactionRES[$i]['paypal_converted_amount'] > 0) {
                        $trConvertedAmt = $currencyCode . " " . round($TransactionRES[$i]['paypal_converted_amount'] * $TransactionRES[$i]['Qty'], 2);
                    } else {
                        $trConvertedAmt = $currencyCode . " " . $TransactionRES[$i]['Fees'];
                    }
                }


                echo $trConvertedAmt;
                ?>
            </font></td>
            <td class='tblinner' valign='middle' width='8%' align='center'><font color='#000000'>
                <?= $TransactionRES[$i]['eChecked']; ?></font>
            </td>
			<?php
			$DelQuery = "SELECT email as Email FROM user u WHERE  u.Id='".$TransactionRES[$i]['UserId']."'";
   		    $ResDelQuery = $Global->SelectQuery($DelQuery);

			?>
            <td class='tblinner' valign='middle' width='22%' align='center'>
                <font color='#000000'>
                   <form id="form<?php echo $i?>" action="<?php echo _HTTP_SITE_ROOT;?>/printpass" method="post" target="_blank">
                            <a href="javascript:;" onclick="document.getElementById('form<?php echo $i?>').submit();">Print Delegate Pass</a>
                            <input type="hidden" name="regno" value='<?php echo $TransactionRES[$i]['Id'];?>'/>
                            <input type="hidden" name="useremail" value='<?php echo $ResDelQuery[0]['Email'];?>'/>
                    </form>
                    
                    
                </font><br/>
             <?php $sqlcancom="select count(id) as commentcount from comment where eventsignupid=".$TransactionRES[$i]['Id'];
			 $commentcount=$Global->SelectQuery($sqlcancom);
			 ?>
           <a class="lbOn" href="transcomment.php?TransId=<?php echo $TransactionRES[$i]['Id'];?>&pagename=OnlyCancelTrans&EventId=<?php echo $_REQUEST[EventId];?>&Status=<?php echo $_REQUEST[Status];?>&email=<?php echo $_REQUEST[email];?>&recptno=<?php echo $_REQUEST[recptno];?>&transid=<?php echo $_REQUEST[transid];?>">ViewComments(<?php echo $commentcount[0][commentcount];?>)</a><br/>
           
           <a class="lbOn" href="emailtrans.php?TransId=<?php echo $TransactionRES[$i]['Id'];?>&email=<?php echo $_REQUEST[email];?>&recptno=<?php echo $_REQUEST[recptno];?>&transid=<?php echo $_REQUEST[transid];?>">Send Message</a><br/>
            
           <a class="lbOn" href="smstrans.php?TransId=<?php echo $TransactionRES[$i]['Id'];?>&email=<?php echo $_REQUEST[email];?>&recptno=<?php echo $_REQUEST[recptno];?>&transid=<?php echo $_REQUEST[transid];?>">Send Sms</a><br/>
           <a href="CheckReg_edit.php?regid=<?php echo $TransactionRES[$i]['Id']?>&email=<?php echo $_REQUEST[email];?>&recptno=<?php echo $_REQUEST[recptno];?>&transid=<?php echo $_REQUEST[transid];?>">Edit</a>
            </td>
         
         
          </tr>
          <?php $cntTransactionRES++;
			}?>
	
  

</table></td>
  </tr>
  <tr><td><strong>Incomplete Card Transaction</strong></td>
  </tr>
  <tr>
    <td><table width='100%' border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='5%' align='center'>Sr. No.</td>
			<td class='tblinner' valign='middle' width='10%' align='center'>Receipt No.</td>
            <td class='tblinner' valign='middle' width='13%' align='center'>Date</td>
            <td class='tblinner' valign='middle' width='22%' align='center'>Event Details</td>
            <td class='tblinner' valign='middle' width='17%' align='center'>Gateway.</td>
            <td class='tblinner' valign='middle' width='14%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='14%' align='center'>Paid</td>
            <td class='tblinner' valign='middle' width='8%' align='center'>Converted Amt<?php if(strlen($sameCurrencyInc)>0){  echo "<span align=center><br>(".$sameCurrencyInc.")</span>";}?></td>
            <td class='tblinner' valign='middle' width='11%' align='center'>Action</td>
          </tr>
        </thead>
        
        <?php	
		
		$Fail=1;
		for($i = 0; $i < count($FailTransactionRES); $i++)
	{ ?>
		<tr>
			<td class='tblinner' valign='middle' width='5%' align='center' ><font color='#000000'><?php echo $Fail;?></font></td>
			<td class='tblinner' valign='middle' width='10%' align='center'><font color='#000000'><?php echo $FailTransactionRES[$i]['Id'];?></font></td>
			<td class='tblinner' valign='middle' width='13%' align='center' ><font color='#000000'>
                             <?php 
                        echo $common->convertTime($FailTransactionRES[$i]['SignupDt'],DEFAULT_TIMEZONE,TRUE);
                        
                        ?>  </font></td>
			<td class='tblinner' valign='middle' width='22%' align='left'><font color='#000000'><?php echo $FailTransactionRES[$i]['Title'];?></font></td>
			<td class='tblinner' valign='middle' width='17%' align='left'><font color='#000000'><?php echo $FailTransactionRES[$i]['PaymentGateway'];?></font></td>     		
			<td class='tblinner' valign='middle' width='14%' align='right'><font color='#000000'><?php echo $FailTransactionRES[$i]['Qty'];?></font></td>
            
                        <td class='tblinner' valign='middle' width='8%' align='right'><font color='#000000'>
                                    <?php echo $FailTransactionRES[$i]['currencyCode'] . " " . ($FailTransactionRES[$i]['Fees'] ); ?></font></td>

                <td class='tblinner' valign='middle' width='8%' align='right'><font color='#000000'>
                        <?php if (strlen($sameCurrencyInc) > 0) {
                            echo ($FailTransactionRES[$i]['Fees'] );
                        } else {
                            echo ($FailTransactionRES[$i]['currencyCode'] . " " . ($FailTransactionRES[$i]['Fees'] * $FailTransactionRES[$i]['Qty']));
                        } ?></font></td>
          
            <td class='tblinner' valign='middle' width='11%' align='center'><a href="CheckReg_edit.php?regid=<?php echo $FailTransactionRES[$i]['Id']?>&email=<?php echo $_REQUEST[email];?>&recptno=<?php echo $_REQUEST[recptno];?>&transid=<?php echo $_REQUEST[transid];?>">Edit</a></td>
          </tr>
          <?php
		$Fail++;
	} ?>
	
  

</table></td>
  </tr>
  
  <tr><td><strong> COD Transaction</strong></td>
  </tr>
  <tr>
    <td><table width='100%' border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='5%' align='center'>Sr. No.</td>
			<td class='tblinner' valign='middle' width='10%' align='center'>Receipt No.</td>
            <td class='tblinner' valign='middle' width='13%' align='center'>Date</td>
            <td class='tblinner' valign='middle' width='22%' align='center'>Event Details</td>
            <td class='tblinner' valign='middle' width='17%' align='center'>GharPay-Id.</td>
            <td class='tblinner' valign='middle' width='14%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='8%' align='center'>Amount<?php if(strlen($sameCurrencyCOD)>0){  echo "<span align=center><br>(".$sameCurrencyCOD.")</span>";}?></td>
            <td class='tblinner' valign='middle' width='8%' align='center'>Status</td>
          
          </tr>
        </thead>
        
        <?php	
		
		$COD=1;
		for($i = 0; $i < count($CODTransactionRES); $i++)
	{ ?>
		<tr>
			<td class='tblinner' valign='middle' width='5%' align='center' ><font color='#000000'><?php echo $COD;?></font></td>
			<td class='tblinner' valign='middle' width='10%' align='center'><font color='#000000'><?php echo $CODTransactionRES[$i]['Id'];?></font></td>
			<td class='tblinner' valign='middle' width='13%' align='center' ><font color='#000000'><?php echo $CODTransactionRES[$i]['SignupDt'];?></font></td>
			<td class='tblinner' valign='middle' width='22%' align='left'><font color='#000000'><?php echo $CODTransactionRES[$i]['Title'];?></font></td>
			<td class='tblinner' valign='middle' width='17%' align='left'><font color='#000000'><?php echo $CODTransactionRES[$i]['GharPayId'];?></font></td>     		
			<td class='tblinner' valign='middle' width='14%' align='right'><font color='#000000'><?php echo $CODTransactionRES[$i]['Qty'];?></font></td>
                        <td class='tblinner' valign='middle' width='8%' align='right'><font color='#000000'><?php if(strlen($sameCurrencyCOD)>0){  echo $CODTransactionRES[$i]['Fees'] * $CODTransactionRES[$i]['Qty'];}else{$CODTransactionRES[$i]['currencyCode']." ".($CODTransactionRES[$i]['Fees'] * $CODTransactionRES[$i]['Qty']);}?></font></td>
            <td class='tblinner' valign='middle' width='8%' align='right'><font color='#000000'><?php echo $CODTransactionRES[$i]['Status'];?></font></td>
          
           
          </tr>
          <?php
		$COD++;
	}?>
	
  

</table></td>
  </tr>
</table>

<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	
	
	</div>
	</td>
  </tr>
</table>
	</div>	
</body>
</html>
<script type="text/javascript" src="<?php echo _HTTP_SITE_ROOT?>/lightbox/prototype.js"></script>
  <script type="text/javascript" src="<?php echo _HTTP_SITE_ROOT?>/lightbox/lightbox.js"></script>
	<link type="text/css" rel="stylesheet" href="<?php echo _HTTP_SITE_ROOT?>/lightbox/lightbox.css" media="screen,projection" />
