<?php 
	session_start();
 include 'loginchk.php';
 
	if($_SESSION['uid']=='')
	{
?>
		<script>
			window.location='index.php';
		</script>
<?php
	}

	include_once("MT/cGlobali.php");
	
	$Global = new cGlobali();	
	
	

	
	$TransId = $_REQUEST['TransId'];

	
	
//	$sqlsales="select * from sales";
        $sqlsales="select id as SalesId,name as SalesName from salesperson";
	$Ressales = $Global->SelectQuery($sqlsales); 
	
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>MeraEvents - Transaction Comments</title>
<link type="text/css" rel="stylesheet" href="<?=_HTTP_CF_ROOT;?>/css/me_customfields.min.css.gz" />
<?php include 'includes/include_js_css.php'; ?>
<script language="javascript" type="text/javascript" src="<?=_HTTP_CF_ROOT;?>/scripts/livevalidation.min.js.gz"></script>

</head>
<body bgcolor="#F2F7FB;">
<div style="background-color:#F2F7FB; margin:0px; padding:0px;">
<div align="right" style="width:10px;height:10px; margin-bottom:20px; float:right;">
<a class="lbAction" rel="deactivate" href="#" style="padding:5px; float:right;"><img src="<?=_HTTP_CF_ROOT;?>/lightbox/close_button.gif" border="0" style="margin-bottom:10px;" /></a><br />
</div>
<div>
	
		<div>
			<div style="padding-left:10px;">
				<h1>Send Email</h1>
			</div>
		</div>
	
</div>
<div >
	
		<!-- Page Info -->
		<div style="background-color:#F2F7FB;">
		  <form name="add_comment" action="CheckReg.php"  id="add_comment" method="post" style="margin:0px; padding:0px;">
        <input type="hidden" name="TransId" value="<?=$TransId;?>" />
         <input type="hidden" name="email" value="<?=$_REQUEST[email];?>" />
        <input type="hidden" name="recptno" value="<?=$_REQUEST[recptno];?>" />
         <input type="hidden" name="transid" value="<?=$_REQUEST[transid];?>" />
        <table width="800" cellpadding="2" cellspacing="2">
         <tr>
                  <td width="20" height="40" align="left">&nbsp;</td>
                  </tr>
                     <tr>
                  <td width="20" height="30" align="left">&nbsp;</td>
                <td width="101" height="30" align="left"><b>Subject:</b></td>
                <td width="677" height="30" align="left"><input type="text" name="subject" id="subject" size="70"/> 
				  <script language="javascript">
							var subject = new LiveValidation('subject');
							subject.add( Validate.Presence );
							
							</script></td>
  </tr>
                <tr>
                  <td width="20" height="30" align="left">&nbsp;</td>
                <td width="101" height="30" align="left"><b>Email Text :</b></td>
                <td width="677" height="30" align="left"><textarea name="emsg" rows="7" cols="70" id="emsg" ></textarea> 
				  <script language="javascript">
							var emsg = new LiveValidation('emsg');
							emsg.add( Validate.Presence );
							
							</script></td>
  </tr>
  
 <tr>
                  <td width="20" height="30" align="left">&nbsp;</td>
                <td width="101" height="30" align="left"><b>Sales Person :</b></td>
                <td width="677" height="30" align="left">
                 <select name="salesp" style="width:180px;" id="salesp">
                  <option value="">Select Sales</option>
                  <?php
				  for($i=0;$i<count($Ressales);$i++){
				  ?>
                  <option value="<?=$Ressales[$i]['SalesId'];?>"><?=$Ressales[$i]['SalesName'];?></option>
                  
                  <?php }?>
                  </select></td>
                  </tr>
                             

                <tr>
                  <td height="35" colspan="3" align="center"><div>
				<div>
					<div align="center">
					
					      <input type="Submit" name="SendEmail" value="Send Email" id="signin_submit" />
					     
					      </div>
				</div>
			  </div></td>
                  </tr>
               
                </table></tr>
                            </table>
		  </form>
	  </div>

		<?php include 'footer_js_css.php'; ?>
	</div>
</div>
</body>
</html>
