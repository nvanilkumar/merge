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
<script language="javascript">
  	document.getElementById('ans3').style.display='block';
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.min.js.gz"></script>

<script>
	function deletusr(delid)
	{
		if (confirm('Are you sure to DELETE this user?') ) 
		{
			window.location = 'edituser.php?action=delete&delid='+delid;
		} 
		else 
		{
			return false;
		}
	}
	function editusr(editid)
	{
		window.location = 'adduser.php?id='+editid;
	}
	function actv(id1)
	{
		if (confirm('Do you want to Activate User?') ) 
		{
			document.frmallusr.act_id.value = id1;
			document.frmallusr.action.value = 'activate';
			document.frmallusr.submit();
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
			document.frmallusr.act_id.value = id2;
			document.frmallusr.action.value = 'deactivate';
			document.frmallusr.submit();
		} 
		else 
		{
			return false;
		}		
	}
</script>
<div>
	<form name="frmallusr" method="post" action="">
	<input type="hidden" name="act_id" value="">
	<input type="hidden" name="action" value="">
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr><td colspan="4">&nbsp;</td></tr>
			<tr><td colspan="4" class="headtitle" align="center">Edit/Delete Users</td></tr>
			<tr>
			  <td colspan="4">&nbsp;</td>
		  </tr>
			<tr><td colspan="4"><div align="center" style="padding-left:100px; width:50%"><a href="user.php" class="menuhead" title="User Management Home">
            User Management Home</a></div></td></tr>
			<tr>
				<td colspan="4">
					<table width="50%" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-4 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating" align="center">
					<thead>
                    	<tr>
							<td align="center" class="tblcont1">User Name</td>
							<td align="center" class="tblcont1">User Designation</td>
							<td align="center" class="tblcont1" ts_nosort="ts_nosort">Action</td>
							<td align="center" class="tblcont1" ts_nosort="ts_nosort">Edit</td>
							<td align="center" class="tblcont1" ts_nosort="ts_nosort">Delete</td>
						</tr>
                    </thead>
						<?php
							for($i = 0; $i < count($UsersList); $i++)
							{
						?>
						<tr>
							<td align="center"  class="helpBod"><?php echo $UsersList[$i]['UserName']; ?></td>
							<td align="center"  class="helpBod"><?php echo $UsersList[$i]['Designation']; ?></td>
							<td align="center" class="helpBod">
							<?php
									if($UsersList[$i]['Active'] == 1)
									{
										echo '<a class="lnk" href="#" onclick="dactv('.$UsersList[$i]['Id'].')">SUSPEND</a>';
									}
									else
									{
										echo '<a class="lnk" href="#" onclick="actv('.$UsersList[$i]['Id'].')">ACTIVATE</a>';
									}
								?>							
							</td>
							<td align="center"  class="helpBod">
                            <img src="images/edit.jpg" border="0" style="cursor:pointer;" onclick="editusr(<?php echo $UsersList[$i]['Id']; ?>)" />
                            </td>
							<td align="center" class="helpBod">
                            <img src="images/delet.jpg" border="0" style="cursor:pointer;" onclick="deletusr(<?php echo $UsersList[$i]['Id']; ?>)" />
                            </td>
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
<!-------------------------------USER MANAGEMENT PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>