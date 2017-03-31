<div class="page-container">
    <div class="wrap">
        <div class="container"> 
            <!-- Main component for a primary marketing message or call to action -->
            <?php include_once('includes/elements/event_detail_top.php');?>
            <!--Step2-->
            <?php if($isExisted) { ?>
                <div class="innerPageContainer" style="margin-bottom: 30px;">
                    <h2 class="pageTitle">Registration Information</h2>
                    <div class="row">
                        <div class="col-md-12">
                            Looks like you used the browser back button after completing your previous transaction to buy another ticket!<br>
                            To buy another ticket for this event go to
                            <a href="<?php echo $eventData['eventUrl'];?>">Preview Event</a> and continue from there.
                            <br><br>Contact support at support@meraevents.com or +91 - 9396 555 888 for assistance.
                        </div>
                    </div>
                </div>
            <?php }elseif($userMismatch) { ?>
                <div class="innerPageContainer" style="margin-bottom: 30px;">
                    <h2 class="pageTitle">Registration Information</h2>
                    <div class="row">
                        <div class="col-md-12">
                            You are not authorized to complete this transaction with this login. Please login with <span style="font-weight: bold;font-size: 18px;"><?php echo $incompleteEmail;?></span><br/> or try out with new transaction
                            <a href="<?php echo $eventData['eventUrl'];?>">Preview Event</a>.
                            <br><br>Contact support at support@meraevents.com or +91 - 9396 555 888 for assistance.
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="innerPageContainer">
                    <h2 class="pageTitle">Registration Information</h2>
                    <div class="row">
                        <div class="col-md-8">
                            
                            <div id="booking_message_div" style="text-align: center;color: red;">
                                <?php
                                    $sessionMessage = $this->customsession->getData('booking_message');
                                    $this->customsession->unSetData('booking_message');
                                    if(!is_null($sessionMessage)) {
                                        echo $sessionMessage;
                                    }
                                ?>    
                            </div>
                            
                            <div class="row">
                                <?php
                                    $formCount = 1;
                                    if($collectMultipleAttendeeInfo == 1) {
                                        $formCount = array_sum($ticketData);
                                    }
                                ?>
                                <div class="col-xs-12 regInfo">
                                    <form action="" name="ticket_registration" id="ticket_registration" enctype="multipart/form-data">
                                        <input type="hidden" name="eventId" id="eventId" value="<?php echo $eventData['id'];?>">
                                        <input type="hidden" name="orderId" value="<?php echo $orderLogId; ?>" />
                                        <input type="hidden" name="paymentGateway" id="paymentGateway">
                                        <input type="hidden" name="paymentGatewayId" id="paymentGatewayId">
                                        
                                        <?php foreach($ticketData as $ticketId => $ticketCount) { ?>
                                            <input type="hidden" name="ticketArr[<?php echo $ticketId; ?>]" id="ticketArr[<?php echo $ticketId; ?>]" value="<?php echo $ticketCount; ?>" />
                                        <?php } ?>
                                        
                                        <input type="hidden" name="eventSignupId" id="eventSignupId" value="<?php echo $eventSignupId;?>">
                                        
                                        <?php
                                            $attendeeCount = 1;
                                            $addonTextNotShown=true;
                                            $hiddenLocationArray = array('Country','State','City');
                                            $localityCount = 0;
                                            $addonTicketLevelFields=false;
                                            $addonData=$normalTickets=array();
                                            //print_r($customFieldsArray);exit;
                                            foreach($ticketData as $ticketId => $ticketCount) {
                                                if(in_array($ticketId, $addonArray)){
                                                    $addonData[$ticketId]=$ticketCount;
                                                }else{
                                                    $normalTickets[$ticketId]=$ticketCount;
                                                }
                                            }
                                            if($collectMultipleAttendeeInfo == 1) {
                                                foreach ($addonData as $ticketId => $ticketCount) {
                                                    $customDataField = $customFieldsArray[$ticketId];
                                                    foreach ($customDataField as $customField) {
                                                        if($customField['fieldlevel']=='ticket'){
                                                            $addonTicketLevelFields=true;break;
                                                        }
                                                    }
                                                    if($addonTicketLevelFields){
                                                        break;
                                                    }
                                                }
                                            }
                                            $ticketData=($normalTickets+$addonData);
                                            foreach($ticketData as $ticketId => $ticketCount) {
                                                if($collectMultipleAttendeeInfo == 0) {
                                                    $ticketCount = $formCount;
                                                }
                                                for($i=1 ; $i <= $ticketCount ; $i++) {?>
                                                    <div class="registration_field_group_<?php echo $attendeeCount;?>">
                                                    
                                                    <?php if($formCount > 1 && !in_array($ticketId, $addonArray)) { ?>
                                                        <h4>Attendee <?php echo $attendeeCount;?>
                                                            <?php if($collectMultipleAttendeeInfo == 1) {?>
                                                                (Ticket: <?php echo $calculationDetails['ticketsData'][$ticketId]['ticketName'];?>)
                                                            <?php } ?>
                                                        </h4>
                                                        <hr>
                                                    <?php }elseif($addonTicketLevelFields && in_array($ticketId, $addonArray) && $addonTextNotShown){
                                                        $addonTextNotShown=false;
                                                        echo '<h4>Add-on Items</h4>';
                                                    }
                                                        /*if($addonTicketLevelFields && in_array($ticketId, $addonArray)) {
                                                            ?>
                                                                (Ticket: <?php echo $calculationDetails['ticketsData'][$ticketId]['ticketName'];?>)
                                                            <?php
                                                        }*/
                                                    ?>
                                                        
                                                        <!-- Custom Fields startes here -->
                                                        <?php
                                                            $customDataField = $customFieldsArray[$ticketId];
                                                            
                                                            $localityMandatory = false;
                                                            foreach($customDataField as $customField) {
                                                                if(in_array($customField['fieldname'],$hiddenLocationArray) && $customField['fieldmandatory'] == 1) {
                                                                    $localityMandatory = true;
                                                                }
                                                            }
                                                            
                                                            foreach($customDataField as $customField) {
                                                                if(($customField['fieldlevel'] == 'event' && !in_array($ticketId, $addonArray)) || ($collectMultipleAttendeeInfo == 1 && $customField['fieldlevel'] == 'ticket')) {
                                                                    $mandatoryClass = '';
                                                                    $trimmedFieldName = str_replace(" ", "", preg_replace("/[^A-Za-z0-9\s\s+]/", "", $customField['fieldname']));
                                                                    $fieldId = $customField['id'];
                                                                    $fieldName = $trimmedFieldName.$attendeeCount;
                                                        ?>
                                                                    <div class="form-group">
                                                                        <input type="hidden" name="formTicket<?php echo $attendeeCount;?>" value="<?php echo $ticketId;?>">
                                                                        <?php
                                                                            $multipleValueFieldArr = array('dropdown','radio','checkbox');
                                                                            
                                                                            if(((in_array($customField['fieldtype'],$multipleValueFieldArr) && count($customField['customFieldValues'][$fieldId]) > 0) || !in_array($customField['fieldtype'],$multipleValueFieldArr)) && (($geoLocalityDisplay == 1 && !in_array($trimmedFieldName,$hiddenLocationArray) || $geoLocalityDisplay == 0))) { ?>
                                                                                <label for="exampleInputtext1"><?php echo $customField['fieldname'];
                                                                                    if($customField['fieldmandatory']) { ?>
                                                                                        <span style='color:red'>*</span>
                                                                                    <?php
                                                                                        $mandatoryClass = 'mandatory_class';
                                                                                    } ?>
                                                                                </label>
                                                                            <?php }
                                                                                /* Label for the first Geo Location enabled field */
                                                                                elseif($localityCount == 0 && $geoLocalityDisplay == 1) {
                                                                                    ?>
                                                                                        <label for="exampleInputtext1"><?php echo DB_LOCALITY;
                                                                                            if($localityMandatory) { ?>
                                                                                                <span style='color:red'>*</span>
                                                                                            <?php
                                                                                                $mandatoryClass = 'mandatory_class';
                                                                                            } ?>
                                                                                        </label>
                                                                                    <?php 
                                                                                }
                                                                            ?>
                                                                        
                                                                        <?php if($customField['fieldtype'] == 'textarea') { ?>
                                                                            
                                                                            <?php if((isset($userData[$trimmedFieldName]) && $userData[$trimmedFieldName] != '' && $attendeeCount == 1) || count($indexedAttendeedetailList)>0) { ?>
                                                                                    <textarea class="form-control customValidationClass <?php echo $mandatoryClass;?>" 
                                                                                      name="<?php echo $fieldName;?>" id="<?php echo $trimmedFieldName.'_'.$attendeeCount;?>" 
                                                                                      data-customFieldId="<?php echo $customField['id'];?>"
                                                                                       data-originalName="<?php echo $customField['fieldname'];?>"
                                                                                      data-customvalidation="<?php echo $customField['customvalidation'];?>" ticketid="<?php echo $ticketId;?>"><?php echo count($indexedAttendeedetailList)>0?$indexedAttendeedetailList[$trimmedFieldName][$attendeeCount]:trim($userData[$trimmedFieldName]);?></textarea>
                                                                            <?php } else { ?>
                                                                                    <textarea class="form-control customValidationClass <?php echo $mandatoryClass;?>" 
                                                                                      name="<?php echo $fieldName;?>" id="<?php echo $trimmedFieldName.'_'.$attendeeCount;?>" 
                                                                                      data-customFieldId="<?php echo $customField['id'];?>"
                                                                                       data-originalName="<?php echo $customField['fieldname'];?>"
                                                                                      data-customvalidation="<?php echo $customField['customvalidation'];?>"  ticketid="<?php echo $ticketId;?>"></textarea>
                                                                            <?php } ?>
                                                                        <?php } elseif($customField['fieldtype'] == 'textbox') { ?>

                                                                                <?php if($geoLocalityDisplay == 0 || ($geoLocalityDisplay == 1 && !in_array($trimmedFieldName,$hiddenLocationArray))) { ?>
                                                                                    
                                                                                    <input type="text" class="form-control customValidationClass <?php echo $mandatoryClass;?>" 
                                                                                               name="<?php echo $fieldName;?>" 
                                                                                               id="<?php echo $trimmedFieldName.'_'.$attendeeCount;?>" 
                                                                                               data-customFieldId="<?php echo $customField['id'];?>"
                                                                                                data-originalName="<?php echo $customField['fieldname'];?>"
                                                                                               data-customvalidation="<?php echo $customField['customvalidation'];?>"  ticketid="<?php echo $ticketId;?>"
                                                                                               value="<?php if(count($indexedAttendeedetailList)>0) echo $indexedAttendeedetailList[$trimmedFieldName][$attendeeCount];elseif(isset($userData[$trimmedFieldName]) && $userData[$trimmedFieldName] != '' && $attendeeCount == 1)
                                                                                               echo $userData[$trimmedFieldName];?>">
                                                                                               
                                                                            <!-- If the Locality field enebled then hiding the Country, State, City fields starts here -->
                                                                                <?php } elseif($geoLocalityDisplay == 1 && in_array($trimmedFieldName,$hiddenLocationArray)) { ?>
                                                                                        <input type="hidden" class="form-control customValidationClass <?php echo $mandatoryClass;?>" 
                                                                                               name="<?php echo $fieldName;?>"
                                                                                               id="<?php echo $trimmedFieldName.'_'.$attendeeCount;?>" 
                                                                                               data-customFieldId="<?php echo $customField['id'];?>"
                                                                                                data-originalName="<?php echo $customField['fieldname'];?>"
                                                                                               data-customvalidation="<?php echo $customField['customvalidation'];?>" ticketid="<?php echo $ticketId;?>"
                                                                                               value="<?php if(count($indexedAttendeedetailList)>0) echo $indexedAttendeedetailList[$trimmedFieldName][$attendeeCount]; elseif($attendeeCount == 1) echo $userData[$trimmedFieldName];?>">
                                                                                        
                                                                                        <!-- In place of Country, placing the Locality field -->   
                                                                                        <?php if($localityCount++ == 0) { ?>
                                                                                            <input type="text" class="form-control <?php echo $mandatoryClass.' localityAutoComplete';?>" 
                                                                                               name="<?php echo DB_LOCALITY.$attendeeCount;?>"
                                                                                                data-originalName="<?php echo $customField['fieldname'];?>"
                                                                                               id="<?php echo DB_LOCALITY.'_'.$attendeeCount;?>" 
                                                                                               value="<?php if($attendeeCount == 1) echo $userData[DB_LOCALITY];?>">
                                                                                               
                                                                                               <span class="<?php echo DB_LOCALITY.'_'.$attendeeCount;?>Err"></span>
                                                                                        <?php } ?>
                                                                                        
                                                                                <?php } ?>
                                                                            <!-- If the Locality field enebled then hiding the Country, State, City fields ends here -->
                                                                        
                                                                        <?php } elseif($customField['fieldtype'] == 'date') { ?>
                                                                        
                                                                            <input type="text" class="form-control customValidationClass <?php echo $mandatoryClass;?> form-datepicker" 
                                                                                   name="<?php echo $fieldName;?>" id="<?php echo $trimmedFieldName.'_'.$attendeeCount;?>" 
                                                                                   data-customFieldId="<?php echo $customField['id'];?>"
                                                                                    data-originalName="<?php echo $customField['fieldname'];?>"
                                                                                   data-customvalidation="<?php echo $customField['customvalidation'];?>" ticketid="<?php echo $ticketId;?>"
                                                                                   value="<?php echo count($indexedAttendeedetailList)>0?$indexedAttendeedetailList[$trimmedFieldName][$attendeeCount]:'';?>"
                                                                                   >
                                                                        
                                                                        <?php } elseif($customField['fieldtype'] == 'dropdown' && count($customField['customFieldValues'][$fieldId]) > 0) { ?>
                                                                        
                                                                            <select class="form-control customValidationClass <?php echo $mandatoryClass;?>" 
                                                                                    name="<?php echo $fieldName;?>" id="<?php echo $trimmedFieldName.'_'.$attendeeCount;?>" 
                                                                                    data-customFieldId="<?php echo $fieldId;?>"
                                                                                     data-originalName="<?php echo $customField['fieldname'];?>"
                                                                                    data-customvalidation="<?php echo $customField['customvalidation'];?>" ticketid="<?php echo $ticketId;?>" >
                                                                                    <option value=''>Select</option>
                                                                                <?php foreach($customField['customFieldValues'][$fieldId] as $customFieldValueArr) { ?>
                                                                                    <option value="<?php echo $customFieldValueArr['value'];?>" <?php  if(count($indexedAttendeedetailList)>0 && isset($indexedAttendeedetailList[$trimmedFieldName][$attendeeCount]) && $indexedAttendeedetailList[$trimmedFieldName][$attendeeCount]==$customFieldValueArr['value']) echo 'selected="selected"'; elseif($customFieldValueArr['isdefault'] == 1) echo 'selected="selected"';?>>
                                                                                        <?php echo $customFieldValueArr['value'];?>
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        
                                                                        <?php } elseif($customField['fieldtype'] == 'file') { ?>
                                                                        
                                                                            <input type="file"  data-originalName="<?php echo $customField['fieldname'];?>" class="custom-file-input customValidationClass <?php echo $mandatoryClass;?>" name="<?php echo $fieldName;?>" id="<?php echo $trimmedFieldName.'_'.$attendeeCount;?>"  data-customFieldId="<?php echo $fieldId;?>">
                                                                        
                                                                        <?php } elseif($customField['fieldtype'] == 'radio' && count($customField['customFieldValues'][$fieldId]) > 0) { ?>
                                                                        
                                                                            <p>
                                                                                <?php foreach($customField['customFieldValues'][$fieldId] as $customFieldValueArr) { ?>
                                                                                    <label class="customlable-r">
                                                                                        <input value="<?php echo $customFieldValueArr['value'];?>" type="radio" class="customValidationClass <?php echo $mandatoryClass;?>" name="<?php echo $fieldName;?>" id="<?php echo $trimmedFieldName.'_'.$attendeeCount.'_'.$customFieldValueArr['id'];?>" <?php echo ($customFieldValueArr['isdefault'] == 1) ? 'checked="checked"' : '';?> data-customFieldId="<?php echo $fieldId;?>"
                                              data-customvalidation="<?php echo $customField['customvalidation'];?>"
                                              data-originalName="<?php echo $customField['fieldname'];?>" ticketid="<?php echo $ticketId;?>"   <?php  echo (count($indexedAttendeedetailList)>0 && isset($indexedAttendeedetailList[$trimmedFieldName][$attendeeCount]) && $indexedAttendeedetailList[$trimmedFieldName][$attendeeCount]==$customFieldValueArr['value'])?'checked="checked"':'';  ?>                                         
                                                                                               >
                                                                                        <?php echo $customFieldValueArr['value'];?>
                                                                                    </label>
                                                                                <?php } ?>
                                                                            </p>
                                                                            
                                                                        <?php } elseif($customField['fieldtype'] == 'checkbox' && count($customField['customFieldValues'][$fieldId]) > 0) { ?>
                                                                            
                                                                            <p>
                                                                                <?php foreach($customField['customFieldValues'][$fieldId] as $customFieldValueArr) { ?>
                                                                                    <label class="customlable-t">
                                                                                        <input value="<?php echo  $customFieldValueArr['value'];?>" type="checkbox" class="customValidationClass <?php echo $mandatoryClass;?>" name="<?php echo $fieldName;?>[]" id="<?php echo $trimmedFieldName.'_'.$attendeeCount.'_'.$customFieldValueArr['id'];?>" <?php if(count($indexedAttendeedetailList)>0 && isset($indexedAttendeedetailList[$trimmedFieldName][$attendeeCount]) && in_array($customFieldValueArr['value'], explode(',', $indexedAttendeedetailList[$trimmedFieldName][$attendeeCount]))) echo 'checked="checked"'; elseif($customFieldValueArr['isdefault'] == 1) echo 'checked="checked"';?> data-customFieldId="<?php echo $fieldId;?>"  data-originalName="<?php echo $customField['fieldname'];?>">
                                                                                        <?php echo  $customFieldValueArr['value'];?>
                                                                                    </label>
                                                                                <?php } ?>
                                                                            </p>
                                                                            
                                                                        <?php } ?>
                                                                        <span class="<?php echo $trimmedFieldName.'_'.$attendeeCount;?>Err"></span>
                                                                    </div>
                                                        <?php   }
                                                            } ?>
                                                        <!-- Custom Fields ends here -->
                                                    </div>
                                                    <?php
                                                    $attendeeCount++;
                                                    $localityCount = 0;
                                                }
                                                if($collectMultipleAttendeeInfo == 0) {
                                                    break;
                                                }
                                            }
                                        ?>
                                    </form>
                                </div>
                                
                                <?php if(count($eventGateways) > 0) { ?>
                                    <?php if($calculationDetails['totalPurchaseAmount'] > 0) { ?>
                                        <h2 class="pageTitle">Proceed to pay using</h2>
                                        <?php 
                                            $checkedKey=0;
                                            $keySet=false;
                                            foreach($eventGateways as $key=>$gateway) {
                                                if(!empty($gateway['gatewaytext'])){
                                                    if(!$keySet){
                                                        $checkedKey = $key;
                                                        $keySet = true;
                                                    }
                                                    echo stripslashes($gateway['gatewaytext']);
                                                }
                                                if($gateway['isdefault'] > 0) {
                                                    $checkedKey = $key;
                                                    $keySet=true;
                                                }
                                            } ?>
                                        <div class="col-xs-12 paymentmode-section1">
                                            
                                            <?php
                                                $ebsGateway = $ebsKey = 0;
                                                $paytmGateway = $paytmKey = 0;
                                                $paypalGateway = $paypalKey = 0;
                                                $mobikwikGateway = $mobikwikKey = 0;
                                            ?>
                                            <?php foreach($eventGateways as $key=>$gateway) {
                                                    $gatewayName = strtolower($gateway['gatewayName']);    
                                            ?>
                                                <?php if($gatewayName == 'ebs') {
                                                    $ebsGateway = 1;
                                                    $ebsKey = $gateway['paymentgatewayid'];
                                                ?>
                                                    <div class="col-sm-6 paymentmode-holder">
                                                        <p class="text-left"><label><input type="radio" id="<?php echo $ebsKey;?>" name="paymentGateway" value="ebs" <?php if($key==$checkedKey){echo 'checked="checked"';}?>><label id="ebs_text">Credit / Debit / Net Banking</label></label></p>
                                                        <div class="paymentmode-btn">
                                                            <a href="javascript:;" class="paymentButton" id="EBS">
                                                                <img src="<?php echo $this->config->item('images_static_path');?>card-payment.png" />
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } elseif($gatewayName == 'paytm') {
                                                    $paytmGateway = 1;
                                                    $paytmKey = $gateway['paymentgatewayid'];
                                                ?>
                                                    <div class="col-sm-6 paymentmode-holder">
                                                        <p class="text-left"><label><input type="radio" id="<?php echo $paytmKey;?>" name="paymentGateway" value="paytm" <?php if($key==$checkedKey){echo 'checked="checked"';}?>><label id="paytm_text">Paytm<label></label></p>
                                                        <div class="paymentmode-btn">
                                                            <a href="javascript:;" class="paymentButton" id="Paytm">
                                                                <img src="<?php echo $this->config->item('images_static_path');?>paytm.png" />
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } elseif($gatewayName == 'mobikwik') {
                                                    $mobikwikGateway = 1;
                                                    $mobikwikKey = $gateway['paymentgatewayid'];
                                                ?>
                                                    <div class="col-sm-6 paymentmode-holder">
                                                        <p class="text-left"><label><input type="radio" id="<?php echo $mobikwikKey;?>" name="paymentGateway" value="mobikwik" <?php if($key==$checkedKey){echo 'checked="checked"';}?>><label id="mobikwik_text">Mobikwik</label></label></p>
                                                        <div class="paymentmode-btn">
                                                            <a href="javascript:;" class="paymentButton" id="Mobikwik">
                                                                <img src="<?php echo $this->config->item('images_static_path');?>mobikwik.png" />
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } elseif($gatewayName == 'paypal') {
                                                    $paypalGateway = 1;
                                                    $paypalKey = $gateway['paymentgatewayid'];
                                                ?>
                                                    <div class="col-sm-6 paymentmode-holder">
                                                        <p class="text-left"><label><input type="radio" id="<?php echo $paypalKey;?>" name="paymentGateway" value="paypal" <?php if($key==$checkedKey || $calculationDetails['currencyCode'] == 'USD'){echo 'checked="checked"';}?>><label id="paypal_text">Paypal</label></label></p>
                                                        <div class="paymentmode-btn">
                                                            <a href="javascript:;" class="paymentButton" id="Paypal">
                                                                <img src="<?php echo $this->config->item('images_static_path');?>paypal.png" />
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <div class="PayNow-Holder">
                                        <a id="paynow" href="javascript:void(0)" class="btn commonBtn login paynowbtn">
                                      PAY NOW </a>
                                    </div>
                                <?php } ?>
                                
                                <?php include_once('includes/elements/payment_gateways.php');?>
                                
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            
                            <?php
                                $editeventurl = $eventData['eventUrl'];
                                if($calculationDetails['totalPurchaseAmount'] == 0) {
                                    $calculationDetails['currencyCode'] = '';
                                }
                                if(isset($referralcode)) {
                                    $reffCode = $referralcode;
                                    if(strpos($eventData['eventUrl'], '?')== true){
                                    	$editeventurl = $eventData['eventUrl']."&reffCode=".$reffCode;
                                    }else{
                                    	$editeventurl = $eventData['eventUrl']."?reffCode=".$reffCode;
                                    }
                                }
                                if(isset($promotercode)) {
                                    $ucode = $promotercode;
                                    if(strpos($eventData['eventUrl'], '?')== true){
                                    	$editeventurl = $eventData['eventUrl']."&ucode=".$ucode;
                                    }else{
                                    $editeventurl = $eventData['eventUrl']."?ucode=".$ucode;
                                    }
                                }
                                if(isset($acode)) {
                                    if(strpos($eventData['eventUrl'], '?')== true){
                                    	$editeventurl = $eventData['eventUrl']."&acode=".$acode;
                                    }else{
                                        $editeventurl = $eventData['eventUrl']."?acode=".$acode;
                                    }
                                }
                               
                            ?>
                            
                            <div class="summarySec">
                                <div class="sumBlog">
                                    <span class="imgOverlay"></span>
                                    <?php
                                        if(isset($eventData['thumbnailPath']) && $eventData['thumbnailPath'] != '') { ?>
                                        <img src="<?php echo $eventData['thumbnailPath'];?>" alt="<?php echo $eventData['title'];?>" title="<?php echo $eventData['title'];?>" onError="this.src='<?php echo $eventData['defaultthumbnailPath']; ?>'">
                                    <?php } ?>
                                    <span class="titles">Payment Summary</span>
                                </div>
                                <div class="summaryDetail">
                                    <p class="floatL">Event Id : <?php echo $eventData['id'];?></p>
                                    <a href="<?php echo $editeventurl;?>" class="floatR backBg" title="Edit Your Order"><span class="icon-edit"></span></a>
                                </div>
                                <div class="ticketSummary">
                                    <span>No of Tickets<p id="ticketQnty"><?php echo $calculationDetails['totalTicketQuantity'];?></p></span>
                                    
                                    <div class="coupon">
                                        <div class="totalamt-div2">
                                            <span>Total Amount</span>
                                                <span>
                                                <?php echo $calculationDetails['currencyCode'].' '.$calculationDetails['totalTicketAmount'];?>
                                                </span>
                                            </span>
                                        </div>
                                        <?php
                                            // Code discount and bulk discount will be considered as "Discount"
                                            $totalCodeBulkDiscount = $calculationDetails['totalCodeDiscount']+$calculationDetails['totalBulkDiscount'];
                                            if($totalCodeBulkDiscount > 0) { ?>
                                                <div>
                                                    <span>Discount</span>
                                                    <span>
                                                        <?php echo $calculationDetails['currencyCode'].' '.$totalCodeBulkDiscount;?>
                                                    </span>
                                                </div>
                                        <?php }
                                        
                                            $totalReferralDiscount = $calculationDetails['totalReferralDiscount'];
                                            if($totalReferralDiscount > 0) { ?>
                                                <div>
                                                    <span>Referral Discount</span>
                                                    <span>
                                                        <?php echo $calculationDetails['currencyCode'].' '.$totalReferralDiscount;?>
                                                    </span>
                                                </div>
                                        <?php } ?>
                                        
                                        <?php
                                            if(isset($calculationDetails['totalTaxDetails']) && count($calculationDetails['totalTaxDetails']) > 0) {
                                                foreach($calculationDetails['totalTaxDetails'] as $taxData) { ?>
                                                    <div>
                                                    <span><?php echo $taxData['label'].' ('.$taxData['value'].'%)';?></span>
                                                    <span>
                                                        <?php echo $calculationDetails['currencyCode'].' '.$taxData['taxAmount'];?>
                                                    </span></div>
                                        <?php   }
                                            } ?>
                                         
                                        <!-- Displaying the extra charge -->   
                                            <?php if(is_array($calculationDetails['extraCharge']) && count($calculationDetails['extraCharge']) > 0) { ?>
                                                
                                                <?php foreach($calculationDetails['extraCharge'] as $extraCharge) {
                                                        if($extraCharge['totalAmount'] > 0) {    
                                                ?>
                                                    <div>
                                                        <span><?php echo $extraCharge['label'];?></span>
                                                        <span>
                                                            <?php echo $calculationDetails['currencyCode'].' '.$extraCharge['totalAmount'];?>
                                                        </span>
                                                    </div>
                                                <?php } } ?>
                                            <?php } ?>
                                    </div>
                                    <div class="totalamt-div2 totalAmountid" amnt= "<?php echo $calculationDetails['totalPurchaseAmount'];?>" ><span>Purchase Amount<p><?php echo $calculationDetails['currencyCode'].' '.$calculationDetails['totalPurchaseAmount'];?></p></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <!--End Step2-->
            <?php } ?>
        </div>
    </div>
    <!-- /.wrap --> 
</div>
<script type="text/javascript">
var customValidationEventIds = "<?php echo json_encode($customValidationEventIds);?>";
customValidationEventIds = $.parseJSON(customValidationEventIds);
</script>
<?php if(isset($configCustomDatemsg[$eventId])){?>
<script type="text/javascript">
var weekendTickets ="<?php echo json_encode($configspecialTickets['weekends']);?>";  
var weekdayTickets ="<?php echo json_encode($configspecialTickets['weekdays']);?>";
weekendTickets= $.parseJSON(weekendTickets);
weekdayTickets = $.parseJSON(weekdayTickets);

 </script>                                          
<?php }?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=<?php echo $this->config->item('google_app_key');?>"></script>
<script type="text/javascript">

	var booking_saveData ='<?php echo commonHelperGetPageUrl('api_bookingSaveData');?>';
	var api_citySearch = '<?php echo commonHelperGetPageUrl('api_citySearch');?>';
	var api_stateSearch = '<?php echo commonHelperGetPageUrl('api_stateSearch');?>';
	var api_countrySearch = '<?php echo commonHelperGetPageUrl('api_countrySearch');?>';
        var api_eventPromoCodes='<?php echo commonHelperGetPageUrl('api_eventPromoCodes');?>';
        var totalSaleTickets='<?php echo $calculationDetails['totalTicketQuantity'];?>';
</script>
