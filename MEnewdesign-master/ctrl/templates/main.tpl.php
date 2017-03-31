<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents</title>

		<!--<link rel="shortcut icon" href="<?php echo _IMAGES_TEMPLATE_URL; ?>garland_favicon.ico" type="image/x-icon">-->
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?=_HTTP_SITE_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?= _HTTP_SITE_ROOT ?>/css/sortable.min.js.gz"></script>	
        <script language="javascript" src="<?= _HTTP_SITE_ROOT ?>/css/sortpagi.min.js.gz"></script>	
	</head>	
<body style="background-image: url(<?php echo _IMAGES_TEMPLATE_URL; ?>background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
	<div id="divMaster" style="width:inherit;">
		<table style="width:100%; height:110px; table-layout:fixed" cellpadding="0" cellspacing="0">
			<tr style="background-color: #0C205B;">
				<td style="width:250px; padding-left:15px; padding-top:8px">
						<a href="<?php echo _HTTP_SITE_ROOT; ?>"><img src="<?php echo _IMAGES_TEMPLATE_URL; ?>garland_dropmenu_logo.png" style="display:block" border="0"/></a>
	  			</td>
				<td align="center" valign="middle" style="color:#FFFFFF; padding-left:100px; font-size:24px; font-weight:bold;"><div style="border:#FFBB00 solid 2px; width:500px; background-position:right; background-repeat:repeat-y; background-image:url(<?php echo _IMAGES_TEMPLATE_URL; ?>headgradient.gif);">MeraEvents Administrator</td>
				<td style="width:300px; padding-right:5px; text-align:right">
			<table style="height:85px; width:100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td style="vertical-align:top; text-align:right; padding-top:5px">
						<div id="divOnlineStatus" style="font-size:8pt;color:#ffffff;font-weight:bold;padding:2px 5px 2px 0px;margin-left:auto;background-position:right;background-repeat:repeat-y;background-image:url(<?php echo _IMAGES_TEMPLATE_URL; ?>namegradient.gif);">
								<?php 
									if (isset($_SESSION['login_user_id']) && ($_SESSION['login_user_id']>0) ) {
										echo "WELCOME,	 ".$_SESSION['login_user_name'];
									} else {
										echo "No User Logged In";
									}
								?>	
										</div>
					</td>
				</tr>
				<tr>
					<td style="vertical-align:bottom; text-align:right">
						<table cellpadding="0" cellspacing="0" style="margin-left:auto; margin-bottom:2px; font-size:8pt;">
							<tr>
								<td style="width:50px; text-align:center">
									<a href="<?php echo _HTTP_SITE_ROOT; ?>" class="topmenuitem"><img src="<?php echo _IMAGES_TEMPLATE_URL; ?>home.gif" /><br />
								  home</a>
							  </td>
								<td style="width:50px; text-align:center">
								<?php 
									if ( isset($_SESSION['login_user_id'] ) && ($_SESSION['login_user_id']>0) ) {
								?>
									<a href="<?php echo getHtmlLink('logout.php?logout=y'); ?>" id="aLoginAction" class="topmenuitem"><img src="<?php echo _IMAGES_TEMPLATE_URL; ?>login.gif" /><br />
								  <span id="spnLoginAction">logout</span>
								  </a>	
								 <?php
									} else {
								 ?> 
											<a href="<?php echo getHtmlLink('login.php'); ?>" id="aLoginAction" class="topmenuitem"><img src="<?php echo _IMAGES_TEMPLATE_URL; ?>login.gif" /><br />
								  <span id="spnLoginAction">login</span>
								  </a>								 
								 <?php
									}
								 ?>
							  </td>
							  
							  <!--td style="width:50px; text-align:center">
									<a href="<?php //echo getHtmlLink('myaccount.php'); ?>" class="topmenuitem"><img src="<?php //echo _IMAGES_TEMPLATE_URL; ?>myaccount.gif" /><br />
								  my a/c</a>
							  </td
	
								<td style="width:50px; text-align:center">
									<a href="<?php echo getHtmlLink('contactin.php'); ?>" class="topmenuitem"><img src="<?php echo _IMAGES_TEMPLATE_URL; ?>contact.gif" /><br />
								  contact</a>
							  </td>-->
																
							</tr>
						</table>
				  </td>
				</tr>
			</table>
	  </td>
			</tr>
		</table>
		<div>
	<table style="width:100%; table-layout:fixed" cellpadding="0" cellspacing="0">
		<tr>

			<td style="height:10px; width:150px; background-image:url(<?php echo _IMAGES_TEMPLATE_URL; ?>headergradient_left.gif)"></td>
			<td style="background-image:url(<?php echo _IMAGES_TEMPLATE_URL; ?>headergradient_right.gif)"></td>
		</tr>
	</table>				
</div>
		<table style="width:100%; height:495px;" cellpadding="0" cellspacing="0">
  <tr>
	<td style="width:150px; vertical-align:top; background-image:url(<?php echo _IMAGES_TEMPLATE_URL; ?>menugradient.jpg); background-repeat:repeat-x">
		<?php include(_CURRENT_TEMPLATE_DIR.'left.tpl.php'); ?>
	</td>
	<td style="vertical-align:top">
	<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
	<?php 
		if (is_file(_CURRENT_TEMPLATE_DIR.$current_page_content) )
		{
			include_once(_CURRENT_TEMPLATE_DIR.$current_page_content);
		}
		else
		{
			echo "Sorry, template file does not exist!";
		}
	?>
	</div>
	</td>
  </tr>
</table>
		<!--<div style="margin-top:30px; margin-left:150px; text-align:center;">
	<div id="divFooterLinks">
		<a href="<?=_HTTP_SITE_ROOT; ?>" id="aFooterHome" style="padding-right:8px;border-right:#000000 1px solid;">Home</a>
		<a href="<?php echo getHtmlLink('contactin.php'); ?>" id="aFooterContact" style="padding-left:8px;">Contact Us</a>
	</div>
	<div style="color: #808080; margin-top:5px">Powered by <a href="" onclick="window.open('http://www.horseracegame.com');">Horse Race Game<a/>&nbsp;&nbsp;All rights reserved.</div>			
</div>-->
	</div>	
</body>
</html>