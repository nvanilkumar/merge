<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - City Management</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
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
<!-------------------------------EDIT CITY PAGE STARTS HERE--------------------------------------------------------------->
<script type="text/javascript" language="javascript">
	function validate_city(){
	
		if(document.edit_city.EventId.value == ""){
			alert("Please Enter Event-Id");
			document.edit_city.EventId.focus();
			return false;
		}
		
		if(document.edit_city.Label.value == ''){
			alert("Please Enter Label Name");
			document.edit_city.Label.focus();
			return false;
		}
		if(document.edit_city.Amount.value == ''){
			alert("Please Enter Amount");
			document.edit_city.Amount.focus();
			return false;
		}else{
				
				 var numeric = document.edit_city.Amount.value;
				 //var regex  = /^\d+(?:\d{0,2})$/;
				 var regex  = /^(?:[1-9]\d*|0)?(?:\.\d+)?$/;
                 
                 if (!regex.test(numeric)){
                 alert("Please Enter Valid Amount");
                    document.edit_city.Amount.focus();
                    return false;
                  } 
			}
		
		return true;
	}
	function getCurrency(type) {
        if (type == 1) {
            document.getElementById('tr_currency').style.display = 'none';
        }
        else if (type == 2) {
            document.getElementById('tr_currency').style.display = '';
        }
    }
</script>

<form action="" method="post" name="edit_city" onSubmit="return validate_city();">
<input type="hidden" name="Id" value="<?=$Id;?>" />
<table width="50%" border="0" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="2"><strong>Edit City </strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Event-Id : </td>
		  <td><label>
            <input type="text" name="EventId" value="<?=$EventId;?>" maxlength="80" <?php echo $disableFields?'readonly="readonly"':'';?> />
          </label></td>
		  </tr>
		<tr>
          <td>Label : </td>
		  <td><label>
            <input type="text" name="Label" value="<?=$Label;?>" maxlength="80" />
          </label></td>
		  </tr>
		<tr>
			<td>Amount : </td>
			<td><label>
			<input type="text" name="Amount" value="<?=$Amount;?>" maxlength="80" <?php echo $disableFields?'readonly="readonly"':'';?>/>
			</label></td>
  </tr>
 <tr>
			<td>Type : </td>
			<td><label>
			<select name="Type" id="Type" onchange="getCurrency(this.value)" <?php echo $disableFields?'disabled="true"':'';?>>
                     <option value="1" <?php if($Type=="1"){?> selected=selected <?php }?>>Percentage</option>
                     <option value="2" <?php if($Type=="2"){?> selected=selected <?php }?>>Flat</option>   </select>
			</label></td>
  </tr>
  <tr id="tr_currency" style="display: <?php if($Type=="1"){?> none <?php }?>;">
            <td>Currency : </td>
            <td><label>
                    <select name="ex_currency" id="ex_currency" <?php echo $disableFields?'disabled="true"':'';?>>
                    <?php for ($t = 0; $t < count($currencyList); $t++) { ?>
                        <option value="<?php echo $currencyList[$t]['id']; ?>" <?php if($currencyList[$t]['id'] == $Currencyid){?> selected=selected <?php } ?> ><?php echo $currencyList[$t]['name'] . "(" . $currencyList[$t]['code'] . ")" ?></option>
                    <?php } ?>
                    </select>
                </label></td>
        </tr>

  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input type="submit" name="Submit" value="Update" />
    </label></td>
  </tr>
</table>
</form>
<!-------------------------------EDIT CITY PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>