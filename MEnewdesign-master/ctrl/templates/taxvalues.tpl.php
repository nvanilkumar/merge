<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - Currency Management</title>
	<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
	<script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo  _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
    <script>
	function validateForm(f)
	{
            var taxVal=jsTrim(f.taxVal.value);           
            
            if (f.taxId.value == '0' || f.taxId.value == ""){
                alert("Please select tax label");
                f.taxId.focus();
                return false;
            }
            if (f.taxType.value == '0' || f.taxType.value == ""){
                alert("Please select tax level");
                f.taxType.focus();
                return false;
            }
            if(taxVal.length=='0' || taxVal==null)
            {
                alert("Please enter tax value");
                f.taxVal.focus();
                return false;
            }
            else if(!IsNumeric(taxVal))
            {
                alert("Please enter valid Tax value");
                f.taxVal.focus();
                return false;
            }
            switch(f.taxType.value) {
                case "country":
                    if (f.frmCountryId.value == '0' || f.frmCountryId.value == ""){
                        alert("Please select country");
                        f.frmCountryId.focus();
                        return false;
                    }
                    break;
                case "state":
                    if (f.frmCountryId.value == '0' || f.frmCountryId.value == ""){
                        alert("Please select country");
                        f.frmCountryId.focus();
                        return false;
                    }
                    if (f.frmStateId.value == '0' || f.frmStateId.value == ""){
                        alert("Please select state");
                        f.frmStateId.focus();
                        return false;
                    }
                    break;                  
                case "city":
                    if (f.frmCountryId.value == '0' || f.frmCountryId.value == ""){
                        alert("Please select country");
                        f.frmCountryId.focus();
                        return false;
                    }
                    if (f.frmStateId.value == '0' || f.frmStateId.value == ""){
                        alert("Please select state");
                        f.frmStateId.focus();
                        return false;
                    }
                    if (f.frmCityId.value == '0' || f.frmCityId.value == ""){
                        alert("Please select city");
                        f.frmCityId.focus();
                        return false;
                    }                        
                    break;
                default:
                    if (f.frmCountryId.value == '0' || f.frmCountryId.value == ""){
                        alert("Please select country");
                        f.frmCountryId.focus();
                        return false;
                    }
                    if (f.frmStateId.value == '0' || f.frmStateId.value == ""){
                        alert("Please select state");
                        f.frmStateId.focus();
                        return false;
                    }
                    if (f.frmCityId.value == '0' || f.frmCityId.value == ""){
                        alert("Please select city");
                        f.frmCityId.focus();
                        return false;
                    }
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
	
        
        // Change state/city values	
        <?php 
            if(isset($srchStateId)){ 
                
                ?>
                window.onload = getState(<?php echo "'".$srchCountryId."'"; ?>, 'srchStateId', <?php echo $srchStateId; ?>);
                
                
          <?php  }
        
        ?>
	function getState(country, stateTagid, stateId)
	{
            if (!$("#"+stateTagid).is(':disabled')) {
                $.ajax({
                    url: "<?php echo _HTTP_SITE_ROOT; ?>/ctrl/ajax.php",
                    type: "POST",
                    data: "call=getStates&countryId="+country,
                    success: function (msg) {
                        if (msg == "ERROR!") {
                            alert("No States!");
                        }
                        else {
                             $("#"+stateTagid).html(msg);
                             if(stateId > 0){
                                 $("#"+stateTagid).val(stateId);
                             }
                         }
                    }
                });
            }
	}
        function getCity(state,cityTagid)
	{
            if (!$("#"+cityTagid).is(':disabled')) {
                $.ajax({
                    url: "<?php echo _HTTP_SITE_ROOT; ?>/ctrl/ajax.php",
                    type: "POST",
                    data: "call=getCities&stateId="+state,
                    success: function (msg) {
                        if (msg == "ERROR!") {
                            alert("No Cities!");
                        }
                        else {
                            $("#"+cityTagid).html(msg);
                        }
                    }
                });
            }
	}
	
        //
        function setLocValues(val) {
            switch(val) {
                case "country":
                    $('#frmStateId').attr('disabled', 'disabled');
                    $('#frmCityId').attr('disabled', 'disabled');
                    break;
                case "state":
                    if ($("#frmStateId").is(':disabled')) {                      
                        $('#frmStateId').removeAttr('disabled');
                    }
                    $('#frmCityId').attr('disabled', 'disabled');
                    break;                  
                case "city":
                    if ($("#frmStateId").is(':disabled')) {                      
                        $('#frmStateId').removeAttr('disabled');
                    }                    
                    if ($("#frmCityId").is(':disabled')) {                      
                        $('#frmCityId').removeAttr('disabled');
                    }
                    break;
                default:                    
                    if ($("#frmStateId").is(':disabled')) {                      
                        $('#frmStateId').removeAttr('disabled');
                    }                    
                    if ($("#frmCityId").is(':disabled')) {                      
                        $('#frmCityId').removeAttr('disabled');
                    }
            }
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
  	document.getElementById('anstax').style.display='block';
</script>
<div align="center" style="width:100%">



<table width="60%" border="0" cellpadding="3" cellspacing="3">
	<tr>
        <td align="center" colspan="2" valign="middle" class="headtitle"><strong>Tax Values</strong> </td>
    </tr>
    
    <tr>
    	<td colspan="2">
            <fieldset style="width:85% ">
        <legend>Add Tax Value</legend>
        <form method="post" action="" onsubmit="return validateForm(this)">
            <table width="100%">
                <tr>
                    <td>Tax Label</td>
                    <td>
                        <select name="taxId" id="taxId">
                        <?php
                        if($TDataCount>0) {
                            for($st = 0; $st < $TDataCount; $st++) { ?>
                            <option value="<?php echo $TData[$st]['id'];?>"><?php echo $TData[$st]['label'];?></option>    
                        <?php    }
                        }
                        ?>
                        </select>
                    </td>                    
                </tr>
                <tr>
                    <td>Tax Type</td>
                    <td>
                        <select name="taxType" id="taxType" onchange="setLocValues(this.value)">
                            <option value="country">Country</option>
                            <option value="state">State</option>
                            <option value="city">City</option>
                        </select>
                    </td>                    
                </tr>
            	<tr>
                    <td>Tax Value</td>
                    <td><input type="text" name="taxVal" id="taxVal" /></td>
                </tr>                
                <tr>
                    <td>Country</td>
                    <td>
                        <select name="frmCountryId" id="frmCountryId" onchange="getState(this.value,'frmStateId',0)" >
                            <option value="">-Select country-</option>
                        <?php
                        if($countryDataCount>0) {
                            for($st = 0; $st < $countryDataCount; $st++) { ?>
                            <option value="<?php echo $countryData[$st]['id'];?>" ><?php echo $countryData[$st]['name'];?></option>    
                        <?php    }
                        } ?>
                        </select>
                    </td>                    
                </tr>                               
                <tr>
                    <td>State</td>
                    <td>
                        <select name="frmStateId" id="frmStateId" onchange="getCity(this.value,'frmCityId')" disabled="disabled">
                            <option value="">-Select state-</option>
                        <?php
                        if($stateDataCount>0) {
                            for($st = 0; $st < $stateDataCount; $st++) { ?>
                            <option value="<?php echo $stateData[$st]['id'];?>" ><?php echo $stateData[$st]['name'];?></option>    
                        <?php    }
                        } ?>
                        </select>
                    </td>                    
                </tr>                             
                <tr>
                    <td>City</td>
                    <td>
                        <select name="frmCityId" id="frmCityId" disabled="disabled">
                            <option value="">-Select city-</option>
                        <?php
                        if($cityDataCount>0) {
                            for($st = 0; $st < $cityDataCount; $st++) { ?>
                            <option value="<?php echo $cityData[$st]['id'];?>" <?php if ($cityData[$st]['id']==$_REQUEST['srchCityId']) {?>selected='selected' <?php }?> ><?php echo $cityData[$st]['name'];?></option>    
                        <?php    }
                        } ?>
                        </select>
                    </td>                    
                </tr>
                
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td>
                        <input type="hidden" name="addTaxForm"  value="1" />
                    </td>
                    <td>
                        <input type="submit" name="currFrmSub"  value="ADD" />
                    </td>
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
                          <td>
                              <form method="post" action="taxvalues.php">
                                  <table width="100%">
                                  <tr>
                                        <td>
                                            <select name="srchtaxId" id="srchtaxId">
                                                <option value="">-Select tax-</option>
                                            <?php
                                            if($TDataCount>0) {
                                                for($st = 0; $st < $TDataCount; $st++) { ?>
                                                <option value="<?php echo $TData[$st]['id'];?>" <?php if ($TData[$st]['id']==$srchtaxId) {?>selected='selected' <?php }?> ><?php echo $TData[$st]['label'];?></option>    
                                            <?php    }
                                            } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="srchCountryId" id="srchCountryId" onchange="getState(this.value,'srchStateId',0)" >
                                                <option value="">-Select country-</option>
                                            <?php
                                            if($countryDataCount>0) {
                                                for($st = 0; $st < $countryDataCount; $st++) { ?>
                                                <option value="<?php echo $countryData[$st]['id'];?>" <?php if ($countryData[$st]['id']==$srchCountryId) {?>selected='selected' <?php }?> ><?php echo $countryData[$st]['name'];?></option>    
                                            <?php    }
                                            } ?>
                                            </select>
                                        </td> 
                                  </tr>
                                  <tr>                                   
                                        <td>
                                            <select name="srchStateId" id="srchStateId" onchange="getCity(this.value,'srchCityId')">
                                                <option value="">-Select state-</option>
                                            <?php
                                            if($stateDataCount>0) {
                                                for($st = 0; $st < $stateDataCount; $st++) { ?>
                                                <option value="<?php echo $stateData[$st]['id'];?>" <?php if ($stateData[$st]['id']==$_REQUEST['srchStateId']) {?>selected='selected' <?php }?> ><?php echo $stateData[$st]['name'];?></option>    
                                            <?php    }
                                            } ?>
                                            </select>
                                        </td>

                                        <td>
                                            <select name="srchCityId" id="srchCityId">
                                                <option value="">-Select city-</option>
                                            <?php
                                            if($cityDataCount>0) {
                                                for($st = 0; $st < $cityDataCount; $st++) { ?>
                                                <option value="<?php echo $cityData[$st]['id'];?>" <?php if ($cityData[$st]['id']==$_REQUEST['srchCityId']) {?>selected='selected' <?php }?> ><?php echo $cityData[$st]['name'];?></option>    
                                            <?php    }
                                            } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="center" valign="middle">
                                            <input type="submit" name="srchSubmit" value="Show" />
                                        </td>
                                    </tr>
                              </table>
                            </form>
                          </td>
                      </tr>
                      <tr>
                        <td valign="middle" class="headtitle">&nbsp</td>
                      </tr>
                        <td><table width="100%" class="sortable" >
                          <thead>
                          <tr>
                            <td class="tblcont1">Sno</td>
                            <td class="tblcont1">Level</td>
                            <td class="tblcont1">Value</td>
                            <td class="tblcont1">Country</td>
                            <td class="tblcont1">State</td>
                            <td class="tblcont1">City</td>
                            <td class="tblcont1">Tax Type</td>
                            <td class="tblcont1">Status</td>
                          </tr></thead>
                        <?php						
						if($taxMappingDataCount>0)
						{	
							$stsno=1;						  
							for($st = 0; $st < $taxMappingDataCount; $st++)
							{
							?>
							  <tr>
								<td class="helpBod"><?php echo $stsno; ?></td>
								<td class="helpBod"><?php echo $taxMappingData[$st]['taxlevel']; ?></td>
								<td class="helpBod"><?php echo $taxMappingData[$st]['taxvalue']; ?>%</td>
								<td class="helpBod"><?php echo $taxMappingData[$st]['countryname']; ?></td>
								<td class="helpBod"><?php echo getStateName($Global, $taxMappingData[$st]['stateid'],0); ?></td>
								<td class="helpBod"><?php echo getCityName($Global, $taxMappingData[$st]['cityid']); ?></td>
                                                                <td class="helpBod"><?php echo $taxMappingData[$st]['label'];?></td>
								<td class="helpBod"><?php  $statustext= ($taxMappingData[$st]['status'] == "1" ) ?"Default":"<a href='taxvalues.php?&taxmapstatus=".$taxMappingData[$st]['id']."'> Make Default </a>";
                                                                
                                                                ?>
                                                                    
                                                                    <?php echo $statustext;?>
                                                                   
                                                                </td>
							  </tr>
							<?php 
							$stsno++;
							}   
						}
						else
						{
							?> <tr><td colspan="6"><b style="color:#C30">Sorry, No reocrds found.</b></td></tr><?php
						}
                        ?>
                        </table></td>
                      </tr>
                      
                    </table>
        </td>
    </tr>
    
    
    
    <tr><td colspan="2"><br /></td></tr>
    
    
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