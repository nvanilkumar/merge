<style type="text/css">
<!--
.style1 {
	font-size: 16px;
	font-weight: bold;
}
-->
</style>
<div align="center" style="width:100%">
<div align="center" style="width:100%">
<div align="left" class="style1" style="width:60%"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $title;?><?php */?></div>
<div align="center" style="width:100%">&nbsp;</div>
<div align="left" style="width:60%"><img src="<?php echo _HTTP_SITE_ROOT; ?> <?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$file_path?><?php */?>"></img></div>
<div align="center" style="width:100%">&nbsp;</div>
<div align="left" style="width:60%"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $status?><?php */?></div>
<div align="left" style="width:60%"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $active_date?><?php */?></div>
<div align="center" style="width:100%">&nbsp;</div>
<div align="left" style="width:60%" class="headtitle">Statistics</div>
<div align="center" style="width:100%">&nbsp;</div>
<table width="60%" class="sotrtable">
  <thead>	
  <tr>
    <td class="tblcont1" align="left" style="padding-left:20px;">&nbsp;</td>
    <td class="tblcont1" align="center">Clicks</td>
  </tr>
  </thead>
 
  <tr>
    <td align="left" style="padding-left:20px;" class="helpBod">Last hour</td>
    <td align="center" class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $this_hour_count;?><?php */?></td>
  </tr>
  
   <tr>
    <td align="left" style="padding-left:20px;" class="helpBod">Today</td>
    <td align="center" class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $today_count;?><?php */?></td>
  </tr>
  
  <tr>
    <td align="left" style="padding-left:20px;" class="helpBod">Last seven days</td>
    <td align="center" class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $last_seven_count;?><?php */?></td>
  </tr>
  
   <tr>
    <td align="left" style="padding-left:20px;" class="helpBod">This month</td>
    <td align="center" class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $this_month_count;?><?php */?></td>
  </tr>
  
  <tr>
    <td align="left" style="padding-left:20px;" class="helpBod">This year</td>
    <td align="center" class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $this_year_count;?><?php */?></td>
  </tr>
  
   <tr>
    <td align="left" style="padding-left:20px;" class="helpBod">All time</td>
    <td align="center" class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $all_count;?><?php */?></td>
  </tr>

</table>

<!-----------------------------BEGIN CLICK HISTORY---------------------------------------->
<div align="center" style="width:100%">
<div align="center" style="width:100%">&nbsp;</div>
<div align="left" style="width:60%" class="headtitle">Click History</div>
<div align="center" style="width:100%">&nbsp;</div>
<table width="60%" class="sotrtable">
  <thead>	
  <tr>
    <td class="tblcont1" align="center">Time</td>
    <td class="tblcont1" align="center">IP Address</td>
	<td class="tblcont1" align="center">Clicked From</td>
	<td class="tblcont1" align="center">&nbsp;</td>
  </tr>
  </thead>
  
  <?php /**************************commented on 17082009 need to remove afterwords**************************?><?php while($history = mysql_fetch_array($sql_history_res)){?><?php */?>
  <tr>
    <td align="center" class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo date("M d H:i",$history['timestamp']);?><?php */?></td>
    <td align="center" class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $history['hostname'];?><?php */?></td>
	<td align="center" class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $history['url'];?><?php */?></td>
    <td align="center" class="helpBod">[<a href="ad_details.php?cid=1<?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$history['cid']?><?php */?>">Details</a>]</td>
  </tr>
  <?php /**************************commented on 17082009 need to remove afterwords**************************?><?php }?><?php */?>
</table>