<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents -Master Management - Currency Management</title>
        <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
        <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script src="<?= _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
        <script>
            $(function() {
                
                $("#success_tr").hide();
                //on change the select box value
                $(".user_status").change(function() {
					var user_id = $(this).attr("id");
                    var user_status_value = $(this).val();
                     
                    var post_data = 'user_status=change&user_status_value='
                            + user_status_value + '&user_id=' + user_id;
                    if (confirm("Are you sure want to change the user status?")) {
                        $.ajax({
                            url: "<?= _HTTP_SITE_ROOT; ?>/ctrl/user_status.php",
                            type: "post",
                            data: post_data,
                            cache: false,
                            async: false,
                            success: function(result) {
                                if (result === 'changed')
                                {
                                    $("#success_tr").show();
                                }
                            }
                        });
                    }else{
                        $(this).val("");
                    }
                });

            });
            function validateForm(f)
            {
                var user_email = f.user_email.value;
                var errCount = 0;
                if (user_email.length == 0 || user_email == null)
                {
                    errCount++;
                    alert("Please enter user name");
                    f.user_email.focus();
                    return false;
                }
                return true;

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
<script language="javascript">
                            document.getElementById('ans2').style.display = 'block';
                        </script>		

                        <div align="center" style="width:100%">



                            <table width="60%" border="0" cellpadding="3" cellspacing="3">
                                <tr>
                                    <td align="center" colspan="2" valign="middle" class="headtitle"><strong>User Status</strong> </td>
                                </tr>


                                <tr id="success_tr" ><td colspan="2" valign="middle" class="headtitle"><b style="color:#090">User Status updated successfully..</b> </td></tr> 

                                <tr>
                                    <td colspan="2">
                                        <form method="post" action="" onsubmit="return validateForm(this)">
                                            <fieldset>
                                                <legend>User Details</legend>
                                                <table>
                                                    <tr><td>User Name / Email Id </td> </tr>
                                                    <tr>
                                                        <td><input type="text" name="user_email" id="user_email" value="<?= @$_POST['user_email'] ?>" /></td>

                                                        <td> 
                                                            <input type="submit" name="submit_form"  value="Submit" /></td>
                                                    </tr>
                                                </table>
                                        </form>
                                        </fieldset>

                                    </td>
                                </tr>


                                <tr><td colspan="2"><br /></td></tr>
                                <tr>
                                    <td colspan="2">

                                        <table width="60%" border="0" cellpadding="3" cellspacing="3">
                                            <tr>
                                                <td  colspan="2" valign="middle" class="headtitle"><strong>Users List</strong> </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2"><table width="100%" class="sortable" >
                                                        <thead>
                                                            <tr>
                                                                <td class="tblcont1">Email </td>
                                                                <td class="tblcont1">Name</td>
                                                                <td class="tblcont1"  >Status </td> 
                                                            </tr></thead>
                                                        <?php
                                                        $flag = 0;
                                                        for ($i = 0; $i < count($user_list); $i++) {
                                                            ?>
                                                            <tr>
                                                                <td class="helpBod"><?php echo $user_list[$i]['email']; ?></td>
                                                                <td class="helpBod"><?= $user_list[$i]['name'] ; ?></td>
                                                                <td class="helpBod">
                                                                    <select name="user_status"  class="user_status" id="<?=$user_list[$i]['id'] ?>">
                                                                        <option value="0"
                                                                                <?= ($user_list[$i]['status'] == "0") ? "selected" : "" ?> >In active </option>
																		<option value="1" 
                                                                                <?= ($user_list[$i]['status'] == "1") ? "selected" : "" ?>>Active </option>
                                                                        <option value="2"
                                                                                <?= ($user_list[$i]['status'] == "2") ? "selected" : "" ?> >Not activated </option>
                                                                    </select>   
                                                                </td>

                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </table></td>
                                            </tr>

                                        </table>

                                    </td>
                                </tr>
                            </table>
                            <div align="center" style="width:100%">&nbsp;</div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        </div>	
    </body>
</html>