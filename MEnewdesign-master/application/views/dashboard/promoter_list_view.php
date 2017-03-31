<div class="rightArea">
    <?php
    $promoterSuccessMessage = $this->customsession->getData('promoterSuccessAdded');
    $this->customsession->unSetData('promoterSuccessAdded');
    if ($promoterSuccessMessage) { ?>
        <div class="db-alert db-alert-success flashHide">
            <strong>  <?php echo $promoterSuccessMessage; ?></strong> 
        </div>
    <?php } ?>
    <div class="heading float-left">
        <h2>Promoters List: <span> <?php echo $eventName; ?> <?php //echo $eventId;  ?></span></h2>
    </div>
    <div class="clearBoth"></div>
    <div class="float-right"> 
        <a href="<?php echo commonHelperGetPageUrl("dashboard-add-affliate", $eventId); ?>" class="createBtn float-left font14"><span class="icon-add pinkBg"></span>Add New Promoter
        </a> </div>
    <div class="clearBoth"></div>
    <?php if(isset($promoterDetails) && !$promoterDetails['status']) { ?>               
        <div class="db-alert db-alert-danger flashHide">                    
            <strong><?php print_r($promoterDetails['response']['messages'][0]); ?></strong> 
        </div>
        <?php } ?>  

        <div class="tablefields">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" data-tablesaw-minimap>
                <thead>
                    <tr>
                        <th scope="col" data-tablesaw-priority="2">Promoter Name</th>
                        <th scope="col" data-tablesaw-priority="2">Promoter Code</th>
                        <th scope="col" data-tablesaw-priority="2" style="width:30% !important">Created On</th>
                        <th scope="col" data-tablesaw-priority="2">Tickets sold</th>
                        <th scope="col" data-tablesaw-priority="2">Amount</th>
                        <th scope="col" data-tablesaw-priority="2">Current Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($promoterDetails['status'] && $promoterDetails['response']['total'] == 0) { ?>
                        <tr><td colspan="6">
                                <div id="noPromoterMessage" class="db-alert db-alert-info">                    
                                    <strong> <?php echo $promoterDetails['response']['messages'][0]; ?></strong> 
                                </div>
                            </td> </tr> <?php
            } else if ($promoterDetails['status'] && $promoterDetails['response']['total'] > 0) {              
                    $i = 0;
                    $promoterList = $promoterDetails['response']['promoterList'];
                    foreach ($promoterList as $index => $row) {
                        $class = 'odd';
                        if ($i % 2 == 0) {
                            $class = '';
                        }
                                ?>
                                <tr <?php echo $class; ?> >
                                    <td><a class="affiliateview" href="<?php echo commonHelperGetPageUrl("dashboard-add-affliate", $eventId.'&'.$row['id']);?>"><?php echo $row['name']; ?></a></td>
                                    <td><?php echo $row['code']; ?></td>
                                    <td><?php echo convertDateTime(convertTime($row['cts'], $eventTimeZoneName, true)); ?></td>
                                    <td><?php echo $row[$row['code']]['quantity']; ?></td>
                                    <td><?php echo $row[$row['code']]['totalamount']; ?></td>
                                    <td><?php if ($row['status'] == 1) { ?>
                                            <button onclick="changeStatus('<?php echo $row['id']; ?>')" type="button" class="btn greenBtn" id='<?php echo $row['id']; ?>'>ACTIVE </button>
                                        <?php } else { ?>
                                            <button onclick="changeStatus('<?php echo $row['id']; ?>')" type="button" class="btn orangrBtn" id='<?php echo $row['id']; ?>'>INACTIVE </button>
                                        <?php } ?></td>
                                </tr>
                            <?php }
                        }
                     ?>                     
            </tbody>
        </table>
    </div>
</div>