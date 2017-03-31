<?php
include 'MT/cGlobali.php';
$Global=new cGlobali();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>MeraEvents - Comments</title>
<script>
function checkComments(f){
    if(f.comments.value.length>0){
        return true;
    }else{
        alert("Please enter valid comments");
        return false;
    }
}
</script>
</head>
<body bgcolor="#F2F7FB;">
<div style="background-color:#F2F7FB; margin:0px; padding:0px;">
<div align="right" style="width:10px;height:10px; margin-bottom:20px; float:right;">
<a class="lbAction" rel="deactivate" href="TransbyEvent_new.php" style="padding:5px; float:right;"><img src="http://localhost/master/Meraevents/lightbox/close_button.gif" border="0" style="margin-bottom:10px;" /></a><br />
</div>
<div>
	
		<div>
			<div style="padding-left:10px;">
				<h1>Partial Payments Comments</h1>
			</div>
		</div>
	
</div>
<div >
	
		<!-- Page Info -->
        <div style="background-color:#F2F7FB;">
            <table border="1px" width="100%">
                <thead>
                    <tr>
                        <td width="5%"><h3>S.NO</h3></td>
                        <td width="80%"><h3>Comments</h3></td>
                </thead>
            <?php $selCmts="SELECT id,comment FROM comment WHERE eventid='".$_GET['EventId']."' and type = 'accounts'";
                                            $resComments=$Global->SelectQuery($selCmts);
                                            $cnt=1;
                                            foreach($resComments as $k=>$value){
                                    ?>
                <tr>   
                    <td id="srNo"><?=$cnt++?></td>
                    <td name="commentsdb<?=$value['id'];?>" id="commentsdb<?=$value['id'];?>"><?=stripslashes($value['comment']);?></td></tr>
                    <?php } ?>
            </table>
            <form name="add_comment" action="TransbyEvent_new.php?EventId=<?=$_GET['EventId']?>"  id="add_comment" method="post" style="margin:0px; padding:0px;" onsubmit="return checkComments(this)">
                <table width="800" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="20" height="40" align="left">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="20" height="30" align="left">&nbsp;</td>
                        <td width="101" height="30" align="left"><b>Comment :</b></td>
                        <td width="677" height="30" align="left">
                            <input type="hidden" name="eventId" id="EventId" value="<?=$_GET['EventId']?>"/>
                            <textarea name="comments" rows="10" cols="30" id="comments" ></textarea></td>
                    </tr>
                    <tr>
                        <td height="35" colspan="3" align="center">
                            <div>
                                <div>
                                    <div align="center">
                                        <input type="Submit" name="AddComment" value="Add Comment" id="signin_submit" />
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
               </table>
            </form>
	  </div>

			</div>
</div>
</body>
</html>
