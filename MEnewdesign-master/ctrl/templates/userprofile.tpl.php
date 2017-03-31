<div align="center" style="width:100%">
<div align="center" style="width:100%">&nbsp;</div>
<?php
		if($sql_selprof['rid'] == 3)
		{
?>
		<div id="org" align="center">
				<table width="50%" cellpadding="2" cellspacing="2" style="border:thin; border-color:#006699; border-style:solid;">
						<tr>
						  <td width="14%" class="tblcont">&nbsp;</td>
						  <td width="32%" align="left" valign="middle" class="tblcont">Username</td>
								<td width="7%" align="center" valign="middle" class="tblcont">:</td>
								<td width="47%" align="left" valign="middle"><?=$arr_user['username'][0]?></td>
						</tr>
						<tr>
						  <td class="tblcont">&nbsp;</td>
						  <td align="left" valign="middle" class="tblcont">Email</td>
								<td align="center" valign="middle" class="tblcont">:</td>
								<td align="left" valign="middle"><?=$arr_user['email'][0]?></td>
						</tr>
						<tr>
						  <td valign="top" class="tblcont">&nbsp;</td>
						  <td align="left" valign="middle" class="tblcont">Personal Detail</td>
								<td align="center" valign="middle" class="tblcont">:</td>
								<td align="left" valign="middle">
										<strong><?php echo $arr_user['salutation'][0].' '.$arr_user['fname'][0].' '.$arr_user['lname'][0]; ?></strong>
										<?php
											echo '<br>';
											echo $arr_user['addr'][0].',<br>'.$arr_user['city'][0].', '.$arr_user['country'][0].'<br>';
											echo 'Phone: '.$arr_user['mob'][0];
										?>								</td>
						</tr>
						<tr>
						  <td valign="top" class="tblcont">&nbsp;</td>
						  <td align="left" valign="middle" class="tblcont">Company Detail</td>
								<td align="center" valign="middle" class="tblcont">:</td>
								<td align="left" valign="middle">
										<strong><?=$arr_user['comp_name'][0]?></strong><br />
										<?php 
											echo $arr_user['comp_addr'][0].',<br>'.$arr_user['comp_city'][0].','.$arr_user['comp_country'][0].'<br>';
											echo 'Phone: '.$arr_user['comp_ph'][0].',<br> Fax: '.$arr_user['comp_fax'][0].'<br>';
											echo 'Email: '.$arr_user['comp_email'][0].',<br> Type: '.$arr_user['comp_type'][0].'<br>';
											echo 'URL: '.$arr_user['comp_url'][0];
										?>								</td>
						</tr>
				</table>
  </div>		
<?php			
		}
		if($sql_selprof['rid'] == 4)
		{
?>
		<div id="del" align="center">
				<table width="50%" cellpadding="2" cellspacing="2" style="border:thin; border-color:#006699; border-style:solid;">
						<tr>
						  <td width="14%" class="tblcont">&nbsp;</td>
						  <td width="32%" align="left" valign="middle" class="tblcont">Username</td>
								<td width="7%" align="center" valign="middle" class="tblcont">:</td>
								<td width="47%" align="left" valign="middle"><?=$arr_user['username'][0]?></td>
						</tr>
						<tr>
						  <td class="tblcont">&nbsp;</td>
						  <td align="left" valign="middle" class="tblcont">Email</td>
								<td align="center" valign="middle" class="tblcont">:</td>
								<td align="left" valign="middle"><?=$arr_user['email'][0]?></td>
						</tr>
						<tr>
						  <td valign="top" class="tblcont">&nbsp;</td>
						  <td align="left" valign="middle" class="tblcont">Personal Detail</td>
								<td align="center" valign="middle" class="tblcont">:</td>
								<td align="left" valign="middle">
										<strong><?php echo $arr_user['salutation'][0].' '.$arr_user['fname'][0].' '.$arr_user['lname'][0]; ?></strong>
										<?php
											echo '<br>';
											echo $arr_user['addr'][0].',<br>'.$arr_user['city'][0].', '.$arr_user['country'][0].'<br>';
											echo 'Phone: '.$arr_user['mob'][0];
										?>								</td>
						</tr>
				</table>
  </div>
<?php		
		}
?>		
</div>