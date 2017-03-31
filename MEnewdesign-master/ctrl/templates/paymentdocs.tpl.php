<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents.com - Admin Panel - Manage Banner</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.css" rel="stylesheet" type="text/css" media="all" />	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>	
	<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.js"></script>
    
    <script type="text/javascript" language="javascript">
	function delConfirmation()
	{
		return window.confirm("Are you sure want to delete this record..?\nIt will delete all the docuements containg with it.");
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
                <script language="javascript">
					document.getElementById('ans4').style.display='block';
				</script>
			</td>
			<td style="vertical-align:top">
				<div id="divMainPage" style="margin-left:10px; margin-right:5px;">
<!-------------------------------Manage Banner PAGE STARTS HERE--------------------------------------------------------------->
					<div align="center" style="width:100%">&nbsp;</div>
					<div align="center" style="width:100%" class="headtitle">Manage Docs</div>
					<div align="center" style="width:100%"><a href="TransbyEvent_new.php?EventId=<?=$_REQUEST[EventId];?>&SerEventName=<?=$_REQUEST[SerEventName];?>&Status=<?=$_REQUEST[Status];?>&txtSDt=<?=$_REQUEST[txtSDt];?>&txtEDt=<?=$_REQUEST[txtEDt];?>&compeve=<?=$_REQUEST[compeve];?>">Back to Gateway Transactions</a></div>
                    <p align="right"><input type="button" onclick="javascript: window.location='uploaddocs.php?EventId=<?php echo $EventId; ?>';" value="Add new Document" /></p>
                    
					<?php if(isset($_SESSION['docUploaded'])){ ?><div>Document Uploaded sucessfully.</div><br /><?php unset($_SESSION['docUploaded']); }?>
					<div id="add_image">
						<table  class="sortable" style="width:90%">
                        	<thead><tr><td class="tblcont1"><strong>Sno.</strong></td><td class="tblcont1">Payment Advice</td><td class="tblcont1">Cheque</td><td class="tblcont1">Cyber Reciept</td><td class="tblcont1">Payment mode</td><td class="tblcont1">Net Amount</td><td class="tblcont1">Amount Paid</td><td class="tblcont1">Payment Type</td><td class="tblcont1">Created On</td><td class="tblcont1">Current Status</td></tr></thead>
                            
                            <?php
							
							$totalRec=count($PaymentinfoRes);
							if($totalRec>0)
							{
								$sno=1;
								for($i=0;$i<$totalRec;$i++)
								{
									if($PaymentinfoRes[$i]['status']==0){$currStatus='Inactive';$stTitle="Click to Activate this record";}else{$currStatus='Active';$stTitle="Click to Inactivate this record";}
									
									if(strcmp($hostname,'www.meraevents.com')==0 || strcmp($hostname,'meraevents.com')==0)
									{
										if($PaymentinfoRes[$i]['Id']>4328){$serverPath=_HTTP_CF_ROOT;}else{$serverPath=_HTTP_SITE_ROOT;}
									}
									else
									{
										if($PaymentinfoRes[$i]['Id']>2537){$serverPath=_HTTP_CF_ROOT;}else{$serverPath=_HTTP_SITE_ROOT;}
									}
									
									?>
                                    <tr>
                                    	<td  class="helpBod"><?php echo $sno; ?></td>
                                        <td class="helpBod"><?php if($PaymentinfoRes[$i]['paymentadvicefileid']>0){ ?><a href="<?php echo CONTENT_CLOUD_PATH.$Global->GetSingleFieldValue("select path from file where id =".$PaymentinfoRes[$i]['paymentadvicefileid']); ?>" target="_blank">View</a><?php } ?></td>
                                        <td class="helpBod"><?php if($PaymentinfoRes[$i]['chequefileid']>0){ ?><a href="<?php echo CONTENT_CLOUD_PATH.$Global->GetSingleFieldValue("select path from file where id =".$PaymentinfoRes[$i]['chequefileid']); ?>" target="_blank">View</a><?php } ?></td>
                                        <td class="helpBod"><?php if($PaymentinfoRes[$i]['cyberrecieptfileid']>0){ ?><a href="<?php echo CONTENT_CLOUD_PATH.$Global->GetSingleFieldValue("select path from file where id =".$PaymentinfoRes[$i]['cyberrecieptfileid']); ?>" target="_blank">View</a> <?php } ?></td>
                                        <td class="helpBod"><?php echo $PaymentinfoRes[$i]['note']; ?></td>
                                        <td class="helpBod"><?php echo $PaymentinfoRes[$i]['netamount']; ?></td>
                                        <td class="helpBod"><?php echo $PaymentinfoRes[$i]['amountpaid']; ?></td>
                                        <td class="helpBod"><?php echo $PaymentinfoRes[$i]['paymenttype']; ?></td>
                                        <td class="helpBod">
                                             <?php 
                        echo $common->convertTime($PaymentinfoRes[$i]['cts'],DEFAULT_TIMEZONE,TRUE);
                        
                        ?>
                                           </td>
                                        

                                        <td class="helpBod">
                                        	<form method="post" action="">
                                            	<input type="hidden" name="eventid" value="<?php echo $EventId; ?>" />
                                                <input type="hidden" name="recid" value="<?php echo $PaymentinfoRes[$i]['id']; ?>" />
                                            	<select name="recStatus">
                                                    <option value="1" <?php if($PaymentinfoRes[$i]['status']==1){echo ' selected '; } ?>>Active</option>
                                                    <option value="0" <?php if($PaymentinfoRes[$i]['status']==0){echo ' selected '; } ?>>Inactive</option>
                                                </select>
                                                <input type="submit" value="Change" name="chnageStatus" />
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
									$sno++;
								}
								
							}
							else
							{
								?>
                                <tr><td colspan="9"><span style="color:#C60; font-weight:bold; font-size:16px;">Sorry, no payment documents found for this event</span></td></tr>
                                <?php
							}
							?>
                        </table>
					</div>
					
<!-------------------------------Manage Banner PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		  </tr>
		</table>
	</div>	
</body>
</html>
	