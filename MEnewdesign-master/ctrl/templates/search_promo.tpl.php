<script language="javascript" type="text/javascript">
	document.getElementById('ans4').style.display='block';
</script>
<div align="center" style="width:100%" class="headtitle">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Search Promotion Code</div>
<div align="center" style="width:100%" class="headtitle">&nbsp;</div>
<form action="" method="post">
<table width="50%" border="0" align="center" cellpadding="3" cellspacing="3" style="border:thin; border-color:#006699; border-style:solid;">
  <tr>
  <td width="14%">&nbsp;</td>
    <td colspan="3"><strong>Search Transaction Against Coupon</strong> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="28%" align="left" valign="middle" class="tblcont">Coupon Name</td>
    <td width="9%" align="center" valign="middle" class="tblcont"> : </td>
    <td width="49%" align="left" valign="middle"><label>
      <input type="text" name="coupon" />
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3" align="center" valign="middle"><label>
      <input type="submit" name="Submit" value="Search" />
    </label></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3" align="left" valign="middle"><a href="view_promocode.php">View All Promotion Codes</a></td>
    </tr>
</table>
</form>

<?php if($_POST['Submit'] == "Search"){?>
<table width="100%">

	<tr>
		<td>
			<b>Organiser Name</b>
		</td>
		<td>
			<b>Purchase Date</b>
		</td>
		<td>
			<b>Invoice No</b>
		</td>
		<td>
			<b>User Type</b>
		</td>
		<td>
			<b>Status</b>
		</td>
	</tr>
   <?php /**************************commented on 17082009 need to remove afterwords**************************?><?php if(mysql_num_rows($sql_res_coupon) == 0){ ?>
   	<tr>
		<td colspan="5">
			No Transactions For This Coupon Name.
		</td>
	</tr>
   <?php	}?><?php */
   
   /****************************************************/
   ?>
   
	<?php /**************************commented on 17082009 need to remove afterwords**************************?><?php while($sql_row_coupon = mysql_fetch_array($sql_res_coupon)){?><?php */?>
	<tr>
		<td>
			<?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_row_coupon['name'];?><?php */?>
		</td>
		<td>
			<?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_row_coupon['pdate'];?><?php */?>
		</td>
		<td>
			<?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_row_coupon['invoice_no'];?><?php */?>
		</td>
		<td>
			Organiser
		</td>
		<td>
			<?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_row_coupon['status'];?><?php */?>
		</td>
	</tr>
	<?php /**************************commented on 17082009 need to remove afterwords**************************?><?php }// End While?><?php */?>
</table>
<?php }// ENd IF?>