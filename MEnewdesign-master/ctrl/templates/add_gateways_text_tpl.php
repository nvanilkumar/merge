<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents -Menu Content Management</title>
        <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortable.js"></script>	
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.js"></script>	
                <script>
                    $(document).ready(function () {
                        $('#saveGatewayText').click(function () {
                            //var inputData = [];
                            var fd = new FormData();
                            $.each($('.textGateway'), function (key, value) {
                                //inputData[] = ;
                                fd.append('inputData[' + $(this).attr('dbid') + ']', $(this).val());
                            });

                            fd.append('call', 'saveGatewayText');

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


                                    <!-------------------------------CONTENT PAGE STARTS HERE--------------------------------------------------------------->
                                    <script language="javascript">
                                        document.getElementById('ans9').style.display = 'block';
                                    </script>
                                    <div style="width:100%" align="center">
                                        <table width="100%">
                                            <tr>
                                                <td colspan="2" class="headtitle" align="center"><strong>Add Payment Gateway Text</strong> </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" align="center">Download sample gateway template text for paytm and copy the same for other's <a href="<?= _HTTP_CF_ROOT; ?>/samples/gatewaytexts.html" download>Sample file</a></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><table width="100%" class="sortable" style="width: 100%;">
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
                                                                <td class="helpBod"><textarea class="textGateway" dbid="<?php echo $responseGateways[$i]['id']; ?>" style="width: 500px;height: 200px;"><?php echo $responseGateways[$i]['gatewaytext']; ?></textarea></td>
                                                                        <!--<td class="helpBod"><a href="country_edit.php?id=<?= $CountryList[$i]['id'] ?>">Save</a></td>-->
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </table></td>
                                            </tr>
                                            <!--<tr>
                                                <td><label>
                                                        <div align="right">
                                                            <input type="button" name="Add" value="Add" onClick="document.location = 'addcountry.php'">
                                                        </div>
                                                    </label></td>
                                                <td><label>
                                                        <div align="right">
                                                            <input type="submit" name="Submit" value="Delete" onClick="return confirm('Are You Sure You Want To Delete These Countries.\n\nThe Changes Cannot Be Undone');">
                                                        </div>
                                                    </label></td>
                                            </tr>-->
                                        </table>
                                        <input type="button" name="saveGatewayText" id="saveGatewayText" value="Save"/>
                                        <div style="width: 20px;">&nbsp;</div>
                                        <div align="center" style="width:100%">&nbsp;</div>
                                    </div>
                                    <!-------------------------------CONTENT PAGE ENDS HERE--------------------------------------------------------------->



                                </div>
                            </td>
                        </tr>
                    </table>
                    </div>	
                </body>
                </html>