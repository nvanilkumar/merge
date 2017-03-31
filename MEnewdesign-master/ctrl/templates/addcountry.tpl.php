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


                                    <!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
                                    <script type="text/javascript" language="javascript">
                                        function validate_country() {
                                            if (document.add_country.country_name.value == '') {
                                                alert("Please enter country name.");
                                                document.add_country.country_name.focus();
                                                return false;
                                            }                                            
                                            if (document.add_country.country_short_name.value == '') {
                                                alert("Please enter Country short name");
                                                document.add_country.country_short_name.focus();
                                                return false;
                                            }
											if (document.add_country.country_code.value == '') {
                                                alert("Please enter Country code");
                                                document.add_country.country_code.focus();
                                                return false;
                                            }
                                            if (document.add_country.country_order.value != '') {
                                                if (isNaN(document.add_country.country_order.value)) {
                                                    alert("Country order should be a numeric");
                                                    document.add_country.country_order.focus();
                                                    return false;
                                                }
                                            }
                                            if (document.add_country.country_order.value <= 0) {
                                                    alert("Country order should accept only positive numbers");
                                                    document.add_country.country_order.focus();
                                                    return false;
                                            }
                                            if (document.add_country.country_timezone.value == '0') {
                                                alert("Please select Country timezone");
                                                document.add_country.country_timezone.focus();
                                                return false;
                                            }
                                            if (document.add_country.country_currency.value == '0') {
                                                alert("Please select Country default currency");
                                                document.add_country.country_currency.focus();
                                                return false;
                                            }
                                            return true;
                                        }
                                    </script>
<script language="javascript">
  	document.getElementById('ans2').style.display='block';
</script>
                                    <form action="" method="post" enctype="multipart/form-data" name="add_country" onsubmit="return validate_country();">
                                        <table width="50%" border="0" cellpadding="3" cellspacing="3">
                                            <tr>
                                                <td colspan="2"><strong>Add Country</strong> </td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>Country Name : </td>
                                                <td><label>
                                                        <input name="country_name" type="text" id="country_name" />
                                                    </label></td>
                                            </tr>
                                             <tr>
                                                    <td>Country Short Name : </td>
                                                    <td><label>
                                                            <input type="text" name="country_short_name" value="" />
                                                        </label></td>
                                                </tr>
												 <tr>
                                                    <td>Country Code : </td>
                                                    <td><label>
                                                            <input type="text" name="country_code" value="" />
                                                        </label></td>
                                                </tr>
                                                <tr>
                                                    <td>Order : </td>
                                                    <td><label>
                                                            <input type="text" name="country_order" value="" />
                                                        </label></td>
                                                </tr>
                                                <tr>
                                                    <td>Logo </td>
                                                    <td><label>
                                                            <input type="file" name="logofile" />
                                                        </label></td>
                                                </tr>             
                                                <tr>
                                                    <td>Timezone : </td>
                                                    <td>
                                                        <label>
                                                            <select name="country_timezone">
                                                                <option value="0">-Select time zone-</option>
                                                                <?php for ($t = 0; $t < count($timezoneList); $t++) { ?>
                                                                    <option  value="<?php echo $timezoneList[$t]['id']; ?>"><?php echo $timezoneList[$t]['zone'] ?></option>
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
                                                                    <option value="<?php echo $currencyList[$t]['id']; ?>" ><?php echo $currencyList[$t]['name'] . "(" . $currencyList[$t]['code'] . ")" ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Featured : </td>
                                                    <td>
                                                        <label>
                                                            <input type="checkbox" name="country_featured" value="1" />
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Default : </td>
                                                    <td>
                                                        <label>
                                                            <input type="checkbox" name="country_default" value="1" />
                                                        </label>
                                                    </td>
                                                </tr>    
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td><label>
                                                        <input type="submit" name="Submit" value="Add" />
                                                    </label></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td><?php echo $MsgCountryExist; ?></td>
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