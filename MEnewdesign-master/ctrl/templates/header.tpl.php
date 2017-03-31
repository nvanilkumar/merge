<div id="divMaster" style="width:inherit;">
	<table style="width:100%; height:110px; table-layout:fixed" cellpadding="0" cellspacing="0">
		<tr style="background-color:  #0C205B;">
			<td style="width:250px; padding-left:15px; padding-top:8px">
					<a href="#"><img src="images/garland_dropmenu_logo (1).png" style="display:block" border="0"/></a>
			</td>
			<td align="center" valign="middle" style="color:#FFFFFF; padding-left:100px; font-size:24px; font-weight:bold;"><div style="border:#FFBB00 solid 2px; width:500px; background-position:right; background-repeat:repeat-y; background-image:url(images/headgradient.gif);">MeraEvents Administrator</td>
			<td style="width:300px; padding-right:5px; text-align:right">
		<table style="height:85px; width:100%;" cellpadding="0" cellspacing="0">
			<tr>
				<td style="vertical-align:top; text-align:right; padding-top:5px">
					<div id="divOnlineStatus" style="font-size:8pt;color:#ffffff;font-weight:bold;padding:2px 5px 2px 0px;margin-left:auto;background-position:right;background-repeat:repeat-y;background-image:url(images/namegradient.gif);">
							<?php 
								if (isset($_SESSION['uid']) && ($_SESSION['uid']>0) ) {
									echo "WELCOME,	 ".$_SESSION['UserName'];
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
								<a href="#" class="topmenuitem"><img src="images/home.gif" /><br />
							  home</a>
						  </td>
							<td style="width:50px; text-align:center">
							<?php 
								if ( isset($_SESSION['uid'] ) && ($_SESSION['uid']>0) ) {
							?>
								<a href="logout.php?logout=y" id="aLoginAction" class="topmenuitem"><img src="images/login.gif" /><br />
							  <span id="spnLoginAction">logout</span>
							  </a>	
							 <?php
								} else {
							 ?> 
										<a href="login.php" id="aLoginAction" class="topmenuitem"><img src="images/login.gif" /><br />
							  <span id="spnLoginAction">login</span>
							  </a>								 
							 <?php
								}
							 ?>
						  </td>
						</tr>
					</table>
			  </td>
			</tr>
		</table>
  		</td>
	</tr>
</table>
<div>
<!--<table style="width:100%; table-layout:fixed" cellpadding="0" cellspacing="0">
	<tr>
		<td style="height:10px; width:150px; background-image:url(images/headergradient_left.gif)"></td>
		<td style="background-image:url(images/headergradient_right.gif)"></td>
	</tr>
</table>-->