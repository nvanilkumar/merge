<?php
 session_start();
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>meraevents.com - Register for the Event</title>
<link rel="stylesheet" href="<?=_HTTP_CF_ROOT;?>/css/website.css" />
<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css" />
</head>
<script language="javascript" type="text/javascript" src="scripts/livevalidation.min.js.gz"></script>
<script language="javascript" type="text/javascript">
 function validat_reg_form()
 {
  
  var charged = document.reg_form.charged.value;
  
  if(charged == "YES"){
	  var codes = document.reg_form.codes.value;
	  var user_code = document.reg_form.promo_code.value;
	  var res = codes.match(user_code);
	  
	  if(document.reg_form.promo_code.value != ''){
		  if(user_code.length == 1){
				if(res == null){
					alert("Promotion Code Entered Is Either Invalid Or Has Already Been Used.");
					document.reg_form.promo_code.focus();
					return false;
				}
						
		  }else{
			alert("Invalid Length Of Promotion Code.");
			document.reg_form.promo_code.focus();
			return false;
		  }/// END IF LENGHT
	  }
  }// IF CHARGED
 
 
  if(document.reg_form.txtname.value== '')
  {
   alert("Please enter name/company");
   document.reg_form.txtname.focus();
   return false;
  }
 	
  if(document.reg_form.email.value == '')
  {
   alert("Please enter email");
   document.reg_form.email.focus();
   return false;
  }
  var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
  var address = document.reg_form.email.value;
  if(reg.test(address) == false)
  {
	alert('Invalid email address');
	document.reg_form.email.focus();
	return false;
  }
  
  if(document.reg_form.contactno.value == '')
  {
   alert("Please enter contact no.");
   document.reg_form.contactno.focus();
   return false;
  }
  var checkOK = "0123456789+- ";
  var checkStr = document.reg_form.contactno.value;
  
  var allValid = true;
  var allNum = "";
  for (i = 0;  i < checkStr.length;  i++)
  {
   ch = checkStr.charAt(i);
   for (j = 0;  j < checkOK.length;  j++)
   if (ch == checkOK.charAt(j))
   break;
   if (j == checkOK.length)
   {
    allValid = false;
    break;
   }
   if (ch != ",")
     allNum += ch;
  }
  if (!allValid)
  {
   alert("Contact number should have only digits");
   document.reg_form.contactno.focus();
   return false;
  }
  
    
  if(document.reg_form.address.value == '')
  {
   alert("Please enter address");
   document.reg_form.address.focus();
   return false;
  }
  if(document.reg_form.pincode.value == '')
  {
   alert("Please enter pincode");
   document.reg_form.pincode.focus();
   return false;
  }
  if(!IsAlphaNemeric(document.reg_form.pincode.value))
  {
  	alert("Please enter only alphabhanumeric value in pincode field");
   	document.reg_form.pincode.focus();
  	 return false;
  }
/*  if(document.reg_form.city.value == '')
  {
   alert("Please enter city");
   document.reg_form.city.focus();
   return false;
  }
   if(!IsAlpha(document.reg_form.city.value))
  {
  	alert("Please enter only alphabets in city field");
   	document.reg_form.city.focus();
  	 return false;
  }
  if(document.reg_form.state.value == '')
  {
   alert("Please enter state");
   document.reg_form.state.focus();
   return false;
  }
  if(!IsAlpha(document.reg_form.state.value))
  {
  	alert("Please enter only alphabets in state field");
   	document.reg_form.state.focus();
  	 return false;
  }*/
  if(document.reg_form.country.value == 0)
  {
   alert("Please select country");
   document.reg_form.country.focus();
   return false;
  }
  if(document.reg_form.state.value == '')
  {
   alert("Please enter state");
   document.reg_form.state.focus();
   return false;
  }
   if(document.reg_form.city.value == '')
  {
   alert("Please enter city");
   document.reg_form.city.focus();
   return false;
  }
  
  if(document.reg_form.quantity.value == ''){
   alert("Please enter quantity.");
   document.reg_form.quantity.focus();
   return false;
  }else
  if(!IsNumeric(document.reg_form.quantity.value)){
   alert("Quantity can only be numeric..");
   document.reg_form.quantity.focus();
   return false;
  }
  return true;
 }/// END OF VALIDATION
 
 function IsNumeric(sText)
{
   var ValidChars = "0123456789.";
   var IsNumber=true;
   var Char;

 
   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
   return IsNumber;
   
   }

