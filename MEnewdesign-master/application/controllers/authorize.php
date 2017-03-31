<?php

/**
 * To accept authorization from user
 *
 * Used to accept authorization from user
 *
 * @author     Original Author <Jagadish M S>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS:1.0
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once(APPPATH . 'handlers/common_handler.php');
require_once(APPPATH . 'handlers/file_handler.php');

//require(APPPATH.'libraries/REST_Controller.php');
class Authorize extends CI_Controller {

    public $server, $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = & get_instance();
        $this->load->model('Server_model');
        $this->load->model('Oauth_clients_model');
        $this->server = $this->Server_model->getServer();
        $this->commonHandler = new Common_handler();
    }

    public function validateAuthorize() {
        $input = $this->input->get();
       $headerValues = $this->commonHandler->headerValues();
       $data['countryList'] = array();
       $data = $headerValues;
       $data['categoryList'] = array();
       $footerValues = $this->commonHandler->footerValues();
       $data['categoryList'] = $footerValues['categoryList'];
       $data['cityList'] = $footerValues['cityList'];
        $_GET['state'] = 1;
        if (!$this->ci->customsession->getData('userId')) {
            $get = $this->input->get();
            $redirectURL = 'authorize/validateAuthorize?';
            foreach ($get as $key => $value) {
                $redirectURL.='&' . $key . '=' . $value;
            }
            $loginURL = ('login?redirect_uri=' . ($redirectURL));
            redirect($loginURL);
            exit;
        }
        $request = OAuth2\Request::createFromGlobals();
        $response = new OAuth2\Response();

        // validate the authorize request
        if (!$this->server->validateAuthorizeRequest($request, $response)) {
            if ($response->getStatusCode() != 200) {
                $error['status'] = $response->getStatusCode();
                $error['error'] = $response->getParameter('error');
                $error['error_description'] = $response->getParameter('error_description');
                print_r(json_encode($error));
                die;
            } else {
                print_r($response);
                die;
            }
        }

        if (empty($this->input->post())) {
            $data = array();
            // echo 'in';
            $inputArray = $this->input->get();
            $select['id'] = $this->Oauth_clients_model->id;
            $select['client_id'] = $this->Oauth_clients_model->client_id;
            $select['client_secret'] = $this->Oauth_clients_model->client_secret;
            $select['user_id'] = $this->Oauth_clients_model->user_id;
            $select['app_name'] = $this->Oauth_clients_model->app_name;
            $select['app_image'] = $this->Oauth_clients_model->app_image;
            $select['access_level'] = $this->Oauth_clients_model->access_level;
            $this->Oauth_clients_model->setSelect($select);
            if (isset($inputArray['client_id'])) {
                $where[$this->Oauth_clients_model->client_id] = $inputArray['client_id'];
            }

            $this->Oauth_clients_model->setWhere($where);
            $data['oauth_details'] = $this->Oauth_clients_model->get();
            $fileHandler=new File_handler();
            $fileArray=array();
            $fileArray['fileids'][]=$data['oauth_details'][0]['app_image'];
            $data['filedata']=$fileHandler->getData($fileArray);
//            print_r( $data['oauth_details']);
//            print_r($data['filedata'] );exit;
            $userData['userId']=$data['oauth_details'][0]['user_id'];
            $userHandler=new User_handler();
            $data['userdata']=$userHandler->getEventsignupUserdata($userData);
//            print_r($data['userdata']);exit;
            $data['redirect_url'] = $inputArray['redirect_url'];
            $data['coludPath']=$this->config->item('images_content_path');
            $data['content'] = 'client_authorize_view';
            $data['pageName'] = 'Developers';
            $data['pageTitle'] = 'Developers';
//            $data['cssArray'] = array($this->config->item('css_public_path'). 'print_tickets'  );
            $data['jsArray'] = array( $this->config->item('js_public_path') . 'static',
                                      $this->config->item('js_public_path') . 'common');
            $this->load->view('templates/user_template', $data);

            
            
        } else {
            $inputArray = $this->input->post();
            //print_r($inputArray);exit;
            $redirectTo = $inputArray['redirect_url'];
            $is_authorized = ($this->input->post('authorized') === 'yes');
            if ($is_authorized) {
                $user_id = $this->ci->customsession->getData('userId');
                $this->server->handleAuthorizeRequest($request, $response, $is_authorized, $user_id);
                // this is only here so that you get to see your code in the cURL request. Otherwise, we'd redirect back to the client
                $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=') + 5, 40);
                $url = $redirectTo . '?code=' . $code;
                header("Location: " . $url);
                exit;
            }
            //echo $redirectTo;
            //exit;
            header("Location: " . $redirectTo);
        }
    }

//    public function processData() {
//        $inputArray = $this->input->post();
//        //print_r($inputArray);exit;
//        $redirectTo = $inputArray['redirect_url'];
//        $request = OAuth2\Request::createFromGlobals();
//        $response = new OAuth2\Response();
//        // print the authorization code if the user has authorized your client
//        $is_authorized = ($this->input->post('authorized') === 'yes');
//        $this->server->handleAuthorizeRequest($request, $response, $is_authorized);
//        if (!$this->server->validateAuthorizeRequest($request, $response)) {
//            if ($response->getStatusCode() != 200) {
//                $error['status'] = $response->getStatusCode();
//                $error['error'] = $response->getParameter('error');
//                $error['error_description'] = $response->getParameter('error_description');
//                print_r(json_encode($error));
//                die;
//            } else {
//                print_r($response);
//                die;
//            }
//        }
//        if ($is_authorized) {
//            // this is only here so that you get to see your code in the cURL request. Otherwise, we'd redirect back to the client
//            $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=') + 5, 40);
//            echo $redirectTo . '?code=' . $code;
//            exit;
//            header("Location: " . $redirectTo . '?code=' . $code);
//        }
//        echo $redirectTo;
//        exit;
//        header("Location: " . $redirectTo);
////        $error['status'] = $response->getStatusCode();
////        $error['error'] = $response->getParameter('error');
////        $error['error_description'] = $response->getParameter('error_description');
////        print_r(json_encode($error));
////        die;
//    }
}
