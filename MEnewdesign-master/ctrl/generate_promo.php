<?php

		include_once("includes/application_top.php");

		include('includes/functions.php');
		include('includes/logincheck.php');

		

		

		 $event_name = $_POST['selEvt'];

		 $user_type = $_POST['selUserType'];

		 

		 

		 
        $coupon_name = $_POST['coupan_name'];
		$discount_rate = $_POST['discount_rate'];
		$discount_type = $_POST['selINR'];
		
		list($d,$m,$y) = explode("/",$_POST['strt_date']);
		$valid_from_date = $m."/".$d."/".$y;
		$valid_from = strtotime($valid_from_date);
		
		//echo "<br>".date("m/d/Y",$valid_from);
		
		list($d,$m,$y) = explode("/",$_POST['end_date']);
		$valid_to_date = $m."/".$d."/".$y;
		$valid_to = strtotime($valid_to_date);
		//echo "<br>".date("m/d/Y",$valid_to);
		
		$tot_num = $_POST['num_code'];

	    

		if(isset($_POST['btnGenerate'])) //Generate Promotion Code

		{

			for($i=0;$i<$tot_num;$i++)

			{

			$PromotionCode[$i] = strtoupper(substr(md5(rand()),0,13));

			$code.= $PromotionCode[$i].'<br>';
      		$for_csv.=$PromotionCode[$i]."\n";
/**************************commented on 17082009 need to remove afterwords**************************
			// INSERT CODES INTO TABLE promotion code/////
			$sql_insert = "INSERT INTO promotion_code SET 
															promo_code='".$PromotionCode[$i]."',
															cupon_name='".$coupon_name."',
															applicable_to=0,
															discount_rate=".$discount_rate.",
															discount_type='".$discount_type."',
															valid_from='".$valid_from."',
															valid_to='".$valid_to."'
															 ";
			mysql_query($sql_insert) or die("Error in insert promo code:".mysql_error());
			////////////////////////////////////////////////////
***************************************************/			

			//insert the new generated promotino code into promotion code table for valiation

			//$sql_insert_pc = "INSERT INTO promotion_code (promotioncode) VALUES ('".$PromotionCode[$i]."')";

			//mysql_query($sql_insert_pc);

			}

		}

	

		$current_page_content	=	'generate_promo.tpl.php';

		include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');

?>	   