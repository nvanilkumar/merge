<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>MeraEvents - Admin Panel - Events of the Month</title>
    <link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
    <link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
    <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>    
    <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script> 
<script>
function filtercity(val)
{
window.location="venuebanners.php?cityid="+val;
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
<!-------------------------------Events of the Month PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
      document.getElementById('ans8').style.display='block';
</script>

<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Venue List for Banners & Priority</div>
<div>Filter by City :- <select name="cityid" id="cityid" onchange="filtercity(this.value)">
<option value="">Select City</option>
<?
for($i=0;$i<count($ResCity);$i++)
{?>
<option value=<?=$ResCity[$i]['Id'];?> <? if($_REQUEST['cityid']==$ResCity[$i]['Id']){ ?> selected <? }?>><?=$ResCity[$i]['City'];?></option>
<? }

?>
</select>
</div>
<div align="center" style="width:100%">&nbsp;</div>
<form action="" method="get" name="frmEofMonth" enctype="multipart/form-data">






<?php if(count($EventsOfMonth) > 0) { ?>
<table width="100%" align="center" >
    <tr>
        <td width="10%" align="left" valign="middle" class="tblcont1">Sr. No.</td>
        <td width="40%" align="left" valign="middle" class="tblcont1">VenueTitle</td>
        <td width="40%" align="left" valign="middle" class="tblcont1">VenueAddress</td>
         <td width="5%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Priority</td>
        <td width="5%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Banner</td>
        <td width="5%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Action</td>
       
    </tr>
    <?php 
        $cnt=1;
        for($i = 0; $i < count($EventsOfMonth); $i++)
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
        <td align="left" valign="middle" ><?php echo stripslashes($EventsOfMonth[$i]['VenueTitle']); ?></td>     
        <td align="left" valign="middle" ><?=$EventsOfMonth[$i]['VenueAddress'];?></td>     
      
        <?php $newIsFamousStatus = 0; ?>
        <td align="left" valign="middle"  id="EventsOfMonth">
            <input type="checkbox" name="chkIsFamous" <?php if($EventsOfMonth[$i]['Priority'] == 1) { $newIsFamousStatus = 0; ?> checked="checked" value="1" <?php } else { $newIsFamousStatus = 1; ?> value="0" <?php } ?> onclick="updateIsFamous('<?php echo $EventsOfMonth[$i]['Id']; ?>','<?=$newIsFamousStatus?>');" />
        </td>
            <?php $newNotMoreStatus = 0; ?>
        <td align="left" valign="middle"  id="NotMore">
            <input type="checkbox" name="chkNotMore" <?php if($EventsOfMonth[$i]['Banner'] == 1) { $newNotMoreStatus = 0; ?> checked="checked" value="1" <?php } else { $newNotMoreStatus = 1; ?> value="0" <?php } ?> onclick="updateNotMore('<?php echo $EventsOfMonth[$i]['Id']; ?>','<?=$newNotMoreStatus?>');" />
        </td>
       <td>
       <a href="#" onclick="window.open('http://venues.meraevents.com/edit_venue.php?Id=<?=$EventsOfMonth[$i]['Id'];?>&uid=<?=$EventsOfMonth[$i]['UserId'];?>&edit=yes','mywindow','menubar=1,width=900,height=600,resizable=yes,scrollbars=yes');">Edit</a>
      
       </td>

    </tr>
    <?php 
    } //ends for loop
    ?>
</table>
<?php 
    } //ends if condition
    else if(count($EventsOfMonth) == 0)
    {
?>
    <table width="90%" align="center">
        <tr>
          <td width="100%" align="left" valign="middle">No match record found.</td>
         </tr>
    </table>
<?php
    }
?>
<script language="javascript" type="text/javascript">
    function updateIsFamous(eId,nStatus)
    {
    
        window.location = 'venuebanners.php?VId='+eId+'&newStatus='+nStatus;
    }
    function updateNotMore(eId,nStatus)
    {

        window.location = 'venuebanners.php?VId='+eId+'&newNotMoreStatus='+nStatus;
    }
	
</script>
</form>
<div align="center" style="width:100%">&nbsp;</div>
<!-------------------------------Events of the Month PAGE ENDS HERE--------------------------------------------------------------->
                </div>
            </td>
        </tr>
    </table>
</div>    
</body>
</html>
