<?php
/**
 * Generates access token
 *
 * used to generate access token from valid authorization code
 *
 * @author     Original Author <Jagadish M S>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS:1.0
 */
require(APPPATH.'libraries/REST_Controller.php');
class Token extends REST_Controller{
    public $server;
    public function __construct() {
        parent::__construct();
        $this->load->model('Server_model');
        $this->server=$this->Server_model->getServer();
    }
    //returns access token with expiration time.This request should be made only in post
    public function handleTokenRequest_post() {        
        // Handle a request for an OAuth2.0 Access Token and send the response to the client
        $this->server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
    }
}