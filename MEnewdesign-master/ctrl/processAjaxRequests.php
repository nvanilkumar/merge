<?php
/*************************************************************************************************************************
 *	File Name:	processAjaxRequests.php
 *	Deatils:	we can use this page for most of the Ajax calls
 *	Created:	by Shashi
 *	
 *			 
 *************************************************************************************************************************/
session_start();

include_once("MT/cGlobali.php"); 
include_once("MT/cStates.php");
include_once("MT/cLogin.php");
 include_once 'includes/common_functions.php';
 include("delegatepass.php");
    $commonFunctions=new functions();
    $delegatePass=new delegatePass();
    //$_GET=$commonFunctions->stripData($_GET);
    //$_POST=$commonFunctions->stripData($_POST);
    //$_REQUEST=$commonFunctions->stripData($_REQUEST);

$Globali = new cGlobali();
//echo "here";
// this is for updating display customfield value on print pass
if(isset($_POST['updateDisplayOnPrintpass']) )
{
	$cid=$_POST['cid'];
	$type=$_POST['type'];
	
	if($type==1){
        $displayOnTicket=1;
    }else{$displayOnTicket=0;}
    
        //Check the selected customfields to display on print pass
    $check_sql="SELECT count(id) FROM  `customfield` 
         WHERE  `eventid` = (select Distinct eventid from customfield where id =".$cid.") 
         and  `displayOnTicket` = 1";
    $display_count=$Globali->GetSingleFieldValue($check_sql);
    if($display_count < 4 || $displayOnTicket == 0 ){
        $sql="update `customfield` set `displayonticket`=? where `id`=?";
        $ecfStmt=$Globali->dbconn->prepare($sql);
        $ecfStmt->bind_param("id",$displayOnTicket,$cid);
        $ecfStmt->execute();
        $ecfStmt->close();
    }
} 


// this is for updating customfield mandatory or not
if(isset($_POST['updateCustomFieldMandatory']) )
{
	$type=$_POST['type'];
	$EventCustomFieldsId=$_POST['EventCustomFieldsId'];
	
	if($type==1){$mandatory=0;}else{$mandatory=1;}
	
	$sql="update `customfield` set `fieldmandatory`=? where `Id`=?";
	
	$ecfStmt=$Globali->dbconn->prepare($sql);
	$ecfStmt->bind_param("id",$mandatory,$EventCustomFieldsId);
	$ecfStmt->execute();
	$ecfStmt->close();
} 

// fetching states by country id
if(isset($_GET['getStates']) )
{
	?><option value="" >--Select State--</option><?php
	if($_GET['uprofile_country'] > 0 || $_GET['uprofile_country']!='')
	{
		$selState = "SELECT id As Id, `name` As State FROM state WHERE countryid='".$Globali->dbconn->real_escape_string($_GET['uprofile_country'])."' Order by name";
		$States = $Globali->SelectQuery($selState);

		for($i = 1; $i < count($States); $i++)
		{
	?>
		<option value="<?=$States[$i]['Id']?>" <?php if($_GET['uprofile_pstate']==$States[$i]['Id']) { ?> selected="selected" <?php } ?>><?=$States[$i]['State']?></option>
	<?php
		}
		if(count($States)>0){ ?>
			<option value="<?=$States[0]['Id']?>" <?php if($_GET['uprofile_pstate']==$States[0]['Id']) { ?> selected="selected" <?php } ?>><?=$States[0]['State']?></option>
		<?php }
	}
	else
	{
//		$selState = "SELECT Id, State FROM States WHERE CountryId='14'";
//		$States = $Globali->SelectQuery($selState);

		//for($i = 0; $i < count($States); $i++)
		//{
	?>
		
	<?php
		//}
	}
}


// fetching cities by state id
if(isset($_GET['getCities']) )
{
	?><option value="" >--Select CIty--</option><?php
	if($_GET['uprofile_pstate'] > 0 || $_GET['uprofile_pstate']!='')
	{
		$selCity = "SELECT c.id As Id,c.`name` As  City FROM statecity as sc 
		JOIN c.city on c.id = sc.cityid WHERE sc.stateid='".$Globali->dbconn->real_escape_string($_GET['uprofile_pstate'])."' Order by c.`name`";
		$Cities = $Globali->SelectQuery($selCity);

		for($i = 0; $i < count($Cities); $i++)
		{
	if($Cities[$i]['City']!='Other'){
                            ?>
                               <option value="<?=$Cities[$i]['Id']?>" <?php if($_GET['uprofile_pcity']==$Cities[$i]['Id']) { ?> selected="selected" <?php } ?>><?=$Cities[$i]['City']?></option>
	<?php
									}else{
										$otherCityId=$Cities[$i]['Id'];
									}
       }
	    if($otherCityId>0){ ?>
								 <option value="<?=$otherCityId?>" <?php if($_REQUEST['profile_pcity']==$otherCityId || $Events->CityId==$otherCityId) { ?> selected="selected" <?php } ?>>Other</option>
                                <?php }
	}
	
}


// fetching locations by city id
if(isset($_GET['getLocations']) )
{
	?><option value="" >--Select Locality--</option><?php
	if($_GET['cityid'] > 0 || $_GET['cityid']!='')
	{
		$selLocations = "SELECT Id, Loc FROM Location WHERE CityId='".$Globali->dbconn->real_escape_string($_GET['cityid'])."'";
		$Locations = $Globali->SelectQuery($selLocations);

		for($l = 0; $l < count($Locations); $l++)
		{
		?>
			<option value="<?=$Locations[$l]['Id']?>"><?=$Locations[$l]['Loc']?></option>
		<?php
		}
	}
}
// function to check currency code exists for this event or not
if(isset($_GET['currConversionESIDchk']))
{
	$esid=strtoupper(trim($_GET['esid']));
	
	$sql="select id As Id,fromcurrencyid as CurrencyId, conversionrate As conversionRate,paymenttransactionid As PaymentTransId,convertedamount As paypal_converted_amount from `eventsignup` where `id`='".$Globali->dbconn->real_escape_string($esid)."' and paymentgatewayid=4";


	$data=$Globali->SelectQuery($sql);
	if(count($data)>0) //record found
	{
            if(strcmp($data[0]['PaymentTransId'],'A1')==0){
                echo 6;
            }else{
		if($data[0]['CurrencyId']==1 && $data[0]['paypal_converted_amount']==0) //INR transaction, conversion not required
		{
			echo 3; //already converted
		}
		elseif($data[0]['CurrencyId']==3) //free transaction, conversion not required
		{
			echo 4; //already converted
		}
		elseif($data[0]['conversionRate']!=1)
		{
			echo 2; //already converted
		}
		else
		{
			echo 1; //you can proceed with currency conversion
		}
            }
	}
	else
	{
		echo 5; // no record found with that eventsignup id
	}
}

// adding/updating topbanner or side banner ad clicks
if(isset($_POST['clickStats']))
{
	$id=$_POST['id'];
	if($_POST['adtype']=='topad')
	{
		$sql="update `Banners` set `clickscount`=clickscount+1 where `Id`=?";
	}
	elseif($_POST['adtype']=='sidead')
	{
		$sql="update `SideBanners` set `clickscount`=clickscount+1 where `Id`=?";
	}
	
	$clicksStmt=$Globali->dbconn->prepare($sql);
	$clicksStmt->bind_param("i",$id);
	$clicksStmt->execute();
	$clicksStmt->close();
	
	
	echo 1;
}



// checking URL availablity while addidng/updating event
if(isset($_POST['checkURLavailability']))
{
	$url=$_POST['url'];
	$type=$_POST['type'];
        $eventCond=NULL;
	if(strcmp($type,'edit')==0){
            $eventCond=" and Id!='".$_POST['EventId']."'";
        }
	$sql="select `Id` from `events` where `URL`='".$Globali->dbconn->real_escape_string($url)."'".$eventCond;

	$data=$Globali->SelectQuery($sql);
	if(count($data)>0)
        {
                echo 1; // URL already exist
        }
        else
        {
                echo 0; // URL not available, you can proceed
        }
	
}

// function to check eventsignupid available or not
if(isset($_POST['checkEventSignupId']))
{
	$id=$_POST['id'];
	
	$sql="select id as `Id` from eventsignup as `EventSignup` where id='".$Globali->dbconn->real_escape_string($id)."'";

	$data=$Globali->SelectQuery($sql);
	if(count($data)>0)
	{
		echo 'success'; // URL already exist
	}
	else
	{
		echo 'error'; // URL not available, you can proceed
	}
}


