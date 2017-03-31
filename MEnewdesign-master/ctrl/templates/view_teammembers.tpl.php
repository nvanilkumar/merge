<script language="javascript">
  	document.getElementById('ans3').style.display='block';
</script>
<script language="javascript">
	function deletemem(delid)
	{
		 var confirmed;
			
			confirmed = confirm("Are you sure to DELETE Team-member!!If you DELETE him/her,he/she will no longer be the user of the site");
		if(confirmed){
				document.location = "delete_member.php?id="+delid;
			}else{
			 return false;
			}
	}
	function val_search()
	{
		if(document.frmearch.t_name.value == '' && document.frmearch.o_name.value == '' && document.frmearch.e_name.value == '')
		{
			alert("Please Enter Any Search Term");
			return false;
		}
		return true;
	}
</script>
<div align="center" style="width:100%">
<table align="center" width="90%">
	  <tr>
	    <td colspan="6" align="center" class="headtitle"><strong>Search Team Members</strong></td>
    </tr>
	  <tr>
    <td colspan="6" align="center" class="headtitle">&nbsp;</td>
  </tr>
  <tr>
  	<td colspan="6" align="center">
    	<form name="frmearch" method="post" action="" onsubmit="return val_search();">
    	<table align="center" width="60%" style="border:thin; border-color:#006699; border-style:solid;" cellpadding="2" cellspacing="2">
        	<tr>
        	  <td>&nbsp;</td>
        	  <td align="left" valign="middle" class="tblcont">&nbsp;</td>
        	  <td align="center" valign="middle" class="tblcont">&nbsp;</td>
        	  <td align="left" valign="middle">&nbsp;</td>
      	  </tr>
        	<tr>
        	  <td width="13%">&nbsp;</td>
            	<td width="37%" align="left" valign="middle" class="tblcont"><strong>Team member's name/email </strong></td>
            	<td width="4%" align="center" valign="middle" class="tblcont">:</td>
                <td width="46%" align="left" valign="middle">
                	<input type="text" name="t_name" />                 </td>
            </tr>
            	<tr>
            	  <td>&nbsp;</td>
            	  <td align="left" valign="middle" class="tblcont"><strong>Organiser name </strong></td>
            	  <td align="center" valign="middle" class="tblcont">:</td>
                <td align="left" valign="middle">
                	<input type="text" name="o_name" />                 </td>
            </tr>
            	<tr>
            	  <td>&nbsp;</td>
            	  <td align="left" valign="middle" class="tblcont"><strong>Event name </strong></td>
            	  <td align="center" valign="middle" class="tblcont">:</td>
                <td align="left" valign="middle">
                	<input type="text" name="e_name" />                 </td>
            </tr>
            	<tr>
            	  <td>&nbsp;</td>
            	  <td colspan="3" align="left" valign="middle"><input type="Submit" name="Search" value="Search" /></td>
          	  </tr>
            	<tr>
            	  <td>&nbsp;</td>
            	  <td colspan="3" align="left" valign="middle">&nbsp;</td>
       	    </tr>
        </table>
        </form>    </td>
  </tr>
  <tr>
    <td colspan="6" align="center" class="headtitle">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6" align="center" class="headtitle"><strong>Team Members</strong></td>
  </tr>
  <tr><td colspan="6" align="center" class="headtitle">&nbsp;</td></tr>
  <tr>
  	<td>
    	<table align="center" width="100%" class="sortable">
   <thead>
  <tr>
    <td width="11%" align="left" valign="middle" class="tblcont1"><strong>User Name </strong></td>
    <td width="15%" align="left" valign="middle" class="tblcont1"><strong>Email</strong></td>
    <td width="15%" align="left" valign="middle" class="tblcont1"><strong>Parent Organiser </strong></td>
    <td width="20%" align="left" valign="middle" class="tblcont1"><strong>Event</strong></td>
    <td width="27%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort"><strong>Permissions</strong></td>
    <td width="12%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort"><strong>Delete</strong></td>
  </tr> 
  </thead>
  <?php 
  /**************************commented on 17082009 need to remove afterwords**************************
  while($sql_team = mysql_fetch_array($sql_team_res)){ 
  
  $sql_user = "SELECT name,mail FROM users WHERE uid='".$sql_team['child_id']."' ";
  $sql_user_res = mysql_query($sql_user) or die("Error in users for child : ".mysql_error());
  $sql_user_row = mysql_fetch_array($sql_user_res);
  
  if($sql_team['edite'] == 1){
  	$permissions = ",Edit ";
  }
  if($sql_team['adde'] == 1){
  	$permissions.= ",Add ";
  }
  
  if($sql_team['dele'] == 1){
  	$permissions.= ",Delete ";
  }
  if($sql_team['publishe'] == 1){
  	$permissions.= ",Publish ";
  }
  if($sql_team['reporte'] == 1){
  	$permissions.= ",Report ";
  }
  $permissions[0] = '';
  ****************************************************/
  ?>
  <tr>
    <td align="left" valign="middle" class="helpBod">test team member<?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_user_row['name'] ?><?php */?></td>
    <td align="left" valign="middle" class="helpBod">test@test.com<?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_user_row['mail'] ?><?php */?></td>
    <td align="left" valign="middle" class="helpBod">test<?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_team['orgname'] ?><?php */?></td>
    <td align="left" valign="middle" class="helpBod">test event<?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_team['event'] ?><?php */?></td>
    <td align="left" valign="middle" class="helpBod">test<?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $permissions ?><?php */?></td>
    <td align="left" valign="middle" class="helpBod"><img border="0" onclick="deletemem(1<?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_team['child_id'] ?><?php */?>)" src="images/delet.jpg" title="Delete"  style="cursor:pointer"/></td>
   <?php /**************************commented on 17082009 need to remove afterwords**************************?><?php } ?><?php */?>
  </tr>
</table>    </td>
  </tr>
</table>
<div align="center" style="width:100%"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $projectpage->getPageLinks(); ?><?php */?></div>
</div>