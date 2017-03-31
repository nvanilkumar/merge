<form action="" method="post">
<table width="100%" border="0">
  <tr>
    <td colspan="2"><strong>Search Users</strong> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Search String :</strong> </td>
    <td><label>
      <input name="search_str" type="text" id="search_str" size="50">
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input type="submit" name="Submit" value="Search">
    </label></td>
  </tr>
</table>
</form>

<table width="100%" border="0">
  
  <tr>
    <td><strong>Name</strong></td>
    <td><strong>Email</strong></td>
    <td><strong>User Type </strong></td>
    <td><strong>Status</strong></td>
  </tr>
  <?php while($sql_search_row = mysql_fetch_array($sql_search_res)){?>
  <tr>
    <td><?php echo $sql_search_row['name']?></td>
    <td><?php echo $sql_search_row['mail']?></td>
    <td><?php echo $sql_search_row['uname']?></td>
    <td><?php 
		if($sql_search_row['status'] == 1)
		echo "Active";
		else
		echo "Blocked";
		?>
	</td>
  </tr>
  <?php } ?>
</table>
<p>&nbsp;</p>
