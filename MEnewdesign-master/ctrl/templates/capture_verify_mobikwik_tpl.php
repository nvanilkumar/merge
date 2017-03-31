<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents -Master Management - Currency Management</title>
        <link href="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <script src="<?php echo  _HTTP_CF_ROOT; ?>/js/jquery.1.7.2.min.js"></script>
                <script type="text/javascript" src="<?php echo _HTTP_CF_ROOT;?>/js/public/jquery.validate.js"></script>
                <script>
                    $(function() {

                        $("#success_tr").hide();
                        //on change the select box value
                        <?php if($_POST['submit_form']){ ?>
                            $("#success_tr").show();    
                        <?php } ?>
                        jQuery.validator.addMethod('filesize', function(value, element, param) {
                            // param = size (en bytes) 
                            // element = element to validate (<input>)
                            // value = value of the element (file name)
                            return this.optional(element) || (element.files[0].size <= param)
                        });

                        // validate contact form on keyup and submit
                        $("#mobikwik_form").validate({
                             
                            rules: {
                                mobikwik_doc: {
                                    required: true,
                                    accept: "xls|xlsx",
                                    filesize: 1048576
                                },
                                transaction_date: {
                                    required: true
                                    
                                }

                            },
                            messages: {
                                mobikwik_doc: {
                                    required: "Please select excel file ...!",
                                    filesize: 'File must be less than 1MB',
                                    accept: 'File must be xls, xlsx'
                                },
                                transaction_date: {
                                    required: "Please select the Deposited date...!"
                                    
                                }

                            },
                            errorPlacement: function(error, element) {
                                error.appendTo(element.next());

                            }

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
                                <div id="divMainPage" style="margin-left: 10px; margin-right:5px">


                                    <div align="center" style="width:100%">



                                        <table width="60%" border="0" cellpadding="3" cellspacing="3">
                                            <tr>
                                                <td align="center" colspan="2" valign="middle" class="headtitle"><strong>Capture to verify</strong> </td>
                                            </tr>


                                            <tr id="success_tr" ><td colspan="2" valign="middle" class="headtitle"><b style="color:#090"> <?php echo $capture_status ?>
                                                   <?php /* $refund_status ."<br/>".
                                                    $refund_date_change_messages */?>
                                                    </b>
                                                
                                                </td></tr> 
                                            <tr>
                                                <td colspan="2">
                                                    <form method="post" action=""  name="mobikwik_form" id="mobikwik_form" enctype="multipart/form-data">
                                                        <fieldset>
                                                            <legend>Details</legend>
                                                            <table>
                                                                <tr><td> Mobikwik document</td> 
                                                                <td> Deposited Date</td> 
                                                                
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="file" name="mobikwik_doc" id="mobikwik_doc" />
                                                                        <span><br/> </span>
                                                                    </td>
                                                                    <td><input type="text" name="transaction_date" id="transaction_date" value="<?php echo $SDt; ?>" onfocus="showCalendarControl(this);"/>
                                                                        <span><br/> </span>
                                                                    </td>

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

                                              

                                                </td>
                                            </tr>
                                        </table>
                                        <div align="center" style="width:100%">&nbsp;</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <script>  $('#ans4').show();</script>
                    </div>	
                            <link rel="stylesheet" type="text/css" media="all" href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
                    <script language="javascript">
                        document.getElementById('ans4').style.display='block';
                    </script>
                </body>
                </html>