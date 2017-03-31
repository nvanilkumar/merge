<div align="center" style="width:100%">
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Promotion Codes</div>
<div align="center" style="width:100%">&nbsp;</div>
<table width="90%" class="sortable" align="center"> 
  <tr>
    <td class="tblcont1"><strong>Coupon Name </strong></td>
	<td class="tblcont1"><strong>Promotion Code </strong></td>
    <td class="tblcont1"><strong>Valid From </strong></td>
    <td class="tblcont1"><strong>Valid To </strong></td>
    <td class="tblcont1"><strong>Discount Rate </strong></td>
    <td class="tblcont1"><strong>Discount Type </strong></td>
    <td class="tblcont1"><strong>Redemption Status </strong></td>
  </tr>
  
  <?php while($sql_promo = mysql_fetch_array($sql_promo_res)){?>
  <tr>
    <td class="helpBod"><?php echo $sql_promo['cupon_name']?></td>
	<td class="helpBod"><?php echo $sql_promo['promo_code']?></td>
    <td class="helpBod"><?php echo date("d/m/Y",$sql_promo['valid_from'])?></td>
    <td class="helpBod"><?php echo date("d/m/Y",$sql_promo['valid_to'])?></td>
    <td class="helpBod"><?php echo $sql_promo['discount_rate']?></td>
    <td class="helpBod"><?php echo $sql_promo['discount_type']?></td>
    <td class="helpBod"><?php if($sql_promo['redemption_status'] == 0){
			  echo "Available";
			  }else{
			  echo "Redeemed";
			  }
		?>	</td>
  
  <?php } ?></tr>
</table>
<div align="center" style="width:100%"><?php echo $projectpage->getPageLinks(); ?></div>
</div>