<script type="text/javascript" language="javascript">
	function validate(){
		if(document.add_plan.plan_name.value == ''){
			alert("Please Enter Plan Name.");
			document.add_plan.plan_name.focus();
			return false;
		}
		
		if(document.add_plan.type_note.value == ''){
			alert("Please Enter Plan Note.");
			document.add_plan.type_note.focus();
			return false;
		}
		
		if(document.add_plan.type_charge.value == ''){
			alert("Please Enter Plan Charge.");
			document.add_plan.type_charge.focus();
			return false;
		}
		
		if(!isInteger(document.add_plan.type_charge.value)){
			alert("Plan Charge Can Only Be Numeric");
			document.add_plan.type_charge.focus();
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

<form action="" method="post" name="add_plan" onSubmit="return validate();">
<table width="100%" border="0">
  <tr>
    <td colspan="2"><strong>Add Plan</strong> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Plan Name : </td>
    <td><label>
      <input type="text" name="plan_name">
    </label></td>
  </tr>
  <tr>
    <td>Plan Note : </td>
    <td><label>
      <textarea name="type_note" cols="40" rows="3"></textarea>
    </label></td>
  </tr>
  <tr>
    <td>Plan Charges : </td>
    <td><label>
      <input type="text" name="type_charge" >
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input type="submit" name="Submit" value="Add">
	  
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?php echo $msg;?></td>
  </tr>
</table>
</form>
