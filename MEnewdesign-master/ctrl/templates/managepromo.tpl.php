<script language="javascript">
function check()
{
	var event_name = document.frmSel.selEvt.value;
	var user_type = document.frmSel.selUserType.value;
		
	if(event_name=='0')
	{
		alert("Please select Event Name.");
		return false;
	}
	
	if(user_type=='0')
	{
		alert("Please select User Type.");
		return false;
	}

	return true;
}


</script>


<div style="padding-left:25px;">

<form  name="frmSel" method="post" action="generate_promo.php">

	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align="left">	
				<strong>Generate Promotion Code</strong>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%" border="0">
				  <tr>
					<td>Select Event</td>
					<td>
					<select name="selEvt">
					<option value="0">-Select Event-</option>
					<?
					while($res_event = mysql_fetch_array($qry_event))
					{
					?>
					<option value="<?=$res_event['nid']?>"><?=$res_event['title']?></option>
					<?
					}
					?>
					</select>
					
					</td>
					
				  </tr>
				  <tr>
					<td>User Type</td>
					<td>
					
					<select name="selUserType">
					<option value="0">-Select User Type-</option>
					<?
					while($res_user_type = mysql_fetch_array($qry_user_type))
					{
					?>
					<option value="<?=$res_user_type['user_type_id']?>"><?=$res_user_type['name']?></option>				
					<?
					}
					?>
					</select>
					
					</td>
					
				  </tr>
				  <tr>
				  	<td>&nbsp;</td>
					<td><input type="submit" name="next" value="Next"  onclick="return check();"/></td>
				  </tr>
				  
				</table>
				
			</td>
		</tr>
	</table>
	</form>
</div>