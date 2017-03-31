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
window.location="properties.php?cityid="+val;
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
<div align="center" style="width:100%" class="headtitle">Property List</div>
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
        <td width="40%" align="left" valign="middle" class="tblcont1">PropertyName</td>
        <td width="40%" align="left" valign="middle" class="tblcont1">Address</td>
         <td width="5%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">State</td>
        <td width="5%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">City</td>
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
        <td align="left" valign="middle" ><?php echo stripslashes($EventsOfMonth[$i]['Name']); ?></td>     
        <td align="left" valign="middle" ><?=$EventsOfMonth[$i]['Address'];?></td>     
      
            <td align="left" valign="middle"  id="EventsOfMonth"><?php echo $Global->GetSingleFieldValue("select State from  States where Id=".$EventsOfMonth[$i]['StateId']); ?>
        </td>
            
        <td align="left" valign="middle"  id="NotMore">
           <?php echo $Global->GetSingleFieldValue("select City from  Cities where Id=".$EventsOfMonth[$i]['CityId']); ?>
        </td>
       <td>
       <a href="#" onclick="window.open('http://venues.meraevents.com/property_add.php?Id=<?=$EventsOfMonth[$i]['Id'];?>&uid=<?=$EventsOfMonth[$i]['UserId'];?>&edit=yes','mywindow','menubar=1,width=900,height=600,resizable=yes,scrollbars=yes');">Edit</a>
      
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
