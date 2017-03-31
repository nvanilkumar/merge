<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
        <script type="text/javascript">
		function validateForm(f)
		{
			var memid=f.memberid.value;
			var memname=f.memname.value;
			
			if(memid=="" || memid==null)
			{
				alert("Please enter Membership Id");
				f.memberid.focus();
				return false;
			}
			else if(isNaN(memid))
			{
				alert("Only numbers allowed in Membership Id");
				f.memberid.focus();
				return false;
			}
			
			if(memname=="" || memname==null)
			{
				alert("Please enter Member Name");
				f.memname.focus();
				return false;
			}
			return true;
			
		}
		</script>
        
        
        
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
	
	
	<!-------------------------------CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<div style="width:100%" align="center">
<table width="50%">
      <tr>
        <td colspan="2" class="headtitle" align="center"><strong>PMI Chennai Data</strong> </td>
      </tr>
      
      <?php 
	  if(isset($_SESSION['pmiAdded']))
	  {
		  ?><tr><td colspan="2" style="color:#090; font-weight:bold; font-size:14px">New Member added successfully..</td></tr><?php
		  unset($_SESSION['pmiAdded']);
	  }
	  if(isset($_SESSION['duplicatePmi']))
	  {
		  ?><tr><td colspan="2" style="color:#F00; font-weight:bold; font-size:14px">Membership Id already exist, please enter another..</td></tr><?php
		  unset($_SESSION['duplicatePmi']);
	  }
	  if(isset($_SESSION['pmiDeleted']))
	  {
		  ?><tr><td colspan="2" style="color:#090; font-weight:bold; font-size:14px">Member deleted successfully..</td></tr><?php
		  unset($_SESSION['pmiDeleted']);
	  }
	  ?>
      
      <tr>
        <td colspan="2">
        
        <form method="post" action="" onsubmit="return validateForm(this)">
        	<table>
                <tr>
                	<td>Membership Id:&nbsp;<input type="text" name="memberid" id="memberid" /></td>
                    <td>Name:&nbsp;<input type="text" name="memname" id="memname" /></td>
                    <td><input type="submit" name="addMember" value="ADD" /></td>
               </tr>
               <tr><td colspan="3"><br />&nbsp;</td></tr>
            </table>
        </form>
        
        </td>
      </tr>
      <tr>
        <td colspan="2"><table width="100%" class="sortable">
          <thead>
          <tr>
            <td class="tblcont1"><strong>Membership Id</strong> </td>
            <td class="tblcont1" ts_nosort="ts_nosort"><strong>Name</strong> </td>
            <td class="tblcont1" ts_nosort="ts_nosort"><strong>Status</strong> </td>
            <td class="tblcont1" ts_nosort="ts_nosort"><strong>Delete</strong></td>
          </tr></thead>
		  <?php 
			for($i = 0; $i < count($pmiList); $i++)
			{  
		  ?>
          <tr>
            <td class="helpBod"><?php echo $pmiList[$i]['MemberShipId'];	?></td>
            <td class="helpBod"><?php echo $pmiList[$i]['Name']; ?></td>
            <td class="helpBod"><?php echo ($pmiList[$i]['Status']==1)?'Used':'Unused'; ?></td>
            <td class="helpBod"><a href="?delmember=<?=$pmiList[$i]['Id']?>"  onClick="return confirm('Are You Sure You Want To Delete this Member.\n\nThe Member Cannot Be Undone');">Delete</a></td>
          </tr>
		  <?php 
			  }
		  ?>
        </table></td>
      </tr>
      
    </table>
<div align="center" style="width:100%">&nbsp;</div>
</div>
<!-------------------------------CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	
	
	</div>
	</td>
  </tr>
</table>
	</div>	
</body>
</html>