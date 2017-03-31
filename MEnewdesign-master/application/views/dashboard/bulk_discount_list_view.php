<div class="rightArea">
        <?php  
        $discountAddedMessage=$this->customsession->getData('discountFlashMessage');
        $this->customsession->unSetData('discountFlashMessage');
        if($discountAddedMessage){?>
        <div class="db-alert db-alert-success flashHide">
            <strong>  <?php echo $discountAddedMessage; ?></strong> 
        </div>
    <?php }?>
    <div class="heading float-left">
        <h2>Bulk Discounts: <span><?php echo $eventName; ?></span></h2>
    </div>
    <div class="clearBoth"></div>
    <div style="width:50%" class="float-left"> </div>
    <div class="float-right"> <a href="<?php echo commonHelperGetPageUrl('dashboard-add-bulkdiscount', $eventId); ?>" class="createBtn float-left font14"><span class="icon-add pinkBg"></span>Add New Bulk Discount</a> </div>
    <div class="clearBoth"></div>
    <div class="refundSec discount">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" data-tablesaw-mode="swipe" data-tablesaw-minimap>
            <thead>
                <tr>
                    <th scope="col" data-tablesaw-priority="persist" style="width:20% !important">Name</th>
                    <th scope="col" data-tablesaw-priority="3" style="width:20% !important">Price</th>
                    <th scope="col" data-tablesaw-priority="3" style="width:20% !important">Used/Limit</th>
                    <th scope="col" data-tablesaw-priority="3" style="width:20% !important">Status</th>
                    <th scope="col" data-tablesaw-priority="3"style="width:20% !important">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($noDiscountMessage)) { ?>
                 <tr><td colspan='6'>   
                <div id="noDiscountMessage" class="db-alert db-alert-info">                    
                    <strong><?php echo $noDiscountMessage; ?></strong> 
                </div>
        </td></tr>
            <?php } else { ?>                     

                <?php
                if ($discountList) {
                    $i = 0;
                    foreach ($discountList as $index => $row) {
                        $class = 'odd';
                        if ($i % 2 == 0) {
                            $class = '';
                        }
                        ?>

                        <tr <?php echo $class; ?> >
                            <td><?php echo $row['name']; ?></td>                
                            <td><?php
                                echo $row['value'];
                                if ($row['calculationmode'] == 'percentage') {
                                    echo'%';
                                }
                                ?></td>
                            <td><?php echo $row['totalused'].' / ('.($row['minticketstobuy'].' - '.((($row['maxticketstobuy']==' ')||($row['maxticketstobuy']==0))?'MAX':$row['maxticketstobuy'])).')'; ?></td>                      
                            <td><?php
                                    if($row['status']==0){?>
                                    <button type="button" class="btn orangrBtn defaultCursor">INACTIVE</button>
                                   <?php } else { ?>
                                         <button type="button" class="btn greenBtn defaultCursor">ACTIVE</button>
                                  <?php  }
                                    ?></td>
                            <td>
                                <a href="<?php echo commonHelperGetPageUrl('dashboard-add-bulkdiscount', $eventId.'&'.$row['id']); ?>"><span class="icon-edit" id="<?php echo $row['code']; ?>"></span></a>
                                 <?php if($row['totalused']==0){ ?> <a href="javascript:deleteDiscount('<?php echo commonHelperGetPageUrl('dashboard-bulkdiscount', $eventId.'&'.$row['id']); ?>')"><span class="icon-delete"></span><?php } ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
            }
            ?>  

            </tbody>
        </table>
    </div>
</div>

