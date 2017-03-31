<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - MIS Reports - Organizer Report</title>
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
<!-------------------------------Organizer Report PAGE STARTS HERE--------------------------------------------------------------->
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.min.js.gz"></script>
<div align="center" style="widows:100%">
	<div align="center" style="widows:100%">&nbsp;</div>
	<div align="center" style="width:100%" class="headtitle">MIS Reports - Organizer Report</div>
	<div align="center" style="width:100%">&nbsp;</div>
	<table width="70%" align="center" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-4 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating">
		<tr>
			<td width="22%" align="center" class="tblcont1">Organiser Name</td>
			<td width="11%" align="center" class="tblcont1">City</td>
			<td width="16%" align="center" class="tblcont1">Company</td>
			<td width="37%" align="center" class="tblcont1">Events</td>
			<td width="14%" align="center" class="tblcont1">No. of Events</td>
		</tr>
		<?php
		$cnt = 0;
		for($i = 0; $i < count($AllOrganizer); $i++)
		{
			//$cnt++;
		?>
		<tr>
			<td align="left" valign="top" class="helpBod"><?php echo $AllOrganizer[$i]['FirstName']; ?></td>	
			<td align="left" valign="top" class="helpBod"><?php echo $AllOrganizer[$i]['City']; ?></td>	
				<td align="left" valign="top" class="helpBod"><?php echo $AllOrganizer[$i]['Company']; ?></td>
				<td align="left" class="helpBod">
					<?php
					$cnt = 0;
					//echo	$EventQuery = "SELECT en.EventName FROM eventnamepref AS enp, events AS e, eventnames AS en WHERE enp.UserId = '".$AllOrganizer[$i]['UserId']."' AND e.UserId = enp.UserId AND enp.EventName = en.Id ORDER BY en.EventName ASC";
						$EventQuery = "SELECT en.EventName FROM eventnamepref AS enp, eventnames AS en WHERE enp.UserId = '".$AllOrganizer[$i]['UserId']."' AND  enp.EventName = en.Id ORDER BY en.EventName ASC";
						$AllEvent = $Global->SelectQuery($EventQuery);
						
						for($j = 0; $j < count($AllEvent); $j++)
						{
					?>
					<ul>
						<?php echo $AllEvent[$j]['EventName']; ?>
					</ul>
					<?php
							$cnt++; 
						}
					?>
				</td>
				<td align="center" class="helpBod"><?php echo $cnt; ?></td>	
		</tr>
		<?php
		}
		?>
	</table>	 
</div>
<!-------------------------------Organizer Report PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>