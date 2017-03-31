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
                <script language="javascript">

                    function SEdt_validate()
                    {
                        var strtdt = document.frmEofMonth.txtSDt.value;
                        var enddt = document.frmEofMonth.txtEDt.value;
                        if (strtdt != '')
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


                    function ClickHereToPrintpass()
                    {
                        //alert("PrintTable");
                        var DocumentContainer = document.getElementById('PrintTable');
                        var WindowObject = window.open('PrintWindow', null, 'width=900,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes');
                        WindowObject.document.writeln(DocumentContainer.innerHTML);
                        WindowObject.document.close();
                        WindowObject.focus();
                        WindowObject.print();
                        WindowObject.close();
                    }

                </script>
                </head>	
                <body style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
                    <?php include('templates/header.tpl.php'); ?>				
                    </div>
                    <table width="103%" cellpadding="0" cellspacing="0" style="width:100%; height:495px;">
                        <tr>
                            <td width="150" style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
                                <?php include('templates/left.tpl.php'); ?>	</td>
                            <td width="848" style="vertical-align:top">
                                <form action="paymentsummary.php" method="post" name="frmEofMonth">
                                    <table width="70%" align="center" class="tblcont">
                                        <tr><td align="center" colspan="2">Payment Summary Report</td></tr>
                                        <tr>
                                            <td width="44%" align="left" valign="middle">Trans Start Date:&nbsp;
                                                <input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
                                            <td width="56%" align="left" valign="middle">Trans End Date:&nbsp;
                                                <input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>


                                            <tr>

<!--                                                <tr><td colspan="2">Select an Event <select name="EventId" id="EventId" >
                                                            <option value="">Select Event</option>
                                                            <?php
                                                            $TotalEventQueryRES = count($EventQueryRES);

                                                            for ($i = 0; $i < $TotalEventQueryRES; $i++) {
                                                                ?>
                                                                <option value="<?php echo $EventQueryRES[$i]['eventid']; ?>" <?php if ($EventQueryRES[$i]['eventid'] == $EventId) { ?> selected="selected" <?php } ?>><?php echo $EventQueryRES[$i]['Details']; ?></option>
                                                            <?php } ?>
                                                        </select></td></tr>-->
                                                <tr><td>Event Id:&nbsp;<input type="text" name="eventIdSrch" id="eventIdSrch" value="<?php echo $EventId; ?>"></td>
                                                    <td>Include Offline Tr:&nbsp;<input type="checkbox" name="offTrans" id="offTrans" <?php if (isset($_REQUEST['offTrans'])) {
                                                                echo 'checked';
                                                            } ?>></td></tr>
                                                <tr><td colspan="2"></td></tr>
                                                <tr><td>Select Status <select name="Status" id="Status" >
                                                            <option value="">Status</option>	

                                                            <option value="Pending" <?php if ($_REQUEST['Status'] == "Pending") { ?> selected="selected" <?php } ?>>Pending</option>
                                                            <option value="Done" <?php if ($_REQUEST['Status'] == "Done") { ?> selected="selected" <?php } ?>>Done</option>

                                                        </select>
                                                        &nbsp; <input type="checkbox" name="compeve" id="compeve" <?php if ($_REQUEST[compeve] == 1) { ?> checked="checked" <?php } ?> value="1" /> Completed
                                                    </td></tr>
                                                <tr> <td colspan="2" style="padding:10px;" align="center" valign="middle"><input type="submit" name="submit" value="Show Report" onclick="return SEdt_validate();" /></td></tr>
                                                </table>
                                                </form>
                                                <div  id="divMainPage" style="margin-left: 10px; margin-right:5px">


                                                    <!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
                                                    <script language="javascript">
                                                        document.getElementById('ans4').style.display = 'block';
                                                    </script>
                                                    <?php
                                                    $data = "<div id='PrintTable'>
<table width='100%' border='1' cellpadding='0' cellspacing='0'  >
			<thead>
            <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='3%' align='center'>Sr. No.</td>
			

            <td class='tblinner' valign='middle' width='34%' align='center'>Event Details</td>
             <td class='tblinner' valign='middle' width='9%' align='center'>OrgName</td>
            <td class='tblinner' valign='middle' width='9%' align='center'>Total Sales</td>
            <td class='tblinner' valign='middle' width='6%' align='center'>Total Need to be Pay</td>
            <td class='tblinner' valign='middle' width='7%' align='center'>Total Paid</td>
            <td class='tblinner' valign='middle' width='7%' align='center'>Diff Amount</td>
            <td class='tblinner' valign='middle' width='11%' align='center'>Amount at EBS</td>            
             <td class='tblinner' valign='middle' width='9%' align='center'>Amount at Ghar Pay</td>
             <td class='tblinner' valign='middle' width='12%' align='center'>Amount to be in Bank</td>
            
         
            
          </tr>
        </thead>";

        $TotalAmount = 0;
        $TotalAmount1 = 0;
        $TotalAmount2 = 0;
        $TotalAmount3 = 0;
        $TotalAmount4 = 0;
        $TotalNetAmount = 0;
        $TotalRevenue = 0;
        for ($i = 0; $i < count($TransactionRES); $i++) {

            $data.=" <tr>
			<td class='tblinner' valign='middle' width='3%' align='center' ><font color='#000000'>" . $cntTransactionRES . "</font></td>
			<td class='tblinner' valign='middle' width='34%' align='center' ><font color='#000000'>" . $Global->GetSingleFieldValue("select title from event where deleted=0 and id='" . $TransactionRES[$i]['eventid'] . "'") . "</font></td>
			<td class='tblinner' valign='middle' width='9%' align='left'><font color='#000000'>" . $Global->GetSingleFieldValue("select u.company AS Company from user as u,event as e where u.id=e.ownerid and e.id='" . $TransactionRES[$i]['eventid'] . "'") . "</font></td>";

            $Totalsales = 0;
            $Totalneedtopaid = 0;
            $Totalpaid = 0;
            $EbsAmount = 0;
            $GharpayAmount = 0;
            $Ebsd = 0;
            $Gharpayd = 0;
            $BankAmount = 0;


            $perc = $Global->GetSingleFieldValue("select percentage from eventsetting where eventid=" . $TransactionRES[$i]['eventid']);
            if ($perc == 0) {
                $perc = 2.5;
            }


            $CountQuerySales = "SELECT id AS Id, signupdate AS SignupDt, quantity AS Qty, (totalamount/quantity) AS Fees FROM eventsignup as s  where 1  and s.paymentstatus='Verified'  AND  ((1 $offTransSql) or s.paymentmodeid=2)   and eventid='" . $TransactionRES[$i]['eventid'] . "'";
            $CountQuerySalesRESt = $Global->SelectQuery($CountQuerySales);
            for ($j = 0; $j < count($CountQuerySalesRESt); $j++) {

                $Totalsales = $Totalsales + ($CountQuerySalesRESt[$j][Qty] * $CountQuerySalesRESt[$j][Fees]);
                if ($CountQuerySalesRESt[$i]['SignupDt'] > '2012-03-31 23:59:59') {
                    $p = $perc + ($perc * 0.1236);
                } else {
                    $p = $perc + ($perc * 0.103);
                }
                $Totalneedtopaid = $Totalneedtopaid + round(($CountQuerySalesRESt[$j][Qty] * $CountQuerySalesRESt[$j][Fees]) - (($CountQuerySalesRESt[$j][Qty] * $CountQuerySalesRESt[$j][Fees]) * ($p / 100)), 2);
            }
            $Totalpaid = $Global->GetSingleFieldValue("select amountpaid AS AmountP from settlement where eventid=" . $TransactionRES[$i]['eventid']);

            $CountQueryEbs = "SELECT id AS Id, signupdate AS SignupDt, quantity AS Qty, (totalamount/quantity) AS Fees FROM eventsignup as s  where 1  and s.paymentstatus='Verified'  AND  (1 $offTransSql) and s.depositdate='0000-00-00 00:00:00' and eventid='" . $TransactionRES[$i]['eventid'] . "'";
            $CountQueryEbsRESt = $Global->SelectQuery($CountQueryEbs);
            for ($k = 0; $k < count($CountQueryEbsRESt); $k++) {

                if ($CountQueryEbsRESt[$i]['SignupDt'] > '2012-03-31 23:59:59') {
                    $pe = 2.15 + (2.15 * 0.1236);
                } else {
                    $pe = 2.15 + (2.15 * 0.103);
                }

                $EbsAmount = $EbsAmount + round(($CountQueryEbsRESt[$k][Qty] * $CountQueryEbsRESt[$k][Fees]) - (($CountQueryEbsRESt[$k][Qty] * $CountQueryEbsRESt[$k][Fees]) * ($pe / 100)), 2);
            }


            $CountQueryEbsd = "SELECT id AS Id, signupdate AS SignupDt, quantity AS Qty, (totalamount/quantity) AS Fees FROM eventsignup as s  where 1  and s.paymentstatus='Verified' AND (1 $offTransSql) and s.depositdate!='0000-00-00 00:00:00' and eventid='" . $TransactionRES[$i]['eventid'] . "'";
            $CountQueryEbsdRESt = $Global->SelectQuery($CountQueryEbsd);
            for ($k = 0; $k < count($CountQueryEbsdRESt); $k++) {

                if ($CountQueryEbsdRESt[$i]['SignupDt'] > '2012-03-31 23:59:59') {
                    $pe = 2.15 + (2.15 * 0.1236);
                } else {
                    $pe = 2.15 + (2.15 * 0.103);
                }

                $Ebsd = $Ebsd + round(($CountQueryEbsdRESt[$k][Qty] * $CountQueryEbsdRESt[$k][Fees]) - (($CountQueryEbsdRESt[$k][Qty] * $CountQueryEbsdRESt[$k][Fees]) * ($pe / 100)), 2);
            }

            $CountQueryCOD = "SELECT id AS Id, signupdate AS SignupDt, quantity AS Qty, (totalamount/quantity) AS Fees FROM eventsignup as s  where 1  and s.paymentstatus='Verified' AND (s.paymentmodeid=2 and s.paymentgatewayid='2') and s.depositdate='0000-00-00 00:00:00' and eventid='" . $TransactionRES[$i]['eventid'] . "'";
            $CountQueryCODRESt = $Global->SelectQuery($CountQueryCOD);
            for ($k = 0; $k < count($CountQueryCODRESt); $k++) {
                $pc = ($CountQueryCODRESt[$k][Qty] * $CountQueryCODRESt[$k][Fees]) / 100;
                if ($pc > 100) {
                    $pc = 100;
                }
                $GharpayAmount = $GharpayAmount + (($CountQueryCODRESt[$k][Qty] * $CountQueryCODRESt[$k][Fees]) - $pc);
            }


            $CountQueryCODd = "SELECT  id as Id, signupdate AS SignupDt, quantity AS Qty, (totalamount/quantity) AS Fees FROM eventsignup as s  where 1  and s.paymentstatus='Verified'  AND  (s.paymentmodeid=2 and s.paymentgatewayid='2') and s.depositdate!='0000-00-00 00:00:00' and eventid='" . $TransactionRES[$i]['eventid'] . "'";
            $CountQueryCODdRESt = $Global->SelectQuery($CountQueryCODd);
            for ($k = 0; $k < count($CountQueryCODdRESt); $k++) {
                $pc = ($CountQueryCODdRESt[$k][Qty] * $CountQueryCODdRESt[$k][Fees]) / 100;
                if ($pc > 100) {
                    $pc = 100;
                }

                $Gharpayd = $Gharpayd + (($CountQueryCODdRESt[$k][Qty] * $CountQueryCODdRESt[$k][Fees]) - $pc);
            }

            $BankAmount = $Gharpayd + $Ebsd - $Totalpaid;

            $data.="<td class='tblinner' valign='middle' width='9%' align='center'><font color='#000000'>" . $Totalsales . "</font></td>     		
<td class='tblinner' valign='middle' width='6%' align='center'><font color='#000000'>" . $Totalneedtopaid . "</font></td>
<td class='tblinner' valign='middle' width='7%' align='right'><font color='#000000'> " . $Totalpaid . "</font></td>
<td class='tblinner' valign='middle' width='7%' align='right'><font color='#000000'>" . round($Totalneedtopaid - $Totalpaid, 2) . "</font></td>
<td class='tblinner' valign='middle' width='11%' align='right'><font color='#000000'>" . $EbsAmount . "</font></td>

<td class='tblinner' valign='middle' width='9%' align='right'><font color='#000000'>" . $GharpayAmount . "</font></td>
<td class='tblinner' valign='middle' width='12%' align='right'><font color='#000000'>" . $BankAmount . "</font></td>
</tr>";

            $TTotalsales = $TTotalsales + $Totalsales;
            $TTotalneedtopaid = $TTotalneedtopaid + $Totalneedtopaid;
            $TTotalpaid = $TTotalpaid + $Totalpaid;
            $TEbsAmount = $TEbsAmount + $EbsAmount;
            $TGharpayAmount = $TGharpayAmount + $GharpayAmount;
            $TBankAmount = $TBankAmount + $BankAmount;
            $cntTransactionRES++;
                    }
                    $data.="<tr><td colspan='3' style='line-height:30px;'><strong>TotalAmount:</strong></td><td  align='right'><font color='#000000'>" . $TTotalsales . "</font></td><td  align='right'><font color='#000000'>" . $TTotalneedtopaid . "</font></td><td  align='right'><font color='#000000'>" . $TTotalpaid . "</font></td><td  align='right'><font color='#000000'>" . ($TTotalneedtopaid - $TTotalpaid) . "</font></td><td  align='right'><font color='#000000'>" . $TEbsAmount . "</font></td><td  align='right'><font color='#000000'>" . $TGharpayAmount . "</font></td><td  align='right'><font color='#000000'>" . $TBankAmount . "</font></td></tr>
     
</table>
</div>
<table width='90%'>
 <tr><td colspan='9' align='center'>
<input src='" . _HTTP_CF_ROOT . "/ctrl/images/print.jpg' title='Print' name='button' value='Print' type='image' onclick='ClickHereToPrintpass()'></td></tr>
</table>";
                                                    ?>
                                                    <table align="center" border="1" width="90%">
                                                        <tr><td align="center">Total Sales</td><td align="center">Need To Pay</td><td align="center">Paid</td><td align="center">Diff Amount</td><td align="center">Amount in EBS</td><td align="center">Amount in Gharpay</td><td align="center">Amount in Bank</td></tr>
                                                        <tr><td align="center"><?php echo $TTotalsales; ?></td><td align="center"><?php echo $TTotalneedtopaid; ?></td><td align="center"><?php echo $TTotalpaid; ?></td><td align="center"><?php echo $TTotalneedtopaid - $TTotalpaid; ?></td><td align="center"><?php echo $TEbsAmount; ?></td><td align="center"><?php echo $TGharpayAmount; ?></td><td align="center"><?php echo $TBankAmount; ?></td></tr>
                                                    </table>
                                                    <p>&nbsp;</p>
                                                    <?php echo $data; ?>

                                                    <!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->

                                                </div>
                                                </td>
                                            </tr>
                                    </table>
                                    </div>	
                                    </body>
                                    </html>