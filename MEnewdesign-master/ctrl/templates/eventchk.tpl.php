
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title>MeraEvents -Menu Content Management</title>
    <link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
    <link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
    <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
    <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>
    <script language="javascript" src="<?=_HTTP_SITE_ROOT;?>/js/public/jQuery.js"></script> 
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
                    var EventId = document.getElementById('Eventid').value;
            if(EventId>0){
            $.get('includes/ajaxSeoTags.php', {eventIDChk: 0, eventid: EventId}, function (data) {
            if (data == "error")
            {
                alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
                document.getElementById('Eventid').focus();
                return false;

            }
        });
        }
    }
        
        function TransStatus(sId)
            {


            var Tckwdz=document.getElementById('Tckwdz'+sId).value;
            var PaidBit=document.getElementById('PaidBit'+sId).value;
            var Exception=document.getElementById('Exception'+sId).value;
            var CompiOrg=document.getElementById('CompiOrg'+sId).value;
            var LeftForPayment=document.getElementById('LeftForPayment'+sId).value;
            var strtdt = document.frmEofMonth.txtSDt.value;
            var enddt = document.frmEofMonth.txtEDt.value;
            var SalesId = document.frmEofMonth.SalesId.value;
            var Eventid=document.frmEofMonth.Eventid.value;
            var eadd = document.frmEofMonth.eadd.value;
            var paid = document.frmEofMonth.paid.value;
            var Tck = document.frmEofMonth.Tck.value;
            var EPublished= document.frmEofMonth.EPublished.value;
            var EException= document.frmEofMonth.EException.value;
            window.location="eventchk.php?value=change&sId="+sId+"&Tckwdz="+Tckwdz+"&PaidBit="+PaidBit+"&CompiOrg="+CompiOrg+"&LeftForPayment="+LeftForPayment+"&txtSDt="+strtdt+"&txtEDt="+enddt+"&SalesId="+SalesId+"&Eventid="+Eventid+"&eadd="+eadd+"&paid="+paid+"&Tck="+Tck+"&EPublished="+EPublished+"&Exception="+Exception+"&EException="+EException;
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
         <option value="<?php echo $SalesQueryRES[$i]['SalesId'];?>" <?php  if($SalesQueryRES[$i]['SalesId']==$_REQUEST[SalesId]){?> selected="selected" <?php  }?>><?php echo $SalesQueryRES[$i]['SalesName'];?></option>
         <?php  }?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td> Event-Id :</td>
    <td width="81%"><label>
    <input type="text" name="Eventid" id="Eventid" value="<?php echo $_REQUEST['Eventid'];?>" />
    </label></td>
  </tr>
   
  <tr>
    <td> Select Events Added by :</td>
    <td width="81%"><label>
      <select name="eadd" id="eadd" >
        <option value="">Both</option>
        <option value="Team" <?php  if($_REQUEST['eadd']=="Team"){?> selected="selected" <?php  }?>>Team</option>
        <option value="Organizer" <?php  if($_REQUEST['eadd']=="Organizer"){?> selected="selected" <?php  }?>>Organizer</option>
        
      </select>
    </label></td>
  </tr>
  <tr>
    <td> Paid :</td>
    <td width="81%"><label>
      <select name="paid" id="paid" >
        <option value="" selected="selected">All</option>
        <option value="1" <?php  if($_REQUEST['paid']=="1"){?> selected="selected" <?php  }?>>Yes</option>
         <option value="0" <?php  if($_REQUEST['paid']=="0"){?> selected="selected" <?php  }?>>No</option>
        
      </select>
    </label></td>
  </tr>
   <tr>
    <td> TckWdz :</td>
    <td width="81%"><label>
      <select name="Tck" id="Tck" >
        <option value="" selected="selected">All</option>
        <option value="1" <?php  if($_REQUEST['Tck']=="1"){?> selected="selected" <?php  }?>>Yes</option>
         <option value="0" <?php  if($_REQUEST['Tck']=="0"){?> selected="selected" <?php  }?>>No</option>
        
      </select>
    </label></td>
  </tr>
  <tr>
    <td> Published :</td>
    <td width="81%"><label>
      <select name="EPublished" id="EPublished" >
        <option value="All" <?php  if($_REQUEST['EPublished']=="All"){?> selected="selected" <?php  }?>>All</option>
        <option value="Yes" <?php  if($_REQUEST['EPublished']=="Yes"){?> selected="selected" <?php  }?>>Yes</option>
         <option value="No" <?php  if($_REQUEST['EPublished']=="No"){?> selected="selected" <?php  }?>>No</option>
        
      </select>
    </label></td>
  </tr>
  <tr>
    <td>  Exception :</td>
    <td width="81%"><label>
      <select name="EException" id="EException" >
        <option value="" selected="selected" >All</option>
        <option value="1" <?php  if($_REQUEST['EException']=="1"){?> selected="selected" <?php  }?>>Yes</option>
         <option value="0" <?php  if($_REQUEST['EException']=="0"){?> selected="selected" <?php  }?>>No</option>
        
      </select>
    </label></td>
  <tr>
    <td colspan="2"><table width="50%" border="0" cellspacing="2" cellpadding="2">
  
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
    <td width="44%" align="center"><strong>Events</strong></td>
    <td width="10%" align="center"><strong>Preview</strong></td>
    <td width="17%" align="center"><strong>Organizer</strong></td>
    <td width="12%" align="center"><strong>Coming From Competitor</strong></td>
    <td width="18%" align="center"><strong>Leftfor1000rs/-</strong></td>
    
    <td width="17%" align="center"><strong>TckWdz</strong></td>
    <td width="17%" align="center"><strong>Paid</strong></td>
    <td width="17%" align="center"><strong>Exception</strong></td>
    <td width="9%" align="center"><strong>Action</strong></td>
  </tr>
    <?php
	$TotalEventsOfMonth = count($EventsOfMonth);
        
        for($i=0; $i < $TotalEventsOfMonth; $i++)
        {
            $org=$EventsOfMonth[$i]['FirstName']."<br/>".$EventsOfMonth[$i]['Company']."<br/>".$EventsOfMonth[$i]['Email']."<br/>".$EventsOfMonth[$i]['Mobile'];

//            $window_url = _HTTP_SITE_ROOT."/dashboard/event/edit/".$EventsOfMonth[$i]['Id']."/".$EventsOfMonth[$i]['UserID']; 
            $eventUrl=_HTTP_SITE_ROOT."/event/".$EventsOfMonth[$i]['URL']."?ucode=organizer";
            $window_url =  _HTTP_SITE_ROOT."/api/user/adminSession?organizerId=".$EventsOfMonth[$i]['UserID']."&eventid=".$EventsOfMonth[$i]['Id']."&adminId=".$uid."&userType=".$_SESSION['adminUserType'];
            
            if(substr_count($EventsOfMonth[$i]['OEmails'],"@meraevents.com")>=1){
               $inner='bgcolor="#66FF66"';
            }else{
               $inner='';
            }
     ?>
  <tr <?php echo $inner;?>>
    <td><a href="#" onclick="window.open('<?php echo $window_url?>','mywindow','menubar=1,width=900,height=600,resizable=yes,scrollbars=yes');"><?php echo $EventsOfMonth[$i][Title];?></a></td>
    <td align="center"><a href="<?php echo $eventUrl?>" target="_blank">Clickhere</a></td>
    <td align="center">
	<?php echo $org;?></td>
    <td align="center">
	 <select name="CompiOrg<?php echo $EventsOfMonth[$i]['Id'];?>" id="CompiOrg<?php echo $EventsOfMonth[$i]['Id'];?>" >
        <option value="">Select</option>
          <option value="1" <?php  if($EventsOfMonth[$i]['CompiOrg']=="1"){?> selected="selected" <?php  }?>>Yes</option>
          <option value="0" <?php  if($EventsOfMonth[$i]['CompiOrg']=="0"){?> selected="selected" <?php  }?>>No</option>
       
      </select>
    </td>
    <td align="center">
	 <select name="LeftForPayment<?php echo $EventsOfMonth[$i]['Id'];?>" id="LeftForPayment<?php echo $EventsOfMonth[$i]['Id'];?>" >
        <option value="">Select</option>
          <option value="1" <?php  if($EventsOfMonth[$i]['LeftForPayment']=="1"){?> selected="selected" <?php  }?>>Yes</option>
          <option value="0" <?php  if($EventsOfMonth[$i]['LeftForPayment']=="0"){?> selected="selected" <?php  }?>>No</option>
       
      </select>
     </td>
    
    <td align="center">
        <select name="Tckwdz<?php echo $EventsOfMonth[$i]['Id'];?>" id="Tckwdz<?php echo $EventsOfMonth[$i]['Id'];?>" >
        <option value="">Select</option>
          <option value="1" <?php  if($EventsOfMonth[$i]['Tckwdz']=="1"){?> selected="selected" <?php  }?>>Yes</option>
          <option value="0" <?php  if($EventsOfMonth[$i]['Tckwdz']=="0"){?> selected="selected" <?php  }?>>No</option>
       
      </select>
    </td>
     <td align="center">
	 	  
           <select name="PaidBit<?php echo $EventsOfMonth[$i]['Id'];?>" id="PaidBit<?php echo $EventsOfMonth[$i]['Id'];?>" >
        <option value="">Select</option>
          <option value="1" <?php  if($EventsOfMonth[$i]['PaidBit']=="1"){?> selected="selected" <?php  }?>>Yes</option>
          <option value="0" <?php  if($EventsOfMonth[$i]['PaidBit']=="0"){?> selected="selected" <?php  }?>>No</option>
      </select>
      </td>
       <td align="center">
	 	  
           <select name="Exception<?php echo $EventsOfMonth[$i]['Id'];?>" id="Exception<?php echo $EventsOfMonth[$i]['Id'];?>" >
        <option value="">Select</option>
          <option value="1" <?php  if($EventsOfMonth[$i]['Exception']=="1"){?> selected="selected" <?php  }?>>Yes</option>
          <option value="0" <?php  if($EventsOfMonth[$i]['Exception']=="0"){?> selected="selected" <?php  }?>>No</option>
      </select>
      </td>
    
      <td align="center"><input type="button" name="changetrans" value="change" onclick="TransStatus(<?php echo $EventsOfMonth[$i]['Id'];?>);"  />
      <?php $sqlcancom="SELECT count(id) as commentcount from eventqualitycomment where eventid=".$EventsOfMonth[$i]['Id'];
			 $commentcount=$Global->SelectQuery($sqlcancom);
			 ?>
           <a  href="addEventcomment.php?EventId=<?php echo $EventsOfMonth[$i]['Id'];?>&pagename=EventComments&txtSDt=<?php echo $SDt;?>&txtEDt=<?php echo $EDt;?>&SalesId=<?php echo $_REQUEST[SalesId];?>&Eventid=<?php echo $_REQUEST['Eventid'];?>&eadd=<?php echo $_REQUEST[eadd];?>&paid=<?php echo $_REQUEST[paid];?>">ViewComments(<?php echo $commentcount[0]['commentcount'];?>)</a></td>
  </tr>
  <tr><td colspan="9" align="center"><?php  if ($commentcount[0]['commentcount'] != 0) { echo $Global->GetSingleFieldValue("SELECT `comment` FROM eventqualitycomment WHERE eventid='".$EventsOfMonth[$i]['Id']."' order by id desc"); } ?>
</td>
  <?php  }?>
  <tr><td colspan="9" align="right"><?php echo $pagination;?></td></tr>
  
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