<?php
            $allemail="Coupon name :,".$_POST['coupon_name']."\n";
			$allemail.="Valid From :,".$_POST['valid_from']."\n";
			$allemail.="Valid To :,".$_POST['valid_to']."\n\n";
			$allemail.="Generated On :,".date("m/d/Y")."\n\n";
			$allemail.="Promotion Codes\n\n";
			$allemail.=$_POST['code'];
		    header("Content-type: text/x-csv");
			//header("Content-type: text/csv");
			//header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename=promotion_codes.csv");
			echo $allemail;
			exit;


?>