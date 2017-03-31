<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - City Management</title>
    
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
    <script src="<?=_HTTP_CF_ROOT;?>/js/public/jQuery.js"></script>
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>
    <link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.min.css.gz" />
	<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.min.js.gz"></script>	
    <script type="text/javascript" language="javascript">
       
	function validateEsignUpForm(f)
	{
		var esid=f.evtsignupid.value;
		if(esid.length=='' || esid==null)
		{
			alert("Please enter Event Signup ID");
			f.evtsignupid.focus();
			return false;
		}
		else
		{
			var res=0;
			var inputString='checkEventSignupId=1&id='+esid;
			$.ajax({
			  url: "<?=_HTTP_SITE_ROOT;?>/ctrl/processAjaxRequests.php",
			  type:"post",
			  data:inputString,
			  cache: false,
			  async: false,
			  success: function(result){
				  if(result=='success')
				  {
					  return true;
				  }
				  else
				  {
					  res=1;
					  
				  }
			  }
			});
			
			if(res>0)
			{
				alert("Invalid Event Signup ID");
				f.evtsignupid.focus();
				return false;
			}
		}
		//return true;
	}
	function validateForm(f)
	{
		var Name=$("#Name").val();
		var EMail=$("#EMail").val();
		var Phone=$("#Phone").val();
		var Address=$("#Address").val();
		var SignupDt=$("#SignupDt").val();;
		
		
		if(Name.length=='' || Name==null)
		{
			alert("Please enter Event Signup name");
			$("#Name").focus();
			return false;
		}
		if(EMail.length=='' || EMail==null)
		{
			alert("Please enter Event Signup email id");
			$("#EMail").focus();
			return false;
		}
		if(Phone.length=='' || Phone==null)
		{
			alert("Please enter Event Signup phone number");
			$("#Phone").focus();
			return false;
		}
//		if(Address.length=='' || Address==null)
//		{
//			alert("Please enter address");
//			f.Address.focus();
//			return false;
//		}
		if(SignupDt.length=='' || SignupDt==null)
		{
			alert("Please enter Event Signup date");
			$("#Address").focus();
			return false;
		}
		return true;
	}
	</script>
    <style>
    .delete_button{position: relative; top: -29px;}
    </style>
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
				<div id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------CITY LIST PAGE STARTS HERE--------------------------------------------------------------->

<div align="center" style="width:100%">
<form action="" method="get" name="edit_form" onsubmit="return validateEsignUpForm(this);">
<table width="60%" border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td align="center" valign="middle" class="headtitle"><strong>Update Event Signup Details</strong> </td>
      </tr>
      
      <?php
	  if(isset($_SESSION['esUpSucc']))
	  {
		  ?>
          <tr><td style="color:#090; font-weight:bold;">Event Signup Id <?php echo $_SESSION['esUpSucc']; ?> details updated successfully..</td></tr>
          <?php
		  unset($_SESSION['esUpSucc']);
	  }
	  if(isset($_SESSION['attUpSucc']))
	  {
		  ?>
          <tr><td style="color:#090; font-weight:bold;">Attendee details updated successfully..</td></tr>
          <?php
		  unset($_SESSION['attUpSucc']);
	  }
	  ?>
      
      <tr>
        <td>Event Signup Id <input type="text" name="evtsignupid" id="evtsignupid" value="<?php echo $esid; ?>" />
            <input type="submit" value="Edit" /></td>
      </tr>
	  <tr><td><?php echo $errormsg; ?></td></tr>
     
    </table>
