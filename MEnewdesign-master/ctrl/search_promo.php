<?php
		include_once("includes/application_top.php");
		include('includes/functions.php');
		include('includes/logincheck.php');

		if($_POST['Submit'] == "Search"){
			
			$coupon = $_POST['coupon'];
/**************************commented on 17082009 need to remove afterwords**************************			
			$sql_coupon = "SELECT promotion_code_redemption.* , users.name,transaction_history.status,transaction_history.date AS pdate
							FROM promotion_code, promotion_code_redemption, transaction_history, users
							WHERE promotion_code.applicable_to =0
							AND promotion_code.promo_id = promotion_code_redemption.promo_id
							AND promotion_code_redemption.invoice_no = transaction_history.invoice_no
							AND promotion_code_redemption.uid = users.uid
							AND promotion_code.redemption_status =1
							AND promotion_code.cupon_name='".$coupon."'
							";
			$sql_res_coupon = mysql_query($sql_coupon) or die("Error in coupon :".mysql_error());
			//$sql_row_coupon = mysql_fetch_array($sql_res_coupon);
****************************************************/			
		}
		
		$current_page_content='search_promo.tpl.php';
		include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');
?>