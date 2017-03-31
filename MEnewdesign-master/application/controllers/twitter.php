<?php

/* Start session and load library. */
session_start();
require_once(APPPATH . 'libraries/twitteroauth/twitteroauth.php');
require_once(APPPATH . 'handlers/user_handler.php');

class Twitter extends CI_Controller {

    public $connection, $userHandler;

    public function __construct() {
        parent::__construct();
        /* Build TwitterOAuth object with client credentials. */
        $this->connection = new TwitterOAuth($this->config->item('twitter_consumer_key'), $this->config->item('twitter_secret_key'));
        $this->userHandler = new User_handler();
    }

    public function redirect() {
        /* Get temporary credentials. */
        $request_token = $this->connection->getRequestToken($this->config->item('twitter_callback_url'));

        /* Save temporary credentials to session. */
        $this->customsession->setData('oauth_token', $request_token['oauth_token']);
        $token = $request_token['oauth_token'];
        $this->customsession->setData('oauth_token_secret', $request_token['oauth_token_secret']);
        /* If last connection failed don't display authorization link. */
        switch ($this->connection->http_code) {
            case 200:
                /* Build authorize URL and redirect user to Twitter. */
                $url = $this->connection->getAuthorizeURL($token);
                header('Location: ' . $url);
                break;
            default:
                /* Show notification if something went wrong. */
                echo 'Could not connect to Twitter. Refresh the page or try again later.';
        }
    }

    public function callback() {
        $input = $this->input->get();
        $oauth_token = isset($input['oauth_token']) ? $input['oauth_token'] : '';
        $oauth_verifier = $input['oauth_verifier'];

        /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
        $this->connection = new TwitterOAuth($this->config->item('twitter_consumer_key'), $this->config->item('twitter_secret_key'), $oauth_token, $this->customsession->getData('oauth_token_secret'));
        /* Request access tokens from twitter */
        $access_token = $this->connection->getAccessToken($oauth_verifier);

        /* Save the access tokens. Normally these would be saved in a database for future use. */
        // $this->customsession->setData('access_token', $access_token);
        
        /* Remove no longer needed request tokens */
        $this->customsession->unSetData('oauth_token_secret');
        $userData = array();
        /* If HTTP response is 200 continue otherwise send to connect page to retry */
        if (200 == $this->connection->http_code) {
            $userData = $this->save($access_token);
        } else {
            //echo '22';
            //exit();
            /* Save HTTP status for error dialog on connnect page. */
            header('Location:' . site_url('login'));
            exit;
        }
        if ($userData['status']) {
            //print_r($userData);
           // print_r($this->customsession->getData('user_id'));exit;
            header('Location:' . site_url().$userData['response']['userData']['redirectUrl']);
        } else {
            print_r($userData);
            exit;
        }
    }

    public function save($access_token) {
        //$inputArray['email'] = $twcontent->name . '@twitter.com';
        $inputArray['type'] = 'twitter';
        $inputArray['accessToken'] = $access_token;
        $userData = $this->userHandler->login($inputArray);
        //print_r($userData);
        return $userData;
    }

}