if(isset($_POST['call'])){
switch($_POST['call']){
	case 'checkEmail':
                $eventId=$_POST['eventId'];
                if(!in_array($eventId, $checkVivo)){
                    $status='false';
					
					$emailEx=explode("@",$_POST['email']);
					if(strtolower($emailEx[1])=="mailinator.com")
					{
						$status='blocked';
					}
					else
					{
						$query="SELECT Id FROM user WHERE  Email='".$_POST['email']."'";
						$resQuery=$Globali->SelectQuery($query);
						if(count($resQuery)>0){
								$status=$resQuery[0]['Id'];
						}
					}
					
                    
                }else{
                    $status='vivo_invalid';
                    $query="SELECT Id FROM vivo_bms WHERE  Email='".$_POST['email']."' and count<10";
                    $resQuery=$Globali->SelectQuery($query);
                    if(count($resQuery)>0){
                            $status='vivo_valid';
                    }
                }
		
		echo $status;
	break;
        case 'checkBookId':
                $eventId=$_POST['eventId'];
                $bookId=$_POST['bookId'];
                $emailId=$_POST['email'];
                $status='vivo_invalid';
                $query="SELECT Id FROM vivo_bms WHERE  Email='".$emailId."' and bookingid='".$bookId."' and count<10";
                $resQuery=$Globali->SelectQuery($query);
                if(count($resQuery)>0){
                        $status='vivo_valid';
                }
                echo $status;
	break;
        case 'isOrganizer':
		$status=array('isUser'=>true);
		$query="SELECT Id FROM user WHERE  EMail='".$_POST['email']."'";
		$resQuery=$Globali->SelectQuery($query);
		if(count($resQuery)>0){
			$query1="SELECT count(*) as count FROM organizer WHERE  UserId='".$resQuery[0]['Id']."'";
			$resQuery1=$Globali->SelectQuery($query1);
			$status['isOrganizer']=$resQuery1[0]['count']>0?true:false;
		}else{
			$status['isUser']=false;
		}
		 echo json_encode($status);
	break;
        case 'resendPassToMail':
                $ismobile= $_REQUEST['ismobile']==1?true:false;
                if($_POST['EmailPass'] == 'EmailPass' || $ismobile)
                {

                    $Email_validation_event_array = array();
                    if(strcmp($hostname,'www.meraevents.com')==0 || strcmp($hostname,'meraevents.com')==0) {

                        $Email_validation_event_array = array(72553=> array('netcracker.com'));

                    } elseif(strcmp($hostname,'stage.meraevents.com')==0) {

                        $Email_validation_event_array = array(55483=> array('netcracker.com'));

                    }
//                    else {
//                        $Email_validation_event_array = array(64954=>array('netcracker.com'));
//                    }

                    $success = 1;

                    $valid_email_array = $Email_validation_event_array[$_POST['EventId']];
                    if(is_array($valid_email_array) && count($valid_email_array) > 0) {

                        $domain1 = array_pop(explode('@', $_POST['sendPassToMail']));
                        if(in_array($domain1,$valid_email_array)) {
                        } else {
                            $success = 0;
                        }
                    }
                    
                    if($success == 1) {
                        include("Extras/mpdf/mpdf.php");
                        $mpdf=new mPDF();
                        $sqluser="select FirstName,Email,Company from user where Id='".$Globali->dbconn->real_escape_string($_SESSION['uid'])."'";
                        $resuser = $Globali->SelectQuery($sqluser);
                        $selEveRes=$Globali->SelectQuery("SELECT display_amount,Title  FROM events WHERE Id = '".$Globali->dbconn->real_escape_string($_POST['EventId'])."'"); 
                        $display_print_pass_amount=$eveTitle=NULL;
                        if(count($selEveRes)>0){
                          $display_print_pass_amount=$selEveRes[0]['display_amount'];
                          $eveTitle=$selEveRes[0]['Title'];
                        }
                        $data=$delegatePass->getPrintPassPDF($_POST['EventId'],$_POST['EventSignupId'], $display_print_pass_amount);	
                        if($_POST['EventId']==65607){
                          $data=$delegatePass->getCustomPrintPassPDF($_POST['EventId'],$_POST['EventSignupId']);
                      }
					  elseif($_POST['EventId']==$PMNCEventId){
        $data=$delegatePass->pmiCustomPdf($_POST['EventId'],$_POST['EventSignupId']);
    }else if(in_array ($_POST['EventId'],$smart_city_free_events_array)){
   
                              $data=$delegatePass->smart_city_email($_POST['EventId'],$_POST['EventSignupId']);

                         }else if(in_array ($_POST['EventId'],$sunburnEvents)){
                           $data=$delegatePass->getCustomSunburnPDF($_POST['EventId'],$_POST['EventSignupId']);    
                        }
                        $data = utf8_encode($data); 
                        $mpdf->WriteHTML($data);
                        if($ismobile)
                        {
                            if($_POST['download']=="true")
                            {
                                $mpdf->Output('E-ticket.pdf', 'D');
                            }
                            exit();
                        }
                        $content = $mpdf->Output('', 'S');
                        $from_name = 'Meraevents.com';
                        $from_mail = 'info@meraevents.com';
                        $from = 'MeraEvents<info@meraevents.com>';

                        $replyto = 'sunila@meraevents.com';
                        $uid = md5(uniqid(time()));
                        $subject = 'Customer Pass for the event '.$eveTitle;

                        $DisplayName=$resuser[0]['FirstName'];
                        $mailto=$resuser[0]['Email'];
                        $name=NULL;
                        if($_POST['sendPassToMail']===$resuser[0]['Email']) $name=$DisplayName;

                        $message ='<table width="70%" border="0" cellpadding="1" cellspacing="2">
                                                 <tr>
                                                     <td colspan="2">Hello '.$name.',</td>
                                                 </tr>';
                         if(strlen($name)>0){
                                 $message.='         <tr>
                                                         <td colspan="2"> Thank you for registering on '.$eveTitle.'</td>
                                                 </tr>';
                         }
                        $message.='<tr>
                                                <td colspan="2">Please find your Pass as an attachment.</td>
                                        </tr>
                                        <tr>
                                                <td colspan="2">&nbsp;</td>
                                        </tr>
                                        <tr>
                                                <td colspan="2">Regards,</td>
                                        </tr>
                                        <tr>
                                                <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                                <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                                <td colspan="2">MeraEvents.com</td>
                                        </tr>
                                </table>';
                        $filename = 'E-ticket.pdf';
                        $bcc='qison@meraevents.com,srilaskmi@meraevents.com';
//                        print_r($data);exit;
                        if(in_array ($_POST['EventId'],$smart_city_free_events_array)){
                                  
    
                                    $message=$delegatePass->smart_city_email_content($_POST['EventSignupId']);
//                                  echo $message;exit;
                                }
                        if($commonFunctions->sendEmail($_POST['sendPassToMail'],$cc,$bcc,$from,$replyto,$subject,$message,$content,$filename))
                        {
                                echo '<span id="success" style="color:green;margin-left:2%;">Email sent successfully.</span>';
                        }
                    } else {
                        echo '<span id="success" style="color:red;margin-left:24%;clear:both;float:left;">Sorry we have closed the registrations , for further extension of the offer please call 9849011841 or 9866079831</span>';
                    }
                    

                }
                break;
		
	case 'resendOrgPassToEMail':
                $ismobile= $_REQUEST['ismobile']==1?true:false;
                if($_POST['EmailPass'] == 'EmailPass' || $ismobile)
                {
                      include("Extras/mpdf/mpdf.php");
                      $mpdf=new mPDF();
                      $sqluser="select FirstName,Email,Company from user where Id='".$Globali->dbconn->real_escape_string($_SESSION['uid'])."'";
                      $resuser = $Globali->SelectQuery($sqluser);
                      $selEveRes=$Globali->SelectQuery("SELECT display_amount,Title  FROM events WHERE Id = '".$Globali->dbconn->real_escape_string($_POST['EventId'])."'"); 
                      $display_print_pass_amount=$eveTitle=NULL;
                      if(count($selEveRes)>0){
                        $display_print_pass_amount=$selEveRes[0]['display_amount'];
                        $eveTitle=$selEveRes[0]['Title'];
                      }
                      $data=$delegatePass->getOrgPrintPassPDF($_POST['EventId'],$_POST['EventSignupId'], $display_print_pass_amount,$_POST['attId']);	
                      $data = utf8_encode($data); 
                      $mpdf->WriteHTML($data);
					  
                      if($ismobile)
                      {
                          if($_POST['download']=="true")
                          {
                              $mpdf->Output('E-ticket.pdf', 'D');
                          }
                          exit();
                      }
					  else
					  {
                      $content = $mpdf->Output('', 'S');
                      $from_name = 'Meraevents.com';
                      $from_mail = 'info@meraevents.com';
                      $from = 'MeraEvents<info@meraevents.com>';

                      $replyto = 'sunila@meraevents.com';
                      $uid = md5(uniqid(time()));
                      $subject = 'Customer Pass for the event '.$eveTitle;

                      $DisplayName=$resuser[0]['FirstName'];
                      $mailto=$resuser[0]['Email'];
                      $name=NULL;
                      if($_POST['sendPassToMail']===$resuser[0]['Email']) $name=$DisplayName;

                      $message ='<table width="70%" border="0" cellpadding="1" cellspacing="2">
                                               <tr>
                                                   <td colspan="2">Hello '.$name.',</td>
                                               </tr>';
                       if(strlen($name)>0){
                               $message.='         <tr>
                                                       <td colspan="2"> Thank you for registering on '.$eveTitle.'</td>
                                               </tr>';
                       }
                      $message.='<tr>
                                              <td colspan="2">Please find your Pass as an attachment.</td>
                                      </tr>
                                      <tr>
                                              <td colspan="2">&nbsp;</td>
                                      </tr>
                                      <tr>
                                              <td colspan="2">Regards,</td>
                                      </tr>
                                      <tr>
                                              <td colspan="2"></td>
                                      </tr>
                                      <tr>
                                              <td colspan="2"></td>
                                      </tr>
                                      <tr>
                                              <td colspan="2">MeraEvents.com</td>
                                      </tr>
                              </table>';
                      $filename = 'E-ticket.pdf';
                      $bcc='qison@meraevents.com,srilaskmi@meraevents.com';
                      if($commonFunctions->sendEmail($_POST['sendPassToMail'],$cc,$bcc,$from,$replyto,$subject,$message,$content,$filename))
                      {
                              echo '<span id="success" style="color:green;margin-left:2%;">Email sent successfully.</span>';
                      }
					  }

                }
                break;
		
        case 'updateShowTC':
            $result['status']=$Globali->ExecuteQuery("UPDATE eventdetail SET tnctype='".$_POST['updateValue']."' WHERE eventid='".$_POST['updateId']."'");
           echo json_encode($result);
                break;
        case 'isEventIdExists':
                    $eventId=$_POST['event_id'];
                    $res=$Globali->SelectQuery("SELECT id,title,url FROM event WHERE deleted=0 and id=".$eventId);
                    $count_res=count($res);
                    $status['eventExists']=false;
                    $cancelEvent=  isset($_POST['cancelEvent'])?$_POST['cancelEvent']:false;
                    if($count_res>0){
                        $status['eventExists']=true;
                        $status['Title']=$res[0]['title'];
                        $status['URL']=$res[0]['url'];
                        $status['EventId']=$res[0]['id'];
                    }
                    if($count_res>0 && $cancelEvent){
                        $selCancel="SELECT id AS Id FROM settlement WHERE paymenttype='EventCanceled' and eventid=".$eventId." order by id desc";
                        $resCancel=$Globali->SelectQuery($selCancel);
                        if(count($resCancel)>0){
                            $status['eventExists']=false;
                            $status['Message']='This event is already cancelled';
                        }else{
                            $selBookings="SELECT COUNT(es.id) as TransCount FROM eventsignup es INNER JOIN event e ON e.id=es.eventid WHERE e.id=".$eventId." and es.paymenttransactionid!='A1' and es.paymentstatus not in('Canceled')";
                            $resSelBookings=$Globali->SelectQuery($selBookings);
                            $status['TransCount']=$resSelBookings[0]['TransCount'];
                            if(count($resSelBookings)>0 && $resSelBookings[0]['TransCount']==0){
                                $status['eventExists']=false;
                                $status['Message']='No bookings happended for this event so you cannot cancel this event';
                            }
                    }
                    }
                   echo json_encode($status);
                    break;
        case 'validateFieldValue':
                $name=$_POST['value'];
                $data['status']=false;
                $type=$_POST['type'];
                $res=array();
                if(strcmp($type, 'country')==0){
                    $res=$Globali->SelectQuery("SELECT Id FROM Countries where Country='".$name."'");
                }elseif(strcmp ($type, 'state')==0){
                    $country=$_POST['country'];
                    $countryId=0;
                    if(!empty($country))
                        $countryId=$Globali->GetSingleFieldValue("SELECT Id FROM Countries WHERE Country='".$country."'");
                    if($countryId>0)
                        $res=$Globali->SelectQuery("SELECT Id FROM States where State='".$name."' AND CountryId='".$countryId."'");
                }elseif(strcmp($type, 'city')==0){
                    $state=$_POST['state'];
                    $stateId=0;
                    if(!empty($state))
                        $stateId=$Globali->GetSingleFieldValue ("SELECT Id FROM States WHERE State='".$state."'");
                    if($stateId>0)
                        $res=$Globali->SelectQuery("SELECT Id FROM Cities where City='".$name."' AND StateId='".$stateId."'");
                    
                }
                if(count($res)>0){
                    $data['status']=true;
                }
                echo json_encode($data);
                break;
        case 'limitTicketType':
                    $status=$_POST['checked'];
                    $event_id=$_POST['event_id'];
                    $data['status']=false;
                    $uptQuery="UPDATE events set limit_ticket_type=".$status." WHERE Id=".$event_id;
                    $res=$Globali->ExecuteQuery($uptQuery);
                    if($res){
                        $data['status']=true;
                    }
                    echo json_encode($data);
                    break;
        /*case 'discount_after_tax':
                    $status=$_POST['checked'];
                    $event_id=$_POST['event_id'];
                    $data['status']=false;
                    $uptQuery="UPDATE events set discount_after_tax=".$status." WHERE Id=".$event_id;
                    $res=$Globali->ExecuteQuery($uptQuery);
                    if($res){
                        $data['status']=true;
                    }
                    echo json_encode($data);
                    break;*/
        case 'cancelEvent':
                    $data['status']=false;
                    $eventId=$_POST['eventId'];
                    
                    //Make solr call to update the event status
//                    $solrStatus=cancelEventCurl($eventId);
//                    $solrStatusOutput = json_decode($solrStatus,true);
//                    //echo 'output is   <pre>';print_r($solrStatusOutput);echo 'output is<br>';exit;
//                    //Rollbacking the event status to normal
//                    if($solrStatusOutput['response']['eventCanceled'] == 'Failed') {                          
//                        $data['eventCanceled']='Something went wrong, please try again';
//                        $data['status']=false;
//                    }else{
                    $PType='EventCanceled';
                    $status=1;
                    $mode='Event got cancelled';
                    $amtP=$netAmt=0;
                    $pDate=date('Y-m-d');
                    /*INSERT INTO Paymentinfo (`EventId`,`PType`,`status`,`Mode`,`AmountP`,`NetAmount`,`PDate`) values
('68965','EventCanceled',1,'Event got cancelled',0,0,'2015-04-29') */
                    $ins_pi="INSERT INTO settlement(`eventid`,`paymenttype`,`status`,`note`,`amountpaid`,`netamount`,`paymentdate`) VALUES(?,?,?,?,?,?,?)";
                    $prpStmt=$Globali->dbconn->prepare($ins_pi);
                    $prpStmt->bind_param('isisdds',$eventId,$PType,$status,$mode,$amtP,$netAmt,$pDate);
                    $prpStmt->execute();
                    
                    //change the event table status
//                    $UpdtDiscounts = "update `event` set `status`=? ,`modifiedby`=? where id = ? ";
//                    $eventStauts=3;
//                    $modifiedBy=$_SESSION['uid'];
//                    $discountsStmt=$Globali->dbconn->prepare($UpdtDiscounts);
//                    $discountsStmt->bind_param('iii',$eventStauts,$modifiedBy,$eventId);
//                    $upId = $discountsStmt->execute();
//                    $discountsStmt->close();
                                      
                    $insId=$prpStmt->insert_id;
                    if($insId>0){
                    $data['status']=true; // salespersonid
                       $orgMailIdSql="SELECT u.email AS Email, u.name AS FirstName, e.title AS Title,
                                s.email AS salesPersonEmail, e.url AS URL 
                                from `user` as u 
				Inner Join event as e on e.ownerid=u.id
                                JOIN eventdetail ed on ed.eventid = e.id
				Left Join salesperson as s on ed.salespersonid = s.id
				left join eventsalespersonmapping ae on  ae.salesid = s.id
				where e.id=".$eventId;
			$orgDetails=$Globali->SelectQuery($orgMailIdSql,MYSQLI_ASSOC);
                        $title='Event has been Canceled: ';
                        $subject=$title.$orgDetails[0]['Title'];
                        $mailto='delivery@meraevents,support.2@meraevents.com';
                        //$mailto='ms.jagadish1@gmail.com';
                        $cc=$bcc=$replyto=$content=$filename=NULL;
                        if(count($orgDetails)>0){
                            if(strlen($orgDetails[0]['salesPersonEmail'])>0){ 
                                $cc=$orgDetails[0]['salesPersonEmail']; 
                            }
                            $from='MeraEvents<admin@meraevents.com>';
                           $message='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                                            <html xmlns="http://www.w3.org/1999/xhtml">
                                            <head>
                                            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                            <title>MeraEvents.com</title>
                                            </head>
                                            <body>
                                            <table width="650" border="0" cellspacing="0" cellpadding="0" align="center" style="background:#fef7ed; border:5px solid #dbdbdb;">
                                              <tr>
                                                <td><table width="560" border="0" cellspacing="0" cellpadding="0" align="center" style="background:#f5826d; padding:5px 0; margin:40px auto 0; box-shadow:0px 2px 0px #999; ">
                                                    <tr>
                                                      <td><p style="background:#f2e3ac; letter-spacing:0.03em; text-align:center; font-size:20px; line-height:30px; font-weight:bold; font-family:Georgia, \'Times New Roman\', Times, serif; color:#333; padding:10px 0; margin:20px 0;"> '.$title.'
                                                          </p></td>
                                                    </tr>
                                                  </table></td>
                                              </tr>
                                              <tr>
                                                <td><table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
                                                    <tr>
                                                      <td><p style="font-size:18px; letter-spacing:0.03em; font-weight:normal; line-height:26px; font-family:Georgia, \'Times New Roman\', Times, serif; color:#000; padding:10px 0; margin:20px 0 5px 0;"> <span style="width:100%; float:left; margin:0 0 15px 0;">Hi,</span> <span style="width:100%; float:left; margin:0 0 24px 0;">The following event has been cancelled: </span><br />
                                                     Event Title : '.$orgDetails[0]['Title'].'<br />
                                                          Event URL : <a href="'._HTTP_SITE_ROOT.'/event/'.$orgDetails[0]['URL'].'" target="_blank">'._HTTP_SITE_ROOT.'/'.$orgDetails[0]['URL'].'<br/>
                                                      </p>
                                                      </td>
                                                    </tr>
                                                  </table></td>
                                              </tr>

                                              <tr>
                                                <td style="border-top:4px solid #cbc6be; margin: 20px 0 0 0; float: left; width: 100%;">&nbsp;</td>
                                              </tr>
                                              <tr>
                                                <td><table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
                                                    <tr>
                                                      <td align="left" width="70%" style="font-size:14px; font-style:italic; color:#333; line-height:20px;">&copy; 2014, Versant Online Solutions Pvt.Ltd, All Rights Reserved.<br />
                                                        2nd Floor, 3 Cube Towers, Whitefield Road, Kondapur, Hyderabad, Andhra Pradesh - 500084</td>
                                                      <td align="right" width="30%"><a href="http://www.meraevents.com" target="_blank"><img src="http://www.meraevents.com/download/MeaEvents_Logo.png" border="0" width="112" height="71" /></a></td>
                                                    </tr>
                                                  </table></td>
                                              </tr>
                                              <tr><td style="height:20px;">&nbsp;</td></tr>
                                            </table>
                                            </body>
                                            </html>
                                            ';
                            $commonFunctions->sendEmail($mailto,$cc,$bcc,$from,$replyto,$subject,$message,$content,$filename,'ctrl');  
                         }
                      }
//                   }                 

                    echo json_encode($data);exit;
                    break;
        case 'bookOffline': 
					
					$return=2; //default error
                    $eventSignupId=0;
                    include_once("MT/cEventSignupFields.php");
                    include("Extras/mpdf/mpdf.php");
                    $mpdf=new mPDF();
                    //include("../delegatepass.php");
                    //$delegatePass=new delegatePass();
                    include("SMSfunction.php");
                    $totalTicketsPrice=$reffCode=NULL;
                    $eventId=$_POST['eventid'];
                    $selTktId=$_POST['tktid'];
                    $selTktQty=$_POST['tktQty'];
                    $promo_code=trim($_POST['promo_code']);
                    $name=$_POST['name'];
                    $email=$_POST['email'];
                    $mobileno=$_POST['mobileno'];
                    $uid=$_SESSION["uid"]; 
                    try{
                        $filename="TextFiles/".$eventId."complimentaryxyxy.txt";
                        $data="Date:".date('d-M-Y h:i A').",TicketId:".$selTktId.",TicketQty:".$selTktQty.",PromoCode:".$promo_code.",Name:".$name.",Email:".$email.",Mobile No:".$mobileno.",LoggedIn UserId:".$uid."\n\n";
                        if(file_exists($filename)){
                            file_put_contents($filename, $data,FILE_APPEND);
                        }else{
                            $fp=  fopen($filename, "w+");
                            fwrite($fp, $data);
                            fclose($fp);
                        }
                    }catch(Exception $e){
                    }
                    $qSelEventDtls = "SELECT Id,Banner,Title,StartDt,EndDt,Venue,ContactDetails,OEmails,URL, Description,UserID,ticketMsg,Logo,EmailTxt,isWebinar,`StateId`,`CityId`,`PinCode`,`web_hook_url`,`display_amount` FROM events WHERE Id = '".$Globali->dbconn->real_escape_string($eventId)."'";
                    $aEventDtls = $Globali->SelectQuery($qSelEventDtls);
                    $arrTicketsId=array();
                    $SelTickets = "SELECT `Id` FROM tickets WHERE EventId='".$Globali->dbconn->real_escape_string($eventId)."' AND SalesStartOn <= '".$Globali->dbconn->real_escape_string(date('Y-m-d H:i:s'))."' AND SalesEndOn >= '".$Globali->dbconn->real_escape_string(date('Y-m-d H:i:s'))."' AND Status=1 order by DispOrder";
                    $ResTickets = $Globali->SelectQuery($SelTickets);
                    
                    $tktNoDisplay=$Globali->GetSingleFieldValue("select `dispno` from `tickets` where `Id`='".$Globali->dbconn->real_escape_string($selTktId)."'");
                    if($tktNoDisplay==1){
                        $uniqueId=$field1=md5(time().$eventId.$uid);
                    }else{ 
                        $uniqueId=$field1="";
                    }
                    foreach($ResTickets as $tktIds){
                        $tktQty=0;
                        if($selTktId==$tktIds['Id']){ $tktQty=($tktNoDisplay==0)?$selTktQty:1;}
                        $arrTicketsId[$tktIds['Id']]=$tktQty;
                    }
                    if($tktNoDisplay==0){ 
                        $loopCount=1; 
                    }else{
                        $loopCount=$selTktQty; $selTktQty=1; 
                    }
                    $tktTotalAmtData=$Globali->SelectQuery("select Name,Price from tickets where Id='".$selTktId."'");
                    $totalPurchaseAmt=0;
                    
                    /*$selectQuery = "SELECT `discount_after_tax` FROM events WHERE Id=".$eventId;
                    $event_details = $Globali->SelectQuery($selectQuery);*/
                    
                    for($loop=0;$loop<$loopCount;$loop++){
                    if($eventId== 67580)        // || $event_details[0]['discount_after_tax'] == 1
                    {
                        $discountsArr=$commonFunctions->bookingCalculations($eventId,$promo_code,$arrTicketsId,$totalTicketsPrice,$reffCode,$Globali,"offline");
                    }
                    else
                    {
                         $discountsArr=$commonFunctions->applyDiscount($eventId,$promo_code,$arrTicketsId,$totalTicketsPrice,$reffCode,$Globali,"offline");
                    }
                        
                    $discountsApplyiedArr = json_decode($discountsArr,true);
                    $ServiceTax=round($discountsApplyiedArr['totalST'],2);
                    $EntTax=round($discountsApplyiedArr['totalET'],2);
                    $purchseTotal=$discountsApplyiedArr['purchaseTotal'];
                    $totalNormalDiscount=$discountsApplyiedArr['totalNormalDiscount'];
                    $totalBulkDiscount=$discountsApplyiedArr['totalBulkDiscount'];
                        
                    //calculating ExtraCharge
                    $ExtraCharge=0;
                    $purchseTotal=round(($purchseTotal/$selTktQty),2);
                    $totalPurchaseAmt+=$purchseTotal;
                        
                    //inserting into EventSignup
                    $esSignupDt=date('Y-m-d H:i:s');
                    $ucode='OFFLINE_'.$uid;
                    $currencyId=$PaymentModeId=$PaidBit=1;
                    $PaymentTransId="Offline";
                    $PaymentStatus="Successful Transaction";
                    $PaymentGateway='EBS';
                    $sqlES="insert into `EventSignup` (UserId,EventId,SignupDt,Qty,Fees,Ccharge,STax,EntTax,DAmount,CurrencyId,Name,EMail,Phone,PromotionCode,PaymentModeId,PaymentTransId,field1,PaymentStatus,PaymentGateway,ucode) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    $esStmt=$Globali->dbconn->prepare($sqlES);
                    $esStmt->bind_param("ddsidddddissssisssss",$uid,$eventId,$esSignupDt,$selTktQty,$purchseTotal,$ExtraCharge,$ServiceTax,$EntTax,$totalNormalDiscount,$currencyId,$name,$email,$mobileno,$promo_code,$PaymentModeId,$PaymentTransId,$field1,$PaymentStatus,$PaymentGateway,$ucode);
                    $sta1=$esStmt->execute();
                    $eventSignupId=$esStmt->insert_id;
                    $esStmt->close();
						
						
						
						
					/*generating Barcode number*/
					if(in_array($EventId, $sunburnEvents)) {
						$barCodeNo = substr($eventId, 0, 2).$eventSignupId; 
					} else {
						//$uniqueNum = $id.$EventId.$_SESSION['uid'];
						$barCodeNo = substr($eventId,1,4).$eventSignupId;
					}

					$esBarCodeSql = "UPDATE EventSignup SET BarcodeNumber = ? WHERE Id = ?";
					$esBarCodeStmt = $Globali->dbconn->prepare($esBarCodeSql);
					$esBarCodeStmt->bind_param("sd", $barCodeNo,$eventSignupId);
					$esBarCodeStmt->execute();
					$esBarCodeStmt->close();
                	/* Adding Barcode to the Event signup table ends here */
						

                    //eventsignupticketdetails
                    if($eventSignupId > 0){
                            $tktTotalAmt=$tktTotalAmtData[0]['Price']*$selTktQty;
                            $event_ticket_query = "INSERT INTO eventsignupticketdetails (EventSignupId, TicketId, NumOfTickets, TicketAmt, promoCode,Discount,BulkDiscount,ServiceTax, EntTax,currencyId) VALUES (?,?,?,?,?,?,?,?,?,?) ";
                            $estdStmt=$Globali->dbconn->prepare($event_ticket_query);
                            $estdStmt->bind_param("ddidsddddi",$eventSignupId,$selTktId,$selTktQty,$tktTotalAmt,$promo_code,$totalNormalDiscount,$totalBulkDiscount,$ServiceTax,$EntTax,$currencyId);
                            $estdStmt->execute();
                            $estdStmt->close();

                            for ($i = 1; $i <= $selTktQty; $i++){
                                //Attendees
                                $attendee_query = "INSERT INTO Attendees (EventSIgnupId, Name, Email, Phone, PaidBit,  ticketid) VALUES (?,?,?,?,?,?)" ;
                                $attStmt=$Globali->dbconn->prepare($attendee_query);
                                $attStmt->bind_param("dsssid",$eventSignupId,$name,$email,$mobileno,$PaidBit,$selTktId);
                                $attStmt->execute();
                                $attId=$attStmt->insert_id;
                                $attStmt->close();

                                //eventsignupcustomfields
                                $cfquery = "SELECT Id,EventCustomFieldName FROM eventcustomfields WHERE EventId='" . $eventId . "' AND EventCustomFieldSeqNo!='-1'  ORDER BY EventCustomFieldSeqNo ASC";
                                $cfData = $Globali->SelectQuery($cfquery);

                                foreach($cfData as $cf){
                                    $cfVal="";
                                    if($cf['EventCustomFieldName']=="Full Name" || $cf['EventCustomFieldName']=="Name"){$cfVal=$name;}
                                    elseif($cf['EventCustomFieldName']=="Mobile No"){$cfVal=$mobileno;}
                                    elseif($cf['EventCustomFieldName']=="Email Id"){$cfVal=$email;}

                                    $EventSignupFields = new cEventSignupFields(0, $eventId, $eventSignupId, $uid, $cf['Id'], $cfVal,$attId);
                                    $ESUpFieldsId = $EventSignupFields->Save();
                                }
                            }
                            //updating discounts, if set
                            if(strlen($promo_code)>0){
                                $SelDiscounts = "SELECT Id,DiscountLevel FROM discounts WHERE EventId='".$Globali->dbconn->real_escape_string($eventId)."' AND ActiveFrom < '".date('Y-m-d H:i:s')."' AND ActiveTo > '".date('Y-m-d H:i:s')."' AND PromotionCode='".$Globali->dbconn->real_escape_string($promo_code)."' AND UsageLimit > DiscountLevel";
                                $ResDiscounts = $Globali->SelectQuery($SelDiscounts);
                                if(count($ResDiscounts) > 0){
                                    $UpdtDiscounts = "UPDATE discounts SET DiscountLevel = ? WHERE Id = ?";
                                    $DiscountLevel=$ResDiscounts[0]['DiscountLevel']+$selTktQty;
                                    $discountsStmt=$Globali->dbconn->prepare($UpdtDiscounts);
                                    $discountsStmt->bind_param("id",$DiscountLevel,$ResDiscounts[0]['Id']);
                                    $upId = $discountsStmt->execute();
                                    $discountsStmt->close();
                                }
                            }
                            //sending delegate pass
                            //$display_amount=$Globali->GetSingleFieldValue("select display_amount from events where Id='".$eventId."'");
                            $promoterMailId=$Globali->SelectQuery("select firstname,lastname,email from user where Id='".$uid."'");
                            if($tktNoDisplay==0){
                                $subject = 'You have successfully registered for '.stripslashes($aEventDtls[0]['Title']).' - '.$eventSignupId;
                                if($eventId==$Vh1GOAEventID && in_array($selTktId, $Vh1GOATktIds)){
                                                                        $message3='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                                    <html xmlns="http://www.w3.org/1999/xhtml">
                                    <head>
                                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                    <title>MeraEvents.com</title>
                                    </head>

                                    <body>
                                    <table width="650" border="0" cellspacing="0" cellpadding="0" align="center" style="background:#fef7ed; border:5px solid #dbdbdb;">
                                      <tr>
                                        <td>

                                                    <table width="560" border="0" cellspacing="0" cellpadding="0" align="center" style="background:#f5826d; padding:5px 0; margin:40px auto 0; box-shadow:0px 2px 0px #999; ">
                                                  <tr>
                                                    <td>
                                                      <p style="background:#f2e3ac; letter-spacing:0.03em; text-align:center; font-size:22px; font-weight:bold; font-family:Georgia, \'Times New Roman\', Times, serif; color:#333; padding:10px 0; margin:20px 0;"> Vh1 Supersonic Goa 2015</p>
                                                    </td> 
                                                  </tr>
                                                </table>



                                        </td>
                                      </tr>
                                      <tr>
                                        <td><table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
                                                  <tr>
                                                    <td>
                                                      <p style="font-size:16px; letter-spacing:0.03em; font-weight:normal; line-height:24px; font-family:Georgia, \'Times New Roman\', Times, serif; color:#000; padding:10px 0; margin:20px 0 5px 0;">
                                                       <span style="width:100%; float:left; margin:0 0 15px 0;">Hi,</span>
                                                       <span style="width:100%; float:left; margin:0 0 24px 0;">Congratulations on pre-blocking your ticket  for Vh1Supersonic Goa 2015.</span>
                                                                       <span style="width:100%; float:left; margin:0 0 24px 0;">You can pay the remainder of the balance (80 percent) on or before September 15, 2015 using the unique link that will be sent to your email.  Failure to comply and pay the remaining 80 percent on or before September 15, 2015 will not result in a refund. </span>
                                                                            <span style="width:100%; float:left; margin:0 0 20px 0;"><span style="color:#f60; font-weight:bold;">Please note, </span> this is not an  e-ticket. It is just a confirmation of the advance payment that we have received to pre-block your ticket. The e-ticket will be sent to you upon payment of the final amount.  The ticket is neither transferable nor refundable.</span>
                                                       </p>
                                                    </td> 
                                                  </tr>
                                                </table></td>
                                      </tr>
                                      <tr>
                                        <td>

                                            <table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
                                                  <tr>
                                                    <td>
                                                     <p style="font-size:16px; letter-spacing:0.03em; font-weight:normal; line-height:24px; font-family:Georgia, \'Times New Roman\', Times, serif; color:#000; padding:15px 0 10px 0; margin:0px 0 5px 0; border-bottom:1px solid #333; border-top:1px solid #333; float:left; width:100%;">

                                                       <span style="width:100%; float:left; margin:0 0 14px 0; font-weight:bold; color:#f60;">Registration No : Vh1GOARegId</span>
                                                                       <span style="width:100%; float:left; margin:0 0 14px 0; font-weight:bold; color:#f60;">Name : Vh1GOARegName</span>
                                                       <span style="width:50%; float:left; margin:0 0 14px 0; font-weight:bold; color:#f60;">Ticket Type : Vh1GOATktName</span>
                                                                       <span style="width:50%; float:left; margin:0 0 14px 0; font-weight:bold; color:#f60;">Qty : Vh1GOATktQty</span>
                                                       </p>
                                                    </td> 
                                                  </tr>
                                                </table>




                                        </td>
                                      </tr>


                                        <tr>
                                        <td>

                                            <table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
                                                  <tr>
                                                    <td>
                                                     <p style="font-size:18px; letter-spacing:0.03em; font-weight:normal; line-height:24px; font-family:Georgia, \'Times New Roman\', Times, serif; color:#000; padding:10px 0 10px 0; margin:0px 0 5px 0;">

                                                       <span style="width:100%; float:left; margin:0 0 14px 0; font-weight:normal; color:#000;">For any Queries Call us on : +91-9396 555 888 <br />email us @ <a href="mailto:support@meraevents.com" style="color:#f60;">support@meraevents.com</a></span>
                                                       </p>
                                                    </td> 
                                                  </tr>
                                                </table>

                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="border-top:4px solid #cbc6be; margin: 20px 0 0 0; float: left; width: 100%;">&nbsp;</td>
                                      </tr>
                                      <tr>
                                            <td>
                                            <table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
                                                      <tr>
                                                        <td align="left" width="70%" style="font-size:14px; font-style:italic; color:#333; line-height:20px;">&copy; 2015, Versant Online Solutions Pvt.Ltd, All Rights Reserved.<br />
                                                2nd Floor, 3 Cube Towers, Whitefield Road, Kondapur, Hyderabad, Andhra Pradesh - 500084</td>
                                                 <td align="right" width="30%"><a href="http://www.meraevents.com" target="_blank"><img src="http://www.meraevents.com/download/MeaEvents_Logo.png" border="0" width="112" height="71" /></a></td>
                                              </tr>
                                            </table>
                                        </td>
                                      </tr>

                                      <tr>
                                        <td style="height:20px;">&nbsp;</td>
                                      </tr>



                                    </table>

                                    </body>
                                    </html>';
                                    $filename = $data =NULL;
                                    //$attendName=$Globali->GetSingleFieldValue("select Name from Attendees where EventSIgnupId='".$Globali->dbconn->real_escape_string($esid)."'");
                                    $message3 = str_replace("Vh1GOARegId", $eventSignupId, $message3);
                                    $message3 = str_replace("Vh1GOARegName", $name, $message3);
                                    //$message3=  str_replace('DEL_FIRST_NAME', $attendName, $message3);
                                    $Vh1GOATktName = strip_tags($Globali->GetSingleFieldValue("SELECT Name FROM tickets WHERE Id=" . $Globali->dbconn->real_escape_string($selTktId)));
                                    $message3 = str_replace("Vh1GOATktName", $Vh1GOATktName, $message3);
                                    $message3 = str_replace("Vh1GOATktQty", $selTktQty, $message3);
                                }else{
                                    $selEMailMsgs = "SELECT Id, Msg, MsgType, SendThruEMailId FROM EMailMsgs WHERE MsgType ='emInvoice'";
                                    $dtlEMailMsgs = $Globali->SelectQuery($selEMailMsgs);

                                    $EMailMsgId = $dtlEMailMsgs[0]['Id'];
                                    $message3  = str_replace("FirstName", $name, $dtlEMailMsgs[0]['Msg']);
                                    $message3  = str_replace("Title", $aEventDtls[0]['Title'], $message3);
                                    if($EmailTxt !=""){
                                            $message3  = str_replace("EmTxt", $EmailTxt, $message3);
                                    }else{
                                            $message3  = str_replace("EmTxt", " ", $message3);
                                    }
                                    $message3  = str_replace("RandomInvoiceNo", $eventSignupId, $message3);

                                    $message3  = str_replace("Fees", $selTktQty*$purchseTotal, $message3);
                                    $message3  = str_replace("delqty",$selTktQty, $message3);
                                    $message3  = str_replace("PaymentTransId", "Offline", $message3);
									
									if(in_array($eventId,$skirllexEvents)){
										$message3  = str_replace("StartDt", date('Y-m-d',strtotime($aEventDtls[0]['StartDt'])), $message3);
                                    	$message3  = str_replace("EndDt", date('Y-m-d',strtotime($aEventDtls[0]['EndDt']))." Gates Open at 5 PM", $message3);
									}
									else
									{
                                    	$message3  = str_replace("StartDt", $aEventDtls[0]['StartDt'], $message3);
                                    	$message3  = str_replace("EndDt", $aEventDtls[0]['EndDt'], $message3);
									}
									
                                    $message3  = str_replace("EventVenue", nl2br($aEventDtls[0]['Venue']), $message3);
                                    $message3  = str_replace("ContactDetails", nl2br($aEventDtls[0]['Contact']), $message3);
                                    $message3  = str_replace("EventHighLights", nl2br($aEventDtls[0]['HighLights']), $message3);
                                    $message3  = str_replace("EventURL", $aEventDtls[0]['URL'], $message3);

                                    $FBSharelink='http://www.facebook.com/share.php?u='.$aEventDtls[0]['URL'].'&title=Meraevents -'.stripslashes($aEventDtls[0]['Title']);
                                    $message3  = str_replace("FBSharelink", $FBSharelink, $message3);

                                    $TwitterSharelink='http://twitter.com/home?status=Meraevents -'.stripslashes($aEventDtls[0]['Title']).'...+'.$aEventDtls[0]['URL'];
                                    $message3  = str_replace("TwitterSharelink", $TwitterSharelink, $message3);



                                    if(in_array($eventId,$sunburnEvents)){$data=$delegatePass->getCustomSunburnPDF($eventId,$eventSignupId);}
                                    else{$data=$delegatePass->getPrintPassPDF($eventId,$eventSignupId,$aEventDtls[0]['display_amount']);}

                                    $data = utf8_encode($data);

                                    $mpdf->WriteHTML($data);
                                    $content = $mpdf->Output('', 'S');
                                    //$content = chunk_split(base64_encode($content));
                                    //$uid = md5(uniqid(time()));
                                    $filename = 'E-ticket.pdf';
                                }
                                $bcc=NULL;
                                //$cc=$promoterMailId.",support@meraevents.com";
                                $cc=$promoterMailId[0]['email'];
                                //$cc=NULL;
                                $from='MeraEvents<admin@meraevents.com>';
                                //$bcc='srilakshmis@meraevents.com';
                                $sta= $commonFunctions->sendEmail($email,$cc,$bcc,$from,$replyto,$subject,$message3,$content,$filename);
                                $_SESSION['transSucc']=true;

                                $eSentDt=date('Y-m-d H:i:s');
                                $EMailSentStmt=$Globali->dbconn->prepare("INSERT INTO EMailSent (UserId, EMailMsgId,EventSignupId, SentDt) VALUES (?,?,?,?)");
                                $sSentDt = date('Y-m-d h:i:s');
                                $EMailSentStmt->bind_param("dids",$uid,$EMailMsgId,$eventSignupId,$eSentDt);
                                $EMailSentStmt->execute();
                                $EMailSentStmt->close();

                                //sending sms
                                sendRegSMS($mobileno,$aEventDtls[0]['Title'],$eventSignupId);
                            }
							
							if($tktNoDisplay==1 && $loopCount<6){
								//sending sms
                                sendRegSMS($mobileno,$aEventDtls[0]['Title'],$eventSignupId);
							}
		
                        }
                    else{
                        $return=2; //error
                    }
					
					
                    }
	
                    if($eventSignupId > 0){
                        //for complementory tickets, different mail
                        if($tktNoDisplay==1){
                            $subject = 'You have successfully registered for '.stripslashes($aEventDtls[0]['Title']).' - '.$uniqueId;
                            $downloadLink=_HTTP_SITE_ROOT."/download/custompass.php?uniqueId=".$uniqueId."&EventId=".$eventId;

                            $promoterName=$promoterMailId[0]['firstname']." ".$promoterMailId[0]['lastname'];


                            $msgTemplate='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>MeraEvents.com</title>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700" rel="stylesheet" type="text/css">
    </head>

    <body>
    <table width="650" border="0" cellspacing="0" cellpadding="0" align="center" style="background:#fef7ed; border:5px solid #dbdbdb;">
      <tr>
        <td><table width="560" border="0" cellspacing="0" cellpadding="0" align="center" style="background:#f5826d; padding:5px 0; margin:30px auto 0; box-shadow:0px 2px 0px #999; ">
            <tr>
              <td><p style="background:#f2e3ac; text-align:center; font-size:19px; line-height:30px; font-weight:bold; font-family:\'Open Sans\',\'Trebuchet MS\', \'Times New Roman\', Times, serif; color:#333; padding:10px 30px; margin:10px 0;">
                  <strong style="font-size:22px !important; font-weight:600; line-height:30px; text-decoration:none;">'.stripslashes($aEventDtls[0]['Title']).'</strong></p></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td><table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
              <td><p style="font-size:17px; float:left; width:100%; letter-spacing:0.03em; font-weight:normal; line-height:24px; font-family:\'Open Sans\',\'Trebuchet MS\', \'Times New Roman\', Times, serif; color:#000; padding:10px 0 20px 0; margin:10px 0 0px 0;"> <span style="width:100%; float:left; margin:0 0 10px 0;">Hello '.ucwords($name).',</span><br><br> <span style="width:100%; float:left; margin:0 0 10px 0;">Thank You! You have been successfully registered on '.stripslashes($aEventDtls[0]['Title']).' </span></p></td>
            </tr>
          </table></td>
      </tr>

      <tr>
        <td align="left"><table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
              <td align="center"><a href="'.$downloadLink.'" style=" background:#f5826d; width:48%; text-align:center; border-radius:5px; -moz-border-radius:5px; -webkit-border-radius:5px; padding:15px 40px; margin:0 auto; height:auto; overflow:auto; text-align:center; font-size:17px; font-weight:600; font-family:\'Open Sans\',\'Trebuchet MS\',\'Times New Roman\', Times, serif; color:#FFF; text-decoration:none;">VIEW / PRINT TICKETS</a></td>
            </tr>
          </table></td>
      </tr>
     <tr><td>&nbsp;</td></tr>
      <tr>
        <td  style="border-bottom:1px solid #ccc; border-top:1px solid #ccc;"><table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
              <td><p style="font-size:15px; letter-spacing:0.03em; font-weight:normal; line-height:22px; font-family:\'Open Sans\',\'Trebuchet MS\', \'Times New Roman\', Times, serif; color:#000; padding:5px 0; margin:10px 0 10px 0; width:100%; float:left;"> <span style="width:100%; float:left; margin:0 0 5px 0;"> <b style="color:#ff6600;">Event Name : </b> <br>              <a href="#" style="color:#000; text-decoration:none; font-weight:600;">'.stripslashes($aEventDtls[0]['Title']).'</a></span><br><br> <span style="width:100%; float:left; margin:0 0 5px 0;"> <b style="color:#ff6600;">Event Date & Time : </b><br>';
			  if(in_array($eventId,$skirllexEvents)){
				  $msgTemplate.=date("d M Y",strtotime($aEventDtls[0]['StartDt'])).' Gates Open at 5 PM';
			  }
			  else
			  {
				  $msgTemplate.=date("d M Y h:i A",strtotime($aEventDtls[0]['StartDt']));
			  }
			  
                  $msgTemplate.='</span><br><br> <span style="width:100%; float:left; margin:0 0 5px 0;"> <b style="color:#ff6600;">Location:</b> <br>
                  '.nl2br($aEventDtls[0]['Venue']).'</span> </p></td>
            </tr>
          </table></td>
      </tr>

      <tr>
        <td  style="border-bottom:1px solid #ccc; border-top:1px solid #ccc;"><table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
              <td><p style="font-size:14px; letter-spacing:0.03em; font-weight:normal; line-height:22px; font-family:\'Open Sans\',\'Trebuchet MS\', \'Times New Roman\', Times, serif; color:#000; padding:5px 0; margin:10px 0 10px 0; width:100%; float:left;"> <span style="width:100%; float:left; margin:0 0 5px 0;"> <b style="color:#ff6600;">Name : </b>  <span  style="color:#000; text-decoration:none; font-weight:600;">'.ucwords($name).'</span></span><br><br> <span style="width:100%; float:left; margin:0 0 5px 0;"> <b style="color:#ff6600;">Email ID : </b>'.$email.'</span><br><br> <span style="width:100%; float:left; margin:0 0 5px 0;"> <b style="color:#ff6600;">Mobile No : </b>'.$mobileno.'</span> </p></td>
            </tr>
          </table></td>
      </tr>

       <tr>
        <td><table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
              <td align="left" valign="top">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:10px 0 0 0;">
      <tr>
        <td align="center" valign="top" width="50%"><b style="color:#ff6600; font-size:14px; letter-spacing:0.03em; font-weight:bold; line-height:22px; font-family:\'Open Sans\',\'Trebuchet MS\', \'Times New Roman\', Times, serif;">Ticket Qty</b><br />
            <b style="color:#333333; font-size:14px; letter-spacing:0.03em; font-weight:bold; line-height:18px; font-family:\'Open Sans\',\'Trebuchet MS\', \'Times New Roman\', Times, serif;">'.$loopCount.'</b>
        </td>
        <td align="center" valign="top" width="50%"><b style="color:#ff6600; font-size:14px; letter-spacing:0.03em; font-weight:bold; line-height:22px; font-family:\'Open Sans\',\'Trebuchet MS\', \'Times New Roman\', Times, serif;">Ticket Type</b><br />
            <b style="color:#333333; font-size:14px; letter-spacing:0.03em; font-weight:bold; line-height:18px; font-family:\'Open Sans\',\'Trebuchet MS\', \'Times New Roman\', Times, serif;">'.$tktTotalAmtData[0]['Name'].'</b>
        </td>
      </tr>
      <tr>
        <td align="center" valign="top" width="50%"><b style="color:#ff6600; font-size:14px; letter-spacing:0.03em; font-weight:bold; line-height:22px; font-family:\'Open Sans\',\'Trebuchet MS\', \'Times New Roman\', Times, serif;">Ticket Price</b><br />
            <b style="color:#333333; font-size:14px; letter-spacing:0.03em; font-weight:bold; line-height:18px; font-family:\'Open Sans\',\'Trebuchet MS\', \'Times New Roman\', Times, serif;">'.$tktTotalAmtData[0]['Price'].'</b>
        </td>
        <td align="center" valign="top" width="50%"><b style="color:#ff6600; font-size:14px; letter-spacing:0.03em; font-weight:bold; line-height:22px; font-family:\'Open Sans\',\'Trebuchet MS\', \'Times New Roman\', Times, serif;">Purchase Total<span style="font-size:12px; color:#333;">(Incl Tax)</span></b><br />
            <b style="color:#333333; font-size:14px; letter-spacing:0.03em; font-weight:bold; line-height:18px; font-family:\'Open Sans\',\'Trebuchet MS\', \'Times New Roman\', Times, serif;">'.$totalPurchaseAmt.'</b>
        </td>
      </tr>
    </table>


              </td>
            </tr>
          </table></td>
      </tr>

      <tr>
        <td style="border-top:4px solid #cbc6be; margin: 20px 0 0 0; float: left; width: 100%;">&nbsp;</td>
      </tr>
      <tr>
        <td><table width="560" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
              <td align="left" width="70%" style="font-size:14px; font-family:\'Times New Roman\', Times, serif; font-style:italic; color:#333; line-height:20px;">&copy '.date("Y").', Versant Online Solutions Pvt.Ltd, All Rights Reserved.<br />
                2nd Floor, 3 Cube Towers, Whitefield Road, Kondapur, Hyderabad, Andhra Pradesh - 500084</td>
              <td align="right" width="30%"><a href="http://www.meraevents.com" target="_blank"><img src="http://www.meraevents.com/download/MeaEvents_Logo.png" border="0" width="112" height="71" /></a></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td style="height:20px;">&nbsp;</td>
      </tr>
    </table>
    </body>
    </html>
    ';



                            $content=$filename=$bcc=$cc=NULL;
                            //$cc=$promoterMailId.",support@meraevents.com";
                            $from='MeraEvents<admin@meraevents.com>';
                            //$bcc='durgeshmishra2525@gmail.com,sudhera99@gmail.com';
                            $CompTo=$promoterMailId[0]['email'].",".$email;
                            //$CompTo=$email;
                            $commonFunctions->sendEmail($CompTo,$cc,$bcc,$from,$replyto,$subject,$msgTemplate,$content,$filename);


                            //sending sms
                            //if($loopCount==1){	sendRegSMS($mobileno,$aEventDtls[0]['Title'],$eventSignupId); }



                            $_SESSION['transSucc']=true;

                    }
					
						$return=1; //success

                    }
                    echo $return;
	            break;
        case 'bookNowValue':
            $eventId=$_POST['eventId'];
            $bookNowValue=$_POST['value'];
            $updQry="UPDATE events SET book_now_value='".$bookNowValue."' WHERE Id='".$eventId."'";
            $data['status']=$Globali->ExecuteQuery($updQry);
            echo json_encode($data['status']);break;
        case 'addComedyReg':
            $name=$_POST['name'];
            $mobile=$_POST['mobile'];
            $email=$_POST['email'];
            $citySel=$_POST['citySel'];
            $tktQty=$_POST['tktQty'];
            $sqlInsertComedyReg="INSERT INTO comedycentral (name, mobile,email,registerforcity,ticketqty) VALUES (?,?,?,?,?)";
            $ComedyRegStmt=$Globali->dbconn->prepare($sqlInsertComedyReg);
            $ComedyRegStmt->bind_param("ssssi",$name,$mobile,$email,$citySel,$tktQty);
            $data['status']=$ComedyRegStmt->execute();
            $ComedyRegStmt->close();
            echo json_encode($data);
            break;
        case 'saveEventGatewayText':
            $inputData=$_POST['inputData'];
            foreach ($inputData as $key => $value) {
                $updateQry="UPDATE eventpaymentgateway SET gatewaytext='".addslashes($value)."' WHERE Id=".$key;
                $status[]=$Globali->ExecuteQuery($updateQry);
            }
            $data['status']=TRUE;
            $data['total']=count($inputData);
            echo json_encode($data);
            break;
        case 'saveGatewayText':
            $inputData=$_POST['inputData'];
            foreach ($inputData as $key => $value) {
                $updateQry="UPDATE paymentgateway SET gatewaytext='".addslashes($value)."' WHERE Id=".$key;
                $status[]=$Globali->ExecuteQuery($updateQry);
            }
            $data['status']=TRUE;
            $data['total']=count($inputData);
            echo json_encode($data);
        break;
        case 'addMicrositeURL':
            $micrositeURL=$_POST['value'];
            $eventId=$_POST['eventId'];
            $solrData = array();
            //echo 'adminIn';exit;
            $solrData['eventId'] = $eventId;
            $solrData['externalurl'] = $micrositeURL;
            $solrData['keyValue'] = $_SESSION['uid'];
            $solrData['updatetype'] = 'updateMicrositeURL';
            $solrUrl = "/api/event/solrEventStatus";
             $data['status']=FALSE;
            $data['response']['total']=0;
            $solrStatus = $commonFunctions->makeSolrCall($solrData, $solrUrl);
            $solrStatusResponse = json_decode($solrStatus, true);
            if ($solrStatusResponse['response']['updateMicrositeURL'] == 'Success') {
                $updateQry="UPDATE eventdetail SET externalurl='".$micrositeURL."' WHERE eventid=".$eventId;
                $status=$Globali->ExecuteQuery($updateQry);
                $data['response']['messages'][] = "Successfully updated!!!";
                $data['status']=TRUE;
                $data['response']['total']=1;
            } else {
                $data['response']['messages'][] = 'Something went wrong, please try again';
            }
            echo json_encode($data);
        break;
	default:echo 'invalid';break;
}
}

