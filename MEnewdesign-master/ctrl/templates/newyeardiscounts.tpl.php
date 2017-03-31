<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents - <?php echo ucfirst($btype); ?> Discounts</title>
        <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <script language="javascript" src="<?= _HTTP_SITE_ROOT; ?>/js/public/jQuery.js"></script>  
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortable.min.js.gz"></script>	
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.min.js.gz"></script>	
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
                                        document.getElementById('ans68').style.display = 'block';

                                        function Remove(val)
                                        {
                                            if (window.confirm("Are you sure want to delete this record..?"))
                                            {
                                                $.ajax({
                                                    url: "<?= _HTTP_SITE_ROOT; ?>/ctrl/ajax.php",
                                                    type: "post",
                                                    data: {"delid": val, "delNYEdiscount": "remove"},
                                                    success: function (data) {
                                                        window.location.reload();
                                                    }
                                                });
                                            }

                                        }

                                        function changeStatus(rid, status)
                                        {
                                            $.ajax({
                                                url: "<?= _HTTP_SITE_ROOT; ?>/ctrl/ajax.php",
                                                type: "POST",
                                                data: "changeNYEDiscountStatus=1&rid=" + rid + "&status=" + status,
                                                async: false,
                                                success: function (msg) {
                                                    $("#statusRow" + rid).html(msg);
                                                }
                                            });


                                        }
                                    </script>
                                    <div style="width:100%" align="center">
                                        <form action="" method="post" name="edit_form">
                                            <table width="80%">
                                                <tr>
                                                    <td colspan="2" class="headtitle" align="center"><strong><?php echo ucfirst($btype); ?> Discounts and Promotions</strong> </td>
                                                </tr>
                                                <!--<tr>
                                                  <td colspan="2" align="left"><a href="admin.php" class="menuhead" title="Master management Home">Master Management Home</a></td>
                                                </tr>-->


                                                <tr>
                                                    <td colspan="2" align="right"><input type="button" name="Add" value="Add New Discount" onClick="document.location = 'nye_discounts_edit.php?btype=<?php echo $btype; ?>'"></td>
                                                </tr>

                                                <?php
                                                if (isset($_SESSION['nye_disc_created'])) {
                                                    ?><tr><td colspan="2"><b style="color:#090; font-weight:bold; font-size:14px;">Discount Code updated successfully..</b></td></tr><?php
                                                    unset($_SESSION['nye_disc_created']);
                                                }
                                                ?>

                                                <tr><td colspan="2"><br /></td></tr>

                                                <tr>
                                                    <td colspan="2"><table class="sortable" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <td class="tblcont1"><strong>Sno</strong> </td>
                                                                    <!--<td class="tblcont1" ts_nosort="ts_nosort"><strong>Event Id</strong> </td>-->
                                                                    <td class="tblcont1" ts_nosort="ts_nosort" style="width:280px;"><strong>Discount Text</strong> </td>
                                                                    <td class="tblcont1" ts_nosort="ts_nosort"><strong>Promo Code</strong> </td>
                                                                    <td class="tblcont1" ts_nosort="ts_nosort"><strong>City Name</strong> </td>
                                                                    <td class="tblcont1" ts_nosort="ts_nosort"><strong>Created On</strong> </td>
                                                                    <td class="tblcont1" ts_nosort="ts_nosort"><strong>Status</strong> </td>
                                                                    <td class="tblcont1" ts_nosort="ts_nosort"><strong>Actions</strong></td>
                                                                </tr></thead>
                                                            <?php
                                                            $totalCount = count($nye_disc_list);
                                                            if ($totalCount > 0) {
                                                                $sno = 1;
                                                                for ($i = 0; $i < $totalCount; $i++) {
                                                                    if ($nye_disc_list[$i]['status'] == 1) {
                                                                        $status = "Active";
                                                                        $statusTitle = "Inactivate this discount";
                                                                    } else {
                                                                        $status = "Inactive";
                                                                        $statusTitle = "Activate this discount";
                                                                    }
                                                                    ?>
                                                                    <tr>
                                                                        <td class="helpBod"><?php echo $sno; ?></td>
                                                                        <!--<td class="helpBod"><?php echo $nye_disc_list[$i]['eventid']; ?></td>-->
                                                                        <td class="helpBod"><?php echo $nye_disc_list[$i]['title']; ?></td>
                                                                        <td class="helpBod"><?php echo $nye_disc_list[$i]['promocode']; ?></td>
                                                                        <td class="helpBod">
                                                               <?php echo isset($nye_disc_list[$i]['name'])?$nye_disc_list[$i]['name']:"All cities"; ?>
                                                                        </td>
                                                                        <td class="helpBod"><?php echo date("d M Y, h:i A", strtotime($nye_disc_list[$i]['cts'])); ?></td>
                                                                        <td class="helpBod"><span id="statusRow<?php echo $nye_disc_list[$i]['id']; ?>"><input type="checkbox"  <?php if ($nye_disc_list[$i]['status'] == 1) {
                                                                        echo ' checked="checked"';
                                                                    } ?>  onclick="changeStatus('<?php echo $nye_disc_list[$i]['id']; ?>', '<?php echo $nye_disc_list[$i]['status']; ?>')" title="<?php echo $statusTitle; ?>" />&nbsp;(<b  style="color:#09C; font-weight:bold;" ><?php echo $status; ?></b>)</span></td>
                                                                        <td class="helpBod">
                                                                            <a href="nye_discounts_edit.php?edit=<?php echo $nye_disc_list[$i]['id']; ?>" title="Edit this record"><img src="images/edit.jpg" /></a>
                                                                            &nbsp;&nbsp;
                                                                            <a style="cursor:pointer" onClick="Remove('<?php echo $nye_disc_list[$i]['id']; ?>');" title="Delete this record"><img src="images/delet.jpg" /></a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                    $sno++;
                                                                }
                                                            } else {
                                                                ?><tr><td colspan="6"><b style="color:#933; font-size:14px; font-weight:bold">Sorry, No records found.</b></tr><?php
                                                        }
                                                            ?>
                                                        </table></td>
                                                </tr>

                                            </table>
                                        </form>
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