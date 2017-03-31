<?php
/************************************************************************************************ 
 *	Page Details : Cron Job for Sending emails to Sales person, ORGANIZER whenever the first transaction done in previous day.
 *	Created / Last Updation Details : Raviteja 
************************************************************************************************/
include_once("commondbdetails.php");
include_once("../ctrl/MT/cGlobali.php");
include_once '../ctrl/includes/common_functions.php';

$Globali=new cGlobali();
$commonFunctions=new functions();

if($_GET['runNow']==1)
{
 $yesterday_date = date ( 'Y-m-d', strtotime ( '-2 day' ) );
 $yesterday_dateend = date ( 'Y-m-d', strtotime ( '-1 day' ) );
//$yesterday_date =$commonFunctions->convertTime($yesterday_date,DEFAULT_TIMEZONE,TRUE);
//$yesterday_date= date('Y-m-d',strtotime($yesterday_date));
$yesterday_date = $yesterday_date . " 18:30:01";
$yesterday_dateend = $yesterday_dateend . " 18:29:59";
   $eventsSql="SELECT distinct(e.id) as eventid FROM `event` e inner JOIN eventsignup es ON es.eventid = e.id where e.enddatetime>= '$yesterday_date'  and es.signupdate>='$yesterday_date'"; 
 
 $current_events = $Globali->SelectQuery($eventsSql );

	if(count($current_events)>0)
	{
		$events=array();
		foreach($current_events as $eventid)
		{
			 $events [] = $eventid['eventid'];
		}
		
		
		
		$event_ids = implode ( ',', $events );
		  $sqlES="SELECT id as Id,eventid as EventId,signupdate as SignUpDt from eventsignup es where eventid IN ($event_ids) and CASE paymentmodeid when 1 then paymenttransactionid !='A1' when 2 then paymentstatus ='Verified' end group by eventid order by id Asc  ";
		 
		$event_signupdates = $Globali->SelectQuery($sqlES);
		if (count ( $event_signupdates ) > 0) {
			
			foreach ($event_signupdates as  $records) {
				$EventId = $records ['EventId'];
				//$signupdate = date ( 'Y-m-d', strtotime($records ['SignUpDt']));
				 $signupdate = $records ['SignUpDt'];
				
				/**
				 * ********************* Start Of First time User Register for Paid Event ************************************
				 */
				if (($signupdate >= $yesterday_date) && ($signupdate <= $yesterday_dateend)) {
					 $sqlOrg= "select u.email as uemail,u.name as fname,u.id as uid,e.title,e.url from user as u
														Left join event as e on e.ownerid=u.id
														where e.id=" . $EventId . " limit 1 "; 
														
					//echo $sqlOrg."<br>";
					
					$organiser_details = $Globali->SelectQuery($sqlOrg);
					/*foreach($organisers as $organiser_res){
						$organiser_details[] = $organiser_res;
						
					}*/
                                        
					 $organiseremailid = $organiser_details [0] ['uemail']; 
					$organisername = $organiser_details [0] ['fname'];
					$organiser_id = $organiser_details [0] ['uid'];
				    $event_title=$organiser_details[0]['title'];
					$event_url=_HTTP_SITE_ROOT.'/event/'.$organiser_details[0]['url'];
					
					$eventDetails="<b>".$event_title." (<a href=".$event_url.">".$EventId."</a>)</b><br><br>";
					
					$sqlsales = "select s.email as Email from salesperson s left join eventsalespersonmapping ae on  ae.salesid = s.id where ae.eventid=" . $EventId . " limit 1";
					$sales = $Globali->SelectQuery( $sqlsales );
					/*foreach($salesquery as $saleres){
						$sales[] = $saleres;
					}*/
					
					$to = $organiseremailid ;
					//$to = "sunila@meraevents.com" ;
					$CC=NULL;
					if (count ( $sales ) > 0) {
						$cc=$sales [0] ['Email'];
					} else {
						$cc = "sreekanthp@meraevents.com,naidu@meraevents.com,insidesales@meraevents.com";
						}
					
					//echo $Event_saleperson_Email;
					
					$email_subject = 'First Transaction for your Event - '.$event_title;
					$from='MeraEvents<admin@meraevents.com>';
					$user_bankdetails = "select * from organizerbankdetail where userid = ".$organiser_id;
					$bankdetails = $Globali->SelectQuery( $user_bankdetails );
					 
					 $eres = $Globali->SelectQuery( "SELECT template FROM messagetemplate WHERE type='PaidEventFirstSignup'" );
                                       
					 $e_content = $eres[0]['template'];
					
					
								if (count ( $bankdetails ) == 0)  {
						$refurl = _HTTP_SITE_ROOT."login?redirect_url=profile/bank";
						$email_content = '			<tr>
													<td height="20" colspan="2">Your Bank Details has not been updated yet.Booking has been done for '.$eventDetails.' .Click below link to update them</td>
												</tr>
												<tr>
													<td height="20" ><a target="_blank" href="'.$refurl.'" title="update the Bank details" ><b>Update Bank Details</b></a></td>
												</tr>';
                                            $bank_email_content = str_replace ( 'Bank_Template', $email_content, $e_content );
                                            $bank_email_content = str_replace ( 'organiser_name', $organisername, $bank_email_content );
                                            $commonFunctions->sendEmail ( $to  , $cc, '', $from, '', $email_subject, $bank_email_content, '', '' );
                                                
                                        }
				}
			}
		}
		/**
		 * ********************* End Of First time User Register for Paid Event ************************************
		 */
	}
}



?>