//function to delete Event Logo/Banner images
if(isset($_POST['delEventImage']))
{
	$EventId=$_POST['EventId'];
	$type=$_POST['type'];
	if($type=='logo'){$sqlAdd=" Logo='' ";}elseif($type=='banner'){ $sqlAdd="Banner=''"; } 
	
	$sqldelbanner="Update events  set ".$sqlAdd." where Id=?";
	
	$eventsUpStmt=$Globali->dbconn->prepare($sqldelbanner);
	$eventsUpStmt->bind_param("d",$EventId);
	$eventsUpStmt->execute();
	$eventsUpStmt->close();
	
	echo 'success';
}


// function to check promoter code available or not
if(isset($_POST['checkPromoterCode']))
{
	$pcode=$_POST['pcode'];
	$eventid=$_POST['eventid'];
	
	$sql="select `Id` from `promoters` where `eventId`='".$Globali->dbconn->real_escape_string($eventid)."' and `promoter`='".$Globali->dbconn->real_escape_string($pcode)."'";

	$data=$Globali->SelectQuery($sql);
	if(count($data)>0)
	{
		echo 'error'; // promoter code already exist
	}
	else
	{
		echo 'success'; // promoter code not exist, you can proceed
	}
}


// function to check promoter email exists for this code or not
if(isset($_POST['checkPromoterEmail']))
{
	$pcode=$_POST['pcode'];
	$eventid=$_POST['eventid'];
	$email=$_POST['email'];
	$type=$_POST['type'];
	$sql="select p.`Id` from `promoters` p, `user` u where p.`eventId`='".$Globali->dbconn->real_escape_string($eventid)."' and u.id=p.uid and u.Email='".$Globali->dbconn->real_escape_string($email)."' and type='".$type."'";


	$data=$Globali->SelectQuery($sql);
	if(count($data)>0)
	{
		echo 'error'; // promoter code already exist
	}
	else
	{
		echo 'success'; // promoter code not exist, you can proceed
	}
}



