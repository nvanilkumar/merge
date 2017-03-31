<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents -Menu Content Management</title>
        <link href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <script language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/sortable.js"></script>
                <script language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.js"></script>
                <link rel="stylesheet" type="text/css" media="all" href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/CalendarControl.css" />
                <script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/ctrl/includes/javascripts/CalendarControl.js"></script>
                <script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/jquery.min.js"></script>
                <script language="javascript">
                    function SEdt_validate()
                    {
                        var strtdt = document.frmEofMonth.txtSDt.value;
                        var enddt = document.frmEofMonth.txtEDt.value;
//                        if (strtdt == '')
//                        {
//                            alert('Please select Start Date');
//                            document.frmEofMonth.txtSDt.focus();
//                            return false;
//                        }
//                        else if (enddt == '')
//                        {
//                            alert('Please select End Date');
//                            document.frmEofMonth.txtEDt.focus();
//                            return false;
//                        }
//                        else //if(strtdt != '' && enddt != '')
//                        {
                        var startdate = strtdt.split('/');
                        var startdatecon = startdate[2] + '/' + startdate[1] + '/' + startdate[0];

                        var enddate = enddt.split('/');
                        var enddatecon = enddate[2] + '/' + enddate[1] + '/' + enddate[0];

                        if (Date.parse(enddatecon) < Date.parse(startdatecon))
                        {
                            alert('End Date must be greater then Start Date.');
                            document.frmEofMonth.txtEDt.focus();
                            return false;
                        }
                        //}
                    }
                    $(document).ready(function () {
                        $('.eventType').change(function () {
                            if ($(this).val() == 'all') {
                                $('#enddateTD').show();
                            } else {
                                $('#enddateTD').hide();
                            }
                        });
                    });

                </script>

                <link href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/jq.css" rel="stylesheet"/>
                <!-- Pick a theme, load the plugin & initialize plugin -->
                <link href="css/theme.default.css" rel="stylesheet"/>
                <script src="css/jquery.tablesorter.min.js"></script>
                <script src="css/jquery.tablesorter.widgets.min.js"></script>
                <script>
                    $(function () {
                        $('table').tablesorter({
                            widgets: ['zebra', 'columns'],
                            usNumberFormat: false,
                            sortReset: true,
                            sortRestart: true
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
                                <form action="" method="post" name="frmEofMonth">
                                    <table width="60%" align="center" class="tblcont">
                                        <tr>
                                            <td width="33%" align="left" valign="middle">Start Date:&nbsp;
                                                <input type="text" name="txtSDt" id="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
                                            <td <?php if ($eventType != 'all') { ?>style="display:none;" <?php } ?> id="enddateTD" width="28%" align="left" valign="middle">End Date:&nbsp;
                                                <input type="text" name="txtEDt" id="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>

                                        </tr>
                                        <tr>
                                            <td width="33%" align="left" valign="middle">Email Id or user Id: &nbsp;
                                                <input type="text" name="userData" id="userData" value="<?php echo $userData; ?>" size="28" /></td>
                                            <td width="33%" align="left" valign="middle">Event Type: &nbsp;
                                                <input type="radio" value="all" class="eventType" name="eventType" <?php if ($eventType == 'all') {
                                    echo "checked='checked'";
                                } ?> />All <input class="eventType" type="radio" value="current" name="eventType" <?php if ($eventType == 'current') {
                                    echo "checked='checked'";
                                } ?>/>Current <input class="eventType" type="radio" value="past" name="eventType" <?php if ($eventType == 'past') {
                                    echo "checked='checked'";
                                } ?>/>Past </td>
                                        </tr>

                                        <tr><td width="25%" align="center" colspan="2" valign="middle"><input type="submit" name="submit" value="Show Report" onclick="return SEdt_validate();" /><input type="hidden" name="formSubmit" value="1" /></td></tr>
                                    </table>
                                </form>
                                <div  id="divMainPage" style="margin-left: 10px; margin-right:5px">


                                    <!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
                                    <script language="javascript">
                                        document.getElementById('ans22').style.display = 'block';
                                    </script>
                                    <table align="center" class="tablesorter" style="margin: 10px 0 -1px; width:90%"  border='1' cellpadding='0' cellspacing='0' >
                                        <thead>
<?php if (isset($_SESSION['nodata'])) {
    unset($_SESSION['nodata']); ?>
                                                <div style="color:green;">No data found.</div>
<?php } ?>
                                            <tr bgcolor='#94D2F3'>
                                                <td class='tblinner' valign='middle' width='5%' align='center'>Sr. No.</td>
                                                <td class='tblinner' valign='middle' width='5%' align='center'>User Id</td>
                                                <td class='tblinner' valign='middle' width='25%' align='center'>Event Organizer Details</td>
                                                <td class='tblinner' valign='middle' width='5%' align='center'>Event City's</td>
                                                <td class='tblinner' valign='middle' width='5%' align='center'>Event category's</td>
                                                <td class='tblinner' valign='middle' width='10%' align='center'>No. of Events</td>
                                                <td class='tblinner' valign='middle' width='40%'>Event Name & ID</td>
                                                <td class='tblinner' valign='middle' width='10%' align='center'>Ticket Quantity</td>
                                                <td class='tblinner' valign='middle' width='10%' align='center'>Amount</td>

                                            </tr>
                                        </thead>

<?php
$cnt = $tqty = 0;
//  $tnoEvents = 0;
// $TotalAmountcard = NULL;
foreach ($finalArr as $userId => $value) {   // iterating grouped array for displaying data
    ?>
                                            <tr>
                                                <td class='tblinner' valign='middle'  align='center' ><font color='#000000'><?php echo ++$cnt; ?></font></td>
                                                <td class='tblinner' valign='middle'  align='center' ><font color='#000000'><?php echo $userId; ?></font></td>
                                                <td class='tblinner' valign='middle'  align='left'><font color='#000000'><?php echo $value['userData']['company'] . "<br/>(" . $value['userData']['email'] . "," . $value['userData']['cityname'] . ")"; ?></font></td>
                                                <td class='tblinner' valign='middle' width='5%' align='center'><?php echo implode(',<br/>', $value['eventcities']); ?></td>
                                                <td class='tblinner' valign='middle' width='5%' align='center'><?php echo implode(',<br/>', $value['eventcategories']); ?></td>
                                                <td class='tblinner' valign='middle'  align='center'><font color='#000000'><?php echo count($value['eventData']); ?></font></td>

                                                <td class='tblinner' valign='middle'><font color='#000000'>

                                                        <?php
                                                        foreach ($value['eventData'] as $eventId => $eventInfo) {
                                                            echo "=> " . $eventInfo['title'] . "(<a href='" . _HTTP_SITE_ROOT . "/event/" . $eventInfo['url'] . "' target='_blank'>" . $eventId . "</a>) <br />";
                                                        }
                                                        ?>
                                                    </font></td>
                                                <td class='tblinner' valign='middle' align='center'><font color='#000000'><?php echo isset($value['totalqty']) ? $value['totalqty'] : 0; ?></font></td>
                                                <td class='tblinner' valign='middle' align='center'><font color='#000000'><?php
                                                        if (isset($value['totalamount'])) {
                                                            $str = '';
                                                            foreach ($value['totalamount'] as $code => $total) {
                                                                $str.=$code . ' ' . round($total) . '<br/>';
                                                                $TotalAmountcard[$code] += $total;
                                                            } echo $str;
                                                        } else {
                                                            echo '0';
                                                        }
                                                        ?></font></td>
                                            </tr>
    <?php
    //$tqty +=$value['totalqty'];
    // $tnoEvents+=count($value['eventData']);
}
?>



<!--                                        <tr>


                                            <tr bgcolor="#FFFFFF">
                                                <td colspan="5" style="line-height:30px; padding:5px;"><strong>Total :</strong></td>
                                                <td  align='center'><font color='#000000'><?php //echo $tnoEvents; ?></font></td>
                                                <td>&nbsp;</td>
                                                <td  align='center'><font color='#000000'><?php //echo $tqty; ?></font></td>
                                                <td align='center'><font color='#000000'> <?php
//                                                    if(count($TotalAmountcard)>0){
//                                                        $str = '';
//                                                        foreach ($TotalAmountcard as $code => $total) {
//                                                            $str.=$code . ' ' . $total . '<br/>';
//                                                        } echo $str;
//                                                    }else{
//                                                        echo '0';
//                                                    }
                                        ?></font></td>
                                            </tr>
                                        </tr>-->

                                    </table>
                                    <br /><br /><br />
                                    <!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->



                                </div>
                            </td>
                        </tr>

                    </table>
                    </div>
                </body>
                </html>
