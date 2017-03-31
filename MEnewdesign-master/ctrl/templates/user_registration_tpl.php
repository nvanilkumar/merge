<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents -Master Management - Currency Management</title>
        <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <script src="<?= _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
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
                        $("#user_form").validate({
                             
                            rules: {
                                ebs_doc: {
                                    required: true,
                                    accept: "xls|xlsx",
                                    filesize: 1048576
                                } 
                              

                            },
                            messages: {
                                ebs_doc: {
                                    required: "Please select excel file ...!",
                                    filesize: 'File must be less than 1MB',
                                    accept: 'File must be xls, xlsx'
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
                                                <td align="center" colspan="2" valign="middle" class="headtitle"><strong>User Excel Form</strong> </td>
                                            </tr>


                                            <tr id="success_tr" ><td colspan="2" valign="middle" class="headtitle"><b style="color:#090"> <?=$status_message ?><BR/>
                                                   
                                                    </b>
                                                
                                                </td></tr> 

                                            <tr>
                                                <td colspan="2">
                                                    <form method="post" action=""  name="user_form" id="user_form" enctype="multipart/form-data">
                                                        <fieldset>
                                                            <legend>Details</legend>
                                                            <table>
                                                                <tr><td> Users document</td> 
                                                                
                                                                
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="file" name="ebs_doc" id="ebs_doc" />
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
                    </div>	
                            <link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.min.js.gz"></script>
                </body>
                </html>