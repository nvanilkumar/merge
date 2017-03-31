<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - User Management - Manage Organizer - Organizer Deatils</title>
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
<!------------------------------- PAGE STARTS HERE--------------------------------------------------------------->
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.min.js.gz"></script>
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Organizer Details</div>
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%">
		<table width="95%" align="center">
			<tr>
				<td colspan="2">
					<a href="user.php" class="menuhead" title="User Management Home">User Management Home</a>&nbsp;&nbsp;
					<a href="manageorganisers.php" class="menuhead" title="Manage Organisers Home">Manage Organisers Home</a>
				</td>
			</tr>
		</table>
		<table width="95%" align="center" style="border:thin; border-color:#006699; border-style:solid;">
			<?php
		//	if(count($OrgList) > 0) 
		//	{
		//	for($m=0; $m < count($OrgDetails); $m++)
		//	{
			?>
			<tr>
				<td align="left" valign="top" width="25%" height="25px">Username: </td>
				<td align="left" valign="top" width="75%"><?php echo $Organizer->UserName; ?></td>
			</tr>
			<tr>
				<td align="left" valign="top">E Mail Id : </td>
				<td align="left" valign="top"><?php echo $Organizer->Email; ?>
				<input type="button" name="EditEmailId" value="Edit Email Id" onclick="javascript:document.getElementById('EditOrgEmail').style.display='';" />
				<div id="EditOrgEmail" style="display:none;">
					<form name="frmEditEmail" action="" method="post">
						<input type="hidden" name="Id" value="<?=$_GET['Id']?>" />
						<input type="text" name="txtEmailId" id="txtEmailId" value="" maxlength="100" onblur="return valEditEmailID();" />&nbsp;
						<input type="submit" name="Submit" value="Submit" />&nbsp;<input type="button" name="Cancel" value="Cancel" onclick="javascript:document.getElementById('EditOrgEmail').style.display='none';" />
						<script language="javascript" type="text/javascript">
						function valEditEmailID()
						{
							var tEmailId = document.getElementById('txtEmailId').value;
							tEmailId = tEmailId.replace(/^\s+|\s+$/g, '') ;
							var emailRegEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

							if(!tEmailId.match(emailRegEx))
							{
								alert('Please enter a valid email address.');
								return false;
							}
						}
						</script>
					</form>
				</div>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top" colspan="2"><hr /></td>
			</tr>
			<tr>
				<td align="left" valign="top" colspan="2"><b>Company Details</b></td>
			</tr>
			<tr>
				<td align="left" valign="top">Company Name : </td>
				<td align="left" valign="top"><?php echo $Organizer->Company; ?></td>
			</tr>
			<tr>
				<td align="left" valign="top">Company Description : </td>
				<td align="left" valign="top"><?php echo $Organizer->CDesc; ?></td>
			</tr>
			<tr>
				<td align="left" valign="top">Company Address : </td>
				<td align="left" valign="top"><?php echo $Organizer->CAddress; ?></td>
			</tr>
			<tr>
				<td align="left" valign="top">Country : </td>
				<td align="left" valign="top">
				<?php
					$Countries = new cCountries($Organizer->CountryId,"");
					$Countries->Load();
					echo $Countries->Country; 
				?>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">State : </td>
				<td align="left" valign="top">
					<?php
					$States = new cStates($Organizer->StateId,"","");
					$States->Load();
					echo $States->State;
					?>				
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">City : </td>
				<td align="left" valign="top">
					<?php
						$Cities = @new cCities($Organizer->CityId);
						$Cities->Load();
						echo $Cities->City;
					?>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">PIN : </td>
				<td align="left" valign="top"><?php echo $Organizer->Cpin; ?></td>
			</tr>
			<tr>
				<td align="left" valign="top">Phone No : </td>
				<td align="left" valign="top"><?php echo $Organizer->CPhone; ?></td>
			</tr>
			<tr>
				<td align="left" valign="top">Fax No: </td>
				<td align="left" valign="top"><?php echo $Organizer->CFax; ?></td>
			</tr>
			<tr>
				<td align="left" valign="top">Company EMail Id: </td>
				<td align="left" valign="top"><?php echo $Organizer->CEMail; ?></td>
			</tr>
			<tr>
				<td align="left" valign="top">Website URL : </td>
				<td align="left" valign="top"><?php echo $Organizer->CURL; ?></td>
			</tr>
			<tr height="120px">
				<td align="left" valign="top">Company Logo : </td>
				<td align="left" valign="top"><img align="middle" src="<?=_HTTP_SITE_ROOT;?>/<?php echo $Organizer->CLogo; ?>" height="120" width="120" title="<?=$Organizer->CURL;?>"/></td>
			</tr>
			<tr>
				<td align="left" valign="top" colspan="2"><hr /></td>
			</tr>
			<tr>
				<td align="left" valign="top" colspan="2"><b>Personal Details</b></td>
			</tr>
			<tr>
				<td align="left" valign="top">Name: </td>
				<td align="left" valign="top">
				<?php
					$SalutationId = $Organizer->Salutation;
					switch($SalutationId)
					{
						case 1 :
								echo "Mr. ";
								break;
						case 2 :
								echo "Miss. ";
								break;
						case 3 :
								echo "Mrs. ";
								break;
						case 4 :
								echo "Msr. ";
								break;
						case 5 :
								echo "Dr. ";
								break;
					}
					echo $Organizer->FirstName.' '.$Organizer->MiddleName.' '.$Organizer->LastName;
					?>
					</td>
				</tr>
			<tr>
				<td align="left" valign="top">Designation: </td>
				<td align="left" valign="top">
				<?php
					$desgn = new cDesignations($Organizer->DesignationId,"");
					$desgn->Load();
					echo $desgn->Designation; 
				?>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">Address: </td>
				<td align="left" valign="top"><?php echo $Organizer->Address; ?></td>
			</tr>
			<tr>
				<td align="left" valign="top">Country: </td>
				<td align="left" valign="top">
				<?php 
					$Countries = new cCountries($Organizer->CCountyId,"");
					$Countries->Load();
					echo $Countries->Country; 
				?></td>
			</tr>
			<tr>
				<td align="left" valign="top">State: </td>
				<td align="left" valign="top">
				<?php
					$States = new cStates($Organizer->CStateId,"","");
					$States->Load();
					echo $States->State;
				?>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">City: </td>
				<td align="left" valign="top">
				<?php
					$Cities = @new cCities($Organizer->CCidd);
					$Cities->Load();
					echo $Cities->City;
				?>				
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">PIN: </td>
				<td align="left" valign="top">
				<?php echo $Organizer->PIN; ?>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">Phone No: </td>
				<td align="left" valign="top">
				<?php echo $Organizer->Phone; ?>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top">Mobile No: </td>
				<td align="left" valign="top">
				<?php echo $Organizer->Mobile; ?>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top" colspan="2"><hr /></td>
			</tr>
			<tr>
				<td colspan="2" align="left" valign="top"><b>Preferences</b></td>
			</tr>
			<tr>
				<td align="left" valign="top">Subscribe To Newsletter :</td>
				<td align="left" valign="top">
				<?php
				$NewslettersubId = $Organizer->NewsletterSub;
				switch($NewslettersubId)
				{
					case 1 :
							echo "Yes";
							break;
					case 0 :
							echo "No";
							break;
				}
				?>
				</td>
			</tr>
			<?php
		//	}
		//	}//ends if
			?>
		</table>
</div>
<!------------------------------- PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>