function IsAlpha(sText)
{
   var ValidChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ";
   var IsNumber=true;
   var Char;
 
   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
   return IsNumber;
   
   }
 function IsAlphaNemeric(sText)
{
   var ValidChars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ";
   var IsNumber=true;
   var Char;
 
   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
   return IsNumber;
   
   }
//// FILL ALL VALUES WHEN same as registered IS CLICKED. 
document.getElementById('issame').onclick = WindowClickEventExplorer;
function WindowClickEventExplorer()
{
		if(document.getElementById('issame').checked)
		{
			document.getElementById('reg_form').txtname.value = '<?php echo $Delegate->FirstName." "." ".$Delegate->LastName; ?>';
			document.getElementById('reg_form').email.value = '<?php echo $Delegate->Email;?>';
			document.getElementById('reg_form').contactno.value = '<?php echo $Delegate->Mobile;?>';
			document.getElementById('reg_form').address.value = '<?php echo $Delegate->Address;?>';
			document.getElementById('reg_form').pincode.value = '<?php echo $Delegate->PIN;?>';
			//document.getElementById('reg_form').city.value = '';
			//document.getElementById('reg_form').state.value = '';
			//document.getElementById('reg_form').country.value = 'India';
		}
		else
		{
			document.getElementById('reg_form').txtname.value = '';
			document.getElementById('reg_form').email.value = '';
			document.getElementById('reg_form').contactno.value = '';
			document.getElementById('reg_form').address.value = '';
			document.getElementById('reg_form').pincode.value = '';
			//document.getElementById('reg_form').city.value = '';
			//document.getElementById('reg_form').state.value = '';
			//document.getElementById('reg_form').country.value = '';
		}
}
</script>
<script>
function getXMLHTTP()
	 { //fuction to return the xml http object
		var xmlhttp=false;	
		try
			{
			xmlhttp=new XMLHttpRequest();
			}
		catch(e)
			{		
				try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
					}
			catch(e){
				
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
	
	
function getStates(strURL) {		
		
	//alert(strURL);
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() 
			{
				if (req.readyState == 4) 
					{
					// only if "OK"
							if (req.status == 200) 
								{						
								document.getElementById('statediv').innerHTML=req.responseText;						
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
	
					
function getCity(strURL) {		
		
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('citydiv').innerHTML=req.responseText;						
					} else {
						alert("Error connecting to network:\n" + req.statusText);
					}
				}				
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
				
	}
	
	
function getStates1(strURL) {		
		
	//alert(strURL);
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = 	function() 
				{
				if (req.readyState == 4) 
					{
					// only if "OK"
						if (req.status == 200) 
								{						
						document.getElementById('statedivU').innerHTML=req.responseText;						
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
	
	function callOnLoad()
	{
	Initialize();
	WindowClickEventExplorer();
	}
</script>
<body class="bg" onLoad="callOnLoad()">
<div>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="10%">&nbsp;</td>
      <td width="80%" align="center" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="20">&nbsp;</td>
          </tr>
          <tr>
            <td><?php  include 'includes/header.php';?>
            </td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="mainback">
                <tr>
                  <td width="21%" align="center" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td><?php include 'includes/left_panel.php'; ?></td>
                      </tr>
                    </table></td>
                  <td width="79%"><table width="100%" border="0">
                      <tr>
                        <td align="center" valign="top">
						<?php
					  $qDName= "Select FirstName from user where Id = '".$_SESSION['uid']."'";	
	   $_SESSION['UserType'];
		if($_SESSION['UserType']=='Delegate')
		{
				?>		
						<div class="center-wrap">
                            <div class="centerbox">
                              <div class="textpadder">
                                <div id="center">
                                  <div id="content">
                                    <div id="main">
                                      <?php
										if (empty($_POST)) 
										{ 
										?>
                                      <h1><font color="#000033">Register for the Event</font></h1>
                                      <div class="tabs"></div>
                                      <div class="node"> <span class="submitted"></span> <span class="taxonomy"></span>
                                        <div class="content"></div>
                                        <div class="clr"> </div>
                                      </div>
                                      <div id="EventSignupDiv" style="display:''">
                                        <div class="clr" id="section2">
                                          <table class="sections" cellpadding="0" cellspacing="0">
                                            <tbody>
                                              <tr valign="top">
                                                <td class="section width99"><div class="block block-block collapsiblock-processed" id="block-block-18">
                                                    <div class="titlewrap">
                                                      <h2 class="title" style="color: rgb(255, 255, 255);"></h2>
                                                    </div>
                                                    <div class="content">
                                                      <form name="reg_form" id="reg_form" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" onsubmit="return validat_reg_form();">
                                                        <table style="border: medium hidden ;" width="80%" align="center" cellpadding="3" cellspacing="6">
                                                          <tbody>
                                                            <tr>
                                                              <td valign="middle" width="46%" align="left"><strong>Event Title :</strong></td>
                                                              <td valign="middle" width="54%" align="left"><strong><?php echo $Events->Title ?></strong></td>
                                                            </tr>
                                                            <tr>
                                                              <td valign="middle" align="left"><strong>Venue : </strong></td>
                                                              <td valign="middle" align="left"><?php echo $Events->Venue ?></td>
                                                            </tr>
                                                            <tr>
                                                              <td valign="middle" align="left"><strong>Date :</strong></td>
                                                              <td valign="middle" align="left"><?php echo $Events->StartDt ?></td>
                                                            </tr>
                                                            <tr>
                                                              <td colspan="2" valign="middle" align="left"><strong> Event Summery/Highlights :</strong></td>
                                                            </tr>
                                                            <tr>
                                                              <td colspan="2" valign="middle" align="left"><?php echo $Events->HighLights ?></td>
                                                            </tr>
                                                            <tr>
                                                              <td colspan="2" align="left">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                              <td colspan="2" align="left"><strong>Billing Details</strong></td>
                                                            </tr>
                                                            <tr>
                                                              <td colspan="2" valign="middle" align="left"><label>
                                                                <input name="issame" id="issame" value="checkbox" onclick="return WindowClickEventExplorer();"  checked="checked" type="checkbox">
                                                                </label>
                                                                &nbsp;Same As Registered Details. </td>
                                                            </tr>
                                                            <tr>
                                                              <td valign="middle" align="left">Name:</td>
                                                              <td valign="middle" align="left"><input name="txtname" id="txtname" maxlength="50" type="text"></td>
                                                            </tr>
								<script>
									var txtname = new LiveValidation('txtname');
									txtname.add( Validate.Presence );
									txtname.add( Validate.Length, { minimum: 3, maximum: 100 } );
									txtname.add( Validate.Format, { pattern: /^[A-Za-z0-9 ]{3,100}$/ } );
								</script>
                                                            <tr>
                                                              <td valign="middle" align="left">Email Id:</td>
                                                              <td valign="middle" align="left"><input name="email" id="email" maxlength="50" type="text"></td>
                                                            </tr>
							  <script>
									var email = new LiveValidation('email');
									email.add( Validate.Presence );
									email.add( Validate.Email );
								</script>
                                                            <tr>
                                                              <td valign="middle" align="left">Contact No:</td>
                                                              <td valign="middle" align="left"><input name="contactno" id="contactno" maxlength="20" type="text"></td>
                                                            </tr>
										  <script>
											var contactno = new LiveValidation('contactno');
											contactno.add( Validate.Presence );
											//profile_contact.add( Validate.Numericality );
											contactno.add( Validate.Length, { minimum: 3, maximum: 20 } );
											</script>
                                                            <tr>
                                                              <td valign="middle" align="left">Address:</td>
                                                              <td valign="middle" align="left"><textarea cols="40" rows="3" id="address" name="address"></textarea></td>
                                                            </tr>
								<script>
									var address = new LiveValidation('address');
									address.add( Validate.Presence );
								</script>
                                                            <tr>
                                                              <td valign="middle" align="left">Country:</td>
                                                              <td valign="middle" align="left"><!--<input type="text" name="country" maxlength="50">-->
                                                                <select name="profile_country" style="width:150px;"  class="form-select required" id="profile_country" onchange="getStates('includes/cdd.php?country='+this.value)">
                                                                  <?php
																$TotalCountriesRES=count($CountriesRES);
																for($i=0; $i<$TotalCountriesRES; $i++)
																{
																?>
                                                                  <option value="<?php echo $CountriesRES[$i]['Id']; ?>"<? if($CountriesRES[$i]['Id']==$Countries->Country){?> selected="selected" <? }?>> <?php echo $CountriesRES[$i]['Country']; ?> </option>
                                                                  <?php
															  }
															  ?>
                                                                </select>
                                                              </td>
                                                            </tr>
                                                            <tr>
                                                              <td valign="middle" align="left">State:</td>
                                                              <td style="width:68%;" id="statediv"><select name="profile_pstate" style="width:150px;"  class="form-select required" id="profile_pstate" >
                                                                  <option value="<?php echo $Delegate->StateId; ?>">
                                                                  <?php 
								  	$States = new cStates($Delegate->StateId);
									$Success = $States->Load();
									
									if ($Success)
									{
										echo $States->State;
									}?>
                                                                  </option>
                                                                </select>
                                                              </td>
                                                            </tr>
                                                            <tr>
                                                              <td valign="middle" align="left">City:</td>
                                                              <td style="width:68%;" id="citydiv"><select name="profile_pcity" style="width:150px;"  class="form-select required" id="profile_pcity" >
                                                                  <option value="<?php echo $Delegate->CityId; ?>">
                                                                  <?php 
																	$Cities = new cCities($Delegate->CityId);
																	$Success = $Cities->Load();
																	
																	if ($Success)
																	{
																		echo $Cities->City;
																	}
																	?>
                                                                  </option>
                                                                </select></td>
                                                            </tr>
                                                            <tr>
                                                              <td valign="middle" align="left">Pin Code:</td>
                                                              <td valign="middle" align="left"><input name="pincode" maxlength="10" type="text"></td>
                                                            </tr>
                                                            <tr>
                                                              <td colspan="2">
															  <?php 
															  if($_GET['EventId']==$Tie2009EventId) //if Tie iSB Event show Extra.
															  {
															  ?>
															  <br>
															  	

<ul>Please read carefully:
  	

    <li> The TiE-ISB Connect 2009 will be held on 22nd and 23rd of October at Hotel Marriott. Registration fee for the two day conference including JumpStart is Rs.<?php echo $TieRegnFreeBoth; ?>. For TiE members the fee would be Rs.<?php echo $TieMemberFeeBoth; ?>.
    <li> Registration fees for the TiE-ISB conference conference, excluding Jumpstart, will be Rs.<?php echo $TieRegnFreeOnlyTie; ?>. For TiE members the fee would be Rs.<?php echo $TieMemberFeeOnlyTie; ?>
    <li> JumpStart your Venture workshop will be held on the 22nd October from 9.00 AM - 12.30 PM. The TiE-ISB Connect conference will be from 2.00 PM, 22nd October - 6.00 PM 23rd October, 2009.
    <li> For issues related to online registration please contact tieisbconnect@phonelinx.com.
	<br>

															  <input type="radio" name="EventChoice" id="EventChoice" value="bothEvent"  checked="checked">I want to attend <a href="http://www.tie-isbconnect.com/jumpstart.html">JumpStart </a> and <a href="http://www.tie-isbconnect.com/register-tieisb.php">TiE-ISB Connect 2009</a></input><br>
															 <input type="radio" name="EventChoice" id="EventChoice" value="onlyTieisb">I just want to attend TiE-ISB Connect 2009 one-day conference</input><br>  
															  <input type="checkbox" name="isTieMember" id="isTieMember" value="1">I am a Tie Member.</input><br>
															  <?php
															  }
															  ?>
															  </td>
                                                            </tr>
													<?php 
													 $free = mysql_fetch_array(mysql_query("Select Id from events where Free = 1 and Id = ".$_GET['EventId']." "));
													 $EventIsFree = mysql_num_rows($free);
													
													if($EventIsFree == 0)
													{
													?>
													                                                            <tr>
                                                              <td>Enter Promotion Code For Redemption :</td>
                                                              <td><label>
                                                                <input name="promo_code" size="16" maxlength="13" type="text">
                                                                <input name="codes" value="?A0805F07AF7C9,B569FEE2A0C13,31178C412D6EE,2EFAB33BB118E,5E2348AB8ABDB,FBCE84B0A5CD0,C145E18DEAEB9,56AC8E48D6A31,55014F5DF64F8,ED48DBBF1431A,2E61146C29F01,6F38DE18986EA" type="hidden">
                                                                </label></td>
                                                            </tr>
													<?php } ?>
                                                            <tr>
                                                              <td colspan="2">&nbsp;</td>
                                                            </tr>
                                                   
                                                            <tr>
                                                              <td colspan="2"><strong>Purchase Details </strong></td>
                                                            </tr>
                                                            <tr>
                                                              <td colspan="2"><table width="100%" cellpadding="3" cellspacing="6">
                                                                  <tbody>
                                                                    <tr>
                                                                      <td><strong>Event Name </strong></td>
                                                                      <td>&nbsp;</td>
                                                                      <td><strong>No. Of Attendees</strong></td>
                                                                      <td>&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td width="60%"><?php echo $Events->Title ?></td>
                                                                      <td></td>
                                                                      <td><label>
                                                                        <input name="quantity" id="quantity" size="3" maxlength="3" type="text">
                                                                        </label></td>
                                                                      <td><label></label></td>
                                                                    </tr>
                                                                  </tbody>
                                                                </table></td>
                                                            </tr>
                                                            <tr>
                                                              <td colspan="2">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                              <td colspan="2" valign="middle" align="center"><input name="frmsubmit" value="Register" type="submit">
                                                               
                                                              </td>
                                                            </tr>
                                                          </tbody>
                                                        </table>
                                                      </form>
                                                    </div>
                                                  </div></td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>
                                      </div>
                                      <?php
									  }
									  else
									  {
									  ?>
                                      <!--<div align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:11px; vertical-align:top" > Thanks, you are successfully registerd 
                                    </div>-->
											 <?php  echo "<BIG>".$EmailSentMsg."</BIG>"; ?>
                                <h1><font color="#000033">Attendee Information</font></h1>
										  <table width="100%" height="823" border="0">
                                        <tr>
                                          <td height="817" valign="top">
								
										        
                                   <?php  if (($Events->Free==0)||($Events->Free==1))
										{
										 if(isset($_POST['Attendee'])=='')
										 {
										?><form method="post" action="<?php $_SERVER['PHP_SELF'] ;?>" name="attendeeForm">
										<?php if($_REQUEST['EventId']==$Tie2009EventId)
											{
											?>
											<input type="hidden" name="tieRate" id="tieRate" value="<?php echo $TieRate; ?>">
											<input type="hidden" name="eVID" id="eVID" value="<?php echo $_REQUEST['EventId']; ?>">
											<?php
											}
										?>
                                                                                           <table>
                                                <tbody>
                                                  <tr>
                                                    <td colspan="4"><strong>Attendee 1 </strong></td>
                                                  </tr>
                                                  <tr>
                                                    <td>Full Name : </td>
                                                    <td><label>
                                                      <input name="fullname_1" id="fullname_1" type="text" value="<?php echo $Delegate->FirstName." ".$Delegate->MiddleName." ".$Delegate->LastName; ?>">
                                                      </label></td>
													    <script>
														var fullname_1 = new LiveValidation('fullname_1');
														fullname_1.add( Validate.Presence );
														fullname_1.add( Validate.Length, { minimum: 1, maximum: 25 } );
														//profile_fname.add( Validate.Format, { pattern: /^[A-Za-z]{3,20}$/ }  );
													    </script>
                                                    <td>Company Name : </td>
                                                    <td><label>
                                                      <input name="comp_name_1" id="comp_name_1" type="text" value="<?php echo $Delegate->Company;?>">
                                                      </label></td>
                                                  </tr>
                                                  <tr>
                                                    <td>Email : </td>
                                                    <td><label>
                                                      <input name="email_1" id="email_1" type="text" value="<?php echo $Delegate->Email;?>">
                                                      </label></td>
													  
													  <script>
														var email_1 = new LiveValidation('email_1');
														email_1.add( Validate.Presence );
														email_1.add( Validate.Email );
													  </script>
                                                    <td>Phone No : </td>
                                                    <td><label>
                                                      <input name="phone_1" id="phone_1" type="text" value="<?php echo $Delegate->Mobile;?>">
                                                      </label></td>
													  <script>
														var phone_1 = new LiveValidation('phone_1');
														phone_1.add( Validate.Presence );
														//profile_contact.add( Validate.Numericality );
														phone_1.add( Validate.Length, { minimum: 3, maximum: 20 } );
														</script>
                                                  </tr>
                                                </tbody>
                                              </table>
                                              <?php 
										for ($i = 2; $i <= $quantity; $i++)
										{
										?>  <table>
                                                <tbody>
                                                  <tr>
                                                    <td colspan="4"><strong>Attendee <?php echo $i ;?> </strong></td>
                                                  </tr>
                                                  <tr>
                                                    <td>Full Name : </td>
                                                    <td><label>
                                                      <input name="fullname_<?php echo $i;?>" id="fullname_<?php echo $i;?>" type="text" value="">
                                                      </label></td>
													  <script>
														var fullname_2 = new LiveValidation('fullname_<?php echo $i;?>');
														fullname_2.add( Validate.Presence );
														fullname_2.add( Validate.Length, { minimum: 1, maximum: 25 } );
														//profile_fname.add( Validate.Format, { pattern: /^[A-Za-z]{3,20}$/ }  );
													    </script>
                                                    <td>Company Name : </td>
                                                    <td><label>
                                                      <input name="comp_name_<?php echo $i;?>" id="comp_name_2" type="text" value="">
                                                      </label></td>
                                                  </tr>
                                                  <tr>
                                                    <td>Email : </td>
                                                    <td><label>
                                                      <input name="email_<?php echo $i;?>" id="email_<?php echo $i;?>" type="text" value="">
                                                      </label></td>
													   <script>
														var email_2 = new LiveValidation('email_<?php echo $i;?>');
														email_2.add( Validate.Presence );
														email_2.add( Validate.Email );
													  </script>
                                                    <td>Phone No : </td>
                                                    <td><label>
                                                      <input name="phone_<?php echo $i;?>" id="phone_<?php echo $i;?>" type="text" value="">
                                                      </label></td>
													   <script>
														var phone_2 = new LiveValidation('phone_<?php echo $i;?>');
														phone_2.add( Validate.Presence );
														//profile_contact.add( Validate.Numericality );
														phone_2.add( Validate.Length, { minimum: 3, maximum: 20 } );
														</script>
                                                  </tr>
                                                </tbody>
                                              </table>
                                              <?php
											}
											?> <table width="43%" border="0" align="center">
												  <tr >
													<td>
													<input name="Attendee" id="Attendee" value="Submit" type="submit">
													</td>
												  </tr>
												</table>
                                              <?php
											    }
												echo $msg. "<br>";
												
												
											}
											  
											?>
                                            </form></td>
                                        </tr>
                                      </table>
                                      <?php 
									  }
									 
									  ?>
									  
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
						  <?php
						  }
						   if($_SESSION['UserType']=='Organizer')
							{
							
							echo "Please register as Delegate to signup for the event";
							}
						  if ($_SESSION['uid']=='')
						  {
						 
						 					
	?>
	<div align="center"  style=" width:500px;vertical-align:middle;background-color:#717B5D; vertical-align:middle" >

	<form name="fSignIn" method="post" action="SingnIn.php" id="fSignIn" >
      <table width="57%" height="175" border="0" cellpadding="0" cellspacing="0" >
        <tr>
          
          <td width="35%" height="67" ><font color="#FFFFFF">Username:</font>            </td>
         
          
          <td width="65%" ><input type="text" name="UserNmae" id="UserNmae" /></td>
        </tr>
		
		<tr>
		 <td width="35%" height="47" ><font color="#FFFFFF">Password:</font>            </td>
		 <td width="65%" ><input type="password" name="UPassword" id="UPassword"/></td>
		</tr>
		<tr>
		<td colspan="2" align="center" valign="middle" ><br><span class="submit"><input  type="submit" name="sLogin" value="login" ></span></td>
		</tr>
		<?php
			if(!empty($mLoginErrorMsg))
			{
			?>
				 <tr>
				  <td ><div  style="height:7px; width:100%; padding-left:10%; color:#FF0000; "><?php echo $mLoginErrorMsg;?></div></td>
				</tr>
			<?php
			}	
			?>
      </table>
    </form>
	
	
	</div>
	<?php
	
						 
						  }
						 
				?>	
						  
					    </td>
                      </tr>
                      <tr align="center" valign="bottom">
                        <td valign="top"><?php include 'templates/bottom_tpl.php' ?></td>
                      </tr>
                      <tr height="10">
                        <td valign="top">&nbsp;</td>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
          </tr>
        </table>
        <table width="1004" border="0" cellpadding="0" cellspacing="0">
          <tr bgcolor="#525252">
            <td height="10">&nbsp;</td>
          </tr>
          <tr bgcolor="#8B8E85">
            <td height="53">&nbsp;</td>
          </tr>
      </table></td>
      <td width="10%">&nbsp;</td>
    </tr>
  </table>
</div>
</body>
</html>
