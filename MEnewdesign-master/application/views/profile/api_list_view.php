<div class="rightArea">
    <div class="heading">
        <h2>Api List <span></span></h2>
    </div>
    <div style="width:50%" class="float-left"> </div>
    <div class="float-right"> <a href="<?php echo commonHelperGetPageUrl('createApp');?>" class="createBtn float-left font14"><span class="icon-add pinkBg"></span>Create New API</a> </div>
    <div class="clearBoth"></div>
    <div class="refundSec discount">
        <table width="100%" border="1" cellspacing="0" cellpadding="0" data-tablesaw-mode="swipe" data-tablesaw-minimap>
            <thead>
                <tr>
                    <th scope="col" data-tablesaw-priority="2" style="padding-left:2%;text-align:left; width:33%;">APP Name</th>
                    <th scope="col" data-tablesaw-priority="2" style="text-align:left;width:50%;">Keys</th>
                    <th scope="col" data-tablesaw-priority="2" style="text-align:left;width:10%;">Action</th>
                </tr>
            </thead>
            <tbody>
               
<!--                 <tr><td colspan='6'>   
                <div id="" class="db-alert db-alert-info">                    
                    <strong></strong> 
                </div>
        </td></tr>-->
                         <?php 
                         if($appList['response']['total'] > 0){
                             $appDetails=$appList['response']['appDetails'];
                             foreach($appDetails as $details){
                         
                         ?>    
                        <tr >
                            <td style="text-align:left !important;"><?php echo $details['app_name'] ?></td>
                            <td style="text-align:left !important;">
                                <span>Client ID : <?php echo $details['client_id'] ?></span><br>
                                <span>Client Secret Key : <?php echo $details['client_secret'] ?> </span>
                            </td>
                            <td style="text-align:left !important;">
                                <a href="<?php echo commonHelperGetPageUrl('updateApp')."/".$details['id'];?>"><span class="icon-edit" id=""></span></a>
                            </td>
                        </tr>
                         <?php  } 
                         }
                         ?>
                    

            </tbody>
        </table>
    </div>
</div>
