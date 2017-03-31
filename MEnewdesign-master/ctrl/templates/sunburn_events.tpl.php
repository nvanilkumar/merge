<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents - Admin Panel - Events of the Month</title>
        <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortable.min.js.gz"></script>	
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.min.js.gz"></script>	
                <script src="<?= _HTTP_SITE_ROOT ?>/js/public/jQuery.js"></script>
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
                                    <!-------------------------------Events of the Month PAGE STARTS HERE--------------------------------------------------------------->
                                    <script language="javascript">
                                        document.getElementById('ans6').style.display = 'block';
                                    </script>
                                    <link rel="stylesheet" type="text/css" media="all" href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/CalendarControl.min.css.gz" />
                                    <script type="text/javascript" language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/includes/javascripts/CalendarControl.min.js.gz"></script>
                                    
                                    <div align="center" style="width:100%">&nbsp;</div>
                                    <div align="center" style="width:100%" class="headtitle">Sunburn Events</div>
                                    <div align="center" style="width:100%">&nbsp;</div>
                                    <form action="" method="post" name="frmEofMonth" enctype="multipart/form-data">
                                        <table width="80%" align="center" class="tblcont">
                                            <tr>
                                                <td align="left" valign="middle">Start Date:&nbsp;<input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
                                                <td align="left" valign="middle">End Date:&nbsp;<input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
                                                <td  align="left" valign="middle"><input type="submit" name="submit" value="Show Events"/></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="middle" colspan="4">Event Name:&nbsp;
                                                    <select name="event_name" id="event_name">
                                                        <option value=''>Select Event Name</option>
                                                        <?php foreach ($event_list as $event_details) { ?>
                                                            <option value="<?= $event_details['id']; ?>" <?php if ($selected_event == $event_details['id']) {
                                                            echo "selected='selected'";
                                                        } ?>><?=$event_details['title']?></option>
<?php } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>




<?php if (count($summery_details_array) > 0) { ?>

                                        <table style="width:100%" align="center">
                                            <tr>
                                                 
                                                <td width="5%" align="left" valign="middle" class="tblcont1">Event Name & Id</td>
                                                <td width="5%" align="left" valign="middle" class="tblcont1">Qty</td>
                                                <td width="5%" align="left" valign="middle" class="tblcont1">ET</td>
                                                <td width="5%" align="left" valign="middle" class="tblcont1">ST</td>
                                                <td width="2%" align="left" valign="middle" class="tblcont1">Discount</td>
                                                <td width="3%" align="left" valign="middle" class="tblcont1">Verified Amount</td>
                                                <td width="3%" align="left" valign="middle" class="tblcont1">Partial Payment </td>
                                                <td width="5%" align="left" valign="middle" class="tblcont1">Amount Total</td>
                                                 
                                            </tr>
                                            <?php
                                            
                                           
                                             foreach($summery_details_array as $details)  {
                                                ?>
                                                <tr>
                                                   
                                                    <td align="left" valign="middle" class="helpBod" height="25"><?=  $details['Title'] ?></td>
                                                    <td align="left" valign="middle" class="helpBod" height="25"><?= $details['Qty'] ?></td>
                                                    <td align="left" valign="middle" class="helpBod" height="25"><?= $details['Taxes'] ?></td>
                                                    
                                                    <td align="left" valign="middle" class="helpBod" height="25"><?=$details['DAmount'] ?></td>
                                                    <td align="left" valign="middle" class="helpBod" height="25"><?= $details['verified_amount'] ?></td>
                                                    <td align="left" valign="middle" class="helpBod" height="25"><?=get_partial_payment($total_partial_payments,$details['event_id']); ?></td>
                                                    <td align="left" valign="middle" class="helpBod" height="25"><?=$details['total_amount'] ?></td>
                                                    
                                                    
                                                </tr>
                                                <?php
 
                                            } //ends for loop
                                            ?>
                                          
                                        </table>
                                        <?php
                                    } //ends if condition
                                    else if (count($SunburnEvents) == 0) {
                                        ?>
                                        <table width="90%" align="center">
                                            <tr>
                                                <td width="100%" align="left" valign="middle">No match record found.</td>
                                            </tr>
                                        </table>
                                        <?php
                                    }
                                    ?></td>
                        </tr>
                    </table>
                    </div>	
                </body>
                </html>
