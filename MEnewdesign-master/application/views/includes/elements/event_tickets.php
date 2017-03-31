<?php 
$divClass = 'col-sm-7 col-xs-12 col-md-8';
$divStyle = '';
$eventId =$eventData['id'];
$configCustomDatemsg = json_decode(CONFIGCUSTOMDATEMSG,true);
$configCustomTimemsg =  json_decode(CONFIGCUSTOMTIMEMSG,true);
$configspecialTickets = json_decode(SPECIALTICKETS,true);
$customValidationEventIds = json_decode(CUSTOMVALIDATIONEVENTIDS,true);
$configspecialTickets = isset($configspecialTickets[$eventId])?$configspecialTickets[$eventId]:array();

$eventTitleColor = '';
$headingBackgroundColor = '';
$ticketTextColor = '';
$bookNowBtnColor = '';
if ($ticketWidget == 'Yes') {
    $divClass = 'col-sm-12 col-xs-12 col-md-12';
    $divStyle = 'padding-left:0px;padding-right:0px;overflow:hidden;';
    $wCodeArray = explode('-', $wCode);

    $eventTitleColor = $wCodeArray[0];
    $headingBackgroundColor = $wCodeArray[1];
    if(strtolower($wCodeArray[2]) != 'ffffff'){ 
        $ticketTextColor = $wCodeArray[2];
    }else{
        $ticketTextColor = $wCodeArray[1];
    }
    $bookNowBtnColor = $wCodeArray[3];
    $limitSingleTicketType = $eventData['eventDetails']['limitSingleTicketType'];?>
    <input type="hidden" name="limitSingleTicketType" id="limitSingleTicketType" value="<?php echo $limitSingleTicketType;?>">
    <div class="<?php echo $divClass; ?>" style="<?php echo $divStyle; ?>">
        <div class="row <?php echo str_replace(" ", "", strtolower($eventData['categoryName'])) ?>" style="<?php if ($headingBackgroundColor != '') echo 'background:' . '#' . $headingBackgroundColor . ';'; ?>">
            <div class="img_below_cont ">
                    <h1 style="<?php if ($eventTitleColor != '') echo 'color:' . '#' . $eventTitleColor . ';'; ?>"><?php echo isset($eventData['title'])?$eventData['title']:'';?></h1>
                    <div class="sub_links"><span class="icon-calender"></span>                       
                               <?php 
                             if(isset($configCustomDatemsg[$eventId]) && !isset($configCustomTimemsg[$eventId])){
                             	echo $configCustomDatemsg[$eventId]." | ".allTimeFormats($eventData['startDate'],4).' to '.allTimeFormats($eventData['endDate'],4);
                            }else if(isset($configCustomTimemsg[$eventId]) && !isset($configCustomDatemsg[$eventId])){
                             	echo allTimeFormats($eventData['startDate'],3);?><?php if (allTimeFormats($eventData['startDate'],9) != allTimeFormats($eventData['endDate'],9))  { echo " - ". allTimeFormats($eventData['endDate'],3);} echo " | ".$configCustomTimemsg[$eventId];
                            }else if(isset($configCustomTimemsg[$eventId]) && isset($configCustomDatemsg[$eventId])){
                             	echo $configCustomDatemsg[$eventId]." | ".$configCustomTimemsg[$eventId];
                            }else{
                            	echo allTimeFormats($eventData['startDate'],3);?><?php if (allTimeFormats($eventData['startDate'],9) != allTimeFormats($eventData['endDate'],9))  { echo " - ". allTimeFormats($eventData['endDate'],3);} echo " | ".allTimeFormats($eventData['startDate'],4).' to '.allTimeFormats($eventData['endDate'],4);
                            }  ?>
                            </span></div>
                    <div class="sub_links"> <span class="icon-google_map_icon"></span><span><?php if(isset($eventData['eventMode']) && $eventData['eventMode'] == 1){ echo "Webinar"; } else { echo $eventData['fullAddress']; } ?></span></div>
                </div>
        </div>
    </div>

        <?php } ?>
