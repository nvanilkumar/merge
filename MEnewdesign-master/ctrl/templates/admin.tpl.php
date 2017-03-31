<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel</title>
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
<!-------------------------------ADMIN LOGIN PAGE STARTS HERE--------------------------------------------------------------->
<div>
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
		  <td align="center" valign="middle" class="headtitle">Master Management </td>
	  </tr>
		<tr>
			<td align="center" valign="middle" class="headtitle">&nbsp;</td>
	  </tr>
		<tr>
			<td align="center">
				<table width="50%" border="0" align="center" cellpadding="2" cellspacing="2" style="border:thin; border-color:#006699; border-style:solid;">
        <tr><td colspan="4">&nbsp;</td></tr>
					<tr>
					  <td width="21%" align="left" valign="middle" class="tblcont" >&nbsp;</td>
						<td width="28%" align="left" valign="middle" class="tblcont" >City </td>
					  <td width="16%" align="center" valign="middle"><a href="addcity.php" class="lnk">Add</a></td>
					  <td width="35%" align="center" valign="middle" style="padding-left:10px;"><a href="editcities.php" class="lnk"> Edit / Delete</a></td>
				  </tr>
					<tr><td colspan="4" align="left" valign="middle">&nbsp;</td>
				  </tr>
					<tr>
					  <td align="left" valign="middle" class="tblcont">&nbsp;</td>
						<td align="left" valign="middle" class="tblcont">State</td>
						<td align="center" valign="middle"><a href="addstate.php" class="lnk">Add</a></td>
					  <td align="center" valign="middle" style="padding-left:10px;"><a href="editstate.php" class="lnk"> Edit / Delete</a></td>
				  </tr>
					<tr><td colspan="4" align="left" valign="middle">&nbsp;</td>
				  </tr>
					<tr>
					  <td align="left" valign="middle" class="tblcont">&nbsp;</td>
						<td align="left" valign="middle" class="tblcont">Country</td>
						<td align="center" valign="middle"><a href="addcountry.php" class="lnk">Add</a></td>
					  <td align="center" valign="middle" style="padding-left:10px;"><a href="editcountry.php" class="lnk"> Edit / Delete</a></td>
				  </tr>
					<tr><td colspan="4" align="left" valign="middle">&nbsp;</td>
<!--				  </tr>
					<tr>
					  <td align="left" valign="middle" class="tblcont">&nbsp;</td>
						<td align="left" valign="middle" class="tblcont">Designation</td>
						<td align="center" valign="middle"><a href="adddesignation.php" class="lnk">Add</a></td>
					  <td align="center" valign="middle" style="padding-left:10px;"><a href="editdesignation.php" class="lnk"> Edit / Delete</a></td>
				  </tr>
					<tr><td colspan="4" align="left" valign="middle">&nbsp;</td>
				  </tr>
					<tr>
					  <td align="left" valign="middle" class="tblcont">&nbsp;</td>
						<td align="left" valign="middle" class="tblcont">Industry</td>
						<td align="center" valign="middle"><a href="addindustry.php" class="lnk">Add</a></td>
					  <td align="center" valign="middle" style="padding-left:10px;"><a href="editindustry.php" class="lnk"> Edit / Delete</a></td>
				  </tr>
					<tr><td colspan="4" align="left" valign="middle">&nbsp;</td>
				  </tr>
				<tr>
					  <td align="left" valign="middle" class="tblcont">&nbsp;</td>
						<td align="left" valign="middle" class="tblcont">Function</td>
						<td align="center" valign="middle"><a href="addfunction.php" class="lnk">Add</a></td>
					  <td align="center" valign="middle" style="padding-left:10px;"><a href="editfunction.php" class="lnk"> Edit / Delete</a></td>
				  </tr>
					<tr><td colspan="4" align="left" valign="middle">&nbsp;</td>
				  </tr>
					<tr>
					  <td align="left" valign="middle" class="tblcont">&nbsp;</td>
						<td align="left" valign="middle" class="tblcont">Event Type</td>
						<td align="center" valign="middle"><a href="addeventtype.php" class="lnk">Add</a></td>
					  <td align="center" valign="middle" style="padding-left:10px;"><a href="editeventtype.php" class="lnk"> Edit / Delete</a></td>
				  </tr>
					<tr><td colspan="4">&nbsp;</td></tr>-->
			  </table>		  </td>
		</tr>
	</table>
</div>
<!-------------------------------ADMIN LOGIN PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>