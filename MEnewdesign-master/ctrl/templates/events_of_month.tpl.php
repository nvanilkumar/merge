<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>MeraEvents - Admin Panel - Events of the Month</title>
    <link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
    <link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
    <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>    
    <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>    
</head>    
<body style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
    <?php include('templates/header.tpl.php'); ?>   
<?php 
    if(count($sdata)>0)
    echo "<div style=\"color\":Red><strong>$uploadToS3Error</strong></div>";
    if(strlen(trim($OptimizeImageScriptError))>0)
    echo "<div style=\"color\":Red><strong>$OptimizeImageScriptError</strong></div>";
    ?>
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
<script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
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

 function checkper()
 {
 var perc = document.frmperc.perc.value;
//alert(perc); (parseInt(perc) != perc) ||
 if(perc!=""){
 if (isNaN(parseFloat(perc)))
 {
  return false;
 }else{
 return true;
 }
}
 
 }
/*
    function getXMLHTTP() 
    { //fuction to return the xml http object
        
        var xmlhttp=false;    
        try
        {
            xmlhttp=new XMLHttpRequest();
        }
        catch(e)    
        {        
            try
            {            
                xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch(e)
            {
                try
                {
                    req = new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch(e1)
                {
                    xmlhttp=false;
                }
            }
        }
            
        return xmlhttp;
    }
    
    function updateFamousEvent(strURL) 
    {            
        var req = getXMLHTTP();
        
        if (req) 
        {    
            req.onreadystatechange = function() 
            {
                if (req.readyState == 4) 
                {
                    // only if "OK"
                    if (req.status == 200) 
                    {                        
                        document.getElementById('EventsOfMonth').innerHTML=req.responseText;                        
                    } 
                    else 
                    {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }                
            }            
            req.open("GET", strURL, true);
            req.send(null);
        }
    }
*/
</script>
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Events Of The Month</div>
<div align="center" style="width:100%">&nbsp;</div>
<form action="" method="post" name="frmEofMonth" >
<table width="80%" align="center" class="tblcont">
    <tr>
      <td width="35%" align="left" valign="middle">Start Date:&nbsp;<input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
      <td width="35%" align="left" valign="middle">End Date:&nbsp;<input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
      <td width="30%" align="left" valign="middle"><input type="submit" name="submit" value="Show Events" onclick="return SEdt_validate();" /></td>
    </tr>
</table>





