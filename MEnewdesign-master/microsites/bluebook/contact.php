<?php
include_once("../../ctrl/MT/cGlobali.php");  
$Globali = new cGlobali();
include_once '../../ctrl/includes/common_functions.php';
$commonFunctions=new functions();

                                     
                        $_GET=$commonFunctions->stripData($_GET);
                        $_POST=$commonFunctions->stripData($_POST);
                        $_REQUEST=$commonFunctions->stripData($_REQUEST);
                       
                        $FirstName = $_REQUEST['first_name'];
			$Email = $_REQUEST['email'];
                        $phone = $_REQUEST['phone'];
                        $message = $_REQUEST['message'];

                       $insert_query = "INSERT INTO `bluebook` (`fullname`,`email`,`mobile`,`suggestion`) VALUES(?,?,?,?)";
                     
                       $insertStmt = $Globali->dbconn->prepare($insert_query);
                       $insertStmt->bind_param("ssss", $FirstName,$Email,$phone,$message);
                       if($insertStmt->execute()){
                           echo 'success';
                           }else{
                              echo 'error';
                              }
                       $insertStmt->close();
                       
                       
                       
                        
              ?>