<?php
session_start();
$uid = $_SESSION['uid'];

include 'loginchk.php';

include_once("MT/cGlobali.php");
$Global = new cGlobali();
$eventId = '';
$totalGateways = 0;

if (isset($_REQUEST['eventid']) && $_REQUEST['eventid'] > 0) {
    $eventId = $_REQUEST['eventid'];
    $query = "SELECT id FROM event WHERE id=" . $eventId . " and deleted=1";
    $outputPaymentInvoice = $Global->SelectQuery($query);
    if (!$outputPaymentInvoice) {
        $getEventGateways = "SELECT epg.id,pg.name,pg.description,epg.eventid,epg.gatewaytext FROM eventpaymentgateway epg INNER JOIN paymentgateway pg ON pg.id=epg.paymentgatewayid WHERE epg.status=1 AND epg.deleted=0 AND epg.eventid='" . $eventId . "'";
        $responseGateways = $Global->SelectQuery($getEventGateways, MYSQLI_ASSOC);
        $totalGateways = count($responseGateways);
    }
}



//include 'templates/add_event_gateway_text.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents - Admin Panel - Add Gateway Event</title>
        <link href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
                <script language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.js"></script> 
                <script>
                    function validateEventIDForm(form)
                    {
                        var eventid = document.getElementById('eventid').value;
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
                        $('#saveGatewayText').click(function () {
                            //var inputData = [];
                            var fd = new FormData();
                            $.each($('.textGateway'), function (key, value) {
                                //inputData[] = ;
                                fd.append('inputData[' + $(this).attr('dbid') + ']', $(this).val());
                            });

                            fd.append('call', 'saveEventGatewayText');

                            //var idata = 'call=saveGatewayText&inputData='+inputData;
                            $.ajax({
                                type: 'POST',
                                url: 'processAjaxRequests.php',
                                data: fd,
                                cache: false,
                                contentType: false,
                                processData: false,
                                async: false,
                                // datatype:'JSON',
                                success: function (response) {
                                    var res = $.parseJSON(response);
                                    if (res.status && res.total > 0) {
                                        alert("Updated Successfully!!!");
                                    } else {
                                        alert(res.messages[0]);
                                    }
                                },
                                error: function (response) {
                                    var res = $.parseJSON(response);
                                    alert(res.messages[0]);
                                }
                            });
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
                                    <div align="center" style="width:100%" class="headtitle">Add Event Gateway Text</div>
                                    <div>

                                        <form action=""  onsubmit="return validateEventIDForm('eventid')" method="POST">
                                            <table>
                                                <td>Event ID</td><td><input type="text" name="eventid" id="eventid" value="<?php echo $eventId; ?>" /></td>
                                                <td><input type="submit" name="Sub" value="Submit" /></td>
                                            </table>
                                        </form>
                                    </div>
                                    <?php if ($totalGateways > 0) { ?>
                                        <div>Download sample gateway template text for paytm and copy the same for other's <a href="<?= _HTTP_CF_ROOT; ?>/samples/gatewaytexts.html" download>Sample file</a></div>
                                        <table width="100%">
                                            <tr>
                                                <td colspan="2"><table width="100%" class="sortable" style="width: 100% !Important">
                                                        <thead>
                                                            <tr>
                                                                <td class="tblcont1"><strong>S.No.</strong> </td>
                                                                <td class="tblcont1"><strong>Gateway Name</strong> </td>
                                                                <td class="tblcont1"><strong>Description</strong> </td>
                                                                <td class="tblcont1"><strong>Gateway Text</strong> </td>
                                                                <!--<td class="tblcont1"><strong>Feature status</strong> </td>-->
                                                                <!--<td class="tblcont1" ts_nosort="ts_nosort"><strong>Actions</strong> </td>-->
                                                                <!--<td class="tblcont1" ts_nosort="ts_nosort"><strong>Delete</strong></td>-->
                                                            </tr></thead>
                                                        <?php
                                                        for ($i = 0; $i < $totalGateways; $i++) {
                                                            ?>
                                                            <tr>
                                                                <td class="helpBod"><?php echo $i + 1; ?></td>
                                                                <td class="helpBod"><?php echo $responseGateways[$i]['name']; ?></td>
                                                                <td class="helpBod"><?php echo $responseGateways[$i]['description']; ?></td>
                                                                <td class="helpBod"><textarea class="textGateway" dbid="<?php echo $responseGateways[$i]['id']; ?>" style="width:500px;height:200px;"><?php echo $responseGateways[$i]['gatewaytext']; ?></textarea></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </table></td>
                                            </tr>
                                        </table>


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
