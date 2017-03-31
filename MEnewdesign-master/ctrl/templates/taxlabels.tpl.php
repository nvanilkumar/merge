<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents -Master Management - Currency Management</title>
        <link href="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
        <link href="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/sortable.js"></script>	
        <script language="javascript" src="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.js"></script>
        <script src="<?php echo  _HTTP_CF_ROOT; ?>/js/jquery.1.7.2.min.js"></script>
        <script>
            function validateForm(f)
            {
               var taxLabel = f.taxLabel.value;
                if (taxLabel.length == 0 || taxLabel == null) {
                    alert("Please enter Tax label");
                    f.taxLabel.focus();
                    return false;
                }
                else if (!validateLabel(taxLabel)) {
                    alert("Please enter valid Tax value");
                    f.taxVal.focus();
                    return false;
                }
                return true;
            }
            //regex validation
            function validateLabel(val)
            {
                var regex = /^[a-zA-Z ]{2,30}$/;
                if (regex.test(val)) {
                    return true;
                }
                else {
                    return false;
                }
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
                    <div id="divMainPage" style="margin-left: 10px; margin-right:5px">
                        <!-------------------------------TAX LABEL LIST PAGE STARTS HERE--------------------------------------------------------------->
                        <script language="javascript">
                            document.getElementById('anstax').style.display = 'block';
                        </script>
                        <div align="center" style="width:100%">
                            <table width="60%" border="0" cellpadding="3" cellspacing="3">
                                <tr>
                                    <td align="center" colspan="2" valign="middle" class="headtitle"><strong>Tax Labels</strong> </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <fieldset style="width:45%;">
                                            <legend>Add Tax Label</legend>
                                            <form method="post" action="" onsubmit="return validateForm(this)">
                                                <table>
                                                    <tr>
                                                        <td>Tax Label</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="taxLabel" id="taxLabel" value="<?php echo (isset($_REQUEST['edit']) && $_REQUEST['edit']!='')?$ediRes[0]['label']:"";?>" />
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="addTaxForm"  value="1" />
                                                            <?php 
                                                            if  ((isset($_REQUEST['edit']) && $_REQUEST['edit']!='')) {
                                                            ?>
                                                            <input type="hidden" name="taxLabelId" value="<?php echo $_REQUEST['edit']; ?>" />
                                                            <?php }?>
                                                            <input type="submit" name="currFrmSub"  value="<?php echo (isset($_REQUEST['edit']) && $_REQUEST['edit']!='')?"Edit":"Add"; ?>" />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </form>
                                        </fieldset>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <table width="60%" border="0" cellpadding="5" cellspacing="5">
                                            <tr>
                                                <td  colspan="2" valign="middle" class="headtitle"><strong>Service Tax</strong> </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <table width="100%" class="sortable" >
                                                        <thead>
                                                            <tr>
                                                                <td class="tblcont1">Sno</td>
                                                                <td class="tblcont1">Label</td>
                                                                <td class="tblcont1" ts_nosort="ts_nosort">Options</td>
                                                            </tr>
                                                        </thead>
                                                        <?php
                                                        if ($TDataCount > 0) {
                                                            $stsno = 1;
                                                            for ($st = 0; $st < $TDataCount; $st++) {
                                                                ?>
                                                                <tr>
                                                                    <td class="helpBod"><?php echo $stsno; ?></td>
                                                                    <td class="helpBod"><?php echo $TData[$st]['label']; ?></td>
                                                                    <td class="helpBod"><a href="?edit=<?php echo $TData[$st]['id'];?>">Edit</a></td>							
                                                                </tr>
                                                                <?php
                                                                $stsno++;
                                                            }
                                                        } else {
                                                            ?> <tr><td colspan="4"><b style="color:#C30">Sorry, No reocrds found.</b></td></tr><?php
                                                        }
                                                        ?>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>





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