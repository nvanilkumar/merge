<?php


namespace AdminBundle\Services;

use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Customer;

class CommonHandler {

    protected $router;
    protected $session;
    protected $em;

    public function __construct($em, $session, $router, $security) {

        $this->router = $router;
        $this->session = $session;
        $this->em = $em;
        $this->security = $security;
    }

    function test() {
        return 'hello';
    }
    
    function prepareCampaignstring($name)
    {
       return str_replace(' ', '_', $name);
    }
    
    function paginate($base_url, $query_str, $total_pages, $current_page, $paginate_limit) {

        $page_array = array();

        $dotshow = true;

        for ($i = 1; $i <= $total_pages; $i ++) {
            if ($i == 1 || $i == $total_pages || ($i >= $current_page - $paginate_limit && $i <= $current_page + $paginate_limit)) {
                $dotshow = true;
                if ($i != $current_page) {
                    $query_str['page'] = $i;
                    $page_array[$i]['url'] = $this->router->generate($base_url, $query_str); //$base_url . "?" . $query_str ."=" . $i;
                }
                $page_array[$i]['text'] = strval($i);
            } else if ($dotshow == true) {
                $dotshow = false;
                $page_array[$i]['text'] = "...";
            }
        }
        return $page_array;
    }

    function importCustomers($file, $ommitfirst = false, $expectedarg = 5) {

      //  $file = WEB_DIRECTORY . "\uploads\customers\\" . $file;
        $arrResult = array();
        $first = 0;
        if (file_exists($file)) {
            $handle = fopen($file, "r");
            if (empty($handle) === false) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($ommitfirst) {
                        if ($first == 0) {
                            $first++;
                        } else {
                            if (isset($data[$expectedarg - 1])) {
                                $arrResult[] = $data;
                            }
                        }
                    } else {
                        if (isset($data[$expectedarg - 1])) {
                            $arrResult[] = $data;
                        }
                    }
                }
                fclose($handle);
            }
        }
        return $arrResult;
    }

    function apiParameterValidations($customerExtn,$campaignId,$type,$dailStatus,$status){
        $response = new Response();
        $message='';
        
        if($type == 'campaigns_data' || $type == 'live_agents'){
            if(!$customerExtn){
                $message .=" customerExtn parameter is missing<br>";
            }
            if(!$campaignId){
              $message .=" campaignId parameter is missing<br>";
            }
        }
       
       if($type == 'campaigns_data' && !$dailStatus){
            $message.=" dailStatus parameter is missing<br>";
        }
        if($type == 'users' && !$status){
            $message.=" Status parameter is missing<br>";
        }


        $responseArray=array(
                            'status' =>'error',
                            'message' => $message
        );
        $response->setContent(json_encode($responseArray));
       // $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        $response->headers->set('Content-Type', 'application/json');  
        return $response;
    }
    
    function apiValueNotFound($msg){
       $response = new Response();
       $responseArray=array(
                            'status' =>'error',
                            'message' => $msg
                            );
        $response->setContent(json_encode($responseArray));
        //$response->setStatusCode(Response::HTTP_NOT_FOUND);
        $response->headers->set('Content-Type', 'application/json');  
        return $response;
    }
    function customerIdbyCustomerExtn($doctrine,$customerExtn){  
        $customerId = 0;
        $repository = $doctrine->getRepository('AppBundle:Customer');
        $customer= $repository->findOneBy( array('phoneNumber' => $customerExtn));
        if($customer){
             $customerId=$customer->getCustomerId();
        }
        
       
       return $customerId;
    }
    function userIdbyAgentExtn($doctrine,$agentExtn){
        $userId = 0;
        $repository = $doctrine->getRepository('AppBundle:User');
        $user= $repository->findOneBy( array('extension' => $agentExtn));
        if($user){
             $userId=$user->getUserId();
        }
        
       
       return $userId;
    }
    function userbyAgentExtn($doctrine,$agentExtn){
        $userId = 0;
        $repository = $doctrine->getRepository('AppBundle:User');
        $user= $repository->findOneBy( array('extension' => $agentExtn));
        
        
       
       return $user;
    }
}
