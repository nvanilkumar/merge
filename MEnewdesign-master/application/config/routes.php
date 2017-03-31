<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home";
$route['login'] = "user/login";
$route['logout'] = "user/logout";
$route['signup'] = "user/signup";
$route['resendActivationLink']='user/resendActivationLink';
$route['activationLink/(:any)']='user/activationLink/$1';
$route['event/preview/(:num)'] = "event/preview/$1";
$route['changePassword/(:any)'] = "user/changePassword/$1";
$route['event/(:any)'] = "event/index/$1";
$route['previewevent'] = "event/index";
$route['PreviewEvent'] = "event/index";
$route['pricingtab.php'] = "event/index";
$route['ticketWidget'] = "event/index";
//$route['404_override'] = "home";
$route['404_override'] = "nopage";
$route['event'] = "home";
//Dashboard related
$route['dashboard'] = "dashboard/myevent/upComingEventList";
$route['dashboard/pastEventList'] = "dashboard/myevent/pastEventList";
$route['dashboard/configure/tnc/(:num)'] = "dashboard/configure/addTermsAndCondition/$1";
$route['dashboard/configure/addcustomfields/(:num)'] = "dashboard/configure/manageCustomFields/$1";
$route['dashboard/configure/addcustomfields/(:num)/(:num)'] = "dashboard/configure/manageCustomFields/$1/$2";
$route['dashboard/configure/editcustomfield/(:num)/(:num)'] = "dashboard/configure/manageCustomFields/$1/0/$2";
$route['dashboard/home/(:num)'] = "dashboard/event/home/$1";
$route['dashboard/home/(:num)/(:any)'] = "dashboard/event/home/$1/$2";
$route['dashboard/home/(:num)/(:any)/(:num)'] = "dashboard/event/home/$1/$2/$3";
//$eventId, $reportType, $transactionType, $page,$promoterCode = '', $currencyCode = ''
//eventid/reporttype/transtype/pagenumber
$route['dashboard/reports/(:num)/(:any)/(:any)/(:num)']="dashboard/reports/transaction/$1/$2/$3/$4";
//eventid/reporttype/transtype/pagenumber/ticketid/pcode/ccode
//$route['dashboard/reports/(:num)/(:any)/(:any)/(:num)/(:any)/(:any)']="dashboard/reports/transaction/$1/$2/$3/$4//$5/$6";
////eventid/reporttype/transtype/pagenumber/mesales
//$route['dashboard/reports/(:num)/(:any)/(:any)/(:num)/meraevents']="dashboard/reports/transaction/$1/$2/$3/$4//meraevents/";
////eventid/reporttype/transtype/pagenumber/ticketid/pcode/ccode
//$route['dashboard/reports/(:num)/(:any)/(:any)/(:num)/(:any)']="dashboard/reports/transaction/$1/$2/$3/$4//$5";
//
////$eventId, $reportType, $transactionType, $page,$ticketId='',$promoterCode = '', $currencyCode = ''
////eventid/reporttype/transtype/pagenumber/ticketid
//$route['dashboard/reports/(:num)/(:any)/(:any)/(:num)/(:num)']="dashboard/reports/transaction/$1/$2/$3/$4/$5";
////eventid/reporttype/transtype/pagenumber/pcode/ccode
//$route['dashboard/reports/(:num)/(:any)/(:any)/(:num)/(:num)/(:any)/(:any)']="dashboard/reports/transaction/$1/$2/$3/$4/$5/$6/$7";
////eventid/reporttype/transtype/pagenumber/mesales
////$route['dashboard/reports/(:num)/(:any)/(:any)/(:num)/meraevents']="dashboard/reports/transaction/$1/$2/$3/$4/meraevents/";
////eventid/reporttype/transtype/pagenumber//ccode
//$route['dashboard/reports/(:num)/(:any)/(:any)/(:num)/(:num)/(:any)']="dashboard/reports/transaction/$1/$2/$3/$4/$5//$6";
$route['dashboard/collaborator/addcollaborator/(:num)'] = "dashboard/collaborator/addCollaborator/$1";
$route['dashboard/collaborator/editcollaborator/(:num)/(:num)'] = "dashboard/collaborator/addCollaborator/$1/$2";
$route['dashboard/collaborator/collaboratorlist/(:num)'] = "dashboard/collaborator/collaboratorList/$1";
$route['dashboard/saleseffort/(:num)']="dashboard/reports/saleseffort/$1";
$route['promoter/currentlist'] = "promoter/index/currentList";
$route['promoter/pastlist'] = "promoter/index/pastList";
$route['promoter/offlinebooking'] = "promoter/index/bookOfflineTicket";
$route['promoter/reports/(:num)/(:any)/(:any)'] = "promoter/index/viewPromoterReports/$1/$2/$3";
$route['promoter/eventDetailsList/(:any)/(:num)/(:any)'] = "promoter/index/eventDetailsList/$1/$2/$3";
$route['dashboard/promote/addOfflinePromoter/(:num)'] = "dashboard/promote/addOfflinePromoter/$1";
$route['dashboard/promote/editOfflinePromoter/(:num)/(:num)'] = "dashboard/promote/addOfflinePromoter/$1/$2";
$route['dashboard/promote/offlinePromoterlist/(:num)'] = "dashboard/promote/offlinePromoter/$1";
$route['profile'] = "profile/index/personalDetail";
$route['profile/company'] = "profile/index/companyDetail";
$route['profile/bank'] = "profile/index/bankDetail";
$route['profile/alert'] = "profile/index/alertSetting";
$route['profile/changePassword'] = "profile/index/changePassword";
$route['profile/developerapi'] = "profile/index/developerapi";
$route['profile/createApp'] = "profile/index/createApp";
$route['profile/updateApp/(:any)'] = "profile/index/updateApp/$1";
$route['currentTicket'] = "profile/index/getCurrentTicket";
$route['api/postebs.php'] = "api/payment/preview";
$route['resource/(:any)'] = "api/oauth_resource/$1";
$route['developers/client_authorize.php'] = "authorize/validateAuthorize";
$route['developers/token.php'] = "token/handleTokenRequest";
$route['pastTicket'] = "profile/index/getPastTicket";
$route['referalBonus'] = "profile/index/getReferalBonus";
$route['noAccess/(:any)'] = "dashboard/event/noAccess/$1";
$route['career'] = "content/index/Career";
$route['faq'] = "content/index/FAQ";
$route['pricing'] = "content/index/Pricing";
//$route['blog'] = "content/index/Blog";
$route['mediakit'] = "content/index/Mediakit";
$route['support'] = "content/index/support";
$route['privacypolicy'] = "content/index/privacypolicy";
$route['news'] = "content/index/News";
$route['eventregistration'] = "content/index/Eventregistration";
$route['selltickets'] = "content/index/Selltickets";
$route['selltickets'] = "content/index/Selltickets";
$route['terms'] = "content/index/Terms";
$route['client_feedback'] = "content/index/Client_feedback";
$route['aboutus'] = "content/index/Aboutus";
$route['team'] = "content/index/Team";
$route['micrositePaymentResponse'] = "microsite/index/micrositePaymentResponse";
// Delegate Pass
$route['delegatepass/(:num)/(:any)'] = "confirmation/delegatepass/$1/$2";
$route['printpass/(:any)'] = "printpass/index/$1";
$route['ByOrganizer/(:any)/(:num)'] = "organization/index/$1/$2";
// Existing SEO URLs

