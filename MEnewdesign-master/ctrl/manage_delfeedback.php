<?php

	
	session_start();
		
	 include 'loginchk.php';

	$uid =	$_SESSION['uid'];
	$base_path = '/home/meraeven/public_html/meraevents_dev';	
	include_once("MT/cGlobal.php");
	$dDate=date('Y-m-d H:i:s');

	$Global = new cGlobal();
   if($_REQUEST[itdel]!="" || $_REQUEST[itdel]!=0)
   {
    $sqlDel="delete from delfeedbacks where Id='".$_REQUEST[itdel]."'";
   $Global->ExecuteQuery($sqlDel);
   }
/*   if($_REQUEST[Submit]=="Submit")
   {
 if($_FILES['fileLogoImage']['error']==0)			//If file is attached.
		{
			$sFileName = $_FILES['fileLogoImage']['name'];
			move_uploaded_file($_FILES['fileLogoImage']['tmp_name'],$base_path."images/".$_FILES['fileLogoImage']['name']);
			$sFileName = "/images/".$sFileName;
		}
   
     $SqlIns="insert into delfeedbacks (vFName,Title,vEmail,vMobile,tComment,dPDate,logo) values ('".$_REQUEST[txtName]."','".$_REQUEST[txtTitle]."','".     $_REQUEST[txtemail]."','".$_REQUEST[txtMobile]."','".$_REQUEST[txtComment]."','".$dDate."','".$sFileName."')";
   $Global->ExecuteQuery($SqlIns);
   
   }*/
	
	//Query For All Testimonials List

	$BannerQuery = "SELECT * FROM delfeedbacks order by dPDate DESC";
	$ResFeed = $Global->SelectQuery($BannerQuery);
	
	include 'templates/manage_delfeedback.tpl.php';
?>