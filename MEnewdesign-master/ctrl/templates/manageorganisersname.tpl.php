<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - User Management - Manage Organizer</title>
	<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
	<script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>	
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
<script language="javascript">
  	document.getElementById('ans20').style.display='block';
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
<script language="javascript" type="text/javascript">
	
	function valid()
	{
		var name = document.frmorgsearch.orgname.value;
		
			
			if(name=="")
			{
				alert('Please Enter OrganizerDisplay Name.');
				return false;
			}
		}
	
</script>
<div>

<table width="100%" cellpadding="0" cellspacing="5" style="padding-left:20px;" >
<tr>
                <td colspan="4" align="center" class="headtitle">Manage Organisers Name</td>
            </tr>
            <tr>
                <td colspan="4" align="left" ><a href="manage_organizersnamesadd.php">Add New Organisers Name</a></td>
            </tr>
            <tr><td colspan="6">
            <table width="100%" cellpadding="0"  cellspacing="0" border="1" >
            <tr>
                <td  align="center" >Sno</td><td  align="center" >Organizer Display Name</td><td  align="center" >Organizer URL</td><td  align="center" >Organizer Name</td><td  align="center" >UserNames</td><td  align="center" >Status</td><td  align="center" >&nbsp;</td>
            </tr>  
          <?php   for($i=0; $i < count($dtlOrgdisp); $i++)
                    {   ?>
            <tr>
                <td  align="center" ><?php echo $i+1;?></td><td  align="center" ><?php echo $dtlOrgdisp[$i]['orgDispName'];?></td><td align="center"><?php echo _HTTP_SITE_ROOT;?>/ByOrganizer/<?php echo $dtlOrgdisp[$i]['orgDispName'];?>/<?php echo $dtlOrgdisp[$i]['Id'];?></td><td  align="center" width="40%" ><?php 
                  $sqlid = "SELECT o.id AS Id,o.userid AS UserId FROM organizationuser AS o where organizationid=".$dtlOrgdisp[$i]['Id'] ;
                $dtsqlid = $Global->SelectQuery($sqlid);
                for($j=0; $j < count($dtsqlid); $j++)
                    {
                     echo $Global->GetSingleFieldValue("SELECT company FROM user where Id='".$dtsqlid[$j]['UserId']."'")." | ";   
                    }
                ?></td><td  align="center" width="20%" ><?php 
                 $sqlid = "SELECT o.id AS Id,o.userid AS UserId FROM organizationuser AS o where organizationid=".$dtlOrgdisp[$i]['Id'] ;
    $dtsqlid = $Global->SelectQuery($sqlid);
                for($j=0; $j < count($dtsqlid); $j++)
                    {
                     echo $Global->GetSingleFieldValue("SELECT username FROM user where Id='".$dtsqlid[$j]['UserId']."'")." | ";   
                    }
                ?></td><td  align="center" ><input type="checkbox" name="chkstatus" <?php if($dtlOrgdisp[$i]['Active']=='1') { $newStatus = 0; ?> checked="checked" value="1" <?php } else { $newStatus = 1; ?> value="0" <?php } ?> onclick="updatestatus('<?php echo $dtlOrgdisp[$i]['Id']; ?>','<?php echo $newStatus?>');" />
                 </td><td  align="center" ><a href="manage_organizersnamesadd.php?edit=<?php echo $dtlOrgdisp[$i]['Id'];?>">Edit</a></td>
            </tr>
          <?php   } ?>
            </table>
            </td></tr></table>
</div>

<!------------------------------- PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>
<script language="javascript" type="text/javascript">
    function updatestatus(Id,nStatus)
    {
        
        window.location = 'manage_organizersnames.php?Id='+Id+'&newStatus='+nStatus+'&Edit=ChangeStatus';
    }
</script>