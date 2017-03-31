<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once(APPPATH . 'handlers/common_handler.php');

class Tsfeedback extends CI_Controller {

    var $commonHandler;
     var $userHandler;
     var $data;
    public function __construct() {
        parent::__construct();

        $this->commonHandler = new Common_handler();
         
        
    }
    public function index($eventSignupId, $type, $nps) {
        $input = $this->input->get();
        $headerValues = $this->commonHandler->headerValues($input);
        $data['countryList'] = array();
        $data = $headerValues;
        $data['categoryList'] = array();
        $footerValues = $this->commonHandler->footerValues();
        $data['categoryList'] = $footerValues['categoryList'];
        $data['cityList'] = $footerValues['cityList'];
               
        $data['content'] = 'truesemantic_view';
        if($type=="bsuccesspage"){
            $data['iframeSrc']='http://www.truesemantic.com/s/na@moJSgqMvKkac?ts_invite_key='.$eventSignupId.'&fcategory=Booking Success&sdtype=1&isq=1&cpid=83&cqid=319&315_27='.$nps;
        }
	else{
            $data['iframeSrc']='http://www.truesemantic.com/s/nbCmoJmgqMvKkac?ts_invite_key='.$eventSignupId.'&sdtype=1&isq=1&cpid=50&cqid=320&320_27='.$nps; 
        }
        
        $data['pageName'] = "MeraEvents - feedback";
        $data['pageTitle'] = "MeraEvents - feedback";
        $data['cssArray'] = array($this->config->item('css_public_path'). 'print_tickets'  );
        $data['jsArray'] = array($this->config->item('js_angular_path') . 'common/commonModule' , 
                             //    $this->config->item('js_angular_path') . 'common/services/cookieService' , 
                               //  $this->config->item('js_angular_path') . 'common/controllers/countryController' ,
                                 $this->config->item('js_public_path') . 'jquery.validate' ,
                                 $this->config->item('js_public_path') . 'static',
                                 $this->config->item('js_public_path') . 'common');

        $this->load->view('templates/user_template', $data);

    }
}
?>
