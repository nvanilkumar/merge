<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>MeraEvents - Admin Panel - Promotions done for event</title>
    <link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
    <link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
    <script language="javascript" src="<?php echo _HTTP_SITE_ROOT;?>/js/public/jQuery.js"></script>    
    <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script> 
<script>
    $(document).ready(function(){
        $('#emailOrg').click(function(){
            var data='call=emailOrg_Promotions&eventId=<?php echo $_GET['eventid']?>';
            $.ajax({
                type:'POST',
                url:'<?php echo _HTTP_SITE_ROOT?>/ctrl/ajax.php',
                data:data,
                success:function(e){
                  alert(e);
                }
            });
        });
        var r_status ='<?php echo $editPromo[0]['status']; ?>';
        if (r_status=="0" || r_status=="1" ){ 
            $("[name=status]").val([r_status]);
        }
    });
function validateEventIDForm(form)
{
       if(form=='eventid')
        {
            var eventid=Trim(document.getElementById('eventid').value);
            if(eventid.length==0)
            {
                    alert("Please enter a Event ID");
                    document.getElementById('eventid').focus();
                    return false;
            }else if(isNaN(eventid) || eventid<=0){
                    alert("Please enter valid Event ID");
                    document.getElementById('eventid').focus();
                    return false;
            }
            else
            {
                    $.post('../processAjaxRequests.php',{call:'isEventIdExists',event_id:eventid}, function(data){
                            var newData=jQuery.parseJSON(data);
                            if(newData.eventExists)
                            {
                                window.location="addpromotions.php?eventid="+eventid;
                            }
                            else
                            {
                                alert("Sorry, we did not find the Event ID, Please Re-enter");
                                document.getElementById('eventid').focus();
                                return false;
                            }
                    });

            }   
        }
        return false;
}


function Trim(str)
{   
      while(str.charAt(0) == (" ") )
      {  
           str = str.substring(1);
      }
      while(str.charAt(str.length-1) == " " )
      {  
           str = str.substring(0,str.length-1);
      }
      return str;
}

function Remove(val,event_id)
{		
    if(window.confirm("Are you sure want to delete this record..?"))
    {
        window.location="addpromotions.php?delete="+val+"&eventid="+event_id;
    }   
}


function validateEventAddForm(f)
{
    var promotionMedium=Trim(f.promotionMedium.value);
    var promotionURL=Trim(f.promotionURL.value);
    if(promotionMedium.length==0)
    {
        alert("Please enter promotion medium");
        document.getElementById('promotionMedium').focus();
        return false;
    }
    else if(promotionURL.length==0){
        alert("Please enter promotion URL");
        document.getElementById('promotionURL').focus();
        return false;
    }else if(promotionURL.length>0){
       /* var pattern1=new RegExp("^(http(?:s)?\\:\\/\\/[a-zA-Z0-9\\-]+(?:\\.[a-zA-Z0-9\\-]+)*\\.[a-zA-Z]{2,6}(?:\\/?|(?:\\/[\\w\\-]+)*)(?:\\/?|\\/\\w+\\.[a-zA-Z]{2,4}(?:\\?[\\w]+\\=[\\w\\-]+)?)?(?:\\&[\\w]+\\=[\\w\\-]+)*)$");
        if(!pattern1.test(promotionURL)){
            alert("Please enter valid URL with http (or) https");
            return false;*/
            $('#promotionURL').trigger('invalid');
        }
        
    return true;
	
	
}
$('#promotionURL').bind('invalid', function(){
   alert('Please enter valid URL'); 
   return false;
});
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
<!-------------------------------promotions PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
      document.getElementById('ans6').style.display='block';
</script>

<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Add Promotions done by MeraEvents for the Event</div>
<div>

<form action="#"  onsubmit="return validateEventIDForm('eventid')">
	<table>
    	<td>Event ID</td><td><input type="text" name="eventid" id="eventid" value="<?php echo $eventid; ?>" /></td>
        <td><input type="submit" name="Sub" value="Submit" /></td>
    </table>
</form>
</div>

