<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>meraevents.com - Admin Panel - Manage Banner</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" rel="stylesheet" type="text/css" media="all" />	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
	<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.min.js.gz"></script>
    
    <script type="text/javascript" language="javascript">
	function validateForm(f)
	{
		var contractdoc = f.contractdoc.value.toLowerCase();
		
		
		
		
		if(contractdoc.length>0)
		{
			var contractdocExt=getFileExtension(contractdoc);
			if(contractdocExt == 'pdf' || contractdocExt == 'jpg' || contractdocExt == 'jpeg' || contractdocExt == 'gif' || contractdocExt == 'png' || contractdocExt == 'doc' || contractdocExt == 'docx')
			{
			}
			else
			{
				alert("Invalid file type for Contract document.\n Allowed file types are: pdf, images and documents");
				return false;
			}
		}
		
		
		
		
		
		return true;
		
		
	}
	
	
	function getFileExtension(filename)
	{
		return filename.split('.').pop();
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
					document.getElementById('ans6').style.display='block';
				</script>
			</td>
			<td style="vertical-align:top">
				<div id="divMainPage" style="margin-left:10px; margin-right:5px;">
<!-------------------------------Manage Banner PAGE STARTS HERE--------------------------------------------------------------->
					<div align="center" style="width:100%">&nbsp;</div>
					<div align="center" style="width:100%" class="headtitle">Contract Document</div>
					
					<div id="add_image">
                    <?php
					if(strlen($sqlCommId[0]['ContractDoc'])>0)
					{
                                           // print_r($_SESSION);
						if(isset($_SESSION['docUploaded']))
						{
							?><p style="color:#090; font-weight:bold; font-size:14px;">Contract document successfully updated for this event. To view <a href="<?php echo _HTTP_CF_ROOT.'/content/'.$sqlCommId[0]['ContractDoc']; ?>" target="_blank" title="Click to View the Document">Click</a> Here. <a href="events_commision.php">Back to</a> Event Commissions</p><?php
							unset($_SESSION['docUploaded']);
						}
						else
						{
						
						?><p style="color:#930; font-weight:bold;">You have already uploaded contract document for this event. To view <a href="<?php echo _HTTP_CF_ROOT.'/content/'.$sqlCommId[0]['ContractDoc']; ?>" target="_blank" title="Click to View the Document">Click</a> Here. <a href="events_commision.php">Back to</a> Event Commissions</p><?php
						}
					}
					else
					{
						
						?>
					<form name="frmAddBanner" action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm(this)">
						<table width="90%" cellpadding="1" cellspacing="2">
							<tr>
								<td colspan="2"><strong>Add Docs (Allowed file types are 'images/pdf/documents' only)</strong></td>
						  </tr>
							<tr>
								<td colspan="2">&nbsp;</td>
						  </tr>

						<tr>
							<td width="23%">
									<b>Contract Document :</b>								</td>
				  <td width="77%">
					<input type="file" name="contractdoc" id="contractdoc" />	</td>
						  </tr>
                            
                         
							<tr>
								<td width="23%">&nbsp;</td>
							  <td width="77%">
                              <input type="hidden" name="commId" value="<?php echo $commId; ?>" />
                              <input type="hidden" name="Upload" value="1" />
                              <input type="Submit" name="Submit" value="Upload" />&nbsp;&nbsp;<input type="button" value="Cancel" onclick="javascript: window.location='events_commision.php'" /></td>
						  </tr>
							<tr>
								<td colspan="2">&nbsp;</td>
						  </tr>							
						</table>
						
					</form>
                    <?php
					}
					?>
					</div>
					
<!-------------------------------Manage Banner PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		  </tr>
		</table>
	</div>	
</body>
</html>
	