<?php
$eventName = (isset($eventDetail['eventName'])) ? $eventDetail['eventName'] : '';
$startDateTime = isset($eventDetail['startDateTime']) ? allTimeFormats(convertTime($eventDetail['startDateTime'],$eventTimeZoneName,TRUE),15) : '';
$eventUrl = isset($eventDetail['url']) ? commonHelperGetPageUrl("preview-event", $eventDetail['url']) : "";
$viralTicketSuccessMessage = $this->customsession->getData('viralTicketSuccessMessage');
$this->customsession->unSetData('viralTicketSuccessMessage');
?>
<div class="clearL"></div>
<div class="rightArea">
    <?php if ($viralTicketSuccessMessage) { ?>
        <div id="viralTicketSuccessMessage" class="db-alert db-alert-success flashHide">
            <strong>  <?php echo $viralTicketSuccessMessage; ?></strong> 
        </div>
    <?php } ?>
    <div class="heading float-left">
        <h2><?php echo $eventName; ?></h2>
        <ul>
            <li><span class="icon-calendar"></span><?php echo $startDateTime; ?></li>
            <li><span class="icon-locator"></span><?php echo $eventDetail['venueName']; ?></li>
            <li><a href="<?php echo $eventUrl . '?ucode=organizer'; ?>" target="_blank"><span class="mce-ico mce-i-link"></span><u><?php echo $eventUrl . '?ucode=organizer'; ?></u></a></li>
        </ul>
    </div>
    <div class="fs-settings-buttons">
        <?php if ($isCurrentEvent) { ?>
            <a href="<?php echo commonHelperGetPageUrl('edit-event', $eventId); ?>"><button type="button" class="Btn"><span class="icon-edit"></span>Edit</button></a>
        <?php } ?>
        <a href="<?php echo commonHelperGetPageUrl('event-preview','','?view=preview&eventId=' . $eventId); ?>" target="_blank"> <button type="button" class="Btn "><span class="icon-preview"></span>Preview</button></a>
    </div>
    <div class="clearBoth"></div>
    <!--Graph Section Start-->
    <div class="graphSec">
        <div class="Box1">
            <div class="fixedBox">
                <h2>ticket transaction</h2>
                <div class="fs-sort-options"> <span class="sort">Sort By:</span>
                	<div class="fs-select-wrapper">
	                    <select id="dateReportType" >
	                        <option <?php
	                        if ($filterType == 'all') {
	                            echo "selected";
	                        }
	                        ?> value="all">All Time</option>
	                        <option <?php
	                        if ($filterType == 'month') {
	                            echo "selected";
	                        }
	                        ?> value="month">This Month</option>
	                        <option <?php
	                            if ($filterType == 'thisweek') {
	                                echo "selected";
	                            }
	                            ?> value="thisweek">This Week</option>
	                        <option <?php
	                        if ($filterType == 'yesterday') {
	                            echo "selected";
	                        }
	                            ?> value="yesterday">Yesterday</option>
	                        <option <?php
	                        if ($filterType == 'today') {
	                            echo "selected";
	                        }
	                            ?> value="today">Today</option>
	                    </select>
	                    <span class="icon-arrow"></span>
                	</div>  
                </div>
				<div class="clearBoth"></div>
                <div class="fs-Box1-content">
                	<div class="defaultDroplist">
	                    <label class="icon-downarrow">
	                        <!--			  id="graph" -->
	                        <select id="ticketType">
	                            <option <?php
	                            if ($ticketId == 0) {
	                                echo "selected";
	                            }
	                            ?>  value="0">All</option>
								<?php foreach ($ticketList as $ticket) {
								    ?> <option <?php
								    if ($ticketId == $ticket['id']) {
								        echo "selected";
								    }
								    ?> value="<?php echo $ticket['id'] ?>"><?php echo $ticket['name'] ?></option><?php }
								?>
	                        </select>
	                    </label>
	                </div>
	                <div class="weekGraph" id="chartContainer">
	                </div>
                <!--            <div class="resources" style=" display: none;">
                              <div class="VIP" > VIP
                                <canvas id="canvas" height="180" width="500"></canvas>
                              </div>
                            </div>-->
                </div><!-- end of fs-Box1-content -->             
            </div>
        </div>
        <div class="Box1">
            <div class="fixedBox">
                <h2>sale</h2>
				<div class="fs-Box1-content">
	                <div class="saleBox">
	                    <h4>Tickets Sale</h4>
	                    <span id="ticketSoldTotal" class="green"><?php echo $totalSaleData['quantity']; ?></span> 
	                </div>
	                <div id="ticketAmountDiv" class="saleBox">
	<?php 
        $amountIsNotSet=true;
	foreach ($totalSaleData['currencySale'] as $currCode => $currecySale) {
            if (!empty($currCode)) {
                 $amountIsNotSet=false;
                 ?>
                              
	                        <h4>total amount <?php echo '('.$currCode.')'; ?></h4>
	                        <span class="green"><?php echo $currecySale; ?></span> 
             <?php } } 
             if($amountIsNotSet){
             ?>
                                <h4>total amount</h4>
	                        <span class="green">0</span> 
             <?php }?>
	                </div>
				</div><!-- end of fs-Box1-content --> 
            </div>
        </div>
        <!-- Second Row-->
        <div class="Box1">
            <div class="fixedBox">
                <h2>sale efforts</h2>
                <div class="fs-sort-options"> 
                	<span class="sort">Sort By:</span>
                	<div class="fs-select-wrapper">
	                    <select id="menulist">
	                        <option value="all">All Time</option>
	                        <option value="month">This Month</option>
	                        <option value="thisweek">This Week</option>
	                        <option value="yesterday">Yesterday</option>
	                        <option value="today">Today</option>
	                    </select>
	                    <span class="icon-arrow"></span>
                	</div>   
                </div>
                <div class="fs-Box1-content">
	                <div class="defaultDroplist">
	                    <label class="icon-downarrow">
	                        <select id="salesType" name="salesType">
                            	<option value="meraevents">MeraEvents Sales</option>
	                            <option value="organizer">Organizer Sales</option>
	                            <option value="promoter">Promoter Sales</option>
	                            <option value="offlinepromoter">Offline Promoter Sales</option>
	                            <option value="boxoffice">Box Office Sales</option>
	                            <option value="viral">Viral Ticketing Sales</option>
	                            
	                        </select>
	                    </label>
	                </div>
	                <div>
						<!-- <div class="memberDetail"> <span>Andy.K</span> <span>Code#301</span> </div>-->
	                </div>
	                <div>
	                    <div class="saleBox2">
	                        <h4>Tickets sold</h4>
	                        <span id="salesEffortTickets" class="gray"><?php echo $salesEffortData['quantity']; ?></span> 
	                    </div>
	                    <div id="salesEffortAmounts" class="saleBox2">
	<?php
	if (is_array($salesEffortData['currencySale'])) {
	    foreach ($salesEffortData['currencySale'] as $key => $value) {
	        ?>
                                <div class="priceDiv"> <h4>total amount (<?php echo $key; ?>)</h4>
                                           <span class="gray"><?php echo $value; ?></span> </div>
	    <?php
	    }
	} else {
	    ?>
	
	                           <div class="priceDiv">  <h4>total amount</h4>
	                            <span class="gray">0</span>  </div>
	                        <?php } ?>
	                    </div>
	                </div>
                </div><!-- end of fs-Box1-content -->   
            </div>
        </div>
        
        
        <div class="Box1">
            <div class="fixedBox">
                <h2>Collaboration</h2>
                <div class="fs-Box1-content">
	                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <colgroup>
                                <col width="33.33333%">
                                <col width="33.33333%">
                                <col width="33.33333%">
                            </colgroup>
	                    <thead>
	                        <tr>
	                            <th>module</th>
	                            <th>member</th>
	                            <th>status</th>
	                        </tr>
                            </thead>
                            <tbody> 
                                <?php   $totalCollaborators=count($collaboratorList);
                                if($totalCollaborators > 0){
                                ?>
	                            <?php foreach ($collaboratorList as $value) { ?>
		                            <tr>
		                                <td><?php
		                                    $data = '';
		                                    $modules = explode(',', $value['modules']);
		                                    foreach ($modules as $key => $value1) {
		                                        $data.=ucfirst($value1) . '<br/>';
		                                    }
		                                    echo $data;
		                                    ?></td>
		                                <td><?php echo $value['name']; ?></td>
		                                <td><?php
		                                    $className = 'greenBtn';
		                                    $buttonValue = 'Active';
		                                    if ($value['status'] == 0) {
		                                        $className = 'orangrBtn';
		                                        $buttonValue = 'Inactive';
		                                    }
		                                    ?>
		                                    <button type="button" class="btn <?php echo $className; ?>  defaultCursor"><?php echo $buttonValue; ?></button>
		                                </td>
		                            </tr>
								<?php }}else{ ?>
                                                                    <td colspan="3"> <div class="db-alert db-alert-info"> <?php echo 'No collaborators'; ?></div></td>
                                                              <?php  } ?>
	
	                    </tbody>
	                </table>
				</div><!-- end of fs-Box1-content -->
            </div>
        </div>
        
          <?php   $totaltickets=count($ticketList);
                                if($totaltickets > 0){
                                ?>
                <div class="fixedBox Box1" style="width:100% !important">
            <div class="fixedBox">
                <h2>Tickets 
                </h2>
               
                <div class="fs-Box1-content overflow">
	                <table width="100%" border="0" cellspacing="0" cellpadding="0" data-tablesaw-mode="swipe" data-tablesaw-minimap="" class="tablesaw-swipe">
	                    <thead>
	                        <tr>
	                            <th>Name</th>
	                            <th>Price</th>
	                            <th>Start/ End Dates</th>
	                            <th>Tax(es)</th>
	                            <th>Sold</th>
	                            <th>Status</th>
	                        </tr>
                            </thead>
                            <tbody> 
                              
	                            <?php foreach ($ticketList as $value) { ?>
		                            <tr>
		                             	<td><?php echo $value['name']; ?></td>
                                                <td>
                                                 <?php 
                                                if($value['type']=='paid' || $value['type']=='addon'){
                                                    echo $value['currencyCode']." ".$value['price'];
                                                }elseif($value['type']=='donation'){
                                                   echo "---".'<br> ('.$value['currencyCode'].')';                                                             
                                                } elseif($value['type']=='free'){
                                                       echo   '0';                                                  
                                                }                                                       
                                                ?> 
                                                </td>
		                                 <td><?php echo "Start: ".date('d-m-Y',strtotime($value['startDate']))."</br>End: ".date('d-m-Y',strtotime($value['endDate'])); ?></td>
		                                <td>
		                                <?php 
		                                if(!empty($taxDetails[$value['id']])){
		                                foreach($taxDetails[$value['id']] as $key => $v){
		                                	echo $v['label'].":".$v['value']." %</br>";
		                                }
		                                }else{echo '--';}
		                                ?>
		                                
		                                </td>
		                               
		                                <td><?php echo $value['totalSoldTickets']; ?></td>
		                                <td><?php
		                                    $className = 'greenBtn';
		                                    $buttonValue = 'Active';
		                                    if ($value['soldout'] == 1||($value['quantity']<=$value['totalSoldTickets'])) {
		                                        $className = 'orangrBtn';
		                                        $buttonValue = 'SoldOut';
		                                    }else if($value['displaystatus']==1){
		                                    	$className = 'orangrBtn';
		                                    	$buttonValue = 'Not to Display';
		                                    }
		                                    else if(strtotime($value['endDate'])<strtotime((convertTime(allTimeFormats('',11), $eventTimeZoneName, TRUE)))){
                                                        $className = 'orangrBtn';
		                                        $buttonValue = 'Sale Date Ended';
                                                    }
		                                    ?>
		                                    <button type="button" class="btn <?php echo $className; ?>  defaultCursor"><?php echo $buttonValue; ?></button>
		                                </td>
		                            </tr>
		                            <?php }?>
	                    </tbody>
	                </table>
	                <?php if(count($ticketList)>0){?>
	                <div style="float:right;">
	                <span id="sendsuccess" style="float: left; padding: 20px 10px 0 0;"></span>
	                <a href="javascript:;" class="submitBtn createBtn float-right"  id="send_ticketslist" style="margin:20px 5px;   float: right;">Send Email</a>
	                </div>
	                <?php }?>
				</div><!-- end of fs-Box1-content -->
				<?php }?>
            </div>
        </div>
        
    </div>
