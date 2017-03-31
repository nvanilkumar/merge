<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - Currency Management</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>
    <script src="<?=_HTTP_CF_ROOT;?>/js/public/jQuery.js"></script>
    <script>
	function validateForm(f)
	{
		var taxVal=jsTrim(f.taxVal.value);
		var taxType=jsTrim(f.taxType.value);
		
		var errCount=0;
		
		if(taxVal.length==0 || taxVal==null)
		{
			alert("Please enter Tax value");
			f.taxVal.focus();
			return false;
		}
		else if(!IsNumeric(taxVal))
		{
			alert("Please enter valid Tax value");
			f.taxVal.focus();
			return false;
		}
		
		
		
		return true;
	}
	
	function delConfirmation()
	{
		return window.confirm("Are you sure want to delete this record..?");
	}
	
	
	function jsTrim(str) {
		str = str.toString();
		var begin = 0;
		var end = str.length - 1;
		while (begin <= end && str.charCodeAt(begin) < 33) { ++begin; }
		while (end > begin && str.charCodeAt(end) < 33) { --end; }
		return str.substr(begin, end - begin + 1);
	}
	
	function IsNumeric(num) {
		return !isNaN(parseFloat(num)) && num >=0;
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
				<div id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------CITY LIST PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans2').style.display='block';
</script>
<div align="center" style="width:100%">



<table width="60%" border="0" cellpadding="3" cellspacing="3">
	<tr>
        <td align="center" colspan="2" valign="middle" class="headtitle"><strong>Currencies</strong> </td>
    </tr>
    
    
    <?php
	if(isset($_SESSION['successMsg']))
	{
		?><tr><td colspan="2" valign="middle" class="headtitle"><b style="color:#090"><?php echo $_SESSION['successMsg']; ?></b> </td></tr><?php
		unset($_SESSION['successMsg']);
	}
	if(isset($_SESSION['currencyDel']))
	{
		?><tr><td colspan="2" valign="middle" class="headtitle"><b style="color:#090">Currency details deleted successfully..</b> </td></tr><?php
		unset($_SESSION['currencyDel']);
	}
	?>
    
    <tr>
    	<td colspan="2">
        <fieldset>
        <legend>Add Tax</legend>
        <form method="post" action="" onsubmit="return validateForm(this)">
        	<table>
            	<tr><td>Tax Value</td><td>Tax Type</td></tr>
                <tr>
                	<td><input type="text" name="taxVal" id="taxVal" /></td>
                    <td><select name="taxType" id="taxType"><option value="ServiceTax">Service Tax</option><option value="EntertainmentTax">Entertainment Tax</option></select></td>
                    <td><input type="hidden" name="addTaxForm"  value="1" /><input type="submit" name="currFrmSub"  value="ADD" /></td>
                </tr>
            </table>
        </form>
        </fieldset>
        
        </td>
    </tr>
    
    
    <tr><td colspan="2"><br /></td></tr>
    <tr>
    	<td colspan="2">
        	
                <table width="60%" border="0" cellpadding="5" cellspacing="5">
                      <tr>
                        <td  colspan="2" valign="middle" class="headtitle"><strong>Service Tax</strong> </td>
                      </tr>
                      
                      <tr>
                        <td colspan="2"><table width="100%" class="sortable" >
                          <thead>
                          <tr>
                            <td class="tblcont1">Sno</td>
                            <td class="tblcont1">Value</td>
                            <td class="tblcont1">Status</td>
                            <td class="tblcont1" ts_nosort="ts_nosort">Options</td>
                            <!--<td class="tblcont1" ts_nosort="ts_nosort">Delete</td>-->
                          </tr></thead>
                        <?php						
						if($STDataCount>0)
						{	
							$stsno=1;						  
							for($st = 0; $st < $STDataCount; $st++)
							{
							?>
							  <tr>
								<td class="helpBod"><?php echo $stsno; ?></td>
								<td class="helpBod"><?php echo $STData[$st]['value']; ?></td>
								<td class="helpBod">
                                <?php
								if($STData[$st]['isdefault']==0)
								{
									?>
									<form action="" method="post">
                                    	<select onchange="this.form.submit()" name="isdefault">
                                        	<option value="1" <?php if($STData[$st]['isdefault']==1) {echo " selected"; } ?>>Default</option>
                                            <option value="0" <?php if($STData[$st]['isdefault']==0) {echo " selected"; } ?>>Not Default</option>
                                        </select>
                                        <input type="hidden" name="rid" value="<?php echo $STData[$st]['id']; ?>" />
                                        <input type="hidden" name="TaxType" value="STax" />
                                        <input type="hidden" name="TaxFormDefault" value="1" />
									</form>
                                    <?php
								}
								else{ echo "Default"; }
								?>
								</td>
								<td class="helpBod">
                                	<form action="" method="post" onsubmit="return delConfirmation()">
                                    	<input type="hidden" name="rid" value="<?php echo $STData[$st]['id']; ?>" />
                                        <input type="submit" name="delTax" value="Delete" />
                                    </form>
                                </td>
								<!--<td class="helpBod">
									<form action="" method="post" name="edit_form">
									<input type="hidden" name="delCurrency" value="<?php echo $CurrData[$i]['Id']; ?>" />
									<input type="submit" name="Submit" value="Delete" onClick="return confirm('Are You Sure You Want To Delete this Currency.\n\nThe Changes Cannot Be Undone');">	
									</form>
								</td>-->
							  </tr>
							<?php 
							$stsno++;
							}
						}
						else
						{
							?> <tr><td colspan="4"><b style="color:#C30">Sorry, No reocrds found.</b></td></tr><?php
						}
                        ?>
                        </table></td>
                      </tr>
                      
                    </table>
        </td>
    </tr>
    
    
    
    <tr><td colspan="2"><br /></td></tr>
    <tr>
    	<td colspan="2">
        	
                <table width="60%" border="0" cellpadding="5" cellspacing="5">
                      <tr>
                        <td  colspan="2" valign="middle" class="headtitle"><strong>Entertainment Tax</strong> </td>
                      </tr>
                      
                      <tr>
                        <td colspan="2"><table width="100%" class="sortable" >
                          <thead>
                          <tr>
                            <td class="tblcont1">Sno</td>
                            <td class="tblcont1">Value</td>
                            <td class="tblcont1">Status</td>
                            <td class="tblcont1" ts_nosort="ts_nosort">Options</td>
                            <!--<td class="tblcont1" ts_nosort="ts_nosort">Delete</td>-->
                          </tr></thead>
                        <?php						
						if($ETDataCount>0)
						{	
							$etsno=1;						  
							for($et = 0; $et < $ETDataCount; $et++)
							{
							?>
							  <tr>
								<td class="helpBod"><?php echo $etsno; ?></td>
								<td class="helpBod"><?php echo $ETData[$et]['value']; ?></td>
								<td class="helpBod">
                                <?php
								if($ETData[$et]['isdefault']==0)
								{
									?>
									<form action="" method="post">
                                    	<select onchange="this.form.submit()" name="isdefault">
                                        	<option value="1" <?php if($ETData[$et]['isdefault']==1) {echo " selected"; } ?>>Default</option>
                                            <option value="0" <?php if($ETData[$et]['isdefault']==0) {echo " selected"; } ?>>Not Default</option>
                                        </select>
                                        <input type="hidden" name="rid" value="<?php echo $ETData[$et]['id']; ?>" />
                                        <input type="hidden" name="TaxType" value="ETax" />
                                        <input type="hidden" name="TaxFormDefault" value="1" />
									</form>
                                    <?php
								}
								else{ echo "Default"; }
								?>
								</td>
								<td class="helpBod">
                                	<form action="" method="post" onsubmit="return delConfirmation()">
                                    	<input type="hidden" name="rid" value="<?php echo $ETData[$et]['id']; ?>" />
                                        <input type="submit" name="delTax" value="Delete" />
                                    </form>
                                </td>
								
							  </tr>
							<?php 
							$etsno++;
							}
						}
						else
						{
							?> <tr><td colspan="4"><b style="color:#C30">Sorry, No reocrds found.</b></td></tr><?php
						}
                        ?>
                        </table></td>
                      </tr>
                      
                    </table>
        </td>
    </tr>
    
</table>





<div align="center" style="width:100%">&nbsp;</div>
</div>
<!-------------------------------CITY LIST PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>