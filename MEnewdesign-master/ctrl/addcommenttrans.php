<?php 
	session_start();
 include 'loginchk.php';
 


	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();	
	
	

	
	$TransId = ($_GET['TransId'])?$_GET['TransId']:$_POST['TransId'];

	
	if($TransId > 0)
	{
	$sqlTransComments="select * from CancelTransComments where EventSIgnupId=".$TransId;
	$indId = $Global->SelectQuery($sqlTransComments); 
	}
	
	
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
<a class="lbAction" rel="deactivate" href="#" style="padding:5px; float:right;"><img src="<?=_HTTP_SITE_ROOT?>/lightbox/close_button.gif" border="0" style="margin-bottom:10px;" /></a><br />
</div>
<div>
	
		<div>
			<div style="padding-left:10px;">
				<h1>Cancel Transaction Comments</h1>
			</div>
		</div>
	
</div>
<div >
	
		<!-- Page Info -->
		<div style="background-color:#F2F7FB;">
        <table width="800" border="1" cellspacing="2" cellpadding="2">
  


           <?
		$TotalindId = count($indId);

		for($i=0; $i < $TotalindId; $i++)
		{?>
		
		 <tr>
    <td width="20"  align="left"><?=$i+1;?></td>
    <td><div><?=$indId[$i][Comment];?></div><div align="right" style="color:#007788; font-family:Arial, Helvetica, sans-serif; font-size:13px;">Posted on: <?=date('d F Y',strtotime($indId[$i][PostedDate]));?></div></td>
  </tr>
	<?	}
		?>
</table>

	    <form name="add_comment" action="transactionhistory.php?TransEventId=<?=$_REQUEST[TransEventId];?>&enddate=<?=$_REQUEST[enddate];?>&startdate=<?=$_REQUEST[startdate];?>"  id="add_comment" method="post" style="margin:0px; padding:0px;">
        <input type="hidden" name="TransId" value="<?=$TransId;?>" />
       
        
        <table width="800" cellpadding="0" cellspacing="0">
         <tr>
                  <td width="20" height="40" align="left">&nbsp;</td>
                  </tr>
                <tr>
                  <td width="20" height="30" align="left">&nbsp;</td>
                <td width="101" height="30" align="left"><b>Comment :</b></td>
                <td width="677" height="30" align="left"><textarea name="comment" rows="10" cols="30" id="comment" ><?=$txtName?></textarea> 
				  <script language="javascript">
							var comment = new LiveValidation('comment');
							comment.add( Validate.Presence );
							
							</script></td>
  </tr>
  

                             

                <tr>
                  <td height="35" colspan="3" align="center"><div>
				<div>
					<div align="center">
					
					      <input type="Submit" name="AddComment" value="Add Comment" id="signin_submit" />
					     
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
