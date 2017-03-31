<div align="center" style="width:100%">
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Manage Plans</div>
<form action="" method="post">
<table align="center" class="sortable" width="70%">
	<tr>
	  <td class="tblcont1"><strong>Type Name </strong></td>
      <td  class="tblcont1"><strong>Type Note </strong></td>
      <td  class="tblcont1"><strong>Charges Per Event</strong></td>
	  <td class="tblcont1"><strong>Registration Charges</strong></td>
	  <td class="tblcont1"><strong>Options </strong></td>
  </tr>
<?php /**************************commented on 17082009 need to remove afterwords**************************?>  <?php while($sql_row_plans = mysql_fetch_array($sql_res_plans)){?>
<?php */?>	<tr>
	  <td class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_row_plans['type_name'];?><?php */?></td>
	  <td class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_row_plans['type_note'];?><?php */?></td>
	  <td class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_row_plans['charges'];?><?php */?></td>
	  <td class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_row_plans['registration_charges'];?><?php */?></td>
	  <td class="helpBod"><a href="editplan.php?id=1<?=$sql_row_plans['sub_type_id']?>">Edit</a></td>
  </tr>
<?php /**************************commented on 17082009 need to remove afterwords**************************?>  <?php } ?>
<?php */?>	
</table>
</form>
</div>