</form>
<br /><br />
<?php if((count($data)>0) && (!isset($_POST['delete_signup'])) ){$display='block';}else{$display='none';} ?>
<form action="" method="post" name="edit_form" onsubmit="return validateForm(this)" style="display:<?php echo $display; ?>">
<table width="60%" border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td align="center" colspan="2" valign="middle" class="headtitle"><strong>Event Signup Details Edit</strong> </td>
      </tr>
      <td>Event Signup Id </td><td><?php echo $esid; ?></td>
      <?php 
	 // if($commonFunctions->isSuperAdmin() || $commonFunctions->isSupportTeam())
	 // {
		 // print_r($data);
		$SignupDt = date("d/m/Y",strtotime($data[0]['SignupDt']));
	  ?>
      <tr><td>Name </td><td>
              <input type="text" name="Name" id="Name" value="<?php echo $data[0]['Name']; ?>"  />
              <input type="hidden" name="Name2" id="Name" value="<?php echo $data[0]['NameId']; ?>"  />
          
          </td></tr>
      <tr><td>EMail </td><td>
              <input type="text" name="EMail" id="EMail" value="<?php echo $data[0]['EMail']; ?>"  />
              <input type="hidden" name="EMail2" id="EMail" value="<?php echo $data[0]['EMailId']; ?>"  />
          </td></tr>
      <tr><td>Phone </td><td>
              <input type="text" name="Phone" id="Phone" value="<?php echo $data[0]['Phone']; ?>"  />
              <input type="hidden" name="Phone2" id="Phone" value="<?php echo $data[0]['PhoneId']; ?>"  />
          
          </td></tr>
      <tr><td>Address </td><td>
              <textarea  name="Address" id="Address" ><?php echo nl2br(stripslashes($data[0]['Address'])); ?></textarea>
          <input type="hidden" name="Address2" id="Phone" value="<?php echo $data[0]['AddressId']; ?>"  />
          </td></tr>
      <tr><td>Ucode </td><td><input type="text" name="Ucode" id="Ucode" value="<?php echo $data[0]['ucode']; ?>"  />
          </td></tr>
      <tr><td>Signup Date </td><td><input type="text" name="SignupDt" id="SignupDt" value="<?php echo $SignupDt; ?>"   onfocus="showCalendarControl(this);" /></td></tr>

      <tr><td align="right"><input type="hidden" name="EventSignUpId" value="<?php echo $esid; ?>" /><input type="submit" name="upEsignup" value="Update" /></tr>
      <?php
	 // }
	  ?>
    </table>
</form> 
<?php if($commonFunctions->isSuperAdmin()){ ?>
<form action="" method="post" name="delete_form"  style="display:<?php echo $display; ?>">
    <input type="submit" name="delete_signup" onclick="return confirm('Are you sure, you want to delete!!')" class="delete_button"value="delete" />
    <input type="hidden" name="delete_signup_id" value="<?php echo $esid; ?>"/>
</form>
<?php }?>
<table width="60%" border="0" cellpadding="3" cellspacing="3" style="display:<?php echo $display; ?>">
      <tr>
        <td align="center" colspan="3" valign="middle" class="headtitle"><strong>Attendee Details</strong> </td>
      </tr>
      <tr bgcolor='#94D2F3'>
          <th class='tblinner' valign='middle' width='15%' align='center'>Sno</th>
          <th class='tblinner' valign='middle' width='30%' align='center'>Name</th>
          <th class='tblinner' valign='middle' width='30%' align='center'>Email</th>
          <th class='tblinner' valign='middle' width='25%' align='center'>Edit</th>
      </tr>
      <?php 
	 // if($commonFunctions->isSuperAdmin())
	//  {
		  $sno=1;
		 foreach($dataAtt as $aid=>$aval)
		 {
			 ?>
             <tr><td><?php echo $sno; ?></td><td><?php echo $aval['Name']; ?></td><td><?php echo $aval['Email']; ?></td><td align="center"><a href="editAttendee.php?aid=<?php echo $aval['Id']; ?>&esid=<?php echo $esid; ?>&eventid=<?php echo $data[0]['EventId']; ?>">Edit</a></td></tr>
             <?php
			 $sno++;
		 }
	  ?>
      
      <?php
	 // }
	  ?>
    </table>

<div align="center" style="width:100%">&nbsp;</div>
</div>
<!-------------------------------CITY LIST PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
<script language="javascript">
  	document.getElementById('ans9').style.display='block';
</script>
</body>
</html>