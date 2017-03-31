<?php

require_once 'common_model.php';

class Oauth_clients_model extends Common_model {

    function __construct() {
        parent::__construct();
        $this->setTableName("oauth_clients");

        //Giving alias names to table field names
        $this->_setFieldNames();
    }

    private function _setFieldNames() {
        $this->id = "id";
        $this->client_id = "client_id";
        $this->client_secret = "client_secret";
        $this->redirect_uri = "redirect_uri";
        $this->user_id = "user_id";
        $this->app_name = "app_name";
        $this->app_image = "app_image";
        $this->access_level = "access_level";
        $this->created_date = "created_date";
    }

}

?>