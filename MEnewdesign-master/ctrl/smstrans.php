<?php
session_start();
include 'loginchk.php';

 $TransId = ($_GET['TransId']) ? $_GET['TransId'] : $_POST['TransId'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>MeraEvents - Transaction Comments</title>
        <link type="text/css" rel="stylesheet" href="<?= _HTTP_CF_ROOT; ?>/css/me_customfields.min.css.gz" />
<?php //include 'includes/include_js_css.php'; ?>
        <SCRIPT LANGUAGE="JavaScript">

        <!-- 
            function textCounter(field, countfield, maxlimit) {
                if (field.value.length > maxlimit) // if too long...trim it!
                    field.value = field.value.substring(0, maxlimit);
                // otherwise, update 'characters left' counter
                else
                    countfield.value = maxlimit - field.value.length;
            }
            function isValid() {
                var mess = document.getElementById('message').value;
                var status = false;
                if (mess.trim().length > 0) {
                    status = true;
                } else {
                    alert("Enter valid message");
                }
                return status;
            }
        //  -->
        </script>

    </head>
    <body bgcolor="#F2F7FB;">
        <div style="background-color:#F2F7FB; margin:0px; padding:0px;">
            <div align="right" style="width:10px;height:10px; margin-bottom:20px; float:right;">
                <a class="lbAction" rel="deactivate" href="#" style="padding:5px; float:right;"><img src="<?= _HTTP_SITE_ROOT ?>/lightbox/close_button.gif" border="0" style="margin-bottom:10px;" /></a><br />
            </div>
            <div>

                <div>
                    <div style="padding-left:10px;">
                        <h1>Send Sms</h1>
                    </div>
                </div>

            </div>
            <div >

                <!-- Page Info -->
                <div style="background-color:#F2F7FB;">
                    <form name="smsmsg" action="CheckReg.php?email=<?= $_REQUEST[email]; ?>&recptno=<?= $_REQUEST[recptno]; ?>&transid=<?= $_REQUEST[transid]; ?>"  id="smsmsg" method="post" style="margin:0px; padding:0px;" onsubmit="return isValid();">
                        <input type="hidden" name="TransId" value="<?= $TransId; ?>" />
                        <table width="800" cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="20" height="40" align="left">&nbsp;</td><td width="101" height="30" align="left"></td><td  >Max Length 160 chars</td>
                            </tr>
                            <tr>
                                <td width="20" height="30" align="left">&nbsp;</td>
                                <td width="101" height="30" align="left"><b>Message :</b></td>
                                <td width="677" height="30" align="left"><textarea name="message" rows="10"  cols="50" id="message" onKeyDown="textCounter(this.form.message, this.form.remLen, 160);" onKeyUp="textCounter(this.form.message, this.form.remLen, 150);"></textarea><br/><br/>
                                    <input readonly type=text name=remLen size=5 maxlength=3 value="160"> characters left
                                </td>
                            </tr>




                            <tr>
                                <td height="35" colspan="3" align="center"><div>
                                        <div>
                                            <div align="center">

                                                <input type="Submit" name="SendSms" value="Send Sms" id="signin_submit" />

                                            </div>
                                        </div>
                                    </div></td>
                            </tr>

                        </table></tr>
                        </table>
                    </form>
                </div>

<?php include 'footer_js_css.php'; ?>
            </div>
        </div>
    </body>
</html>
