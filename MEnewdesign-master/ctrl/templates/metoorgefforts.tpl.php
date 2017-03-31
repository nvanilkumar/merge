<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>	
        <link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
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
	
	var promotercode=document.getElementById('promotercode'+sId).value; 
	
	var strtdt = document.frmEofMonth.txtSDt.value;
		var enddt = document.frmEofMonth.txtEDt.value;
		var recptno = document.frmEofMonth.recptno.value;
		var EventId=document.frmEofMonth.eventIdSrch.value;		
	window.location="metoorgefforts.php?value="+promotercode+"&sId="+sId+"&txtSDt="+strtdt+"&txtEDt="+enddt+"&recptno="+recptno+"&EventId="+EventId+"&submit=Show+Report";
	}
	function convertall(){
		 if (confirm("Are you sure to move all efforts to organizer!") == true) {
		var strtdt = document.frmEofMonth.txtSDt.value;
		var enddt = document.frmEofMonth.txtEDt.value;
		var recptno = document.frmEofMonth.recptno.value;
		var EventId=document.frmEofMonth.eventIdSrch.value;	
		window.location="metoorgefforts.php?value=organizer&txtSDt="+strtdt+"&txtEDt="+enddt+"&recptno="+recptno+"&EventId="+EventId+"&convert=all&&submit=Show+Report";
		 }
		}
	</script>
