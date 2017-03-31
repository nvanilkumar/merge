<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents.com - Admin Panel - Manage Banner</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.css" rel="stylesheet" type="text/css" media="all" />	
        <link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
        <script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>	
	<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.js"></script>
    <script src="<?=_HTTP_SITE_ROOT ?>/js/public/jQuery.js"></script>
    <script type="text/javascript" language="javascript">
        $(document).ready(function(){
           $('#frmAddDocs').submit(function(e){
               e.preventDefault();
               var AmountP=this.AmountP.value;
               var PType=this.PType.value;
               if(validateForm(this)){
                   if(PType=='Done'){
                        $.ajax({
                            url:'<?php echo _HTTP_SITE_ROOT;?>/ctrl/ajax.php',
                            type:'POST',
                            data:'call=checkVerifiedAmt&AmountP='+AmountP+'&EventId=<?=$EventId?>',
                            success:function(res){
                                 var newRes=$.parseJSON(res);
                                 if(!newRes.status){
                                     if(window.confirm("Are you sure you want to enter amount less than verfied amount and set payment type to Done?"))
                                         $('#frmAddDocs').unbind().submit();
                                         //window.location.href=("<?=_HTTP_SITE_ROOT?>/ctrl/paymentdocs.php?EventId=<?=$EventId;?>");
                                 }else{
                                     $('#frmAddDocs').unbind().submit();
                                     //window.location.href=("<?=_HTTP_SITE_ROOT?>/ctrl/paymentdocs.php?EventId=<?=$EventId;?>");
                                 }
                            }

                         });
                    }else{
                                     $('#frmAddDocs').unbind().submit();
                                     //window.location.href=("<?=_HTTP_SITE_ROOT?>/ctrl/paymentdocs.php?EventId=<?=$EventId;?>");
                                 }
               }
           });
        });
        
        
	function validateForm(f)
	{
		var padvice = f.padvice.value.toLowerCase();
		var pdone = f.pdone.value.toLowerCase();
		var cyber = f.cyber.value.toLowerCase();
		
		
		
		if(padvice.length>0)
		{
			var padviceExt=getFileExtension(padvice);
			if(padviceExt == 'pdf' || padviceExt == 'jpg' || padviceExt == 'jpeg' || padviceExt == 'gif' || padviceExt == 'png' || padviceExt == 'doc' || padviceExt == 'docx')
			{
			}
			else
			{
				alert("Invalid file type for Payment Advice.\n Allowed file types are: pdf, images and documents");
				return false;
			}
		}
		
		if(pdone.length>0)
		{
			var pdoneExt=getFileExtension(pdone);
			if(pdoneExt=='pdf' || pdoneExt=='jpg' || pdoneExt=='jpeg' || pdoneExt=='gif' || pdoneExt=='png' || pdoneExt=='doc' || pdoneExt=='docx')
			{
			}
			else
			{
				alert("Invalid file type for Cheque.\n Allowed file types are: pdf, images and documents");
				return false;
			}
		}
		
		if(cyber.length>0)
		{
			var cyberExt=getFileExtension(cyber);
			if(cyberExt=='pdf' || cyberExt=='jpg' || cyberExt=='jpeg' || cyberExt=='gif' || cyberExt=='png' || cyberExt=='doc' || cyberExt=='docx')
			{
			}
			else
			{
				alert("Invalid file type for Cyber Reciept.\n Allowed file types are: pdf, images and documents");
				return false;
			}
		}else{
                    alert("Please upload cyber reciept");
                    return false;
                }
                var pmode=f.pmode.value;
                if(pmode.trim()==""){
                    alert("Please enter payment mode");
                    return false;
                }
		var PDate=f.PDate.value;
		if(PDate.trim()==""){
                    alert("Please enter date");
                    return false;
                }
                var PType=f.PType.value;
                if(PType==""){
                    alert("Please select payment type");
                    return false;
                }
		var AmountP=f.AmountP.value;
		 var pattern = /^-?[0-9]+(.[0-9]{1,2})?$/;
          if (AmountP.match(pattern)==null)
		  {
			 alert("Please enter valid amount");
                    return false; 
		  }else{
			  var status=true;
                    return status; 
		  }               
	}
	
	
	function getFileExtension(filename)
	{
		return filename.split('.').pop();
	}
	</script>
    
    <style>
        .mandatory{
            color: red;
        }
    </style>
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
					<div align="center" style="width:100%"><a href="TransbyEvent_new.php?EventId=&SerEventName=<?=$_REQUEST[SerEventName];?>&Status=<?=$_REQUEST[Status];?>&txtSDt=<?=$_REQUEST[txtSDt];?>&txtEDt=<?=$_REQUEST[txtEDt];?>&compeve=<?=$_REQUEST[compeve];?>">Back to Gateway Transactions</a></div>
					<div id="add_image">
                                            <form name="frmAddDocs" id="frmAddDocs" action="" method="post" enctype="multipart/form-data">
                                                <input type="hidden" name="addDocs" value="1" />
						<table width="90%" cellpadding="1" cellspacing="2">
							<tr>
								<td colspan="2"><strong>Add Docs (Allowed file types are 'images/pdf/documents' only)</strong></td>
						  </tr>
							<tr>
								<td colspan="2">&nbsp;</td>
						  </tr>

						<tr>
								<td width="23%">
									<b>Payment Advice :</b>								</td>
				  <td width="77%">
					<input type="file" name="padvice" id="padvice" maxlength="100" title="Size should be 772 px X 200 px" size="50" />	</td>
						  </tr>
                            <tr>
								<td width="23%">
									<b>Cheque :</b>								</td>
						  <td width="77%">
							<input type="file" name="pdone" id="pdone" maxlength="100" title="Size should be 772 px X 200 px" size="50" />	</td>
						  </tr>
                          
                          
                          <tr>
								<td width="23%">
                                                                    <b>Cyber Receipt:<span class="mandatory"> * </span></b>								</td>
						  <td width="77%">
							<input type="file" name="cyber" id="cyber" maxlength="100" title="Size should be 772 px X 200 px" size="50" />	</td>
						  </tr>
                          
                          
                          
							<tr>
								<td width="23%">
									<b>Payment mode (note):<span class="mandatory"> * </span></b>								</td>
						  <td width="77%">
							<textarea name="pmode" id="pmode" rows="5" cols="50"></textarea>						</td>
						  </tr>
                          <tr>
								<td width="23%">
									<b>Net Amount:</b>								</td>
						        <td width="77%">
							<input type="text" name="NetAmount" id="NetAmount" value="" />						</td>
						  </tr>
                           <tr>
								<td width="23%">
									<b>  Amount Paid:<span class="mandatory"> * </span></b>								</td>
						        <td width="77%">
								<input type="text" name="AmountP" id="AmountP" value="" />							</td>
						  </tr>
						  <tr>
						  <td><b>Currency</b></td>
						  <td>
						  <select name="ccode" id="ccode">
						  <option value="0">Select</option>
						  <?php
						  for($i=0;$i<count($currencyRes);$i++){ ?>
						  <option value="<?php echo $currencyRes[$i]['id'] ;?>"><?php echo $currencyRes[$i]['code'] ;?></option>
							  
							  
						  <?php 
						  }
						  ?>
						  </select>
						  </td>
						  </tr>
                                                    <tr>
								<td width="23%">
									<b>  Payment Date:<span class="mandatory"> * </span></b>								</td>
						        <td width="77%">
                                                            <input type="text" name="PDate" id="PDate" value="" onfocus="showCalendarControl(this);" />							</td>
						  </tr>
                                                    <tr>
								<td width="23%">
									<b>  Payment Type:<span class="mandatory"> * </span></b>								</td>
						        <td width="77%">
                                                            <select name="PType" id="PType">
                                                                <option value="">Select</option>
                                                                <option value="Partial Payment">Partial Payment</option>
                                                                <option value="Done">Done</option>
                                                            </select>							</td>
						  </tr>
							<tr>
								<td width="23%">&nbsp;</td>
							  <td width="77%"><input type="Submit" name="Submit" value="Upload" />&nbsp;&nbsp;<input type="button" value="Cancel" onclick="javascript: window.location='paymentdocs.php?EventId=<?php echo $EventId; ?>'" /></td>
						  </tr>
							<tr>
								<td colspan="2">&nbsp;</td>
						  </tr>							
						</table>
						
					</form>
					</div>
					
<!-------------------------------Manage Banner PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		  </tr>
		</table>
	</div>	
</body>
</html>
	