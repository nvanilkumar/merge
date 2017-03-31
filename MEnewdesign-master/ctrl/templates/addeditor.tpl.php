<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>
<script>
function getXMLHTTP()
{ 
	//fuction to return the xml http object
	var xmlhttp=false;	
	try
	{
		xmlhttp=new XMLHttpRequest();
	}
	catch(e)
	{		
		try
		{			
			xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(e)
		{
			
			try
			{
				req = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch(e1)
			{
				xmlhttp=false;
			}
		}
	}		
	return xmlhttp;
}

function getCity(strURL) 
{
	var req = getXMLHTTP();
	//alert(strURL);
	if (req) 
	{
		req.onreadystatechange = function() 
		{
			if (req.readyState == 4) 
			{
				// only if "OK"
				if (req.status == 200) 
				{						
					document.getElementById('getCities').innerHTML=req.responseText;						
				} 
				else 
				{
					alert("Error connecting to network:\n" + req.statusText);
				}
			}				
		}			
		req.open("GET", strURL, true);
		req.send(null);
	}	
}		
function email(strURL) 
{		
	
	var req = getXMLHTTP();
	//alert(strURL);
	if (req) {
		req.onreadystatechange = function() 
		{
			if (req.readyState == 4) 
			{
				// only if "OK"
				if (req.status == 200) 
				{						
					document.getElementById('EmailMessage').innerHTML=req.responseText;						
				} 
				else 
				{
					alert("Error connecting to network:\n" + req.statusText);
				}
			}				
		}			
		req.open("GET", strURL, true);
		req.send(null);
	}		
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
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script type="text/javascript" language="javascript">
	function validate_category(){
		if(document.add_category.profile_fname.value == ''){
			alert("Please Enter Name");
			document.add_category.profile_fname.focus();
			return false;
		}
		if(document.add_category.mail.value == ''){
			alert("Please Enter Email-Id");
			document.add_category.mail.focus();
			return false;
		}
		
		if(document.add_category.profile_contact.value == ''){
			alert("Please Enter Mobile no");
			document.add_category.profile_contact.focus();
			return false;
		}
		
		return true;
	}
	function deled(eid){
	var r=confirm("Are you sure you want to delete!");
if (r==true)
  {
 window.location="addeditor.php?edel=y&edid="+eid;
  }
else
  {
  
  }
	
	}
</script>


<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>
    <form action="" method="post" name="add_category" onsubmit="return validate_category();">
<table width="50%" border="0" align="left" cellpadding="0" cellspacing="5" class="grayrndbox">
                      <tr></tr>
                      <tr>
                        <td width="29%" height="25" align="left" class="fonttahoma">Name:*</td>
                        <td width="71%" height="25" align="left"><input tabindex="1" type="text" maxlength="25"  name="profile_fname" id="profile_fname" value="<?php echo $_REQUEST['profile_fname'];?>" class="input_textbox" /></td>
                      </tr>
                     
                      <tr>
                        <td height="25" align="left" class="fonttahoma">Email Address:*</td>
                        <td height="25" align="left"><input type="text" tabindex="2" maxlength="64" name="mail" id="mail" value="<?php echo $_REQUEST['mail']; ?>" class="input_textbox" onblur="email('../includes/email.php?q='+this.value)"/>
                            <div id="EmailMessage"></div></td>
                      </tr>
                     <tr>
                     <td height="25" align="left" class="fonttahoma">State: </td>
                        <td align="left"> <select tabindex="13" name="profile_pstate" style="width:150px;"  id="profile_pstate" onChange="getCity('<?=_HTTP_SITE_ROOT?>/ajaxCities.php?profile_pstate='+this.value)">
							<option value="---Select---" >--Select State--</option>
                            <?
							$selState = "SELECT Id, State FROM States WHERE CountryId='14'";
								$States = $Global->SelectQuery($selState);
			
								for($i = 0; $i < count($States); $i++)
								{
							?>
								<option value="<?=$States[$i]['Id']?>" <?php if($_REQUEST['profile_pstate']==$States[$i]['Id']) { ?> selected="selected" <?php } ?>><?=$States[$i]['State']?></option>
							<?php
								}
							?>
                                </select></td>
                      </tr>
                   
                      <tr>
                        <td height="25" align="left" class="fonttahoma">City:</td>
                        <td align="left">  <div id="getCities" style="padding-left:3px;" >
                                  <select tabindex="14" name="profile_pcity" style="width:150px;"  id="profile_pcity">
							<option value="---Select---">--Select City--</option>
                                      <option value="194">Other</option>
                                  </select></div>                        </td>
                     </tr>

                     
                      <tr>
                        <td height="25" align="left" class="fonttahoma">Mobile No. *</td>
                        <td align="left"><input tabindex="5" type="text" maxlength="20" name="profile_contact" id="profile_contact" value="<?php echo $_REQUEST['profile_contact']; ?>" class="input_textbox" /></td>
                      </tr>
                      <script>
								var profile_contact = new LiveValidation('profile_contact');
								profile_contact.add(Validate.Presence);
								profile_contact.add(Validate.Length, { minimum: 10, maximum: 20 } );
								profile_contact.add(Validate.ContactNo);
							</script>
                      
                      <tr>
                        <td height="25" colspan="2" align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="80">
                            <input type="submit" style="cursor:pointer;" name="Submit" value="Create" class="button small blue round5"/></td>
                            <td width="330" align="right">&nbsp;</td>
                          </tr>
                        </table></td>
                      </tr>
                    </table>
</form>
    </td>
   
  </tr>
  <tr><td>
  <? if(count($dtlseledt)>0) {?>
  <table width="100%" border="1" cellspacing="2" cellpadding="2">
  <tr>
    <td colspan="7" align="center" bgcolor="#CCCCCC"><strong>Editor List</strong></td>
    
  </tr>
  <tr>
    <td align="center">Name</td>
    <td align="center">Email</td>
     <td align="center">State</td>
    <td align="center">City</td>
    <td align="center">Mobileno</td>
    <td align="center">Action</td>
    <td align="center">Login</td>
  </tr>
  <?
  for($i=0;$i<count($dtlseledt);$i++)
  {
  ?>
  <tr>
    <td align="center"><?=$dtlseledt[$i][DisplayName];?></td>
    <td align="center"><?=$dtlseledt[$i][Email];?></td>
    <td align="center"><?=$Global->GetSingleFieldValue("select State from States where Id='".$dtlseledt[$i][StateId]."'");?></td>
    <td align="center"><?=$Global->GetSingleFieldValue("select City from Cities where Id='".$dtlseledt[$i][CityId]."'");?></td>
    <td align="center"><?=$dtlseledt[$i][Mobile];?></td>
    <td align="center"><input type="button" name="delete" value="delete"  onclick="deled(<?=$dtlseledt[$i][Id];?>);" /></td>
     <?
		$window_url = _HTTP_SITE_ROOT."/dashboard.php?UserType=Editor&uid=".$dtlseledt[$i]['Id'];
		?>

    <td align="center"><a href="#" onclick="window.open('<?=$window_url?>','mywindow','menubar=1,width=900,height=600,resizable=yes,scrollbars=yes');">click</a></td>
  </tr>
  <? }?>
</table>
<? }?>
  </td></tr>
</table>


<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	
	
	</div>
	</td>
  </tr>
</table>
	</div>	
</body>
</html>