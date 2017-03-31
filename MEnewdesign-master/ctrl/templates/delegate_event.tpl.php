<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -MeraEvents.com - Admin Panel - MIS Reports - Delegates Report</title>
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
<!-------------------------------Delegates Report PAGE STARTS HERE--------------------------------------------------------------->
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.min.js.gz"></script>

<div align="center" style="width:100%">
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">MIS Reports - Delegate Report</div>
<div align="center" style="width:100%">&nbsp;</div>
	<?php
		if($nid != 0)
		{
	?>
		<div id="regevent" align="center">
			<table width="50%" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-4 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating">
  				<tr>
					<td width="51%" align="center" class="tblcont1">Delegate Name</td>
					<td width="16%" align="center" class="tblcont1">City</td>
					<td width="33%" align="center" class="tblcont1">Company</td>
				</tr>
				<?php
				for($i = 0; $i < count($DelegateEvent); $i++)
				{
				?>
				<tr>
					<td align="left" valign="middle" class="helpBod"><?php echo $DelegateEvent[$i]['DisplayName']; ?></td>
					<td align="left" valign="middle" class="helpBod"><?php echo $DelegateEvent[$i]['City']; ?></td>
					<td align="left" valign="middle" class="helpBod"><?php echo $DelegateEvent[$i]['Company']; ?></td>
				</tr>
				<?php	
				}
				?>
			</table>
            <form action="" method="post">
				<input type="hidden" name="event" value="<?php echo $nid; ?>" />
				<!--input type = "hidden" name="sql_query" value="<?php /*?><?php echo $sql_for_csv; ?><?php */?>"-->
				<input type="Submit" name="Submit" value="Export As CSV">
		   </form>	
  </div>
	<?php	
		}
		else
		{
	?>
			<div id="allevent" align="center">
				<table width="60%" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-4 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating">
					<tr>
						<td width="34%" align="center" valign="middle"  class="tblcont1">Sr. No.</td>
						<td width="34%" align="center" valign="middle"  class="tblcont1">Event name</td>
						<td width="27%" align="center" valign="middle"  class="tblcont1">Delagate Name</td>
						<td width="15%" align="center" valign="middle"  class="tblcont1" ts_nosort="ts_nosort">City</td>
						<td width="24%" align="center" valign="middle"  class="tblcont1" ts_nosort="ts_nosort">Company</td>
					</tr>
					<?php
						for($j = 0; $j < count($AllEventsDelegates); $j++)
						{
							$cnt = 1;
					?>
						<tr>
							<td align="left" valign="middle" class="helpBod"><?php echo $cnt++; ?></td>
							<td align="left" valign="middle" class="helpBod"><?php echo $AllEventsDelegates[$j]['EventName']; ?></td>
							<td align="left" valign="middle" class="helpBod"><?php echo $AllEventsDelegates[$j]['DisplayName']; ?></td>
							<td align="left" valign="middle" class="helpBod"><?php echo $AllEventsDelegates[$j]['City']; ?></td>
							<td align="left" valign="middle" class="helpBod"><?php echo $AllEventsDelegates[$j]['Company']; ?></td>
						</tr>
					<?php
						}
					?>
				</table>
				<form action="" method="post">
					<!--input type="hidden" name="event" value="<?php /*?><?php echo $nid; ?><?php */?>" /-->
					<!--input type = "hidden" name="sql_query" value="<?php /*?><?php echo $sql_for_csv; ?><?php */?>"-->
					<input type="Submit" name="Save" value="Export As CSV">
				</form>	
			</div>
		<?php	
			}
		?>
</div>
<!-------------------------------Delegate Report PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>