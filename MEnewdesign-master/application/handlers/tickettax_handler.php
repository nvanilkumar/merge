<?php

/**
 * TicketTax related business logic will be defined in this class
 * Getting TicketTax Related data
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		CountryId - required
 *                      cityId,categoryId,type (optional)
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     16-06-2015
 * @Last Modified 16-06-2015
 */
require_once(APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/country_handler.php');
require_once(APPPATH . 'handlers/taxmapping_handler.php');
require_once(APPPATH . 'handlers/tax_handler.php');

class Tickettax_handler extends Handler {

    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('TicketTax_model');
        $this->ci->load->model('Tax_model');
    }

    function create($ticketId, $inputArray) {
        $createTicketTax = array();
        $ticketTax = array();

        for ($i = 0; $i < count($createTicketTax[$this->ci->TicketTax_model->label]); $i++) {
            $ticketTax['label'] = $createTicketTax[$this->ci->TicketTax_model->label][$i];
            $ticketTax['value'] = $createTicketTax[$this->ci->TicketTax_model->value][$i];
            $ticketTax[$this->ci->TicketTax_model->ticketid] = $ticketId;
            $this->ci->TicketTax_model->setInsertUpdateData($ticketTax);
            $status = $this->ci->TicketTax_model->insert_data();
        }
        if ($status) {
            $output['status'] = TRUE;
            $output["response"]["message"][] = TICKET_TAX_ADDED;
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output["response"]["message"][] = ERROR_ADD_TICKET;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
    }

    /*
     * Function to get the Taxes based on country,state,city
     *
     * @access	public
     * @param	$inputArray contains
     * 			countryName - string
     * 			stateName - string (optional)
     * 			cityName - string (optional)
     * 			taxMappingId - integer (optional)
     * @return	array
     */

    public function getTaxes($inputArray) {
        $status='';
        if(isset($inputArray['status'])){
          $status=" AND status = ".$inputArray['status'];  
        }
        if (!isset($inputArray['countryid'])) {
            $countryName = $inputArray['countryName'];
            $stateName = $inputArray['stateName'];
            $cityName = $inputArray['cityName'];

            $taxMappingId = isset($inputArray['taxMappingId'])?$inputArray['taxMappingId']:'';
            $countryId = $stateId = $cityId = '';
            $finalTaxArray = array();

            if ($taxMappingId == '') {
                // Code to get the Country Data starts here
                $this->countryHandler = new Country_handler();
                $countryInputArray['keyWord'] = $countryName;
                $countryInputArray['isNameExact'] = true;
                $countryData = $this->countryHandler->searchByKeyword($countryInputArray);

                if ($countryData['response']['total'] > 0) {
                    $countryId = $countryData['response']['countryList'][0]['id'];
                } else {
                    $output['status'] = TRUE;
                    $output['response']['messages'][] = ERROR_NO_DATA;
                    $output['response']['total'] = 0;
                    $output['statusCode'] = STATUS_OK;
                    return $output;
                }
                // Code to get the Country Data ends here

                if ($countryId > 0) {
                    // Code to get the State Data starts here
                    if ($stateName != '') {
                        $this->stateHandler = new State_handler();
                        $stateInputArray['keyWord'] = $stateName;
                        $stateInputArray['countryId'] = $countryId;
                        $stateInputArray['isNameExact'] = true;
                        $stateData = $this->stateHandler->searchByKeyword($stateInputArray);

                        if ($stateData['response']['total'] > 0) {
                            $stateId = $stateData['response']['stateList'][0]['id'];
                        }
                    }
                    // Code to get the State Data ends here
                }

                if ($stateId > 0) {
                    // Code to get the City Data starts here
                    if ($cityName != '') {
                        $this->cityHandler = new City_handler();
                        $cityInputArray['keyWord'] = $cityName;
                        $cityInputArray['countryId'] = $countryId;
                        $cityInputArray['isNameExact'] = true;
                        $cityInputArray['major'] = 0;
                        $cityData = $this->cityHandler->getCitySearch($cityInputArray);

                        if ($cityData['response']['total'] != 0) {
                            $cityId = $cityData['response']['cityList'][0]['id'];
                        }
                    }
                    // Code to get the City Data ends here
                }
            } else {
                $where = $this->ci->TicketTax_model->id . " = " . $taxMappingId. $status;
            }
        } else {
            $countryId = $inputArray['countryid'];
            $stateId = $inputArray['stateid'];
            $cityId = $inputArray['cityid'];
        }
        $tempMapWhere = $countryMappingDetails = $stateMappingDetails = $cityMappingDetails = array();

        $stateCondition = $cityCondition = '';
        if ($stateId > 0) {
            $stateCondition = " OR (type = 'state' AND stateid = " . $stateId . ") ";
            if ($cityId > 0) {
                $cityCondition = " OR (type = 'city' AND stateid = " . $stateId . " AND cityid = " . $cityId . ") ";
            }
        }
            
        if($taxMappingId == '') {
            $where = "countryid = $countryId AND 
                ((type = 'country') $stateCondition $cityCondition)$status";
        }
        



        $select['id'] = $this->ci->TicketTax_model->id;
        $select['taxid'] = $this->ci->TicketTax_model->taxid;
        $select['value'] = $this->ci->TicketTax_model->value;
        $select['type'] = $this->ci->TicketTax_model->type;
        $tempMappingDetails = $this->ci->TicketTax_model->getTaxes($select, $where, true, '', 'taxmapping');
        //echo $this->ci->db->last_query();exit;
        $taxMappingDetails = array();
        $selectTax[$this->ci->TicketTax_model->label] = $this->ci->TicketTax_model->label;
        $this->ci->TicketTax_model->setSelect($selectTax);
        $this->ci->TicketTax_model->setTableName('tax');
        $this->ci->TicketTax_model->setGroupBy(array($this->ci->TicketTax_model->id));
        $taxWhere[$this->ci->TicketTax_model->status] = 1;
        $taxWhere[$this->ci->TicketTax_model->deleted] = 0;

        if (is_array($tempMappingDetails) && count($tempMappingDetails) > 0) {

            // Getting the tax label from `tax` table and inserting into `tax details array`
            foreach ($tempMappingDetails as $taxMap) {
                $taxWhere[$this->ci->TicketTax_model->id] = $taxMap[$this->ci->TicketTax_model->taxid];
                $this->ci->TicketTax_model->setWhere($taxWhere);
                $taxDetails = $this->ci->TicketTax_model->get();
                if (is_array($taxDetails) && count($taxDetails) > 0) {
                    $taxMap[$this->ci->TicketTax_model->label] = $taxDetails[0][$this->ci->TicketTax_model->label];
                    $taxMappingDetails[$taxMap[$this->ci->TicketTax_model->taxid]][$taxMap[$this->ci->TicketTax_model->type]] = $taxMap;
                }
            }

            foreach ($taxMappingDetails as $taxMappingDetail) {
                // Filtering the Tax based on the priority from city,state,country
                if (isset($taxMappingDetail['city']) && count($taxMappingDetail['city']) > 0) {
                    $finalTaxArray[] = $taxMappingDetail['city'];
                } elseif (isset($taxMappingDetail['state']) && count($taxMappingDetail['state']) > 0) {
                    $finalTaxArray[] = $taxMappingDetail['state'];
                } else {
                    $finalTaxArray[] = $taxMappingDetail['country'];
                }
            }
        }
        if (count($finalTaxArray) > 0) {
            $output['status'] = TRUE;
            $output['response']['taxList'] = $finalTaxArray;
            $output['response']['messages'] = array();
            $output['response']['total'] = 1;
            $output['statusCode'] = STATUS_OK;
            return $output;
        } else {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_TAX;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
    }

    /**
     * To add multiple ticket taxes
     * @param type $ticketTax
     * $tickettax[][taxid]
     * $tickettax[][ticektid]
     * @param type  $userData['createdby']
     * @param type  $userData['modifiedby']
     * @return int
     */
    public function addTicketTax($ticketTax, $userData) {
        $ticketTaxData = array();
        foreach ($ticketTax as $key => $ticketTaxValue) {

            $ticketTaxData[$key]['taxid'] = $ticketTaxValue['taxid'];
            $ticketTaxData[$key]['ticektid'] = $ticketTaxValue['ticektid'];
        }
        $this->ci->TicketTax_model->setInsertUpdateData($ticketTaxData);
        $response = $this->ci->TicketTax_model->insertMultiple_data();
        if ($response) {
            $output['status'] = TRUE;
            $output['response']['affectedRows'] = $response;
            $output["response"]["messages"] = array();
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        } else {
            $output['status'] = FALSE;
            $output["response"]["messages"][] = ERROR_SOMETHING_WENT_WRONG;
            $output['statusCode'] = STATUS_SERVER_ERROR;
            return $output;
        }
    }

    /**
     * fetch taxes based on tickets

     */
    public function getTicketTaxByTicketId($inputArray) {
        
        $this->TaxMappingHandler = new Taxmapping_handler();
        $selectinput = array();
		$this->ci->TicketTax_model->resetVariable();		
        $selectinput['taxmappingid'] = $this->ci->TicketTax_model->taxmappingid;
        $selectinput['ticketid'] = $this->ci->TicketTax_model->ticketid;
        $this->ci->TicketTax_model->setSelect($selectinput);
        $whereIn[$this->ci->TicketTax_model->ticketid] = $inputArray['ticketIds'];
        $where[$this->ci->TicketTax_model->deleted] = 0;
        $where[$this->ci->TicketTax_model->status] = 1;
        //$where[$this->ci->TicketTax_model->taxmappingid.' > '] = 0;
        $this->ci->TicketTax_model->setWhere($where);
        $this->ci->TicketTax_model->setWhereIns($whereIn);
        $response = $this->ci->TicketTax_model->get();
        if (count($response) == 0) {
            $output['status'] = TRUE;
            $output["response"]["message"][] = ERROR_NO_TAX;
            $output["response"]["total"] = 0;
            $output['statusCode'] = STATUS_OK;
            return $output;
        }
        $uniqueTaxMapping = array();
        foreach ($response as $value) {
            $uniqueTaxMapping[$value['taxmappingid']] = $value['taxmappingid'];
        }

        $input['ids'] = $uniqueTaxMapping;
        $taxMappingList = $this->TaxMappingHandler->getTaxmapping($input);
        if ($taxMappingList['status'] && $taxMappingList['response']['total'] > 0) {
            $responseTaxMapping = $taxMappingList['response']['taxMappingList'];
            $indexedTaxMapping = commonHelperGetIdArray($responseTaxMapping);
        } else {
            return $taxMappingList;
        }
        
        $taxHandler = new Tax_handler();
        $getTaxResponse = $taxHandler->getTaxList();
        if ($getTaxResponse['status'] && $getTaxResponse['response']['total'] > 0) {
            $responseTax = $getTaxResponse['response']['taxList'];
            $indexedTax = commonHelperGetIdArray($responseTax);
        } else {
            return $getTaxResponse;
        }
        
        foreach ($response as $key => $value) {
            $response[$key]['taxlabel'] = $indexedTax[$indexedTaxMapping[$value['taxmappingid']]['taxid']]['label'];
            $response[$key]['taxid'] = $indexedTaxMapping[$value['taxmappingid']]['taxid'];
            $response[$key]['taxvalue'] = $indexedTaxMapping[$value['taxmappingid']]['value'];
            $response[$key]['taxmappingid']=$value['taxmappingid'];
        }
        $finalResponse = array();
        foreach ($response as $key => $value) {
            $finalResponse[$value['ticketid']]['tax'][$value['taxid']]['label'] = $value['taxlabel'];
            $finalResponse[$value['ticketid']]['tax'][$value['taxid']]['id'] = $value['taxid'];
            $finalResponse[$value['ticketid']]['tax'][$value['taxid']]['value'] = $value['taxvalue'];
            $finalResponse[$value['ticketid']]['tax'][$value['taxid']]['taxmappingid'] = $value['taxmappingid'];
        }
        //creating response output 
        $output = parent::createResponse(TRUE, "", STATUS_OK, count($finalResponse), 'ticketTaxList', $finalResponse);
        return $output;
    }

}

?>