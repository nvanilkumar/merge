<?php

@session_start();


include 'loginchk.php';

$uid = $_SESSION['uid'];
include_once("MT/cGlobali.php");
//include_once 'uploadToS3.php';
include_once 'includes/common_functions.php';
$commonFunctions = new functions();

$Global = new cGlobali();

/* if(isset($_REQUEST['bkimg']))
  {

  $EventId = $_REQUEST['EventId'];
  $update_query="UPDATE event SET EventBackground='' WHERE Id='".$EventId."'";
  $Global->ExecuteQuery($update_query);
  // mysql_close();
  }
 */
if (isset($_REQUEST['newStatus'])) {
    $EventId = $_REQUEST['EventId'];
    $IsFamous = $_REQUEST['newStatus'];
    $solrStatus=popularityEventCurl($EventId,$IsFamous);
    $solrStatusOutput = json_decode($solrStatus,true);
             
    if($solrStatusOutput['response']['updatedPriority'] == 'Success'){
        $update_query = "UPDATE event SET popularity='" . $IsFamous . "' WHERE id='" . $EventId . "'";
        $Global->ExecuteQuery($update_query);
    }else{
        $sdata['solrMessage']='Something went wrong on solr side, please try again';
        $sdata['status']=false;
    }  
// mysql_close();
}
if ($_REQUEST['Save'] == "Save") {

    $PEventId = $_REQUEST['PEventId'];
    $perc = $_REQUEST['perc'];
    $Commentperc = $_REQUEST['Commentperc'];
    $TicketType = $_REQUEST['TicketType'];
    /*$_FILES['EventBg']['tmp_name'];

    if ($_FILES['EventBg']['error'] == 0) {

        $blacklist1 = array(".php", ".phtml", ".php3", ".php4");
        $php1 = 0;
        foreach ($blacklist1 as $item) {
            if (preg_match("/$item\$/i", $_FILES['EventBg']['name'])) {
                $php1++;
            }
        }
        if ($php1 == 0) {
            if (isset($PEventId)) {
                //make the max event id for the event id already exist
                $EBannerPath = "../" . _HTTP_Content . "/eventbanner/" . $PEventId;
                $MaxEventId = $PEventId;

                if (!is_dir($EBannerPath)) {
                    mkdir($EBannerPath, 0777, TRUE);
                }
            }
            $banner_path = "../" . _HTTP_Content . "/eventbanner/" . $MaxEventId . "/" . $_FILES['EventBg']['name'];
            $banner_path1 = "eventbanner/" . $MaxEventId . "/" . $_FILES['EventBg']['name'];
            move_uploaded_file($_FILES['EventBg']['tmp_name'], $banner_path);
            //writing image paths to the  textfile (imageUploadInfo.txt)
            $uploadedImages = fopen("../TextFiles/imageUploadInfo.txt", 'a');
            fwrite($uploadedImages, $banner_path1 . "\r\n"); //writing into text file all the image paths, just to know the count of them
            fclose($uploadedImages);*/

            //need to call optimization script (optimizeimage.sh)
            //$OptimizeImageScriptError = $commonFunctions->optimizeAllImages(_OPTIMIZEPATH . "optimizeimage.sh");
            /*****************************Uploading to S3***************************************** */
            /*if ($_SERVER['HTTP_HOST'] != "localhost") {
                $uploadToS3Error = $commonFunctions->uploadImageToS3($banner_path, _BUCKET);
            // $modPath=  substr($banner_path, 3);
            // $S3Sucess=uploadToS3($banner_path, $modPath, _BUCKET);
            // if (file_exists($banner_path) && $S3Sucess) {
            //  unlink($banner_path);
            // }
            }
            //File uploading successfull
        }
    } else if ($_REQUEST['backimg'] != "") {
        $banner_path1 = $_REQUEST['backimg'];
    } else {
        $banner_path1 = "";
    }*/
//,TicketType='" . $TicketType . "',EventBackground='" . $banner_path1 . "'
    $update_query = "UPDATE eventsetting SET percentage='" . $perc . "',commentpercentage='" . $Commentperc . "' WHERE eventid='" . $PEventId . "'";
    $Global->ExecuteQuery($update_query);
//   mysql_close();
}

if (isset($_REQUEST['newNotMoreStatus'])) {
    $EventId = $_REQUEST['EventId'];
    $newNotMoreStatus = $_REQUEST['newNotMoreStatus'];

    $update_query = "UPDATE eventsetting SET notmore='" . $newNotMoreStatus . "' WHERE eventid='" . $EventId . "'";
    $Global->ExecuteQuery($update_query);
    //   mysql_close();
}

if (isset($_REQUEST['newNoDates'])) {
    $EventId = $_REQUEST['EventId'];
    $newNoDates = $_REQUEST['newNoDates'];

    $update_query = "UPDATE eventsetting SET nodates='" . $newNoDates . "' WHERE eventid='" . $EventId . "'";
    $Global->ExecuteQuery($update_query);
//   mysql_close();
}
if (isset($_REQUEST['newNeedVolStatus'])) {
    $EventId = $_REQUEST['EventId'];
    $newNeedVolStatus = $_REQUEST['newNeedVolStatus'];

    $update_query = "UPDATE eventsetting SET needvol='" . $newNeedVolStatus . "' WHERE eventid='" . $EventId . "'";
    $Global->ExecuteQuery($update_query);
//  mysql_close();
}
if (isset($_REQUEST['newContactDisp'])) {


    $EventId = $_REQUEST['EventId'];
    $newContactDisp = $_REQUEST['newContactDisp'];

    $update_query = "UPDATE eventdetail SET contactdisplay='" . $newContactDisp . "' WHERE eventid='" . $EventId . "'";
    $Global->ExecuteQuery($update_query);
//  mysql_close();
}
if (isset($_REQUEST['newwidgetdisp'])) {

    $EventId = $_REQUEST['EventId'];
    $newwidgetdisp = $_REQUEST['newwidgetdisp'];

    $update_query = "UPDATE events SET widgetdisp='" . $newwidgetdisp . "' WHERE Id='" . $EventId . "'";
    $Global->ExecuteQuery($update_query);
    //  mysql_close();
}

