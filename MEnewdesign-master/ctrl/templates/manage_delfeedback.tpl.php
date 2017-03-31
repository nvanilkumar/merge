<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>meraevents.com - Admin Panel - Manage Delegate Feedback</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" rel="stylesheet" type="text/css" media="all" />	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
	<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.min.js.gz"></script>
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
				<div id="divMainPage" style="margin-left:10px; margin-right:5px;">
<!-------------------------------Manage Banner PAGE STARTS HERE--------------------------------------------------------------->
					<div align="center" style="width:100%">&nbsp;</div>
					<div align="center" style="width:100%" class="headtitle">Manage Delegate Feedback</div>
					<div align="center" style="width:100%">&nbsp;</div>
					<div><?=$msgActionStatus?></div>
					<div id="add_image">
					
					</div>
					<table width="100%" align="center" border="1">
   <tr bgcolor="#CCCCCC">
    <td>Sno</td>
    <td>Name</td>
    <td>Title</td>
    <td>Email</td>
    <td>Mobile</td>
    <td width="40%">FeedBack</td>
    <td>Posted Date</td>
    <td>Status</td>
    <td>Edit</td>
    <td>Delete</td>
    
  </tr>
  <?
  $totalFeedbacks=count($ResFeed);
              if($totalFeedbacks >0)
              {
		for($i=0;$i < $totalFeedbacks; $i++)
		{  
  ?>
  <tr>
    <td><?=$i+1;?></td>
    <td><?=$ResFeed[$i][vFName];?></td>
    <td><?=$ResFeed[$i][Title];?></td>
    <td><?=$ResFeed[$i][vEmail];?></td>
    <td><?=$ResFeed[$i][vMobile];?></td>
    <td><?=$ResFeed[$i][tComment];?></td>
    <td><?=$ResFeed[$i][eStatus];?></td>
    <td><?=$ResFeed[$i][dPDate];?></td>
    <td align="center"><a href="manage_delfeedback_edit.php?editid=<?=$ResFeed[$i][Id];?>&Ax=Yes"><img src="images/edit.gif" border="0" /></a></td>
    <td align="center"><a href="manage_delfeedback.php?itdel=<?=$ResFeed[$i][Id];?>&Ax=Yes"><img src="images/delete.gif" border="0" /></a></td>
  </tr>
  <? } }else {?>
 <tr>
    <td>&nbsp;</td>
    <td colspan="10">No Feedbacks Found</td>
  </tr>
<? } ?>
</table>

<!-------------------------------Manage Banner PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		  </tr>
		</table>
	</div>	
</body>
</html>