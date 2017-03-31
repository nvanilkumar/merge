
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>MeraEvents - Admin Panel - Promotions text for event</title>
    <link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
    <link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
    <script language="javascript" src="<?php echo _HTTP_SITE_ROOT;?>/js/public/jQuery.js"></script>    
    <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script> 
<script>
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
                    $.post('<?php echo _HTTP_SITE_ROOT?>/ctrl/processAjaxRequests.php',{call:'isEventIdExists',event_id:eventid}, function(data){
                            var newData=jQuery.parseJSON(data);
                            if(newData.eventExists)
                            {
                                window.location="addpromotionaltext.php?eventid="+eventid;
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
<div align="center" style="width:100%" class="headtitle">Add Promotional text for the Event</div>
<div>
    <div style="font-size: 13px; padding-bottom: 14px;"> <?php if(isset($_REQUEST['editID'])){echo 'Promotional Text is updated sucessfully';}?></div>
<form action="#"  onsubmit="return validateEventIDForm('eventid')">
	<table>
            <tr>
    	<td>Event ID</td><td><input type="text" name="eventid" id="eventid" value="<?php echo $eventid; ?>" /></td>
        <td><input type="submit" name="Sub" value="Submit" /></td></tr>
    </table>
</form>
</div>

<div align="center" style="width:100%;padding-top: 10px;">&nbsp;</div>
<div id="promotionsForm" style="display:<?php if(isset($promotionsRes))echo 'block';else echo 'none'; ?>">
        <form method="post" action="">
            <label>Promotional text</label>
            <div><textarea name="promotionalText" rows='8' cols='50' id="promotionalText" ><?php echo $promotionsRes[0]['promotionaltext'];?></textarea>
            </div>
            <input type="submit" value="UPDATE" />
        </form>
</div>

<div align="center" style="width:100%">&nbsp;</div>
<!-------------------------------PROMOTIONS PAGE ENDS HERE--------------------------------------------------------------->
                </div>
            </td>
        </tr>
    </table>
</div>    
</body>
</html>
