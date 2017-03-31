<?php

/**
 * Banner related business logic will be defined in this class
 * Getting Banners Related data
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @param		CountryId - required
 *                      cityId,categoryId,type (optional)
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified On  25-06-2015
 * @Last Modified By  Sridevi
 */
require_once(APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/city_handler.php');
require_once(APPPATH . 'handlers/file_handler.php');

class Banner_handler extends Handler {

    var $fileHandler;
    var $cityHandler;
    var $ci;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
    }

    function getBannerList($inputArray) {
		
        $this->ci->load->library('form_validation');
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('countryId', 'Country Id', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('cityId', 'City Iddd', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('categoryId', 'Category Id', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('type', 'Type', 'type|required_strict');
        $this->ci->form_validation->set_rules('limit', 'limit', 'is_natural_no_zero');
        $countryId = $inputArray['countryId'];
        $cityId = (isset($inputArray['cityId']) ? $inputArray['cityId'] : 0 );
        $categoryId = (isset($inputArray['categoryId']) ? $inputArray['categoryId'] : 0);
        $type = (isset($inputArray['type']) ? $inputArray['type'] : 1);
        $limit = (isset($inputArray['limit']) && $inputArray['limit'] > 0) ? $inputArray['limit'] : 10;
        $limit = ($type == 1) ? 10 : $limit;
        $imageType = ($type == 1) ? IMAGE_TOP_BANNER : IMAGE_BOTTOM_BANNER;
        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $validationStatus = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $validationStatus['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        } else {
            $this->ci->load->model('banner_mapping_model');
            $this->fileHandler = new File_handler();
            $this->cityHandler = new City_handler();
            $majorCityList = array();
            $this->ci->banner_mapping_model->resetVariable();
            $selectInput['bannerid'] = $this->ci->banner_mapping_model->bannerid;
            $selectInput['title'] = $this->ci->banner_mapping_model->title;
            $selectInput['type'] = $this->ci->banner_mapping_model->type;
            $selectInput['url'] = $this->ci->banner_mapping_model->url;
            $selectInput['eventid'] = $this->ci->banner_mapping_model->eventid;
            $selectInput['imagefileid'] = $this->ci->banner_mapping_model->imagefileid;
            $where = array();
            $groupBy = array();
            $orderBy = array();
            $cityOrWhere = array();
            $categoryOrWhere = array();
            $customWhere = array();
            if ($cityId > 0) {
                $cityInputArray = array('countryId' => $countryId, 'major' => 1);
                $majorCityListRes = $this->cityHandler->getCityList($cityInputArray);
                if ($majorCityListRes['status'] == TRUE) {
                    $majorCityList = commonHelperGetIdArray($majorCityListRes['response']['cityList']);
                    if (array_key_exists($cityId, $majorCityList) == false) {
                        $cityOrWhere[$this->ci->banner_mapping_model->othercities] = 1;
                    }
                }
                if (strlen($majorCityList[$cityId]['aliascityid']) > 0) {

                    $cityArr = explode(',', $majorCityList[$cityId]['aliascityid']);
                }
                $cityArr[] = $cityId;
                $cityOrWhere[$this->ci->banner_mapping_model->cityid] = $cityArr;
            } else {
                $cityOrWhere[$this->ci->banner_mapping_model->allcities] = 1;
            }

            $where[$this->ci->banner_mapping_model->countryid] = $countryId;

            if ($categoryId > 0) {
                $categoryOrWhere[$this->ci->banner_mapping_model->categoryid] = $categoryId;
            } else {
                $categoryOrWhere[$this->ci->banner_mapping_model->allcategories] = 1;
            }
			
            // This was a quck fix to not to allow negative orders - Sai Sudheer
//            $where['order >'] = 0;//Need to accept negitve values
            $where[$this->ci->banner_mapping_model->type] = $type;
            $where[$this->ci->banner_mapping_model->status] = 1;
            $where[$this->ci->banner_mapping_model->deleted] = 0;
            $where['startdatetime <='] = allTimeFormats('', 6);
            $where['enddatetime >='] = allTimeFormats('', 6);
            $groupBy['bannerid'] = $this->ci->banner_mapping_model->bannerid;
            $orderBy['order'] = $this->ci->banner_mapping_model->order;
            // getting response from DB
            $this->ci->banner_mapping_model->setSelect($selectInput);
            $this->ci->banner_mapping_model->setOrWhere($categoryOrWhere);
            $this->ci->banner_mapping_model->setOrWhere($cityOrWhere);
            $this->ci->banner_mapping_model->setWhere($where);
            $this->ci->banner_mapping_model->setGroupBy($groupBy);
            $this->ci->banner_mapping_model->setOrderBy($orderBy);
            $this->ci->banner_mapping_model->setRecords($limit);
            $bannerResponse = $this->ci->banner_mapping_model->get();
			//print_r($bannerResponse); exit;
			
//            echo $this->ci->db->last_query();exit;
             //echo "<pre>";print_r($bannerResponse); exit;
            if ($bannerResponse != FALSE && count($bannerResponse) > 0) {
                $bannerImageIdArray = array();
                foreach ($bannerResponse as $bannerkey => $banner) {
                    $bannerImageIdArray[] = $banner['imagefileid'];
                }
                // getting file path fro banners from file table 
                $fileData = $this->fileHandler->getFileData(array('id', $bannerImageIdArray));
                if ($fileData['status'] && $fileData['response']['total'] > 0) {
                    $fileDataTemp = commonHelperGetIdArray($fileData['response']['fileData']);
                }
                $response = array();
                foreach ($bannerResponse as $banKey => $banValue) {
                    $response[$banKey] = $banValue;
                    $fileDbPath = (strlen(trim($fileDataTemp[$banValue['imagefileid']]['path'])) > 0) ? $fileDataTemp[$banValue['imagefileid']]['path'] : "";
                    if (strlen($fileDbPath) > 0) {
                        $response[$banKey]['bannerImage'] = $this->ci->config->item('images_content_cloud_path') . $fileDbPath;
                    }/* else{
                      $response[$banKey]['bannerImage'] = commonHelperDefaultImage($fileDbPath, $imageType);
                      } */
                }
				
                $total = count($response);
                $output['status'] = TRUE;
                $output['response']['bannerList'] = $response;
                $output['response']['total'] = $total;
                $output['response']['messages'] = array();
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else if (count($bannerResponse) == 0) {
                $output['status'] = TRUE;
                $output["response"]["messages"][] = ERROR_NO_DATA;
                $output['response']['total'] = 0;
                $output['statusCode'] = STATUS_OK;
                return $output;
            } else {
                $output['status'] = FALSE;
                $output["response"]["messages"][] = ERROR_INTERNAL_DB_ERROR;
                $output['statusCode'] = STATUS_SERVER_ERROR;
                return $output;
            }
        }
    }

}
