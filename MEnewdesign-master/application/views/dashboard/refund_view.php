<div class="rightArea">
    <div class="heading">
        <h2>Export Refund: <span><?php echo $eventName; ?></span></h2>
    </div>
    <!--Data Section Start-->
    <input type="hidden" name="eventId" id="eventId" value="<?php echo $eventId; ?>"/>
    <div class="float-right">
        <?php if($refundOutput['response']['total'] == 0){?>
        <button type="button" class="Btn" name="exportReports" onClick="alert('No records found')";><span class="icon-export"></span>export</button>
        <?php } else { ?> 
        <button type="button" class="Btn" name="exportReports" id="exportReports"><span class="icon-export"></span>export</button>
            <?php } ?>
<!--        <button type="button" class="Btn"><span class="icon-mail"></span>email body</button>-->
        <button type="button" class="Btn" name="emailAttachedReports" id="emailAttachedReports"><span class="icon-attachment"></span>email attachment</button>
    </div>        

    <div class="clearBoth"></div>
    <div><span id='mailSent' class='success'></span></div>
    <div class="refundSec">
        <?php if (count($headerFields) > 0) { ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" id="transactionTable" class="transactionTable">
                <thead>
                    <tr>
                        <?php foreach ($headerFields as $value) {
                            ?>

                            <th><?php echo $value; ?></th>

                        <?php } ?> 
                    </tr>
                <?php } ?>
                </thead>
                <tbody>
                <?php if (isset($refundOutput) && $refundOutput['status'] == true && $refundOutput['response']['total'] == 0) { ?>
                    <tr>
                        <td colspan="9">
                        <div class="db-alert db-alert-info">                    
                                <strong> <?php print_r($refundOutput['response']['messages'][0]); ?></strong> 
                        </div>
                        </td>
                    </tr>
                    <?php
                } else {
                    $SNo = 1;
                    foreach ($refundOutput['response']['transactionList'] as $index => $signupIndexRow) {
                        ?>
                        <tr>                                        
                            <?php foreach ($signupIndexRow as $value) { ?>
                                <td><?php echo $SNo++; ?></td>
                                <td><?php echo $value['id']; ?></td>                                       
                                <td><?php echo $value['signupDate']; ?></td>
                                <td><?php
                                    foreach ($value['ticketDetails'] as $key => $ticketValue) {
                                        echo $ticketValue['tickettype'];
                                    }
                                    ?></td>
                                <td><?php
                                       echo $value['contactDetails']['name']. '<br>'.$value['contactDetails']['email']. '<br>'.$value['contactDetails']['phone'];                                     
                                    ?></td>                                        
                                <?php foreach ($value['ticketDetails'] as $key => $ticketValue) { ?>
                                    <td><?php echo $ticketValue['quantity']; ?></td>   
                                    <td><?php
                                        echo $ticketValue['amount'];
                                    }
                                    ?></td>
                                <td><?php echo $value['paid']; ?></td>      
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
            </tbody>
        </table>
        <table>
                <?php if (count($grandTotal)>0) { ?>
                    <!--Total Table-->
                    <tr class="TotalTable">
                        <td colspan="12" style="border:none;padding: 0;">
                            <table align="left" cellpadding="0" cellspacing="0" style="background:none; font-size:24px; font-weight:normal; border:none;" class="TransactionTotalDiv">
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
                <input type="hidden" name="SNo" id="SNo" value="<?php echo $SNo; ?>"/>
                <input type="hidden" name="displaylimit" id="displaylimit" value="<?php echo REPORTS_DISPLAY_LIMIT; ?>"/>
                <input type="hidden" name="totalTransactionCount" id="totalTransactionCount" value="<?php echo $totalTransactionCount; ?>"/>
                <?php if(isset($custIdArray)){?>
                <input type="hidden" name="custIds" id="custIds" value="<?php echo json_encode($custIdArray) ?>" />
                <?php }?>
            </table>
    </div>
</div>                    