
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title>MeraEvents -Menu Content Management</title>
		<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css" />
        <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
        <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
<script language="javascript">
	function SEdt_validate()
	{
		var strtdt = document.frmEofMonth.txtSDt.value;
		var enddt = document.frmEofMonth.txtEDt.value;
		var SalesId = document.frmEofMonth.SalesId.value;
		
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

    
	var Tckwdz=document.getElementById('Tckwdz'+sId).value;
	var PaidBit=document.getElementById('PaidBit'+sId).value;
	var strtdt = document.frmEofMonth.txtSDt.value;
	var enddt = document.frmEofMonth.txtEDt.value;
    var SalesId = document.frmEofMonth.SalesId.value;
	var Eventid=document.frmEofMonth.Eventid.value;
	var eadd = document.frmEofMonth.eadd.value;
    var paid = document.frmEofMonth.paid.value;
 
window.location="eventchk.php?value=change&sId="+sId+"&Tckwdz="+Tckwdz+"&PaidBit="+PaidBit+"&txtSDt="+strtdt+"&txtEDt="+enddt+"&SalesId="+SalesId+"&Eventid="+Eventid+"&eadd="+eadd+"&paid="+paid;
	}

    </script>
</head>	
<body style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
	<?php include('templates/header.tpl.php'); ?>				

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


 

<?php echo $msg;?>



<table width="98%" border="0" cellpadding="3" cellspacing="3">
<tr><td colspan="2" align="center">
<table width="100%" border="1">
<tr><td width="52%" align="left">

<a href="eventchk.php?txtSDt=<?php echo $_REQUEST[txtSDt];?>&txtEDt=<?php echo $_REQUEST[txtEDt];?>&SalesId=<?php echo $_REQUEST[SalesId];?>&Eventid=<?php echo $_REQUEST['Eventid'];?>&eadd=<?php echo $_REQUEST[eadd];?>&paid=<?php echo $_REQUEST[paid];?>"><strong>Back to CheckEventInfo</strong></a></td>

<td width="48%"  align="left"><b><?php echo $ResEvent[0][Title];?></b><br/>
 <?php $sqlo="SELECT `name` AS FirstName,`company` AS Company, `email` AS Email, `mobile` AS Mobile FROM user where id=".$ResEvent[0]['UserID'];
         $r=$Global->SelectQuery($sqlo);
		 $org=$r[0]['FirstName']."<br/>".$r[0]['Company']."<br/>".$r[0]['Email']."<br/>".$r[0]['Mobile'];
         ?>
<?php echo $org;?></td></tr>
</table></td></tr>
<tr><td colspan="2" align="center">
<table width="100%" border="1">
  <?php
		$TotalindId = count($indId);

		for($i=0; $i < $TotalindId; $i++)
		{?>
		
		 <tr>
    <td width="60"  align="left"><?php echo $i+1;?></td>
    <td width="1121">
        <?php 
            $qrySalesName = "select `name` AS SalesName FROM salesperson WHERE id= '".$indId[$i]['QPid']."'";
            $byName = $Global->GetSingleFieldValue($qrySalesName);
         ?>
        <div><?php echo $indId[$i]['Comment'];?></div>
        <div align="right" style="color:#007788; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
            Posted on: <?php echo date('d F Y',strtotime($indId[$i]['PostedDt']));?> By <?php echo $byName;?>
        </div>
    </td>
  </tr>
	<?php	}
		?>
        </table></td></tr>
        <tr>
        <td colspan="2">
        <form action="" method="post">
        <input type="hidden" name="txtSDt" value="<?php echo $_REQUEST['txtSDt'];?>" />
        <input type="hidden" name="txtEDt" value="<?php echo $_REQUEST['txtEDt'];?>" />
        <input type="hidden" name="SalesId" value="<?php echo $_REQUEST['SalesId'];?>" />
        <input type="hidden" name="Eventid" value="<?php echo $_REQUEST['Eventid'];?>" />
        <input type="hidden" name="eadd" value="<?php echo $_REQUEST['eadd'];?>" />
        <input type="hidden" name="paid" value="<?php echo $_REQUEST['paid'];?>" />
        <input type="hidden" name="EventId" value="<?php echo $_REQUEST['EventId'];?>" />
        <table width="98%" border="0" cellpadding="3" cellspacing="3">
         <tr>
                  <td width="38" height="40" align="left">&nbsp;</td>
        </tr>
                <tr>
                  <td width="38" height="30" align="left">&nbsp;</td>
                <td width="178" height="30" align="left"><b>Comment :</b></td>
                <td width="1120" height="30" align="left"><textarea name="comment" rows="10" cols="30" id="comment" ><?php echo $txtName;?></textarea> 
				  <script language="javascript">
							var comment = new LiveValidation('comment');
							comment.add( Validate.Presence );
							
							</script></td>
  </tr>
  
<tr>
  <tr>
                  <td width="38" height="30" align="left">&nbsp;</td>
        <td width="178" height="30" align="left"><b>Sales person :</b></td>
        <td width="1120" height="30" align="left">
      <select name="qname" id="qname" >
        <option value="">Select</option>
        <?php
		$TotalSalesQueryRES = count($SalesQueryRES);

		for($i=0; $i < $TotalSalesQueryRES; $i++)
		{
		?>
         <option value="<?php echo $SalesQueryRES[$i]['SalesId'];?>" <?php if($SalesQueryRES[$i]['SalesId']==$_REQUEST['qname']){?> selected="selected" <?php }?>><?php echo $SalesQueryRES[$i]['SalesName'];?></option>
         <?php }?>
      </select>
    </label></td>
  </tr>
                             

                <tr>
                  <td height="35" colspan="3" align="center"><div>
				<div>
					<div align="center">
					
					      <input type="Submit" name="AddComment" value="Add Comment" id="signin_submit" />
					     
			        </div>
				</div>
			  </div></td>
            </tr>
               
      </table>
        </form>
        </td></tr>
      </table>
      

    
      
     

<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
</div>
</td>
</tr>
</table>
	
  </body>
</html>