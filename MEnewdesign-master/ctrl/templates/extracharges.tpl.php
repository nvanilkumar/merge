<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents -Master Management - City Management</title>
        <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortable.min.js"></script>	
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.min.js"></script>	
                <script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
                <script>
                    $(document).ready(function () {
                        $('.extrachargeStatus').click(function () {
                            var status = 0;
                            var id = $(this).val();
                            if ($(this).is(':checked')) {
                                status = 1;
                                $(this).siblings('.extrachargeSpanStatus').text('Active');
                            } else {
                                $(this).siblings('.extrachargeSpanStatus').html('Inactive');
                            }
                            $('#messageRow').show();
                            $.ajax({
                                url: '<?php echo _HTTP_SITE_ROOT . "/ctrl/ajax.php" ?>',
                                type: 'POST',
                                data: 'call=updateExtraChargeStatus&status=' + status + '&id=' + id,
                                success: function (response) {
                                    $('#messageRow').hide();
                                    var res = $.parseJSON(response);
                                    if (res.status && res.response.total > 0) {
                                        alert("Status updated successfully!!!");
                                    } else {
                                        alert(res.messages[0]);
                                    }
                                },
                                error: function (response) {
                                    $('#messageRow').hide();
                                    var res = $.parseJSON(response);
                                    alert(res.messages[0]);
                                }
                            });
                        });
                    })

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
                                <div id="divMainPage" style="margin-left: 10px; margin-right:5px">
                                    <!-------------------------------CITY LIST PAGE STARTS HERE--------------------------------------------------------------->

                                    <div align="center" style="width:100%">
                                        <form action="" method="post" name="edit_form">
                                            <table width="0%" border="0" cellpadding="3" cellspacing="3">
                                                <tr>
                                                    <td align="center"  valign="middle" class="headtitle"><strong>Extra Charges Management</strong> </td>
                                                </tr>
                                                <tr id="messageRow" style="display: none;">
                                                    <td align="center"  valign="middle" class="headtitle">Please wait... </td>
                                                </tr>
                                                <tr>
                                                    <td ><table width="80%" align="center">
                                                            <thead>
                                                                <tr>
                                                                    <td class="tblcont1">EventId</td>
                                                                    <td class="tblcont1">Event</td>
                                                                    <td style="width:400px;" class="tblcont1">Label</td>
                                                                    <td style="width:400px;" class="tblcont1">Amount</td>
                                                                    <td style="width:400px;" class="tblcont1">Type</td>
                                                                    <td style="width:400px;" class="tblcont1" ts_nosort="ts_nosort">Edit </td>
                                                                    <td style="width:400px;" class="tblcont1" ts_nosort="ts_nosort">Status</td>
                                                                </tr></thead>
                                                            <?php
                                                            $flag = 0;
                                                            for ($i = 0; $i < count($CityList); $i++) {
                                                                ?>
                                                                <tr>
                                                                    <td class="helpBod"><?php echo $CityList[$i]['eventid']; ?></td>
                                                                    <td class="helpBod"><?php echo $CityList[$i]['title']; ?></td>
                                                                    <td class="helpBod"><?php echo $CityList[$i]['label']; ?></td>
                                                                    <td class="helpBod"><?php
                                                                        echo $CityList[$i]['value'];
                                                                        if ($CityList[$i]['type'] == 2)
                                                                            echo " " . $CityList[$i]['code'];
                                                                        ?></td>
                                                                    <td class="helpBod"><?php echo ($CityList[$i]['type'] == 1) ? 'Percent' : 'Flat'; ?></td>
                                                                    <td class="helpBod"><a href="extracharges_edit.php?id=<?php echo $CityList[$i]['id']; ?>">Edit</a></td>
                                                                    <td style="width:400px;" class="helpBod"><input type="checkbox" name="city[]" class="extrachargeStatus" value="<?php echo $CityList[$i]['id']; ?>" <?php
                                                                        if ($CityList[$i]['status'] == 1) {
                                                                            echo 'checked="checked"';
                                                                        }
                                                                        ?> /><span class="extrachargeSpanStatus"><?php echo $CityList[$i]['status'] == 1 ? 'Active' : 'Inactive'; ?></span></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </table></td>
                                                </tr>
                                                <tr>
                                                    <td><label>
                                                            <div>
                                                                <input type="button" name="Add" value="Add" onClick="document.location = 'addextracharges.php'">
                                                                    &nbsp;
                                                                    <!--<input type="submit" name="Submit" value="Delete" onClick="return confirm('Are You Sure You Want To Delete.\n\nThe Changes Cannot Be Undone');">-->
                                                            </div>
                                                        </label></td> 
                                                    <td><label>
                                                            <div align="right">

                                                            </div>
                                                        </label></td>
                                                </tr>
                                            </table>
                                        </form>
                                        <div align="center" style="width:100%">&nbsp;</div>
                                    </div>
                                    <!-------------------------------CITY LIST PAGE ENDS HERE--------------------------------------------------------------->
                                </div>
                            </td>
                        </tr>
                    </table>
                    </div>	
                </body>
                </html>