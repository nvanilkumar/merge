<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>MeraEvents - Admin Panel - Manage SEO Data</title>
    <link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
    <link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
    <script language="javascript" src="<?=_HTTP_SITE_ROOT;?>/js/public/jQuery.js"></script>    
    <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>   
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
<!-------------------------------SEO types PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
function Remove(val)
{
                if(window.confirm("Are you sure you want to delete this event?")){
                    $.ajax({
                        data:'call=deleteEvent&EventId='+val+'&deleteRequest=true',
                        url:'<?=_HTTP_SITE_ROOT?>/ctrl/ajax.php',
                        type:'POST',
                        success:function(res){
                            var newRes=$.parseJSON(res);
                            if(newRes['transCheck']){
                                alert("This event has transactions, So you can not delete it.");
                            }
                            else if(newRes['status']){
                                alert("Successfully deleted the event");
                                location.href="<?=_HTTP_SITE_ROOT?>/ctrl/delete_request.php";
                            }else{
                                alert("Something went wrong,please try again!!!");
                            }
                        }
                    });
                }  
}

function Cancel(val)
{
		if(window.confirm("Are you sure want to cancel this delete request..?"))
		{
                    $.ajax({
                        data:'call=cancelRequest&EventId='+val,
                        url:'<?=_HTTP_SITE_ROOT?>/ctrl/ajax.php',
                        type:'POST',
                        success:function(res){
                            var newRes=$.parseJSON(res);
                            if(newRes['status']){
                                alert("Successfully canceled the delete request");
                                location.href="<?=_HTTP_SITE_ROOT?>/ctrl/delete_request.php";
                            }else{
                                alert("Something went wrong,please try again!!!");
                            }
                        }
                    });
		}
}

</script>

<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Delete Requests</div>
<div><?php echo $msg;?></div>
<!--deleted requests list-->
<?php if(count($deleteRequests) > 0) { ?>
<table width="100%" align="center" >
    <tr>
        <td width="5%" align="left" valign="middle" class="tblcont1">Sr. No.</td>
        <td width="5%" align="left" valign="middle" class="tblcont1">Event ID</td>
        <td width="10%" align="left" valign="middle" class="tblcont1">Event Name</td>
        <td width="10%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Action</td>
    </tr>
    <?php 
        $cnt=1;
        for($i = 0; $i < count($deleteRequests); $i++)
        {
              if($i%2==0)
              {
              $trcol="bgcolor=#D6D6D6";
              }else{
                 $trcol="bgcolor=#8C8C8C";

                }
    ?>
    <tr <?=$trcol;?>>
        <td align="center" valign="middle"  height="25"><?=$cnt++?></td>
        <td align="left" valign="middle" >
            <?php 
                echo $deleteRequests[$i]['id'];
            ?>
        </td>     
        <td align="left" valign="middle" ><?=stripslashes($deleteRequests[$i]['title']);?></td>
        <td>
            <a style="cursor:pointer" href="javascript:void(0);" onClick="Remove('<?php echo $deleteRequests[$i]['id']; ?>');" title="Delete the event">Delete event</a>
            &nbsp;&nbsp;
            <a style="cursor:pointer" href="javascript:void(0);" onClick="Cancel('<?php echo $deleteRequests[$i]['id'];?>')" title="Cancel the event">Cancel delete request</a>
        </td>
    </tr>
    <?php 
    } //ends for loop
    ?>
</table>
<?php
echo $functions->pagination($limit,$page,'deleterequest.php?page=',$rows); //call function to show pagination
 
    } //ends if condition
    else if(count($deleteRequests) == 0)
    {
?>
    <table width="100%" >
        <tr>
        <td width="5%" align="left" valign="middle" class="tblcont1">Sr. No.</td>
        <td width="5%" align="left" valign="middle" class="tblcont1">Event ID</td>
        <td width="10%" align="left" valign="middle" class="tblcont1">Event Name</td>
        <td width="10%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Action</td>
    </tr>
        <tr>
          <td width="100%" align="left" valign="middle" class="tblcont1" colspan="4">No record found.</td>
         </tr>
    </table>
<?php
    }
?>
<div align="center" style="width:100%">&nbsp;</div>
<!--deleted events-->
<div align="center" style="width:100%" class="headtitle">Deleted Events</div>
<?php if(count($deletedEvents) > 0) { ?>
<table width="100%" align="center" >
    <tr>
        <td width="5%" align="left" valign="middle" class="tblcont1">Sr. No.</td>
        <td width="5%" align="left" valign="middle" class="tblcont1">Event ID</td>
        <td width="10%" align="left" valign="middle" class="tblcont1">Event Name</td>
    </tr>
    <?php 
        $cnt=1;
        for($i = 0; $i < count($deletedEvents); $i++)
        {
              if($i%2==0)
              {
              $trcol="bgcolor=#D6D6D6";
              }else{
                 $trcol="bgcolor=#8C8C8C";

                }
    ?>
    <tr <?=$trcol;?>>
        <td align="center" valign="middle"  height="25"><?=$cnt++?></td>
        <td align="left" valign="middle" >
            <?php 
                echo $deletedEvents[$i]['id'];
            ?>
        </td>     
        <td align="left" valign="middle" ><?=stripslashes($deletedEvents[$i]['title']);?></td>
    </tr>
    <?php 
    } //ends for loop
    ?>
</table>
<?php
echo $functions->pagination($limit,$page,'deleterequest.php?page=',$rows); //call function to show pagination
 
    } //ends if condition
    else if(count($deletedEvents) == 0)
    {
?>
    <table width="90%">
    <tr>
        <td width="5%" align="left" valign="middle" class="tblcont1">Sr. No.</td>
        <td width="5%" align="left" valign="middle" class="tblcont1">Event ID</td>
        <td width="10%" align="left" valign="middle" class="tblcont1">Event Name</td>
    </tr>
        <tr>
            <td width="100%" align="left" valign="middle" class="tblcont1" colspan="3">No record found.</td>
         </tr>
    </table>
<?php
    }
?>
<div align="center" style="width:100%">&nbsp;</div>
<!-------------------------------SEO types PAGE ENDS HERE--------------------------------------------------------------->
                </div>
            </td>
        </tr>
    </table>
</div>    
</body>
</html>