if (isset($_REQUEST['newnodiscount'])) {

    $EventId = $_REQUEST['EventId'];
    $newnodiscount = $_REQUEST['newnodiscount'];

    $update_query = "UPDATE eventsetting SET nodiscount='" . $newnodiscount . "' WHERE eventid='" . $EventId . "'";
    $Global->ExecuteQuery($update_query);
    //   mysql_close();
}

if (isset($_REQUEST['newfbcomment'])) {
    $EventId = $_REQUEST['EventId'];
    $newfbcomment = $_REQUEST['newfbcomment'];

    $update_query = "UPDATE eventsetting SET nofbcomments='" . $newfbcomment . "' WHERE eventid='" . $EventId . "'";
    $Global->ExecuteQuery($update_query);
//   mysql_close();
}





if ($_REQUEST['submit'] == 'Show Events' || ($_REQUEST['txtSDt']!="" && $_REQUEST['txtEDt']!="")) {
    $SDt = $_REQUEST['txtSDt'];
    $SDtExplode = explode("/", $SDt);
    $SDtYMD = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0] . ' 00:00:00';

    $EDt = $_REQUEST['txtEDt'];
    $EDtExplode = explode("/", $EDt);
    $EDtYMD = $EDtExplode[2] . '-' . $EDtExplode[1] . '-' . $EDtExplode[0] . ' 23:59:59';
    //TicketType,EventBackground,
    $EventsQuery = "SELECT e.id AS Id, e.title AS Title, e.startdatetime AS StartDt, "
                        . " e.enddatetime AS EndDt, e.popularity AS IsFamous, e.ownerid AS UserID, "
                        . "es.notmore AS NotMore, es.needvol AS NeedVol,"
                        . "ed.contactdisplay AS ContactDisp, es.percentage AS perc,es.commentpercentage AS Commentperc, "
                        . "es. nofbcomments AS Nofbcomments, es.nodates AS NoDates, es.nodiscount AS NoDiscount "
                        . "FROM eventsetting es JOIN event e ON e.id=es.eventid "
                        . "JOIN eventdetail ed ON ed.eventid=e.id "
                        . "WHERE 1 AND (startdatetime >= '" . $SDtYMD . "' AND enddatetime <='" . $EDtYMD . "') "
                        . "AND title!='' "
                        . "ORDER BY startdatetime, title ASC";
    $EventsOfMonth = $Global->SelectQuery($EventsQuery);
} else {
	$SDt = date ("d/m/Y", mktime (0,0,0,date("m"),(date("d")-1),date("Y")));
	$EDt =date ("d/m/Y", mktime (0,0,0,date("m"),(date("d")-1),date("Y")));
   // $SDt = $_REQUEST['txtSDt'];
    $SDtExplode = explode("/", $SDt);
    $SDtYMD = $SDtExplode[2] . '-' . $SDtExplode[1] . '-' . $SDtExplode[0] . ' 00:00:00';
    $SDtYMD =$commonFunctions->convertTime($SDtYMD, DEFAULT_TIMEZONE);

   // $EDt = $_REQUEST['txtEDt'];
    $EDtExplode = explode("/", $EDt);
    $EDtYMD = $EDtExplode[2] . '-' . $EDtExplode[1] . '-' . $EDtExplode[0] . ' 23:59:59';
    $EDtYMD =$commonFunctions->convertTime($EDtYMD, DEFAULT_TIMEZONE);

    $EventsQuery = "SELECT e.id AS Id, e.title AS Title, e.startdatetime AS StartDt, "
                        . " e.enddatetime AS EndDt, e.popularity AS IsFamous, e.ownerid AS UserID, "
                        . "es.notmore AS NotMore, es.needvol AS NeedVol,"
                        . "ed.contactdisplay AS ContactDisp, es.percentage AS perc, es.commentpercentage AS Commentperc, "
                        . "es. nofbcomments AS Nofbcomments, es.nodates AS NoDates, es.nodiscount AS NoDiscount "
                        . "FROM eventsetting es JOIN event e ON e.id=es.eventid "
                        . "JOIN eventdetail ed ON ed.eventid=e.id "
                        . " WHERE 1 AND (startdatetime >= '" . $SDtYMD . "' AND enddatetime <='" . $EDtYMD . "') "
                        . "AND title!='' "
                        . "ORDER BY startdatetime, title ASC";
    $EventsOfMonth = $Global->SelectQuery($EventsQuery);
}

function popularityEventCurl($EventId,$IsFamous){
    $url = _HTTP_SITE_ROOT . "/api/event/solrEventPopularityStatus"; 
    $loginUserId=$_SESSION['uid'];
    // Get cURL resource
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => array(
            eventId => $EventId,
            keyValue => $loginUserId,
            popularityValue=>$IsFamous,
            updatetype=>"prioritystatus"
                ),
        CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json')
    ));
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    // Close request to clear up some resources
    curl_close($curl);
    return $resp;
}

include_once 'templates/events_of_month.tpl.php';
?>