<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	list organization user
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 25th Aug 2009
 *	2.	IsAffiliate checkbox feature implemented on 15th Sep 2009, get the request to update the IsAffiliate status of organizer
 *	3.	Now affiliation mail to be send to organizer registered.
******************************************************************************************************************************************/
	
	session_start();
	$uid =	$_SESSION['uid'];
	
	include 'loginchk.php';
	

	if(isset($_REQUEST['spId']) && $_REQUEST['spId']!='')
	{
		

		 $Id = $_REQUEST['spId'];
		
		if($_REQUEST[newAffiliateStatus]!='')
		{
		$IsAffiliate1 = $_REQUEST['newAffiliateStatus'];
        $resaon="affiliate";
	
		$update_sp = "UPDATE serviceprovider SET IsAffiliate = '".$IsAffiliate1."' WHERE UserId = '".$Id."'";
		mysql_query($update_sp);
		//mysql_close();	
		}
		if($_REQUEST[newTrustedStatus]!='')
		{
		$IsTrusted1 = $_REQUEST['newTrustedStatus'];
        $resaon="trusted";
	
		$update_sp = "UPDATE serviceprovider SET IsTrusted = '".$IsTrusted1."' WHERE UserId = '".$Id."'";
		mysql_query($update_sp);
		//mysql_close();	
		}
		if($IsAffiliate1==1 || $IsTrusted1==1)
		{
			include_once("MT/cGlobal.php");
			$Global = new cGlobal();
			
			$selOrgDetails = "SELECT Email, FirstName FROM user WHERE Id = '".$Id."'";
			$OrgDetails = $Global->SelectQuery($selOrgDetails);
			
			$to = $OrgDetails[0]['Email'];
			$FirstName = $OrgDetails[0]['FirstName'];
			
			$subject = 'You are now '.$resaon.' member of meraevents.com';
			
			$message = '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<style type="text/css">
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: normal;
	color: #000000;
}
.style1 {font-size: 11px}
</style>
</head>

<body>
<table width="600" border="1" cellpadding="0" cellspacing="0" bordercolor="#F5F4EF">
  
  <tr>
    <td align="center" valign="top"><table width="98%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="27%" align="left" valign="middle"><a href="http://www.meraevents.com/" target="_blank"><img src="http://www.meraevents.com/email_images/logo.jpg" width="159" height="92" border="0" /></a></td>
            <td width="73%" align="left" valign="middle"><img src="http://www.meraevents.com/email_images/header_txt_img.jpg" width="162" height="26" border="0" /></td>
          </tr>
        </table></td>
      </tr>
      <tr></tr>
      <tr>
        <td height="5" align="left" valign="top" bgcolor="#302825"></td>
      </tr>
      <tr>
        <td align="left" valign="top"><p><strong>Dear '.$FirstName.',</strong> </p>
          <p>Congratulations, You are now '.$resaon.' member of meraevents.com</p>
          <br /></td>
      </tr>
    
      <tr>
        <td height="30" align="left" valign="middle"><p>Click on the link to go to MeraEvents Sign In page&nbsp;&nbsp;<a href="http://www.meraevents.com" target="_blank"><u>www.meraevents.com</u></a> <br />
          </p>
          <br /></td>
      </tr>
      <tr></tr>
      <tr></tr>
      <tr></tr>
      <tr>
        <td align="left"><p><i>Kindly note this is system-generated mail with regard to your account at meraevents.com. We take highest measure to ensure your profile information is secure with us.</i></p><br />
          <p>You are most welcome to contact us for any query, 
            comments and suggestions at <br /><a href="mailto:support@meraevents.com" target="_blank">
            support@meraevents.com</a>&nbsp;please call us at  +91-40-40404160 </p>          </td>
      </tr>
      <tr>
        <td align="left"><p><span class="style2">With Best Wishes,</span><br />
            <strong>MeraEvents.com Team</strong></p>          </td>
      </tr>
      <tr></tr>
    </table>    </td>
  </tr>
  <tr>
    <td height="70" align="center" valign="top" bgcolor="#DCD9C7"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="25" align="center"><span class="style1"> 2nd Floor , 3 Cube Towers,&nbsp; Whitefield Road, Kondapur | Hyderabad |Andhra Pradesh | INDIA | Ph: +91 40 4040 4160 </span></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td height="25" align="center"><table width="95%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%" align="left" valign="middle" class="style1"><table width="200" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="62" valign="middle"> Product by </td>
                  <td width="138" valign="middle"><a href="http://www.versanttechnologies.com/" target="_blank"><img src="http://www.meraevents.com/email_images/versant-logo.jpg" width="100" height="22" border="0" /></a></td>
                </tr>
              </table></td>
            <td width="50%" align="right" class="style1">&copy; 2009, meraevents.com </td>
          </tr>
        </table></td>
      </tr>
    </table>      </td>
  </tr>
  <tr>
    <td height="2" align="center" valign="top" bgcolor="#D2CEB9"></td>
  </tr>
</table>
</body>
</html>
';
													$headers = "From:admin@meraevents.com\r\n" .
													'X-Mailer: PHP/' . phpversion() . "\r\n" .
													"MIME-Version: 1.0\r\n" .
													"Content-Type: text/html; charset=utf-8\r\n" .
													"Content-Transfer-Encoding: 8bit\r\n\r\n";
										            // Send email
										 			mail($to, $subject, $message, $headers);
			
			
		}
		
		
	
	}

if(isset($_REQUEST['action']) && $_REQUEST['spdelid']!='')
	{
	 	 $delete_sp = "delete from  serviceprovider  WHERE UserId = '".$_REQUEST['spdelid']."'";
		mysql_query($delete_sp);
		 $delete_user = "delete from user where Id = '".$_REQUEST['spdelid']."'";
		mysql_query($delete_user);
		//mysql_close();	
	}
	include_once("MT/cGlobal.php");
	include_once("MT/cUser.php");
	include_once("MT/cCities.php");
	include_once("MT/cServiceProvider.php");
	
	$Global = new cGlobal();		
	
	$name = $_REQUEST['orguser'];
	$email = $_REQUEST['orgemail'];
	$comp = $_REQUEST['orgcompany'];
	$startdt = strtotime($_REQUEST['strt_date'].' 00:00::00');
	$enddt = strtotime($_REQUEST['end_date'].' 23:59::00');
	$stats = $_REQUEST['sts'];
	
	
	
	$SpQuery = "SELECT sp.*, u.Id, u.UserName, u.Password, c.City, u.Company FROM serviceprovider AS sp, user AS u, Cities AS c WHERE sp.UserId = u.Id  AND u.CityId = c.Id";
		
		if($name!='') $OrgQuery .= " AND u.FirstName = '".$name."'";
		if($email!='') $OrgQuery .= " AND sp.SPEMail='".$email."'";
		if($comp!='') $OrgQuery .= " AND u.Company='".$comp."'";
		if($stats!='-1') $OrgQuery .= " AND u.Active='".$stats."'";
		
		$OrgQuery .= " ORDER BY u.UserName ASC";


	$SpList = $Global->SelectQuery($SpQuery);
	
	include 'templates/listsp.tpl.php';
?>