<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Solrlibrary {

    private $solrUrl;
    private $eventCollection;
    private $tagCollection;
    private $ci;

    public function __construct() {
        // getting solr url    
        $this->ci = & get_instance();
        $this->ci->config->load('solrconfig');
        $this->solrUrl = $this->ci->config->item('solrUrl');
        $this->eventCollection = $this->ci->config->item('eventCollection');
        $this->tagCollection = $this->ci->config->item('tagCollection');
    }

// getSolrResults() getting events from solr
    public function getSolrResults($inputs, $sort, $facet = '', $start = 0, $limit = 10, $collection,$orConditionInputs='',$inArray=array(),$onlyOrCondition='') {
        if($collection=='event'){
          $collection = $this->eventCollection;
        }
        if($collection=='tag'){
          $collection = $this->tagCollection;
        }
        $url = $inputQuery = $sortQuery = $facetQuery = '';
        $result = array();
        // Input perameters
        if (isset($inputs) && !empty($inputs)) {
            $inputQuery = $this->getInputFileds($inputs);
        }
        
        if (isset($orConditionInputs) && !empty($orConditionInputs)) {
            if(empty($inputQuery)){
                $inputQuery = '?q='.$this->getOrInputFileds($orConditionInputs);
            } else {
                $inputQuery .= urlencode(' AND ');
                $inputQuery .= $this->getOrInputFileds($orConditionInputs);
            }
        }
        if (isset($onlyOrCondition) && !empty($onlyOrCondition)) {
            if(empty($inputQuery)){
                $inputQuery = '?q='.$this->getOnlyOrInputFileds($onlyOrCondition);
            } else {
                $inputQuery .= urlencode(' AND ');
                $inputQuery .= $this->getOnlyOrInputFileds($onlyOrCondition);
            }
        }
        
        if(count($inArray) > 0) {
            if(empty($inputQuery)){
                $inputQuery = '?q='.$this->getOrInputFileds($orConditionInputs);
            } else {
                $inputQuery .= urlencode(' AND ');
                $inputQuery .= $this->getInArrayInputFileds($inArray);
            }
        }
        // Order query
        if (isset($sort) && !empty($sort)) {
            $sortQuery = $this->getOrderData($sort);
        }
        // Groupby query
        if (isset($facet) && $facet != '') {
            $facetQuery = $this->getfacetData($facet);
            $limit = 0;
        }
        if(empty($inputQuery)){
            $inputQuery="*:*";
            $inputQuery='?q='.urlencode($inputQuery);
        }
        if(!isset($start) && $start == null){
        	$start=0;
        }
        //Preparing the solr url
        $url = $this->solrUrl . $collection."/select". $inputQuery . $sortQuery . "&start=" .$start. "&rows=" . $limit . "&wt=json&indent=true" . $facetQuery;
      // print_r($url);echo '<br>';exit;
//  $eventsResponce = file_get_contents($url);
	   $eventsResponce = $this->connectSolr($url);
        if ($eventsResponce === false) {
            $error = json_encode(array("Error" => "Solr server not found (or) Invalid solr url."));
            return $error;
        }
        $eventsResponce = json_decode($eventsResponce, true);

        if (isset($facet) && $facet != '') {
            $result['response']['facetCounts'] = $eventsResponce['facet_counts']['facet_fields'];
        } else {
            $result['response']['total'] = $eventsResponce['response']['numFound'];
            $result['response']['start'] = $eventsResponce['response']['start'];
            $result['response']['events'] = $eventsResponce['response']['docs'];
        }

        return json_encode($result);
    }
	
	
	// getSitemapSolrEvents() getting events data from solr
    public function getSolrEventsSelectedFields($inputs, $selectFields = array('*'), $sort, $facet = '', $start = 0,$limit = 10,  $collection,$orConditionInputs='',$inArray=array(),$onlyOrCondition='') {
		//print_r($selectFields);
        if($collection=='event'){
          $collection = $this->eventCollection;
        }
        if($collection=='tag'){
          $collection = $this->tagCollection;
        }
        $url = $inputQuery = $sortQuery = $facetQuery = '';
        $result = array();
        // Input perameters
        if (isset($inputs) && !empty($inputs)) {
            $inputQuery = $this->getInputFileds($inputs);
        }
        
        if (isset($orConditionInputs) && !empty($orConditionInputs)) {
            if(empty($inputQuery)){
                $inputQuery = '?q='.$this->getOrInputFileds($orConditionInputs);
            } else {
                $inputQuery .= urlencode(' AND ');
                $inputQuery .= $this->getOrInputFileds($orConditionInputs);
            }
        }
        if (isset($onlyOrCondition) && !empty($onlyOrCondition)) {
            if(empty($inputQuery)){
                $inputQuery = '?q='.$this->getOnlyOrInputFileds($onlyOrCondition);
            } else {
                $inputQuery .= urlencode(' AND ');
                $inputQuery .= $this->getOnlyOrInputFileds($onlyOrCondition);
            }
        }
        
        if(count($inArray) > 0) {
            if(empty($inputQuery)){
                $inputQuery = '?q='.$this->getOrInputFileds($orConditionInputs);
            } else {
                $inputQuery .= urlencode(' AND ');
                $inputQuery .= $this->getInArrayInputFileds($inArray);
            }
        }
        // Order query
        if (isset($sort) && !empty($sort)) {
            $sortQuery = $this->getOrderData($sort);
        }
        // Groupby query
        if (isset($facet) && $facet != '') {
            $facetQuery = $this->getfacetData($facet);
            //$limit = 0;
        }
        if(empty($inputQuery)){
            $inputQuery="*:*";
            $inputQuery='?q='.urlencode($inputQuery);
        }
        if(!isset($start) && $start == null){
        	$start=0;
        }
		
		
		$selectFields = implode(",",$selectFields);
		
        //Preparing the solr url
        $url = $this->solrUrl . $collection."/select". $inputQuery . $sortQuery . "&start=0&rows=".$limit."&fl=".$selectFields."&wt=json&indent=true" . $facetQuery;
		//echo $url;
//  $eventsResponce = file_get_contents($url);
	   $eventsResponce = $this->connectSolr($url);
        if ($eventsResponce === false) {
            $error = json_encode(array("Error" => "Solr server not found (or) Invalid solr url."));
            return $error;
        }
        $eventsResponce = json_decode($eventsResponce, true);

        if (isset($facet) && $facet != '') {
            $result['response']['facetCounts'] = $eventsResponce['facet_counts']['facet_fields'];
        } else {
            $result['response']['total'] = $eventsResponce['response']['numFound'];
            $result['response']['start'] = $eventsResponce['response']['start'];
            $result['response']['events'] = $eventsResponce['response']['docs'];
        }

        return json_encode($result);
    }
	
    
	private function connectSolr($url)
	{
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // return the transfer as a string 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $json = curl_exec($ch); 
        curl_close($ch); 
		return $json;     		
	}
	
    public function getInArrayInputFileds($inputs) {
        $inputQuery = $str = '';
        foreach ($inputs as $inputKey => $inputValue) {
            foreach($inputValue as $inArrayvalue) {
                $str .= $inArrayvalue." ";
            }
            $finalStr = $inputKey.' :('.$str.' )';
        }
        $inputQuery = urlencode($finalStr);
        $inputQuery = $inputQuery;

        return $inputQuery;
    }

    // Input perameters
    public function getInputFileds($inputs) {
        $inputQuery = '';
        foreach ($inputs as $inputKey => $inputValue) {

            $inputQuery.=$inputKey . ':' . $inputValue . ' AND ';
        }
        $inputQuery = urlencode(rtrim($inputQuery, ' AND '));
        $inputQuery = '?q=' . $inputQuery;

        return $inputQuery;
    }
    
    public function getOrInputFileds($inputs) {
        $inputQuery = '';
        foreach ($inputs as $inputKey => $inputValue) {
            $inputValue = str_replace('"', "", $inputValue);
            $inputQuery.=$inputKey . ':*' . $inputValue . '* OR ';
        }
        $inputQuery = urlencode(rtrim($inputQuery, ' OR '));
        $inputQuery = '(' . $inputQuery.')';
        return $inputQuery;
    }
    
    public function getOnlyOrInputFileds($inputs) {
        $inputQuery = '';
        foreach ($inputs as $inputKey => $inputValue) {
            $inputValue = '(' . $inputValue.')';
            $inputValue = str_replace('"', "", $inputValue);
            $inputQuery.=$inputKey . ':' . $inputValue . ' OR ';
        }
        $inputQuery = urlencode(rtrim($inputQuery, ' OR '));
        $inputQuery = '(' . $inputQuery.')';
       
        return $inputQuery;
    }

    // Order query
    public function getOrderData($sort) {
        $sortQuery = '';
        foreach ($sort as $sortKey => $sortValue) {
            $sortQuery.=$sortKey . ' ' . $sortValue . ',';
        }
        $sortQuery = urlencode(rtrim($sortQuery, ','));
        $sortQuery = '&sort=' . $sortQuery;
        return $sortQuery;
    }

    // Groupby query
    public function getfacetData($facet) {
        $facetQuery = $facetString = '';
        $facetArray = explode(',', $facet);
        foreach ($facetArray as $key => $value) {
           $facetString.='&facet.field='.$value; 
        }
        // facet.mincount=1 is for Facet Fields with No Zeros
        $facetQuery = '&facet=true' . $facetString . '&facet.mincount=1&json.nl=arrarr';
        return $facetQuery;
    }

    
    // Add Solr Event

    public function addSolr($dataString,$collection) {
         if($collection=='event'){
          $collection = $this->eventCollection;
        }
        if($collection=='tag'){
          $collection = $this->tagCollection;
        }
        $data = array(
            "add" => array(
                "doc" => $dataString,
                "commitWithin" => 1000,
            ),
        );
        $inputData = json_encode($data);
        $ch = curl_init($this->solrUrl . $collection.'/update?wt=json');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $inputData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json')
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $result = curl_exec($ch);
        $a = curl_getinfo($ch);
        curl_close($ch);
        return $a;
    }
    
    // Delete Solr Event
    
    public function deleteSolr($dataString,$collection){
        if($collection=='event'){
          $collection = $this->eventCollection;
        }
        if($collection=='tag'){
          $collection = $this->tagCollection;
        }
        $deletedData ='<delete><query>id:'.$dataString.'</query></delete>';
        $ch = curl_init($this->solrUrl.$collection.'/update?stream.body='.$deletedData.'&commit=true');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json')
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $result = curl_exec($ch);
        $a = curl_getinfo($ch);
        curl_close($ch);
        return $a;
    }
    
    
    //update event in solr
    
    
     public function updateSolr($dataString,$collection) {
         if($collection=='event'){
          $collection = $this->eventCollection;
          $dataString['mts']=date("Y-m-d\TH:i:s\Z");
        }
        if($collection=='tag'){
          $collection = $this->tagCollection;
        }
        $data = array(
            "add" => array(
                "doc" => $dataString,
                "commitWithin" => 1000,
            ),
        );
        $inputData = json_encode($data);
        $ch = curl_init($this->solrUrl . $collection.'/update?wt=json');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $inputData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json')
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $result = curl_exec($ch);
        $a = curl_getinfo($ch);
        curl_close($ch);
        return $a;
    }
    
}