<input type="hidden" name="eventId" id="eventId" value="<?php echo $eventData['id']; ?>">
<input type="hidden" name="referralCode" id="referralCode" value="<?php echo $referralCode; ?>">
<input type="hidden" name="promoterCode" id="eventId" value="<?php echo $promoterCode; ?>">
<?php if (count($ticketDetails) > 0) { ?>
<div id="useracco" class="<?php echo $divClass; ?> event_left_cont" style="<?php echo $divStyle; ?>">
    <ul>
        <?php
        $ticketids = $nowdate = "";
	//echo "<pre>";print_r($ticketDetails);exit;
        //if (count($ticketDetails) > 0) {
            $soldoutTickets = array();
            $comingSoonTickets = array();
            foreach ($ticketDetails as $ticket) {
                // echo $nowdate = strtotime(date('Y-m-d H:i:s'));
                $nowdate = strtotime(nowDate($eventData['location']['timeZoneName']));
                $startdate = strtotime($ticket['startDate']);
                $enddate = strtotime($ticket['endDate']);
                // $lastdate = convertTime($ticket['endDate'],$eventData['timeZoneName'],true);
                $lastdate = $ticket['endDate'];
                if ($ticket['soldout'] == 1 || $enddate < $nowdate) {
                    $soldoutTickets[] = $ticket;
                } else if ($startdate > $nowdate) {
                    $comingSoonTickets[] = $ticket;
                } else {
                    ?> 
                    <li id="accrdn_1" class="<?php echo str_replace(" ", "", strtolower($eventData['categoryName'])); ?>">
                        <div class="borderTop">

                        </div>
                        <div class="div-content">
                            <div class="cont_main">
                                <div class="eventCatName">
                                    <?php
                                    $description = $ticket['description'];
                                    $first = substr($description, 0, 80);
                                    $last = substr($description, 80);
                                    ?>

                                    <h2 style="<?php if ($ticketTextColor != '') echo 'color:' . '#' . $ticketTextColor . ';'; ?>"><?php echo $ticket['name'] ?> </h2>
                                       
                                    <p class="tckdesc"> <?php echo $description;?></p>
                                    <?php if (isset($ticket['taxes']) && count($ticket['taxes']) > 0) {
                                        foreach ($ticket['taxes'] as $taxData) {
                                            ?>
                                            <p class="taxtype">* Exclusive of <?php echo ucfirst($taxData['label']) . ' ' . $taxData['value'] . '%' ?></p>
                <?php }
                ?>
                                        <p></p>
                                    <?php } ?>
                                    <p class="lastdate">
                                        <i>Last Date: <?php echo lastDateFormat($ticket['endDate']) ?></i> 

                                    </p>
                                    <?php
                                    if (isset($ticket['viralData'])) {
                                        $viralValue = $ticket['currencyCode'] . ' ' . $ticket['viralData']['receivercommission'];
                                        if ($ticket['viralData']['type'] == 'percentage') {
                                            $viralValue = $ticket['viralData']['receivercommission'] . '%';
                                        }
                                        ?>
                                        <p class="redeem">Applicable Referral Discount <?php echo $viralValue; ?> </p>
                                        <?php } ?>

                                </div>
                                <div class="eventCatValue" style="<?php if ($ticketTextColor != '') echo 'color:' . $ticketTextColor . ';'; ?>"> <span> 
            <?php if ($ticket['price'] > 0 || $ticket['type'] == 'donation') {
                echo $ticket['currencyCode'] . " ";
            } ?>
                                    <?php if ($ticket['type'] != 'donation') {
                                        echo $ticket['price'];
                                    } else if ($ticket['type'] == 'donation') { ?>
                                        <?php if ($ticket['soldout'] != 1 && $enddate >= $nowdate && $startdate <= $nowdate) { ?>
                                                <input type="text" name="ticket_selector" placeholder="Enter Amount" size='10' class="ticket_selector selectNo" value="" id="<?php echo $ticket['id'] ?>"> <?php } ?>
                                    <?php } ?> </span> </div>

                                <div class="eventCatSelect"> 
                                    <!--                        <span class="selectpicker">-->
                                    <?php
                                    //preparing the options array
                                    $optionText='';
                                    $optionText .= "<option value='0'>0</option>";
                                    for ($option = $ticket['minOrderQuantity']; $option <= $ticket['maxOrderQuantity']; $option++) {
                                        if (($ticket['totalSoldTickets'] + $option) <= $ticket['quantity']) {
                                            $optionText = $optionText . "<option>" . $option . "</option>";
                                        }
                                    }
                                    ?>
            <?php if ($ticket['type'] != 'donation') { ?>
                <?php if ($ticket['soldout'] != 1 && $enddate >= $nowdate) { ?>
                                            <select class="form-control ticket_selector selectNo <?php echo $ticket['type'];?>" id="<?php echo $ticket['id'] ?>">
                    <?php echo $optionText; ?>
                                            </select>
                <?php } ?>
            <?php } else if ($ticket['type'] == 'donation') {
                echo 1;
            } ?>
                                    <!-- </span> -->  
                                </div>


                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="borderBottom">

                        </div>
                    </li>
                    <?php
                }

                $ticketids.=$ticket['id'] . ",";
            }
            foreach ($comingSoonTickets as $ticket) {
                $startdate = strtotime($ticket['startDate']);
                $enddate = strtotime($ticket['endDate']);
                // $startdateConverted = convertTime($ticket['startDate'],$eventData['timeZoneName'],true);
                $startdateConverted = $ticket['startDate'];
                ?>
                <li id="accrdn_1" class="<?php echo str_replace(" ", "", strtolower($eventData['categoryName'])); ?>">
                    <div class="borderTop">

                    </div>
                    <div class="div-content">
                        <div class="cont_main">
                            <div class="eventCatName">
                                    <?php
                                    $description = $ticket['description'];
                                    $first = substr($description, 0, 80);
                                    $last = substr($description, 80);
                                    ?>

                                <h2 style="<?php if ($ticketTextColor != '') echo 'color:' . $ticketTextColor . ';'; ?>"><?php echo $ticket['name'] ?> </h2>


                                <p class="tckdesc"> <?php echo $description;?></p>

                                <p class="lastdate">
                                    <i>Start Date: <?php echo lastDateFormat($startdateConverted) ?></i>                          
                                </p>

                            </div>
                            <div class="eventCatValue" style="<?php if ($ticketTextColor != '') echo 'color:' . $ticketTextColor . ';'; ?>"> 
                                <span> <?php if ($ticket['price'] > 0 || $ticket['type'] == 'donation') {
                                echo $ticket['currencyCode'] . " ";
                            } ?><?php if ($ticket['type'] != 'donation') {
                            echo $ticket['price'];
                        }
                        //else if($ticket['type'] == 'donation'){
                                    ?>
                                <?php //if($ticket['soldout'] != 1  && $enddate >= $nowdate && $startdate <= $nowdate) { ?>
                           <!-- <input type="text" name="ticket_selector" placeholder="Enter Amount" size='10' class="ticket_selector selectNo" value="" id="<?php // echo $ticket['id']  ?>"><?php // }  ?>--> 
                                <?php //}?>
                                </span> 
                            </div>

                            <div class="eventCatSelect"> 
                                <?php
                                // $optionText="";
                                //if($ticket['soldout'] == 1  || $enddate < $nowdate){ 
                                ?>
                                     <!--<span class="selectpicker soldout_text"> <?php //echo 'Sold Out'; ?></span>--> 

                                <?php // } else if($startdate > $nowdate ){  ?>
                                <span class="selectpicker comingsoon_text"> <?php echo 'Coming Soon'; ?></span> 
                                <?php //  } else{  ?>
        <!--                        <span class="selectpicker">-->
        <?php
        //preparing the options array
//                                    $optionText .= "<option value='0'>0</option>";
//                                    for($option=$ticket['minOrderQuantity'];$option<=$ticket['maxOrderQuantity']; $option++){
//                                       if(($ticket['totalSoldTickets']+$option) <= $ticket['quantity']) {   
//                                            $optionText=$optionText."<option>".$option."</option>";
//                                        }
//                                    }
        ?>
        <?php //if($ticket['type'] != 'donation') {  ?>
                                     <!--<select class="form-control ticket_selector selectNo"  id="<?php //echo $ticket['id']  ?>">-->
        <?php // echo $optionText; ?>
                                <!--</select>-->
                <?php // }else if($ticket['type'] == 'donation') { echo 1;}  ?>
                                <!-- </span> -->  <?php // }?>
                            </div>


                        </div>

                    </div>
                    <div class="clearfix"></div>

                    <div class="borderBottom">

                    </div>
                </li>

        <?php
    }

    foreach ($soldoutTickets as $ticket) {
        $startdate = strtotime($ticket['startDate']);
        $enddate = strtotime($ticket['endDate']);
        //$lastdate = convertTime($ticket['endDate'],$eventData['timeZoneName'],true);    
        $lastdate = $ticket['endDate'];
        ?>
                <li id="accrdn_1" class="<?php echo str_replace(" ", "", strtolower($eventData['categoryName'])); ?>">
                    <div class="borderTop">

                    </div>
                    <div class="div-content">
                        <div class="cont_main">
                            <div class="eventCatName">
                                    <?php
                                    $description = $ticket['description'];
                                    $first = substr($description, 0, 80);
                                    $last = substr($description, 80);
                                    ?>

                                <h2 style="<?php if ($ticketTextColor != '') echo 'color:' . $ticketTextColor . ';'; ?>"><?php echo $ticket['name'] ?> </h2>


                                    <p class="tckdesc"> <?php echo $description;?></p>
        <?php  if( $enddate < $nowdate ){ ?>
                                <p class="saledate">Sale Date Ended</p>
        <?php }  ?>
                            </div>
                            <div class="eventCatValue" style="<?php if ($ticketTextColor != '') echo 'color:' . $ticketTextColor . ';'; ?>"> 
                                <span> <?php if ($ticket['price'] > 0 || $ticket['type'] == 'donation') {
            echo $ticket['currencyCode'] . " ";
        } ?>
        <?php if ($ticket['type'] != 'donation') {
            echo $ticket['price'];
        }//else if($ticket['type'] == 'donation'){ ?>
                <?php //if($ticket['soldout'] != 1  && $enddate >= $nowdate && $startdate <= $nowdate) {  ?>
                                <!--<input type="text" name="ticket_selector" placeholder="Enter Amount" size='10' class="ticket_selector selectNo" value="" id="<?php // echo $ticket['id'] ?>"><?php // } ?> -->
                <?php //}?>
                                </span>
                            </div>

                            <div class="eventCatSelect"> 
                                <span class="selectpicker soldout_text"> <?php echo 'Sold Out'; ?></span> 
                            </div>


                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="borderBottom">

                    </div>
                </li>
        <?php
    }
//}
//trim comma
$ticketids = rtrim($ticketids, ',');
?>



      
                <?php 
                $buttonName=!empty($eventData['eventDetails']['bookButtonValue'])?$eventData['eventDetails']['bookButtonValue']:'Book Now';
                if($eventData['eventDetails']['organizertnc'] != '') { ?>
                <span class="terms">By clicking "<?php echo $buttonName;?>" you agree to the <a href="#tnc">Terms &amp; Conditions</a></span>
                <?php }?>
            
        <div class="clearfix"></div>
        <div class="book" id="calucationsDiv" style="display:none;">
            <div id="ajax_calculation_div" class="book_subcont_a col-md-12">
                <table id="totalAmountTbl" width="100%" class="table_cont table_cont_1">
                    <tbody>
                        <tr>
                            <td class="table_left_cont">Total Amount<span class="currencyCodeStr"></span></td>
                            <td class="table_ryt_cont" id="total_amount">0</td>
                        </tr>
                    </tbody>
                </table>

                <table style="display: none;" id="bulkDiscountTbl" width="100%" class="table_cont table_cont_1">
                    <tbody>
                        <tr>
                            <td class="table_left_cont">Bulk Discount Amount<span class="currencyCodeStr"></span></td>
                            <td class="table_ryt_cont" id="bulkDiscount_amount">0</td>
                        </tr>
                    </tbody>
                </table>
                <table style="display: none;" id="discountTbl" width="100%" class="table_cont table_cont_1">
                    <tbody>
                        <tr>
                            <td class="table_left_cont">Discount Amount<span class="currencyCodeStr"></span></td>
                            <td class="table_ryt_cont" id="discount_amount">0</td>
                        </tr>
                    </tbody>
                </table>
                <table style="display: none;" id="referralDiscountTbl" width="100%" class="table_cont table_cont_1">
                    <tbody>
                        <tr>
                            <td class="table_left_cont">Referral Discount Amount<span class="currencyCodeStr"></span></td>
                            <td class="table_ryt_cont" id="referralDiscount_amount">0</td>
                        </tr>
                    </tbody>
                </table>
                <div id="taxesDiv">

                </div>
                <table style="display: none;" id="extraChargeTbl" width="100%" class="table_cont table_cont_1">
                    
                </table>
                <table style="display: none;" id="roundOfValueTbl" width="100%" class="table_cont table_cont_1">
                    <tbody>
                        <tr>
                            <td class="table_left_cont">Round of Value<span class="currencyCodeStr"></span></td>
                            <td class="table_ryt_cont" id="roundOfValue">0</td>
                        </tr>
                    </tbody>
                </table>
                <div id="showdis" style="display:none;">
                    <table  width="100%" class="table_cont table_cont_2" id="TicketTableInfo1">
                        <tbody>
                            <tr>
<?php if (isset($eventData['normalDiscountExists']) && $eventData['normalDiscountExists'] == 1) { ?>
                                    <td class="discount_code"><a class="pointerCursor" onclick="ShowDiscountCodePopup();">Have Discount Code ?</a></td>
<?php } ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="hidedis" style="display:none">
                    <table  width="100%" class="table_cont table_cont_2" id="TicketTableInfo1">
                        <tbody>
                            <tr>
                                <td class="discount_code"><a class="pointerCursor" onclick="HideDiscountCodePopup();">Close Discount Code</a></td>
                                <td class="code_input"><div>
                                        <input type="text" class="require PromoCodeNewCSS" id="promo_code" name="promo_code" value="0">                       
                                        <div class="coupon_apply"><a id="apply_discount_btn"  onclick="return applyDiscount();" class="pointerCursor">Apply</a></div>
                                        <div class="coupon_reset"> <a id="apply_discount_btn"  onclick="return clearDiscount();" class="pointerCursor">Clear</a></div>                          
                                    </div>
                            </tr>
                        </tbody>
                    </table>               
                </div>
                <table width="100%" class="table_cont table_cont_3">
                    <tbody>
                        <tr>
                            <td class="table_left_cont"><strong>Purchase Total<span class="currencyCodeStr"></span></strong></td>
                            <td class="final_val"><strong id="purchase_total">0</strong></td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%">
                    <tbody><tr>
<?php if (($eventData['eventDetails']['tnctype'] == 'organizer' && !empty($eventData['eventDetails']['organizertnc']) ) || ($eventData['eventDetails']['tnctype'] == 'meraevents' && !empty($eventData['eventDetails']['meraeventstnc']))) { ?>
                                <td class="TermsTD">
                                    <div class="terms_cont">
                                        By clicking "<?php echo $buttonName; ?>" you agree to the <a style="color:#3366FF; cursor:pointer;" class="event_tnc">Terms and Conditions</a>                            
                                </td>
<?php } ?>
                            <td style="float:right;">
                                <div> 
                                    <a class="book_now" href="javascript:;" onclick="booknow()" >
                                        <div id="wrap" class="<?php echo str_replace(" ", "", strtolower($eventData['categoryName'])); ?>" style="<?php if ($bookNowBtnColor != '') echo 'background:' . '#' . $bookNowBtnColor . ';'; ?>">
                                            <div id="content"> <?php echo $buttonName; ?> </div>
                                        </div>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody></table>
            </div>
        </div>
    </ul>
</div>
<?php } else{ ?>
        <div class="col-sm-7 col-xs-12 col-md-8 eventDetails" id="event_about">
                <h3>About The Event</h3>
                <div>
                    <p><?php echo stripslashes($eventData['description']); ?></p>
                </div>
            </div>
		
<?php }?>
<!--T & C Start-->
<div class="modal fade" id="event_tnc" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog-invite modal-dialog-center1">
        <div class="modal-content">
            <div class="popup_signup" style="height:auto; overflow:auto;">
                <div class="popup-closeholder">
                    <button data-dismiss="modal" class="popup-close">X</button>
                </div>
                <hr>
                <h3>Terms & Conditions</h3>
                <h3 class="subject"><?php echo isset($eventData['title']) ? $eventData['title'] : ''; ?></h3> 
                <hr></hr>
                <div class="event_tnc_holder tncoverflow">
<?php echo $tncDetails; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!--T & C End-->