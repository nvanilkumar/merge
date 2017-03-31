<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - User Management</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
</head>	
<body style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
	<?php include('templates/header.tpl.php'); ?>				
	</div>
	<table style="width:100%; height:495px;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
				<?php include('templates/left.tpl.php'); ?>
			</td>
			<td style="vertical-align:top">
				<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------USER MANAGEMENT PAGE STARTS HERE--------------------------------------------------------------->
<div>
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
		  <td align="center" class="headtitle">User Management </td>
	  </tr>
		<tr>
			<td align="center" class="headtitle">&nbsp;</td>
		</tr>
		<tr>
			<td align="center">
				<table width="60%" cellpadding="0" cellspacing="0" style="border:thin; border-color:#006699; border-style:solid;">
		      <tr><td colspan="4">&nbsp;</td></tr>
					<tr>
					  <td width="14%" align="left" valign="middle" class="tblcont">&nbsp;</td>
						<td width="31%" align="left" valign="middle" class="tblcont">Manage Admin Users</td>
						<td width="17%" align="left" valign="middle">
                        <!--a href="adduser.php" class="lnk">Add User</a-->
                        </td>
					  <td width="38%" align="left" valign="middle" style="padding-left:10px;"><a href="edituser.php" class="lnk"> Delete / Manage User</a></td>
				  </tr>
					<tr><td colspan="4" align="left" valign="middle">&nbsp;</td>
				  </tr>
					<tr>
					  <td align="left" valign="middle" class="tblcont">&nbsp;</td>
						<td align="left" valign="middle" class="tblcont">Manage Partners</td>
						<td align="left" valign="middle"><a href="addpartners.php" class="lnk">Add Partners</a></td>
					  <td align="left" valign="middle" style="padding-left:10px;"><a href="editpartners.php" class="lnk"> Delete / Manage Partners</a></td>
				  </tr>
					<tr><td colspan="4" align="left" valign="middle">&nbsp;</td>
				  </tr>
					<tr>
					  <td align="left" valign="middle" class="tblcont">&nbsp;</td>
						<td align="left" valign="middle" class="tblcont">Manage Front End Users</td>
				    <!--<td align="right"><a href="addcountry.php">Add</a></td>-->
						<td colspan="2" align="left" valign="middle"><a href="manageuser.php" class="lnk">Manage Users</a></td>
				  </tr>
					<tr><td colspan="4" align="left" valign="middle">&nbsp;</td>
				  </tr>
					<tr>
					  <td align="left" valign="middle" class="tblcont">&nbsp;</td>
						<td align="left" valign="middle" class="tblcont">Manage Organisers</td>
				    <!--<td align="right"><a href="adddesignation.php">Add</a></td>-->
						<td colspan="2" align="left" valign="middle"><a href="manageorganisers.php" class="lnk">Manage Organisers</a></td>
				  </tr>
					<tr><td colspan="4" align="left" valign="middle">&nbsp;</td>
				  </tr>
					<!--tr>
					  <td align="left" valign="middle" class="tblcont">&nbsp;</td>
                      <td align="left" valign="middle" class="tblcont">Manage Team Members </td-->
					  <!--<td align="right"><a href="adddesignation.php">Add</a></td>-->
                      <!--td colspan="2" align="left" valign="middle"><a href="view_teammembers.php" class="lnk">View Team Members </a></td>
				  </tr-->
					<tr>
					  <td colspan="4" align="left" valign="middle">&nbsp;</td>
				  </tr>
					<tr>
					  <td align="left" valign="middle" class="tblcont">&nbsp;</td>
                      <td align="left" colspan="3" valign="middle" class="tblcont">&nbsp;</td>
					  <!--<td align="right"><a href="adddesignation.php">Add</a></td>-->
                      <!--td colspan="2" align="left" valign="middle"><a href="search_user.php" class="lnk">Search Users </a></td-->
				  </tr>
					<tr>
					  <td colspan="4" align="left" valign="middle">&nbsp;</td>
				  </tr>
				</table>		  </td>
		</tr>
	</table>
</div>
<!-------------------------------USER MANAGEMENT PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>