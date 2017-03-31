<?php

/**
 * OAuth server autoload files
 *
 * Acts as OAuth server,establishes database connection,autoloads all requried library files 
 *
 * @author     Original Author <Jagadish M S>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS:1.0
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require(APPPATH . 'libraries/oauth2libraries/src/OAuth2/Autoloader.php');

class Server_model extends CI_Model {

    //server object is intiallized
    public $server;

    // Autoloading (composer is preferred, but for this example let's just do this)
    public function __construct() {
        parent::__construct();
        OAuth2\Autoloader::register();
        // $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
        $storage = new OAuth2\Storage\Pdo(array('dsn' => 'mysql:dbname=' . $this->db->database . ';host=' . $this->db->hostname . ':' . $this->db->port, 'username' => $this->db->username, 'password' => $this->db->password));
        // Pass a storage object or array of storage objects to the OAuth2 server class
        $this->server = new OAuth2\Server($storage);
        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        $this->server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
        // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        $this->server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
        //print_r($this->server);
    }

    //returns the server object
    public function getServer() {
        return $this->server;
    }

}
