<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
            <title>MeraEvents -Menu Content Management</title>
                <link href="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
                <link href="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <script language="javascript" src="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/sortable.js"></script>	
                <script language="javascript" src="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.js"></script>	
                <link rel="stylesheet" type="text/css" media="all" href="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/CalendarControl.css" />
                <script type="text/javascript" language="javascript" src="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/includes/javascripts/CalendarControl.js"></script>
                <script type="text/javascript" language="javascript" src="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/jquery.min.js"></script>
            <script language="javascript">
                function SEdt_validate()
                {
                        var strtdt = document.frmEofMonth.txtSDt.value;
                        var enddt = document.frmEofMonth.txtEDt.value;
                        if(strtdt == '')
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
                        }
                        else //if(strtdt != '' && enddt != '')
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
	</script>
    <link href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/jq.css" rel="stylesheet">

	<!-- jQuery: required (tablesorter works with jQuery 1.2.3+) -->
	<script src="css/jquery.min.js"></script>

	<!-- Pick a theme, load the plugin & initialize plugin -->
	<link href="css/theme.default.css" rel="stylesheet">
	<script src="css/jquery.tablesorter.min.js"></script>
	<script src="css/jquery.tablesorter.widgets.min.js"></script>
	<script>
	$(function(){
		$('table').tablesorter({
			widgets        : ['zebra', 'columns'],
			usNumberFormat : false,
			sortReset      : true,
			sortRestart    : true
		});
	});
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
    <table width="60%" align="center" class="tblcont">
	
    <tr>
			  
				<td align="left" valign="middle" class="tblcont">Event City :
					<select name="selCity" id="selCity">
				<option value="">All Cities</option>
                <?php
				foreach($ctrlCities as $cityData)
				{
					list($cityk,$cityv)=$cityData;
					?>
					<option value="<?php echo $cityk; ?>"  <?php if($_REQUEST['selCity']==$cityk) {?> selected="selected" <?php }?> >
					<?php echo $cityv; ?></option><?php			}
				?>
                <option value="Other" <?php if($_REQUEST['selCity']=="Other") {?> selected="selected" <?php }?>>Other Cities</option>
				</select>
				</td>
                
                <td align="left" valign="middle" class="tblcont">Sales Person  : 
       <select name="SalesId" id="SalesId" >
        <option value="">Select</option>
        <?php
		$TotalSalesQueryRES = count($SalesQueryRES);

		foreach($salesPersons as $sales)
		{
		?>
         <option value="<?=$sales['SalesId'];?>" <?php if($sales['SalesId']==$_REQUEST['SalesId']){?> selected="selected" <?php }?>><?=$sales['SalesName'];?></option>
         <?php }?>
      </select>
    </td>
	
			</tr>
         <tr>
            <td>Organizer City: <select name="organizerCity" id="organizerCity">
				<option value="">All Cities</option>
                    <?php
				foreach($ctrlCities as $cityData)
				{
					list($cityk,$cityv)=$cityData;
					?>
					<option value="<?php echo $cityk; ?>"  <?php if($organizerCity==$cityk) {?> selected="selected" <?php }?> >
					<?php echo $cityv; ?></option><?php			}
				?>
                <option value="Other" <?php if($organizerCity=="Other") {?> selected="selected" <?php }?>>Other Cities</option>
                </select> </td>
            <td>Category: <select name="Category" id="Category">
                    <option value="" >All Categories</option>
                <?php foreach ($resCat as $k=>$value){ ?>
                    <option value="<?php echo $value['id'];?>" <?php if($value['id']==$_REQUEST['Category']){ echo 'selected="selected"';}?>><?php echo $value['name'];?></option>
                <?php } ?>
                   
                </select> </td>
        </tr>

        <tr><td width="25%" align="center" colspan="2" valign="middle"><input type="submit" name="submit" value="Show Report" onclick="return SEdt_validate();" /><input type="hidden" name="formSubmit" value="1" /><input type="submit" name="exportReports" id="exportReports" style="margin-left:10px;" value="Export Report" /></td></tr>
