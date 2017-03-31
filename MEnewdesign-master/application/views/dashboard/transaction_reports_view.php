<!--Right Content Area Start-->
<div class="rightArea">
    <div class="heading">
        <h2>Export Reports: <span> <?php echo isset($eventTitle) ? $eventTitle : ''; ?></span></h2>
    </div>
    <!--Data Section Start-->
    <input type="hidden" name="eventId" id="eventId" value="<?php echo $eventId; ?>"/>
    <input type="hidden" name="page" id="page" value="<?php echo $page; ?>"/>
    <input type="hidden" name="promoterCode" id="promoterCode" value="<?php echo $promoterCode; ?>"/>
    <input type="hidden" name="currencyCode" id="currencyCode" value="<?php echo $currencyCode; ?>"/>
    <input type="hidden" name="transactionType" id="transactionType" value="<?php echo $transactionType; ?>"/>
    
        <?php $qString= "";
    if (isset($promoterCode) && !empty($promoterCode)){ 
        $qString .= 'promotercode='.$promoterCode;
     }else if(isset($currencyCode) && !empty ($currencyCode)){
        $qString .= '&currencycode='.$currencyCode;
     } ?>
    <input type="hidden" name="qString" id="qString" value="<?php echo $qString; ?>"/>
    <div class="fs-transtaction-actions">
	    <div class="fs-filter-actions">
        <?php if(!isset($showFilters)){ ?>
        <div class="defaultDroplist">
	            <div>
                <label class="icon-downarrow">
	                    <select name="selectTransType" id="selectTransType">
                        <option <?php if ($transactionType == 'all') { ?>selected="selected"<?php } ?> value="all">All  Transactions</option>
                        <option <?php if ($transactionType == 'card') { ?>selected="selected"<?php } ?> value="card">Card Transactions</option>
                        <option <?php if ($transactionType == 'cod') { ?>selected="selected"<?php } ?> value="cod">COD Transactions</option>
                        <option <?php if ($transactionType == 'free') { ?>selected="selected"<?php } ?> value="free">Free Transactions</option>
                        <option <?php if ($transactionType == 'incomplete') { ?>selected="selected"<?php } ?> value="incomplete">Incomplete Transactions</option>
                        <option <?php if ($transactionType == 'viral') { ?>selected="selected"<?php } ?> value="viral">Viral Transactions</option>
                        <option <?php if ($transactionType == 'affiliate') { ?>selected="selected"<?php } ?> value="affiliate">Affiliate Marketing Reports</option>
                        <option <?php if ($transactionType == 'offline') { ?>selected="selected"<?php } ?> value="offline">Offline Transactions</option>
                        <option <?php if ($transactionType == 'boxoffice') { ?>selected="selected"<?php } ?> value="boxoffice">Box Office Transactions</option>
                        <!--<option>Sales Effort Reports</option>-->
                        <option <?php if ($transactionType == 'cancel') { ?>selected="selected"<?php } ?> value="cancel">Cancel Transactions</option>
                    </select>
                </label>
            </div>

	            <div>
                <label class="icon-downarrow">
	                    <select name="selectTicketType" id="selectTicketType">
                        <option value="0">Select Ticket</option>
                        <?php foreach ($ticketsData as $ticket) { ?>
                            <option <?php
                            if ($ticketId == $ticket['id']) {
                                echo "selected";
                            }
                            ?> value="<?php echo $ticket['id']; ?>"><?php echo $ticket['name']; ?></option>
                            <?php } ?>
                    </select>
                </label>
            </div>
	            <div class="fs-summary-detail">
                <?php if ($transactionType != 'cancel') { ?>
                        <label style="float:left;"> <input class="reportType" type="radio" name="reportType" value="summary" <?php if ($reportType == 'summary') { ?>checked="checked" <?php } ?>>
                    Summary</label>
                <?php } ?>
                <?php if ($transactionType != 'incomplete') { ?>
                   <label style="float:left;"> <input class="reportType" type="radio" name="reportType" value="detail" <?php if ($reportType == 'detail') { ?>checked="checked" <?php } ?>>
                    Detail</label>
                <?php } ?>
            </div>
            <?php if (isset($downloadAllRequired)) { ?>
	                <div>
                    <button id="downloadAll" name="downloadAll" class="Btn" type="button">Download all files</button>
                    <p id="download_files" style=" text-align: center;padding: 0;margin: 10px 0;"></p>
                </div>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
		<div class="fs-export-email fs-export-reports-special">
                    <?php if($errors){ ?>
                     <button type="button" class="Btn" name="exportReports"  onClick="alert('No records found')";><span class="icon-export"></span>export</button>
                   <?php }else{ ?>
        <button type="button" class="Btn" name="exportReports" id="exportReports"><span class="icon-export"></span>export</button>
              <?php }?>
<!--        <button type="button" class="Btn"><span class="icon-mail"></span>email body</button>-->
         <?php if(!isset($showFilters)){ ?>
        <button type="button" class="Btn" name="emailAttachedReports" id="emailAttachedReports"><span class="icon-attachment"></span>email attachment</button>
    <?php } ?>
    </div>
    </div>
                <table width="100%">
                <?php if ($transactionType != 'incomplete') { ?>
                    <!--Total Table-->
                    <tr class="TotalTable">
                        <td colspan="12" style="border:none;padding: 0;">
                            <table width="100%" align="left" cellpadding="0" cellspacing="0" style="background:none; font-size:24px; font-weight:normal; border:none;" class="TransactionTotalDiv">
                                <tbody><tr><td colspan="2">
                                            <span class="transactiontitle">Total Quantity</span>
                                            <span id="quantitySum"><?php echo isset($grandTotal['totalquantity']) ? $grandTotal['totalquantity'] : 0; ?></span></td>

                                        <td colspan="2"><span class="transactiontitle">Total Amount</span> 
                                            <span id="amountSum"><?php 
                                                if (isset($grandTotal['totalamount']) && is_array($grandTotal['totalamount'])) {
                                                    $currencyStr = '';
                                                    foreach ($grandTotal['totalamount'] as $key => $value) {
                                                        if (!empty($key)) {
                                                            $currencyStr.=$key . ' ' . $value . "\n";
                                                         }
                                                    }
                                                        echo !empty($currencyStr) ? $currencyStr : '0';
                                                    
                                                } else {
                                                    echo '0';
                                                }
                                                ?></span></td>
                                        <td colspan="2"><span class="transactiontitle">Total Discount</span> 
                                            <span id="amountSum"><?php 
                                                if (isset($grandTotal['totaldiscount']) && is_array($grandTotal['totaldiscount'])) {
                                                    $currencyStr = '';
                                                    foreach ($grandTotal['totaldiscount'] as $key => $value) {
                                                        if (!empty($key)) {
                                                            $currencyStr.=$key . ' ' . $value . "\n";
                                                         }
                                                    }
                                                        echo !empty($currencyStr) ? $currencyStr : '0';
                                                    
                                                } else {
                                                    echo '0';
                                                }
                                                ?></span></td>
                                                                                                                                        <!--                                        <td colspan="2"><span class="transactiontitle">Discount Amount</span> <span> 1500 INR</span></td>


                                                                                                                                                                                <td colspan="2"><span class="transactiontitle">Cash on Delivery</span> <span> 1500 INR</span></td>-->

                                        <td colspan="2"><span class="transactiontitle">Total Paid</span> 

                                            <span id="paidSum"><?php
                                                if (isset($grandTotal['totalpaid']) && is_array($grandTotal['totalpaid'])) {
                                                    $currencyStr = '';
                                                    foreach ($grandTotal['totalpaid'] as $key => $value) {
                                                        if (!empty($key)) {
                                                            $currencyStr.=$key . ' ' . $value . "\n";
                                                        }
                                                    }
                                                    echo!empty($currencyStr) ? $currencyStr : '0';
                                                } else {
                                                    echo '0';
                                                }
                                                ?></span></td>
                                    </tr></tbody></table>
                        </td>
                    </tr>
        <!--                    <tr class="TotalTable">
                        <td >Grand Total</td>
                        <td id="quantitySum"><?php echo isset($grandTotal['totalquantity']) ? $grandTotal['totalquantity'] : 0; ?></td>
                        <td id="amountSum"><?php
//                            if (isset($grandTotal['totalamount']) && is_array($grandTotal['totalamount'])) {
//                                $currencyStr = '';
//                                foreach ($grandTotal['totalamount'] as $key => $value) {
//                                    $currencyStr.=$key . ' ' . $value . "\n";
//                                }
//                                echo $currencyStr;
//                            } else {
//                                echo '0';
//                            }
                    ?></td>
                        <td id="paidSum"><?php
//                            if (isset($grandTotal['totalpaid']) && is_array($grandTotal['totalpaid'])) {
//                                $currencyStr = '';
//                                foreach ($grandTotal['totalpaid'] as $key => $value) {
//                                    $currencyStr.=$key . ' ' . $value . "\n";
//                                }
//                                echo $currencyStr;
//                            } else {
//                                echo '0';
//                            }
                    ?></td>
                        <td  id="discountSum"></td>
                    </tr>-->
                <?php } ?>
                </table>
    <div class="clearBoth"></div>
    <div class="refundSec bottomAdjust100">
        <?php if (count($headerFields) > 0) { ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" id="transactionTable" class="transactionTable">
                <thead>
                    <tr>
                        <?php foreach ($headerFields as $value) {
                            ?>

                            <th scope="col" data-tablesaw-priority="2"><?php echo $value; ?></th>

                        <?php } ?> 
                        <?php
                        if (isset($fileCustomFieldArray) && count($fileCustomFieldArray)) {
                            foreach ($fileCustomFieldArray as $custId => $custName) {
                                $custIdArray[] = $custId;
                                ?>
               
                                <th scope="col" data-tablesaw-priority="1"><?php echo $custName; ?></th>
                                <?php
                            }
                        }
                        ?>
                    </tr>
                </thead>
                <?php } ?>
                <tbody>
                <?php if (isset($errors)) { ?>
                    <tr>
                        <td colspan="9"><div class="db-alert db-alert-info"><?php print_r($errors[0]); ?></div></td>
                    </tr>
                    <?php
                } else {
                    $SNo = 1;
                    $loop = 1;
                    //print_r($transactionList);exit;
                    if ($reportType == 'detail') {
                        
                        foreach ($transactionList as $transaction) {
                            foreach ($transaction as $value) {
                                foreach ($value['ticketDetails'] as $ticket) {
                                    ?>
                                    <tr>
                                        <td><?php echo $SNo++; ?></td>
                                        <?php if ($transactionType != 'incomplete') { ?>
                                            <td><?php echo $value['id']; ?></td>
                                        <?php } ?>
                                        <td><span><?php echo $value['signupDate']; ?></span></td>
                                        <td><?php echo $ticket['tickettype']; ?></td>
                                        <?php if ($transactionType == 'affiliate') { ?>
                                            <td><?php echo $ticket['promotercode']; ?></td>
                                        <?php } ?>
                                        <td><?php echo $value['contactDetails']['name'] . '<br>' . $value['contactDetails']['email'] . '<br>' . $value['contactDetails']['phone']; ?></td>  
                                        <td><?php echo $ticket['quantity']; ?></td>
                                        <td><?php echo $ticket['amount']; ?></td>
                                        <?php if ($transactionType != 'incomplete' && $transactionType != 'cancel') { ?>
                                            <td><?php echo $value['paid'];
                                            ?></td>
                                            <td><?php echo $value['discount']; ?>
                        <!--                                            <span class="float-right grayBg icon-add view"></span>--></td>
                                        <?php } elseif ($transactionType == 'incomplete') {
                                            ?>
                                            <td><?php echo $ticket['failedcount'];
                                            ?></td>
                                            <td><?php echo $ticket['comment']; ?>
                                                <!--<span class="float-right grayBg icon-add view"></span>-->
                                            </td>
                                        <?php } else {
                                            ?>
                                            <td><?php echo $value['paid'];
                                            ?></td>
                                            <td><?php echo $value['comment']; ?>
                                                <!--<span class="float-right grayBg icon-add view"></span>-->
                                            </td>
                                            <?php
                                        }
                                        $colspan = '9';
                                        if ($transactionType == 'cod') {
                                            $colspan = '12';
                                            ?>
                                            <td><?php echo $ticket['comment']; ?></td>
                                            <td><?php echo $ticket['status']; ?></td>
                                            <td><?php echo $ticket['deliverystatus']; ?></td>
                                        <?php } ?>
                                        <?php
                                        if (isset($custIdArray) && count($custIdArray) > 0) {
                                            foreach ($custIdArray as $key => $id) {
                                                $path = '';
                                                if (isset($ticket['customfields'][$id]['value'])) {
                                                    $path = $ticket['customfields'][$id]['value'];
                                                }
                                                if (strlen($path) > 0) {
                                                    ?> 
                                                    <td><a href="<?php echo $path; ?>" target="_blank">View</a> | <br/><a href="<?php echo commonHelperGetPageUrl('download-file','','?filePath='.$path); ?>">Download</a></td>

                                                <?php } else { ?>
                                                    <td>&nbsp;</td>
                                                    <?php
                                                }
                                            }
                                            ?>

                                        <?php } ?>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                    } else {
                        foreach ($transactionList as $value) {
                            //print_r($value);exit;
                            $loop = 1;
                            $bookedTicketsCount = count($value['ticketDetails']);
                            foreach ($value['ticketDetails'] as $ticket) {
                                ?>
                                <tr>
                                    <td><?php echo $loop == 1 ? $SNo++ : ''; ?></td>
                                    <?php if ($transactionType != 'incomplete') { ?>
                                        <td><?php echo $loop == 1 ? $value['id'] : ''; ?></td>
                                    <?php } ?>
                                    <td><span><?php echo $loop == 1 ? $value['signupDate'] : ''; ?></span></td>
                                    <td><?php echo $ticket['tickettype']; ?></td>
                                    <?php if ($transactionType == 'affiliate') { ?>
                                        <td><?php echo $ticket['promotercode']; ?></td>
                                    <?php } ?>
                                    <td><?php
                                        echo $value['contactDetails']['name'] . '<br>' . $value['contactDetails']['email'] . '<br>' . $value['contactDetails']['phone'];
                                        ?></td>                                     <td><?php echo $ticket['quantity']; ?></td>
                                    <td><?php echo $ticket['amount']; ?></td>
                                    <?php if ($transactionType != 'incomplete' && $transactionType != 'cancel') { ?>
                                        <td><?php echo ($bookedTicketsCount == $loop) ? $value['paid'] : '';
                                        ?></td>
                                        <td><?php echo ($bookedTicketsCount == $loop) ? $value['discount'] : ''; ?>
                    <!--                                            <span class="float-right grayBg icon-add view"></span>--></td>
                                    <?php } elseif ($transactionType == 'incomplete') {
                                        ?>
                                        <td><?php echo ($bookedTicketsCount == $loop) ? $value['failedcount'] : '';
                                        ?></td>
                                        <td><?php echo ($bookedTicketsCount == $loop) ? $value['comment'] : ''; ?>
                                            <!--<span class="float-right grayBg icon-add view"></span>-->
                                        </td>
                                    <?php } else {
                                        ?>
                                        <td><?php echo ($bookedTicketsCount == $loop) ? $value['comment'] : ''; ?>
                                            <!--<span class="float-right grayBg icon-add view"></span>-->
                                        </td>
                                        <?php
                                    }
                                    $colspan = '9';
                                    if ($transactionType == 'cod') {
                                        $colspan = '12';
                                        ?>
                                        <td><?php echo $ticket['comment']; ?></td>
                                        <td><?php echo $ticket['status']; ?></td>
                                        <td><?php echo $ticket['deliverystatus']; ?></td>
                                    <?php } ?>
                                    <?php
                                    if (isset($custIdArray) && count($custIdArray) > 0 && $bookedTicketsCount == $loop) {
                                        foreach ($custIdArray as $key => $id) {
                                            $path = '';
                                            if (isset($value['customfields'][$id])) {
                                                $path = $value['customfields'][$id];
                                            }
                                            if (strlen($path) > 0) {
                                                ?> 
                                                <td><a href="<?php echo $path; ?>" target="_blank">View</a> | <br/><a href="<?php echo commonHelperGetPageUrl('download-file','','?filePath='.$path);?>">Download</a></td>

                                            <?php } else { ?>
                                                <td>&nbsp;</td>
                                                <?php
                                            }
                                        }
                                        ?>

                                    <?php } ?>
                                </tr>
                                <?php
                                $loop++;
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        <?php if ($totalTransactionCount > REPORTS_DISPLAY_LIMIT) { ?>
            <button type="button" name="loadMoreTransactions" id="loadMoreTransactions">Load More</button>
        <?php } ?>  
                <input type="hidden" name="SNo" id="SNo" value="<?php echo $SNo; ?>"/>
                <input type="hidden" name="displaylimit" id="displaylimit" value="<?php echo REPORTS_DISPLAY_LIMIT; ?>"/>
                <input type="hidden" name="totalTransactionCount" id="totalTransactionCount" value="<?php echo $totalTransactionCount; ?>"/>
                <?php if(isset($custIdArray)){?>
                <input type="hidden" name="custIds" id="custIds" value="<?php echo json_encode($custIdArray) ?>" />
                <?php }?>
        <?php } ?>
            