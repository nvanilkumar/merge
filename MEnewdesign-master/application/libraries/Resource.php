<?php
/**
 * Library to checks is valid user is accessing the REST API
 *
 * Used to check whether valid API request is made with access_token
 *
 * @author     Original Author <Jagadish M S>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS:1.0
 */
class Resource{
    public $server,$CI;
    public function __construct(){
        $this->CI=&get_instance();
        $this->CI->load->model('Server_model');
        $this->server=$this->CI->Server_model->getServer();
    }
    //validate access token and return's response
    public function verifyAccessToken() {
        $status=true;
        // Handle a request to a resource and authenticate the access token
        if (!$this->server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $status=false;
        }
        return $status;
    }
    
    //To retrive the access token related information
    public function getAccessTokenDetails() {
        //Access token related user details information
       $access_token_details = $this->server->getAccessTokenData(OAuth2\Request::createFromGlobals());
       return $access_token_details;
    }

}
