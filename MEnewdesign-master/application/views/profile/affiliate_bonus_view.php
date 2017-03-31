    <div class="rightArea">

      <?php if (isset($messages)) { ?>
        <div id="alertMessage" <?php if($status) { ?>
             class="db-alert db-alert-success flashHide" <?php } else { ?> 
             class="db-alert db-alert-danger flashHide" <?php } ?> >
            <strong>&nbsp;&nbsp;  <?php echo $messages; ?></strong> 
        </div>
    <?php } ?> 
      <div class="heading">
        <h2>Affiliate Bonus</h2>
      </div>
      <!--Data Section Start-->
         <div class="tablefields">
             <form name="alerts" method="post" is="alerts" action="">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-center">
          <thead>
            <tr>
              <th>S.no</th>
              <th>Event Data</th>
              <th>Transaction Count</th>
              <th>Commission Earned(INR)</th>
            </tr>
          </thead>
          <tbody>
              <?php if(count($affiliateBonusDetails)>0){
                  $i=1;
                  $totalCommission=$totalSaleQty=0;
                        foreach ($affiliateBonusDetails as $key => $value) {
                  ?>
                            <tr>
                              <td><?php echo $i++;?></td>
                              <td><a href="<?php echo $value['url'];?>" target="_blank"><?php echo $value['title'];?></a></td>
                              <td class="alert-chkbtn"><?php echo count($value['transactions']);?></td>
                              <td class="alert-chkbtn"><?php echo $value['commission'];?></td>  
                            </tr>
                        <?php $totalCommission+=$value['commission'];
                              $totalSaleQty+=count($value['transactions']);
                        } ?>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                                <td><?php echo $totalSaleQty;?></td>
                                <td><?php echo 'INR '.$totalCommission;?></td>
                            </tr>
                        
                      <?php  }else{ ?>
                     <tr>
                        <td colspan="4"><div class="db-alert db-alert-info">No data found.</div></td>
                    </tr>
               <?php }?>
        </tbody>
        </table>
<!--        <div class="float-right">
          <input type="submit" class="createBtn float-right" name="alertForm" value="Save & Exit"/>
        </div>-->
             </form>
        
        </div>
    </div>
