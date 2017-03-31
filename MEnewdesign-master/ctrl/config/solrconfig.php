<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Solr collection configuration

//$config['eventCollection']="collection1";
//$config['tagCollection']="collection2"; 

$config['eventCollection']="dev_events";
$config['tagCollection']="dev_tags"; 

// solor quiry url configuration
 switch (getenv('HTTP_HOST')) {
            case 'localhost':
                $config['solrUrl'] = 'http://ec2-54-157-135-27.compute-1.amazonaws.com:8080/solr/';

                break;
            case 'menew.com':
                $config['solrUrl'] = 'http://ec2-54-157-135-27.compute-1.amazonaws.com:8080/solr/';

                break;
            case 'ec2-54-157-135-27.compute-1.amazonaws.com':
                $config['solrUrl'] = 'http://ec2-54-157-135-27.compute-1.amazonaws.com:8080/solr/';

                break;
            
            default:
                $config['solrUrl'] = 'http://localhost:8983/solr/';
        }
        
         