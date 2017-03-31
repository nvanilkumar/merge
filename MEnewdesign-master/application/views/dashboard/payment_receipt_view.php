<div class="rightArea">
    <div class="heading float-left">
        <h2>Cyber Receipt: <span> <?php echo $eventTitle; ?></span></h2>
    </div>
    <div class="clearBoth"></div>
    <!--Data Section Start-->
    <div class="paymentSec">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" data-tablesaw-mode="swipe" data-tablesaw-minimap>
            <thead>
                <tr>
                    <th scope="col" data-tablesaw-priority="persist">Receipt</th>
                    <th scope="col" data-tablesaw-priority="2">Action</th>
                    <th scope="col" data-tablesaw-priority="2">Date</th>
                    <th scope="col" data-tablesaw-priority="2">Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $receiptCount = count($paymentreceipt);
                if ($receiptCount > 0) {
					
					foreach ($paymentreceipt as $key => $val) 
					{
						if(isset($val['chequefilePath']) && strlen($val['chequefilePath']) > 0)
						{
                         ?>
                         <tr>
                         	<td>Cheque Receipt</td>
                            <td><a href="<?php echo $val['chequefilePath']; ?>" target="_blank">View | </a>
                                        <a href="<?php echo commonHelperGetPageUrl('download-file','','?filePath='.$val['chequefilePath']); ?>">Download</a></td>               
                            <td><span><?php echo   convertTime($val['cts'], DEFAULT_TIME_ZONE,TRUE); ?></span></td>
                            <td><?php echo $val['note']; ?>. </td>
                         </tr> 
                         <?php
						}
                    }
					
					
					foreach ($paymentreceipt as $key => $val) 
					{
						if(isset($val['paymentadvicefilePath']) && strlen($val['paymentadvicefilePath']) > 0)
						{
                        ?> 
                        <tr>
                            <td>Payment Receipt</td>
                            <td><a href="<?php echo $val['paymentadvicefilePath']; ?>" target="_blank">View | </a> 
                                        <a href="<?php echo commonHelperGetPageUrl('download-file','','?filePath='.$val['paymentadvicefilePath']); ?>">Download</a></td> 
                            <td><span><?php echo convertTime($val['cts'], DEFAULT_TIME_ZONE,TRUE); ?></span></td>
                            <td><?php echo $val['note']; ?>. </td>
                        </tr> 
                        <?php
						}
                     }
					 
					 
					 foreach ($paymentreceipt as $key => $val) 
					 {
						 if(isset($val['cyberrecieptfilePath']) && strlen($val['cyberrecieptfilePath']) > 0)
						 {
                         ?> 
                         <tr>
                             <td>Cyber Receipt</td> 
                             <td><a href="<?php echo $val['cyberrecieptfilePath']; ?>" target="_blank">View | </a> 
                                            <a href="<?php echo commonHelperGetPageUrl('download-file','','?filePath='.$val['cyberrecieptfilePath']); ?>">Download</a></td> 
                             <td><span><?php echo convertTime($val['cts'], DEFAULT_TIME_ZONE,TRUE); ?></span></td>
                             <td><?php echo $val['note']; ?>. </td>
                     	 </tr> 
            			 <?php
						 }
					}
				 } else { ?>
                    <tr>     </tr>   </tbody>
            </table>
            <br>
            <div class="db-alert db-alert-info">
                <strong>&nbsp;&nbsp; Sorry, No Payment Reciepts found for this Event.</strong> 
            </div>              
<?php } ?>       
    </div>
</div>