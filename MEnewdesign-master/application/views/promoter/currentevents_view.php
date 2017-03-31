<div class="rightArea">
    <div class="heading">
        <h2>Promoter Events List</h2>
    </div>
    <div class="PromoterView">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" data-tablesaw-mode="swipe" data-tablesaw-minimap>
            <thead>
                <tr>
                    <th scope="col" data-tablesaw-priority="persist">Event Id</th>
                    <th scope="col" data-tablesaw-priority="3">Event Title</th>
                    <th scope="col" data-tablesaw-priority="3">City</th>
                    <th scope="col" data-tablesaw-priority="3">Prmoter Code Status</th>
                    <th scope="col" data-tablesaw-priority="3">Options</th>
                </tr>
            </thead>

            <tbody>

                <?php if (count($promoters) > 0) { ?>
                    <?php foreach ($promoters as $promoter) {
                        ?>
                        <tr>
                            <td><?php echo $promoter['eventId']; ?></td>
                            <td><?php echo $promoter['eventTitle']; ?><br>
                                <?php
                                if ($promoter['type'] == 'offline') {
                                    $reportsLink = commonHelperGetPageUrl('promoter-transaction-report', $promoter['eventId'] . '&offline&' . $promoter['code']);
                                    ?>
                                    <span class="PV_salestext PV_color">(Offline sales active)</span>
                                    <span class="PV_salestext">Transactions: <a href="<?php echo $reportsLink; ?>">Tickets - <?php echo $promoter['quantity']; ?></a> |  <a href="<?php echo $reportsLink; ?>">Amount - <?php echo round($promoter['totalamount']); ?></a></span>
                                <?php } else { ?>    
                                <?php } ?>

                            </td>
                            <td><?php echo $promoter['city']; ?></td> 
                            <?php if ($promoter['status'] == 1) { ?>
                                <td><button type="button" class="btn greenBtn defaultCursor">ACTIVE</button></td>
                            <?php } else { ?>
                                <td><button type="button" class="btn orangrBtn defaultCursor">INACTIVE</button></td>
                            <?php } ?>
                            <?php if ($promoter['type'] == 'offline') { ?>
                                <td><a href="offlinebooking?eventId=<?php echo $promoter['eventId'] ?>&id=<?php echo $promoter['promoterId'] ?>" class="sellBtn">SELL TICKETS</a></td>
                            <?php } else { ?>    
                                <td><a href="<?php echo commonHelperGetPageUrl('user-promoterview-eventdetailslist', 'current&' . $promoter['eventId'] . '&' . $promoter['code']); ?>" class="sellBtn">VIEW DETAILS</a></td>
                            <?php } ?>

                        </tr><?php } ?>
                <?php } else { ?>
                    <tr><td colspan="5"><div class="db-alert db-alert-info"><?php echo 'Sorry, No records found.'; ?></div></td></tr> 
                        <?php } ?>
            </tbody>
        </table>
    </div>
</div>
