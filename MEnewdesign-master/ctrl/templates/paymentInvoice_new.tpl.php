<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents -Master Management - Event Search Management</title>
        <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <script type="text/javascript" language="javascript"  src="<?php echo _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>            
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortable.js"></script>
                <link rel="stylesheet" type="text/css" media="all" href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/CalendarControl.css" />
                <script type="text/javascript" language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/includes/javascripts/CalendarControl.js"></script>
                <script language="javascript">
                    function showListingFee()
                    {
                        if (document.getElementById('Cheque').checked)
                            document.getElementById('listingFee').style.display = '';
                        else
                            document.getElementById('listingFee').style.display = 'none';
                    }
                    function SEdt_validate()
                    {
                        var strtdt = document.frmEofMonth.txtSDt.value;
                        var enddt = document.frmEofMonth.txtEDt.value;
                        if (strtdt == '')
                        {
                            alert('Please select Start Date');
                            document.frmEofMonth.txtSDt.focus();
                            return false;
                        }
                        else if (enddt == '')
                        {
                            alert('Please select End Date');
                            document.frmEofMonth.txtEDt.focus();
                            return false;
                        }
                        else //if(strtdt != '' && enddt != '')
                        {
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
                        }
                    }
                </script>	
                <script language="javascript" type="text/javascript">

                    function val_form()
                    {
                        var EventId = document.getElementById('event_id_custom').value;
                        if (EventId == '')
                        {

                            alert("Please Select Event Title");
                            document.getElementById('event_id_custom').focus();
                            return false;

                        }

                        var strtdt = document.frmEofMonth.txtSDt.value;
                        var enddt = document.frmEofMonth.txtEDt.value;
                        if (strtdt != '' && enddt != '')
                        {
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
                        }


                        var Accno = document.getElementById('Accno').value;
                        var IFCS = document.getElementById('IFCS').value;


                        if (Accno == '' || Accno == null)
                        {
                            alert("Please update the organizer bank details first and comeback here");
                            return false;
                        }

                        if (IFCS == '' || IFCS == null)
                        {
                            alert("Please update the organizer bank details first and comeback here");
                            return false;
                        }


                        document.frmEofMonth.submit();

                    }

                    function loadper(val)
                    {
                        var eventid = val;
                        if (eventid.length == 0)
                        {
                            alert("Please enter a Event Id");
                            document.getElementById('event_id_custom').focus();
                            return false;
                        } else if (isNaN(eventid) || eventid <= 0) {
                            alert("Please enter valid Event Id");
                            document.getElementById('event_id_custom').focus();
                            return false;
                        } else {
                            $.get('includes/ajaxSeoTags.php', {eventIDChk: 0, eventid: eventid}, function (data) {
                                if (data == "error")
                                {
                                    alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
                                    document.getElementById('event_id_custom').focus();
                                    return false;

                                } else {
                                    window.location = "paymentInvoice_new.php?EventId=" + eventid;
                                    return false;
                                }
                            });
                        }
                    }
                    
                    function generate_details() {
                        var eventid = document.getElementById('event_id_custom').value;
                        if (eventid.length == 0)
                        {
                            alert("Please enter a Event Id");
                            document.getElementById('event_id_custom').focus();
                            return false;
                        } else if (isNaN(eventid) || eventid <= 0) {
                            alert("Please enter valid Event Id");
                            document.getElementById('event_id_custom').focus();
                            return false;
                        } else {
                            $.get('includes/ajaxSeoTags.php', {eventIDChk: 0, eventid: eventid}, function (data) {
                                if (data == "error")
                                {
                                    alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
                                    document.getElementById('event_id_custom').focus();
                                    return false;

                                } else {
                                    loadper(eventid);
                                }
                            });
                        }

                    }

                    function add_bank_details() {
                        //alert('');return false;
                        var AccName = document.getElementById('AccName').value;
                        var Accno = document.getElementById('Accno').value;
                        var Acctype = document.getElementById('Acctype').value;
                        var BnkName = document.getElementById('BnkName').value;
                        var BnkBranch = document.getElementById('BnkBranch').value;
                        var IFCS = document.getElementById('IFCS').value;


                        var EventId = document.getElementById('EventId').value;
                        //var Comm = document.getElementById('Comm').value;
                        var event_id_custom = document.getElementById('event_id_custom').value;
                        if (EventId == '')
                        {
                            if (event_id_custom == '') {
                                alert("Please Select Event");
                                document.getElementById('EventId').focus();
                                return false;
                            }
                        }

                        if (AccName == '') {
                            alert("Please Enter Account Name");
                            return false;
                        }
                        else if (Accno == '') {
                            alert("Please Enter Account Number");
                            return false;
                        }
                        else if (Acctype == '') {
                            alert("Please Enter Account Type");
                            return false;
                        }
                        else if (BnkName == '') {
                            alert("Please Enter Bank Name");
                            return false;
                        }
                        else if (BnkBranch == '') {
                            alert("Please Enter Branch");
                            return false;
                        }
                        else if (IFCS == '') {
                            alert("Please Enter IFCS Number");
                            return false;
                        } else {
                            return true;
                        }
                        return false;
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
                                <div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
                                    <!-------------------------------DISPLAY ALL EVENT PAGE STARTS HERE--------------------------------------------------------------->
                                    <script language="javascript">
                                        document.getElementById('ans6').style.display = 'block';
                                    </script>
                                    <link rel="stylesheet" type="text/css" media="all" href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/pagi_sort.min.css.gz" />
                                    <script type="text/javascript" language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/includes/javascripts/sortpagi.min.js.gz"></script>
                                    <div align="center" style="width:100%">
                                        <?php
                                        if (isset($_SESSION['PaymentAdviceMailSent'])) {
                                            ?><p style="color:#090; font-size:14px">Mail has been sent...</p><?php
                                            unset($_SESSION['PaymentAdviceMailSent']);
                                        } elseif (isset($_SESSION['BankDetailsAdded'])) {
                                            ?><p style="color:#090; font-size:14px">Bank details <?php echo ($_SESSION['bank_details_action'] == 'Add') ? 'Added' : 'Updated'; ?> successfully</p><?php
                                            unset($_SESSION['BankDetailsAdded']);
                                            unset($_SESSION['bank_details_action']);
                                        }
                                        ?>
                                        <form action="" method="post" name="frmEofMonth" id="frmEofMonth" onsubmit="return val_form();">
                                            <table width="100%" >
                                                <input type="hidden" name="eventOverAllPerc" value="<?php echo $perc; ?>"  />
                                                <tr><td colspan="2"><br /></td></tr>
<?php
if (count($BankQueryRES) > 0 && strlen($BankQueryRES[0]['title']) > 0) {
    ?>
                                                    <tr>
                                                        <td  align="left">Event Title</td><td><b><?php echo $BankQueryRES[0]['title']; ?></b></td>
                                                    </tr>
    <?php
}
?>
                                                <tr style="display:none;" id="listingFee">
                                                    <td  align="left">Listing Fee</td><td><input type="text" name="feeAmt" value="1124" id="feeAmt" size="40" disabled="disabled"/><br/><span id="listingInfo" style="color:red;font-style: italic;">* including service tax 14%</span></td>
                                                </tr>

                                                <tr>
                                                    <td  align="left"><strong>Event Id</strong></td><td><input type="text" name="event_id_custom" id="event_id_custom" size="40" value="<?= $_REQUEST[EventId]; ?>" onchange="loadper(this.value);" />
                                                        <input type="button" value="View Bank Details" id="event_generate" onclick="return generate_details();"/></td>
                                                </tr>

                                                <tr>
                                                    <td  align="left" colspan="2"><table width="100%" border="0" cellspacing="2" cellpadding="2">
                                                            <tr>
                                                                <td>Apply Zero (overall):<input type="checkbox" name="applyzero" id="applyzero" value="1"/> </td>
                                                                <td>Include Offline Tr. :<input type="checkbox" name="offline" id="offline" value="1"/> </td>
																<td>Show Extra Charge:<input type="checkbox" name="show_extra" id="show_extra" value="1"/> </td>
                                                                <td>Exclude Spot Registration :<input type="checkbox" name="exclude_spot" id="exclude_spot" value="1"/> </td>
                                                                <td>Listing Fee :<input type="checkbox" name="Cheque" id="Cheque" value="1" onclick="showListingFee();"/></td>
                                                                <!--td>COD :<input type="checkbox" name="COD" id="COD" value="1"/></td-->
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr><td colspan="2">
                                                        <table width="50%">
                                                            <tr>
                                                                <td width="35%" align="left" valign="middle">Start Date:&nbsp;<input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
                                                                <td width="35%" align="left" valign="middle">End Date:&nbsp;<input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);"  /></td>

                                                            </tr></table>
                                                    </td></tr>
                                                <tr>
                                                    <td  align="left">Cheque No</td><td><input type="text" name="Chqno" id="Chqno" size="40" /></td>
                                                </tr>
                                                <tr>
                                                    <td align="left">Cheque Date</td><td><input type="text" name="Chqdt" id="Chqdt" size="40" /></td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2" align="left"><table width="100%" border="0" cellspacing="2" cellpadding="2">
                                                            <tr height="20">
                                                                <td height="25" colspan="2"><strong>Organizer Account   Details:</strong></td>
                                                            </tr>
                                                            <tr height="20">
                                                                <td width="22%" height="25"> Beneficiary  Name</td>
                                                                <td width="78%" height="25"><input type="text" name="AccName" value="<?= $BankQueryRES[0][accountname]; ?>" id="AccName" size="40"  disabled="disabled"/></td>
                                                            </tr>
                                                            <tr height="20">
                                                                <td height="25"> Beneficiary Ac/No</td>
                                                                <td height="25"><input type="text" name="Accno" value="<?= $BankQueryRES[0][accountnumber]; ?>" id="Accno" size="40"  disabled="disabled"/></td>
                                                            </tr>
                                                            <tr height="20">
                                                                <td height="25"> Account Type(Savings/Current) </td>
                                                                <td height="25"><input type="text" name="Acctype" value="<?= $BankQueryRES[0][accounttype]; ?>" id="Acctype" size="40"  disabled="disabled"/></td>
                                                            </tr>
                                                            <tr height="20">
                                                                <td height="25">Bank Name<strong>&nbsp;</strong></td>
                                                                <td height="25"><input type="text" name="BnkName" value="<?= $BankQueryRES[0][bankname]; ?>" id="BnkName" size="40"  disabled="disabled"/></td>
                                                            </tr>
                                                            <tr height="20">
                                                                <td height="25">Bank    Branch &amp; Address</td>
                                                                <td height="25"><input type="text" name="BnkBranch" value="<?= $BankQueryRES[0][branch]; ?>" id="BnkBranch" size="40"  disabled="disabled" /></td>
                                                            </tr>
                                                            <tr height="20">
                                                                <td height="25">IFSC Code</td>
                                                                <td height="25"><input type="text" name="IFCS" id="IFCS" value="<?= $BankQueryRES[0][ifsccode]; ?>" size="40"  disabled="disabled" /></td>
                                                            </tr>
                                                            <!--tr height="20">
                                                              <td height="25">&nbsp;</td>
                                                              <td height="25"><input type="submit" name="add_update_bankdetails" value="Add/UpdateBankDetails" onclick="return add_bank_details();"/> </td>
                                                            </tr-->
                                                        </table></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" align="left">&nbsp;</td>
                                                </tr>
                                                <td colspan="2" align="left">
                                                    <input type="hidden"  name="export"  value="ExportPaymentAdvice"  />
                                                    <input type="hidden"  name="download"  value="EmailPaymentAdvice"  />
                                                    <input type="button" name="export"  value="ExportPaymentAdvice" onclick="return val_form();" /> &nbsp; <input type="button" name="download"  value="EmailPaymentAdvice"  onclick="return val_form();" /></td>
                                                </tr>
                                            </table>

                                        </form>

                                    </div>
                                    <!-------------------------------DISPLAY ALL EVENT PAGE ENDS HERE--------------------------------------------------------------->
                                </div>
                            </td>
                        </tr>

                    </table>
                    </div>	
                </body>
                </html>
                <script type="text/javascript" src="<?= _HTTP_SITE_ROOT ?>/lightbox/prototype.min.js.gz"></script>
                <script type="text/javascript" src="<?= _HTTP_SITE_ROOT ?>/lightbox/lightbox.min.js.gz"></script>
                <link type="text/css" rel="stylesheet" href="<?= _HTTP_SITE_ROOT ?>/lightbox/lightbox.min.css.gz" media="screen,projection" />