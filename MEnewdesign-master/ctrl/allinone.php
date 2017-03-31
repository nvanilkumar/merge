<?php

include('MT/cGlobali.php');
error_reporting(-1);
ini_set('display_errors', 1);

$conn = new cGlobali();

if ($_SERVER['HTTP_HOST'] == 'menew.com') {
    $ticketids = array('Rookie' => 84329, 'Trainee' => 84328, 'Scouted' => 84327);
    $eventId = '96703'; //$c_1
} elseif ($_SERVER['HTTP_HOST'] == 'dev2.meraevents.com') {
    $ticketids = array('Rookie' => 84326, 'Trainee' => 84325, 'Scouted' => 84324);
    $eventId = '96702'; //$c_1
} elseif ($_SERVER['HTTP_HOST'] == 'stage.meraevents.com') {
    $ticketids = array('Rookie' => 92203, 'Trainee' => 92204, 'Scouted' => 92205);
    $eventId = '101737'; //$c_1
} elseif ($_SERVER['HTTP_HOST'] == 'www.meraevents.com') {
    $ticketids = array('Rookie' => 90913, 'Trainee' => 90914, 'Scouted' => 90915);
    $eventId = '101066'; //$c_1
}

$truncate = "update `venueseat` set deleted=1 WHERE `EventId`=$eventId";
$conn->ExecuteQuery($truncate);
echo "data truncated<br>";
$venueQry = "select id from theatervenue where Venue='Korean Embassy' and deleted=0 limit 1";
$venueRes = $conn->SelectQuery($venueQry);
$venueId = 0;
if (count($venueRes) > 0) {
    $venueId = $venueRes[0]['id'];
}
//print_r($venueId);exit;

$query = "INSERT INTO `venueseat` ( `Id` , `VenueId` , `GridPosition` , `Seatno` , `Price` , `Type` , `Status` , `EventId` , `EventSIgnupId` , `BDate`,`ticketid` )
VALUES ";


$ticketPrices = array('Rookie' => 6000, 'Trainee' => 2500, 'Scouted' => 1500);
//seat rows alphabet wise
$alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'ZZ');
//echo count($alpha); exit;
//a+1 b-1 c+1 d-1 e+1 f-1 g+1 h-1 i+1 j+1 k+1 l+1 m+1 n+1 o+1 p+1 q+1 r+1 s+1 t+1 u+1 v+1 w+1 x+1 y+1 z+1 zz+1
$level = array('Rookie' => '0-4', 'Trainee' => '5-16', 'Scouted' => '17-26');
//  -1 +1 -1 +1 -1 +1 +1 +1 +1 +1 +1 +1 +1 +1 +1 +1 +1 +1 +1 +1 +1 +1 +1 +1
$seattype = array('Available', 'Booked', '', 'Inprocess');
$noofSeats = array('A' => 34, 'B' => 35, 'C' => 36, 'D' => 37, 'E' => 40, 'F' => 35, 'G' => 40, 'H' => 41, 'I' => 42, 'J' => 38, 'K' => 38, 'L' => 48, 'M' => 32
    , 'N' => 46, 'O' => 46, 'P' => 44, 'Q' => 56, 'R' => 46, 'S' => 46, 'T' => 48, 'U' => 48, 'V' => 50, 'W' => 50, 'X' => 52, 'Y' => 52, 'Z' => 50, 'ZZ' => 58);
$midofSeats = array('A' => 17, 'B' => 18, 'C' => 18, 'D' => 19, 'E' => 20, 'F' => 18, 'G' => 20, 'H' => 21, 'I' => 21, 'J' => 19, 'K' => 19, 'L' => 24, 'M' => 16
    , 'N' => 23, 'O' => 23, 'P' => 24, 'Q' => 28, 'R' => 23, 'S' => 23, 'T' => 24, 'U' => 24, 'V' => 25, 'W' => 25, 'X' => 26, 'Y' => 26, 'Z' => 25, 'ZZ' => 29);
$cntValue = array('A' => 1, 'B' => -1, 'C' => +1, 'D' => -1, 'E' => +1, 'F' => -1, 'G' => +1, 'H' => -1, 'I' => +1, 'J' => +1, 'K' => +1, 'L' => +1, 'M' => 1
    , 'N' => 1, 'O' => 1, 'P' => 1, 'Q' => 1, 'R' => 1, 'S' => 1, 'T' => 1, 'U' => 1, 'V' => 1, 'W' => 1, 'X' => 1, 'Y' => 1, 'Z' => 1, 'ZZ' => 1);