// function to change the status (Active/Inactive) of Promoter link
if(isset($_POST['changePromoterStatus']))
{
	$rid=$_POST['rid'];
	$status=$_POST['status'];
        $type=$_POST['type'];
	
	if($status==1){$newStatus=0; $newTitle="Activate this code"; $anchorText="Inactive"; $checked='';}else{$newStatus=1; $newTitle="Inactivate this code"; $anchorText="Active";$checked=' checked="checked" ';}
	
	$sqlPCode="Update `promoters`  set `status`=? where Id=? and type=?";
	
	$PcodeUpStmt=$Globali->dbconn->prepare($sqlPCode);
	$PcodeUpStmt->bind_param("ids",$newStatus,$rid,$type);
	$PcodeUpStmt->execute();
	$PcodeUpStmt->close();
	
	echo '<input type="checkbox" onclick="changeStatus('.$rid.','.$newStatus.')" title="'.$newTitle.'" '.$checked.' />&nbsp;(<b  style="color:#09C; font-weight:bold;" >'.$anchorText.'</b>)';
}



// function to change the status (Active/Inactive) of a Collaborator
if(isset($_POST['changeCollaboratorStatus']))
{
	$rid=$_POST['rid'];
	$status=$_POST['status'];
	
	if($status==1){$newStatus=0; $newTitle="Activate this Collaborator"; $anchorText="Inactive"; $checked='';}else{$newStatus=1; $newTitle="Inactivate this Collaborator"; $anchorText="Active";$checked=' checked="checked" ';}
	
	$sqlColl="Update `event_collaborators`  set `status`=? where id=?";
	
	$CollUpStmt=$Globali->dbconn->prepare($sqlColl);
	$CollUpStmt->bind_param("id",$newStatus,$rid);
	$CollUpStmt->execute();
	$CollUpStmt->close();
	
	echo '<input type="checkbox" onclick="changeStatus('.$rid.','.$newStatus.')" title="'.$newTitle.'" '.$checked.' />&nbsp;(<b  style="color:#09C; font-weight:bold;" >'.$anchorText.'</b>)';
}

