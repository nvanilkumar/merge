<div class="rightArea">
    <?php  
        $discountFlashMessage=$this->customsession->getData('discountFlashMessage');
        $this->customsession->unSetData('discountFlashMessage');
    ?>
    <?php if($discountFlashMessage){?>
        <div class="db-alert db-alert-success flashHide">
            <strong>  <?php echo $discountFlashMessage; ?></strong> 
        </div>
    <?php }?>
    <div class="heading">
        <h2>Discount Codes: <span><?php echo $eventName; ?></span></h2>
    </div>
    <div style="width:50%" class="float-left"> </div>
    <div class="float-right"> <a href="<?php echo commonHelperGetPageUrl('dashboard-add-discount', $eventId); ?>" class="createBtn float-left font14"><span class="icon-add pinkBg"></span>Add New Discount</a> </div>
    <div class="clearBoth"></div>
    <div class="refundSec discount">
        <table width="100%" border="1" cellspacing="0" cellpadding="0" data-tablesaw-mode="swipe" data-tablesaw-minimap>
            <thead>
                <tr>
                    <th scope="col" data-tablesaw-priority="persist" style="padding-left:2%;text-align:left;width:20% !important">Name</th>
                    <th scope="col" data-tablesaw-priority="2" style="text-align:left;width:12% !important">Price</th>
                    <th scope="col" data-tablesaw-priority="2" style="text-align:left;width:15% !important">Used/Limit</th>
                    <th scope="col" data-tablesaw-priority="2" style="text-align:left;width:17% !important">Promotion Code</th>
                    <th scope="col" data-tablesaw-priority="2" style="text-align:left;width:15% !important">Status</th>
                    <th scope="col" data-tablesaw-priority="2" style="text-align:left;width:10% !important">Action</th>
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
                            <td style="padding-left:2%; text-align:left !important;"><?php echo $row['name']; ?></td>                
                            <td style="text-align:left !important;"><?php
                                echo $row['value'];
                                if ($row['calculationmode'] == 'percentage') {
                                    echo'%';
                                }
                                ?></td>
                            <td style="text-align:left !important;"><?php echo $row['totalused'].'/'.$row['usagelimit']  ?></td>                  
                            <td style="text-align:left !important;"><?php echo $row['code']; ?></td>
                            <td style="text-align:left !important;"><?php
                                    if($row['status']==0){?>
                                    <button type="button" class="btn orangrBtn defaultCursor" >INACTIVE</button>
                                   <?php } else { ?>
                                         <button type="button" class="btn greenBtn defaultCursor">ACTIVE</button>
                                  <?php  }
                                    ?></td>
                            <td style="text-align:left !important;">
                                <a href="<?php echo commonHelperGetPageUrl('dashboard-add-discount', $eventId.'&'.$row['id']); ?>"><span class="icon-edit" id="<?php echo $row['code']; ?>"></span></a>
                               <?php if($row['totalused']==0){ ?> <a href="javascript:deleteDiscount('<?php echo commonHelperGetPageUrl('dashboard-list-discount', $eventId.'&'.$row['id']); ?>')"><span class="icon-delete"></span><?php } ?>
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
