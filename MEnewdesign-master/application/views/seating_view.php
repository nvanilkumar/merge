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
    <?php } else { ?>
        <div class="row">
            <div>
            <?php $ticketids = array('Level-1' => 75027, 'Level-2' => 75028, 'Balcony' => 75029);?>
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
                            <table border="0" align="right" cellpadding="0" style="text-align:right;">
                                <tr>
                                    <td height="30"><img src="<?php echo $availableImage; ?>" width="13" height="15" /></td>
                                    <td height="30" style="text-align:left;">Available</td>
                                    <td height="30">&nbsp;</td>

                                    <td height="30"><img src="<?php echo $currentBookingImage; ?>" width="13" height="15" class="style3" /></td>
                                    <td height="30" style="text-align:left;">Current Booking</td>
                                    <td height="30">&nbsp;</td>

                                    <td height="30"><img src="<?php echo $otherAreaImage; ?>" width="13" height="15" /></td>
                                    <td height="30" style="text-align:left;">Other Area </td>
                                    <td height="30">&nbsp;</td>

                                    <td height="30"><img src="<?php echo $bookedImage; ?>" width="13" height="15" /></td>
                                    <td height="30" style="text-align:left;">Booked</td>
                                </tr>
                            </table>
                            <div style="clear:both;"></div>

                            <div id="stage"></div>

                            <h1 class="level"><?php echo $ticketsData[$ticketids['Level-1']]['name']; ?> (<img src="http://content.meraevents.com/images/rupee-icon.gif" width="12" height="12" /> <?php echo $ticketsData[$ticketids['Level-1']]['price']; ?>)</h1>
                            <div class="seatplan">
                                <ul class="r">

                                    <?php
                                    $notype1 = isset($calculationDetails['ticketsData'][$ticketids['Level-1']]['selectedQuantity']) ? $calculationDetails['ticketsData'][$ticketids['Level-1']]['selectedQuantity'] : 0;
                                    $cnt = 0;
                                    for ($i = 0; $i < count($ResSeatslevel1); $i++) {
                                        $cnt++;
                                        $rowno = substr($ResSeatslevel1[$i]['GridPosition'], 0, 1);
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



                                        if ($cnt == 74) {
                                            echo '</ul></div><div class="seatplan"><ul class="r">';
                                            $cnt = 0;
                                        }
                                    } //ends for loop
                                    ?>
                                </ul>
                            </div>
                            <h1 class="level"><?php echo $ticketsData[$ticketids['Level-2']]['name']; ?> (<img src="http://content.meraevents.com/images/rupee-icon.gif" width="12" height="12" /> <?php echo $ticketsData[$ticketids['Level-2']]['price']; ?>)</h1>
                            <div class="seatplan">
                                <ul class="r">

                                    <?php
                                    $cnt = 0;
                                    $notype2 = isset($calculationDetails['ticketsData'][$ticketids['Level-2']]['selectedQuantity']) ? $calculationDetails['ticketsData'][$ticketids['Level-2']]['selectedQuantity'] : 0;
                                    for ($i = 0; $i < count($ResSeatslevel2); $i++) {
                                        $cnt++;
                                        //$notype2 = 0;
                                        //$notype2 = isset($calculationDetails['ticketsData'][$ticketids['Level-2']]['selectedQuantity']) ? $calculationDetails['ticketsData'][$ticketids['Level-2']]['selectedQuantity'] : 0;
                                        $rowno = substr($ResSeatslevel2[$i]['GridPosition'], 0, 1);
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
                                        if ($cnt == 74) {
                                            echo '</ul></div><div class="seatplan"><ul class="r">';
                                            $cnt = 0;
                                        }
                                    } //ends for loop
                                    ?>
                                </ul>
                            </div>
                            <h1 class="level"><?php echo $ticketsData[$ticketids['Balcony']]['name']; ?> (<img src="http://content.meraevents.com/images/rupee-icon.gif" width="12" height="12"/> <?php echo $ticketsData[$ticketids['Balcony']]['price']; ?>)</h1>
                            <div class="seatplan">
                                <ul class="r">

                                    <?php
                                    $notype3 = isset($calculationDetails['ticketsData'][$ticketids['Balcony']]['selectedQuantity']) ? $calculationDetails['ticketsData'][$ticketids['Balcony']]['selectedQuantity'] : 0;
                                    $cnt = 0;
                                    for ($i = 0; $i < count($ResSeatslevel3); $i++) {
                                        //$notype3 = 0;
                                        //$notype3 = isset($calculationDetails['ticketsData'][$ticketids['Balcony']]['selectedQuantity']) ? $calculationDetails['ticketsData'][$ticketids['Balcony']]['selectedQuantity'] : 0;
                                        $cnt++;
                                        $rowno = substr($ResSeatslevel3[$i]['GridPosition'], 0, 2);
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
                                        if ($cnt == 74) {
                                            echo '</ul></div><div class="seatplan"><ul class="r">';
                                            $cnt = 0;
                                        }
                                    } //ends for loop
                                    ?>
                                </ul>
                            </div>
                            <div align="center">
                                <input type="button" name="SubmitAttendee" value="Confirm Seats"  onclick="return validat_reg_form('<?= $eventsignupId; ?>',<?php echo round($calculationDetails['ticketsData'][$ticketids['Level-1']]['selectedQuantity'] + $calculationDetails['ticketsData'][$ticketids['Level-2']]['selectedQuantity'] + $calculationDetails['ticketsData'][$ticketids['Balcony']]['selectedQuantity']); ?>);"id="signin_submit" class="processbutton" style="margin:15px;" />
                            </div>
                            <?php include_once('includes/elements/payment_gateways.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>