</div>
<input type="hidden" name="eventId" id="eventId" value="<?php echo $eventId; ?>"/>
<script>
var api_reportsGetWeekwiseSales = "<?php echo commonHelperGetPageUrl('api_reportsGetWeekwiseSales')?>";
var api_reportsSalesEffortReports = '<?php echo commonHelperGetPageUrl('api_reportsSalesEffortReports');?>';
var api_sendTicketsoldDataToorganizer = '<?php echo commonHelperGetPageUrl('api_sendTicketsoldDataToorganizer');?>';
    var filterType = '<?php echo $filterType; ?>';
    var weekWiseJSONData = '<?php echo json_encode($weekWiseData); ?>';
    // var currencyCode='<?php echo $currencyCode . ' '; ?>';

</script>
<?php 
if($totaltickets>0){?>
<script>
$(function(){
    $('#send_ticketslist').on('click', function () {
        var pageUrl = api_sendTicketsoldDataToorganizer;
        var eventId = $('#eventId').val();
        var userEmail = "<?php echo $this->customsession->getData('userEmail');?>";
        var userId = "<?php echo $this->customsession->getData('userId');?>";
        var input = 'eventId=' + eventId + '&userEmail=' + userEmail + '&userId='+userId;
        var method = 'POST';
        var dataFormat = 'JSON';
    	$("#sendsuccess").html('<img id="success-img" src="../../images/me-loading.gif">');
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
        function callbackFailure(result) {
//            $("#sendsuccess").html(result.responseJSON.response.messages);
               alert("Email not Sent. Try later");
        }
        function callbackSuccess(result) {
        	$("#sendsuccess").html('Mail sent Successfully');
        	setTimeout(function(){
        		$("#sendsuccess").html('');
            	},2000);
            // window.location.href = site_url+result.response.filepath;
        }
    });
    $('.graphSec ,.db_Eventbox').matchHeight();

});

</script>
<?php }?>


<script src="<?php echo $this->config->item('js_public_path') . 'dashboard/fusioncharts/js/fusioncharts.js'; ?>"></script>
<script src="<?php echo $this->config->item('js_public_path') . 'dashboard/fusioncharts/js/themes/fusioncharts.theme.fint.js'; ?>"></script>