<?php
include_once("MT/cGlobali.php");
include_once("MT/cEvents.php");
class delegatePass{
        public $hideOrgDetails=array();
        public $smirnoff="";
        function __construct(){
            $hostname=strtolower($_SERVER['HTTP_HOST']);
            if(strcmp($hostname,'www.meraevents.com')==0 || strcmp($hostname,'meraevents.com')==0)
            {
                    $this->hideOrgDetails=array(72536,72537,72542,72941);
                    $this->smirnoff=72941;
            }elseif(strcmp($hostname,'stage.meraevents.com')==0)
            {
                    $this->hideOrgDetails=array(64948,55469,55432);
                    $this->smirnoff=55432;
            }elseif(strcmp($hostname,'localhost')==0)
            {
                    $this->hideOrgDetails=array(64948,67593);
                    $this->smirnoff=67593;
            }
        }
        function getCustomSunburnHtml($EventId,$EventSignupId){
            $Globali = new cGlobali();
            $Events = @new cEvents($EventId);
            $Success = $Events->Load();
			$eventDate=date('M',  strtotime($Events->StartDt)).' <br><span style="font-size:45px; line-height:50px; text-align:center; font-weight:900;">'.date('j',  strtotime($Events->StartDt)).'</span><br>'.date('Y',  strtotime($Events->StartDt));
            $imagePath='/images/venue.png';
			$eventTitle="ARENA BLASTERJAXX TOUR";
                        
            if($EventId==66564){
                //echo $Events->CityId;
                $imagePath='/images/hyderabadSunburnVenue.png';
				$liveDanceTxt='<tr><td align="center" style="font-family:\'Trebuchet MS\',Arial; font-weight: bold; font-size:22px; border-bottom:1px solid #C00; padding:0; margin:0; background:#efe9d9; ">Live &nbsp;&nbsp; Love &nbsp;&nbsp; Dance</td></tr>';
            }else if($EventId==66566){
                $imagePath='/images/mumbaiSunburnVenue.png';
				$liveDanceTxt='<tr><td align="center" style="font-family:\'Trebuchet MS\',Arial; font-weight: bold; font-size:22px; border-bottom:1px solid #C00; padding:0; margin:0; background:#efe9d9; ">Live &nbsp;&nbsp; Love &nbsp;&nbsp; Dance</td></tr>';
            }else if($EventId==66567){
                $imagePath='/images/bangloreSunburnVenue.png';
				$liveDanceTxt='<tr><td align="center" style="font-family:\'Trebuchet MS\',Arial; font-weight: bold; font-size:22px; border-bottom:1px solid #C00; padding:0; margin:0; background:#efe9d9; ">Live &nbsp;&nbsp; Love &nbsp;&nbsp; Dance</td></tr>';
            }
			
			//echo $liveDanceTxt;
			$barcode_height=100;
                        $barcode_yPos=100;
			 if($EventId==67580){
				$logoPath='/images/edsheeranLogo.png';
                $imagePath='/images/edsheeranVenue.png';
				$eventTitle="Ed Sheeran - Fly Music Festival";
				$liveDanceTxt='';
                                //$uniqueNum=$EventSignupId.$Events->Id.$Events->UserID;
                                $barcode_height=100;
                                $barcode_yPos=180;
            }
            else {
                  $logoPath='/images/logo.png';
            }
			
			
			
			
            $getUserDetails="SELECT a.Name,es.Qty,es.PaymentModeId,es.Id,t.Name as TicketName,es.Fees,es.PaymentTransId,es.BarcodeNumber 
                FROM EventSignup es 
                INNER JOIN Attendees a ON a.EventSignupId=es.Id 
                INNER JOIN events e ON e.Id=es.EventId 
                INNER JOIN tickets t ON t.Id=a.TicketId where es.EventId='".$EventId."' and es.Id='".$EventSignupId."' order by a.Id desc limit 1";
            $resUserDtls=$Globali->SelectQuery($getUserDetails);
           $displayAmount=false;
            
            if(count($resUserDtls)>0){
                 $uniqueNum=  $resUserDtls[0]['BarcodeNumber']; 
                 if(empty($uniqueNum) || $uniqueNum==0){
                    $uniqueNum=  substr($Events->Id, 0,2).$EventSignupId;
                }
                if(strcmp($resUserDtls[0]['PaymentTransId'],'Offline')==0){
                    $displayAmount=true;
                }
                if($displayAmount){$paymentType='OFFLINE';}else{$paymentType='CARD';} 
            ?>
<table width="750" border="0" cellspacing="0" cellpadding="0" bgcolor="#efe9d9" style="padding:4px; font-family:'Open Sans',sans-serif; background-color: #efe9d9; width:850px; margin:0 auto;" id="printInnerPass" >
  <tr>
    <td align="left" valign="top" style="padding:0; margin:0; border-left: 1px solid #c00 !important; border-right: 1px solid #c00 !important; border-top: 1px solid #c00 !important;  border-bottom: 1px solid #c00 !important;   ">
    	<table width="748" border="0" cellspacing="1" cellpadding="1" bgcolor="#efe9d9">
  <tr>
    <td width="64" height="305" style="background:#efe9d9; border-right:1px solid #C00; padding:0; margin-right:10px;"><img src="<?php echo _HTTP_SITE_ROOT.$imagePath;?>" width="59" height="216"/></td> 
     <td width="4" height="305" style="background:#efe9d9; padding:0; margin:0;"></td> 
    <td align="left" valign="top" style="padding:0; margin:0; width:520px;">
        <table width="520" border="0" cellspacing="1" cellpadding="1" style="height: 140px;">
  <tr>
<td align="left" valign="top" style="background:#efe9d9; padding:0; margin:0; font-family:'Open Sans', 'Open Sans Condensed','Open Sans Condensed Light','Open Sans Extrabold','Open Sans Light', 'Open Sans Semibold'; font-size:20px; font-weight:900; border-bottom:1px solid #C00; vertical-align:middle;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="151" height="36" style="padding:0; margin:0;">
     <p style="float:left; margin:5px 0; padding:0px 0 0 10px;"> <img src="<?php echo _HTTP_SITE_ROOT.$logoPath; ?>" alt="" width="151" height="36"></p>
     </td>
  
    <td>   <p style="float:left; margin:0; padding:0px 0 0 10px; font-size:20px;"><?php echo $eventTitle; ?></p></td>
  </tr>
</table>

  
  
  
    </td>  </tr>
   
  <tr>
    <td style="padding-top:10px; padding-bottom:10px; margin:0;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <?php echo $liveDanceTxt; ?>
  
  <tr>
    <td style="padding:0; margin:0;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="padding:0; margin:0;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:10px 0;">
  <tr>
    <td width="103" height="115"  align="center" style="background:#efe9d9; padding:0; margin:0; font-family:'Open Sans', 'Open Sans Condensed','Open Sans Condensed Light','Open Sans Extrabold','Open Sans Light', 'Open Sans Semibold'; font-size:20px; border-right:1px solid #C00; line-height:25px;">
			<p><?php echo $eventDate; ?></p></td>
    <td align="left" valign="top" style="margin:0; padding:0;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding-top:10px; padding-left:10px !important;height: 140px; ">
  <tr>
<td align="center" valign="middle" style="background:#efe9d9; padding:0; margin:0; font-family:'Open Sans', 'Open Sans Condensed','Open Sans Condensed Light','Open Sans Extrabold','Open Sans Light', 'Open Sans Semibold'; font-size:16px; ">
			<p style="border-right:1px solid #C00;"><strong>CATEGORY</strong> <br><?php echo $resUserDtls[0]['TicketName']?></p></td>    
            <td  align="center" valign="middle" style="background:#efe9d9; padding:0; margin:0; font-family:'Open Sans', 'Open Sans Condensed','Open Sans Condensed Light','Open Sans Extrabold','Open Sans Light', 'Open Sans Semibold'; font-size:16px; ">
			<p style="border-right:0px solid #C00;"><strong>QUANTITY</strong> <br><?php echo $resUserDtls[0]['Qty']?></p></td>
             
  </tr>

   <tr><td height="1" colspan="2" align="center" valign="middle" style="padding:0; margin:0;"><p style="border-top:1px solid #C00; height:2px; padding:0; margin-top: 2px; margin-bottom: 0;"></p> </td></tr>

  <!-- <tr><td height="5" colspan="2" align="center" valign="middle" > </td></tr>-->
  
    <tr>
<td align="center" valign="middle" style="background:#efe9d9; padding:0; margin:0; font-family:'Open Sans', 'Open Sans Condensed','Open Sans Condensed Light','Open Sans Extrabold','Open Sans Light', 'Open Sans Semibold'; font-size:16px; ">
			<p style="border-right:1px solid #C00;"><strong>PAYMENT MODE</strong> <br><?php echo $paymentType;?></p></td>    
            <td  align="center" valign="middle" style="background:#efe9d9; padding:0; margin:0; font-family:'Open Sans', 'Open Sans Condensed','Open Sans Condensed Light','Open Sans Extrabold','Open Sans Light', 'Open Sans Semibold'; font-size:16px; ">
			<p style="border-right:0px solid #C00;"><strong>REG.NUMBER</strong> <br><?php echo $resUserDtls[0]['Id']?></p></td>
            <td  align="center" valign="middle" style="background:#efe9d9; padding:0; margin:0; font-family:'Open Sans', 'Open Sans Condensed','Open Sans Condensed Light','Open Sans Extrabold','Open Sans Light', 'Open Sans Semibold'; font-size:16px; ">&nbsp;
			</td>
  </tr>
  <?php if($displayAmount){?>
  <tr><td height="1" colspan="2" align="center" valign="middle" style="padding:0; margin:0;"><p style=" height:2px; padding:0; margin-top: 2px; margin-bottom: 0;"></p> </td></tr>
  <tr>
<td align="center" valign="middle" style="background:#efe9d9; border-top:1px solid #C00; padding:0; margin:0; font-family:'Open Sans', 'Open Sans Condensed','Open Sans Condensed Light','Open Sans Extrabold','Open Sans Light', 'Open Sans Semibold'; font-size:16px; ">
   <p style=""><strong>PRICE<span style="font-size:12px;">&nbsp;(Incl of ET)</span></strong> <br><?php echo round($resUserDtls[0]['Qty']*$resUserDtls[0]['Fees']);?></p></td>    
            <td  align="center" valign="middle" style="background:#efe9d9; padding:0; margin:0; border-top:1px solid #C00; font-family:'Open Sans', 'Open Sans Condensed','Open Sans Condensed Light','Open Sans Extrabold','Open Sans Light', 'Open Sans Semibold'; font-size:16px; ">&nbsp;</td>
  </tr>
  <?php }?>
</table>
</td>
   
  </tr>
</table>
</td>
  </tr>
  <tr>
    <td align="center" style="background:#efe9d9; padding:0; margin:0; font-family:'Open Sans', 'Open Sans Condensed','Open Sans Condensed Light','Open Sans Extrabold','Open Sans Light', 'Open Sans Semibold'; font-size:18px; font-weight:700; border-top:1px solid #C00; padding:10px 0 !important;">
        Name: <?php echo ucwords($resUserDtls[0]['Name']);?></td>
  </tr>
</table>
</td>
  </tr>
  <tr>
    <td style="background:#f60; padding:5px 0; padding:0; margin:0; font-family:'Open Sans', 'Open Sans Condensed','Open Sans Condensed Light','Open Sans Extrabold','Open Sans Light', 'Open Sans Semibold'; font-size:15px; color:#FFF !important; text-align:center;">
			This is not your ticket. Exchange this at the boxoffice for your ticket.</td>
  </tr>
</table>
</td>
  </tr>
</table>

    
    
    </td> 
    <td width="4" height="305" style="background:#efe9d9; padding:0; margin:0;"></td> 
    <td align="left" valign="top" style="padding:0; margin:0; width:160px;">
    	
        <table width="160" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right" style="font-family:'Open Sans', 'Open Sans Condensed','Open Sans Condensed Light','Open Sans Extrabold','Open Sans Light', 'Open Sans Semibold'; font-size:12px; color:#333; padding:0; margin:0;"><img src="<?php echo _HTTP_CF_ROOT;?>/images/mera-logo.png" width="104" height="59" alt=""><br />www.meraevents.com</td>
  </tr>
  <tr>
    <td align="right" style="padding:5px 0; margin: 0;">
       
<!--        <img src="http://barcode.tec-it.com/barcode.ashx?code=Code128&modulewidth=fit&data=<?php echo $uniqueNum?>&dpi=96&imagetype=gif&rotation=90&color=&bgcolor=&fontcolor=&quiet=0&qunit=mm" width="<?php echo $barcode_height;?>" height="158" />-->
        
        <img src="<?php echo _HTTP_SITE_ROOT;?>/barcode/barcode.php?text=<?php echo $uniqueNum;?>&angle=270" width="<?php echo $barcode_height;?>" height="158" style="padding: 10px 0px;" >
        <!--<img src="<?php echo _HTTP_CF_ROOT;?>/images/barcode.png" width="52" height="166" alt="" style="margin-right:10px;">--></td>
  </tr>
  <tr>
   <td  style="background:#efe9d9; font-family:'Open Sans', 'Open Sans Condensed','Open Sans Condensed Light','Open Sans Extrabold','Open Sans Light', 'Open Sans Semibold'; font-size:12px; font-weight:normal; padding:0; margin:0; text-align:right;">
			 <p style="margin-right:10px; padding-top:5px 0; margin: 0;">+91-9396 555 888<br>
support@meraevents.com</p></td>

  </tr>
</table>

    </td> 
    <td width="4" height="305" style="background:#efe9d9; padding:0; margin:0;"></td> 
  </tr>
 </table>
    </td>
  </tr>
  <?php
/*			$selTnC="SELECT * FROM eventTC WHERE EventId='".$Globali->dbconn->real_escape_string($EventId)."'";
			$ResTnC=$Globali->SelectQuery($selTnC);
			if(count($ResTnC)>0)
			{
                            $desc=NULL;
                            if(strcmp($ResTnC[0]['showTC'],'organizer')==0){
                                $desc=$ResTnC[0]['Org_Description'];
                            }else if (strcmp($ResTnC[0]['showTC'],'meraevents')==0){
                                $desc=$ResTnC[0]['ME_Description'];
                            }
			    if(!empty($desc)){	?>
  <tr><td><?php echo $desc;?></td></tr>
  <?php } } */?>
  <tr>
  <td align="center"><img src="<?php echo _HTTP_SITE_ROOT;?>/images/sunburnTC.jpg" style="width:99%"></td>
</tr> 
</table>
<?php if(!$ismobile)
    {
    ?>
      <div  style="margin:0px auto 0 45.5%; width:100px">
    <input style="width:114%;" title="Print" name="button" class="processbutton" value="Print" type="button" onClick="ClickHereToPrintpass('printInnerPass')">
     </div> 
    
    &nbsp;
    <div id="sendPassDiv">
    <label id="sendPassToEmailTxt" for="sendPassToEmail" style="margin-left:10.85%;">Send Pass to email:</label>
        <input style="width:227px;" type="text" class="wizardtext" placeholder="Email id" name="sendPassToEmail" id="sendPassToEmail"  value="" /> 
        <input style="width: 10%;" id="EMailButton" title="Send Email" name="button" class="processbutton" value="Send Email" type="submit" onclick="ClickHereToEmailpass();"><span id="sendEmailLoading"></span>
    </div>
    
    <?php }  }
        }
         function getCustomSunburnPDF($EventId,$EventSignupId,$barcodeType=NULL){
            $Globali = new cGlobali();
            $Events = @new cEvents($EventId);
            $Success = $Events->Load();
			$eventDate=date('M',  strtotime($Events->StartDt)).' <br><span style="font-size:45px; line-height:50px; text-align:center; font-weight:900;">'.date('j',  strtotime($Events->StartDt)).'</span><br>'.date('Y',  strtotime($Events->StartDt));
            $eventDay=  date('j',  strtotime($Events->StartDt));
            $imagePath='/images/venue.png';
			
			$eventTitle="ARENA BLASTERJAXX TOUR";
			
             if($EventId==66564){
                //echo $Events->CityId;
                $imagePath='/images/hyderabadSunburnVenue.png';
				$liveDanceTxt='<tr><td align="center" style="font-family:\'Trebuchet MS\',Arial; font-weight: bold; font-size:22px; border-bottom:1px solid #C00; padding:0; margin:0; background:#efe9d9; ">Live &nbsp;&nbsp; Love &nbsp;&nbsp; Dance</td></tr>';
            }else if($EventId==66566){
                $imagePath='/images/mumbaiSunburnVenue.png';
				$liveDanceTxt='<tr><td align="center" style="font-family:\'Trebuchet MS\',Arial; font-weight: bold; font-size:22px; border-bottom:1px solid #C00; padding:0; margin:0; background:#efe9d9; ">Live &nbsp;&nbsp; Love &nbsp;&nbsp; Dance</td></tr>';
            }else if($EventId==66567){
                $imagePath='/images/bangloreSunburnVenue.png';
				$liveDanceTxt='<tr><td align="center" style="font-family:\'Trebuchet MS\',Arial; font-weight: bold; font-size:22px; border-bottom:1px solid #C00; padding:0; margin:0; background:#efe9d9; ">Live &nbsp;&nbsp; Love &nbsp;&nbsp; Dance</td></tr>';
            }
			
			$barcode_height=100;
                        $barcode_yPos=100;
			if($EventId==67580){
				$logoPath='/images/edsheeranLogo.png';
                $imagePath='/images/edsheeranVenue.png';
				$eventTitle="Ed Sheeran - Fly Music Festival";
				$liveDanceTxt='';
                              // $uniqueNum=$EventSignupId.$Events->Id.$Events->UserID;
                               $barcode_height=65;
                               $barcode_yPos=180;
            }
            else {
                  $logoPath='/images/logo.png'; 
            }
            
            $getUserDetails="SELECT a.Name,es.Qty,es.PaymentModeId,es.Id,t.Name as TicketName,es.Fees,es.PaymentTransId FROM EventSignup es INNER JOIN Attendees a ON a.EventSignupId=es.Id INNER JOIN events e ON e.Id=es.EventId INNER JOIN tickets t ON t.Id=a.TicketId where es.EventId='".$EventId."' and es.Id='".$EventSignupId."' order by a.Id desc limit 1";
            $resUserDtls=$Globali->SelectQuery($getUserDetails);
                $displayAmount=false;
            
            if(count($resUserDtls)>0){
                $uniqueNum=  $resUserDtls[0]['BarcodeNumber'];
                if(empty($uniqueNum) || $uniqueNum==0){
                    $uniqueNum=  substr($Events->Id, 0,2).$EventSignupId;
                }
                if( strcmp($barcodeType,"techit")==0){
                    $barcode_link='http://barcode.tec-it.com/barcode.ashx?code=Code128&modulewidth=fit&data='.$uniqueNum.'&dpi=96&imagetype=gif&rotation=90&color=&bgcolor=&fontcolor=&quiet=0&qunit=mm';
                }
                else{
                    $barcode_link=_HTTP_SITE_ROOT.'/barcode/barcode.php?text='.$uniqueNum.'&angle=270';
                }
                if(strcmp($resUserDtls[0]['PaymentTransId'],'Offline')==0){
                    $displayAmount=true;
                }
                if($displayAmount){$paymentType='OFFLINE';}else{$paymentType='CARD';} 
                    $data='<table width="750" cellspacing="0" cellpadding="0" border="0" bgcolor="#efe9d9" id="printInnerPass" style="padding:4px; font-family:\'Open Sans\',sans-serif; background-color: #efe9d9; width:850px; margin:0 auto;">
  <tbody><tr>
    <td valign="top" align="left" style="padding:0; margin:0; border-left: 1px solid #c00 !important; border-right: 1px solid #c00 !important; border-top: 1px solid #c00 !important;  border-bottom: 1px solid #c00 !important;   ">
    	<table width="748" cellspacing="1" cellpadding="1" border="0" bgcolor="#efe9d9">
  <tbody><tr>
    <td width="64" height="320" style="background:#efe9d9; border-right:1px solid #C00; padding:0; margin-right:10px;"><img width="59" height="216" src="'._HTTP_SITE_ROOT.$imagePath.'"></td> 
     <td width="4" height="320" style="background:#efe9d9; padding:0; margin:0;"></td> 
    <td valign="top" align="left" style="padding:0; margin:0; width:520px;">
    	<table width="520" cellspacing="1" cellpadding="1" border="0">
  <tbody><tr>
<td valign="top" align="left" style="background:#efe9d9; padding:0; margin:0; font-family:\'Open Sans\',\'Trebuchet MS\'; font-size:20px; font-weight:900; border-bottom:1px solid #C00; vertical-align:middle;">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tbody><tr>
    <td width="151" height="36" style="padding:0; margin:0;">
     <p style="float:left; margin:5px 0; padding:0px 0 0 10px;"> <img width="151" height="36" alt="" src="'._HTTP_SITE_ROOT.$logoPath.'"></p>
     </td>
  
    <td>   <p style="float:left; margin:0; padding:0px 0 0 10px; font-size:20px;">'.$eventTitle.'</p></td>
  </tr>
</tbody></table>
  
  


    </td>  </tr>
   
  <tr>
    <td style="padding-top:10px; padding-bottom:10px; margin:0;"><table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tbody>'.$liveDanceTxt.'
  <tr>
    <td style="padding:0; margin:0;"><table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tbody><tr>
    <td style="padding:0; margin:0;"><table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding:10px 0;">
  <tbody><tr>
    <td width="103" height="115" align="center" style="background:#efe9d9; padding:0; margin:0; font-family:\'Open Sans\',\'Trebuchet MS\'; font-size:20px; border-right:1px solid #C00; line-height:25px;">
			<p>'.$eventDate.'</p></td>
    <td valign="top" align="left" style="margin:0; padding:0;"><table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding-top:10px; padding-left:10px !important; ">
  <tbody><tr>
<td valign="middle" width="140" align="center" style="background:#efe9d9; text-align:center; border-right:1px solid #C00; padding:0; margin:0; font-family:\'Open Sans\',\'Trebuchet MS\'; font-size:16px; ">
			<p style="margin:0;"><strong>CATEGORY</strong> <br>'.$resUserDtls[0]['TicketName'].'</p></td>    
            <td valign="middle" width="140" align="center" style="background:#efe9d9; text-align:center; padding:0; margin:0; font-family:\'Open Sans\',\'Trebuchet MS\'; font-size:16px; ">
			<p style="margin:0;"><strong>QUANTITY</strong> <br>'.$resUserDtls[0]['Qty'].'</p></td>
             
  </tr>

<tr><td valign="middle" height="2" align="center" style="padding:0; margin:0;" colspan="2"><p style=" height:2px; padding:0; margin-top: 2px; margin-bottom: 0;"></p> </td></tr>

<!-- <tr><td height="5" colspan="2" align="center" valign="middle" > </td></tr>-->
  
    <tr>
<td valign="middle" align="center" width="180" style="background:#efe9d9; border-top:1px solid #C00; text-align:center; border-right:1px solid #C00; padding:5px; margin:0; font-family:\'Open Sans\',\'Trebuchet MS\'; font-size:16px; ">
			<p style="margin:0;"><strong>PAYMENT MODE</strong> <br>'.$paymentType.'</p></td>    
            <td valign="middle" width="180" align="center" style="background:#efe9d9; border-top:1px solid #C00; padding:5px; text-align:center; margin:0; font-family:\'Open Sans\',\'Trebuchet MS\'; font-size:16px; ">
			<p style="margin:0;"><strong>REG.NUMBER</strong> <br>'.$resUserDtls[0]['Id'].'</p></td>
            <td valign="middle" align="center" style="background:#efe9d9; padding:0; margin:0; font-family:\'Open Sans\',\'Trebuchet MS\'; font-size:16px; ">&nbsp;
			</td>
  </tr>';
                    if($displayAmount){
  $data.='<tr><td valign="middle" height="1" align="center" style="padding:0; margin:0;" colspan="2"><p style=" height:2px; padding:0; margin-top: 2px; margin-bottom: 0;"></p> </td></tr>
  <tr>
<td valign="middle" align="center" style="background:#efe9d9; text-align:center; border-top:1px solid #C00; padding:5px; margin:0; font-family:\'Open Sans\',\'Trebuchet MS\'; font-size:16px; ">
   <p style="margin:0;"><strong>PRICE<span style="font-size:12px;">&nbsp;(Incl of ET)</span></strong> <br>'.round($resUserDtls[0]['Qty']*$resUserDtls[0]['Fees']).'</p></td>    
            <td valign="middle" align="center" style="background:#efe9d9; padding:5px; margin:0; border-top:1px solid #C00; font-family:\'Open Sans\',\'Trebuchet MS\'; font-size:16px; ">&nbsp;</td>
  </tr>';
                    }
$data.='</tbody></table>
</td>
   
  </tr>
</tbody></table>
</td>
  </tr>
  <tr>
    <td align="center" style="background:#efe9d9; padding:0; margin:0; font-family:\'Open Sans\',\'Trebuchet MS\'; font-size:18px; font-weight:700; border-top:1px solid #C00; padding:10px 0 !important;">
        Name: '.$resUserDtls[0]['Name'].'</td>
  </tr>
</tbody></table>
</td>
  </tr>
  <tr>
    <td style="background:#f60; padding:5px 0; padding:0; margin:0; font-family:\'Open Sans\',\'Trebuchet MS\'; font-size:15px; color:#FFF !important; text-align:center;">
			This is not your ticket. Exchange this at the boxoffice for your ticket.</td>
  </tr>
</tbody></table>
</td>
  </tr>
</tbody></table>
    


    </td> 
    <td width="4" height="320" style="background:#efe9d9; padding:0; margin:0;"></td> 
    <td valign="top" align="left" style="padding:0; margin:0; width:160px;">
    	
        <table width="160" cellspacing="0" cellpadding="0" border="0">
  <tbody><tr>
    <td align="right" style="font-family:\'Open Sans\',\'Trebuchet MS\'; font-size:12px; color:#333; padding:0; margin:0;"><img width="104" height="59" alt="" src="'._HTTP_CF_ROOT.'/images/mera-logo.png"><br>www.meraevents.com</td>
  </tr>
  <tr>
    <td align="right" style="padding:5px 0; margin: 0;">
           
<img src="'.$barcode_link.'" width="70" height="158" style="padding: 10px 0px;" >           
       
  </tr>
  <tr>
   <td style="background:#efe9d9; font-family:\'Open Sans\',\'Trebuchet MS\'; font-size:12px; font-weight:normal; padding:0; margin:0; text-align:right;">
			 <p style="margin-right:10px; padding-top:5px 0; margin: 0;">+91-9396 555 888<br>
support@meraevents.com</p></td>
  </tr>
</tbody></table>
    </td> 
    <td width="4" height="320" style="background:#efe9d9; padding:0; margin:0;"></td> 
  </tr>
 </tbody></table>
    </td>
  </tr>
    <tr>
  <td align="center"><img style="width:99%" src="'._HTTP_SITE_ROOT.'/images/sunburnTC.jpg"></td>
</tr> 
</tbody></table>';
            }
            return $data;
           // echo  $data;exit;
         }
        function getCustomPassHtml($EventId,$EventSignupId){
            $Globali = new cGlobali();
		
		$Events = @new cEvents($EventId);
                $Success = $Events->Load();
                $TicketingOptionsRes=$Globali->SelectQuery("SELECT enable_info FROM ticketingoptions WHERE EventId='".$Globali->dbconn->real_escape_string($Events->Id)."'");
			//print_r($TicketingOptionsRes);
			
			if(count($TicketingOptionsRes)>0)
			{
				if($TicketingOptionsRes[0]['enable_info']==0){$TicketingOption=0;}//collect one
				else{ $TicketingOption=1; }//collect all
			}
			else{ $TicketingOption=0; } //collect one
		
		if($TicketingOption==0){$sqlAddLimit=" limit 1";}else{$sqlAddLimit="";}
					  
					  
		$atteneeSql="select a.Id 'attendeeId',a.Name, a.Email,t.Name 'ticketName', a.Phone from `Attendees` AS a
									 RIGHT JOIN `tickets` AS t ON a.ticketid=t.Id
									 where a.`EventSIgnupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."' ".$sqlAddLimit;
		$attendeeData = $Globali->SelectQuery($atteneeSql);
					  
		$sqlTktDetails="select estd.TicketId,t.`Name`,t.Description,estd.`NumOfTickets`,estd.`TicketAmt` 
				  from `eventsignupticketdetails` AS estd
					RIGHT JOIN `tickets` AS t ON estd.TicketId=t.Id
					WHERE estd.`EventSignupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."'";
					
		$estdData=$Globali->SelectQuery($sqlTktDetails);
		$ticketIdsArr=array();
		for($et=0;$et<count($estdData);$et++)
		{
			$ticketIdsArr[]=$estdData[$et]['TicketId'];
		}
		?>
<div id="printDiv">
<section class="container strip">

</section>
<section class="container main">
<div class="row">
<div class="col-md-12" style="padding:3px;">
<p style="border:3px solid #0a0a0a;"><img src="<?php echo _HTTP_SITE_ROOT;?>/eticket/ads1.jpg" width="100%" height="100px;"/></p>
</div>
</div>
<div class="row">
<div class="col-md-12">
 <span class="col-md-3"><img src="<?php echo _HTTP_SITE_ROOT;?>/eticket/logo2.png" alt="CCL CRICKET"/></span>
  <span class="col-md-9">
 

			<img src="<?php echo _HTTP_SITE_ROOT;?>/eticket/cclposter.JPG" width="70%" height="100%" alt="CCL CRICKET" class="pull-right"/>

  </span>
</div>
</div><br/>

<!-- header part -->


<section class="row1">
     
   
<div class="col-md-12">
    		<div class="panel panel-primary">
    			
    				<strong style="color:red;">E-Ticket</strong><hr>
    			
                <table class="pull-left" width="60%" align="left">
				<tr><td><b>Name:</b></td><td><?php echo $attendeeData[0]['Name'];?></td></tr>
				<tr><td><b>Mobile:</b></td><td><?php echo $attendeeData[0]['Phone'];?></td></tr>
				<tr><td><b>E-mail:</b></td><td><?php echo $attendeeData[0]['Email'];?></td></tr>
				</table>
                 <table class="pull-right" width="40%" align="right">
                     <?php
                        $strtDt=  date('d M Y', strtotime($Events->StartDt));
                        $time=date('g a', strtotime($Events->StartDt))." to ".date('g a', strtotime($Events->EndDt));
                     ?>
				 <tr><td><b>Match:</b></td><td><?php echo $Events->Title;?></td></tr>
				<tr><td><b>Venue:</b></td><td>Hyderabad (L.B Stadium)</td></tr>
				<tr><td><b>Date:</b></td><td><?php echo $strtDt;?></td></tr>
				<tr><td><b>Time:</b></td><td><?php echo $time?></td></tr>
				 </table>
    			<div class="panel-body">
    				<div class="table-responsive">
    					<table class="table table-condensed" width="100%" align="left">
    						<thead>
                                                    <tr><td colspan="4" height="40px">&nbsp;</td></tr>
                                <tr>
        							<td><strong>Booking Id</strong></td>
									<td class="text-center"><strong>Seating Category</strong></td>
        							<!-- <td class="text-center"><strong>Ref. Number</strong></td>-->
        							<td class="text-center"><strong>Quantity</strong></td>
        							<td class="text-right"><strong>Total Paid(Rs.)</strong></td>
                                </tr>
    						</thead>
    						<tbody>
    							<!-- foreach ($order->lineItems as $line) or some such thing here -->
                                                        <?php $tktTotalAmount=0;				  
				  for($t=0;$t<count($estdData);$t++)
				  {
					  $ticketIdsArr[]=$estdData[$t]['TicketId'];
					  $tktTotalAmount+=$estdData[$t]['TicketAmt'];
					  ?>
    							<tr>
    								<td><b><?php echo $EventSignupId;?></b></td>
                                                                <td class="text-center"><?php echo   str_replace('Category ', '', $estdData[$t]['Name']);?></td>
    								<!-- <td class="text-center">Ref.101</td>-->
    								<td class="text-center"><?php echo $estdData[$t]['NumOfTickets'];?></td>
    								<td class="text-right"><?php echo $estdData[$t]['TicketAmt'];?></td>
    							</tr>
                        	<?php } ?>
    						<tr>
                                                    <td colspan="2">&nbsp;</td>
                                                    <td class="text-center"><b><b>Total Amount </b></td>
                                                    <td class="text-right"><?php echo $tktTotalAmount;?></td>
                                                </tr>	
    						</tbody>
    					</table>
    				</div>
					<div class="col-md-12" style="border:3px solid #0a0a0a;">
					<img src="<?php echo _HTTP_SITE_ROOT;?>/eticket/ads2.jpg" width="100%" height="100px;"/>
					</div>
                             <?php
			$selTnC="SELECT * FROM eventTC WHERE EventId='".$Globali->dbconn->real_escape_string($EventId)."'";
			$ResTnC=$Globali->SelectQuery($selTnC);
			if(count($ResTnC)>0)
			{
                            $desc=NULL;
                            if(strcmp($ResTnC[0]['showTC'],'organizer')==0){
                                $desc=$ResTnC[0]['Org_Description'];
                            }else if (strcmp($ResTnC[0]['showTC'],'meraevents')==0){
                                $desc=$ResTnC[0]['ME_Description'];
                            }
			    if(!empty($desc)){	?>
					<div class="col-md-12">
					<div style="float:left; padding:15px; width:100%;">
                                                    <?php echo $desc?>
                                        </div>	</div> <?php } }?></div>
					<p><button type="button" class="btn btn-warning pull-right" onclick="ClickHereToPrintpass('printDiv')"  style="margin-top: 0px;">Print Ticket</button> </p>
                                        <?php if(!$ismobile){ ?>
                                        <p><input type="text" name="emailToSendPass" id="emailToSendPass" value=""/><button type="button" class="btn btn-warning" name="EMailButton" id="EMailButton" onclick="ClickHereToEmailpass();" style="margin-top: 0px;">Send Email</button><span id="sendEmailLoading"></span> </p>
                                        <p class="pull-right" >Powered By<br/>
                                    <img src="http://content.meraevents.com/images/logo-Eticket.jpg"></p>
                                        <?php }?>
    			</div>
    		</div>
    	</div>
</section>



</section>

                    <?php
        }
        function getCustomPrintPassPDF($EventId,$EventSignupId){
       $Globali = new cGlobali();
	   
	   
	   $Events = @new cEvents($EventId);
       $Success = $Events->Load();
		$TicketingOptionsRes=$Globali->SelectQuery("SELECT enable_info FROM ticketingoptions WHERE EventId='".$Globali->dbconn->real_escape_string($Events->Id)."'");
			//print_r($TicketingOptionsRes);
			
			if(count($TicketingOptionsRes)>0)
			{
				if($TicketingOptionsRes[0]['enable_info']==0){$TicketingOption=0;}//collect one
				else{ $TicketingOption=1; }//collect all
			}
			else{ $TicketingOption=0; } //collect one
			
		
		
		if($TicketingOption==0){$sqlAddLimit=" limit 1";}else{$sqlAddLimit="";}
						
		$atteneeSql="select a.Id 'attendeeId', a.Name, a.Email,t.Name 'ticketName', a.Phone from `Attendees` AS a
				 RIGHT JOIN `tickets` AS t ON a.ticketid=t.Id
				 where a.`EventSIgnupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."' ".$sqlAddLimit;
				 
		$attendeeData = $Globali->SelectQuery($atteneeSql);
		
		
		
		$sqlTktDetails="select estd.TicketId,t.`Name`,t.Description,estd.`NumOfTickets`,estd.`TicketAmt` 
				  from `eventsignupticketdetails` AS estd
					RIGHT JOIN `tickets` AS t ON estd.TicketId=t.Id
					WHERE estd.`EventSignupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."'";
					
		$estdData=$Globali->SelectQuery($sqlTktDetails);
		
		$ticketIdsArr=array();
		for($et=0;$et<count($estdData);$et++)
		{
			$ticketIdsArr[]=$estdData[$et]['TicketId'];
		}
		
		/*$customSql="select cf.`Id`, cf.`EventCustomFieldName` from `eventcustomfields` AS cf
			LEFT JOIN `event_ticket_customfields` AS etc ON cf.Id=etc.eve_tic_eventcustomfield_id
			where cf.`displayOnTicket`=1 and cf.EventCustomFieldSeqNo > 0
			and (cf.`EventId`='".$Globali->dbconn->real_escape_string($Events->Id)."' or etc.`eve_tic_ticket_id` in (".implode(",",$ticketIdsArr)."))";
			
			
		$customData = $Globali->SelectQuery($customSql);
		$cfDatacount=count($customData);*/
                 $strtDt=  date('d M Y', strtotime($Events->StartDt));
                 $time=date('g a', strtotime($Events->StartDt))." to ".date('g a', strtotime($Events->EndDt));
       $data='<table width="980" align="center" cellpadding="0" cellspacing="0">
	<tr>
   		<td style="border:3px solid #0a0a0a;" align="center" valign="top"><img src="eticket/ccl-ad-1-pdf.jpg" width="100%" height="100"/></td>
	</tr>
    <tr><td height="20"></td></tr><!--For Spacing-->
     <tr>
   		<td>
        	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
              <tr>
                <td width="287" align="left" valign="top"><img src="eticket/logo2.png" alt="CCL CRICKET" width="287" /></td>
                <td width="60%" align="right" valign="top"><img src="eticket/cclposter.JPG" width="90%" /></td>
              </tr>
            </table>


       </td>
	</tr>
    <tr><td height="10"></td></tr><!--For Spacing-->
     <tr>
   		<td align="left" style="float:left; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:20px; color:#f00; padding:5px 10px; font-weight:bold; border-bottom:2px solid #ccc; width:100%;">E-Ticket</td>
	</tr>
    <tr><td height="10"></td></tr><!--For Spacing-->
     <tr>
   		<td>
        	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
  <tr>
    <td width="40%" align="left" valign="top">
    	<table width="490" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100" style="float:left; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; color:#000; padding:5px 10px; font-weight:bold; ">Name : </td>
   
    <td width="350" align="left" style="float:left; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; color:#000; padding:5px 10px; font-weight:normal;">'.$attendeeData[0]['Name'].'</td>
  </tr>
  <tr>
    <td width="100" style="float:left; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; color:#000; padding:5px 10px; font-weight:bold; ">Mobile : </td>
    
    <td align="left" width="350" style="float:left; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; color:#000; padding:5px 10px; font-weight:normal;">'.$attendeeData[0]['Phone'].'</td>
  </tr>
  <tr>
    <td width="100" style="float:left; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; color:#000; padding:5px 10px; font-weight:bold; ">E-mail : </td>
    
    <td align="left" width="350" style="float:left; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; color:#000; padding:5px 10px; font-weight:normal;">'.$attendeeData[0]['Email'].'</td>
  </tr>
</table>

</td>
    <td width="490" align="left" valign="top">
    		<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td width="130" style="float:left; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; color:#000; padding:5px 10px; font-weight:bold; ">Match : </td>
   
    <td width="320"align="left" valign="top" style="float:left; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; color:#000; padding:5px 10px; font-weight:normal; line-height:normal;">'.$Events->Title.'</td>
  </tr>
  <tr>
    <td width="130" style="float:left; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; color:#000; padding:5px 10px; font-weight:bold; ">Venue : </td>
    
    <td align="left" width="320" style="float:left; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; color:#000; padding:5px 10px; font-weight:normal;">Hyderabad (L.B Stadium)</td>
  </tr>
  <tr>
    <td width="130" style="float:left; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; color:#000; padding:5px 10px; font-weight:bold; ">Date : </td>
    
    
    <td align="left" width="320" style="float:left; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; color:#000; padding:5px 10px; font-weight:normal;">'.$strtDt.'</td>
  </tr>
  <tr>
    <td width="26%" style="float:left; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; color:#000; padding:5px 10px; font-weight:bold; ">Time : </td>
    
    
    <td align="left" style="float:left; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; color:#000; padding:5px 10px; font-weight:normal;">'.$time.'</td>
  </tr>
</table>
    </td>
  </tr>
</table>
        </td>
	</tr>
     <tr><td height="10"></td></tr><!--For Spacing-->
     <tr>
   		<td align="center" valign="top" width="980">
        
            <table width="980" border="1" cellspacing="0" cellpadding="10" bordercolor="#cccccc">
                <tr>
                <td width="245" align="left" style="font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#000; padding-bottom:10px; padding-left:15px;">Booking Id</td>
                <td width="245" align="center" style="font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#000; padding-bottom:10px;">Seating Category</td>
                <td width="245" align="center" style="font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#000; padding-bottom:10px;">Quantity</td>
                <td width="245" align="right" style="font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#000; padding-bottom:10px; padding-right:25px;">Total Paid(Rs.)</td>
                </tr>';
       $tktTotal=0;
        for($t=0;$t<count($estdData);$t++)
				  {
            $tktTotal+=$estdData[$t]['TicketAmt'];
                $data.= '<tr>
                  <td width="245" align="left" style="font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; font-weight:normal; color:#000; padding-bottom:10px; padding-left:15px;">'.$EventSignupId.'</td>
                  <td width="245" align="center" style="font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; font-weight:normal; color:#000; padding-bottom:10px;">'.  str_replace('Category ', '', $estdData[$t]['Name']).'</td>
                  <td width="245" align="center" style="font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; font-weight:normal; color:#000; padding-bottom:10px;">'.$estdData[$t]['NumOfTickets'].'</td>
                  <td width="245" align="right" style="font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; font-weight:normal; color:#000; padding-bottom:10px; padding-right:25px;">'.$estdData[$t]['TicketAmt'].'</td>
                </tr>';
                                  }
            $data.='<tr>
                  <td width="245" align="right" style="font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; font-weight:normal; color:#000; padding-bottom:10px; padding-left:15px;" colspan="3">Total Amount </td>
                  <td width="245" align="right" style="font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:16px; font-weight:normal; color:#000; padding-bottom:10px; padding-left:15px;">'.$tktTotal.'</td></tr></table>
        
       </td>
	</tr>
     <tr><td height="10"></td></tr><!--For Spacing-->
     <tr>
   		<td align="center" width="100%" valign="top" style="border:3px solid #0a0a0a;"><img src="eticket/ccl-ad-2-pdf.jpg" width="100%" height="100px;"/></td>
	</tr>';
  $selTnC="SELECT * FROM eventTC WHERE EventId='".$Events->Id."'";
			$ResTnC=$Globali->SelectQuery($selTnC);
                        
			if(count($ResTnC)>0)
			{
                            $desc=NULL;
                            if(strcmp($ResTnC[0]['showTC'],'organizer')==0){
                                $desc=$ResTnC[0]['Org_Description'];
                            }else if (strcmp($ResTnC[0]['showTC'],'meraevents')==0){
                                $desc=$ResTnC[0]['ME_Description'];
                            }
			    if(!empty($desc)){
    $data.='<tr>
   		<td align="left" valign="top" style="padding:10px; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; font-size:15px; color:#000; font-weight:normal;">'.$desc.'</td>
	</tr>';
                            }
                        }
    $data.='<tr><td height="10"></td></tr><!--For Spacing-->
    <tr>
	  <td align="left" valign="top" style="background:#333;">
      </td>
	</tr>
     <tr><td align="right">Powered By<br/><img src="http://content.meraevents.com/images/logo-Eticket.jpg"></td></tr>
</table>
 '; return $data;
   }
	function getPrintPassHtml($EventId,$EventSignupId)
	{
		$Globali = new cGlobali();
		
		$Events = @new cEvents($EventId);
        $Success = $Events->Load();
		
		//getting PMI reg no
		if($Events->Id==56832) {
			$sqlField="select `field2` from `EventSignup` where `Id`='".$Globali->dbconn->real_escape_string($EventSignupId)."'";
			$pmi2RegNo=$Globali->GetSingleFieldValue($sqlField);
		}
		
		
    	$TSeats=$Globali->GetSingleFieldValue("SELECT EventId from VenueSeats WHERE EventId='".$Globali->dbconn->real_escape_string($Events->Id)."'");
		if($TSeats>0){
			
			$seatQuery= "SELECT GridPosition,Seatno,Type FROM VenueSeats WHERE EventSIgnupId='".$Globali->dbconn->real_escape_string($EventSignupId)."' order by id ";
        $seatRes = $Globali->SelectQuery($seatQuery);
        $SNo="";
        for($s=0;$s<count($seatRes);$s++){
            if($seatRes[$s]['Type']=='GASECTION' || $seatRes[$s]['Type']=='GASECTIONBANGALORE' || $seatRes[$s]['Type']=='GASEATING'  || $seatRes[$s]['Type']=='VIPSEATING')
            {
              //  $rowno=preg_replace("/[0-9]/", "", $rowno);
                $SNo.= preg_replace("/[0-9]/", "", $seatRes[$s]['GridPosition']).$seatRes[$s]['Seatno'].", ";
            }else if($seatRes[$s]['Type']=='FIRSTFLOOR' )
            {
              //  $rowno=preg_replace("/[0-9]/", "", $rowno);
                $SNo.= substr($seatRes[$s]['GridPosition'],0,2).$seatRes[$s]['Seatno'].", ";
            }else
            $SNo.= substr($seatRes[$s]['GridPosition'],0,1).$seatRes[$s]['Seatno'].", ";
        }
			
		
		$SeatsNo=" <b>Seat Nos-</b> ".substr($SNo,0,-2);
		
		} else {
		$SeatsNo="";
		}
		
		
		$StartDt1 =date('F j, Y',strtotime($Events->StartDt));
        $EndDt1 = date('F j, Y',strtotime($Events->EndDt));
		$ddate=$StartDt1;
        if($StartDt1 !=$EndDt1){ $ddate.='-'.$EndDt1; }
		
		global $skirllexEvents;
		if(in_array($Events->Id,$skirllexEvents)){ $ddate.=' | Time : Gates Open at 5 PM' ;}
		elseif($Events->Id==81101){  }
		else{ $ddate.=' | Time : '.date('g:i a',strtotime($Events->StartDt)).'-'.date('g:i a',strtotime($Events->EndDt)); }
		
		
		
		
	    $sqlEventSignup="select Qty,Fees,Ccharge,PaymentModeId,PromotionCode,PaymentTransId,PaymentGateway,Ccharge,STax,DAmount,referralDAmount,c.code,es.BarcodeNumber from EventSignup es INNER JOIN currencies c ON c.Id=es.CurrencyId  where es.Id='".$Globali->dbconn->real_escape_string($EventSignupId)."'";
		$res=$Globali->SelectQuery($sqlEventSignup);
                $spot_booking_values=array('SpotCash','SpotCard');
		if($res[0]['Fees']!=0)
		{
			if($res[0]['PaymentModeId']==1)
			{
				if($res[0]['PaymentTransId']=="PayPalPayment")
				{ $paymentmode="PayPal Transaction";  }
                                else if(in_array($res[0]['PaymentTransId'], $spot_booking_values)){
                                    $paymentmode="Spot Booking";
                                } else{ $paymentmode="Card Transaction"; }
			}
			else
			{
				$paymentmode="Cheque Transaction";
			}
			if($res[0]['PromotionCode']=="Y")
			{
				$paymentmode="Pay At Counter";
			}
			elseif($res[0]['PaymentGateway']=="CashonDelivery")
			{
				$paymentmode="Cash On Delivery";
			}
		} else { $paymentmode="Free Registration"; }
		
		
		
		$TicketingOptionsRes=$Globali->SelectQuery("SELECT enable_info FROM ticketingoptions WHERE EventId='".$Globali->dbconn->real_escape_string($Events->Id)."'");
			//print_r($TicketingOptionsRes);
			
			if(count($TicketingOptionsRes)>0)
			{
				if($TicketingOptionsRes[0]['enable_info']==0){$TicketingOption=0;}//collect one
				else{ $TicketingOption=1; }//collect all
			}
			else{ $TicketingOption=0; } //collect one
		
		if($TicketingOption==0){$sqlAddLimit=" limit 1";}else{$sqlAddLimit="";}
					  
					  
		$atteneeSql="select a.Id 'attendeeId',a.Name, a.Email,t.Name 'ticketName', a.Phone from `Attendees` AS a
									 RIGHT JOIN `tickets` AS t ON a.ticketid=t.Id
									 where a.`EventSIgnupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."' ".$sqlAddLimit;
		$attendeeData = $Globali->SelectQuery($atteneeSql);
					  
		$sqlTktDetails="select estd.TicketId,t.`Name`,t.Description,estd.`NumOfTickets`,estd.`TicketAmt`,t.`Donation` 
				  from `eventsignupticketdetails` AS estd
					RIGHT JOIN `tickets` AS t ON estd.TicketId=t.Id
					WHERE estd.`EventSignupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."'";
					
		$estdData=$Globali->SelectQuery($sqlTktDetails);
		$ticketIdsArr=array();$isOnlyDonation=true;$isOnlyDonationArr=NULL;
		for($et=0;$et<count($estdData);$et++)
		{
			$ticketIdsArr[]=$estdData[$et]['TicketId'];
                        if($estdData[$et]['Donation']!=1){
                            $isOnlyDonationArr[]=false;
                        }
		}
		$arrValues=  array_values($isOnlyDonationArr);
                if(in_array(false, $arrValues)){
                    $isOnlyDonation=false;
                }
		
		
		$customSql="select cf.`Id`, cf.`EventCustomFieldName` from `eventcustomfields` AS cf
			LEFT JOIN `event_ticket_customfields` AS etc ON cf.Id=etc.eve_tic_eventcustomfield_id
			where cf.`displayOnTicket`=1 and cf.EventCustomFieldSeqNo > 0
			and  (cf.`EventId`='".$Globali->dbconn->real_escape_string($Events->Id)."' or etc.`eve_tic_ticket_id` in (".implode(",",$ticketIdsArr)."))";
			
			$customData = $Globali->SelectQuery($customSql);
			$cfDatacount=count($customData);
		
		?>
    
    
    
    <div class="printtable"  id="PrintInnerPass" style="margin-top:20px; margin-left:22px;font-family: 'maven_proregular';">
    <?php //echo $commonFunctions->getPrintPass($Events->Id,$EventSignupId,$Globali); ?>
    
        <div id="PrintTicketsNew">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="left" valign="top" width="50%"><h3><?php echo stripslashes($Events->Title);?></h3>
                     <?php global $HideDateTime; if(in_array($Events->Id, $HideDateTime)) {} else { ?> <p class="Print-Timings">Date :  <?php echo $ddate  ?></p> <?php } ?>
                      <p class="Print-Timings">Venue : <?php echo nl2br($Events->Venue); ?></p></td>
                    <td calss="Print-Registration" align="right" valign="top" width="50%"><h4>Event ID : <?php echo $Events->Id; ?><br>
                        Registration No : <?php echo $EventSignupId;?><br>
                        <?php if($Events->Id==56832){ ?> PMI Conference Registration No: <?php echo $pmi2RegNo."<br>"; } ?>
                        Payment Mode : <?php echo $paymentmode; ?>
                        <?php if(strlen(trim($SeatsNo))>0){
							echo '<br>'.$SeatsNo;
							}
							
						if(($TicketingOption==0 & $cfDatacount==0) || $cfDatacount==0){
							echo "<br>Name : ".$attendeeData[0]['Name'];
						}
						?>
                            
                            </h4> </td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td class="Print-TicketTable"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <th width="50%">Ticket Type</th>
                    <th width="25%">Quantity</th>
                    <th width="25%">Price</th>
                  </tr>
                  <?php
				 
				  
				  $tktTotalAmount=0;
				  				  
				  for($t=0;$t<count($estdData);$t++)
				  { 
					  $ticketIdsArr[]=$estdData[$t]['TicketId'];
					  $tktTotalAmount+=$estdData[$t]['TicketAmt'];
					  ?>
                      <tr>
                        <td align="left" valign="top" style="padding-left:20px;"><?php echo $estdData[$t]['Name']; ?><br />
                    <?php if($EventId!=$this->smirnoff && $EventId!=73632){?>        
                    <span style="font-size:11px"><?php echo $estdData[$t]['Description']; ?></span>
                        <?php } ?>
                        </td>
                        <td align="left" valign="top" style="padding-left:20px;"><?php echo $estdData[$t]['NumOfTickets']; ?></td>
                        <td align="left" valign="top" style="padding-left:20px;"><?php echo $res[0]['code']." ".$estdData[$t]['TicketAmt']; ?></td>
                      </tr>
                      <?php
				  }
				  if(!$isOnlyDonation){?>
                  <tr>
                    <td align="left" valign="top" style="padding-left:20px; border-right:0;">&nbsp;</td>
                    <td align="right" valign="top" style="padding-right:20px; text-align:right;">Total Amount</td>
                    <td align="left" valign="top" style="padding-left:20px;"><?php echo $res[0]['code']." ".$tktTotalAmount; ?></td>
                  </tr>
                 
                                  <?php }
				  if($res[0]['STax']>0)
				  {
					  ?>
                      <tr>
                        <td align="left" valign="top" style="padding-left:20px; border-right:0;">&nbsp;</td>
                        <td align="right" valign="top" style="padding-right:20px; text-align:right;">Service Tax</td>
                        <td align="left" valign="top" style="padding-left:20px;"><?php echo $res[0]['code']." ".round($res[0]['STax'],2); ?></td>
                      </tr>
                      <?php
				  }
				  
				  $sqlBulkDiscount="select sum(BulkDiscount) from eventsignupticketdetails where `EventSignupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."'";
				  $totalBulkDiscount=$Globali->GetSingleFieldValue($sqlBulkDiscount);
				  $totalBulkDiscount=($totalBulkDiscount>0)?$totalBulkDiscount:0;
				  
				  if(($res[0]['DAmount'] + $res[0]['referralDAmount'])>0)
				  {
					  $DAmount=$res[0]['DAmount']+$res[0]['referralDAmount']-$totalBulkDiscount;
					  if($DAmount>0)
				  	  {
					  ?>
                      <tr>
                        <td align="left" valign="top" style="padding-left:20px; border-right:0;">&nbsp;</td>
                        <td align="right" valign="top" style="padding-right:20px; text-align:right;">Discount Amount</td>
                        <td align="left" valign="top" style="padding-left:20px;"><?php echo $res[0]['code']." ".round($DAmount,2); ?></td>
                      </tr>
                       <?php
					  }
				  }				  
				  
				  if($totalBulkDiscount>0)
				  {
					  ?>
                      <tr>
                        <td align="left" valign="top" style="padding-left:20px; border-right:0;">&nbsp;</td>
                        <td align="right" valign="top" style="padding-right:20px; text-align:right;">Bulk Discount</td>
                        <td align="left" valign="top" style="padding-left:20px;"><?php echo $res[0]['code']." ".round($totalBulkDiscount,2); ?></td>
                      </tr>
                      <?php
				  }
				  if($res[0]['PaymentGateway']=="CashonDelivery") {?>
                      <tr>
                        <td align="left" valign="top" style="padding-left:20px; border-right:0;">&nbsp;</td>
                        <td align="right" valign="top" style="padding-right:20px; text-align:right;">Courier Charges</td>
                        <td align="left" valign="top" style="padding-left:20px;">INR 40</td>
                      </tr>
                      <?php
				  }
				  
				  
				  $toatlPurchaseAmount=round($res[0]['Qty']*$res[0]['Fees']-$res[0]['Ccharge']);
				  if($res[0]['PaymentGateway']=="CashonDelivery") {$toatlPurchaseAmount+=40;}
                                  //$totalText="Total Purchase Amount ";
                                  //if(in_array($EventId, $this->hideOrgDetails) || $Events->Id==73246){
                                    $totalText='<b>Total Purchase Amount <span style="font-size: 12px; line-height: 12px; padding: 0px; margin: 0px; width: 100%; float: right; text-align: right;">(Incl of All Taxes, excluding convenience fee)</span></b>';
                                //  }else
                                      if($isOnlyDonation){
                                      $totalText="<b>Donation Total</b>";
                                  }
                                  ?>
                  
                  <tr>
                      <td colspan="2" align="right" valign="top" style="padding-right:20px; text-align:right;"><?php echo $totalText;?></td>
                    <td align="left" valign="top" style="padding-left:20px;"><b><?php echo $res[0]['code']." ".round($toatlPurchaseAmount,2);?></b></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td></td>
            </tr>
            
            
            
            
           
          	<?php
			
			
			if($cfDatacount>0)
			{
				?>
                <tr><td><b style="font-size: 14px;">Attendee Information</b></td></tr>
				<tr>
				  <td class="Print-DelegatesInfo"><table width="970" border="0" cellspacing="0" cellpadding="0" >
					  <tr>
						<th>Delegate Name</th>
						<!--<th>Mobile</th>
						<th>Email</th>-->
						<th>Ticket Type</th>
						<?php
						for($cn=0;$cn<$cfDatacount;$cn++)
						{
							?> <th><?php echo $customData[$cn]['EventCustomFieldName']; ?></th> <?php
						}
						?>
					  </tr>
					  
					  <?php
					  for($a=0;$a<count($attendeeData);$a++)
					  {
						  ?>
						  <tr>
							<td><?php echo $attendeeData[$a]['Name']; ?></td>
						   <!-- <td><?php //echo $attendeeData[$a]['Phone']; ?></td>
							<td><?php //echo $attendeeData[$a]['Email']; ?></td>-->
							<td><?php echo $attendeeData[$a]['ticketName']; ?></td>
							<?php
							if($cfDatacount>0)
							{
								for($cd=0;$cd<$cfDatacount;$cd++)
								{
									?><td><?php echo $Globali->GetSingleFieldValue("select `EventSignupFieldValue` from `eventsignupcustomfields` where `EventCustomFieldsId`='".$Globali->dbconn->real_escape_string($customData[$cd]['Id'])."' and `attendeeId`='".$Globali->dbconn->real_escape_string($attendeeData[$a]['attendeeId'])."' and `EventId`='".$Globali->dbconn->real_escape_string($Events->Id)."' and `EventSignupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."'");; ?></td><?php
								}
							}
							?>
						  </tr>
						  <?php
					  }
					  ?>
					  
					 
					</table></td>
				</tr>
				
				<?php
			}
			?>
            
            <tr><td>&nbsp;</td></tr>
           


             <?php if(!in_array($EventId, $this->hideOrgDetails) && $Events->Id!=73246){ ?>
            <tr>
              <td class="Print-OrganizerInfo"><table width="100%" border="0" cellspacing="0" cellpadding="0" >
                  <tr>
                  	<?php
					$orgSql="select CONCAT(FirstName, ' ', LastName) 'orgName' from `user` where Id='".$Globali->dbconn->real_escape_string($Events->UserID)."' ";
					$orgData=$Globali->SelectQuery($orgSql);
					?>
                    <td align="left" valign="top" >
                      
                        <h3>Organizer Contact  Details   :   <?php echo $orgData[0]['orgName']; ?></h3>
                      <p><?php if(strlen($Events->ContactDetails)){ echo 'Phone or Email : '.$Events->ContactDetails; }  if(strlen($Events->WebUrl)){ echo ' | Website : '.$Events->WebUrl; } ?></p>
                     
                    </td>
                  </tr>
                </table></td>
            </tr>
             <?php }?>
            <?php if($Events->Id==56832){ ?><tr><td  class="Print-Text" align="center">This is an acknowledgement for your registration. A detailed confirmation mail will be sent from PMI Conference team within 10 days</td></tr> <?php } ?>
            
            <?php $tc=NULL;
            
             $uniqueNum=$res[0]['BarcodeNumber'];
                        if(empty($uniqueNum) || $uniqueNum==0){
                             $uniqueNum=  substr($Events->Id, 1,4).$EventSignupId;
                        }
                        ?>
            <tr>
              <td class="Print-Text">You are most welcome to contact us for any query, please call us at +91-9396555888</td>
            </tr>
            <tr>
            	<td>
                	<table width="100%" border="0" cellspacing="0" cellpadding="0" >
                      <tr>
                        <td width="50%"> 
<!--                            <img src="http://barcode.tec-it.com/barcode.ashx?code=Code128&modulewidth=fit&data=<?php echo $EventSignupId.$Events->Id;?>&dpi=96&imagetype=gif&rotation=90&color=&bgcolor=&fontcolor=&quiet=0&qunit=mm" height="90" width="70%" />-->
                 <img src="<?php echo _HTTP_SITE_ROOT;?>/barcode/barcode.php?text=<?php echo $uniqueNum;?>&angle=0" height="90" width="70%" style="padding: 10px 0px;" >     
                        </td>
                        <td align="right" width="50%" style="padding-right:20px;"><p style="padding-right:10px;">Powered By</p>
                                    <img src="http://content.meraevents.com/images/logo-Eticket.jpg"></td>
                      </tr>
                    </table>

                </td>
            </tr>
            <?php
            if(in_array($EventId, $this->hideOrgDetails)){ 
                $imagePath=_HTTP_SITE_ROOT.'/images/soa-terms.jpg';
                if($EventId==$this->smirnoff){
                    $imagePath=_HTTP_SITE_ROOT.'/images/smirnoff-terms.jpg';
                }
                 if($EventId==73632){
                    $imagePath=_HTTP_SITE_ROOT.'/images/asot-terms.jpg';
                }
                
            $tc='<tr><td>
                	<table width="100%" border="0" cellspacing="0" cellpadding="0" >
                      <tr>
                        <td> 
                 <img src="'.$imagePath.'" height="350" width="75%" style="padding: 10px 0px;" >  
                        </td>
                      </tr>
                        </table>
            </td></tr>';
           }
            else{
			$selTnC="SELECT * FROM eventTC WHERE EventId='".$Globali->dbconn->real_escape_string($EventId)."'";
			$ResTnC=$Globali->SelectQuery($selTnC);
                        //print_r($ResTnC);
			if(count($ResTnC)>0)
			{
                            $desc=NULL;
                            if(strcmp($ResTnC[0]['showTC'],'organizer')==0){
                                $desc=$ResTnC[0]['Org_Description'];
                            }else if (strcmp($ResTnC[0]['showTC'],'meraevents')==0){
                                $desc=$ResTnC[0]['ME_Description'];
                            }
			    if(!empty($desc)){	?>
                <tr>
                  <td class="Print-Terms">
                <?php if($Events->Id!=71772 && $Events->Id!=71769 && $Events->Id!=73246)  {?>  <h4><b>Terms & Conditions</b></h4> <?php } ?>
                    <?php echo $desc; ?>
                  </td>
                </tr>
                <?php }
			}
            }
                        $uniqueNum=$res[0]['BarcodeNumber'];
                        if(empty($uniqueNum) || $uniqueNum==0){
                             $uniqueNum=  substr($Events->Id, 1,4).$EventSignupId;
                        }
                        ?>
            
            <?php if(!is_null($tc)){
                echo $tc;
            }?>
          </table>
        </div>
      </div>
    <?php
    
    $Email_validation_event_array = array();
    if(strcmp($hostname,'www.meraevents.com')==0 || strcmp($hostname,'meraevents.com')==0) {
    
        $Email_validation_event_array = array(72553=> array('netcracker.com'));
    } elseif(strcmp($hostname,'stage.meraevents.com')==0) {
        $Email_validation_event_array = array(55483=> array('netcracker.com'));
    } 
//    else {
//        $Email_validation_event_array = array(64954=>array('netcracker.com'));
//    }
    
    $Email_validation_event_required = false;
    if(is_array($Email_validation_event_array[$Events->Id]) && count($Email_validation_event_array[$Events->Id]) > 0) {
        $validation_array = $Email_validation_event_array[$Events->Id];
        $domain1 = array_pop(explode('@', $attendeeData[0]['Email']));
        
        if(!in_array($domain1,$validation_array)) {
            $Email_validation_event_required = true;
        }
    }
    
    ?>
    

  <!--End of TwelveCol Print View-->
    <div style="clear:both">&nbsp;</div>
    <?php if(!$ismobile)
    {
		
        if(!$Email_validation_event_required) {
    ?>
      <div  style="margin:0px auto 0 45.5%; width:100px">
    <input style="width:114%;" title="Print" name="button" class="processbutton" value="Print" type="button" onClick="ClickHereToPrintpass('PrintInnerPass')">
     </div> 
       <?php } ?>
    &nbsp;
    <div id="sendPassDiv">
    <label id="sendPassToEmailTxt" for="sendPassToEmail" style="margin-left:10.85%;">Send Pass to email:</label>
        <input style="width:227px;" type="text" class="wizardtext" placeholder="Email id" name="sendPassToEmail" id="sendPassToEmail"  value="" /> 
        <input style="width: 10%;" id="EMailButton" title="Send Email" name="button" class="processbutton" value="Send Email" type="submit" onclick="ClickHereToEmailpass();"><span id="sendEmailLoading"></span>
    </div>
    
    <?php }?>
 
   <?php 
   }
   
   
   
   
   
   
   
   
   function getPrintPassPDF($EventId,$EventSignupId, $amount_status)
   {
	   $Globali = new cGlobali();
	   
	   
	   $Events = @new cEvents($EventId);
       $Success = $Events->Load();
	   
	   //getting PMI reg no
		if($Events->Id==56832) {
			$sqlField="select `field2` from `EventSignup` where `Id`='".$Globali->dbconn->real_escape_string($EventSignupId)."'";
			$pmi2RegNo=$Globali->GetSingleFieldValue($sqlField);
		}
		
		
	   
	   $TSeats=$Globali->GetSingleFieldValue("SELECT EventId from VenueSeats WHERE EventId='".$Globali->dbconn->real_escape_string($Events->Id)."'");
		if($TSeats>0){
			
			
			$seatQuery= "SELECT GridPosition,Seatno,Type FROM VenueSeats WHERE EventSIgnupId='".$Globali->dbconn->real_escape_string($EventSignupId)."' order by id ";
        $seatRes = $Globali->SelectQuery($seatQuery);
        $SNo="";
        for($s=0;$s<count($seatRes);$s++){
            if($seatRes[$s]['Type']=='GASECTION' || $seatRes[$s]['Type']=='GASECTIONBANGALORE' || $seatRes[$s]['Type']=='GASEATING'  || $seatRes[$s]['Type']=='VIPSEATING')
            {
              //  $rowno=preg_replace("/[0-9]/", "", $rowno);
                $SNo.= preg_replace("/[0-9]/", "", $seatRes[$s]['GridPosition']).$seatRes[$s]['Seatno'].", ";
            }else if($seatRes[$s]['Type']=='FIRSTFLOOR' )
            {
              //  $rowno=preg_replace("/[0-9]/", "", $rowno);
                $SNo.= substr($seatRes[$s]['GridPosition'],0,2).$seatRes[$s]['Seatno'].", ";
            }else
            $SNo.= substr($seatRes[$s]['GridPosition'],0,1).$seatRes[$s]['Seatno'].", ";
        }
			
		
		$SeatsNo=" <b>Seat Nos-</b> ".substr($SNo,0,-2);
		
		} else {
		$SeatsNo="";
		}
		
		
		$StartDt1 =date('F j, Y',strtotime($Events->StartDt));
        $EndDt1 = date('F j, Y',strtotime($Events->EndDt));
		$ddate=$StartDt1;
        if($StartDt1 !=$EndDt1){ $ddate.='-'.$EndDt1; }
		
		global $skirllexEvents;
		if(in_array($Events->Id,$skirllexEvents)){ $ddate.=' | Time : Gates Open at 5 PM' ;}
		elseif($Events->Id==81101){  }
		else{ $ddate.=' | Time : '.date('g:i a',strtotime($Events->StartDt)).'-'.date('g:i a',strtotime($Events->EndDt)); }
		
		
	  $sqlEventSignup="select Qty,Fees,Ccharge,PaymentModeId,PromotionCode,PaymentTransId,PaymentGateway,Ccharge,STax,DAmount,referralDAmount,CurrencyId,c.code,es.BarcodeNumber from EventSignup es INNER JOIN currencies c ON c.Id=es.CurrencyId where es.Id='".$Globali->dbconn->real_escape_string($EventSignupId)."'";
		
		$res=$Globali->SelectQuery($sqlEventSignup);
		if($res[0]['Fees']!=0)
		{
			if($res[0]['PaymentModeId']==1)
			{
				if($res[0]['PaymentTransId']=="PayPalPayment")
				{ $paymentmode="PayPal Transaction";  }
				else{ $paymentmode="Card Transaction"; }
			}
			else
			{
				$paymentmode="Cheque Transaction";
			}
			if($res[0]['PromotionCode']=="Y")
			{
				$paymentmode="Pay At Counter";
			}
			elseif($res[0]['PaymentGateway']=="CashonDelivery")
			{
				$paymentmode="Cash On Delivery";
			}
		} else { $paymentmode="Free Registration"; }
		
		
		
		
		
		$TicketingOptionsRes=$Globali->SelectQuery("SELECT enable_info FROM ticketingoptions WHERE EventId='".$Globali->dbconn->real_escape_string($Events->Id)."'");
			//print_r($TicketingOptionsRes);
			
			if(count($TicketingOptionsRes)>0)
			{
				if($TicketingOptionsRes[0]['enable_info']==0){$TicketingOption=0;}//collect one
				else{ $TicketingOption=1; }//collect all
			}
			else{ $TicketingOption=0; } //collect one
			
		
		
		if($TicketingOption==0){$sqlAddLimit=" limit 1";}else{$sqlAddLimit="";}
						
		$atteneeSql="select a.Id 'attendeeId', a.Name, a.Email,t.Name 'ticketName', a.Phone from `Attendees` AS a
				 RIGHT JOIN `tickets` AS t ON a.ticketid=t.Id
				 where a.`EventSIgnupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."' ".$sqlAddLimit;
				 
		$attendeeData = $Globali->SelectQuery($atteneeSql);
		
		
		
		$sqlTktDetails="select estd.TicketId,t.`Name`,t.Description,estd.`NumOfTickets`,estd.`TicketAmt`,t.`Donation` 
				  from `eventsignupticketdetails` AS estd
					RIGHT JOIN `tickets` AS t ON estd.TicketId=t.Id
					WHERE estd.`EventSignupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."'";
					
		$estdData=$Globali->SelectQuery($sqlTktDetails);
		
		$ticketIdsArr=array();$isOnlyDonation=true;$isOnlyDonationArr=NULL;
		for($et=0;$et<count($estdData);$et++)
		{
			$ticketIdsArr[]=$estdData[$et]['TicketId'];
                        if($estdData[$et]['Donation']!=1){
                            $isOnlyDonationArr[]=false;
                        }
		}
		$arrValues=  array_values($isOnlyDonationArr);
                if(in_array(false, $arrValues)){
                    $isOnlyDonation=false;
                }
		$customSql="select cf.`Id`, cf.`EventCustomFieldName` from `eventcustomfields` AS cf
			LEFT JOIN `event_ticket_customfields` AS etc ON cf.Id=etc.eve_tic_eventcustomfield_id
			where cf.`displayOnTicket`=1 and cf.EventCustomFieldSeqNo > 0
			and (cf.`EventId`='".$Globali->dbconn->real_escape_string($Events->Id)."' or etc.`eve_tic_ticket_id` in (".implode(",",$ticketIdsArr)."))";
			
			
		$customData = $Globali->SelectQuery($customSql);
		$cfDatacount=count($customData);
	   
	   
	   $data='<table width="900" border="0" cellspacing="0" cellpadding="0" align="center" style="border:1px solid #ccc;font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; margin-top:20px;">
            <tr>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="border-collapse: collapse;">
                  <tr>
                    <td align="left" valign="top" width="50%" style="padding:15px 20px;"><h3 style="margin:0; padding:0 0 5px 0; font-size:18px;">'.stripslashes($Events->Title).'</h3>';
                      global $HideDateTime;  
                    if(in_array($Events->Id,$HideDateTime)){ } else { 
                    $data.='<p style="padding:2px 0 0 0; margin:0; font-size:14px;">Date : '.$ddate.'</p>';
                    }
                    $data.='<p style="padding:2px 0 0 0; margin:0; font-size:14px;">Venue : '.nl2br($Events->Venue).'</p></td>
                            <td align="right" valign="top" width="50%" style="padding:15px 20px;"><h4 style="padding:0; font-size:16px; line-height:22px; margin:0; font-weight:normal;">Event ID : '.$Events->Id.'<br>
                        Registration No : '.$EventSignupId.'<br>';
						
						
						if($Events->Id==56832){ $data.='PMI Conference Registration No: '.$pmi2RegNo.'<br>'; } 
						
						
                        $data.='Payment Mode : '.$paymentmode; 
						if(strlen(trim($SeatsNo))>0){ $data.='<br>'.$SeatsNo; }
						if(($TicketingOption==0 & $cfDatacount==0) || $cfDatacount==0){ $data.='<br>Name : '.$attendeeData[0]['Name']; }
				
				
				$data.='</h4></td>
                  </tr>
                </table></td>
            </tr>
			<tr>
              <td  style="padding:0px;">
              <table width="890"   border="0" cellspacing="0" cellpadding="0" align="center" style="border-collapse: collapse;">
                  <tr>
                    <td  style="text-align:left; background-color:#f2f2f2; padding:5px 20px 5px 20px; border:1px solid #cacaca; "><b>Ticket Type</b></td>
                    <td style="text-align:left; background-color:#f2f2f2; padding:5px 20px 5px 20px; border:1px solid #cacaca;"><b>Quantity</b></td>';
                
                if($amount_status == "TRUE"){
                 $data.=   '<td style="text-align:left; background-color:#f2f2f2; padding:5px 20px 5px 20px; border:1px solid #cacaca;"><b>Price</b></td>';
                }
				$data.=   ' </tr>';
                    
				  
				  
				  
				  $tktTotalAmount=0;
				  for($t=0;$t<count($estdData);$t++)
				  {
					  $tktTotalAmount+=$estdData[$t]['TicketAmt'];
					  $data.='<tr>
								<td align="left" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">'.$estdData[$t]['Name'];
                                          if($EventId!=$this->smirnoff && $EventId!=73632){
                                          $data.='<br /><span style="font-size:11px">'.$estdData[$t]['Description'].'</span>';
                                          }
                                          $data.='</td><td align="left" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">'.$estdData[$t]['NumOfTickets'].'</td>';
                      			if($amount_status == "TRUE"){
									$data.='<td align="left" valign="middle" style="padding:5px 20px 5px 20px;border:1px solid #cacaca;">'.$res[0]['code']." ".$estdData[$t]['TicketAmt'].'</td>';
                      			}
					  $data.=   ' </tr>';    
				  }
				  
                  if($amount_status == "TRUE"){
                      if(!$isOnlyDonation){
                    $data.='<tr>
                      <td colspan="2" align="right" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">Total Amount</td>
                      <td align="left" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">'.$res[0]['code']." ".$tktTotalAmount.'</td>
                    </tr>';
                      }
				  
					  if($res[0]['STax']>0)
					  {
						  $data.='<tr>
								<td colspan="2" align="right" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">Service Tax</td>
								<td align="left" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">'.$res[0]['code']." ".round($res[0]['STax'],2).'</td>
							  </tr>';
					  }
					  
					  $sqlBulkDiscount="select sum(BulkDiscount) from eventsignupticketdetails where `EventSignupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."'";
					  $totalBulkDiscount=$Globali->GetSingleFieldValue($sqlBulkDiscount);
					  $totalBulkDiscount=($totalBulkDiscount>0)?$totalBulkDiscount:0;
					  
					  
					  if(($res[0]['DAmount'] + $res[0]['referralDAmount'])>0 )
					  {
						  $DAmount=$res[0]['DAmount']+$res[0]['referralDAmount']-$totalBulkDiscount;
						  if($DAmount>0)
						  {
						  	  $data.='<tr>
								<td colspan="2" align="right" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">Discount Amount</td>
								<td align="left" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">'.$res[0]['code']." ".round($DAmount,2).'</td>
							  </tr>';
						  }
					  }
					  
					  if($totalBulkDiscount>0)
					  {
						  $data.='<tr>
								<td colspan="2" align="right" valign="middle"  style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">Bulk Discount</td>
								<td align="left" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;"> '.$res[0]['code']." ".round($totalBulkDiscount,2).'</td>
							  </tr>';
					  }
					  if($res[0]['PaymentGateway']=="CashonDelivery")
					  {
						  $data.='<tr>
								<td colspan="2" align="right" valign="middle"  style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">Courier Charges</td>
								<td align="left" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">'.$res[0]['code'].' 40</td>
							  </tr>';
					  }
					  
					  $toatlPurchaseAmount=round($res[0]['Qty']*$res[0]['Fees']-$res[0]['Ccharge']);
					  if($res[0]['PaymentGateway']=="CashonDelivery"){$toatlPurchaseAmount+=40;}
                                //$totalText="Total Purchase Amount ";
                                //if(in_array($EventId, $this->hideOrgDetails) || $Events->Id==73246){
                                   $totalText='<b>Total Purchase Amount <span style="font-size: 12px; line-height: 12px; padding: 0px; margin: 0px; width: 100%; float: right; text-align: right;">(Incl of All Taxes, excluding convenience fee)</span></b>';
                                //  }else
                                      if($isOnlyDonation){
                                      $totalText="<b>Donation Total</b>";
                                  }
						 $data.='<tr>
						<td colspan="2" align="right" valign="middle"  style=" text-align:right;padding:5px 20px 5px 20px;  border:1px solid #cacaca;">'.$totalText.'</td>
						<td align="left" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;"><b>'.$res[0]['code']." ".round($toatlPurchaseAmount,2).'</b></td>
					  </tr>';
                     
                 }
                $data.=' </table></td>
            </tr>';
			
			
			
			if($cfDatacount>0)
			{
				$data.='<tr><td style="font-size:12px; padding:10px 10px; color:#F00;"> Attendee Information</td></tr>';
				$data.='<tr>
				  <td style="padding:0px 0 0 0;">
				  <table width="880" border="0" cellspacing="0" cellpadding="0" style=" font-size:14px; border-collapse: collapse;" >
					  <tr>
						<td bgcolor="#f2f2f2" valign="middle" style="text-align:left; padding:6px 5px 6px 4px;  border:1px solid #cacaca;"><b>Delegate Name</b></td>
						<td  bgcolor="#f2f2f2" valign="middle" style="text-align:left; padding:6px 5px 6px 4px;  border:1px solid #cacaca;"><b>Ticket Type</b></td>';
						
						
							for($cn=0;$cn<$cfDatacount;$cn++)
							{
								$data.='<td  bgcolor="#f2f2f2" valign="middle" style="text-align:left; padding:6px 5px 6px 4px;  border:1px solid #cacaca;"><b>'.$customData[$cn]['EventCustomFieldName'].'</b></td>';
							}
						$data.='</tr>';
						
						
					  
					  for($a=0;$a<count($attendeeData);$a++)
					  {
						  $data.='<tr>
							<td   align="left" valign="middle" style="padding:6px 5px 6px 4px;  border:1px solid #cacaca;">'.$attendeeData[$a]['Name'].'</td>
							<td   align="left" valign="middle" style="padding:6px 5px 6px 4px;  border:1px solid #cacaca;">'.$attendeeData[$a]['ticketName'].'</td>';
	
							if($cfDatacount>0)
							{
								for($cd=0;$cd<$cfDatacount;$cd++)
								{
									if($cd==0){$styles='padding:6px 5px 6px 4px;  border:1px solid #cacaca;';}else{$styles='padding:6px 5px 6px 4px;  border:1px solid #cacaca;';}
									$data.='<td  align="left" valign="middle" style="'.$styles.'">'.$Globali->GetSingleFieldValue("select `EventSignupFieldValue` from `eventsignupcustomfields` where `EventCustomFieldsId`='".$Globali->dbconn->real_escape_string($customData[$cd]['Id'])."' and `attendeeId`='".$Globali->dbconn->real_escape_string($attendeeData[$a]['attendeeId'])."' and `EventId`='".$Globali->dbconn->real_escape_string($Events->Id)."' and `EventSignupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."'").'</td>';
								}
							}
							$data.='</tr>';
					  }
						
						
						$data.='</table></td>
				</tr>
				<tr><td></td></tr>';
				}
				
			
			if($Events->Id==56832){ $data.='<tr><td  align="center">This is an acknowledgement for your registration. A detailed confirmation mail will be sent from PMI Conference team within 10 days</td></tr>'; }
			$tc=NULL;
                        $uniqueNum=$res[0]['BarcodeNumber'];
                        if(empty($uniqueNum) || $uniqueNum==0){
                            $uniqueNum=  substr($Events->Id, 1,4).$EventSignupId;
                        }
                        if(!in_array($EventId, $this->hideOrgDetails) && $Events->Id!=73246){  
            $data.='
            <tr>
              <td style="padding:10px 20px 10px 20px; border-bottom:1px solid #cacaca;  border-top:1px solid #cacaca; background:#f2f2f2; font:\'Trebuchet MS\', Arial, Helvetica, sans-serif; color:#111;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">';
			  
			  $orgSql="select CONCAT(FirstName, ' ', LastName) 'orgName' from `user` where Id='".$Globali->dbconn->real_escape_string($Events->UserID)."' ";
			  $orgData=$Globali->SelectQuery($orgSql);
                      
                  $data.='<tr>
                    <td align="left" valign="top" >';
                   
                  $data.='<h3 style="font-size:18px; margin:0; padding:0;">Organizer Contact  Details   :   '.$orgData[0]['orgName'].'</h3>
                      <p style="margin:0; padding:5px 0 5px 0; font-size:15px;">';
					  if(strlen($Events->ContactDetails)){ $data.='Phone or Email : '.$Events->ContactDetails; }  
					  if(strlen($Events->WebUrl)){ $data.=' | Website : '.$Events->WebUrl; }
					  $data.='</p>';
                                          
                        $data.='</td></tr>
                </table></td>';
                         
            $data.='</tr>';
             }
                        $data.='
            
            <tr>
              <td bgcolor="#f2f2f2" style="text-align:center;font-size:15px; font-weight:bold; color:#111;padding:8px 0; border-top:1px solid #cacaca; border-bottom:1px solid #cacaca;">You are most welcome to contact us for any query, please call us at +91-9396555888</td>
            </tr>
            <tr>
            	<td style="padding:20px;">
                	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
                      <tr>
                        <td width="50%" style="padding:5px 0 0 0;">
                      
 <img src="'._HTTP_SITE_ROOT.'/barcode/barcode.php?text='.$uniqueNum.'&angle=0" width="35%" height="100" style="padding: 10px 10px;" >                             
</td>
                        <td align="right" width="50%" style="padding:0px; margin:0;"><p style="padding-right:10px; font-size:13px;margin:0;">Powered By</p><br>
                                    <img src="http://content.meraevents.com/images/logo-Eticket.jpg"></td>
                      </tr>
                    </table>
                </td>
            </tr>';
                         
			if(in_array($EventId, $this->hideOrgDetails)){ 
                            $imagePath=_HTTP_SITE_ROOT.'/images/soa-terms.jpg';
                            if($EventId==$this->smirnoff){
                                $imagePath=_HTTP_SITE_ROOT.'/images/smirnoff-terms.jpg';
                            }
            $tc='<tr><td>
                	<table width="100%" border="0" cellspacing="0" cellpadding="0" >
                      <tr>
                        <td> 
                 <img src="'.$imagePath.'" width="70%" style="padding: 10px 0px;" >  
                        </td>
                      </tr>
                        </table>
            </td></tr>';
           }
            else{
			$selTnC="SELECT * FROM eventTC WHERE EventId='".$Events->Id."'";
			$ResTnC=$Globali->SelectQuery($selTnC);
                        
			if(count($ResTnC)>0)
			{
                            $desc=NULL;
                            if(strcmp($ResTnC[0]['showTC'],'organizer')==0){
                                $desc=$ResTnC[0]['Org_Description'];
                            }else if (strcmp($ResTnC[0]['showTC'],'meraevents')==0){
                                $desc=$ResTnC[0]['ME_Description'];
                            }
			    if(!empty($desc)){
                                
                                  $data.='<tr><td>';
                            if($Events->Id!=71772 && $Events->Id!=71769 && $Events->Id!=73246){ 
                            $data.='<h4><b>Terms & Conditions</b></h4>';}
                        
                                
                                 $data.=' '.$desc.'</td></tr>';
                            }
			}
            }	
            
            if(!is_null($tc)){
                $data.=$tc;
            }
          $data.='</table>';
		 //print_r($data);exit;
		  return $data;
   }
   
   
   
   function getPrintPassHtmlNew($EventId,$EventSignupId,$attId=NULL)
	{
		$Globali = new cGlobali();
		
		$Events = @new cEvents($EventId);
        $Success = $Events->Load();
		
		//getting PMI reg no
		if($Events->Id==56832) {
			$sqlField="select `field2` from `EventSignup` where `Id`='".$Globali->dbconn->real_escape_string($EventSignupId)."'";
			$pmi2RegNo=$Globali->GetSingleFieldValue($sqlField);
		}
		
		
    	$TSeats=$Globali->GetSingleFieldValue("SELECT EventId from VenueSeats WHERE EventId='".$Globali->dbconn->real_escape_string($Events->Id)."'");
		if($TSeats>0){
			
			
			$seatQuery= "SELECT GridPosition,Seatno,Type FROM VenueSeats WHERE EventSIgnupId='".$Globali->dbconn->real_escape_string($EventSignupId)."' order by id ";
        $seatRes = $Globali->SelectQuery($seatQuery);
        $SNo="";
        for($s=0;$s<count($seatRes);$s++){
            if($seatRes[$s]['Type']=='GASECTION' || $seatRes[$s]['Type']=='GASECTIONBANGALORE' || $seatRes[$s]['Type']=='GASEATING'  || $seatRes[$s]['Type']=='VIPSEATING')
            {
              //  $rowno=preg_replace("/[0-9]/", "", $rowno);
                $SNo.= preg_replace("/[0-9]/", "", $seatRes[$s]['GridPosition']).$seatRes[$s]['Seatno'].", ";
            }else if($seatRes[$s]['Type']=='FIRSTFLOOR' )
            {
              //  $rowno=preg_replace("/[0-9]/", "", $rowno);
                $SNo.= substr($seatRes[$s]['GridPosition'],0,2).$seatRes[$s]['Seatno'].", ";
            }else
            $SNo.= substr($seatRes[$s]['GridPosition'],0,1).$seatRes[$s]['Seatno'].", ";
        }
			
		
		$SeatsNo=" <b>Seat Nos-</b> ".substr($SNo,0,-2);
		
		} else {
		$SeatsNo="";
		}
		
		
		$StartDt1 =date('F j, Y',strtotime($Events->StartDt));
        $EndDt1 = date('F j, Y',strtotime($Events->EndDt));
		$ddate=$StartDt1;
        if($StartDt1 !=$EndDt1){ $ddate.='-'.$EndDt1; }
		$ddate.=' | Time : '.date('g:i a',strtotime($Events->StartDt)).'-'.date('g:i a',strtotime($Events->EndDt));
		
		
	    $sqlEventSignup="select Qty,Fees,Ccharge,PaymentModeId,PromotionCode,PaymentTransId,PaymentGateway,Ccharge,STax,DAmount,referralDAmount,c.code from EventSignup es INNER JOIN currencies c ON c.Id=es.CurrencyId  where es.Id='".$Globali->dbconn->real_escape_string($EventSignupId)."'";
		$res=$Globali->SelectQuery($sqlEventSignup);
		if($res[0]['Fees']!=0)
		{
			if($res[0]['PaymentModeId']==1)
			{
				if($res[0]['PaymentTransId']=="PayPalPayment")
				{ $paymentmode="PayPal Transaction";  }
				else{ $paymentmode="Card Transaction"; }
			}
			else
			{
				$paymentmode="Cheque Transaction";
			}
			if($res[0]['PromotionCode']=="Y")
			{
				$paymentmode="Pay At Counter";
			}
			elseif($res[0]['PaymentGateway']=="CashonDelivery")
			{
				$paymentmode="Cash On Delivery";
			}
		} else { $paymentmode="Free Registration"; }
		
		
		
		$TicketingOptionsRes=$Globali->SelectQuery("SELECT enable_info FROM ticketingoptions WHERE EventId='".$Globali->dbconn->real_escape_string($Events->Id)."'");
			//print_r($TicketingOptionsRes);
			
			if(count($TicketingOptionsRes)>0)
			{
				if($TicketingOptionsRes[0]['enable_info']==0){$TicketingOption=0;}//collect one
				else{ $TicketingOption=1; }//collect all
			}
			else{ $TicketingOption=0; } //collect one
		
		if($TicketingOption==0){$sqlAddLimit=" limit 1";}else{$sqlAddLimit="";}
		
		
		if(strlen($attId)>0)
		{
			$attTicketId=$Globali->GetSingleFieldValue("select `ticketid` from `Attendees` where Id='".$Globali->dbconn->real_escape_string($attId)."' ");
			$attSqlAdd=" and a.Id='".$Globali->dbconn->real_escape_string($attId)."' ";
			$sqlTktDetailsAdd=" and estd.TicketId='".$Globali->dbconn->real_escape_string($attTicketId)."'";
		}
		else{ $attTicketId=$attSqlAdd=$sqlTktDetailsAdd=NULL;}
		
		
					  
					  
		$atteneeSql="select a.Id 'attendeeId',a.Name, a.Email,t.Name 'ticketName', a.Phone from `Attendees` AS a
									 RIGHT JOIN `tickets` AS t ON a.ticketid=t.Id
									 where a.`EventSIgnupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."'
									    ".$attSqlAdd.$sqlAddLimit;
		$attendeeData = $Globali->SelectQuery($atteneeSql);
					  
		$sqlTktDetails="select estd.TicketId,estd.Discount, estd.BulkDiscount, estd.ReferralDiscount, estd.ServiceTax, t.`Name`, t.Description, estd.`NumOfTickets`,estd.`TicketAmt` 
				  from `eventsignupticketdetails` AS estd
					RIGHT JOIN `tickets` AS t ON estd.TicketId=t.Id
					WHERE estd.`EventSignupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."'".$sqlTktDetailsAdd;
					
		$estdData=$Globali->SelectQuery($sqlTktDetails);
		$ticketIdsArr=array();
		for($et=0;$et<count($estdData);$et++)
		{
			$ticketIdsArr[]=$estdData[$et]['TicketId'];
		}
		
		
		$customSql="select cf.`Id`, cf.`EventCustomFieldName` from `eventcustomfields` AS cf
			LEFT JOIN `event_ticket_customfields` AS etc ON cf.Id=etc.eve_tic_eventcustomfield_id
			where cf.`displayOnTicket`=1 and cf.EventCustomFieldSeqNo > 0
			and  (cf.`EventId`='".$Globali->dbconn->real_escape_string($Events->Id)."' or etc.`eve_tic_ticket_id` in (".implode(",",$ticketIdsArr)."))";
			
			$customData = $Globali->SelectQuery($customSql);
			$cfDatacount=count($customData);
		
		?>
    
    
    
    <div class="printtable"  id="PrintInnerPass" style="margin-top:20px; margin-left:22px;font-family: 'maven_proregular';">
    <?php //echo $commonFunctions->getPrintPass($Events->Id,$EventSignupId,$Globali); ?>
    
        <div id="PrintTicketsNew">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="left" valign="top" width="50%"><h3><?php echo stripslashes($Events->Title);?></h3>
                      <p class="Print-Timings">Date :  <?php echo $ddate  ?></p>
                      <p class="Print-Timings">Venue : <?php echo nl2br($Events->Venue); ?></p></td>
                    <td calss="Print-Registration" align="right" valign="top" width="50%"><h4>Event ID : <?php echo $Events->Id; ?><br>
                        Registration No : <?php echo $EventSignupId;?><br>
                        <?php if($Events->Id==56832){ ?> PMI Conference Registration No: <?php echo $pmi2RegNo."<br>"; } ?>
                        Payment Mode : <?php echo $paymentmode; ?>
                        <?php if(strlen(trim($SeatsNo))>0){
							echo '<br>'.$SeatsNo;
							}
							
						if(($TicketingOption==0 & $cfDatacount==0) || $cfDatacount==0){
							echo "<br>Name : ".$attendeeData[0]['Name'];
						}
						?>
                            
                            </h4> </td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td class="Print-TicketTable"><table width="100%" border="0" cellspacing="0" cellpadding="0" pa >
                  <tr>
                    <th width="50%">Ticket Type</th>
                    <th width="25%">Quantity</th>
                    <th width="25%">Price</th>
                  </tr>
                  <?php
				 
				  
				  $tktTotalAmount=0;				  
				  for($t=0;$t<count($estdData);$t++)
				  {
					  //$ticketIdsArr[]=$estdData[$t]['TicketId'];
					  
					  
					  
					  if(strlen($attId)>0)
					  {
						  $tktTotalAmount+=($estdData[$t]['TicketAmt']/$estdData[$t]['NumOfTickets']);
						  $currentTicketAmt=$res[0]['code']." ".($estdData[$t]['TicketAmt']/$estdData[$t]['NumOfTickets']);
					  }else
					  {
						  $tktTotalAmount+=$estdData[$t]['TicketAmt'];
						  $currentTicketAmt=$res[0]['code']." ".$estdData[$t]['TicketAmt'];
					  }
					  ?>
                      <tr>
                        <td align="left" valign="top" style="padding-left:20px;"><?php echo $estdData[$t]['Name']; ?><br /><span style="font-size:11px"><?php echo $estdData[$t]['Description']; ?></span></td>
                        <td align="left" valign="top" style="padding-left:20px;"><?php if(strlen($attId)>0){ echo 1; }else{ echo $estdData[$t]['NumOfTickets']; } ?></td>
                        <td align="left" valign="top" style="padding-left:20px;"><?php echo $currentTicketAmt; ?></td>
                      </tr>
                      <?php
				  }
				  ?>
                  <tr>
                    <td align="left" valign="top" style="padding-left:20px; border-right:0;">&nbsp;</td>
                    <td align="right" valign="top" style="padding-right:20px; text-align:right;">Total Amount</td>
                    <td align="left" valign="top" style="padding-left:20px;"><?php echo $res[0]['code']." ".$tktTotalAmount; ?></td>
                  </tr>
                 
                  <?php
				  if($res[0]['STax']>0)
				  {
					  if(strlen($attId)>0){$currentST=$res[0]['code']." ".round($estdData[0]['ServiceTax'],2);}else{$currentST=$res[0]['code']." ".round($res[0]['STax'],2);}
					  ?>
                      <tr>
                        <td align="left" valign="top" style="padding-left:20px; border-right:0;">&nbsp;</td>
                        <td align="right" valign="top" style="padding-right:20px; text-align:right;">Service Tax</td>
                        <td align="left" valign="top" style="padding-left:20px;"><?php echo $currentST; ?></td>
                      </tr>
                      <?php
				  }
				  
				  $sqlBulkDiscount="select sum(BulkDiscount) from eventsignupticketdetails where `EventSignupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."'";
				  $totalBulkDiscount=$Globali->GetSingleFieldValue($sqlBulkDiscount);
				  $totalBulkDiscount=($totalBulkDiscount>0)?$totalBulkDiscount:0;
				  
				  if(($res[0]['DAmount'] + $res[0]['referralDAmount'])>0)
				  {
					  if(strlen($attId)>0){$DAmount=$res[0]['code']." ".round(($estdData[0]['Discount']/$estdData[0]['NumOfTickets']),2);}
					  else{$DAmount=$res[0]['code']." ".round($res[0]['DAmount']+$res[0]['referralDAmount']-$totalBulkDiscount,2);}
					   
					  //$DAmount=$res[0]['DAmount']+$res[0]['referralDAmount']-$totalBulkDiscount;
					  if($DAmount>0)
				  	  {
					  ?>
                      <tr>
                        <td align="left" valign="top" style="padding-left:20px; border-right:0;">&nbsp;</td>
                        <td align="right" valign="top" style="padding-right:20px; text-align:right;">Discount Amount</td>
                        <td align="left" valign="top" style="padding-left:20px;"><?php echo $DAmount; ?></td>
                      </tr>
                       <?php
					  }
				  }				  
				  
				  if($totalBulkDiscount>0)
				  {
					  if(strlen($attId)>0){ $currentBulkDiscount=$res[0]['code']." ".round(($estdData[0]['BulkDiscount']/$estdData[0]['NumOfTickets']),2);}
					  else{$currentBulkDiscount=$res[0]['code']." ".round($totalBulkDiscount,2); }
					  ?>
                      <tr>
                        <td align="left" valign="top" style="padding-left:20px; border-right:0;">&nbsp;</td>
                        <td align="right" valign="top" style="padding-right:20px; text-align:right;">Bulk Discount</td>
                        <td align="left" valign="top" style="padding-left:20px;"><?php echo $currentBulkDiscount; ?></td>
                      </tr>
                      <?php
				  }
				  if($res[0]['PaymentGateway']=="CashonDelivery") {?>
                      <tr>
                        <td align="left" valign="top" style="padding-left:20px; border-right:0;">&nbsp;</td>
                        <td align="right" valign="top" style="padding-right:20px; text-align:right;">Courier Charges</td>
                        <td align="left" valign="top" style="padding-left:20px;">INR 40</td>
                      </tr>
                      <?php
				  }
				  
				  if(strlen($attId)>0){$toatlPurchaseAmount=$res[0]['code']." ".round(($estdData[0]['TicketAmt']/$estdData[0]['NumOfTickets'])-($estdData[0]['Discount']/$estdData[0]['NumOfTickets'])-($estdData[0]['BulkDiscount']/$estdData[0]['NumOfTickets'])-($estdData[0]['ReferralDiscount']/$estdData[0]['NumOfTickets'])+($estdData[0]['ServiceTax']/$estdData[0]['NumOfTickets']));}
				  else{$toatlPurchaseAmount=$res[0]['code']." ".round($res[0]['Qty']*$res[0]['Fees']-$res[0]['Ccharge']);}
				  
				  if($res[0]['PaymentGateway']=="CashonDelivery") {$toatlPurchaseAmount+=40;}
				  ?>
                  
                  <tr>
                    <td align="left" valign="top" style="padding-left:20px; border-right:0;">&nbsp;</td>
                    <td align="right" valign="top" style="padding-right:20px; text-align:right;"><b>Total Purchase Amount</b></td>
                    <td align="left" valign="top" style="padding-left:20px;"><b><?php echo $toatlPurchaseAmount;?></b></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td></td>
            </tr>
            
            
            
            
           
          	<?php
			
			
			if($cfDatacount>0)
			{
				?>
                <tr><td><b style="font-size: 14px;">Attendee Information</b></td></tr>
				<tr>
				  <td class="Print-DelegatesInfo"><table width="970" border="0" cellspacing="0" cellpadding="0" >
					  <tr>
						<th>Customer Name</th>
						<!--<th>Mobile</th>
						<th>Email</th>-->
						<th>Ticket Type</th>
						<?php
						for($cn=0;$cn<$cfDatacount;$cn++)
						{
							?> <th><?php echo $customData[$cn]['EventCustomFieldName']; ?></th> <?php
						}
						?>
					  </tr>
					  
					  <?php
					  for($a=0;$a<count($attendeeData);$a++)
					  {
						  ?>
						  <tr>
							<td><?php echo $attendeeData[$a]['Name']; ?></td>
						   <!-- <td><?php //echo $attendeeData[$a]['Phone']; ?></td>
							<td><?php //echo $attendeeData[$a]['Email']; ?></td>-->
							<td><?php echo $attendeeData[$a]['ticketName']; ?></td>
							<?php
							if($cfDatacount>0)
							{
								for($cd=0;$cd<$cfDatacount;$cd++)
								{
									?><td><?php echo $Globali->GetSingleFieldValue("select `EventSignupFieldValue` from `eventsignupcustomfields` where `EventCustomFieldsId`='".$Globali->dbconn->real_escape_string($customData[$cd]['Id'])."' and `attendeeId`='".$Globali->dbconn->real_escape_string($attendeeData[$a]['attendeeId'])."' and `EventId`='".$Globali->dbconn->real_escape_string($Events->Id)."' and `EventSignupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."'");; ?></td><?php
								}
							}
							?>
						  </tr>
						  <?php
					  }
					  ?>
					  
					 
					</table></td>
				</tr>
				
				<?php
			}
			?>
            
            <tr><td>&nbsp;</td></tr>
           


            
            <tr>
              <td class="Print-OrganizerInfo"><table width="100%" border="0" cellspacing="0" cellpadding="0" >
                  <tr>
                  	<?php
					$orgSql="select CONCAT(FirstName, ' ', LastName) 'orgName' from `user` where Id='".$Globali->dbconn->real_escape_string($Events->UserID)."' ";
					$orgData=$Globali->SelectQuery($orgSql);
					?>
                    <td align="left" valign="top" ><h3>Organizer Contact  Details   :   <?php echo $orgData[0]['orgName']; ?></h3>
                      <p><?php if(strlen($Events->ContactDetails)){ echo 'Phone or Email : '.$Events->ContactDetails; }  if(strlen($Events->WebUrl)){ echo ' | Website : '.$Events->WebUrl; } ?></p></td>
                  </tr>
                </table></td>
            </tr>
            
            <?php if($Events->Id==56832){ ?><tr><td  class="Print-Text" align="center">This is an acknowledgement for your registration. A detailed confirmation mail will be sent from PMI Conference team within 10 days</td></tr> <?php } ?>
            
            <?php
			$selTnC="SELECT * FROM eventTC WHERE EventId='".$Globali->dbconn->real_escape_string($EventId)."'";
			$ResTnC=$Globali->SelectQuery($selTnC);
			if(count($ResTnC)>0)
			{
                            $desc=NULL;
                            if(strcmp($ResTnC[0]['showTC'],'organizer')==0){
                                $desc=$ResTnC[0]['Org_Description'];
                            }else if (strcmp($ResTnC[0]['showTC'],'meraevents')==0){
                                $desc=$ResTnC[0]['ME_Description'];
                            }
			    if(!empty($desc)){	?>
                <tr>
                  <td class="Print-Terms">
                    <h4><b>Terms & Conditions</b></h4>
                    <?php echo $desc; ?>
                  </td>
                </tr>
                <?php }
			}
			?>
            <tr>
              <td class="Print-Text">You are most welcome to contact us for any query, please call us at +91-9396555888</td>
            </tr>
            <tr>
            	<td>
                	<table width="100%" border="0" cellspacing="0" cellpadding="0" >
                      <tr>
                        <td width="50%"> 
<!--                            <img src="http://barcode.tec-it.com/barcode.ashx?code=Code128&modulewidth=fit&data=<?php echo $EventSignupId.$Events->Id.$Events->UserID;?>&dpi=96&imagetype=gif&rotation=360&color=&bgcolor=&fontcolor=&quiet=0&qunit=mm" height="90" width="60%" />-->
                        <img src="<?php echo _HTTP_SITE_ROOT;?>/barcode/barcode.php?code=Code128&text=<?php echo $EventSignupId.$Events->Id.$Events->UserID;?>&angle=0" width="60%" height="90" style="padding: 10px 0px;" >
                        </td>
                        <td align="right" width="50%" style="padding-right:20px;"><p style="padding-right:10px;">Powered By</p>
                                    <img src="http://content.meraevents.com/images/logo-Eticket.jpg"></td>
                      </tr>
                    </table>

                </td>
            </tr>
          </table>
        </div>
      </div>
    
    

  <!--End of TwelveCol Print View-->
    <div style="clear:both">&nbsp;</div>
    <?php if(!$ismobile)
    {
    ?>
      <div  style="margin:0px auto 0 45.5%; width:100px">
    <input style="width:114%;" title="Print" name="button" class="processbutton" value="Print" type="button" onClick="ClickHereToPrintpass('PrintInnerPass')">
     </div> 
    
    &nbsp;
    <div id="sendPassDiv">
    <label id="sendPassToEmailTxt" for="sendPassToEmail" style="margin-left:10.85%;">Send Pass to email:</label>
        <input style="width:227px;" type="text" class="wizardtext" placeholder="Email id" name="sendPassToEmail" id="sendPassToEmail"  value="" /> 
        <input style="width: 10%;" id="EMailButton" title="Send Email" name="button" class="processbutton" value="Send Email" type="submit" onclick="ClickHereToEmailpass();"><span id="sendEmailLoading"></span>
    </div>
    
    <?php } ?>
 
   <?php 
   }
   
   
   function getOrgPrintPassPDF($EventId,$EventSignupId, $amount_status,$attId=NULL)
   {
	   $Globali = new cGlobali();
	   
	   
	   $Events = @new cEvents($EventId);
       $Success = $Events->Load();
	   
	   //getting PMI reg no
		if($Events->Id==56832) {
			$sqlField="select `field2` from `EventSignup` where `Id`='".$Globali->dbconn->real_escape_string($EventSignupId)."'";
			$pmi2RegNo=$Globali->GetSingleFieldValue($sqlField);
		}
		
		
	   
	   $TSeats=$Globali->GetSingleFieldValue("SELECT EventId from VenueSeats WHERE EventId='".$Globali->dbconn->real_escape_string($Events->Id)."'");
		if($TSeats>0){
			
			
			$seatQuery= "SELECT GridPosition,Seatno,Type FROM VenueSeats WHERE EventSIgnupId='".$Globali->dbconn->real_escape_string($EventSignupId)."' order by id ";
        $seatRes = $Globali->SelectQuery($seatQuery);
        $SNo="";
        for($s=0;$s<count($seatRes);$s++){
            if($seatRes[$s]['Type']=='GASECTION' || $seatRes[$s]['Type']=='GASECTIONBANGALORE' || $seatRes[$s]['Type']=='GASEATING'  || $seatRes[$s]['Type']=='VIPSEATING')
            {
              //  $rowno=preg_replace("/[0-9]/", "", $rowno);
                $SNo.= preg_replace("/[0-9]/", "", $seatRes[$s]['GridPosition']).$seatRes[$s]['Seatno'].", ";
            }else if($seatRes[$s]['Type']=='FIRSTFLOOR' )
            {
              //  $rowno=preg_replace("/[0-9]/", "", $rowno);
                $SNo.= substr($seatRes[$s]['GridPosition'],0,2).$seatRes[$s]['Seatno'].", ";
            }else
            $SNo.= substr($seatRes[$s]['GridPosition'],0,1).$seatRes[$s]['Seatno'].", ";
        }
			
		
		$SeatsNo=" <b>Seat Nos-</b> ".substr($SNo,0,-2);
		
		} else {
		$SeatsNo="";
		}
		
		
		$StartDt1 =date('F j, Y',strtotime($Events->StartDt));
        $EndDt1 = date('F j, Y',strtotime($Events->EndDt));
		$ddate=$StartDt1;
        if($StartDt1 !=$EndDt1){ $ddate.='-'.$EndDt1; }
		$ddate.=' | Time : '.date('g:i a',strtotime($Events->StartDt)).'-'.date('g:i a',strtotime($Events->EndDt));
		
		
	  $sqlEventSignup="select Qty,Fees,Ccharge,PaymentModeId,PromotionCode,PaymentTransId,PaymentGateway,Ccharge,STax,DAmount,referralDAmount,CurrencyId,c.code from EventSignup es INNER JOIN currencies c ON c.Id=es.CurrencyId where es.Id='".$Globali->dbconn->real_escape_string($EventSignupId)."'";
		
		$res=$Globali->SelectQuery($sqlEventSignup);
		if($res[0]['Fees']!=0)
		{
			if($res[0]['PaymentModeId']==1)
			{
				if($res[0]['PaymentTransId']=="PayPalPayment")
				{ $paymentmode="PayPal Transaction";  }
				else{ $paymentmode="Card Transaction"; }
			}
			else
			{
				$paymentmode="Cheque Transaction";
			}
			if($res[0]['PromotionCode']=="Y")
			{
				$paymentmode="Pay At Counter";
			}
			elseif($res[0]['PaymentGateway']=="CashonDelivery")
			{
				$paymentmode="Cash On Delivery";
			}
		} else { $paymentmode="Free Registration"; }
		
		
		
		
		
		$TicketingOptionsRes=$Globali->SelectQuery("SELECT enable_info FROM ticketingoptions WHERE EventId='".$Globali->dbconn->real_escape_string($Events->Id)."'");
			//print_r($TicketingOptionsRes);
			
			if(count($TicketingOptionsRes)>0)
			{
				if($TicketingOptionsRes[0]['enable_info']==0){$TicketingOption=0;}//collect one
				else{ $TicketingOption=1; }//collect all
			}
			else{ $TicketingOption=0; } //collect one
			
		
		
		if($TicketingOption==0){$sqlAddLimit=" limit 1";}else{$sqlAddLimit="";}
		
		
		if(strlen($attId)>0)
		{
			$attTicketId=$Globali->GetSingleFieldValue("select `ticketid` from `Attendees` where Id='".$Globali->dbconn->real_escape_string($attId)."' ");
			$attSqlAdd=" and a.Id='".$Globali->dbconn->real_escape_string($attId)."' ";
			$sqlTktDetailsAdd=" and estd.TicketId='".$Globali->dbconn->real_escape_string($attTicketId)."'";
		}
		else{ $attTicketId=$attSqlAdd=$sqlTktDetailsAdd=NULL;}
		
		
		
						
		$atteneeSql="select a.Id 'attendeeId', a.Name, a.Email,t.Name 'ticketName', a.Phone from `Attendees` AS a
				 RIGHT JOIN `tickets` AS t ON a.ticketid=t.Id
				 where a.`EventSIgnupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."'  
				  ".$attSqlAdd.$sqlAddLimit;
				 
		$attendeeData = $Globali->SelectQuery($atteneeSql);
		
		
		
		$sqlTktDetails="select estd.TicketId,estd.Discount, estd.BulkDiscount, estd.ReferralDiscount, estd.ServiceTax, t.`Name`, t.Description, estd.`NumOfTickets`,estd.`TicketAmt`
				  from `eventsignupticketdetails` AS estd
					RIGHT JOIN `tickets` AS t ON estd.TicketId=t.Id
					WHERE estd.`EventSignupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."' ".$sqlTktDetailsAdd;
					
		$estdData=$Globali->SelectQuery($sqlTktDetails);
		
		$ticketIdsArr=array();
		for($et=0;$et<count($estdData);$et++)
		{
			$ticketIdsArr[]=$estdData[$et]['TicketId'];
		}
		
		$customSql="select cf.`Id`, cf.`EventCustomFieldName` from `eventcustomfields` AS cf
			LEFT JOIN `event_ticket_customfields` AS etc ON cf.Id=etc.eve_tic_eventcustomfield_id
			where cf.`displayOnTicket`=1 and cf.EventCustomFieldSeqNo > 0
			and (cf.`EventId`='".$Globali->dbconn->real_escape_string($Events->Id)."' or etc.`eve_tic_ticket_id` in (".implode(",",$ticketIdsArr)."))";
			
			
		$customData = $Globali->SelectQuery($customSql);
		$cfDatacount=count($customData);
	   
	   
	   $data='<table width="900" border="0" cellspacing="0" cellpadding="0" align="center" style="border:1px solid #ccc;font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; margin-top:20px;">
            <tr>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="border-collapse: collapse;">
                  <tr>
                    <td align="left" valign="top" width="50%" style="padding:15px 20px;"><h3 style="margin:0; padding:0 0 5px 0; font-size:18px;">'.stripslashes($Events->Title).'</h3>
                      <p style="padding:2px 0 0 0; margin:0; font-size:14px;">Date : '.$ddate.'</p>
                    <p style="padding:2px 0 0 0; margin:0; font-size:14px;">Venue : '.nl2br($Events->Venue).'</p></td>
                    <td align="right" valign="top" width="50%" style="padding:15px 20px;"><h4 style="padding:0; font-size:16px; line-height:22px; margin:0; font-weight:normal;">Event ID : '.$Events->Id.'<br>
                        Registration No : '.$EventSignupId.'<br>';
						
						
						if($Events->Id==56832){ $data.='PMI Conference Registration No: '.$pmi2RegNo.'<br>'; } 
						
						
                        $data.='Payment Mode : '.$paymentmode; 
						if(strlen(trim($SeatsNo))>0){ $data.='<br>'.$SeatsNo; }
						if(($TicketingOption==0 & $cfDatacount==0) || $cfDatacount==0){ $data.='<br>Name : '.$attendeeData[0]['Name']; }
				
				
				$data.='</h4></td>
                  </tr>
                </table></td>
            </tr>
			<tr>
              <td  style="padding:0px;">
              <table width="890"   border="0" cellspacing="0" cellpadding="0" align="center" style="border-collapse: collapse;">
                  <tr>
                    <td  style="text-align:left; background-color:#f2f2f2; padding:5px 20px 5px 20px; border:1px solid #cacaca; "><b>Ticket Type</b></td>
                    <td style="text-align:left; background-color:#f2f2f2; padding:5px 20px 5px 20px; border:1px solid #cacaca;"><b>Quantity</b></td>';
                
                if($amount_status == "TRUE"){
                 $data.=   '<td style="text-align:left; background-color:#f2f2f2; padding:5px 20px 5px 20px; border:1px solid #cacaca;"><b>Price</b></td>';
                }
				$data.=   ' </tr>';
                    
				//$data.='<tr><td colspan="3">'.print_r($estdData).'</td></tr>';
				  
				  
				  
				  $tktTotalAmount=0;
				  for($t=0;$t<count($estdData);$t++)
				  {
					  //$tktTotalAmount+=$estdData[$t]['TicketAmt'];
					  
					  if(strlen($attId)>0)
					  {
						  $tktTotalAmount+=($estdData[$t]['TicketAmt']/$estdData[$t]['NumOfTickets']);
						  $currentTicketAmt=$res[0]['code']." ".($estdData[$t]['TicketAmt']/$estdData[$t]['NumOfTickets']);
					  }else
					  {
						  $tktTotalAmount+=$estdData[$t]['TicketAmt'];
						  $currentTicketAmt=$res[0]['code']." ".$estdData[$t]['TicketAmt'];
					  }
					  
					  $currentNumOfTickets=(strlen($attId)>0)?1:$estdData[$t]['NumOfTickets'];
					  
					  
					  $data.='<tr>
								<td align="left" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">'.$estdData[$t]['Name'].'<br /><span style="font-size:11px">'.$estdData[$t]['Description'].'</span></td>
								<td align="left" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">'.$currentNumOfTickets.'</td>';
                      			if($amount_status == "TRUE"){
									$data.='<td align="left" valign="middle" style="padding:5px 20px 5px 20px;border:1px solid #cacaca;">'.$currentTicketAmt.'</td>';
									
                      			}
					  $data.=   ' </tr>';    
				  }
				  
                  if($amount_status == "TRUE"){
                    $data.='<tr>
                      <td colspan="2" align="right" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">Total Amount</td>
                      <td align="left" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">'.$res[0]['code']." ".$tktTotalAmount.'</td>
                    </tr>';
				  
					  if($res[0]['STax']>0)
					  {
						  if(strlen($attId)>0){$currentST=$res[0]['code']." ".round(($estdData[0]['ServiceTax']/$estdData[0]['NumOfTickets']),2);}else{$currentST=$res[0]['code']." ".round($res[0]['STax'],2);}
						  $data.='<tr>
								<td colspan="2" align="right" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">Service Tax</td>
								<td align="left" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">'.$currentST.'</td>
							  </tr>';
					  }
					  
					  $sqlBulkDiscount="select sum(BulkDiscount) from eventsignupticketdetails where `EventSignupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."'";
					  $totalBulkDiscount=$Globali->GetSingleFieldValue($sqlBulkDiscount);
					  $totalBulkDiscount=($totalBulkDiscount>0)?$totalBulkDiscount:0;
					  
					  
					  if(($res[0]['DAmount'] + $res[0]['referralDAmount'])>0 )
					  {
						  //$DAmount=$res[0]['DAmount']+$res[0]['referralDAmount']-$totalBulkDiscount;
						  if(strlen($attId)>0){$DAmount=round(($estdData[0]['Discount']/$estdData[0]['NumOfTickets']),2);}
					  	  else{$DAmount=round($res[0]['DAmount']+$res[0]['referralDAmount']-$totalBulkDiscount,2);}
						  
						  if($DAmount>0)
						  {
						  	  $data.='<tr>
								<td colspan="2" align="right" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">Discount Amount</td>
								<td align="left" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">'.$res[0]['code']." ".$DAmount.'</td>
							  </tr>';
						  }
					  }
					  
					  if($totalBulkDiscount>0)
					  {
						  if(strlen($attId)>0){ $currentBulkDiscount=$res[0]['code']." ".round(($estdData[0]['BulkDiscount']/$estdData[0]['NumOfTickets']),2);}
					      else{$currentBulkDiscount=$res[0]['code']." ".round($totalBulkDiscount,2); }
					  
						  $data.='<tr>
								<td colspan="2" align="right" valign="middle"  style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">Bulk Discount</td>
								<td align="left" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;"> '.$currentBulkDiscount.'</td>
							  </tr>';
					  }
					  if($res[0]['PaymentGateway']=="CashonDelivery")
					  {
						  $data.='<tr>
								<td colspan="2" align="right" valign="middle"  style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">Courier Charges</td>
								<td align="left" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;">'.$res[0]['code'].' 40</td>
							  </tr>';
					  }
					  
					  //$toatlPurchaseAmount=round($res[0]['Qty']*$res[0]['Fees']-$res[0]['Ccharge']);
					  if(strlen($attId)>0){$toatlPurchaseAmount=$res[0]['code']." ".round(($estdData[0]['TicketAmt']/$estdData[0]['NumOfTickets'])-($estdData[0]['Discount']/$estdData[0]['NumOfTickets'])-($estdData[0]['BulkDiscount']/$estdData[0]['NumOfTickets'])-($estdData[0]['ReferralDiscount']/$estdData[0]['NumOfTickets'])+($estdData[0]['ServiceTax']/$estdData[0]['NumOfTickets']));}
				      else{$toatlPurchaseAmount=$res[0]['code']." ".round($res[0]['Qty']*$res[0]['Fees']-$res[0]['Ccharge']);}
				  
					  if($res[0]['PaymentGateway']=="CashonDelivery"){$toatlPurchaseAmount+=40;}
					  
						 $data.='<tr>
						<td colspan="2" align="right" valign="middle"  style=" text-align:right;padding:5px 20px 5px 20px;  border:1px solid #cacaca;"><b>Total Purchase Amount</b></td>
						<td align="left" valign="middle" style="padding:5px 20px 5px 20px; border:1px solid #cacaca;"><b>'.$toatlPurchaseAmount.'</b></td>
					  </tr>';
                     
                 }
                $data.=' </table></td>
            </tr>';
			
			
			
			if($cfDatacount>0)
			{
				$data.='<tr><td style="font-size:12px; padding:10px 10px; color:#F00;"> Attendee Information</td></tr>';
				$data.='<tr>
				  <td style="padding:0px 0 0 0;">
				  <table width="880" border="0" cellspacing="0" cellpadding="0" style=" font-size:14px; border-collapse: collapse;" >
					  <tr>
						<td bgcolor="#f2f2f2" valign="middle" style="text-align:left; padding:6px 5px 6px 4px;  border:1px solid #cacaca;"><b>Customer Name</b></td>
						<td  bgcolor="#f2f2f2" valign="middle" style="text-align:left; padding:6px 5px 6px 4px;  border:1px solid #cacaca;"><b>Ticket Type</b></td>';
						
						
							for($cn=0;$cn<$cfDatacount;$cn++)
							{
								$data.='<td  bgcolor="#f2f2f2" valign="middle" style="text-align:left; padding:6px 5px 6px 4px;  border:1px solid #cacaca;"><b>'.$customData[$cn]['EventCustomFieldName'].'</b></td>';
							}
						$data.='</tr>';
						
						
					  
					  for($a=0;$a<count($attendeeData);$a++)
					  {
						  $data.='<tr>
							<td   align="left" valign="middle" style="padding:6px 5px 6px 4px;  border:1px solid #cacaca;">'.$attendeeData[$a]['Name'].'</td>
							<td   align="left" valign="middle" style="padding:6px 5px 6px 4px;  border:1px solid #cacaca;">'.$attendeeData[$a]['ticketName'].'</td>';
	
							if($cfDatacount>0)
							{
								for($cd=0;$cd<$cfDatacount;$cd++)
								{
									if($cd==0){$styles='padding:6px 5px 6px 4px;  border:1px solid #cacaca;';}else{$styles='padding:6px 5px 6px 4px;  border:1px solid #cacaca;';}
									$data.='<td  align="left" valign="middle" style="'.$styles.'">'.$Globali->GetSingleFieldValue("select `EventSignupFieldValue` from `eventsignupcustomfields` where `EventCustomFieldsId`='".$Globali->dbconn->real_escape_string($customData[$cd]['Id'])."' and `attendeeId`='".$Globali->dbconn->real_escape_string($attendeeData[$a]['attendeeId'])."' and `EventId`='".$Globali->dbconn->real_escape_string($Events->Id)."' and `EventSignupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."'").'</td>';
								}
							}
							$data.='</tr>';
					  }
						
						
						$data.='</table></td>
				</tr>
				<tr><td></td></tr>';
				}
				
			
			if($Events->Id==56832){ $data.='<tr><td  align="center">This is an acknowledgement for your registration. A detailed confirmation mail will be sent from PMI Conference team within 10 days</td></tr>'; }
			
			
			$selTnC="SELECT * FROM eventTC WHERE EventId='".$Events->Id."'";
			$ResTnC=$Globali->SelectQuery($selTnC);
                        
			if(count($ResTnC)>0)
			{
                            $desc=NULL;
                            if(strcmp($ResTnC[0]['showTC'],'organizer')==0){
                                $desc=$ResTnC[0]['Org_Description'];
                            }else if (strcmp($ResTnC[0]['showTC'],'meraevents')==0){
                                $desc=$ResTnC[0]['ME_Description'];
                            }
			    if(!empty($desc)){
                                $data.='<tr><td><h4><b>Terms & Conditions</b></h4><div style="font-size:10px;">'.$desc.'</div></td></tr>';
                            }
			}
			
			
            $data.='<tr><td style="height:10px;">&nbsp;</td></tr>
            <tr>
              <td style="padding:10px 20px 10px 20px; border-bottom:1px solid #cacaca;  border-top:1px solid #cacaca; background:#f2f2f2; font:\'Trebuchet MS\', Arial, Helvetica, sans-serif; color:#111;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">';
			  
			  $orgSql="select CONCAT(FirstName, ' ', LastName) 'orgName' from `user` where Id='".$Globali->dbconn->real_escape_string($Events->UserID)."' ";
			  $orgData=$Globali->SelectQuery($orgSql);
                  $data.='<tr>
                    <td align="left" valign="top" ><h3 style="font-size:18px; margin:0; padding:0;">Organizer Contact  Details   :   '.$orgData[0]['orgName'].'</h3>
                      <p style="margin:0; padding:5px 0 5px 0; font-size:15px;">';
					  if(strlen($Events->ContactDetails)){ $data.='Phone or Email : '.$Events->ContactDetails; }  
					  if(strlen($Events->WebUrl)){ $data.=' | Website : '.$Events->WebUrl; }
					  $data.='</p></td></tr>
                </table></td>
            </tr>
            
            <tr>
              <td bgcolor="#f2f2f2" style="text-align:center;font-size:15px; font-weight:bold; color:#111;padding:8px 0; border-top:1px solid #cacaca; border-bottom:1px solid #cacaca;">You are most welcome to contact us for any query, please call us at +91-9396555888</td>
            </tr>
            <tr>
            	<td style="padding:20px;">
                	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
                      <tr>
                        <td width="50%" style="padding:10px 0 0 0;"> 
                        
                             <img src="'._HTTP_SITE_ROOT.'/barcode/barcode.php?text='.$EventSignupId.$Events->Id.$Events->UserID.'&angle=0" width="60%" height="90" style="padding: 10px 0px;" > 
                            
</td>
                        <td align="right" width="50%" style="padding:0px; margin:0;"><p style="padding-right:10px; font-size:13px;margin:0;">Powered By</p><br>
                                    <img src="http://content.meraevents.com/images/logo-Eticket.jpg"></td>
                      </tr>
                    </table>
                </td>
            </tr>
          </table>';
		  
		  return $data;
   }
     //For smart city free print pass
  function smart_city_email($EventId,$EventSignupId){
    
        $Globali = new cGlobali();
        $Events = @new cEvents($EventId);
        $sqlEventSignup = "select Name,CurrencyId,SignupDt,c.code,es.BarcodeNumber,Address from EventSignup es "
            . "INNER JOIN currencies c ON c.Id=es.CurrencyId "
            
            . "where es.Id='" . $Globali->dbconn->real_escape_string($EventSignupId) . "'";
        $res = $Globali->SelectQuery($sqlEventSignup);
        $uniqueNum = $res[0]['BarcodeNumber'];
        if (empty($uniqueNum) || $uniqueNum == 0) {
            $uniqueNum=  substr($Events->Id, 1,4).$EventSignupId;
        }
       
        
        //bring the event custom fields ids, names
        $event_custom_fields = "SELECT * 
                                FROM  eventcustomfields 
                                WHERE  EventId =".$EventId;
        $event_custom_fields_result = $Globali->SelectQuery($event_custom_fields);
        $custom_fields_details_array=array('Full Name','Address','State','City','Company Name','BadgeType');
        $custom_field_name_ids=array();
        foreach($event_custom_fields_result as $details){
            if(in_array($details['EventCustomFieldName'], $custom_fields_details_array)){
                 
                $custom_field_name_ids[$details['Id']]=$details['EventCustomFieldName'];
            }
        }
       
        //Attach user values to custom fields id's
        $event_custom_field_values="SELECT * 
                                FROM  eventsignupcustomfields 
                                WHERE  EventId =".$EventId." and EventSignupId=".$EventSignupId;
        $event_custom_field_values_result = $Globali->SelectQuery($event_custom_field_values);
//            EventCustomFieldsId
        foreach($event_custom_field_values_result as $details){
              
            $user_details_array[$custom_field_name_ids[$details['EventCustomFieldsId']]]=$details['EventSignupFieldValue'];
             
        }
        
        
        $barcode_link=_HTTP_SITE_ROOT.'/barcode/barcode.php?text='.$uniqueNum.'&angle=0';
        $image_url=_HTTP_SITE_ROOT."/images/SCI-2015-Visitor-Badge_02.jpg";
        $mid_image=_HTTP_SITE_ROOT."/images/SCI-2015-Visitor-Badge_03.jpg";
        $image_url2=_HTTP_SITE_ROOT."/images/top-new-1.jpg";
        $text_image=_HTTP_SITE_ROOT."/images/top-font-img.jpg";
        $image_url4=_HTTP_SITE_ROOT."/images/top-new-2.jpg";
        
        $image_url3=_HTTP_SITE_ROOT."/images/right-1.jpg";
//        $user_details=date,
   
        $user_details=$user_details_array["Full Name"]."<br/><br/>".
                      $user_details_array["Company Name"]."<br/>".
                      $user_details_array["Address"]."<br/>".
                      $user_details_array["City"].",".$user_details_array["State"]."<br/> India";
        $badge_type=(isset($user_details_array["BadgeType"]))?$user_details_array["BadgeType"]:"VISITOR BADGE";
        
        $bottom_user_details="<span style='font-size: 18px;font-weight: bold; text-transform: uppercase;'>".$user_details_array["Full Name"]."<br/>"
                                .$user_details_array["Company Name"]."</span>";
        $name=$user_details_array["Full Name"];
    $message = <<<EOT
   <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
}
</style>
</head>
<body>
<table width="100%" border="0">
  <tr>
    <td><table width="800" border="0" align="center">
      <tr>
        <td><table width="800" border="0">
          <tr>
            <td width="400" align="left" valign="top">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">
    
    <p style="padding:10px; font-family:Tahoma, Geneva, sans-serif; font-size:16px;text-transform: uppercase;">
    	$user_details
    </p>
    </td>
  </tr>
</table>
            </td>
            <td><img src="$image_url" width="400" height="309" alt="" /></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><img src="$mid_image" width="800" height="70"  alt="" /></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="308" align="left" valign="top" style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#000000; line-height:16px; text-align:justify; padding:8px;"><span style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#000000; line-height:18px;">Dear $name   , <br />
                  <br />
                  </span><strong>Welcome to Smart Cities India 2015 exhibition and conference. <br />
                    <br />
                    </strong>Please print this badge at your home/office <br /><br />
        Collect a badge holder and lanyard at the venue entrance, and walk into the exhibition hall.<br />
                  <br />
                  IMPORTANT: Please make sure that the Bar Code area is not damaged / folded, else a fresh badge will need to be generated from the Registration Desk. <br />
                  <br />
        Welcome to Smart Cities India 2015 expo from 20- 22 May 2015, Pragati Maidan, New Delhi.<br /><br />
                  Yours truly, <br />
                  <br />
                  Team, Smart Cities India <br />
                  <br />
                  PS: If you forget this Self-Printed Badge, please visit the Pre-Registered section of the Registration Counters.</td>
              </tr>
            </table></td>
            <td width="10">&nbsp;</td>
            <td valign="top" align="left" width="300"><table width="100%" border="0" cellpadding="5" cellspacing="5">
              <tr>
                <td style="font-size:13px; color:#333333; font-family:Verdana, Geneva, sans-serif; line-height:18px; padding:3px 5px; text-transform:uppercase; ">For More Information, Contact:</td>
              </tr>
              <tr>
                <td valign="top" style="padding:0px 8px 8px 10px;  font-family:Arial, Helvetica, sans-serif; text-transform:none; font-size:14px; line-height:26px; "><p><strong>Tabish Ali</strong><br />
                  Ph.                   +91-11-4279 5045<br />
                  Mob. +91 99118 67476 <br />
                  E-mail: <a href="mailto:tabisha@eigroup.in">tabisha@eigroup.in</a><a href="mailto:tabisha@eigroup.in"><br />
                    </a><br />
                  <strong> Exhibitions India Group</strong><br />
                  217-B, 2nd Floor, <br />
                  Okhla Industrial Estate, Phase III, New Delhi 110 020, <br />
                  India</p></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
        <tr>
        <td><table width="800" border="0">
          <tr>
             <td width="400" align="left" valign="top">
                <table width="400" align="left" style="padding:0; margin:0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="left" valign="top">
        <img src="$image_url2" width="400" height="147" alt="" />
                         </td>        
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="padding:5px 0 5px 20px;">
                            $bottom_user_details
                         </td>        
                    </tr>
                    <tr>
                        <td align="left" valign="top">
                        
                            <table style="width:400px;" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="width:400px; background:#ccc; color:#000; padding: 6px 0;text-align: center;font-family: Arial,sans-serif;font-size: 20px;">$badge_type</td>
                                </tr>
                               </table>
                         </td>        
                    </tr>
        
                </table>
                
             </td>
            <td width="400" align="left" valign="top"><div style="width:400px; height:265px;"><img src="$image_url3" width="400" height="265" alt="" /></div></td>
            </tr>
          </table></td>
      </tr>
       <tr>
        <td><table width="800" border="0">
          <tr>
             <td width="400" align="center" valign="top"><img src="$barcode_link" width="200" height="70" alt="" /></td>
            <td>&nbsp;</td>
            </tr>
          </table></td>
      </tr>
        
       
          </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
EOT;

    return $message;
}
   
function smart_city_email_content($event_signup_id){
    $Globali = new cGlobali();

 $sqlEventSignup = "select Name,CurrencyId,SignupDt,c.code,es.BarcodeNumber,es.Address "
        . " ,u.FirstName from EventSignup es "
            . "INNER JOIN currencies c ON c.Id=es.CurrencyId "
            . "INNER JOIN events ev on ev.Id=es.EventId "
            . "INNER JOIN user u on u.id =ev.UserId "
            . "where es.Id='" . $Globali->dbconn->real_escape_string($event_signup_id) . "'";
 
        $res = $Globali->SelectQuery($sqlEventSignup);
        $first_name=$res[0]["Name"];
        $regards_name=$res[0]["FirstName"];
        $message= <<<EX
     <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body style="padding:0px; margin:0px;"><div style="background:#FFFFFF; padding:">
		<table width="100%" cellspacing="0" style="background-color: rgb(238, 237, 231);" class="backgroundTable"><tbody><tr><td valign="top" align="center">
<table width="550" cellspacing="0" cellpadding="0" style="border-bottom: 1px solid rgb(238, 237, 231);">
<tbody><tr><td height="40" align="center" class="headerTop" style="border-top: 0px solid rgb(0, 0, 0); border-bottom: 0px none rgb(255, 255, 255); padding: 0px; background-color: rgb(238, 237, 231); text-align: center;"><div style="font-size: 10px; color: rgb(153, 153, 153); line-height: 300%; font-family: Verdana; text-decoration: none;" class="adminText"></div></td></tr><tr><td style="background-color: rgb(255, 255, 255);" class="headerBar"><table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td width="120"><div style="padding: 10px;" class="divpad"><a href="http://www.meraevents.com" target="_blank" rel="nofollow"><span style="line-height: 0px;">
<img src="http://content.meraevents.com/images/onlineportalemailer.jpg" alt="MeraEvents.com" title="MeraEvents.com"   border="0" style="display: block;" /></span></a></div></td>
                        <td width="430" valign="top" align="right">&nbsp;</td>
		</tr></tbody></table></td></tr></tbody></table><table width="550" cellspacing="0" cellpadding="0" style="background-color: rgb(255, 255, 255);" class="bodyTable"><tbody><tr><td valign="top" align="left" style="padding: 20px 20px 0pt; background-color: rgb(255, 255, 255);" class="defaultText"><table width="95%" cellpadding="0" cellspacing="0" style="font-family:Arial, Helvetica, sans-serif; font-size:14px;">
          <tr>
            <td colspan="2" height="20">Hello $first_name,</td>
          </tr>
          <tr>
            <td colspan="2">
                <br/>
                Thank you for registering as a visitor at the Smart Cities India 2015 exhibition and conference (20-22 May 2015, Pragati Maidan, New Delhi, India).
                 <br /></td>
          </tr>
          
         
          <tr>
            <td width="350"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                   <tr>
                    <td height="20"></td>
                  </tr>
                  
                    <tr>
                    <td height="20">Registration No:  <b>$event_signup_id</b></a> </td>
                  </tr>
                   <tr>
                    <td height="20"></td>
                  </tr>
                  
                  <tr>
                    <td height="20">Please visit Event link:- <a href="http://www.smartcitiesindia.com/visi-reg.aspx"><b>Smart Cities India 2015 exhibition and conference </b> </a></td>
                  </tr>
                  <tr>
                    <td height="20">Start Date:- <b>2015-05-20 10:00:00</b> </a></td>
                  </tr>
                  <tr>
                    <td height="20">End Date:- <b>2015-05-22 16:00:00</b></td>
                  </tr>
                
                   <tr>
                    <td height="20"></td>
                  </tr>
                   </tbody>
            </table></td>
            <td valign="top">&nbsp;</td>
          </tr>
          
          
          <tr>
            <td colspan="2" height="20"></td>
          </tr>
           <tr>
          	<td colspan="2">
                
                
Your badge has been attached with this email. <br/><br/>
 
Please print this badge at your home/office.<br/><br/>
Collect a badge holder and lanyard at the venue entrance, and walk into the exhibition hall.<br/><br/>
 
Please contact the following for additional information.<br/><br/>
 
Tabish Ali<br/>
Ph. +91-11-4279 5045 | Mob. +91 99118 67476 | E-mail: tabisha@eigroup.in<br/>
 <br/>
Exhibitions India Group<br/>
217-B, 2nd Floor, Okhla Industrial Estate, Phase III, New Delhi 110 020, India<br/>
Tel: +91 11 4279 5000 | Fax: +91 11 4279 5098<br/>
 
 
            </td>
          </tr>
          <tr>
            <td colspan="2" height="20"></td>
          </tr>
          
         
         
          <tr>
            <td colspan="2" height="40">Thanks</td>
          </tr>
          
          <tr>
            <td colspan="2" height="40">Regards,<br /> $regards_name<br /></td>
          </tr>
          
          
        </table></td>
		</tr>
		            <tr></tr>
		            <tr><td align="left" style="padding: 20px;"><table width="510" cellspacing="0" cellpadding="0" border="0">	<tbody><tr>		<td style="font-family: Tahoma,Verdana,Geneva; font-size: 10px; color: rgb(98, 98, 98);">Questions, Comments, Critics? Email us at <a style="color: rgb(6, 117, 181); text-decoration: none;" href="/mc/compose?to=info@hereitself.com" target="_blank" ymailto="mailto:support@meraevents.com" rel="nofollow"><span id="lw_1266263330_4" class="yshortcuts">support@meraevents.com</span></a></td>		
		          <td width="100" align="right" style="font-family: Tahoma,Verdana,Geneva; font-size: 10px; color: rgb(98, 98, 98);">Friend us on:</td>		<td width="60" align="right"><a href="http://www.facebook.com/meraevents" target="_blank" rel="nofollow"><img width="23" height="22" border="0" alt="Facebook" src="http://content.meraevents.com/images/socio_logos/facebook.png"></a> &nbsp;<a href="http://twitter.com/meraevents_com" target="_blank" rel="nofollow"><img width="23" height="22" border="0" alt="Twitter" src="http://content.meraevents.com/images/socio_logos/twitter.png"></a></td>	
		            </tr></tbody></table></td></tr><tr><td valign="top" align="left" style="border-top: 10px solid rgb(255, 255, 255); padding: 20px 5px; background-color: rgb(238, 237, 231);" class="footerRow"><div style="font-size: 10px; color: rgb(153, 153, 153); line-height: 100%; font-family: Tahoma;" class="footerText">		            Copyright (C) 2010 itself. All rights reserved.<br>
		</div></td></tr></tbody></table></td></tr></tbody></table>
	</div></body>
</html>
EX;
 
        return $message;
}



function pmiCustomPdf($EventId,$EventSignupId)
{
	$Globali = new cGlobali();
	   
	   
	   $Events = @new cEvents($EventId);
       $Success = $Events->Load();
		
		$StartDt1 =date('F j, Y',strtotime($Events->StartDt));
        $EndDt1 = date('F j, Y',strtotime($Events->EndDt));
		$ddate=$StartDt1;
        if($StartDt1 !=$EndDt1){ $ddate.='-'.$EndDt1; }
		$ddate.=' | Time : '.date('g:i a',strtotime($Events->StartDt)).'-'.date('g:i a',strtotime($Events->EndDt));
		
		
	  $sqlEventSignup="select  userid,signupdt,Qty,Fees,Ccharge,PaymentModeId,PromotionCode,PaymentTransId,PaymentGateway,Ccharge,STax,DAmount,referralDAmount,CurrencyId,c.code,es.BarcodeNumber from EventSignup es INNER JOIN currencies c ON c.Id=es.CurrencyId where es.Id='".$Globali->dbconn->real_escape_string($EventSignupId)."'";
		
		$res=$Globali->SelectQuery($sqlEventSignup);
		if($res[0]['Fees']!=0)
		{
			if($res[0]['PaymentModeId']==1)
			{
				if($res[0]['PaymentTransId']=="PayPalPayment")
				{ $paymentmode="PayPal Transaction";  }
				else{ $paymentmode="Card Transaction"; }
			}
			else
			{
				$paymentmode="Cheque Transaction";
			}
			if($res[0]['PromotionCode']=="Y")
			{
				$paymentmode="Pay At Counter";
			}
			elseif($res[0]['PaymentGateway']=="CashonDelivery")
			{
				$paymentmode="Cash On Delivery";
			}
		} else { $paymentmode="Free Registration"; }
		
		
		$billingInfo=$Globali->SelectQuery("select concat(firstname,' ',lastname) 'username', email, address from user where id='".$res[0]['userid']."' ");
		
		
		$TicketingOptionsRes=$Globali->SelectQuery("SELECT enable_info FROM ticketingoptions WHERE EventId='".$Globali->dbconn->real_escape_string($Events->Id)."'");
			//print_r($TicketingOptionsRes);
			
			if(count($TicketingOptionsRes)>0)
			{
				if($TicketingOptionsRes[0]['enable_info']==0){$TicketingOption=0;}//collect one
				else{ $TicketingOption=1; }//collect all
			}
			else{ $TicketingOption=0; } //collect one
			
		
		
		if($TicketingOption==0){$sqlAddLimit=" limit 1";}else{$sqlAddLimit="";}
						
		$atteneeSql="select a.Id 'attendeeId', a.Name, a.Email,t.Name 'ticketName', a.Phone from `Attendees` AS a
				 RIGHT JOIN `tickets` AS t ON a.ticketid=t.Id
				 where a.`EventSIgnupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."' ".$sqlAddLimit;
				 
		$attendeeData = $Globali->SelectQuery($atteneeSql);
		
		
		
		$sqlTktDetails="select estd.TicketId,t.`Name`,t.Description,estd.`NumOfTickets`,estd.`TicketAmt`,t.`Donation` 
				  from `eventsignupticketdetails` AS estd
					RIGHT JOIN `tickets` AS t ON estd.TicketId=t.Id
					WHERE estd.`EventSignupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."'";
					
		$estdData=$Globali->SelectQuery($sqlTktDetails);
		
		$ticketIdsArr=array();$isOnlyDonation=true;$isOnlyDonationArr=NULL;
		for($et=0;$et<count($estdData);$et++)
		{
			$ticketIdsArr[]=$estdData[$et]['TicketId'];
                        if($estdData[$et]['Donation']!=1){
                            $isOnlyDonationArr[]=false;
                        }
		}
		$arrValues=  array_values($isOnlyDonationArr);
                if(in_array(false, $arrValues)){
                    $isOnlyDonation=false;
                }
		$customSql="select cf.`Id`, cf.`EventCustomFieldName` from `eventcustomfields` AS cf
			LEFT JOIN `event_ticket_customfields` AS etc ON cf.Id=etc.eve_tic_eventcustomfield_id
			where cf.`displayOnTicket`=1 and cf.EventCustomFieldSeqNo > 0
			and (cf.`EventId`='".$Globali->dbconn->real_escape_string($Events->Id)."' or etc.`eve_tic_ticket_id` in (".implode(",",$ticketIdsArr)."))";
			
			
		$customData = $Globali->SelectQuery($customSql);
		$cfDatacount=count($customData);
	
	$message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<body>
<table width="720" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr><td width="720"><img src="images/pmi/main.jpg" width="720" height="254" alt="Project Management National Conference, India ARCHITECTING PROJECT MANAGEMENT for Redefining India... The Lalit Ashok, Bengaluru | September 10-12, 2015 | A PMI Team India Event" style="display:block;" border="0"></td></tr>
  
  <tr><td><br></td></tr>
  
  <tr>
  	<td>
    <p  style="text-align: justify; color: #D3611C; font-size: 15pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold;">Your registration is confirmed!</p>
    <p style="line-height: 16pt; text-align: left; color: #000; font-size: 11pt; font-family: Arial, Helvetica, sans-serif;">Welcome <b>'.$billingInfo[0]['username'].'</b>,<br><br>	 

 	Thank you for registering for the Project Management National Conference, India 2015, scheduled from September 10 to 12 at The Lalit Ashok, Kumara Krupa High Grounds, Bengaluru, Karnataka, India.<br><br>	 

 	Your registration has been confirmed based on the details provided below. Counters for distributing the delegate kit will open on September 10, 2015.</p>
    </td>
  </tr>
  
  <tr><td><br></td></tr>
  
  
  <tr>
  	<td>
    <table width="624" border="0" cellspacing="0" cellpadding="0"  align="center">
                        <tr>
                          
                          <td colspan="2" valign="middle" bgcolor="#d5711a" style="text-align: left; border-bottom-color: #FFF; border-bottom-style: solid; border-bottom-width: 2px; background-color: #d3611c; color: #FFF; font-size: 13pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold; padding-left:20px">Registration Details</td>
                          </tr>
                        <tr height="20px">
                          
                          <td valign="middle" bgcolor="#ffdea1" style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold; text-align: left;padding-left:10px">Transaction ID</td>
                          
                          <td  valign="middle" bgcolor="#ffdea1" style="border-bottom-color: #FFF; border-bottom-style: solid; border-bottom-width: 2px; text-align: left; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold;padding-left:20px"><strong>'.$EventSignupId.'</strong></td>
                         
                        </tr>
                        <tr>
                          
                          <td  height="24" valign="middle" bgcolor="#ffdea1" style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold; text-align: left;padding-left:10px">Registration Date</td>
                          
                          <td  valign="middle" bgcolor="#ffdea1" style="border-bottom-color: #FFF; border-bottom-style: solid; border-bottom-width: 2px; text-align: left; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold;padding-left:20px"><strong>'.date("d M Y, h:i A",strtotime($res[0]['signupdt'])).'</strong></td>
                         
                        </tr>
                        <tr>
                          
                          <td  height="24" valign="middle" bgcolor="#ffdea1" style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold; text-align: left;padding-left:10px">Registration Status</td>
                          
                          <td  valign="middle" bgcolor="#ffdea1" style="border-bottom-color: #FFF; border-bottom-style: solid; border-bottom-width: 2px; text-align: left; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold;padding-left:20px"><strong>Confirmed</strong></td>
                         
                        </tr>
                        
                        
                      </table>
    </td>
  </tr>
  
  
  
  <tr><td><br></td></tr>
  
  
  <tr>
  	<td>
    <table width="624" border="0" cellspacing="0" cellpadding="0" align="center">
                        <tr>
                          
                          <td colspan="5" valign="middle" bgcolor="#d5711a" style="text-align: left; border-bottom-color: #FFF; border-bottom-style: solid; border-bottom-width: 2px; background-color: #d3611c; color: #FFF; font-size: 13pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold; padding-left:20px">Group List</td>
                          </tr>
                          <tr bgcolor="#ffdea1" height="35px">
                          <th style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold; text-align: center;">Sr. No.</th>
                          <th style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold; text-align: center;">Name</th>
                          <th style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold; text-align: center;">Registration Type</th>
                          <th style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold; text-align: center;">Email ID</th>
                          <th style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold; text-align: center;">Selected Special program</th>
                          </tr>';
						  
						  $pmiattsno=1;
							  for($a=0;$a<count($attendeeData);$a++)
					  {
						  $message.='<tr bgcolor="#ffdea1" height="25px">
                          <td style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif;  text-align: center;">'.$pmiattsno.'</td>
                          <td style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif;  text-align: center;">'.$attendeeData[$a]['Name'].'</td>
                          <td style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif;  text-align: center;">'.$attendeeData[$a]['ticketName'].'</td>
                          <td style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif;  text-align: center;">'.$attendeeData[$a]['Email'].'</td>';
						  
						  if($cfDatacount>0)
							{
								for($cd=0;$cd<$cfDatacount;$cd++)
								{
									$message.='<td style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif;  text-align: center;">'.$Globali->GetSingleFieldValue("select `EventSignupFieldValue` from `eventsignupcustomfields` where `EventCustomFieldsId`='".$Globali->dbconn->real_escape_string($customData[$cd]['Id'])."' and `attendeeId`='".$Globali->dbconn->real_escape_string($attendeeData[$a]['attendeeId'])."' and `EventId`='".$Globali->dbconn->real_escape_string($Events->Id)."' and `EventSignupId`='".$Globali->dbconn->real_escape_string($EventSignupId)."'").'</td>';
								}
							}
							$pmiattsno++;
						  
                          $message.='</tr>';
					  }
                          
                          $message.='</table>
    </td>
  </tr>
  
  <tr><td><br></td></tr>
  
  
  <tr>
  	<td>
    <table width="624" border="0" cellspacing="0" cellpadding="0" align="center">
                        <tr>
                          
                          <td colspan="3" valign="middle" bgcolor="#d5711a" style="text-align: left; border-bottom-color: #FFF; border-bottom-style: solid; border-bottom-width: 2px; background-color: #d3611c; color: #FFF; font-size: 13pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold; padding-left:20px">Charges for Agenda Items and Other Fees</td>
                          </tr>
                          <tr bgcolor="#ffdea1" height="35px">
                          <th style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold; text-align: center;">Item</th>
                          <th style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold; text-align: center;">Rate (INR)</th>
                          <th style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold; text-align: center;">Quantity</th></tr>';
						  
						  $tktTotalAmount=0;
				  for($t=0;$t<count($estdData);$t++)
				  {
					  $tktTotalAmount+=$estdData[$t]['TicketAmt'];
						  $message.='<tr bgcolor="#ffdea1" height="25px">
                          <td style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif;  text-align: center;">'.$estdData[$t]['Name'].'</td>
                          <td style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif;  text-align: center;">'.$res[0]['code']." ".$estdData[$t]['TicketAmt'].'</td>
                          <td style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif;  text-align: center;">'.$estdData[$t]['NumOfTickets'].'</td>';
						  
						  
                          $message.='</tr>';
					  }
					  $toatlPurchaseAmount=round($res[0]['Qty']*$res[0]['Fees']-$res[0]['Ccharge']);
					  $message.='<tr bgcolor="#ffdea1" height="30px">
					  <td colspan="2" align="right" style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif;  text-align: center;padding-right:15px">Total: (Including Taxes)</td>
					  <td style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif;  text-align: center;">'.$res[0]['code']." ".round($toatlPurchaseAmount,2).' </td></tr>';
                          
                          $message.='</table>
    </td>
  </tr>
  
  
  <tr><td><br></td></tr>
  
  
  
  <tr>
  	<td>
    <table width="624" border="0" cellspacing="0" cellpadding="0"  align="center">
                        <tr>
                          
                          <td colspan="2" valign="middle" bgcolor="#d5711a" style="text-align: left; border-bottom-color: #FFF; border-bottom-style: solid; border-bottom-width: 2px; background-color: #d3611c; color: #FFF; font-size: 13pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold; padding-left:20px">Billing Contact Details</td>
                          </tr>
                        <tr height="24px">
                          
                          <td valign="middle" bgcolor="#ffdea1" style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold; text-align: left;padding-left:20px">Name</td>
                          
                          <td  valign="middle" bgcolor="#ffdea1" style="border-bottom-color: #FFF; border-bottom-style: solid; border-bottom-width: 2px; text-align: left; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold;padding-left:20px"><strong>'.$billingInfo[0]['username'].'</strong></td>
                         
                        </tr>
                        <tr height="24px">
                          
                          <td valign="middle" bgcolor="#ffdea1" style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold; text-align: left;padding-left:20px">Address</td>
                          
                          <td  valign="middle" bgcolor="#ffdea1" style="border-bottom-color: #FFF; border-bottom-style: solid; border-bottom-width: 2px; text-align: left; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold;padding-left:20px"><strong>'.$billingInfo[0]['address'].'</strong></td>
                         
                        </tr>
                        <tr height="24px">
                          
                          <td valign="middle" bgcolor="#ffdea1" style="border-bottom-color: #FFF; border-right-color: #FFF; border-bottom-style: solid; border-right-style: solid; border-bottom-width: 2px; border-right-width: 2px; color: #000000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold; text-align: left;padding-left:20px">Email Address</td>
                          
                          <td  valign="middle" bgcolor="#ffdea1" style="border-bottom-color: #FFF; border-bottom-style: solid; border-bottom-width: 2px; text-align: left; font-size: 9pt; font-family: Arial, Helvetica, sans-serif; font-weight: bold;padding-left:20px"><strong>'.$billingInfo[0]['email'].'</strong></td>
                         
                        </tr>
                        
                        
                      </table>
    </td>
  </tr>
  
  
  
  <tr><td><br></td></tr>
  
  <tr><td>
  <p style="line-height: 16pt; text-align: left; color: #000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif;">Please Note: The charges will be billed to your credit or debit card as <b>Versant Online Solutions Pvt. Ltd.</b><br></p>
  
  <p  style="line-height: 16pt; text-align: left; color: #000; font-size: 9pt; font-family: Arial, Helvetica, sans-serif;">For confirmed registrants, PMI India will send out a welcome mailer with additional information about the conference. This welcome mailer will be sent on or before September 5, 2015. During all days of the conference, delegates must bring print-out or present a soft copy of the email over their phone of 1) this confirmation email 2) welcome email that will have additional information and 3) a photo ID card and present it at the registration counter.<br><br></p>
  
  <p  style="line-height: 16pt; text-align: left; color: #000; font-size: 11pt; font-family: Arial, Helvetica, sans-serif;">You can contact us by e-mail at <a href="mailto:conference2015@pmi-india.org">conference2015@pmi-india.org</a>. Kindly include your registration number, e-mail address, name and dates of the event in all future correspondence.<br><br></p>
  
  <p  style="line-height: 16pt; text-align: left; color: #000; font-size: 11pt; font-family: Arial, Helvetica, sans-serif;"><a href="http://www.pmi.org.in/events/conference2015/#venue-details" target="_blank">Click here</a> for Venue Details<br>
                       <a href="http://www.pmi.org.in/events/conference2015/#venue-details" target="_blank">Click here</a> to read Terms and Conditions<br></p>
  
  <p  style="line-height: 16pt; text-align: left; color: #000; font-size: 11pt; font-family: Arial, Helvetica, sans-serif;"><strong>Note for Members and Credential Holders</strong>: Remember you are bound by the PMI\'s Code of Ethics and Professional Conduct.<br><br></p>
  
  <p  style="line-height: 16pt; text-align: left; color: #000; font-size: 11pt; font-family: Arial, Helvetica, sans-serif;">For further information about <strong>Project Management National Conference, India 2015</strong>, visit our website <a href="http://www.pmi.org.in/events/conference2015/" target="_blank">www.pmi.org.in/conference</a>. Thank you for registering for the Project Management National Conference, India 2015. We hope you have a wonderful time.<br></p>
  
  Best Regards,<br><br>
  
  <table width="620">
  	<tr>
	<td align="left"><b>Organizing Committee <br>
Project Management National Conference, India 2015</b><br>
<a href="http://www.pmi.org.in/events/conference2015/" target="_blank">www.pmi.org.in/conference</a></td>
	<td align="right"><a href="http://www.pmibangalorechapter.in/" target="_blank"><img src="images/pmi/logo.jpg" alt="HOSTED &amp; ORGANIZED BY PMI BANGALORE INDIA CHAPTER" width="190" height="82" style="display:block;" border="0"></a></td>
	</tr>
  </table><br>
  
  <p style="line-height: 16pt; text-align: left; color: #000; font-size: 7pt; font-family: Arial, Helvetica, sans-serif;">PMI, the PMI Logo and PROJECT MANAGEMENT INSTITUTE are registered marks of Project Management Institute, Inc.</p>
  
  
  </td></tr>
  
  <tr><td><img src="images/pmi/footer.jpg" width="720" height="21" style="display:block;" border="0"></td></tr>
  
</table>
</body>
</html>
';

return $message;
}

   
   
   
   
   
   
   
   
   
}

?>