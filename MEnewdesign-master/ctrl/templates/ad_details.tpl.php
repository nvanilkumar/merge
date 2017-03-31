<div align="center" style="width:100%">&nbsp;</div>
<div align="left" style="width:60%" class="headtitle">Click Details</div>
<div align="center" style="width:100%">&nbsp;</div>
<table width="60%" class="sotrtable" >
  <tr>
    <td class="tblcont1">Time</td>
    <td class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo date("d F Y - H:i",$sql_row['timestamp']);?><?php */?></td>
  </tr>
  <tr>
    <td class="tblcont1">IP Address</td>
    <td class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_row['hostname'];?><?php */?></td>
  </tr>
  <tr>
    <td class="tblcont1">User Agent</td>
    <td class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_row['user_agent'];?><?php */?></td>
  </tr>
  <tr>
    <td class="tblcont1">URL</td>
    <td class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_row['url'];?><?php */?></td>
  </tr>
  <tr>
    <td class="tblcont1">Advertisement</td>
    <td class="helpBod"><img src="<?php echo _HTTP_SITE_ROOT; ?><?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$sql_row['filepath']?><?php */?>"></img></td>
  </tr>
   <tr>
    <td colspan="2"><a href="ad_stats.php?aid=1<?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$sql_row['aid']?><?php */?>">Back</a></td>
  </tr>

</table>
