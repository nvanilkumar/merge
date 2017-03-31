<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents - Admin Panel - Organizer Login</title>
        <link href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">

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
                                <div id="divMainPage" style="margin-left: 10px; margin-right:5px">
                                    <script language="javascript">
                                        document.getElementById('ans9').style.display = 'block';
                                    </script>
                                    <script language="javascript" src="<?= _HTTP_SITE_ROOT; ?>/js/public/jQuery.js"></script> 
                                    <script language="javascript">
                                        function SEdt_validate()
                                        {
                                            var EventId = document.apieventsubmit.EventId.value;

                                            if (EventId.trim() == '')
                                            {
                                                alert('Please Enter Event-Id');
                                                document.apieventsubmit.EventId.focus();
                                                return false;
                                            } else if (isNaN(EventId.trim())) {
                                                alert('Please Enter vaild Event-Id');
                                                document.apieventsubmit.EventId.focus();
                                                return false;
                                            } else {
                                                $.get('includes/ajaxSeoTags.php', {eventIDChk: 0, eventid: EventId}, function (data) {
                                                    if (data == "error")
                                                    {
                                                        alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
                                                         document.apieventsubmit.EventId.focus();
                                                        return false;

                                                    }
                                                });
                                            }
                                        }
                                        function updatestatus(Id, nStatus, type)
                                        {
                                            $.ajax({
                                                data: 'call=apiEnabling&EventId=' + Id + '&Status=' + nStatus + '&Type=' + type + '&adminId=' + '<?php echo $uid ?>',
                                                url: '<?= _HTTP_SITE_ROOT ?>/ctrl/ajax.php',
                                                type: 'POST',
                                                success: function (res) {
                                                    var newRes = $.parseJSON(res);
                                                    if (newRes['status']) {
                                                        alert(newRes['status']);
                                                        location.href = "<?= _HTTP_SITE_ROOT ?>/ctrl/apiEvent.php?EventId=" + Id;
                                                    }
                                                }
                                            });
                                        }
                                    </script>
                                    <div align="center" style="width:100%">&nbsp;</div>
                                    <div align="center" style="width:100%" class="headtitle">Enter  Event-Id</div>
                                    <div align="center" style="width:100%">&nbsp;</div>
                                    <form action="" method="post" name="apieventsubmit" id="apieventsubmit">
                                        <table width="50%" align="center" class="tblcont">
                                            <tr>
                                                <td width="35%" align="left" valign="middle">Event-Id:&nbsp;<input type="text" name="EventId" id="EventId" value="<?php echo $EventId; ?>"  /></td>
                                                <td width="30%" align="left" valign="middle"><input type="submit" name="getEveDtls" id="getEveDtls" value="Submit" onclick="return SEdt_validate();" /></td>
                                                <tr>
                                                    </table>
                                                    <p align="center"><?php if ($msg != '') echo $msg; ?></p>
                                                    <?php if (count($ResOrgQuery) > 0) { ?>
                                                        <table width="100%" align="center" class="sortable">
                                                            <tr>

                                                                <td width="40%" align="left" valign="middle" class="tblcont1">Event Id</td>
                                                                <td width="15%" align="left" valign="middle" class="tblcont1">Standard API</td>
                                                                <td width="15%" align="left" valign="middle" class="tblcont1">Mobile API</td>

                                                            </tr>
                                                            <?php
                                                            for ($i = 0; $i < count($ResOrgQuery); $i++) {
                                                                ?>
                                                                <tr>

                                                                    <td align="left" valign="middle" class="helpBod"><?php echo $ResOrgQuery[$i]['eventid']; ?></td> 	
                                                                    <td align="left" valign="middle" class="helpBod">
                                                                        <input type="checkbox" name="stdstatus" <?php
                                                                        if ($ResOrgQuery[$i]['standardapi'] == '1') {
                                                                            $stdStatus = 0;
                                                                            ?> checked="checked" value="1" <?php
                                                                               } else {
                                                                                   $stdStatus = 1;
                                                                                   ?> value="0" <?php } ?> onclick="updatestatus('<?php echo $EventId; ?>', '<?php echo $stdStatus ?>', '1');" />
                                                                    </td>
                                                                    <td align="left" valign="middle" class="helpBod" >
                                                                        <input type="checkbox" name="mobstatus" <?php
                                                                        if ($ResOrgQuery[$i]['mobileapi'] == '1') {
                                                                            $mobStatus = 0;
                                                                            ?> checked="checked" value="1" <?php
                                                                               } else {
                                                                                   $mobStatus = 1;
                                                                                   ?> value="0" <?php } ?> onclick="updatestatus('<?php echo $EventId; ?>', '<?php echo $mobStatus ?>', '2');" />
                                                                    </td>


                                                                </tr>
                                                                <?php
                                                            } //ends for loop
                                                            ?>
                                                        </table>
                                                        <?php
                                                    } //ends if condition     
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