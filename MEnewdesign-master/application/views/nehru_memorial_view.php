<div class="container"> 
    <!--LOGIN AND SIGNUP SECTION-->
    <?php if ($isExisted) { ?>
        <div class="innerPageContainer" style="margin-bottom: 30px;">
            <h2 class="pageTitle">Registration Information</h2>
            <div class="row">
                <div class="col-md-12">
                    Looks like you used the browser back button after completing your previous transaction to buy another ticket!<br>
                    To buy another ticket for this event go to
                    <a href="<?php echo $eventData['eventUrl']; ?>">Preview Event</a> and continue from there.
                    <br><br>Contact support at support@meraevents.com or +91 - 9396 555 888 for assistance.
                </div>
            </div>
        </div>
    <?php } else { 
        //print_r($calculationDetails);exit;
        ?>
    <style>
        .level{
            width:70% !Important;
        }
    </style>
        <div class="row">
            <div>
            <?php $ticketids = array('Gold'=>86993,'Silver'=>86994,'Bronze'=>86995,'Balcony'=>86996); ?>
                <div>
                    <script>
                        var api_updateSeats = "<?php echo commonHelperGetPageUrl('api_updateSeats') ?>";
                        var paymentGatewaySelected = "<?php echo $paymentGatewaySelected; ?>";
                        var api_checkUpdateSeats = "<?php echo commonHelperGetPageUrl('api_checkUpdateSeats') ?>";
                    </script>
                    <link rel="stylesheet" href="<?php echo $this->config->item('protocol') . $_SERVER['HTTP_HOST'] . '/css/public/styles-seating.css' ?>">
                    <input type="hidden" name="eventId" id="eventId" value="<?php echo $eventId; ?>"/>
                    <div class="col-lg-12" id="LeftPaddCol">
                        <div id="WizardBox">
                            <table border="0" align="right" cellpadding="0" style=" text-align: right; padding: 10px 0;  width: 45%; float:right;  margin: 10px 0 40px 0;">
                                <tr>
                                    <td height="30"><img src="<?php echo $availableImage; ?>" width="13" height="15" /></td>
                                    <td height="30" style="text-align:left; padding:5px 0 0 5px;">Available</td>
                                    <td height="30">&nbsp;</td>

                                    <td height="30"><img src="<?php echo $currentBookingImage; ?>" width="13" height="15" class="style3" /></td>
                                    <td height="30" style="text-align:left; padding:5px 0 0 5px;">Current Booking</td>
                                    <td height="30">&nbsp;</td>

                                    <td height="30"><img src="<?php echo $otherAreaImage; ?>" width="13" height="15" /></td>
                                    <td height="30" style="text-align:left; padding:5px 0 0 5px;">Other Area </td>
                                    <td height="30">&nbsp;</td>

                                    <td height="30"><img src="<?php echo $bookedImage; ?>" width="13" height="15" /></td>
                                    <td height="30" style="text-align:left; padding:5px 0 0 5px;">Booked</td>
                                </tr>
                            </table>
                            <div style="clear:both;"></div>

                            <div id="stage"></div>

                            <h1 class="level"><?php echo $ticketsData[$ticketids['Gold']]['name']; ?> (<img src="http://static.meraevents.com/content/images/rupee-icon.gif" width="12" height="12" /> <?php echo $ticketsData[$ticketids['Gold']]['price']; ?>)</h1>
                            <div class="seatplan">
                                <ul class="r" style="margin:0 auto; width:580px;">

                                    <?php
                                    $notype1 = isset($calculationDetails['ticketsData'][$ticketids['Gold']]['selectedQuantity']) ? $calculationDetails['ticketsData'][$ticketids['Gold']]['selectedQuantity'] : 0;
                                    $cnt = 0;
                                    for ($i = 0; $i < count($ResSeatslevel1); $i++) {
                                        $cnt++;
                                        $rowno = preg_replace('/[0-9]/', "", $ResSeatslevel1[$i]['GridPosition']);
                                        if ($cnt == 1) {
                                            echo '<li class="ltr">' . $rowno . '</li>';
                                        }

                                        //var_dump((int)$ResSeatslevel1[$i]['Seatno']);
                                        if ((int) $ResSeatslevel1[$i]['Seatno'] == 0) {
                                            echo '<li class="b"></li>';
                                        } else {
                                            if ($notype1 == "" || $notype1 == 0) {
                                                echo '<li class="bl x" id="' . $rowno . $ResSeatslevel1[$i]['Seatno'] . '" ></li>';
                                            } else {
                                                if ($ResSeatslevel1[$i]['Status'] == 'Available') {
                                                    echo '<li class="s bl" onclick="CngClass1(this,' . $notype1 . ',' . $ResSeatslevel1[$i]['Id'] . ',' . $eventsignupId . ');" id="' . $rowno . $ResSeatslevel1[$i]['Seatno'] . '" title="' . $ResSeatslevel1[$i]['Seatno'] . '"></li>';
                                                } else if ($ResSeatslevel1[$i]['Status'] == 'Booked') {
                                                    echo '<li class="r rd" id="' . $rowno . $ResSeatslevel1[$i]['Seatno'] . '" ></li>';
                                                } else if ($ResSeatslevel1[$i]['Status'] == 'Reserved') {
                                                    echo '<li class="b x" id="' . $rowno . $ResSeatslevel1[$i]['Seatno'] . '" ></li>';
                                                } else if ($ResSeatslevel1[$i]['Status'] == 'InProcess') {
                                                    echo '<li class="r rd" id="' . $rowno . $ResSeatslevel1[$i]['Seatno'] . '" ></li>';
                                                }
                                            }
                                        }



                                        if ($cnt == 32) {
                                            echo '</ul></div><div class="seatplan"><ul class="r" style="margin:0 auto; width:580px;">';
                                            $cnt = 0;
                                        }
                                    } //ends for loop
                                    ?>
                                </ul>
                            </div>
                            <h1 class="level"><?php echo $ticketsData[$ticketids['Silver']]['name']; ?> (<img src="http://static.meraevents.com/content/images/rupee-icon.gif" width="12" height="12" /> <?php echo $ticketsData[$ticketids['Silver']]['price']; ?>)</h1>
                            <div class="seatplan">
                                <ul class="r" style="margin:0 auto; width:580px;">

                                    <?php
                                    $cnt = 0;
                                    $notype2 = isset($calculationDetails['ticketsData'][$ticketids['Silver']]['selectedQuantity']) ? $calculationDetails['ticketsData'][$ticketids['Silver']]['selectedQuantity'] : 0;
                                    for ($i = 0; $i < count($ResSeatslevel2); $i++) {
                                        $cnt++;
                                        //$notype2 = 0;
                                        //$notype2 = isset($calculationDetails['ticketsData'][$ticketids['Silver']]['selectedQuantity']) ? $calculationDetails['ticketsData'][$ticketids['Silver']]['selectedQuantity'] : 0;
                                        $rowno = preg_replace('/[0-9]/', "", $ResSeatslevel2[$i]['GridPosition']);
                                        if ($cnt == 1) {
                                            echo '<li class="ltr">' . $rowno . '</li>';
                                        }

                                        if ((int) $ResSeatslevel2[$i]['Seatno'] == 0) {
                                            echo '<li class="b"></li>';
                                        } else {
                                            if ($notype2 == "" || $notype2 == 0) {
                                                echo '<li class="bl x" id="' . $rowno . $ResSeatslevel2[$i]['Seatno'] . '" ></li>';
                                            } else {
                                                if ($ResSeatslevel2[$i]['Status'] == 'Available') {
                                                    echo '<li class="s bl" onclick="CngClass2(this,' . $notype2 . ',' . $ResSeatslevel2[$i]['Id'] . ',' . $eventsignupId . ');" id="' . $rowno . $ResSeatslevel2[$i]['Seatno'] . '" title="' . $ResSeatslevel2[$i]['Seatno'] . '"></li>';
                                                } else if ($ResSeatslevel2[$i]['Status'] == 'Booked') {
                                                    echo '<li class="r rd" id="' . $rowno . $ResSeatslevel2[$i]['Seatno'] . '" ></li>';
                                                } else if ($ResSeatslevel2[$i]['Status'] == 'Reserved') {
                                                    echo '<li class="b x" id="' . $rowno . $ResSeatslevel2[$i]['Seatno'] . '" ></li>';
                                                } else if ($ResSeatslevel2[$i]['Status'] == 'InProcess') {
                                                    echo '<li class="r rd" id="' . $rowno . $ResSeatslevel2[$i]['Seatno'] . '" ></li>';
                                                }
                                            }
                                        }
                                        ?>





                                        <?php
                                        if ($cnt == 32) {
                                            echo '</ul></div><div class="seatplan"><ul class="r" style="margin:0 auto; width:580px;">';
                                            $cnt = 0;
                                        }
                                    } //ends for loop
                                    ?>
                                </ul>
                            </div>
                            <h1 class="level"><?php echo $ticketsData[$ticketids['Bronze']]['name']; ?> (<img src="http://static.meraevents.com/content/images/rupee-icon.gif" width="12" height="12"/> <?php echo $ticketsData[$ticketids['Bronze']]['price']; ?>)</h1>
                            <div class="seatplan">
                                <ul class="r"  style="margin:0 auto; width:580px;">

                                    <?php
                                    $notype3 = isset($calculationDetails['ticketsData'][$ticketids['Bronze']]['selectedQuantity']) ? $calculationDetails['ticketsData'][$ticketids['Bronze']]['selectedQuantity'] : 0;
                                    $cnt = 0;
                                    for ($i = 0; $i < count($ResSeatslevel3); $i++) {
                                        //$notype3 = 0;
                                        //$notype3 = isset($calculationDetails['ticketsData'][$ticketids['Bronze']]['selectedQuantity']) ? $calculationDetails['ticketsData'][$ticketids['Bronze']]['selectedQuantity'] : 0;
                                        $cnt++;
                                        $rowno = preg_replace('/[0-9]/', "", $ResSeatslevel3[$i]['GridPosition']);
                                        if ($cnt == 1) {
                                            echo '<li class="ltr">' . $rowno . '</li>';
                                        }
                                        //var_dump($ResSeatslevel3[$i]['Seatno']);
                                        if ($ResSeatslevel3[$i]['Seatno'] == 0) {
                                            echo '<li class="b"></li>';
                                        } else {
                                            if ($notype3 == "" || $notype3 == 0) {
                                                echo '<li class="bl x" id="' . $rowno . $ResSeatslevel3[$i]['Seatno'] . '" ></li>';
                                            } else {
                                                if ($ResSeatslevel3[$i]['Status'] == 'Available') {
                                                    echo '<li class="s bl" onclick="CngClass3(this,' . $notype3 . ',' . $ResSeatslevel3[$i]['Id'] . ',' . $eventsignupId . ');" id="' . $rowno . $ResSeatslevel3[$i]['Seatno'] . '" title="' . $ResSeatslevel3[$i]['Seatno'] . '"></li>';
                                                } else if ($ResSeatslevel3[$i]['Status'] == 'Booked') {
                                                    echo '<li class="r rd" id="' . $rowno . $ResSeatslevel3[$i]['Seatno'] . '" ></li>';
                                                } else if ($ResSeatslevel3[$i]['Status'] == 'Reserved') {
                                                    echo '<li class="b x" id="' . $rowno . $ResSeatslevel3[$i]['Seatno'] . '" ></li>';
                                                } else if ($ResSeatslevel3[$i]['Status'] == 'InProcess') {
                                                    echo '<li class="r rd" id="' . $rowno . $ResSeatslevel3[$i]['Seatno'] . '" ></li>';
                                                }
                                            }
                                        }
                                        ?>





                                        <?php
                                        if ($cnt == 32) {
                                            echo '</ul></div><div class="seatplan"><ul class="r" style="margin:0 auto; width:580px;">';
                                            $cnt = 0;
                                        }
                                    } //ends for loop
                                    ?>
                                </ul>
                            </div>
                            <h1 class="level"><?php echo $ticketsData[$ticketids['Balcony']]['name']; ?> (<img src="http://static.meraevents.com/content/images/rupee-icon.gif" width="12" height="12"/> <?php echo $ticketsData[$ticketids['Balcony']]['price']; ?>)</h1>
                            <div class="seatplan">
                                <ul class="r" style="margin:0 auto; width:580px;">

                                    <?php
                                    $notype4 = isset($calculationDetails['ticketsData'][$ticketids['Balcony']]['selectedQuantity']) ? $calculationDetails['ticketsData'][$ticketids['Balcony']]['selectedQuantity'] : 0;
                                    $cnt = 0;
                                    for ($i = 0; $i < count($ResSeatslevel4); $i++) {
                                        //$notype3 = 0;
                                        //$notype3 = isset($calculationDetails['ticketsData'][$ticketids['Balcony']]['selectedQuantity']) ? $calculationDetails['ticketsData'][$ticketids['Balcony']]['selectedQuantity'] : 0;
                                        $cnt++;
                                        $rowno = preg_replace('/[0-9]/', "", $ResSeatslevel4[$i]['GridPosition']);
                                        if ($cnt == 1) {
                                            echo '<li class="ltr">' . $rowno . '</li>';
                                        }
                                        //var_dump($ResSeatslevel4[$i]['Seatno']);
                                        if ($ResSeatslevel4[$i]['Seatno'] == 0) {
                                            echo '<li class="b"></li>';
                                        } else {
                                            if ($notype4 == "" || $notype4 == 0) {
                                                echo '<li class="bl x" id="' . $rowno . $ResSeatslevel4[$i]['Seatno'] . '" ></li>';
                                            } else {
                                                if ($ResSeatslevel4[$i]['Status'] == 'Available') {
                                                    echo '<li class="s bl" onclick="CngClass4(this,' . $notype4 . ',' . $ResSeatslevel4[$i]['Id'] . ',' . $eventsignupId . ');" id="' . $rowno . $ResSeatslevel4[$i]['Seatno'] . '" title="' . $ResSeatslevel4[$i]['Seatno'] . '"></li>';
                                                } else if ($ResSeatslevel4[$i]['Status'] == 'Booked') {
                                                    echo '<li class="r rd" id="' . $rowno . $ResSeatslevel4[$i]['Seatno'] . '" ></li>';
                                                } else if ($ResSeatslevel4[$i]['Status'] == 'Reserved') {
                                                    echo '<li class="b x" id="' . $rowno . $ResSeatslevel4[$i]['Seatno'] . '" ></li>';
                                                } else if ($ResSeatslevel4[$i]['Status'] == 'InProcess') {
                                                    echo '<li class="r rd" id="' . $rowno . $ResSeatslevel4[$i]['Seatno'] . '" ></li>';
                                                }
                                            }
                                        }
                                        ?>





                                        <?php
                                        if ($cnt == 32) {
                                            echo '</ul></div><div class="seatplan"><ul class="r" style="margin:0 auto; width:580px;">';
                                            $cnt = 0;
                                        }
                                    } //ends for loop
                                    ?>
                                </ul>
                            </div>
                            <div align="center">
                                <input type="button" name="SubmitAttendee" value="Confirm Seats"  onclick="return validat_reg_form('<?= $eventsignupId; ?>',<?php echo $calculationDetails['totalTicketQuantity']; ?>);"id="signin_submit" class="processbutton" style="margin:15px;" />
                            </div>
                            <?php include_once('includes/elements/payment_gateways.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>