//code to get tinyurl for a normal url
if(isset($_POST['getTinyUrl']))
{
	$url=urldecode($_POST['url']);
	if(strlen(trim($url))>0){echo $commonFunctions->get_tiny_url($url);}else{echo $url;}
}



// checking event ID for custom terms and conditions, whether exist or not set($_REQUEST['eventIDChk'])
if(isset($_POST['eventIDChkforTC']))
{
	$eventid=trim($_POST['eventid']);
	
	$sql="select id as `Id` from `event` where deleted=0 and `Id`='".$Globali->dbconn->real_escape_string($eventid)."'";
	//echo $sql;
	$data=$Globali->GetSingleFieldValue($sql);
	
	if(strlen($data)>0)
	{
		$sql2="select eventid as `Id` from `eventdetail` where `eventid`='".$Globali->dbconn->real_escape_string($eventid)."'";
		$rid=$Globali->GetSingleFieldValue($sql2);
		if(strlen($rid)>0)
		{
			echo "customTermsAndConditions.php?eventid=".$eventid."&edit=".$rid;
		}
		else
		{
			echo "customTermsAndConditions.php?eventid=".$eventid;
		}
	}
	else{
		echo "error";
	}
        
}


// deleting custom terms and conditions entry created by admin
if(isset($_POST['delTCrecord']))
{   
    //echo "SELECT `organizertnc` FROM eventdetail WHERE eventid='".$delid."'";
    $delid=$_POST['delid'];
    $orgDesc=$Globali->GetSingleFieldValue("SELECT `organizertnc` FROM eventdetail WHERE eventid='".$delid."'");
    //echo  "here";
    //print_r($orgDesc);
    if(!empty($orgDesc)){
        $sql="UPDATE `eventdetail` SET `meraeventstnc`='' where `eventid`=?";
        $seoTypesStmt=$Globali->dbconn->prepare($sql);
        $seoTypesStmt->bind_param("d",$delid);
        $seoTypesStmt->execute();
        $seoTypesStmt->close();
        echo 'true';
    }else{
      echo  'false';
    }
        
} 