</table>
</form>
	<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans22').style.display='block';
</script>
<table align="center" class="tablesorter" style="margin: 10px 0 -1px; width:90%"  border='1' cellpadding='0' cellspacing='0' >
			<thead>
                            <?php if(isset($_SESSION['nodata'])){?>
                            <div style="color:green;">No data found.</div>
                            <?php }?>
            <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='5%' align='center'>Sr. No.</td>
                        <td class='tblinner' valign='middle' width='5%' align='center'>User Id</td>
                        
            <td class='tblinner' valign='middle' width='25%' align='center'>Event Organizer.</td>
            <?php if($organizerCity=='other' || $organizerCity=='allcities'){?>
                            <td class='tblinner' valign='middle' width='5%' align='center'>User City</td>
                        <?php }?>
			<td class='tblinner' valign='middle' width='10%' align='center'>No. of Events</td>
            <td class='tblinner' valign='middle' width='30%'>Event Name</td>
			<td class='tblinner' valign='middle' width='30%'>Event Id</td>
			<td class='tblinner' valign='middle' width='30%'>Category</td>
            <td class='tblinner' valign='middle' width='10%' align='center'>Tck Qty</td>
            <td class='tblinner' valign='middle' width='10%' align='center'>Amount</td>
			<td class='tblinner' valign='middle' width='10%' align='center'>ME Qty</td>
			<td class='tblinner' valign='middle' width='10%' align='center'>ME Amount</td>
			<td class='tblinner' valign='middle' width='10%' align='center'>Org Qty</td>
			<td class='tblinner' valign='middle' width='10%' align='center'>Org Amount</td>
			<td class='tblinner' valign='middle' width='10%' align='center'>Oth Qty</td>
			<td class='tblinner' valign='middle' width='10%' align='center'>Oth Amount</td>
			<td class='tblinner' valign='middle' width='10%' align='center'>Extra Amount</td>
			<td class='tblinner' valign='middle' width='10%' align='center'>ME Revenue</td>
			<td class='tblinner' valign='middle' width='10%' align='center'>Org Revenue</td>
			<td class='tblinner' valign='middle' width='10%' align='center'>Gateway Fee</td>
			<td class='tblinner' valign='middle' width='10%' align='center'>Tot Revenue</td>
          
          </tr>
        </thead>
        
        <?php	
		$TotalAmountcard=NULL;
		$TotalAmountPayatCounter=0;
		$TotalAmountchk=0;
		$Totalchk=0;
		$Totalcard=0;
		$TotalPayatCounter=0;
		$tnoEvents = 0;
		$tqty = 0;
		$tMEqty = 0;
		$tOrgqty = 0;
		$tMEAmount = 0;
		$tExAmount = 0;
		$tOrgAmount = 0;
		$tOthqty = 0;
		$tOthAmount = 0;
		
		
	
		
	//Organizing all grouping in one array
	$finalArr=NULL;	
	
	
	//foreach($TransactionRES as $key=>$value)
	while($value=$TransactionRES->fetch_assoc())
	{
		$userid=$value['UserID'];
		$eventid=$value['EventId'];
		$eventtitle=$value['Title'];
		$Category=$value['Category'];
		$quantity=$value['tktQty'];
		$fees=$value['Fees'];
        $purchseTotal=$value['totalAmount'];
		$MEQty=$value['MEQty'];
		$MEAmount=$value['MEAmount'];
		$ExAmount=$value['ExAmount'];
		$OthQty=$value['OthQty'];
		$OthAmount=$value['OthAmount'];
		$OrgQty=$value['OrgQty'];
		$OrgAmount=$value['OrgAmount']+$value['OrgAmount1']+$value['OrgAmount2'];
		if($value['mecommission']!=""){
         $MERevenue=$value['MEAmount']*($value['mecommission']/100);
		 }else{
		$MERevenue=$value['MEAmount']*$MEComm;
		 }
		 if($value['percentage']!=0){
			  $OrgRevenue=($value['OrgAmount']*($value['percentage']/100))+($value['OrgAmount1']*($value['percentage']/100))+($value['OrgAmount2']*($value['percentage']/100));
			   }else{
				  $OrgRevenue=($value['OrgAmount']*$OrgComm)+($value['OrgAmount1']*$OrgComm1)+($value['OrgAmount2']*$OrgComm2); 
			   }
	     $Gatewayfee = 	($value['totalAmount'] * (2.28/100));	   
		 $TotRevenue=$MERevenue+$OrgRevenue+$ExAmount-$Gatewayfee;		   
		$UserName=$value['UserName'];
		$Company=$value['Company'];
		
		
		
		
		if(is_array($finalArr[$userid]))  // checking if an array is created at userid level
		{
			if(is_array($finalArr[$userid][$eventid]))  				 // checking if eventid is present under userid
			{
				$finalArr[$userid][$eventid]['qty']+=$quantity;     //qty stores total quantity at event level
				$finalArr[$userid][$eventid]['fees']+=$purchseTotal;		 // fees stores total fees at event level
				$finalArr[$userid]['totalqty']+=$quantity;					 // totalqty stores total quantity of all the fetched events
				$finalArr[$userid]['totalfees'][$value['code']]+=$purchseTotal;			 // totalfees stores total fees of all the fetched events
			    $finalArr[$userid]['MEQty']+=$MEQty;
				$finalArr[$userid]['MEAmount'][$value['code']]+=$MEAmount;
				$finalArr[$userid]['OthQty']+=$OthQty;
				$finalArr[$userid]['OthAmount'][$value['code']]+=$OthAmount;
				$finalArr[$userid]['OrgQty']+=$OrgQty;
				$finalArr[$userid]['OrgAmount'][$value['code']]+=$OrgAmount;
				$finalArr[$userid]['ExAmount'][$value['code']]+=$ExAmount;
				$finalArr[$userid]['MERevenue'][$value['code']]+=$MERevenue;
				$finalArr[$userid]['OrgRevenue'][$value['code']]+=$OrgRevenue;
				$finalArr[$userid]['Gatewayfee'][$value['code']]+=$Gatewayfee;
				$finalArr[$userid]['TotRevenue'][$value['code']]+=$TotRevenue;
			}
			else
			{
				$finalArr[$userid][$eventid]['name']=$eventtitle;
				$finalArr[$userid][$eventid]['id']=$eventid;
				$finalArr[$userid][$eventid]['Category']=$Category;
				$finalArr[$userid][$eventid]['qty']=$quantity;
				$finalArr[$userid][$eventid]['fees']=$purchseTotal;
				$finalArr[$userid]['totalqty']+=$quantity;
				$finalArr[$userid]['totalfees'][$value['code']]+=$purchseTotal;
				$finalArr[$userid]['MEQty']+=$MEQty;
				$finalArr[$userid]['MEAmount'][$value['code']]+=$MEAmount;
				$finalArr[$userid]['OthQty']+=$OthQty;
				$finalArr[$userid]['OthAmount'][$value['code']]+=$OthAmount;
				$finalArr[$userid]['OrgQty']+=$OrgQty;
				$finalArr[$userid]['OrgAmount'][$value['code']]+=$OrgAmount;
				$finalArr[$userid]['ExAmount'][$value['code']]+=$ExAmount;
				$finalArr[$userid]['MERevenue'][$value['code']]+=$MERevenue;
				$finalArr[$userid]['OrgRevenue'][$value['code']]+=$OrgRevenue;
				$finalArr[$userid]['Gatewayfee'][$value['code']]+=$Gatewayfee;
				$finalArr[$userid]['TotRevenue'][$value['code']]+=$TotRevenue;
				$finalArr[$userid][$eventid]['UserName']=$UserName;
				$finalArr[$userid][$eventid]['Company']=$Company;
                $finalArr[$userid][$eventid]['UserId']=$value['UserID'];
                $finalArr[$userid][$eventid]['cityname']=isset($value['cityname'])?$value['cityname']:'';
			}
		
	}
		else
		{
			$finalArr[$userid][$eventid]['name']=$eventtitle;
			$finalArr[$userid][$eventid]['id']=$eventid;
			$finalArr[$userid][$eventid]['Category']=$Category;
			$finalArr[$userid][$eventid]['qty']=$quantity;
			$finalArr[$userid][$eventid]['fees']=$purchseTotal;
			$finalArr[$userid]['totalqty']=$quantity;
			$finalArr[$userid]['totalfees'][$value['code']]=$purchseTotal;
			$finalArr[$userid]['MEQty']+=$MEQty;
			$finalArr[$userid]['OthQty']+=$OthQty;
			$finalArr[$userid]['OthAmount'][$value['code']]+=$OthAmount;
			$finalArr[$userid]['MEAmount'][$value['code']]+=$MEAmount;
			$finalArr[$userid]['OrgQty']+=$OrgQty;
			$finalArr[$userid]['OrgAmount'][$value['code']]+=$OrgAmount;
			$finalArr[$userid]['ExAmount'][$value['code']]+=$ExAmount;
			$finalArr[$userid]['MERevenue'][$value['code']]+=$MERevenue;
			$finalArr[$userid]['OrgRevenue'][$value['code']]+=$OrgRevenue;
			$finalArr[$userid]['Gatewayfee'][$value['code']]+=$Gatewayfee;
			$finalArr[$userid]['TotRevenue'][$value['code']]+=$TotRevenue;
			$finalArr[$userid]['UserName']=$UserName;
			$finalArr[$userid]['Company']=$Company;
                        $finalArr[$userid]['UserId']=$value['UserID'];
                        $finalArr[$userid]['cityname']=isset($value['cityname'])?$value['cityname']:'';
		}
	
	
	
}

		
		
		 //echo '<pre>';print_r($finalArr);
		
	foreach($finalArr as $key=>$value)   // iterating grouped array for displaying data
	{ 
		?>
		<tr>
			<td class='tblinner' valign='middle'  align='center' ><font color='#000000'><?php echo ++$cnt;?></font></td>
                        <td class='tblinner' valign='middle'  align='center' ><font color='#000000'><?php echo $value['UserId'];?></font></td>
			<td class='tblinner' valign='middle'  align='left'><font color='#000000'><?php echo $value['Company']."<br/>(".$value['UserName'].")";?></font></td>
                        <?php if($organizerCity=='other' || $organizerCity=='allcities'){?>
                            <td class='tblinner' valign='middle' width='5%' align='center'><?php echo $value['cityname'];?></td>
                        <?php }?>
                        <td class='tblinner' valign='middle'  align='center'><font color='#000000'><?php echo count($value)-16;?></font></td>
            
          <!--  (minus) 2 is done to remove the extra totalqty and totalfees index while counting items in array-->
            
            
			<td class='tblinner' valign='middle' ><font color='#000000'>
			
            <?php
               foreach($value as $keyin=>$valuein)
			{

				if($keyin=='totalqty' || $keyin=='totalfees'  || $keyin=='UserName'  || $keyin=='Company' || $keyin=='UserId' || $keyin=='cityname' || $keyin=='MEAmount' || $keyin=='MEQty' || $keyin=='OrgQty' || $keyin=='OrgAmount' || $keyin=='OthQty' || $keyin=='OthAmount' || $keyin=='ExAmount' || $keyin=='MERevenue' || $keyin=='OrgRevenue' || $keyin=='Gatewayfee' || $keyin=='TotRevenue')
				{  //ignore totalqty and totalfees for that is not related to event
				}
				else
				echo "=> ".$valuein['name']." <br />";
				
			}
            
			?>
            </font></td>		
			<td class='tblinner' valign='middle' ><font color='#000000'>
			
            <?php
               foreach($value as $keyin=>$valuein)
			{

				if($keyin=='totalqty' || $keyin=='totalfees'  || $keyin=='UserName'  || $keyin=='Company' || $keyin=='UserId' || $keyin=='cityname' || $keyin=='MEAmount' || $keyin=='MEQty' || $keyin=='OrgQty' || $keyin=='OrgAmount' || $keyin=='OthQty' || $keyin=='OthAmount' || $keyin=='ExAmount'  || $keyin=='MERevenue' || $keyin=='OrgRevenue' || $keyin=='Gatewayfee' || $keyin=='TotRevenue')
				{  //ignore totalqty and totalfees for that is not related to event
				}
				else
				echo "=> ".$valuein['id']." <br />";
				
			}
            
			?>
            </font></td>		
			<td class='tblinner' valign='middle' ><font color='#000000'>
			
            <?php
               foreach($value as $keyin=>$valuein)
			{

				if($keyin=='totalqty' || $keyin=='totalfees'  || $keyin=='UserName'  || $keyin=='Company' || $keyin=='UserId' || $keyin=='cityname' || $keyin=='MEAmount' || $keyin=='MEQty' || $keyin=='OrgQty' || $keyin=='OrgAmount' || $keyin=='OthQty' || $keyin=='OthAmount' || $keyin=='ExAmount'  || $keyin=='MERevenue' || $keyin=='OrgRevenue' || $keyin=='Gatewayfee' || $keyin=='TotRevenue')
				{  //ignore totalqty and totalfees for that is not related to event
				}
				else
				echo "=> ".$valuein['Category']." <br />";
				
			}
            
			?>
            </font></td>		
			<td class='tblinner' valign='middle' align='center'><font color='#000000'><?php echo $value['totalqty'];?></font></td>
                        <td class='tblinner' valign='middle' align='center'><font color='#000000'>
						<?php $str=''; foreach ($value['totalfees'] as $code => $total) {
                        $str.=$code.' '.round($total).'<br/>';
                        $TotalAmountcard[$code] += $total;
                    } echo $str;?></font></td>
					<td class='tblinner' valign='middle' align='center'><font color='#000000'>
					<?php echo $value['MEQty'];?>
					</font></td>
			<td class='tblinner' valign='middle' align='center'><font color='#000000'><?php $str=''; foreach ($value['MEAmount'] as $code => $Mtotal) {
                        $str.=$code.' '.round($Mtotal).'<br/>';
                        $TotalAmountcardM[$code] += $Mtotal;
                    } echo $str; ?></font></td>
					<td class='tblinner' valign='middle' align='center'><font color='#000000'><?php echo $value['OrgQty'];?></font></td>
            <td class='tblinner' valign='middle' align='center'><font color='#000000'><?php $str=''; foreach ($value['OrgAmount'] as $code => $Ototal) {
                        $str.=$code.' '.round($Ototal).'<br/>';
                        $TotalAmountcardO[$code] += $Ototal;
                    } echo $str; ?></font></td>
					<td class='tblinner' valign='middle' align='center'><font color='#000000'><?php echo $value['OthQty'];?></font></td>
            <td class='tblinner' valign='middle' align='center'><font color='#000000'><?php $str=''; foreach ($value['OthAmount'] as $code => $Othtotal) {
                        $str.=$code.' '.round($Othtotal).'<br/>';
                        $TotalAmountcardOth[$code] += $Othtotal;
                    } echo $str; ?></font></td>
					 <td class='tblinner' valign='middle' align='center'><font color='#000000'><?php $str=''; foreach ($value['ExAmount'] as $code => $Extotal) {
                        $str.=$code.' '.round($Extotal).'<br/>';
                        $TotalAmountcardEx[$code] += $Extotal;
                    } echo $str; ?></font></td>
            <td class='tblinner' valign='middle' align='center'><font color='#000000'><?php $str=''; foreach ($value['MERevenue'] as $code => $MERevenue) {
                        $str.=$code.' '.round($MERevenue).'<br/>';
                        $TotalAmountcardMR[$code] += $MERevenue;
                    } echo $str; ?></font></td>
            <td class='tblinner' valign='middle' align='center'><font color='#000000'><?php $str=''; foreach ($value['OrgRevenue'] as $code => $OrgRevenue) {
                        $str.=$code.' '.round($OrgRevenue).'<br/>';
                        $TotalAmountcardOR[$code] += $OrgRevenue;
                    } echo $str; ?></font></td>	
						<td class='tblinner' valign='middle' align='center'><font color='#000000'><?php $str=''; foreach ($value['Gatewayfee'] as $code => $Gatewayfee) {
                        $str.=$code.' '.round($Gatewayfee).'<br/>';
                        $TotalGatewayAmountcardTR[$code] += $Gatewayfee;
                    } echo $str; ?></font></td>		
<td class='tblinner' valign='middle' align='center'><font color='#000000'><?php $str=''; foreach ($value['TotRevenue'] as $code => $TotRevenue) {
                        $str.=$code.' '.round($TotRevenue).'<br/>';
                        $TotalAmountcardTR[$code] += $TotRevenue;
                    } echo $str; ?></font></td>						
            </tr>
          <?php


		 
		 $tqty +=$value['totalqty'];
		 $tMEqty +=$value['MEQty'];
		 $tOrgqty +=$value['OrgQty'];
		 $tOthqty += $value['OthQty'];
		$Totalcard +=1;// $TransactionRES[$i]['Qty'];
		$tnoEvents +=count($value)-16;
		 //  (minus) 2 is done to remove the extra totalqty and totalfees index while counting items in array
	}?>
	


  <tr>


  <tr bgcolor="#FFFFFF">
      <td colspan="<?php if($organizerCity=='other' || $organizerCity=='allcities'){ echo '4';}else{echo '3';}?>" style="line-height:30px; padding:5px;"><strong>Total :</strong></td>
    <td  align='center'><font color='#000000'><?php echo $tnoEvents;?></font></td>
    <td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
    <td  align='center'><font color='#000000'><?php echo $tqty;?></font></td>
    <td align='center'><font color='#000000'> <?php $str=''; foreach ($TotalAmountcard as $code => $total) {
    $str.=$code.' '.$total.'<br/>';
} echo $str;?></font></td>
 <td  align='center'><font color='#000000'><?php echo $tMEqty;?></font></td>
