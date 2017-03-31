<script type="text/javascript" language="javascript">
	function validate(){
		if(document.edit_plan.plan_name.value == ''){
			alert("Please Enter Plan Name.");
			document.edit_plan.plan_name.focus();
			return false;
		}
		
		if(document.edit_plan.type_note.value == ''){
			alert("Please Enter Plan Note.");
			document.edit_plan.type_note.focus();
			return false;
		}
		
		if(document.edit_plan.type_charge.value == ''){
			alert("Please Enter Plan Charge.");
			document.edit_plan.type_charge.focus();
			return false;
		}
		
		if(!isInteger(document.edit_plan.type_charge.value)){
			alert("Plan Charge Can Only Be Numeric");
			document.edit_plan.type_charge.focus();
			return false;
		}
		
		return true;
	}// END OF FUNCTION
	
	function isInteger(s){
	var i;
    for (i = 0; i < s.length; i++){   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
} 
</script>
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Edit Plan</div>
<div align="center" style="width:100%">&nbsp;</div>
<form action="" method="post" name="edit_plan" onSubmit="return validate();">
<table width="80%" align="center" border="0"  style="border:thin; border-color:#006699; border-style:solid;">
  <tr>
    <td width="10%">&nbsp;</td>
    <td width="22%">&nbsp;</td>
    <td width="3%">&nbsp;</td>
    <td width="65%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Plan Name</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><label>
      <input type="text" name="plan_name" value="<?=$plan_name?>" disabled="disabled">
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Plan Note</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><label>
      <textarea name="type_note" cols="40" rows="3"><?=$type_note?>
      </textarea>
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Charges Per Event</td>
    <td align="center" valign="middle" class="tblcont">: </td>
    <td align="left" valign="middle"><label>
      <input type="text" name="type_charge" value="<?=$type_charge?>">
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Registration Charges</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><label>
      <input type="text" name="reg_charge" value="<?=$reg_charges?>">
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="left" valign="middle"><label>
      <input type="submit" name="Submit" value="Apply">
	  <input type="hidden" name="plan_id" value="<?=$plan_id?>">
    </label></td>
  </tr>
</table>
</form>
