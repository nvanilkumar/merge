<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management - TEDx2014</title>
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
        <script type="text/javascript">
		function validateForm(f)
		{
			var email=f.email.value;
			
			if(email=="" || email==null)
			{
				alert("Please enter Email Id");
				f.email.focus();
				return false;
			}
			else if(!validEmail(email))
			{
				alert("Please enter a valid Email Id");
				f.email.focus();
				return false;
			}
			
			
			return true;
			
		}
		function validEmail(e) {
			var filter = /^\s*[\w\-\+_]+(\.[\w\-\+_]+)*\@[\w\-\+_]+\.[\w\-\+_]+(\.[\w\-\+_]+)*\s*$/;
			return String(e).search (filter) != -1;
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
        <td colspan="2" class="headtitle" align="center"><strong>TEDx 2014 (BITS) Data</strong> </td>
      </tr>
      
      <?php 
	  if(isset($_SESSION['tedAdded']))
	  {
		  ?><tr><td colspan="2" style="color:#090; font-weight:bold; font-size:14px">New Email Id added successfully..</td></tr><?php
		  unset($_SESSION['tedAdded']);
	  }
	  if(isset($_SESSION['duplicateTED']))
	  {
		  ?><tr><td colspan="2" style="color:#F00; font-weight:bold; font-size:14px">Email Id already exist, please enter another..</td></tr><?php
		  unset($_SESSION['duplicateTED']);
	  }
	  if(isset($_SESSION['tedDeleted']))
	  {
		  ?><tr><td colspan="2" style="color:#090; font-weight:bold; font-size:14px">Email deleted successfully..</td></tr><?php
		  unset($_SESSION['tedDeleted']);
	  }
	  ?>
      
      <tr>
        <td colspan="2">
        
        <form method="post" action="" onsubmit="return validateForm(this)">
        	<table>
                <tr>
                	<td>Email Id:&nbsp;<input type="text" name="email" id="email" /></td>
                    <td><input type="submit" name="addMember" value="ADD" /></td>
               </tr>
               <tr><td colspan="3"><br />&nbsp;</td></tr>
            </table>
        </form>
        
        </td>
      </tr>
      <?php
	  $totRec=count($tedList);
	  ?>
      
      <tr><td colspan="2" align="right"><span style="color:#930">Total <b><?php echo $totRec; ?></b> records found.</span></td></tr>
      
      <tr>
        <td colspan="2"><table width="100%" class="sortable">
          <thead>
          <tr>
            <td class="tblcont1"><strong>Sno</strong> </td>
            <td class="tblcont1" ts_nosort="ts_nosort"><strong>Email Id</strong> </td>
            <td class="tblcont1" ts_nosort="ts_nosort"><strong>Status</strong> </td>
            <td class="tblcont1" ts_nosort="ts_nosort"><strong>Delete</strong></td>
          </tr></thead>
		  <?php 
		  $sno=1;
			for($i = 0; $i < $totRec; $i++)
			{  
		  ?>
          <tr>
            <td class="helpBod"><?php echo $sno;	?></td>
            <td class="helpBod"><?php echo $tedList[$i]['Email']; ?></td>
            <td class="helpBod"><?php echo ($tedList[$i]['Status']==1)?'Used':'Unused'; ?></td>
            <td class="helpBod"><a href="?delmember=<?=$tedList[$i]['Id']?>"  onClick="return confirm('Are You Sure You Want To Delete this Member.\n\nThe Member Cannot Be Undone');">Delete</a></td>
          </tr>
		  <?php 
		  $sno++;
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