<td  align='center'><font color='#000000'><?php $str=''; foreach ($TotalAmountcardM as $code => $total) {
    $str.=$code.' '.$total.'<br/>';
} echo $str;?></font></td>
 <td  align='center'><font color='#000000'><?php echo $tOrgqty;?></font></td>
<td  align='center'><font color='#000000'><?php $str=''; foreach ($TotalAmountcardO as $code => $total) {
    $str.=$code.' '.$total.'<br/>';
} echo $str;?></font></td>
<td  align='center'><font color='#000000'><?php echo $tOthqty;?></font></td>
<td  align='center'><font color='#000000'><?php $str=''; foreach ($TotalAmountcardOth as $code => $total) {
    $str.=$code.' '.$total.'<br/>';
} echo $str;?></font></td>
<td  align='center'><font color='#000000'><?php $str=''; foreach ($TotalAmountcardEx as $code => $total) {
    $str.=$code.' '.$total.'<br/>';
} echo $str;?></font></td>
<td  align='center'><font color='#000000'><?php $str=''; foreach ($TotalAmountcardMR as $code => $total) {
    $str.=$code.' '.$total.'<br/>';
} echo $str;?></font></td>
<td  align='center'><font color='#000000'><?php $str=''; foreach ($TotalAmountcardOR as $code => $total) {
    $str.=$code.' '.$total.'<br/>';
} echo $str;?></font></td>
<td  align='center'><font color='#000000'><?php $str=''; foreach ($TotalGatewayAmountcardTR as $code => $total) {
    $str.=$code.' '.$total.'<br/>';
} echo $str;?></font></td>
<td  align='center'><font color='#000000'><?php $str=''; foreach ($TotalAmountcardTR as $code => $total) {
    $str.=$code.' '.$total.'<br/>';
} echo $str;?></font></td>
  </tr>
  
  
  </table>
  <br /><br /><br />
<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	
	
	</div>
	</td>
  </tr>
  
</table>
	</div>	
</body>
</html>
