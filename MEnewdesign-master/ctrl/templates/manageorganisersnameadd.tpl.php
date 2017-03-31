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
        //alert('1  :'+document.getElementById('selOrg').value);
        var org_name=document.getElementById('selOrg').value;	
            if(name==""){
                alert('Please Enter OrganizerDisplay Name.');
                return false;
            }
            if(org_name=="-1") {
                alert('Please Select the Organizer');
                return false;
            }            
        return true;
    }
</script>

<div>
	<form name="frmorgsearch" action=" " method="post" onSubmit="return valid();" enctype="multipart/form-data">
		<table width="100%" cellpadding="0" cellspacing="5" style="padding-left:20px;">
			
			<tr>
                <td colspan="4" align="center" class="headtitle"><?php if (isset($_REQUEST['edit']) && $_REQUEST['edit']!='') echo 'Edit '; else echo 'Add '; ?>Organisers Name</td>
            </tr>
			<tr><td colspan="4">&nbsp;</td></tr>
			<tr>
            	<td>
                	<table align="center" width="100%" cellpadding="2" cellspacing="5" style="border:thin; border-color:#006699; border-style:solid;">
                    	<tr>
                    	  <td width="16%">&nbsp;</td>
                       	  <td width="26%" align="left" valign="middle" class="tblcont">Organizer Display Name</td>
                       	  <td width="7%" align="center" valign="middle" class="tblcont">:</td>
				<td colspan="3" align="left" valign="middle"><input type="text" name="orgname" id="orgname" value="<?php echo $dtlOrgnameedit[0]['name'];?>" /></td>
			</tr>
             
            <tr>
                    	  <td width="16%">&nbsp;</td>
                       	  <td width="26%" align="left" valign="middle" class="tblcont">Description</td>
                       	  <td width="7%" align="center" valign="middle" class="tblcont">:</td>
				<td colspan="3" align="left" valign="middle">
                    <textarea rows="4" cols="50" name="org_description"><?php echo $dtlOrgnameedit[0]['information'];?></textarea>
               </td>
			</tr>
             
             <tr>
                    	  <td width="16%">&nbsp;</td>
                       	  <td width="26%" align="left" valign="middle" class="tblcont">Banner Upload</td>
                       	  <td width="7%" align="center" valign="middle" class="tblcont">:</td>
				<td colspan="3" align="left" valign="middle">
                    <span style="color:#099">Banner image size should be 1140px-Width &amp; 330px-Height for better view <br/></span>
                    <input name="org_banner" id="org_banner" size="40" type="file" value="">
                       <input type="hidden" name="org_banner_old" value="<?php echo $dtlOrgnameedit[0]['bannerpathid'];?>"/> 
                       <?php if(($_REQUEST['edit']!='') && $dtlOrgnameedit[0]['bannerpathid']!=0 ){?>
                       <img src="<?php echo CONTENT_CLOUD_PATH.$dtlOrgnameedit[0]['bannerpath'];?>" width="100" height="50"/>
                       <?php }?>
                </td>
			</tr>           
			
			<tr>
			  <td>&nbsp;</td>
				<td align="left" valign="middle" class="tblcont">Organizer</td>
				<td align="center" valign="middle" class="tblcont">:</td>
				<td colspan="3" align="left" valign="middle" valign="top">
                    <select name="selOrg[]" id="selOrg" multiple=multiple size=5>
                        <option value="-1" <?php echo ($_REQUEST['edit']!='')?'':'selected'; ?>>Select Organizer</option>
                <?php
                $dtlOrgCount=count($dtlOrg);
                    for($i=0; $i < $dtlOrgCount; $i++)
                    {
                    //Rather than the city id we are sending the city name here for uniqueness 
                ?>    <option value="<?php echo $dtlOrg[$i]['Id'];?>"><?php echo $dtlOrg[$i]['Company']." ".$dtlOrg[$i]['Email'];?></option>
                <?php    
                    }
                ?>
                    </select>
                </td>
                <?php if($_REQUEST['edit']!=''){ ?>
                <td width="50%" align="left" valign="middle">
                <table width="100%" border="1" cellpadding="2" cellspacing="0">
               
					<?php
//                 $sqlid = "SELECT o.Id,o.UserId FROM orgdispnameid AS o where OrgId=".$_REQUEST[edit] ;
//    $dtsqlid = $Global->SelectQuery($sqlid);
//                for($j=0; $j < count($dtsqlid); $j++)
//                    {
//                    //SHOULD COMBINE THIS QUERY WITH OUTER QUERY-Ph.
//                     echo " <tr><td>".$Global->GetSingleFieldValue("SELECT Company FROM user where Id='".$dtsqlid[$j][UserId]."'")."</td><td><a href=manage_organizersnamesadd.php?edit=$_REQUEST[edit]&delete=".$dtsqlid[$j][Id].">Delete</a></td></tr> ";   
//                    }
                                        
                     $sqlid = "SELECT odn.id, odn.userid, u.company "
                             . "FROM organizationuser odn, user u "
                             . "WHERE odn.userid = u.id AND odn.deleted=0 AND odn.organizationid = ".$_REQUEST['edit'];
                      $dtsqlid = $Global->SelectQuery($sqlid);
                      $countDtSqlId=count($dtsqlid);
                     for($j=0; $j < $countDtSqlId; $j++)
                     {
                         echo " <tr><td>".$dtsqlid[$j]['company']."</td><td><a href=manage_organizersnamesadd.php?edit=".$_REQUEST['edit']."&delete=".$dtsqlid[$j]['id'].">Delete</a></td></tr> ";   
                     }
                                        
                                        
                ?> </table>
				</td> <?php }?>
			</tr>
		
			<tr>
			  <td>&nbsp;</td>
				<td align="left" valign="middle" class="tblcont">Status</td>
				<td align="center" valign="middle" class="tblcont">:</td>
				<td colspan="3" align="left" valign="middle">
					<select name="sts">
						<option value="0">SELECT</option>
						<option value="1" <?php if($dtlOrgnameedit[0]['status']==1){?> selected <?php }?>>ACTIVE</option>
						<option value="0" <?php if($dtlOrgnameedit[0]['status']==0){?> selected <?php }?>>INACTIVE</option>
					</select>				</td>
			</tr>
			<tr>
				<td colspan="6" align="center">
                <?php if($_REQUEST['edit']!=""){ ?>
                <input type="submit" name="Update" value="Update">
                <?php } else {  ?>
                <input type="submit" name="Add" value="Add"></td>
                <?php } ?>
                        </tr>
                    </table>                </td>
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