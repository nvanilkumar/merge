<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - User Management - Manage Organizer</title>
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

<script>
	function actv(id1)
	{
		if (confirm('Do you want to Activate User?') ) 
		{
			document.frmorgdisplay.act_id.value = id1;
			document.frmorgdisplay.action.value = 'activate';
			document.frmorgdisplay.submit();
		} 
		else 
		{
			return false;
		}		
	}
	function dactv(id2)
	{
		if (confirm('Do you want to De-Activate User?') ) 
		{
			document.frmorgdisplay.act_id.value = id2;
			document.frmorgdisplay.action.value = 'deactivate';
			document.frmorgdisplay.submit();
		} 
		else 
		{
			return false;
		}		
	}
	function updtwht(uid,status)
	{
		if(status == 1)
		{
			if (confirm('Are you sure to make this User as a WhiteList User?') ) 
			{
				document.frmorgdisplay.uid.value = uid;
				document.frmorgdisplay.status.value = 1;
				document.frmorgdisplay.action.value = 'whtlist';
				document.frmorgdisplay.submit();
			} 
			else 
			{
				return false;
			}
		}	
		
		if(status == 0)
		{
			if (confirm('Are you sure to remove this user from list of WhiteList User?') ) 
			{
				document.frmorgdisplay.uid.value = uid;
				document.frmorgdisplay.status.value = 0;
				document.frmorgdisplay.action.value = 'whtlist';
				document.frmorgdisplay.submit();
			} 
			else 
			{
				return false;
			}
		}	
	}
</script>
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Manage Organizer</div>
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%">
	<form name="frmorgdisplay" action="" method="post">
		<input type="hidden" name="act_id" value="" />
		<input type="hidden" name="action" value="" />
		<input type="hidden" name="uid" value="" />
		<input type="hidden" name="sts" value="<?=$stats?>" />
		<input type="hidden" name="orguser" value="<?=$name?>" />
		<input type="hidden" name="orgemail" value="<?=$email?>" />
		<input type="hidden" name="orgcompany" value="<?=$comp?>" />
		<input type="hidden" name="selCity" value="<?=$City?>" />
		<input type="hidden" name="strt_date" value="<?=$_REQUEST['strt_date']?>" />
		<input type="hidden" name="end_date" value="<?=$_REQUEST['end_date']?>" />
		<input type="hidden" name="orgId" value="" />
		<input type="hidden" name="newAffiliateStatus" value="" />
		<table width="95%" align="center">
			<tr>
				<td colspan="2">
					<a href="user.php" class="menuhead" title="User Management Home">User Management Home</a>&nbsp;&nbsp;
					<a href="manageorganisers.php" class="menuhead" title="Manage Organisers Home">Manage Organisers Home</a>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $msgOrganizerStatus; ?></td>
			</tr>
			<tr>
				<td colspan="2"><?php echo "Total No. of Organizers in Result Set: ".count($OrgList); ?></td>
			</tr>
		</table>
		<?php $cntOrgList = 0; ?>
		<table width="95%" align="center" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-3 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating">
<thead>
			<tr>
				<td width="7%" align="center" class="tblcont1">Sr. No.</td>
				<td width="18%" align="center" class="tblcont1">Name</td>
				<td width="10%" align="center" class="tblcont1">City</td>
				<td width="20%" align="center" class="tblcont1">Company Name</td>
				<td width="25%" align="center" ts_nosort="ts_nosort" class="tblcont1">Posted Events</td>
				<td width="10%" align="center" class="tblcont1">Action</td>
				<td width="10%" align="center" ts_nosort="ts_nosort" class="tblcont1">Affiliate</td>
			</tr>
          </thead>
			<?php
			if(count($OrgList) > 0) 
			{
			for($m=0; $m < count($OrgList); $m++)
			{
				$cntOrgList++;
			?>
			<tr>
				<td align="left" valign="top" class="helpBod"><?php echo $cntOrgList.'.'; ?></td>
				<td align="left" valign="top" class="helpBod"><a href="org_details.php?Id=<?=$OrgList[$m]['UserId']?>"><?php echo $OrgList[$m]['UserName']; ?></a></td>
                                 <?
                                   $EventsQueryCity = "SELECT City FROM Cities WHERE Id='".$OrgList[$m]['City']."'";
						$EventsListcity = $Global->SelectQuery($EventsQueryCity);

                                 ?>
				<td align="left" valign="top" class="helpBod"><?php echo $EventsListcity[0]['City']; ?></td>
				<td align="left" valign="top" class="helpBod"><?php echo $OrgList[$m]['Company']; ?></td>
				<td align="left" valign="top" class="helpBod">
					<?php 
						$EventsQuery = "SELECT Id, Title, URL FROM events WHERE UserId='".$OrgList[$m]['UserId']."' ORDER BY Title ASC";
						$EventsList = $Global->SelectQuery($EventsQuery);
						$cnt = 1;
						for($j = 0; $j < count($EventsList); $j++)
						{
						?>
						<ul>
						<a href="<?=_HTTP_SITE_ROOT;?>/event/<?=$EventsList[$j]['URL'];?>" target="_blank" ><?php echo $cnt.'. '.stripslashes($EventsList[$j]['Title']); ?></a>
						</ul>			
						<?php
						$cnt++;
						}
					?>
				</td>
				<?php 
					$window_url = _HTTP_SITE_ROOT."/dashboard.php?UserType=Organizer&uid=".$OrgList[$m]['Id'];
				?>
				<td align="left" valign="top" class="helpBod">
				<a href="#" onclick="window.open('<?=$window_url?>','mywindow','menubar=1,width=900,height=600,resizable=yes,scrollbars=yes');">Edit</a>
				</td>
				<?php 
				$newIsAffiliateStatus = 0; 
				?>
				<td align="left" valign="top" class="helpBod">
				<?php 
				//Created the string for organizer is affiliate or not and according the variables are set and displayed.
				//be very careful while changing the code the variable is displayed in input box.
				if($OrgList[$m]['IsAffiliate']==1) 
				{ 
					$newIsAffiliateStatus = 0; 
					$strListOrg ='checked="checked"';
					$strListOrg .= 'value="'.$OrgList[$m]['IsAffiliate'].'"'; 
				} 
				else 
				{ 
					$newIsAffiliateStatus = 1; 
					$strListOrg = 'value="'.$OrgList[$m]['IsAffiliate'].'"'; 
				}
				?>
				<input type="checkbox" name="OrgIsAffiliate" id="OrgIsAffiliate" <?=$strListOrg?>  onclick="updateIsAffiliate('<?php echo $OrgList[$m]['Id']; ?>','<?=$newIsAffiliateStatus?>');" />
				</td>		
			<?php
			}
			}//ends if
			else
			{
			?>
			<tr>
				<td align="left" valign="middle" class="helpBod" colspan="7"><?php echo "No Result Match Found!"; ?></td>
			</tr>
			<?php 
			}
			?>
		</table>
		<script language="javascript" type="text/javascript">
			function updateIsAffiliate(oId,nAffStatus)
			{
				//window.location = 'listorganisers.php?orgId='+oId+'&newAffiliateStatus='+nAffStatus;
				document.frmorgdisplay.orgId.value=oId;
				document.frmorgdisplay.newAffiliateStatus.value=nAffStatus;
				document.frmorgdisplay.submit();
			}
		</script>
  </form>
</div>
<!------------------------------- PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>