<div class="rightArea">
             <?php
        $offlinePromoterMessage = $this->customsession->getData('offlinePromoterFlashMessage');
        $this->customsession->unSetData('offlinePromoterFlashMessage');
        if ($offlinePromoterMessage) { ?>
        <div class="db-alert db-alert-success flashHide">
            <strong>  <?php echo $offlinePromoterMessage; ?></strong> 
        </div>
    <?php } ?>
    <?php if(isset($messages)){?>
                <div class="db-alert db-alert-danger flashHide">
            <strong>  <?php echo $messages;?></strong> 
        </div>                 
        <?php }?>  
      <div class="heading float-left">
        <h2>Promoters List: <span> <?php echo $eventName; ?></span></h2>
      </div>    
      <div class="clearBoth"></div>
      <div class="float-right"> <a href="<?php echo commonHelperGetPageUrl('dashboard-add-offline-promoter', $eventId); ?>" class="createBtn float-left font14"><span class="icon-add pinkBg"></span>Add New Offline Promoter</a> </div>
      <div class="clearBoth"></div>
      <div class="tablefields">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" data-tablesaw-mode="swipe" data-tablesaw-minimap>
          <thead>
            <tr>
              <th scope="col" data-tablesaw-priority="persist">Promoter Name</th>
              <th scope="col" data-tablesaw-priority="3">Tickets Sold</th>
              <th scope="col" data-tablesaw-priority="3">Tickets Data</th>
              <th scope="col" data-tablesaw-priority="3">Amount</th>
              <th scope="col" data-tablesaw-priority="3">Current Status</th>
              <th scope="col" data-tablesaw-priority="3">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            if ($offlinePromotorList['status'] && $offlinePromotorList['response']['total'] == 0) { ?>
                        <tr><td colspan="6">
                                <div class="db-alert db-alert-info">                    
                                    <strong> <?php echo $offlinePromotorList['response']['messages'][0]; ?></strong> 
                                </div>
                            </td> </tr> <?php
            } else if ($offlinePromotorList['status'] && $offlinePromotorList['response']['total'] > 0) {  
                
                $i = 1;
                foreach($offlinePromotorList['response']['offlinePromotorList'] as   $row){ 
                 $reportsLink = commonHelperGetPageUrl('dashboard-transaction-report', $row['eventid'] . '&summary&offline&1','?promotercode='.$row['code']);
                 
           $class = 'odd';
                        if ($i % 2 == 0) {
                            $class = '';
                        }
                ?>
             <tr <?php echo $class; ?> >
              <td><?php echo $row['name']; ?></td>
               
              <td> <?php if($row['totalsold'] !=''){?> <a href="<?php echo $reportsLink; ?>" </a><?php  echo $row['totalsold'];}else{echo '0';}  ?></td>

              <td>
               <?php 
                foreach($row['ticketData'] as   $tdata){
                     $ticketsreportsLink = commonHelperGetPageUrl('dashboard-transaction-report', $row['eventid'] . '&summary&offline&1','?ticketid='.$tdata['ticketid']);
                    if($tdata['quantity'] > 0) {
                    ?>
                    <?php echo $tdata['ticketname'];?> =><a href="<?php echo $ticketsreportsLink.='&promotercode=' . $row['code']; ?>">Qty:<?php echo $tdata['quantity'];?> (Amount:<?php echo $tdata['totalamount'];?>)</a><br/>
               <?php }else{                
               }}
                ?>
              </td>
              
              <td>
              <?php if($row['finalAmount'] !=''){ ?> <a href="<?php echo $reportsLink; ?>"><?php echo $row['finalAmount']; ?></a> <?php }else{echo '0';}  ?>
              </td>
              <td><button onclick="changeStatus('<?php echo $row['id']; ?>')" type="button" <?php if ($row['status'] == 1) { ?> class="btn greenBtn" <?php } else { ?> class="btn orangrBtn" <?php } ?>id='<?php echo $row['id']; ?>'><?php if ($row['status'] == 1) {
                    echo 'active';
                } else {
                    echo 'inactive';
                } ?></button></td>
              <td> <a href="<?php echo commonHelperGetPageUrl('dashboard-edit-offline-promoter',$eventId.'&'.$row['id']); ?>"><span class="icon-edit"></span></a> </td>
            </tr>
            <?php $i++;} } ?>
          </tbody>
        </table>
      </div>
    </div>