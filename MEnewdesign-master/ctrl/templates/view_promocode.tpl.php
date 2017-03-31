<div align="center" style="width:100%">
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Promotion Codes</div>
<div align="center" style="width:100%">&nbsp;</div>
<table width="90%" align="center" class="sortable">  
  <thead>
  <tr>
    <td align="left" valign="middle" class="tblcont1"><strong>Coupon Name </strong></td>
    <td align="left" valign="middle" class="tblcont1"><strong>Valid From </strong></td>
    <td align="left" valign="middle" class="tblcont1"><strong>Valid To </strong></td>
    <td align="left" valign="middle" class="tblcont1"><strong>Discount Rate </strong></td>
    <td align="left" valign="middle" class="tblcont1"><strong>Discount Type </strong></td>
  </tr>
  </thead>
  <?php /**************************commented on 17082009 need to remove afterwords**************************?><?php while($sql_promo = mysql_fetch_array($sql_promo_res)){?><?php */?>
  <tr>
    <td align="left" valign="middle" class="helpBod"><a href="list_code.php?name=<?php /*?><?=$sql_promo['cupon_name']?><?php */?>"><?php /*?><?php echo $sql_promo['cupon_name']?><?php */?></a></td>
    <td align="left" valign="middle" class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo date("d/m/Y",$sql_promo['valid_from'])?><?php */?></td>
    <td align="left" valign="middle" class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo date("d/m/Y",$sql_promo['valid_to'])?><?php */?></td>
    <td align="left" valign="middle" class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_promo['discount_rate']?><?php */?></td>
    <td align="left" valign="middle" class="helpBod"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_promo['discount_type']?><?php */?></td>
  <?php /**************************commented on 17082009 need to remove afterwords**************************?><?php } ?><?php */?></tr>
</table>
<div align="center" style="width:100%"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $projectpage->getPageLinks(); ?><?php */?></div>
</div>