// function to change the status (Active/Inactive) BULK DISCOUNTS : BULK DISCOUNT EVENT
if(isset($_POST['bulk_status']))
{
	$discount_id=$_POST['discount_id'];
	$status=($_POST['status']== 0)? '1':0;
	
	$sqlPCode="UPDATE `discounts` SET `Status`=? WHERE Id=?";
	
	$PcodeUpStmt=$Globali->dbconn->prepare($sqlPCode);
	$PcodeUpStmt->bind_param("id",$status,$discount_id);
	$PcodeUpStmt->execute();
	$PcodeUpStmt->close();
	
	echo $status;exit();
}



// function to check promoter/discount code exists for this event or not
if(isset($_POST['checkPromotionCode']))
{
	$txtPromotionCode=$_POST['txtPromotionCode'];
	$eventid=$_POST['eventid'];
        $discountid=$_POST['discountid'];
	
	$sql="select `Id` from `discounts` where `EventId`='".$Globali->dbconn->real_escape_string($eventid)."' and `discountmode`='normal' and `PromotionCode`='".$Globali->dbconn->real_escape_string($txtPromotionCode)."' and Id != '".$discountid."'";


	$data=$Globali->SelectQuery($sql);
	if(count($data)>0)
	{
		echo 'error'; // discount code already exist
	}
	else
	{
		echo 'success'; // discount code not exist, you can proceed
	}
}