<?php 
$countPromotionRes=count($promotionsRes);
if($countPromotionRes > 0) {     ?>
<div id="promotionsForm">
<table>
    <tr>
        <th><br />Event Details</th>
    </tr>
    <tr>
    	<td>Event Title</td>
        <td><?php echo stripslashes($promotionsRes[0]['Title']); ?></td>
    </tr>
    <tr>
    	<td>Event URL</td>
        <td><a href="<?php echo _HTTP_SITE_ROOT;?>/event/<?php echo $promotionsRes[0]['URL']; ?>" target="_blank"><?php echo _HTTP_SITE_ROOT;?>/event/<?php echo  $promotionsRes[0]['URL']; ?></a></td>
    </tr>
    <tr>
        <th><br /></th>
    </tr>
</table>
<form method="post" action="" onsubmit="return validateEventAddForm(this)">
<table>
    <tr>
    	<td>Promotions Medium</td>
        <td><input type="text" name="promotionMedium" id="promotionMedium" value="<?php echo $editPromo[0]['promotionMedium'];?>" /><br /></td>
    </tr>
    <tr>
    	<td>Promotions Link</td>
        <td><input type="URL" name="promotionURL" id="promotionURL" value="<?php echo $editPromo[0]['promotionURL'];?>" /><br /></td>
    </tr>
    <tr>
    	<td></td>
        <td><span style="color: #006633;">(Please enter link with http or https)</span></td>
    </tr>
    <tr>
    	<td>Status</td>
        <td> <input type="radio" name="status" value="1"  checked/> Acitve  <br/>
             <input type="radio" name="status" value="0" /> In Acitve</td>
    </tr>
    <tr>
    	<td>Comments</td>
        <td><textarea name="comments" id="comments" ><?php echo @$editPromo[0]['comments'];?> </textarea>
             <br /></td>
    </tr>
    
    <tr>
    	<td colspan="2">
            <input type="hidden" name="addPromotion" value="1" />
            <input type="hidden" name="eventId" value="<?php echo $promotionsRes[0]['EventId']?>" />
            <input type="submit" value="<?php if(isset($_REQUEST['edit']))echo 'MODIFY'; else echo 'ADD'; ?>" />
        </td>
    </tr>
</table>
</form>

</div>





<div align="center" style="width:100%">&nbsp;</div>
<?php if(count($promotionsRes) ==0 && is_null($promotionsRes[0]['promotionMedium']) && is_null($promotionsRes[0]['promotionURL']))
    {
?>
    <table width="90%" align="center">
        <tr>
            <td width="100%" align="center" valign="middle">No records found.</td>
         </tr>
    </table>
<?php
    }else{
?>
<form action="" method="get" name="frmEofMonth" enctype="multipart/form-data">
<table width="100%" align="center" >
    <tr>
        <td width="5%" align="left" valign="middle" class="tblcont1">Sr. No.</td>
        <td width="5%" align="left" valign="middle" class="tblcont1">Event ID</td>
        <td width="30%" align="left" valign="middle" class="tblcont1">Event Name</td>
        <td width="20%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Promotion Medium</td>
        <td width="30%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Promotion URL</td>
        <td width="30%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Status</td>
        <td width="30%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Comments</td>
        <td width="10%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Action</td>
       
    </tr>
    <?php 
         $cnt=1;
        for($i = 0; $i <$countPromotionRes ; $i++)
        {
             if($i%2==0)
              {
              $trcol="bgcolor=#D6D6D6";
              }else{
                 $trcol="bgcolor=#8C8C8C";

                }
    
    ?>
    <tr <?php echo $trcol;?>>
        <td align="center" valign="middle"  height="25"><?php echo $cnt++?></td>
        <td align="left" valign="middle" >
            <a href="<?php echo _HTTP_SITE_ROOT;?>/event/<?php echo $promotionsRes[$i]['URL']; ?>" target="_blank" title="Preview this Event"><?php echo $promotionsRes[$i]['EventId']; ?></a>
        </td>     
        <td align="left" valign="middle" ><?php echo stripslashes($promotionsRes[$i]['Title']);?></td>  
        <td align="left" valign="middle" ><?php echo $promotionsRes[$i]['promotionMedium'];?></td>
        <td align="left" valign="middle" ><?php echo $promotionsRes[$i]['promotionURL'];?></td>
        <td align="left" valign="middle" ><?php echo ($promotionsRes[$i]['status']==1)?'Active':'In Active';?></td>
        <td align="left" valign="middle" ><?php echo $promotionsRes[$i]['comments'];?></td>
        <td>
            <a style="cursor:pointer" href="addpromotions.php?edit=<?php echo $promotionsRes[$i]['Id']?>&eventid=<?php echo $promotionsRes[$i]['EventId']?>" title="edit this record"><img src="images/edit.jpg" /></a>
<!--           <a style="cursor:pointer" onClick="Remove('<?php echo $promotionsRes[$i]['Id']; ?>','<?php echo $promotionsRes[$i]['EventId']; ?>');" title="Delete this record"><img src="images/delet.jpg" /></a>-->
        </td>
    </tr>
    <?php 
    } //ends for loop
   ?>
    <tr>
        <td colspan="4" class="tblcont1">&nbsp;</td>
        <td colspan="4" class="tblcont1" style="text-align: center;"><input type="button" value="Send Email to Organizer" name="emailOrg" id="emailOrg"/></td>
    </tr>
    </table></form>
<?php }//else table
} //count more than 0
?>

<div align="center" style="width:100%">&nbsp;</div>
<!-------------------------------PROMOTIONS PAGE ENDS HERE--------------------------------------------------------------->
                </div>
            </td>
        </tr>
    </table>
</div>    
</body>
</html>
