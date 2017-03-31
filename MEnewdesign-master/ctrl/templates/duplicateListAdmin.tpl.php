<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - Event Search Management</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>
    <script>	
    function EventTrans(EventId)
	{
	
	window.location="duplicateListAdmin.php?EventId="+EventId;
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
<!-------------------------------DISPLAY ALL EVENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans4').style.display='block';
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.min.js.gz"></script>
<div align="center" style="width:100%">

	<table width="100%" >
        <tr>
        <td colspan="2" align="left" class="headtitle">  <div >Select an Event <select name="EventId" id="EventId" onChange="EventTrans(this.value);">
        <option value="">Select Event</option>
        <?
		$TotalEventQueryRES = count($EventQueryRES);

		for($i=0; $i < $TotalEventQueryRES; $i++)
		{
		?>
         <option value="<?=$EventQueryRES[$i]['Id'];?>" <? if($EventQueryRES[$i]['Id']==$_REQUEST[EventId]){?> selected="selected" <? }?>><?=$EventQueryRES[$i]['Title'];?></option>
         <? }?>
      </select>
      </div> </td> 
      </tr>
      <tr><td>&nbsp;</td></tr>
      <tr>
        <td colspan="2" align="left">
           
                        
        
        </td>
      </tr>
      <tr><td colspan="2">
      <table width="100%" align="center" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-3 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating">
		
			<tr bgcolor="#ebeae9">
				<td  width="10%" align="center">Receipt No.</td>
				<td  width="15%" align="center">Date</td>
				<td  width="10%" align="center">Transaction / Cheque No.</td>
                <td  width="10%" align="center">Promotion Code</td>
				<td  width="20%" align="center">Name</td>
				<td  width="20%" align="center">Email</td>
				<td  width="10%" align="center">Phone No.</td>
				<td  width="10%" align="center">Amount</td>
               <td  width="10%" align="center">Action</td>
			</tr>
		
		<?php 	
                if(count($Resduplist) > 0){
		for($cou = 0; $cou < count($Resduplist); $cou++)
		{ 
		  if($Resduplist[$cou]['PaymentModeId']==2){
		$selChqPay= "SELECT EventSignupId, ChqNo, ChqDt, ChqBank, Cleared FROM ChqPmnts WHERE EventSignupId='".$Resduplist[$cou]['Id']."'";
				$dtlCP = $Global->SelectQuery($selChqPay);
			  $PaymentTransId = $dtlCP[0]['ChqNo'];
			  }else{
			   $PaymentTransId = $Resduplist[$cou]['PaymentTransId'];
			  }

		?>
			<tr>
				<td  valign="top" bgcolor="#f0efee" height="30"><font color="#000000"><?php echo $Resduplist[$cou]['Id']; ?></font></td>
				<td  valign="top" bgcolor="#f5f4f3"><font color="#000000"><?php echo $Resduplist[$cou]['SignupDt']; ?></font></td>
                <td  valign="top" bgcolor="#f0efee"><font color="#000000"><?php echo $PaymentTransId; ?></font></td>
                <td  valign="top" bgcolor="#f0efee"><font color="#000000"><?php echo $Resduplist[$cou]['PromotionCode']; ?></font></td>
				<td  valign="top" bgcolor="#f5f4f3"><font color="#000000"><?php echo $Resduplist[$cou]['Name']; ?></font></td>
				<td  valign="top" bgcolor="#f0efee"><font color="#000000"><?php echo $Resduplist[$cou]['EMail']; ?></font></td>
				<td  valign="top" bgcolor="#f0efee"><font color="#000000"><?php echo $Resduplist[$cou]['Phone']; ?></font></td>
				<td  valign="top" bgcolor="#f5f4f3"><font color="#000000"><?php echo $Resduplist[$cou]['Qty'] * $Resduplist[$cou]['Fees']; ?></font></td>
                <td  valign="top" bgcolor="#f5f4f3" align="center"><a class="lbOn" href="<?=_HTTP_SITE_ROOT?>/duplicateListEdit.php?attend=<?=$Resduplist[$cou]['Id'];?>">Edit</a> &nbsp; <a href="<?=_HTTP_SITE_ROOT?>/admin/duplicateListAdmin.php?del=<?=$Resduplist[$cou]['Id'];?>&EventId=<?=$_REQUEST['EventId'];?>">Delete</a></td>
			</tr>
          <?php  
		  } }else { ?>
                            <tr>
				<td  valign="top" align="center" bgcolor="#f0efee" colspan="11" height="30"><font color="#000000">No Dupliactes</font></td>
				
			</tr>
                     <tr>
				<td  valign="top"  colspan="11"><p>&nbsp;</p></td>				
			</tr>
                  <? }  ?>
      </table>
      </td>
      </table>
  
</div>
<!-------------------------------DISPLAY ALL EVENT PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>
<script type="text/javascript" src="<?=_HTTP_SITE_ROOT?>/lightbox/prototype.min.js.gz"></script>
  <script type="text/javascript" src="<?=_HTTP_SITE_ROOT?>/lightbox/lightbox.min.js.gz"></script>
	<link type="text/css" rel="stylesheet" href="<?=_HTTP_SITE_ROOT?>/lightbox/lightbox.min.css.gz" media="screen,projection" />