<?php
		include_once("includes/application_top.php");
		include('includes/functions.php');
		include('includes/logincheck.php');
		include('includes/paginator.php');
		
		$name = $_GET['name'];
		
		$sql_promo = "SELECT * FROM promotion_code WHERE applicable_to=0 AND cupon_name='".$name."' ";
		$sql_promo_res = mysql_query($sql_promo) or die("Error in promotion code : ".mysql_error());
				
		$project_numbers = mysql_num_rows($sql_promo_res);
		///////////// Code For Paging//////////////////////////
		$projectpage =& new Paginator($_GET['page'],$project_numbers);
		$projectpage->set_Limit(15); 
		$projectpage->set_Links(2);
		$limit1 = $projectpage->getRange1(); 
		$limit2 = $projectpage->getRange2();
		  
		$sql_promo  = "SELECT * FROM promotion_code WHERE applicable_to=0 AND cupon_name='".$name."' LIMIT ".$limit1.",".$limit2;
		$sql_promo_res = mysql_query($sql_promo) or die("Error in promotion code : ".mysql_error());
		
		////////////////////////////////
			
		
		$current_page_content	=	'list_code.tpl.php';
		include_once(_CURRENT_TEMPLATE_DIR.'main.tpl.php');
?>	   