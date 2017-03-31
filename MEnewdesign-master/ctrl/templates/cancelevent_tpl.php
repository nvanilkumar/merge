<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>MeraEvents - Admin Panel - Cancel Event</title>
    <link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
    <link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" language="javascript" src="<?php echo  _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
    <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script> 
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
                    $.post('<?php echo _HTTP_SITE_ROOT?>/ctrl/processAjaxRequests.php',{call:'isEventIdExists',event_id:eventid,cancelEvent:true}, function(data){
						         var newData=jQuery.parseJSON(data);	
															 
                            if(newData.eventExists)
                            {
                                $('#cancelForm').css('display','block');
                                $('#eventTitle').html(newData.Title);
                                $('#eventURL').attr('href','<?php echo _HTTP_SITE_ROOT;?>/event/'+newData.URL);
                                $('#eventURL').text('<?php echo _HTTP_SITE_ROOT;?>/event/'+newData.URL);
                                var result=window.confirm("Are you sure you want to cancel event?");
                                if(result){
                                    $.post('<?php echo _HTTP_SITE_ROOT?>/ctrl/processAjaxRequests.php',{call:'cancelEvent',eventId:eventid},function(res){
                                        var data=$.parseJSON(res);
                                        console.log(data);
                                        if(data.eventCanceled){
                                            alert(data.eventCanceled);
                                        }else if(data.status){
                                            alert("Successfully cancelled event!!!");
                                        }
                                    });
                                }
                            }else if(newData.Message){
                                $('#cancelForm').css('display','block');
                                $('#eventTitle').html(newData.Title);
                                alert(newData.Message);
                                document.getElementById('eventid').focus();
                                return false;
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
<!-------------------------------CANCEL EVENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
      document.getElementById('ans9').style.display='block';
</script>

<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Cancel Event</div>
<div>

<form action="#"  onsubmit="return validateEventIDForm('eventid')">
	<table>
    	<td>Event ID</td><td><input type="text" name="eventid" id="eventid" value="<?php echo $eventid; ?>" /></td>
        <td><input type="submit" name="Sub" value="Submit" /></td>
    </table>
</form>
</div>

<div id="cancelForm" style="display: none;">
<table>
    <tr>
        <th><br />Event Details</th>
    </tr>
    <tr>
    	<td>Event Title</td>
        <td id="eventTitle"></td>
    </tr>
    <tr>
    	<td>Event URL</td>
        <td><a id="eventURL" href="#" target="_blank"></a></td>
    </tr>
    <tr>
        <th><br /></th>
    </tr>
</table>

</div>


<div align="center" style="width:100%">&nbsp;</div>
<!-------------------------------EVENT CANCEL PAGE ENDS HERE--------------------------------------------------------------->
                </div>
            </td>
        </tr>
    </table>
</div>    
</body>
</html>
