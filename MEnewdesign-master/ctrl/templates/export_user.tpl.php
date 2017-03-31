<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - Export User List</title>
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
<!-------------------------------Export User List PAGE STARTS HERE--------------------------------------------------------------->

<form action="" method="post">
<table width="100%" border="0" align="center">
  <tr>
    <td colspan="2" align="center" valign="middle" class="headtitle"><strong>Export User List</strong> </td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    </tr>
  <tr>
  	<td>
    	<table align="center" cellpadding="2" cellspacing="5" width="50%" style="border:thin; border-color:#006699; border-style:solid;">
       	  <tr>
       	    <td align="left" valign="middle">&nbsp;</td>
       	    <td align="left" valign="middle">&nbsp;</td>
       	    <td align="center" valign="middle">&nbsp;</td>
       	    <td align="left" valign="middle">&nbsp;</td>
   	      </tr>
       	  <tr>
       	    <td width="14%" align="left" valign="middle">&nbsp;</td>
       	    <td width="32%" align="left" valign="middle" class="tblcont">Event</td>
       		  <td width="9%" align="center" valign="middle" class="tblcont"> : </td>
    <td width="45%" align="left" valign="middle"><label>
      <select name="event" style="width:170px;">
		  <option value="0">--Select--</option>
		  <?php 
		  for($i = 0; $i < count($EventList); $i++)
		  {
		  ?>
		  <option value="<?php echo $EventList[$i]['Id']; ?>"><?php echo $EventList[$i]['EventName']; ?></option>
		  <?php 
		  }
		  ?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">City</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><label>
      <select name="city" style="width:170px;">
	  	<option value="0">--Select--</option>
	  	<?php for($i = 0; $i < count($CityList); $i++) 
	  	{
	  	?>
	  	<option value="<?php echo $CityList[$i]['Id']?>"><?php echo $CityList[$i]['City']; ?></option>
	  	<?php 
	  	}
	  	?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Organiser</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><label>
      <select name="organiser" style="width:170px;">
		<option value="0">--Select--</option>
		<?php 
		for($i = 0; $i < count($OrganizerList); $i++) 
		{
		?>
		<option value="<?php echo $OrganizerList[$i]['UserId']; ?>"><?php echo $OrganizerList[$i]['FirstName'].' '.$OrganizerList[$i]['LastName']; ?></option>
		<?php 
		}
		?>
	  </select>
    </label></td>
  </tr>
  <tr>
    <td colspan="4" align="center" valign="middle"><label>
    <input type="submit" name="Submit" value="Export To CSV">
    </label></td>
    </tr>
        </table>    </td>
  </tr>
</table>
</form>
<!-------------------------------Export User List PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>