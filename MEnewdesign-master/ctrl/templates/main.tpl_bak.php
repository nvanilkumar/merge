<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title> ADMIN PANEL :: MeraEvents</title>
<meta name="robots" content="noindex,nofollow" />
		<meta name="description" content="">
		<meta name="keywords" content="">
		<META NAME="ROBOTS" CONTENT="index,follow">
		<meta name="Copyright" CONTENT="Copyright Retail Network Services Group LLC 2008. All rights reserved">
		<meta name="Publisher" CONTENT="">
		<meta name="Distribution" CONTENT="Global">
		<meta name="Language" content="English">
		<meta name="Rating" CONTENT="General">
		<link href="<?php echo _HTTP_SITE_ROOT.'/css/ddcolortabs.min.css.gz'?>" rel="stylesheet" type="text/css">
		<link href="<?php echo _HTTP_SITE_ROOT.'/css/font_style.css'?>" rel="stylesheet" type="text/css">
		<link href="<?php echo _HTTP_SITE_ROOT.'/css/pagi_sort.min.css.gz'?>" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="<?= _HTTP_SITE_ROOT ?>/includes/javascripts/common.min.js.gz"></script>
		<script type="text/javascript" src="<?= _HTTP_SITE_ROOT ?>/includes/javascripts/validation.min.js.gz"></script>

		<script type="text/javascript" src="<?= _HTTP_SITE_ROOT ?>/includes/javascripts/bookmark.min.js.gz"></script>



		<script type="text/javascript" src="<?= _HTTP_SITE_ROOT ?>/includes/javascripts/help.min.js.gz"></script>



		<script type="text/javascript" src="<?= _HTTP_SITE_ROOT ?>/includes/javascripts/jscript.min.js.gz"></script>



		<script type="text/javascript" src="<?= _HTTP_SITE_ROOT ?>/includes/javascripts/viewport.min.js.gz"></script>



		<script type="text/javascript" src="<?= _HTTP_SITE_ROOT ?>/includes/javascripts/webforms.min.js.gz"></script>



		<script type="text/javascript" src="<?= _HTTP_SITE_ROOT ?>/includes/javascripts/webforms2.min.js.gz"></script>



		<script type="text/javascript" src="<?= _HTTP_SITE_ROOT ?>/includes/javascripts/nifty.min.js.gz"></script>



		<script type="text/javascript" src="<?= _HTTP_SITE_ROOT ?>/includes/javascripts/final.min.js.gz"></script>



		<script type="text/javascript" src="<?= _HTTP_SITE_ROOT ?>/includes/javascripts/prototype.min.js.gz"></script>



		<script type="text/javascript" src="<?= _HTTP_SITE_ROOT ?>/includes/javascripts/dropdowntabs.min.js.gz"></script>

		<script type="text/javascript" src="<?= _HTTP_SITE_ROOT ?>/includes/javascripts/sortpagi.min.js.gz"></script>
		<script type="text/javascript" src="<?= _HTTP_SITE_ROOT ?>/includes/javascripts/sortable.min.js.gz"></script>



<style type="text/css">



<!--



#Layer1 {



	position:absolute;



	left:486px;



	top:25px;



	width:477px;



	height:11px;



	z-index:1;



}



-->



</style>



</head>



<center>



	<body>