$rowwise = array(
    'Rookie-A' => '1,07x-14i-05x-06i-06x-14i-L',
    'Rookie-B' => '1,07x-14i-05x-07i-05x-14i-L',
    'Rookie-C' => '1,07x-14i-04x-08i-05x-14i-L',
    'Rookie-D' => '1,07x-14i-04x-09i-04x-14i-L',
    'Rookie-E' => '1,06x-15i-03x-10i-03x-15i-L',
    'Trainee-F' => '1,09x-12i-03x-11i-02x-12i-L',
    'Trainee-G' => '1,07x-14i-02x-12i-02x-14i-L',
    'Trainee-H' => '1,07x-14i-01x-13i-02x-14i-L',
    'Trainee-I' => '1,07x-14i-01x-14i-01x-14i-L',
    'Trainee-J' => '1,07x-14i-01x-5i-04x-05i-01x-14i-L',
    'Trainee-K' => '1,07x-14i-01x-5i-04x-05i-01x-14i-L',
    'Trainee-L' => '1,03x-24i-04x-24i-L',
    'Trainee-M' => '1,11x-16i-04x-16i-L',
    'Trainee-N' => '1,03x-14i-01x-09i-03x-09i-02x-14i-L',
    'Trainee-O' => '1,03x-14i-01x-09i-03x-09i-02x-14i-L',
    'Trainee-P' => '1,03x-14i-01x-10i-02x-10i-01x-14i-L',
    'Trainee-Q' => '1,01x-56i-01x-L',
    'Scouted-R' => '1,01x-13i-02x-10i-06x-10i-03x-13i-L',
    'Scouted-S' => '1,01x-13i-02x-10i-06x-10i-03x-13i-L',
    'Scouted-T' => '1,01x-13i-02x-11i-04x-11i-03x-13i-L',
    'Scouted-U' => '1,01x-13i-02x-11i-04x-11i-03x-13i-L',
    'Scouted-V' => '1,01x-13i-01x-12i-04x-12i-02x-13i-L',
    'Scouted-W' => '1,01x-13i-01x-12i-04x-12i-02x-13i-L',
    'Scouted-X' => '1,01x-13i-01x-13i-02x-13i-02x-13i-L',
    'Scouted-Y' => '1,01x-13i-01x-13i-02x-13i-02x-13i-L',
    'Scouted-Z' => '1,03x-11i-01x-14i-01x-14i-01x-11i-L',
    'Scouted-ZZ' => '1,58i-L',
);

$maxcount = 58;

foreach ($level as $levelkey => $levelvalue) {
    $ticketid = $ticketids[$levelkey];
    $explevel = explode('-', $levelvalue); //0-12
    $ticketPrice = $ticketPrices[$levelkey];

    $reseredStatus = array('E');
//running loop for alphabet array containing another loop 74times for seatnumbers
    foreach ($alpha as $key => $value) {
        $alpabet = $value;
        if (($key >= $explevel[0]) && ($explevel[1] >= $key)) { // (0>=0 && 12>=0) 
            for ($i = 1; $i <= $maxcount; $i++) {
                $rowcheck = $levelkey . '-' . $alpabet;
                $seatStatus = 'Available';
                if (in_array($alpabet, $reseredStatus)) {
                    $seatStatus = 'Reserved';
                }
//checking if alignment of a particular row is set or not
                echo "=>" . $rowcheck . "<br />";
                if (array_key_exists($rowcheck, $rowwise)) {
                    $count = 1;
                    //$rowwise[$rowcheck])=35,15x-09i-04x-17i-05x-09i-L
                    $explode = explode(',', $rowwise[$rowcheck]);
                    $startcount = $explode[0];
                    //echo "#".$startcount;

                    $explode2 = explode('-', $explode[1]);

                    foreach ($explode2 as $ekey => $evalue) {
                        $lastchars = substr($evalue, -1); //x
                        $number = substr($evalue, 0, 2); //15
                        if (strcmp('x', $lastchars) == 0) {
                            for ($x = 0; $x < $number; $x++) {
                                $seatgrid = $alpabet . $i;
                                $val = 0;
                                $query.=" (NULL , '$venueId', '" . $seatgrid . "', '" . $val . "', '$ticketPrice', '" . $levelkey . "', '$seatStatus', '$eventId', '0', '','" . $ticketid . "'),";
                                $i++;
                            }
                            continue;
                        } else if (strcmp('i', $lastchars) == 0) {

                            for ($x = 0; $x < $number; $x++) {
                                $seatgrid = $alpabet . $i;


                                $query.=" (
NULL , '$venueId', '" . $seatgrid . "', '" . $startcount . "', '$ticketPrice', '" . $levelkey . "', '$seatStatus', '$eventId', '0', '','" . $ticketid . "'
),";
                                $i++;
                                if ($count < $midofSeats[$alpabet]) {
                                    $startcount+=2;
                                } elseif ($count == $midofSeats[$alpabet]) {
                                    $startcount = $startcount + $cntValue[$alpabet];
                                } else {
                                    $startcount = $startcount - 2;
                                }
                                $count++;
                            }
                            continue;
                        } else {

                            $remaining = $maxcount - $i + 1;
                            //echo "=>".$rowcheck."#".$remaining."<br />";
                            //echo $remaining."<br>";

                            for ($x = 0; $x < $remaining; $x++) {
                                $seatgrid = $alpabet . $i;
                                $val = 0;
                                $query.=" (NULL , '$venueId', '" . $seatgrid . "', '" . $val . "', '$ticketPrice', '" . $levelkey . "', '$seatStatus', '$eventId', '0', '','" . $ticketid . "'),";
                                $i++;
                            }
                            continue;
                        }
                    }
                } else {
                    $seatgrid = $alpabet . $i;

                    $val = $i;
                    $query.=" (NULL , '$venueId', '" . $seatgrid . "', '" . $val . "', '$ticketPrice', '" . $levelkey . "', '$seatStatus', '$eventId', '0', '','" . $ticketid . "'),";
                }


//echo "-".$i."<br />";
            } //end of for with max count
        }//end of char code check
    }
}

$query = substr($query, 0, -1);

echo $query; //exit;

$conn->ExecuteQuery($query);



