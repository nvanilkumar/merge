<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -MeraEvents - Admin Panel - User Management - Manage Organizer</title>
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
<div align="center" style="width:100%" class="headtitle">Manage ServiceProvider</div>
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
		<input type="hidden" name="strt_date" value="<?=$_REQUEST['strt_date']?>" />
		<input type="hidden" name="end_date" value="<?=$_REQUEST['end_date']?>" />
		<input type="hidden" name="orgId" value="" />
		<input type="hidden" name="newAffiliateStatus" value="" />
		<table width="95%" align="center">
			<tr>
				<td colspan="2">
					<a href="user.php" class="menuhead" title="User Management Home">User Management Home</a>&nbsp;&nbsp;
					<a href="manageservicep.php" class="menuhead" title="Manage ServiceProvider Home">Manage ServiceProvider Home</a>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $msgOrganizerStatus; ?></td>
			</tr>
		</table>
		<table width="95%" align="center" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-3 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating">
<thead>
			<tr>
				<td width="12%" align="center" class="tblcont1">Name</td>
				<td width="10%" align="center" class="tblcont1">City</td>
				<td width="21%" align="center" class="tblcont1">Company Name</td>
				<td width="12%" align="center" ts_nosort="ts_nosort" class="tblcont1">Affiliate</td>
                <td width="12%" align="center" ts_nosort="ts_nosort" class="tblcont1">Trusted</td>
                <td width="12%" align="center" ts_nosort="ts_nosort" class="tblcont1">Action</td>
			</tr>
          </thead>
			<?php
			if(count($SpList) > 0) 
			{
			for($m=0; $m < count($SpList); $m++)
			{
			
					$window_url = "http://www.meraevents.com/myaccount_sp.php?UserType=ServiceProvider&spidadmin=".$SpList[$m]['UserId'];
				
			?>
			<tr>
				<td align="left" valign="top" class="helpBod"><a href="#" onclick="window.open('<?=$window_url?>','mywindow','menubar=1,width=900,height=600,resizable=yes,scrollbars=yes');"><?php echo $SpList[$m]['UserName']; ?></a></td>
				<td align="left" valign="top" class="helpBod"><?php echo $SpList[$m]['City']; ?></td>
				<td align="left" valign="top" class="helpBod"><?php echo $SpList[$m]['Company']; ?></td>
				
				<?php 
				$newIsAffiliateStatus = 0; 
				?>
				<td align="left" valign="middle" class="helpBod">
				<input type="checkbox" name="serIsAffiliate" id="serIsAffiliate" <?php if($SpList[$m]['IsAffiliate']==1) { $newIsAffiliateStatus = 0; ?> checked="checked" value="<?=$SpList[$m]['IsAffiliate']?>" <?php } else { $newIsAffiliateStatus = 1; ?> value="<?=$SpList[$m]['IsAffiliate']?>" <?php } ?> onclick="updateIsAffiliate('<?php echo $SpList[$m]['Id']; ?>','<?=$newIsAffiliateStatus?>');" />
				</td>	
                	
				<?php 
				$newIsTrustedStatus = 0; 
				?>
				<td align="left" valign="middle" class="helpBod">
				<input type="checkbox" name="spIsTrusted" id="spIsTrusted" <?php if($SpList[$m]['IsTrusted']==1) { $newIsTrustedStatus = 0; ?> checked="checked" value="<?=$SpList[$m]['IsTrusted']?>" <?php } else { $newIsTrustedStatus = 1; ?> value="<?=$SpList[$m]['IsTrusted']?>" <?php } ?> onclick="updateIsTrusted('<?php echo $SpList[$m]['Id']; ?>','<?=$newIsTrustedStatus?>');" />
				</td>		
                <td align="left" valign="middle" class="helpBod"><a href="listsp.php?action=delete&spdelid=<?=$SpList[$m]['UserId']?>" onclick="return confirm('Are you sure to delete this image');">Delete</a></td>			
			<?php
			}
			}//ends if
			else
			{
			?>
			<tr>
				<td align="left" valign="middle" class="helpBod" colspan="5"><?php echo "No Result Match Found!"; ?></td>
			</tr>
			<?php 
			}
			?>
		</table>
		<script language="javascript" type="text/javascript">
			function updateIsAffiliate(oId,nAffStatus)
			{
				window.location = 'listsp.php?spId='+oId+'&newAffiliateStatus='+nAffStatus;
				
			}
			function updateIsTrusted(oId,nTrusStatus)
			{
				window.location = 'listsp.php?spId='+oId+'&newTrustedStatus='+nTrusStatus;
				
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