<div align="center">
	<table width="80%" cellpadding="0" cellspacing="0" style="border-bottom:1px #CCCCCC solid; border-left:1px #CCCCCC solid; border-right:1px #CCCCCC solid; border-top:1px #CCCCCC solid;">
		<tr>
			<td width="20%" style="padding-left:25px;" bgcolor="#CCCCCC" valign="top">
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td align="left"><a href="admin.php" class="lnk">Master Management</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td align="left"><a href="user.php" class="lnk">User Management</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td align="left"><a href="news.php" class="lnk">News Management</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td align="left"><a href="newsletter.php" class="lnk">Newsletter Management</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td align="left"><a href="exportuser.php" class="lnk"> Export User List</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td align="left"><a href="adbanner.php" class="lnk">Ad Banner Management</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td align="left"><a href="paymentapproval.php" class="lnk">Payment Approval</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td align="left"><a href="eventapproval.php" class="lnk">Event Approval</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td align="left"><a href="eventcancellation.php" class="lnk">Event Cancellation</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td align="left"><a href="managepromo.php" class="lnk">Manage Promo Code</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td align="left"><a href="transaction.php" class="lnk">Transaction Report</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td align="left"><a href="pendingstatus.php" class="lnk">Pending Status Report</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td align="left"><a href="misreport.php" class="lnk">MIS Reports</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td align="left"><a href="changepassword.php" class="lnk">Change Password</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td align="left">
						<a href="http://www.meraevents.com/lists/admin/?login=<?=$_SESSION['login']?>&password=<?=$_SESSION['password']?>"  class="lnk" target="_blank">Newsletter</a>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
				</table>
			</td>
			<td valign="top">
				<?php
					if(is_file(_CURRENT_TEMPLATE_DIR.$current_page_content)) 
					{
						include_once(_CURRENT_TEMPLATE_DIR.$current_page_content);
					}
					else
					{
						echo "Sorry, template file does not exist!";
					} 
				?>
			</td>
		</tr>
	</table>
</div>
 

			<!--<div id="main" style="width:865px; height:600px; " align="center">



			<br />



			<div id="form" style="background-color:#FFFFFF; width:865px; height:95%;" align="center">



			<div id="header" style="background-color:#FFFFFF; width:865px;" align="center">



			<table cellpadding="0" width="100%" cellspacing="0" border="0" >
				<tr>
				<td align="right" height="30" background="<?=_HTTP_SITE_ROOT?>/images/BD.jpg" valign="middle" >
				
				<table width="97%" border="0" align="center" cellpadding="0" cellspacing="0">

					  <tr>
		
						
				<?php

				 if(isset($_SESSION['user_id']))

						{

					?>



					
						
		
						<td valign="middle" align="right">
						<div id="colortab" class="ddcolortabs" align="right">
							<ul id="nav" style="width:500px;">
							<li ><a href="<?=_HTTP_SITE_ROOT?>/manageuser.php"><span>Manage Users | </span></a></li>
							<li ><a href="<?=_HTTP_SITE_ROOT?>/administration/dealer_content.php"><span>Dealer Content | </span></a></li>
							<li ><a href="<?=_HTTP_SITE_ROOT?>/administration/admin.php"><span>Dealer Email | </span></a></li>
							<li><a href="<?=_HTTP_SITE_ROOT?>/administration/special_offers.php"><span>Special Offers | </span></a></li>
							<li ><a href="logout.php"><span>Log Out</span></a></li>
							</ul>
						</div>
						</td>
												
						

							<?php



								}

					else

					

						{

					

					  ?>		

					

							<td valign="middle" align="right">				

						<div id="colortab" class="ddcolortabs">

					

							<ul id="nav" style="width:150px">

					

					

							<li ><a href="<?=_HTTP_SITE_ROOT?>/index_in.php"><span>Go Back Home</span></a></li>

							</ul>

						</div>
						</td>
					

					<?php

					

						}

						

				?>

						

						<script type="text/javascript">



						//SYNTAX: tabdropdown.init("menu_id", [integer OR "auto"])



						tabdropdown.init("colortab", "auto")



						</script>



					</tr>
						</table>

					</td>

				</tr>



		</table>



  </div>



		<div id="middle" align="center" style="background-color:#FFFFFF; width:865px;">



			<?php



				if(is_file(_CURRENT_TEMPLATE_DIR.$current_page_content)) 



				{



					include_once(_CURRENT_TEMPLATE_DIR.$current_page_content);



				}



				else



				{



					echo "Sorry, template file does not exist!";



				}



			?>



		</div>



				</div>



				<br />



			</div>-->



		</body>



		</center>



</html>