<?php
session_start();
$uid = $_SESSION['uid'];
include 'loginchk.php';
include_once("MT/cGlobali.php");
$Global = new cGlobali();
$eventId = '';
$totalEvents = 0;
if (isset($_REQUEST['eventid']) && $_REQUEST['eventid'] > 0) {
    $eventId = $_REQUEST['eventid'];
    $getEvent = "SELECT e.id,e.url,e.title,ed.externalurl FROM event e INNER JOIN eventdetail ed ON ed.eventid=e.id WHERE e.deleted=0 and e.id='" . $eventId . "' LIMIT 1";
    $responseGetEvent = $Global->SelectQuery($getEvent, MYSQLI_ASSOC);
    $totalEvents = count($responseGetEvent);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents - Admin Panel - Add Microsite Event URL</title>
        <link href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
                <script language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.js"></script> 
                <script>
                    function validateEventIDForm(form)
                    {
                        if (form == 'eventid')
                        {
                            var eventid = Trim(document.getElementById('eventid').value);
                                if (eventid.length == 0)
                                {
                                    alert("Please enter a Event Id");
                                    document.getElementById('eventid').focus();
                                    return false;
                                } else if (isNaN(eventid) || eventid <= 0) {
                                    alert("Please enter valid Event Id");
                                    document.getElementById('eventid').focus();
                                    return false;
                                } else {
                                    $.get('includes/ajaxSeoTags.php', {eventIDChk: 0, eventid: eventid}, function (data) {
                                        if (data == "error")
                                        {
                                            alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
                                            document.getElementById('eventid').focus();
                                            return false;

                                        }
                                    });
                                }
                            }
                        }


                    function Trim(str)
                    {
                        while (str.charAt(0) == (" "))
                        {
                            str = str.substring(1);
                        }
                        while (str.charAt(str.length - 1) == " ")
                        {
                            str = str.substring(0, str.length - 1);
                        }
                        return str;
                    }
                    $(document).ready(function () {
                        $('#save').click(function () {
                            var micrositeURL = $('#micrositeURL').val();
                            if (micrositeURL != '' && !/^(http|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:/~+#-]*[\w@?^=%&amp;/~+#-])?/.test(micrositeURL)) {
                                alert("Please enter valid URL with http(s)")
                                return false;
                            } else {
                                var data = {call: 'addMicrositeURL'};
                                data.value = $('#micrositeURL').val();
                                data.eventId = $('#eventid').val();
                                console.log(JSON.stringify(data));
                                $.ajax({
                                    type: 'POST',
                                    url: 'processAjaxRequests.php',
                                    data: data,
                                    success: function (response) {
                                        var res = $.parseJSON(response);
                                        if (res.status && res.response.total > 0) {
                                            alert("Updated Successfully!!!");
                                        } else {
                                            alert(res.response.messages[0]);
                                        }
                                    },
                                    error: function (response) {
                                        var res = $.parseJSON(response);
                                        alert(res.response.messages[0]);
                                    }
                                });
                            }
                        });
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
                                    <!-------------------------------Add Gateway Event PAGE STARTS HERE--------------------------------------------------------------->
                                    <script language="javascript">
                                        document.getElementById('ans6').style.display = 'block';</script>

                                    <div align="center" style="width:100%">&nbsp;</div>
                                    <div align="center" style="width:100%" class="headtitle">Add Microsite URL</div>
                                    <div>

                                        <form action=""  onsubmit="return validateEventIDForm('eventid')" method="POST">
                                            <table>
                                                <td>Event ID</td><td><input type="text" name="eventid" id="eventid" value="<?php echo $eventId; ?>" /></td>
                                                <td><input type="submit" name="Sub" value="Submit" /></td>
                                            </table>
                                        </form>
                                    </div>
                                    <?php if ($totalEvents) { ?>
                                        <div style="border: 1px solid black;padding: 5%;width: 50%;text-align: center;margin-left: 20%;margin-top: 5%;">
                                            <h3>Event Name:<a  href="<?php echo _HTTP_SITE_ROOT . '/event/' . $responseGetEvent[0]['url']; ?>" target="_blank"><?php echo $responseGetEvent[0]['title']; ?></a></h3><br/>
                                            <span>Microsite URL:</span><input style="width: 340px;margin-left: 10px;" type="text" name="micrositeURL" value="<?php echo $responseGetEvent[0]['externalurl']; ?>" id="micrositeURL"/><input style="margin-left: 10px;" value="Save" type="button" name="save" id="save"/>
                                        </div>
                                    <?php } ?>
                                    <div align="center" style="width:100%">&nbsp;</div>
                                    <!-------------------------------Add Gateway Event PAGE ENDS HERE--------------------------------------------------------------->
                                </div>
                            </td>
                        </tr>
                    </table>
                    <input style="margin-left:500px;" type="button" name="saveGatewayText" id="saveGatewayText" value="Save"/>
                    <div style="width: 20px;">&nbsp;</div>
                    </div>    
                </body>
                </html>
