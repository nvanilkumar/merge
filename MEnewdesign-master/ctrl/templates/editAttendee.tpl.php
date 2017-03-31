<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - City Management</title>
    
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
    
    <script src="<?php echo  _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
        
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>
    <link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.min.css.gz" />
	<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.min.js.gz"></script>	
    <script type="text/javascript" language="javascript">
	/*function validateForm(f)
	{
		var esid=f.evtsignupid.value;
		if(esid.length=='' || esid==null)
		{
			alert("Please enter Event Signup ID");
			f.evtsignupid.focus();
			return false;
		}
		else
		{
			var res=0;
			var inputString='checkEventSignupId=1&id='+esid;
			$.ajax({
			  url: "<?=_HTTP_SITE_ROOT;?>/processAjaxRequests.php",
			  type:"post",
			  data:inputString,
			  cache: false,
			  async: false,
			  success: function(result){
				  if(result=='success')
				  {
					  return true;
				  }
				  else
				  {
					  res=1;
					  
				  }
			  }
			});
			
			if(res>0)
			{
				alert("Invalid Event Signup ID");
				f.evtsignupid.focus();
				return false;
			}
		}
		//return true;
	}*/
	
	function getCities(val,cityid)
    {
		$.get("<?= _HTTP_SITE_ROOT; ?>/getSCity2.php?AX=Yes&StateId="+val, function(data)
		{
			$("#"+cityid).html(data);
        });
	
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

<div align="center" style="width:100%">

<br /><br />

<form action="" method="post" name="edit_form" onsubmit="return validateForm(this)">
<table width="60%" border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td align="center" colspan="2" valign="middle" class="headtitle"><strong>Attendee Details Edit</strong> </td>
      </tr>
      <td>Event Signup Id </td><td><?php echo $esid; ?></td>
      <?php 
      
	 // if($commonFunctions->isSuperAdmin())
	//  {
//		 print_r($dataCF);
		 foreach($dataCF as $cfk=>$cfv)
		 {
			 $customFieldValue=$cfv['value'];
			 
			 if($cfv['EventCustomFieldType']=="")//if cutomfield is Textarea
			 {
				 ?>
                 <tr><td><?php echo $cfv['EventCustomFieldName']; ?> </td><td><textarea cols="40"  rows="3" id="<?php echo str_replace(" ", "_", preg_replace("/[^A-Za-z0-9\s\s+]/", "", $cfv['EventCustomFieldName'])); ?>"   name="<?php echo str_replace(" ", "_", preg_replace("/[^A-Za-z0-9\s\s+]/", "", $cfv['EventCustomFieldName'])); ?>"><?php echo $customFieldValue; ?></textarea></td></tr>
                 <?php
			 }
			 elseif($cfv['EventCustomFieldType']==3)//if cutomfield is radio button
			 {
				 $sql_selEvntCustFeldMultpleVal = "SELECT EventCustomFieldId, EventCustomFieldMultipleValues FROM eventcustomfieldsmultiplevalue WHERE EventCustomFieldId = '" . $cfv['Id'] . "' ORDER BY Id ASC";
                 $selEvntCustFeldMultplVal = $Global->SelectQuery($sql_selEvntCustFeldMultpleVal);

                 $TotalEvntCustFeldMultplVal = count($selEvntCustFeldMultplVal);
				 ?>
                 <tr><td><?php echo $cfv['EventCustomFieldName']; ?> </td><td>
                 <?php
                 for ($j = 0; $j < $TotalEvntCustFeldMultplVal; $j++) 
				 {
                 	?><input type="radio"  <?php if (trim($selEvntCustFeldMultplVal[$j]['EventCustomFieldMultipleValues'])==trim($customFieldValue)) {
                                                echo "checked";
                                            } ?>  id="<?php echo str_replace(" ", "_", preg_replace("/[^A-Za-z0-9\s\s+]/", "", $cfv['EventCustomFieldName'])); ?>[]" name="<?php echo str_replace(" ", "_", preg_replace("/[^A-Za-z0-9\s\s+]/", "", $cfv['EventCustomFieldName'])); ?>[]"   value="<?php echo $selEvntCustFeldMultplVal[$j]['EventCustomFieldMultipleValues']; ?>"  />&nbsp;<?php echo $selEvntCustFeldMultplVal[$j]['EventCustomFieldMultipleValues']; ?>&nbsp;
                     <?php
                 }
				 ?>
                 </td></tr>
                 <?php
                  
                                                        
			 }
			 elseif($cfv['EventCustomFieldType']==4)//if cutomfield is checkboxs
			 {
				 $customFieldValueEx=explode(" ",$customFieldValue);
				 
				 
				 $sql_selEvntCustFeldMultpleVal = "SELECT EventCustomFieldId, EventCustomFieldMultipleValues FROM eventcustomfieldsmultiplevalue WHERE EventCustomFieldId = '" . $cfv['Id'] . "' ORDER BY Id ASC";
                 $selEvntCustFeldMultplVal = $Global->SelectQuery($sql_selEvntCustFeldMultpleVal);

                 $TotalEvntCustFeldMultplVal = count($selEvntCustFeldMultplVal);
				 ?>
                 <tr><td><?php echo $cfv['EventCustomFieldName']; ?> </td><td>
                 <?php
                 for ($j = 0; $j < $TotalEvntCustFeldMultplVal; $j++)
				 {
					 
                     ?>
                     <input  type="checkbox" id="<?php echo str_replace(" ", "_", preg_replace("/[^A-Za-z0-9\s\s+]/", "", $cfv['EventCustomFieldName'])); ?>[]" <?php if (in_array(trim($selEvntCustFeldMultplVal[$j]['EventCustomFieldMultipleValues']), $customFieldValueEx)) {echo " checked ";} ?> name="<?php echo str_replace(" ", "_", preg_replace("/[^A-Za-z0-9\s\s+]/", "", $cfv['EventCustomFieldName'])); ?>[]"  value="<?php echo $selEvntCustFeldMultplVal[$j]['EventCustomFieldMultipleValues']; ?>"  />&nbsp;<?php echo $selEvntCustFeldMultplVal[$j]['EventCustomFieldMultipleValues']; ?><br />	
                    <?php
                 }
				 ?>
                 </td></tr>
                 <?php
                                                        
			 }
			 elseif($cfv['EventCustomFieldType']==5)//if cutomfield is Dropdown
			 { 
                 $sql_selEvntCustFeldMultpleVal = "SELECT EventCustomFieldId, EventCustomFieldMultipleValues FROM eventcustomfieldsmultiplevalue WHERE EventCustomFieldId = '" . $cfv['Id'] . "' ORDER BY Id ASC";
                 $selEvntCustFeldMultplVal = $Global->SelectQuery($sql_selEvntCustFeldMultpleVal);

                 $TotalEvntCustFeldMultplVal = count($selEvntCustFeldMultplVal);
				 ?>
                 <tr><td><?php echo $cfv['EventCustomFieldName']; ?> </td><td>
                 <?php
                 if ($cfv['EventCustomFieldName'] == 'Designation') 
				 {
					$SelectDesignatons = "SELECT Id,Designation FROM Designations ORDER BY Designation ASC"; 
    				$Designations = $Global->SelectQuery($SelectDesignatons);
                 	$TotalDesignations = count($Designations);
                    ?>
                    <select  id="<?= str_replace(" ", "_", preg_replace("/[^A-Za-z0-9\s\s+]/", "", $cfv['EventCustomFieldName'])) . $i; ?>" name="<?= str_replace(" ", "_", preg_replace("/[^A-Za-z0-9\s\s+]/", "", $cfv['EventCustomFieldName'])); ?>">
                        <option value="">Select Designation</option>
                        <?php
                        for ($iDeg = 0; $iDeg < $TotalDesignations; $iDeg++)
						{
                             ?>
                             <option value="<?= $Designations[$iDeg]['Id'] ?>" <?php if ($customFieldValue == $Designations[$iDeg]['Designation']) { ?> selected="selected" <?php } ?>><?= $Designations[$iDeg]['Designation'] ?></option>
                             <?php
                        }
                        ?>
                        </select>

                    <?php
                } 
				elseif ($cfv['EventCustomFieldName'] == 'State') 
				{
                    $States = $Global->SelectQuery("SELECT Id, State FROM States");
                    $TotalStates = count($States);
                    $cityDropdownId = "City";
                    ?>
                    <select  id="<?= str_replace(' ', '_', preg_replace("/[^A-Za-z0-9\s\s+]/", "", $cfv['EventCustomFieldName'])); ?>" name="<?= str_replace(' ', '_', preg_replace("/[^A-Za-z0-9\s\s+]/", "", $cfv['EventCustomFieldName'])); ?>" onChange="getCities(this.value,'<?= $cityDropdownId; ?>');">
                        <option value="">Select State</option>
                        <?php
                        for ($iStates = 0; $iStates < $TotalStates; $iStates++)
						{
                        	?>
                            <option value="<?= $States[$iStates]['Id'] ?>" <?php if ($customFieldValue == $States[$iStates]['State']) { ?> selected="selected" <?php } ?>><?= $States[$iStates]['State'] ?></option>
                            <?php
                        }
                        ?>
                        </select>

                    <?php
                } 
				elseif ($cfv['EventCustomFieldName'] == 'City') 
				{
                    $Cities = $Global->SelectQuery("SELECT Id, City FROM Cities");

                    $TotalCities = count($Cities);
                    ?>
                    <select  id="<?= str_replace(" ", "_", preg_replace("/[^A-Za-z0-9\s\s+]/", "", $cfv['EventCustomFieldName'])); ?>"  name="<?= str_replace(" ", "_", preg_replace("/[^A-Za-z0-9\s\s+]/", "", $cfv['EventCustomFieldName'])); ?>">
                         <option value="">Select City</option>
                         <?php
                         for ($iCities = 0; $iCities < $TotalCities; $iCities++) 
						 {
                              ?>
                              <option value="<?= $Cities[$iCities]['Id'] ?>" <?php if ($customFieldValue == $Cities[$iCities]['City']) { ?> selected="selected" <?php } ?>><?= $Cities[$iCities]['City'] ?></option>
                               <?php
                         }
                         ?>
                   </select>

                   <?php
                   } 
				   else 
				   {
                        ?>
                        <select  id="<?php echo str_replace(" ", "_", preg_replace("/[^A-Za-z0-9\s\s+]/", "", $cfv['EventCustomFieldName'])); ?>" name="<?php echo str_replace(" ", "_", preg_replace("/[^A-Za-z0-9\s\s+]/", "", $cfv['EventCustomFieldName'])); ?>">
                             <option value="" selected="selected">Select</option>
                             <?php
                             for ($jMult = 0; $jMult < $TotalEvntCustFeldMultplVal; $jMult++) 
							 {
                                 ?>
                                 <option value="<?php echo $selEvntCustFeldMultplVal[$jMult]['EventCustomFieldMultipleValues']; ?>" <?php if ($customFieldValue == $selEvntCustFeldMultplVal[$jMult]['EventCustomFieldMultipleValues']) { ?> selected="selected" <?php } ?>><?php echo $selEvntCustFeldMultplVal[$jMult]['EventCustomFieldMultipleValues']; ?></option>
                                  <?php
                             }
                             ?>
                        </select>
                        <?php
                     }
					 
                      ?>
                 </td></tr>
                 <?php                                  
			 }
			 else
			 {
			 ?>
             <tr><td><?php echo $cfv['EventCustomFieldName']; ?> </td><td><input type="text" 
                                                                                 name="<?php echo $cfv['Id']."-".$cfv['customfieldid']; ?>" 
                                                                                 id="<?php echo str_replace(" ", "_", preg_replace("/[^A-Za-z0-9\s\s+]/", "", $cfv['EventCustomFieldName'])); ?>" 
                                                                                 value="<?php echo $customFieldValue; ?>"  /></td></tr>
             <?php
			 }
		 }
	  ?>

      <tr><td align="right"><input type="hidden" name="AttendeeIdUp" value="<?php echo $aid; ?>" /><input type="hidden" name="esID" value="<?php echo $esid; ?>" /><input type="hidden" name="eventid" value="<?php echo $eventid; ?>" /><input type="submit" name="upEsignup" value="Update" /></tr>
      <?php
	  //}
	  ?>
    </table>
</form><br /><br />



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