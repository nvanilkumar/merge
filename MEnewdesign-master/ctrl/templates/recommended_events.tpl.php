<script language="javascript" type="text/javascript">
	document.getElementById('ans1').style.display='block';
	function deletrec(delid)
	{
		if (confirm('Are you sure to DELETE this record?') ) 
		{
			window.location = 'recommended_events.php?delid='+delid;
		} 
		else 
		{
			return false;
		}
	}
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/includes/javascripts/sortpagi.min.js.gz"></script>
<table width="90%" align="center" class="inner sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-3 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating">
  <thead>
  <tr>
    <td width="5%" height="24" class="tblcont1">Sr No.</td>
    <td width="11%" class="tblcont1">Username</td>
    <td width="31%" class="tblcont1">Event Name</td>
    <td width="22%" class="tblcont1">Event Url</td>
    <td width="31%" class="tblcont1">Event Description</td>
    <td width="31%" class="tblcont1">Delete</td>
  </tr>
  </thead>
<?php
	$i = 0;
/**************************commented on 17082009 need to remove afterwords**************************
  	while($row = mysql_fetch_array($qry))
	{
	$i++;
****************************************************/
  ?>
  <tr>
    <td align="left" valign="top" class="helpBod"><?=$i;?></td>
    <td align="left" valign="top" class="helpBod">
		<?php /**************************commented on 17082009 need to remove afterwords**************************?><?php
			$user = "SELECT * FROM users where uid = '".$row['uid']."' ";
			$u_qry = mysql_fetch_array(mysql_query($user));
			echo $name = $u_qry['name'];
        ?> <?php */?>   </td>
    <td align="left" valign="top" class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$row['name']?><?php */?></td>
    <td align="left" valign="top" class="helpBod"><a href="<?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$row['url']?><?php */?>" title="<?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$row['url']?><?php */?>"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$row['url']?><?php */?></a></td>
    <td align="left" valign="top" class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$row['description']?><?php */?></td>
    <td align="left" valign="top" class="helpBod">
	<img src="images/delet.jpg" border="0" onclick="deletrec(1<?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$row['id']?><?php */?>)" style="cursor:pointer;" title="Delete" />
	</td>
  </tr>
<?php /**************************commented on 17082009 need to remove afterwords**************************?> <?php
 	
 	}
 ?><?php */?>
</table>
