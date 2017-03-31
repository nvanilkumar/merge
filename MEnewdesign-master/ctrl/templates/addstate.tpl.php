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


                                    <!-------------------------------CONTENT PAGE STARTS HERE--------------------------------------------------------------->
                                    <script type="text/javascript" language="javascript">
                                        function validate_state() {

                                            if (document.add_state.country.value == "0") {
                                                alert("Please Select Country Name");
                                                document.add_state.country.focus();
                                                return false;
                                            }

                                            if (document.add_state.state.value == '') {
                                                alert("Please Enter State Name");
                                                document.add_state.state.focus();
                                                return false;
                                            }

                                            return true;
                                        }
                                    </script>
<script language="javascript">
  	document.getElementById('ans2').style.display='block';
</script>
                                    <form action="" method="post" name="add_state" onSubmit="return validate_state();">
                                        <table width="50%" border="0" cellpadding="3" cellspacing="3">
                                            <tr>
                                                <td colspan="2"><strong>Add State</strong></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>Select Country : </td>
                                                <td><label>
                                                        <select name="country">
                                                            <option value="0" selected="selected">--SELECT--</option>
                                                            <?php
                                                            for ($i = 0; $i < count($CountryList); $i++) {
                                                                ?>
                                                                <option value="<?php echo $CountryList[$i]['id']; ?>"><?php echo $CountryList[$i]['name']; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </label></td>
                                            </tr>
                                            <tr>
                                                <td>State Name : </td>
                                                <td><label>
                                                        <input type="text" name="state">
                                                    </label></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td><label>
                                                        <input type="submit" name="Submit" value="Add">
                                                    </label></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td><?php echo $msgCountryStateExist; ?></td>
                                            </tr>
                                        </table>
                                    </form>
                                    <!-------------------------------CONTENT PAGE ENDS HERE--------------------------------------------------------------->



                                </div>
                            </td>
                        </tr>
                    </table>
                    </div>	
                </body>
                </html>