// function to check currency code exists for this event or not
if(isset($_POST['checkCurrency']))
{
	$currCode=strtoupper(trim($_POST['currCode']));
	
	$sql="select `Id` from `currencies` where `code`='".$Globali->dbconn->real_escape_string($currCode)."'";


	$data=$Globali->SelectQuery($sql);
	if(count($data)>0)
	{
		echo 'error'; // discount code already exist
	}
	else
	{
		echo 'success'; // discount code not exist, you can proceed
	}
}



if(isset($_POST['sendRegistrationSMS']))
{ 
	include_once("SMSfunction.php"); 
	$mobile=$_POST['mobile'];
	$smsMessage=  urldecode($_POST['smsMessage']);
	
	if(strlen($mobile)>0 && strlen($smsMessage)>0)
	{
		functionSendSMS($mobile, $smsMessage, '');
		echo "success";
	}
	else
		echo "fail";
}


if(isset($_GET['downloadDelegatePDF']))
{
	include("Extras/mpdf/mpdf.php");
    $mpdf=new mPDF();
					  
	$selEveRes=$Globali->SelectQuery("SELECT display_amount,Title  FROM events WHERE Id = '".$Globali->dbconn->real_escape_string($_GET['EventId'])."'"); 
    $display_print_pass_amount=$eveTitle=NULL;
    if(count($selEveRes)>0){
        $display_print_pass_amount=$selEveRes[0]['display_amount'];
        $eveTitle=$selEveRes[0]['Title'];
    }
	
	$data=$delegatePass->getOrgPrintPassPDF($_GET['EventId'],$_GET['EventSignupId'], $display_print_pass_amount,$_GET['attId']);	
    $data = utf8_encode($data); 
    $mpdf->WriteHTML($data);
	
	$mpdf->Output('E-ticket.pdf', 'D');
}