<style>
    .special_class{background-color: #AFE49F;}
</style>

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
    <form action="" method="get" name="frmEofMonth">
    <table width="80%" align="center" class="tblcont">
    	<?php
            if(isset($sessData['failed1']) || isset($sessData['failed2']) || isset($sessData['failed3'])){
		?>
        	<tr><td colspan="3"><h3 style="color:#06F;"><?=$sessData['failed1'].'<br>'.$sessData['failed2'].'<br>'.$sessData['failed3'].'<br>'.$sessData['success'];?></h3></td></tr>
        <?php
			//unset($_SESSION['capturedTransStatus']);
		}else if(count($sessData)>0){ ?>
                    <tr><td colspan="3"><h3 style="color:#06F;">Updation successfull.</h3></td></tr>
               <?php }
    ?>
	<tr>
	  <td width="35%" align="left" valign="middle">Start Date:&nbsp;<input type="text" id="txtSDt" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="35%" align="left" valign="middle">End Date:&nbsp;<input type="text" id="txtEDt" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	
	<tr>
    <tr><td>Receipt No. <input type="text" name="recptno" id="recptno" value="<?=$_REQUEST[recptno];?>" />
    </td> <td>Event Id:&nbsp;<input type="text" name="eventIdSrch" id="eventIdSrch" value=<?php if($_REQUEST['eventIdSrch']!=""){ echo $_REQUEST['eventIdSrch'] ;}else{ echo $_REQUEST[EventId];}?>></td></tr>
 
            <tr><td>&nbsp;&nbsp;<input type="submit" name="submit" value="Show Report" onclick="return SEdt_validate();" />
               
                    
                </td></tr>
</table>
</form>
	<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans4').style.display='block';
</script>
<p><?php if($msg!='') echo $msg; 

if(count($TransactionRES)>0){
echo '<input type="button" name="allefforts" value="Convert All to Organizer Efforts" onclick="convertall()" />';	
	
}
?></p>

<table width='95%' border='1' cellpadding='0' cellspacing='0' >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='3%' align='center'>Sr.No.</td>
			<td class='tblinner' valign='middle' width='10%' align='center'>Receipt No.</td>
            <td class='tblinner' valign='middle' width='16%' align='center'>Date</td>
            <td class='tblinner' valign='middle' width='32%' align='center'>Event Details</td>
            <td class='tblinner' valign='middle' width='10%' align='center'>Transaction No.</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>Payment<br />Gateway</td>
            <td class='tblinner' valign='middle' width='5%' align='center'>Qty</td>
            <td class='tblinner' valign='middle' width='15%' align='center'>Paid</td>
            <td class='tblinner' valign='middle' width='11%' align='center'>Ver/Not</td>
            <td class='tblinner' valign='middle' width='8%' align='center'>Promoter Code</td>
            <td class='tblinner' valign='middle' width='11%' align='center'>Action</td>
          </tr>
        </thead>
        
        <?php 
		$TotalAmountcard=$TotalAmountchk=$Totalchk=$Totalcard=$totConversionAmt=$tot_commission=0;
              
        $countTransactionRES=count($TransactionRES);
		for($i = 0; $i < $countTransactionRES; $i++)
		{ 
            $class_name="";
            if($TransactionRES[$i]['PaymentStatus'] === "Successful"){ $class_name='class="special_class"'; }
			
			$currencyCode=$TransactionRES[$i]['currencyCode'];
			
            /*if($TransactionRES[$i]['conversionRate']!=1 && $TransactionRES[$i]['currencyCode']!="INR"){
                         $currencyCode="INR";
            }*/
			
			$totConversionAmt+=round($TransactionRES[$i]['AMOUNT'],2);
			
            ?>
		<tr <?=$class_name?> >
			<td class='tblinner' valign='middle'  align='center' ><font color='#000000'><?=$cntTransactionRES;?></font></td>
			<td class='tblinner' valign='middle'  align='center'><font color='#000000'><?=$TransactionRES[$i]['id'];?></font></td>
			<td class='tblinner' valign='middle' align='center' ><font color='#000000'><?=
                        $common->convertTime($TransactionRES[$i]['signupdate'],DEFAULT_TIMEZONE,TRUE);?></font></td>
			<td class='tblinner' valign='middle' align='left'><font color='#000000'><?=stripslashes($TransactionRES[$i]['title']);?></font></td>
			<td class='tblinner' valign='middle'  align='left'><font color='#000000'>TR:<?=$TransactionRES[$i]['paymenttransactionid'];?></font></td>
            <td class='tblinner' valign='middle'  align='left'><font color='#000000'><?=$TransactionRES[$i]['name'];?></font></td>
            <td class='tblinner' valign='middle' align='right'><font color='#000000'><?=$TransactionRES[$i]['quantity'];?></font></td>
			<td class='tblinner' valign='middle'  align='right'><font color='#000000'><?php echo $TransactionRES[$i]['currencyCode']." ".$TransactionRES[$i]['totalamount'];?></font></td>
             <td class='tblinner' valign='middle'  align='right'><font color='#000000'> <?php echo $TransactionRES[$i]['paymentstatus'];?> </font></td>      
            
            <td class='tblinner' valign='middle'  align='right'><font color='#000000'>
			<select name="promotercode<?=$TransactionRES[$i]['id']?>" id="promotercode<?=$TransactionRES[$i]['id']?>"  <?php if($TransactionRES[$i]['promotercode']=="organizer"){ ?> disabled <?php }?>  >
            <option value="organizer" >Org Effort</option>
            <option value="" <?php if($TransactionRES[$i]['promotercode']==""){?> selected="selected" <?php } ?>>Me Effort</option>
          
            </select>
			</font></td>
      
            <td class='tblinner' valign='middle'  align='right'><input type="button" name="changetrans" value="change" onclick="TransStatus(<?=$TransactionRES[$i]['id']?>);"  /></td>
          </tr>
          <?php
		  
		$cntTransactionRES++;
	}
	
	
     
        ?>

    
  
            
        
</table>
<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	<?php 
        
       $requesturi=$_SERVER['REQUEST_URI'];
         //echo $requesturi."<br>";
		 //$requesturi=preg_replace('/&page=[0-9]*/', '', $requesturi);
		 
		 if(strpos($requesturi,"page=")!==false)
		 {
			 $requesturiEx=explode("page=",$requesturi);
			 $requesturi=$requesturiEx[0];
			 
			 $lastChar=substr($requesturi,-1);
			 if($lastChar=='&')
			 {

				 $requesturi=substr($requesturi,0,-1);
			 }
		 }
		 
		 
		 if(strpos($requesturi,"?")!==false)
		 {
			  echo pagination($perPage,$page,"$requesturi&page=",$totalItems);
		 }
		 else
		 {
			  echo pagination($perPage,$page,"$requesturi?page=",$totalItems);
		 }
		// echo $requesturi;
		 
         
             
         // echo $requesturi;
              //echo $_SERVER['HTTP_HOST'];
              
        ?>
	
	</div>
	</td>
  </tr>
</table>
	</div>	
</body>
</html>