<?php if(count($EventsOfMonth) > 0) { ?>
<table width="100%" align="center" class="sortable">
    <tr>
        <td width="10%" align="left" valign="middle" class="tblcont1">Sr. No.</td>
        <td width="40%" align="left" valign="middle" class="tblcont1">Event Name</td>
        <td width="2%" align="left" valign="middle" class="tblcont1">Event-Id</td>
        <td width="15%" align="left" valign="middle" class="tblcont1">Start Date</td>
        <td width="15%" align="left" valign="middle" class="tblcont1">End Date</td>
        <td width="10%" align="left" valign="middle" class="tblcont1">Action</td>
        <td width="10%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">IsFamous</td>
        <td width="15%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">NoMore Events</td>
        <td width="15%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">NoTime</td>
         <td width="15%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Need Volunteer</td>
         <td width="15%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Contact Display</td>
         <td width="15%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">tck Widget No</td>
         <td width="15%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">DiscountNo</td>
         <td width="15%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">FBComments No</td>
          <td width="15%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Per</td>
          <!--<td width="15%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">BackGround Img</td>-->
          <td width="15%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Comments</td>
          <!--<td width="15%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">TicketType</td>-->
          <td width="15%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Action</td>
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
        
        //START window.open URL creation code
        //Please be sure to do any changes.        
        $UserID = $EventsOfMonth[$i]['UserID'];
//        $window_url = _HTTP_SITE_ROOT."/dashboard-aevent?UserType=Organizer&uid=".$UserID."&EventId=".$EventsOfMonth[$i]['Id'];
        $window_url =  _HTTP_SITE_ROOT."/api/user/adminSession?organizerId=".$UserID."&eventid=".$EventsOfMonth[$i]['Id']."&adminId=".$uid."&userType=".$_SESSION['adminUserType'];
            
        //END window.open URL creation code
        ?>
        </td>
        <td align="left" valign="middle" class="helpBod"><a href="#" onclick="window.open('<?php echo $window_url?>','mywindow','menubar=1,width=900,height=600,resizable=yes,scrollbars=yes');">Edit</a></td>
        <?php $newIsFamousStatus = 0; ?>
        <td align="left" valign="middle" class="helpBod" id="EventsOfMonth">
            <select name="isFamous" onchange="updateIsFamous('<?php echo $EventsOfMonth[$i]['Id']; ?>',this)">
                <option value="0" <?php if($EventsOfMonth[$i]['IsFamous'] == 0) {echo 'selected=selected';}?> name="normal">Normal</option>
                <option value="1" <?php if($EventsOfMonth[$i]['IsFamous'] == 1) {echo 'selected=selected';}?>>Famous</option>
                <option value="2" <?php if($EventsOfMonth[$i]['IsFamous'] == 2) {echo 'selected=selected';}?>>Very Famous</option>
            </select>
<!--            <input type="checkbox" name="chkIsFamous" <?php if($EventsOfMonth[$i]['IsFamous'] == 1) { $newIsFamousStatus = 0; ?> checked="checked" value="1" <?php } else { $newIsFamousStatus = 1; ?> value="0" <?php } ?> onclick="updateIsFamous('<?php echo $EventsOfMonth[$i]['Id']; ?>','<?php echo $newIsFamousStatus?>');" />-->
        </td>
            <?php $newNotMoreStatus = 0; ?>
        <td align="left" valign="middle" class="helpBod" id="NotMore">
            <input type="checkbox" name="chkNotMore" <?php if($EventsOfMonth[$i]['NotMore'] == 1) { $newNotMoreStatus = 0; ?> checked="checked" value="1" <?php } else { $newNotMoreStatus = 1; ?> value="0" <?php } ?> onclick="updateNotMore('<?php echo $EventsOfMonth[$i]['Id']; ?>','<?php echo $newNotMoreStatus?>');" />
        </td>
         <?php $newNoDates = 0; ?>
        <td align="left" valign="middle" class="helpBod" id="NotMore">
            <input type="checkbox" name="chkNoDates" <?php if($EventsOfMonth[$i]['NoDates'] == 1) { $newNoDates = 0; ?> checked="checked" value="1" <?php } else { $newNoDates = 1; ?> value="0" <?php } ?> onclick="updateNoDates('<?php echo $EventsOfMonth[$i]['Id']; ?>','<?php echo $newNoDates?>');" />
        </td>
          <?php $newNeedVolStatus = 0; ?>
        <td align="left" valign="middle" class="helpBod" id="NotMore">
            <input type="checkbox" name="chkNeedVol" <?php if($EventsOfMonth[$i]['NeedVol'] == 1) { $newNeedVolStatus = 0; ?> checked="checked" value="1" <?php } else { $newNeedVolStatus = 1; ?> value="0" <?php } ?> onclick="updateNeedVol('<?php echo $EventsOfMonth[$i]['Id']; ?>','<?php echo $newNeedVolStatus?>');" />
        </td>
           <?php $newContactDisp = 0; ?>
        <td align="left" valign="middle" class="helpBod" id="ContactDisp">
            <input type="checkbox" name="chkContactDisp" <?php if($EventsOfMonth[$i]['ContactDisp'] == 1) { $newContactDisp = 0; ?> checked="checked" value="1" <?php } else { $newContactDisp = 1; ?> value="0" <?php } ?> onclick="updateContactDisp('<?php echo $EventsOfMonth[$i]['Id']; ?>','<?php echo $newContactDisp?>');" />
        </td>
          <?php $newwidgetdisp = 0; ?>
        <td align="left" valign="middle" class="helpBod" id="widgetdisp">
            <input type="checkbox" name="chkwidgetdisp" <?php if($EventsOfMonth[$i]['widgetdisp'] == 1) { $newwidgetdisp = 0; ?> checked="checked" value="1" <?php } else { $newwidgetdisp = 1; ?> value="0" <?php } ?> onclick="updatewidgetdisp('<?php echo $EventsOfMonth[$i]['Id']; ?>','<?php echo $newwidgetdisp?>');" />
        </td>
         <?php $newnodiscount = 0; ?>
        <td align="left" valign="middle" class="helpBod" id="nodiscount">
            <input type="checkbox" name="chknodiscount" <?php if($EventsOfMonth[$i]['NoDiscount'] == 1) { $newnodiscount = 0; ?> checked="checked" value="1" <?php } else { $newnodiscount = 1; ?> value="0" <?php } ?> onclick="updatenodiscountp('<?php echo $EventsOfMonth[$i]['Id']; ?>','<?php echo $newnodiscount?>');" />
        </td>
         <?php $newfbcomment = 0; ?>
        <td align="left" valign="middle" class="helpBod" id="fbcommentdisp">
            <input type="checkbox" name="chkfbcomment" <?php if($EventsOfMonth[$i]['Nofbcomments'] == 1) { $newfbcomment = 0; ?> checked="checked" value="1" <?php } else { $newfbcomment = 1; ?> value="0" <?php } ?> onclick="updatefbcomment('<?php echo $EventsOfMonth[$i]['Id']; ?>','<?php echo $newfbcomment?>');" />
        </td>
        <form name="frmperc" action="" method="post" enctype="multipart/form-data" onsubmit="return checkper();">
          <input type="hidden" name="PEventId" value="<?php echo $EventsOfMonth[$i]['Id'];?>"  />
         
        <td align="left" valign="middle" class="helpBod" >
        
      
            <input type="text" name="perc" size="5" value="<?php echo $EventsOfMonth[$i]['perc'];?>" id="perc"  />
            
             
         
        </td>
        <!--<td align="left" valign="middle" class="helpBod" ><input name="EventBg" id="EventBg"  type="file" value="" />
        <input type="hidden" name="backimg" value="<?php echo $EventsOfMonth[$i]['EventBackground'];?>" />
        <?php
        /*if($EventsOfMonth[$i]['EventBackground']!=""){  echo "Banner exist &nbsp";?> 
        <span style="cursor:pointer;" onclick="updatebgimg('<?php echo $EventsOfMonth[$i]['Id']; ?>')" >Delete </span>
        <?php  } */ ?> 
        
        </td>--> <td align="left" valign="middle" class="helpBod" ><input type="text" name="Commentperc" value="<?php echo $EventsOfMonth[$i]['Commentperc'];?>" id="Commentperc"  />   </td>
         <!--<td align="left" valign="middle" class="helpBod" ><select name="TicketType">
         <option value="E-Ticket" <?php //if($EventsOfMonth[$i]['TicketType']=="E-Ticket") { ?> selected="selected" <?php //}?>>E-Ticket</option><option value="Physical"  <?php //if($EventsOfMonth[$i]['TicketType']=="Physical") { ?> selected="selected" <?php //}?>>Physical</option></select>
            </td>--><td align="left" valign="middle" class="helpBod" ><input type="submit" name="Save" value="Save" /></td>
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
<script language="javascript" type="text/javascript">
    function updateIsFamous(eId,sel)
    {
        var strtdt = document.frmEofMonth.txtSDt.value;
        var enddt = document.frmEofMonth.txtEDt.value;
        var nStatus=sel.value;
        window.location = 'events_of_month.php?EventId='+eId+'&newStatus='+nStatus+'&txtSDt='+strtdt+'&txtEDt='+enddt;
    }
    function updateNotMore(eId,nStatus)
    {
        var strtdt = document.frmEofMonth.txtSDt.value;
        var enddt = document.frmEofMonth.txtEDt.value;
        window.location = 'events_of_month.php?EventId='+eId+'&newNotMoreStatus='+nStatus+'&txtSDt='+strtdt+'&txtEDt='+enddt;
    }
	 function updateNoDates(eId,nStatus)
    {
        var strtdt = document.frmEofMonth.txtSDt.value;
        var enddt = document.frmEofMonth.txtEDt.value;
        window.location = 'events_of_month.php?EventId='+eId+'&newNoDates='+nStatus+'&txtSDt='+strtdt+'&txtEDt='+enddt;
    }
    function updateNeedVol(eId,nStatus)
    {
        var strtdt = document.frmEofMonth.txtSDt.value;
        var enddt = document.frmEofMonth.txtEDt.value;
        window.location = 'events_of_month.php?EventId='+eId+'&newNeedVolStatus='+nStatus+'&txtSDt='+strtdt+'&txtEDt='+enddt;
    }
    function updateContactDisp(eId,nStatus)
    {
        var strtdt = document.frmEofMonth.txtSDt.value;
        var enddt = document.frmEofMonth.txtEDt.value;
        window.location = 'events_of_month.php?EventId='+eId+'&newContactDisp='+nStatus+'&txtSDt='+strtdt+'&txtEDt='+enddt;
    }
    function updatewidgetdisp(eId,nStatus)
    {
        var strtdt = document.frmEofMonth.txtSDt.value;
        var enddt = document.frmEofMonth.txtEDt.value;
        window.location = 'events_of_month.php?EventId='+eId+'&newwidgetdisp='+nStatus+'&txtSDt='+strtdt+'&txtEDt='+enddt;
    }
	 function updatenodiscountp(eId,nStatus)
    {
        var strtdt = document.frmEofMonth.txtSDt.value;
        var enddt = document.frmEofMonth.txtEDt.value;
        window.location = 'events_of_month.php?EventId='+eId+'&newnodiscount='+nStatus+'&txtSDt='+strtdt+'&txtEDt='+enddt;
    }
    function updatefbcomment(eId,fbstatus)
    {
    var strtdt = document.frmEofMonth.txtSDt.value;
        var enddt = document.frmEofMonth.txtEDt.value;
        window.location = 'events_of_month.php?EventId='+eId+'&newfbcomment='+fbstatus+'&txtSDt='+strtdt+'&txtEDt='+enddt;
    }
    
    function updatebgimg(eId)
    {
      var strtdt = document.frmEofMonth.txtSDt.value;
        var enddt = document.frmEofMonth.txtEDt.value;
        window.location = 'events_of_month.php?EventId='+eId+'&bkimg=1&txtSDt='+strtdt+'&txtEDt='+enddt;    
    }    
</script>
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
