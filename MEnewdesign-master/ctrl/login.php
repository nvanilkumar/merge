<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Login page
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 27th Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	
	include_once("MT/cGlobali.php");
	include_once("MT/cUser.php");
	
	include_once '../ctrl/includes/common_functions.php';
	$commonFunctions=new functions();
	$_POST=$commonFunctions->stripData($_POST,array("password"));
	$_GET=$commonFunctions->stripData($_GET,1);
	
	$Global = new cGlobali();

	
	$msgLogin = $_REQUEST['msgLogin'];
      $return_url = urldecode($_REQUEST['return_url']);
	
	//After user putin his user name and password
	if($_POST['process']=='SignIn')
	{
		$username = $_POST['user_name'];
		$passwordOrg = $_POST['password'];
		$pass = md5($passwordOrg);
		

		$LoginQuery = "SELECT id,usertype ,email FROM user WHERE username='".$Global->dbconn->real_escape_string($username)."' AND password='".$Global->dbconn->real_escape_string($pass)."' AND status = 1 "
                            . "AND deleted = 0 AND usertype in ('admin','superadmin')";
										
		
		
		$LoginList = $Global->SelectQuery($LoginQuery);
               
		if(count($LoginList) == 1){
                    echo "here";
                
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    $_SESSION["uid"]=$LoginList[0]['id'];
                    $_SESSION["useremail"]=$LoginList[0]['email'];
                    $_SESSION['UserName']=$username;
                    $_SESSION['username']=$username;
                    $_SESSION['adminUserType']=$LoginList[0]['usertype'];
                    $_SESSION['login_string'] = hash('sha512', $pass . $user_browser);
                    //echo $return_url;
                    if($return_url==""){
                       header("location:admin.php");
                    }
                    else{
                     header("location:".$return_url);
                    }
		}
		else
		{
		//	include 'templates/login.tpl.php';
			$msgLogin = "Incorrect User Name or Password";
		?>
			<script language="javascript" type="text/javascript">
				//document.getElementById("errorLogin").innerHTML = "Incorrect User Name or Password";
			</script> 
	<?php 
		}//ENDS ELSE
	}			

	// display content
	include 'templates/login.tpl.php';
?>