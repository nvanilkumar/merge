<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once(APPPATH . 'handlers/common_handler.php');

class Content extends CI_Controller {

    var $commonHandler;
     var $userHandler;
     var $data;
    public function __construct() {
        parent::__construct();

        $this->commonHandler = new Common_handler();
         
        
    }
    public function index($inputArray) {
        $input = $this->input->get();
        $headerValues = $this->commonHandler->headerValues($input);
        $data['countryList'] = array();
        $data = $headerValues;
        $data['categoryList'] = array();
        $footerValues = $this->commonHandler->footerValues();
        $data['categoryList'] = $footerValues['categoryList'];
        $data['cityList'] = $footerValues['cityList'];
        //$data['moduleName'] = $inputArray;
         switch ($inputArray) {
             case "Career":
                    $data['content'] = 'includes/static/careers_view';
                    $data['pageName'] = "Career";
                    $data['pageTitle'] = "Career";
                    break;
//             case "Blog":
//                   commonHelperGetPageUrl('blog');
//                    break;
             case "Mediakit":
                    $data['content'] = 'includes/static/mediakit_view';
                    $data['pageName'] = "Mediakit";
                    $data['pageTitle'] = "Mediakit";
                    break;
             case "FAQ":
                    $data['content'] = 'includes/static/faq_view';
                    $data['pageName'] = "FAQ";
                    $data['pageTitle'] = "FAQ";
                    break; 
             case "Pricing":
                    $data['content'] = 'includes/static/faq_view';
                    $data['pricing'] = 1;
                    $data['pageName'] = "Pricing";
                    $data['pageTitle'] = "Pricing";
                    break;    
            case "globalaffiliate-faq":
                    $data['content'] = 'includes/static/affiliate_program_faq';
                    $data['globalaffiliate'] = 1;
                    $data['hideGlobalAffiliateHeader'] = 1;
                    $data['pageName'] = "globalaffiliate";
                    $data['pageTitle'] = "globalaffiliate";
                    break; 
            case "globalaffiliate-why":
                    $data['content'] = 'includes/static/affiliate_program_why';
                    $data['globalaffiliate'] = 1;
                    $data['hideGlobalAffiliateHeader'] = 1;
                    $data['pageName'] = "globalaffiliate";
                    $data['pageTitle'] = "globalaffiliate";
                    break; 
            case "globalaffiliate-join":
                    $data['content'] = 'includes/static/affiliate_program_create';
                    $data['globalaffiliate'] = 1;
                    $data['pageName'] = "globalaffiliate";
                    $data['pageTitle'] = "globalaffiliate";
                    $data['hideGlobalAffiliateHeader'] = 1;
                    $data['jsArray'] = array($this->config->item('js_public_path') . 'global-affiliate');
                    require_once(APPPATH . 'handlers/promoter_handler.php');
                    $promoterHandler=new Promoter_handler();
                    $data['isGlobalPromoter']=false;
                    if(getUserId()){
                        $inputCode['userid']=  getUserId();
                        $codeResponse=$promoterHandler->getGlobalCode($inputCode);
                    }
                    if(isset($codeResponse) && $codeResponse['status'] && $codeResponse['response']['total']>0){
                        $data['code']=$codeResponse['response']['promoterList'][0]['code'];
                        $data['isGlobalPromoter']=true;
                    }else{
                        $data['code']=$promoterHandler->generateCodeForGlobalAff();
                    }
                    break;
            case "globalaffiliate-view":
                    $data['content'] = 'includes/static/affiliate_program_view';
                    $data['globalaffiliate'] = 1;
                    $data['hideGlobalAffiliateHeader'] = 1;
                    $data['pageName'] = "globalaffiliate";
                    $data['pageTitle'] = "globalaffiliate";
                    break;
             case "News":
                    $data['content'] = 'includes/static/news_and_press_view';
                    $data['pageName'] = "News";
                    $data['pageTitle'] = "News";                 
                    break;
             case "Client_feedback":
                    $data['content'] = 'includes/static/client_feedback_view';
                    $data['pageName'] = "Client Feedback";
                    $data['pageTitle'] = "Client Feedback";                 
                    break;
             case "Aboutus":
                    $data['content'] = 'includes/static/aboutus_view';
                    $data['pageName'] = "About Us";
                    $data['pageTitle'] = "About Us";                 
                    break;
             case "Team":
                    $data['content'] = 'includes/static/team_view';
                    $data['pageName'] = "Team";
                    $data['pageTitle'] = "Team";                 
                    break;
             case "apidevelopers":
                     $data['content'] = 'includes/static/developers_view';
                     $inputArray="Developers";
                     break;     
             case "Eventregistration":
                    $data['content'] = 'includes/static/eventregistration_view';
                    $data['pageName'] = "Free Event Registration";
                    $data['pageTitle'] = "Free Event Registration";                 
                    break;
             case "Selltickets":
                    $data['content'] = 'includes/static/selltickets_view';
                    $data['pageName'] = "Sell Tickets Online";
                    $data['pageTitle'] = "Sell Tickets Online";                 
                    break;
             case "Terms":
                    $data['content'] = 'includes/static/terms_view';
                    $data['pageName'] = "Terms and Conditions";
                    $data['pageTitle'] = "Terms and Conditions";                 
                    break;   
            case "privacypolicy":
                    $data['content'] = 'includes/static/privacypolicy_view';
                    $data['pageName'] = "Privacy Policy";
                    $data['pageTitle'] = "Privacy Policy";                
                    break;   
            case "support":
                    $data['content'] = 'includes/static/support_view';
                    $data['pageName'] = "Support";
                    $data['pageTitle'] = "Support";                
                    break;   
            case "bugbounty":
                    $data['content'] = 'includes/static/bugbounty_view';
                    $data['pageName'] = "Bug Bounty";
                    $data['pageTitle'] =  "Bug Bounty";;                
                    break; 
             default:
                $data['content'] = '';
                break;
         }
        $data['cssArray'] = array($this->config->item('css_public_path'). 'print_tickets'  );
        $jsData = array(//$this->config->item('js_angular_path') . 'common/commonModule' , 
                             //    $this->config->item('js_angular_path') . 'common/services/cookieService' , 
                               //  $this->config->item('js_angular_path') . 'common/controllers/countryController' ,
                                 $this->config->item('js_public_path') . 'jquery.validate' ,
                                 $this->config->item('js_public_path') . 'static',
                                 $this->config->item('js_public_path') . 'common');
       if(isset($data['jsArray']) && count($data['jsArray'])>0){
            $data['jsArray']=  array_merge( $data['jsArray'],$jsData);
       }else{
           $data['jsArray']= $jsData;
       }
        $this->load->view('templates/user_template', $data);

    }
}
?>
