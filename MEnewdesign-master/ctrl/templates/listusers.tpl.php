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
<!------------------------------- PAGE STARTS HERE--------------------------------------------------------------->
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.min.js.gz"></script>
<script>
	function actv(id1)
	{
		if (confirm('Do you want to Activate User?') ) 
		{
			document.frmusrdisplay.act_id.value = id1;
			document.frmusrdisplay.action.value = 'activate';
			document.frmusrdisplay.submit();
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
			document.frmusrdisplay.act_id.value = id2;
			document.frmusrdisplay.action.value = 'deactivate';
			document.frmusrdisplay.submit();
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
				document.frmusrdisplay.uid.value = uid;
				document.frmusrdisplay.status.value = 1;
				document.frmusrdisplay.action.value = 'whtlist';
				document.frmusrdisplay.submit();
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
				document.frmusrdisplay.uid.value = uid;
				document.frmusrdisplay.status.value = 0;
				document.frmusrdisplay.action.value = 'whtlist';
				document.frmusrdisplay.submit();
			} 
			else 
			{
				return false;
			}
		}	
	}
</script>
<div>
<form action="" method="post" name="frmusrdisplay">
	<input type="hidden" name="act_id" value="" />
	<input type="hidden" name="action" value="" />
	<input type="hidden" name="uid" value="" />
	<input type="hidden" name="status" value="" />
	<input type="hidden" name="user" value="<?=$name?>" />
	<input type="hidden" name="email" value="<?=$email?>" />
	<input type="hidden" name="strt_date" value="<?=$startdt?>" />
	<input type="hidden" name="end_date" value="<?=$enddt?>" />
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td class="headtitle" align="center">Manage Front End Users</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td align="center">
				<table width="90%" cellpadding="4" cellspacing="0" style="border-left:1px solid #999999; border-top:1px solid #999999; border-right:1px solid #999999;" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-4 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating">
				<tr bgcolor="#CCCCCC">
					<td width="18%" align="center" class="tblcont1" >Name</td>
					<td width="16%" align="center" class="tblcont1" >City</td>
					<td width="47%" align="center" class="tblcont1" ts_nosort="ts_nosort">Registered Events</td>
					<td width="19%" align="center" class="tblcont1" ts_nosort="ts_nosort">Action</td>
				</tr>
				<?php
				if(count($UsersList) > 0) 
				{
					for($i=0; $i < count($UsersList); $i++)
					{
				?>
				<tr <?php if($i % 2 != 0){ ?> bgcolor="#CBDEDB" <?php } ?> >
					<td class="helpBod" valign="top" ><?php echo $UsersList[$i]['UserName']; ?></td>
					<td class="helpBod" valign="top" ><?php echo $UsersList[$i]['City']; ?></td>
					<td class="helpBod" >
						<?php 
						$EventsQuery = "SELECT Id, Title FROM events WHERE UserId='".$UsersList[$i]['Id']."' ORDER BY Title ASC";
						$EventsOfMonth = $Global->SelectQuery($EventsQuery);
						$cnt = 1;
						for($j = 0; $j < count($EventsOfMonth); $j++)
						{
						?>
						<ul>
						<?php echo $cnt.'. '.$EventsOfMonth[$j]['Title']; ?>
						</ul>			
						<?php
						$cnt++;
						}
						?>
					</td>		
					<td align="center" style="border-bottom:1px solid #999999; border-left:1px solid #999999;">
						<?php 
							if($UserList[$i]['Active'] == 1)
							{
								echo '<a class="lnk" href="#" onclick="dactv('.$UserList[$i]['Id'].')">SUSPEND</a>';
							}
							else
							{
								echo '<a class="lnk" href="#" onclick="actv('.$UserList[$i]['Id'].')">ACTIVATE</a>';
							}
						?>
					</td>	
				</tr>		
				<?php
					}//ends for loop
				}//ends if
				else
				{
				?>
				<tr bgcolor="#CCCCCC">
					<td width="100%" align="center" class="tblcont1" colspan="4" >No Record Found</td>
				</tr>
				<?php 
				} 
				?>
			</table>
			</td>
		</tr>
	</table>		
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