<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents -Menu Content Management</title>
        <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
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


                                   
                                    <script type="text/javascript" language="javascript">
                                        function validate_country() {
                                            if (document.edit_country.country_name.value == '') {
                                                alert("Please enter Country Name");
                                                document.edit_country.country_name.focus();
                                                return false;
                                            }
                                            if (document.edit_country.country_short_name.value == '') {
                                                alert("Please enter Country short name");
                                                document.edit_country.country_short_name.focus();
                                                return false;
                                            }
											 if (document.edit_country.country_code.value == '') {
                                                alert("Please enter Country Code");
                                                document.edit_country.country_code.focus();
                                                return false;
                                            }
                                            if (document.edit_country.country_order.value != '') {
                                                if (isNaN(document.edit_country.country_order.value)) {
                                                    alert("Country order should be a numeric");
                                                    document.edit_country.country_order.focus();
                                                    return false;
                                                }
                                            }
                                            if (document.add_country.country_order.value <= 0) {
                                                alert("Country order should accept only positive numbers");
                                                document.add_country.country_order.focus();
                                                return false;
                                        }
                                            if (document.edit_country.country_timezone.value == '0') {
                                                alert("Please select Country timezone");
                                                document.edit_country.country_timezone.focus();
                                                return false;
                                            }
                                            if (document.edit_country.country_currency.value == '0') {
                                                alert("Please select Country default currency");
                                                document.edit_country.country_currency.focus();
                                                return false;
                                            }

                                            return true;
                                        }
                                    </script>
<script language="javascript">
  	document.getElementById('ans2').style.display='block';
</script>
                                    <form action="" method="post" name="edit_country" onsubmit="return validate_country();" enctype="multipart/form-data">
                                        <table width="50%" border="0">
                                            <tr>
                                                <td colspan="2"><strong>Edit Country</strong> </td>
                                            </tr>
                                            <tr>
                                                <td><span style="color:#f00"><?= $error_message; ?></span></td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <?php for ($i = 0; $i < count($EditCountry); $i++) { ?> 
                                                <tr>
                                                    <td>Country Name : </td>
                                                    <td><label>
                                                            <input type="text" name="country_name" value="<?= $EditCountry[$i]['name'] ?>" />
                                                            <input type="hidden" name="country_id" value="<?= $CountryId ?>" />
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Country Short Name : </td>
                                                    <td><label>
                                                            <input type="text" name="country_short_name" value="<?= $EditCountry[$i]['shortname'] ?>" />
                                                        </label></td>
                                                </tr>
												 <tr>
                                                    <td>Country Code : </td>
                                                    <td><label>
                                                            <input type="text" name="country_code" value="<?= $EditCountry[$i]['code'] ?>" />
                                                        </label></td>
                                                </tr>
                                                <tr>
                                                    <td>Order : </td>
                                                    <td><label>
                                                            <input type="text" name="country_order" value="<?= $EditCountry[$i]['order'] ?>" />
                                                        </label></td>
                                                </tr>

                                                <tr>
                                                    <td>Logo </td>
                                                    <td><?php if (isset($EditCountry[$i]['logofile']) && !empty($EditCountry[$i]['logofile'])) { ?>
                                                            <img src="<?php echo CONTENT_CLOUD_PATH.$EditCountry[$i]['logofile'] ?>" />
                                                            <input type="hidden" name="fileId" value="<?php echo $EditCountry[$i]['logofileid']?>"/>
                                                        <?php } ?><label>
                                                            <input type="file" name="flagfile" />
                                                        </label></td>
                                                </tr>             
                                                <tr>
                                                    <td>Timezone : </td>
                                                    <td>
                                                        <label>
                                                            <select name="country_timezone">
                                                                <option value="0">-Select time zone-</option>
                                                                <?php for ($t = 0; $t < count($timezoneList); $t++) { ?>
                                                                    <option  value="<?php echo $timezoneList[$t]['id']; ?>" <?php echo $timezoneList[$t]['id'] == $EditCountry[$i]['timezoneid'] ? "selected" : ""; ?>><?php echo $timezoneList[$t]['zone'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Default currency : </td>
                                                    <td>
                                                        <label>
                                                            <select name="country_currency">
                                                                <option value="0">-Select currency-</option>
                                                                <?php for ($t = 0; $t < count($currencyList); $t++) { ?>
                                                                    <option value="<?php echo $currencyList[$t]['id']; ?>"  <?php echo $currencyList[$t]['id'] == $EditCountry[$i]['defaultcurrencyid'] ? "selected" : ""; ?>><?php echo $currencyList[$t]['name'] . "(" . $currencyList[$t]['code'] . ")" ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Status : </td>
                                                    <td>
                                                       
                                                            <input type="radio" name="country_status" value="1" <?php echo $EditCountry[$i]['status'] == 1 ? "checked" : ""; ?> /><label>Active&nbsp;&nbsp;&nbsp;</label><input type="radio" name="country_status" value="0" <?php echo $EditCountry[$i]['status'] == 0 ? "checked" : ""; ?> /><label>Inactive
                                                        </label>
                                                    </td>
                                                </tr> 
                                                <tr>
                                                    <td>Featured : </td>
                                                    <td>
                                                        <label>
                                                            <input type="checkbox" name="country_featured" value="1" <?php echo $EditCountry[$i]['featured'] == 1 ? "checked" : ""; ?> />
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Default : </td>
                                                    <td>
                                                        <label>
                                                            <input type="checkbox" name="country_default" value="1" <?php echo $EditCountry[$i]['default'] == 1 ? "checked" : ""; ?> />
                                                        </label>
                                                    </td>
                                                </tr>                                                
                                            <?php } ?>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td><label>
                                                        <input type="submit" name="Submit" value="Update">
                                                    </label></td>
                                            </tr>
                                        </table>
                                    </form>
                                    <!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->



                                </div>
                            </td>
                        </tr>
                    </table>
                    </div>	
                </body>
                </html>