if(isset($_POST['getEventTickets']))
{
	$EventId=$_POST['EventId'];
	$uid=$_SESSION['uid'];
	$promoterid=$Globali->GetSingleFieldValue("select Id from `promoters` where eventId='".$Globali->dbconn->real_escape_string($EventId)."' and `uid`='".$Globali->dbconn->real_escape_string($uid)."' and `type`='offline'");
	
	$sqlTkts="select `ticketid` from `offline_promoter_tickets` where `promoterid`='".$Globali->dbconn->real_escape_string($promoterid)."' and `eventid`='".$Globali->dbconn->real_escape_string($EventId)."'";
	$tktsData=$Globali->SelectQuery($sqlTkts);
	
	$ticketidsSql=NULL;
	if(count($tktsData)>0)
	{ 
		$ticketidsSql=" AND t.Id in (";
		foreach($tktsData as $ptkt){ $ticketidsSql.=$ptkt['ticketid'].","; }
		$ticketidsSql=substr($ticketidsSql,0,-1);
		$ticketidsSql.=")"; 
	}
	
	
	
	$returnstr=NULL;
	
	$condition=$ticketidsSql." AND t.SalesStartOn <= '".date('Y-m-d H:i:s')."' AND t.SalesEndOn >= '".date('Y-m-d H:i:s')."' AND t.Status=1 and t.dispno=0";
//    $ResTickets = $commonFunctions->getTicketDetails($EventId,$Globali,$condition);
	
    //promoter realted tickets
    $promoter_tickets_query="select ticketid, t.Id,t.Name from offline_promoter_tickets opt
                             left join tickets as t on t.id=opt.ticketid
                             where promoterid=".$promoterid." AND t.SalesStartOn <= '".date('Y-m-d H:i:s')."' AND t.SalesEndOn >= '".date('Y-m-d H:i:s')."' AND t.Status=1";
           
    $ResTickets=$Globali->SelectQuery($promoter_tickets_query); 
    
	$tktCount=count($ResTickets);
	//print_r($ResTickets);
	
	if($tktCount>0)
	{
		$returnstr.="success^^^^<option value=''>Select Ticket</option>";
		for($t=0;$t<$tktCount;$t++)
		{
			$returnstr.="<option value='".$ResTickets[$t]['Id']."'>".$ResTickets[$t]['Name']."</option>";
		}
	}
	else{ $returnstr.="error^^^^"; }
	
	echo $returnstr;
	
	
	
}


if(isset($_POST['getTicketQty']))
{
	$ticketid=$_POST['ticketid'];
	$eventId=$_POST['eventId'];
	
	
	$returnstr=NULL;
	
	
	$SelTickets="select t.`OrderQtyMax`,t.`OrderQtyMin`,t.`MaxQtyOnSale`,count(res.Id) as soldTickets
	 from tickets t 
				left outer join (
              select a.id as Id,a.ticketId as ticketId FROM Attendees as a
            Inner Join EventSignup as es on a.EventSignupId=es.Id  WHERE   ((((es.paymentgateway='CashonDelivery') or es.paymenttransid!='A1' or
        (es. PaymentModeId=2 and PaymentTransId='A1' and es.echecked='Verified') )
        and es.eChecked NOT IN('Canceled','Refunded'))  or es.fees=0 or es.promotioncode='FreeTicket' ) and es.EventId='".$Globali->dbconn->real_escape_string($eventId)."'  
) as res on t.id=res.ticketId where t.Id=".$Globali->dbconn->real_escape_string($ticketid)." limit 1";
    $ResTickets=$Globali->SelectQuery($SelTickets);
	$tktQtyCount=count($ResTickets);
    
	if($tktQtyCount>0)
	{
		$returnstr.="success^^^^<option value=''>Select Quantity</option>";
		
		$TempTcksql="select t.id,IFNULL(sum(Qty),0) Qty from tickets t left outer join Temptickets tt on t.id=tt.ticketid where  t.Id in (".$ticketid.") group by t.id;";
        $ResTempTck=$Globali->SelectQuery($TempTcksql);
        foreach ($ResTempTck as $value) {
             $tempTicketValues[]=array($value['id']=>$value['Qty']);
        }
		
		
		if (count($ResTempTck) > 0) {
			foreach ($tempTicketValues as $value) {
				if (isset($value[$ticketid])) {
					$tcklevel = $value[$ticketid];
					break;
				} else {
					$tcklevel = 0;
				}
			}
		} else {
			$tcklevel = 0;
		}
		
		
		$remainqty = $ResTickets[0]['MaxQtyOnSale'] - ($ResTickets[0]['soldTickets'] + $tcklevel);
		if ($remainqty > $ResTickets[0]['OrderQtyMax']) {
			$tottick = $ResTickets[0]['OrderQtyMax'];
		} else {
			$tottick = $remainqty;
		}
		
		
		
		for($q=$ResTickets[0]['OrderQtyMin'];$q<=$tottick;$q++)
		{
			$returnstr.="<option value='".$q."'>".$q."</option>";
		}
	}
	else{ $returnstr.="error^^^^"; }
	
	echo $returnstr;
	
	
	
}





// function to check Collaborator email is same as org email  or not
if(isset($_POST['checkCollaboratorEmail']))
{
	$eventid=$_POST['eventid'];
	$cemail=$_POST['cemail'];
	$sql="select e.`id` from `events` e
	Inner join `user` as u on e.userid=u.id and u.email='".$Globali->dbconn->real_escape_string($cemail)."'
	where e.`id`='".$Globali->dbconn->real_escape_string($eventid)."' ";


	$data=$Globali->SelectQuery($sql);
	if(count($data)>0)
	{
		echo 'error'; // Collaborator email is same as org email, error
	}
	else
	{
		echo 'success'; // Collaborator email not exist, you can proceed
	}
}

//function to check TEDX email
if(isset($_POST['chkTedXemail']))
{
	$eventid=$_POST['event_id'];
	$email=$_POST['email'];
	$sql="select `status` from `tedx` where `eventid`='".$eventid."' and `email`='".$email."' ";
	$data=$Globali->SelectQuery($sql);
	if(count($data)>0)
	{
		if($data[0]['status']==1){ echo 'booked'; }
		else{ echo 'available'; }
	}
	else
	{
		echo 'notvalid'; 
	}
	//echo 'booked';
}


if(isset($_POST['chkPromoCode']))
{
	$pcode=trim($_POST['pcode']);
	$arr=array("9REI1501", "9REI1502", "9REI1503", "9REI1504", "9REI1505", "9REI1506", "9REI1507");
	
	if(in_array($pcode,$arr)){ echo "valid";}
	else{ echo "invalid";}
	
}


// function to update event transaction related settings
if(isset($_POST['updateEvtTrAlerts']))
{
	$discount_id=$_POST['discount_id'];
	$status=($_POST['status']== 0)? '1':0;
	
	$sqlPCode="Update `alerts`  set `Status`=? where Id=?";
	
	$PcodeUpStmt=$Globali->dbconn->prepare($sqlPCode);
	$PcodeUpStmt->bind_param("id",$status,$discount_id);
	$PcodeUpStmt->execute();
	$PcodeUpStmt->close();
	
	echo $status;exit();
}

function sendRegSMS($mobileno,$eTitle,$eventSignupId)
{
    global $Globali;
    $uid=$_SESSION['uid'];

    $selSMSMsgs = "SELECT Id, Msg FROM SMSMsgs WHERE MsgType ='smOrgEvtSUpSucc'";
    $dtlSMSMsgs = $Globali->SelectQuery($selSMSMsgs);
    $SMSMsgId = $dtlSMSMsgs[0]['Id'];
    $sms  = str_replace("EventTitle", substr($eTitle,0,100), $dtlSMSMsgs[0]['Msg']);
    $sms = str_replace("RandomInvoiceNo", $eventSignupId, $sms);
    functionSendSMS($mobileno, $sms, 0);

    $sSentDt = date('Y-m-d h:i:s');
    $sqlInsertSMSSent="INSERT INTO SMSSent (UserId, SMSMsgId,EventSignupId, SentDt) VALUES (?,?,?,?)";
    $SMSSentStmt=$Globali->dbconn->prepare($sqlInsertSMSSent);
    $SMSSentStmt->bind_param("dids",$uid,$SMSMsgId,$eventSignupId,$sSentDt);
    $SMSSentStmt->execute();
    $SMSSentStmt->close();
			
}


function cancelEventCurl($eventId) {
    $url = _HTTP_SITE_ROOT . "/api/event/eventCancel";//api call to cancel the event
    $loginUserId=$_SESSION['uid'];
    // Get cURL resource
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => array(
            eventId => $eventId,
            keyValue => $loginUserId),
        CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json')
    ));
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    // Close request to clear up some resources
    curl_close($curl);
    return $resp;
}

            ?>