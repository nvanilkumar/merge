<style type="text/css">
<!--
.style1 {
	font-size: 14px;
	font-style: italic;
}
-->
</style>
<script type="text/javascript" language="javascript">
	function validate(){
		
		var message = document.sms_trigger.message.value;
		
		
		if(message == ''){
			alert("Message Can Not Be Empty");
			return false;
		}
		else if(message.length > 160){
			alert("Message Can Not Exeed 160 Characters.");
			return false;
		}
		
		return true;
	}
</script>
<form action="" method="post" name="sms_trigger" onsubmit="return validate();">
<table width="70%" border="0" align="center">
  <tr>
    <td colspan="2"><div align="center"><strong>Trigger Settings </strong></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="tblcont1">Trigger : </td>
    <td class="helpBod"><?php echo $trigger['title']?></td>
  </tr>
  <tr>
    <td class="tblcont1">SMS Message : </td>
    <td class="helpBod"><label>
      <textarea name="message" cols="60" rows="3"><?php echo $trigger['message']?></textarea>
      <br>
      <span class="style1">Maximum of 160 characters allowed. </span></label></td>
  </tr>
  <tr>
    <td class="tblcont1">Enable : </td>
    <td class="helpBod"><label>
      <input type="checkbox" name="enable" value="1" <?php echo $checked?> >
    </label></td>
  </tr>
  <tr>
    <td class="tblcont1">&nbsp;</td>
    <td class="helpBod"><label>
      <input type="submit" name="Submit" value="Submit">
	  <input type="hidden" name="tid" value="<?php echo $trigger['tid']?>">
    </label></td>
  </tr>
</table>
</form>