//TrueSemantci URL
$route['tsfeedback/(:num)/(:any)/(:num)'] = "tsfeedback/index/$1/$2/$3";
$route['apidevelopers'] = "content/index/apidevelopers";

//$route['(:any)-events'] = "home/index";
//$route['(:any)/entertainment'] = "home/index";
//$route['(:any)/professional'] = "home/index";
//$route['(:any)/training'] = "home/index";
//$route['(:any)/campus'] = "home/index";
//$route['(:any)/spiritual'] = "home/index";
//$route['(:any)/trade-shows'] = "home/index";
//$route['(:any)/sports'] = "home/index";
//$route['entertainment'] = "home/index";
//$route['professional'] = "home/index";
//$route['training'] = "home/index";
//$route['campus'] = "home/index";
//$route['spiritual'] = "home/index";
//$route['trade-shows'] = "home/index";
//$route['sports'] = "home/index";
//$route['bugbounty'] = "content/index/bugbounty";

/* End of file routes.php */
/* Location: ./application/config/routes.php */


//routes
//$route['batc'] = "microsite/index/batc";
//$route['bhojanamitra'] = "microsite/index/bhojanamitra";
//$route['bluebook'] = "microsite/index/bluebook";
//$route['bombayrockers'] = "microsite/index/bombayrockers";
//$route['cccf'] = "microsite/index/cccf";
//$route['ccl'] = "microsite/index/ccl";
//$route['dandiya'] = "microsite/index/dandiya";
//$route['dandiya/(:any)'] = "microsite/index/dandiya";
//$route['deltingroup'] = "microsite/index/deltingroup";
//$route['edsheeran'] = "microsite/index/edsheeran";
//$route['event-ticketsales-accelerator'] = "microsite/index/event-ticketsales-accelerator";
//$route['event-ticketsales-accelerator/(:any)'] = "microsite/index/event-ticketsales-accelerator/$1";
//$route['flintbeats-arijitsingh-dypatil'] = "microsite/index/flintbeats-arijitsingh-dypatil";
//
//$route['iifautsavam'] = "microsite/index/iifautsavam ";
//$route['kingfisher-octoberfest-hyderabad'] = "microsite/index/kingfisher-octoberfest-hyderabad";
//$route['markusschulz'] = "microsite/index/markusschulz";
//$route['missionkakatiya'] = "microsite/index/missionkakatiya";
//$route['mtvixtreme'] = "microsite/index/mtvixtreme";
//
//$route['newyear'] = "microsite/index/newyear";
//$route['newyear/(:any)'] = "microsite/index/newyear";
//
//$route['holi2015'] = "microsite/index/holi2015";
//$route['holi2015/(:any)'] = "microsite/index/holi2015";
//
//
//
//$route['holi'] = "microsite/index/holi";
//$route['holi/(:any)'] = "microsite/index/holi/$1";
//
//$route['shivkhera'] = "microsite/index/shivkhera";
//
//$route['prophec'] = "microsite/index/prophec";
//$route['sensation'] = "microsite/index/sensation";
//$route['skyfesthyderabad'] = "microsite/index/skyfesthyderabad";
//$route['smirnoffexperience'] = "microsite/index/smirnoffexperience";
//$route['soa50withwandw'] = "microsite/index/soa50withwandw";
//$route['spiro'] = "microsite/index/spiro";
//$route['sunburnblasterjaxx'] = "microsite/index/sunburnblasterjaxx";
//$route['tajballoonfestival'] = "microsite/index/tajballoonfestival";
//$route['titos'] = "microsite/index/titos";
//$route['vh1superslingers'] = "microsite/index/vh1superslingers";
//$route['vh1supersonic'] = "microsite/index/vh1supersonic";
//$route['vh1supersonic2015'] = "microsite/index/vh1supersonic2015";
//$route['vh1supersonicarcade-aboveandbeyond'] = "microsite/index/vh1supersonicarcade-aboveandbeyond";
//$route['vh1supersonic-mnd'] = "microsite/index/vh1supersonic-mnd";
//$route['vh1supersonicskrillexindiatour'] = "microsite/index/vh1supersonicskrillexindiatour";
//$route['why-pay-more-efforts'] = "microsite/index/why-pay-more-efforts";
//$route['uba'] = "microsite/index/uba";
//$route['sbicard'] = "microsite/index/sbicard";
//$route['dashberlin'] = "microsite/index/dashberlin";


$route['web/api/v1/(:any)'] = 'webapiv1/$1';

$route['globalaffiliate/join'] = "content/index/globalaffiliate-join";
$route['globalaffiliate/faq'] = "content/index/globalaffiliate-faq";
$route['globalaffiliate/home'] = "content/index/globalaffiliate-view";
$route['globalaffiliate/why'] = "content/index/globalaffiliate-why";
