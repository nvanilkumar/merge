<?php
/**
 * Bookmark related business logic will be defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     14-08-2015
 * @Last Modified 14-08-2015
 */

require_once(APPPATH . 'handlers/handler.php');
require_once(APPPATH . 'handlers/event_handler.php');

class Bookmark_handler extends Handler {

    var $ci;
	
    public function __construct() 
	{
		parent::__construct();
		$this->ci = parent::$CI;
		$this->ci->load->model('Bookmark_model');
    }
	
	/*
    * Function to bookmark the event
    *
    * @access	public
    * @param	$inputArray contains
    * 				eventId - integer
    * 				userId - integer
    * @return	array
    */
	function saveBookmark($inputArray) {
		
	$this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'event id', 'required_strict|is_natural_no_zero');

        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
		}
		
		$userId = $this->ci->customsession->getUserId();
		/* check whether the user already bookmarked it or not */
                        $this->ci->Bookmark_model->resetVariable();
			$selectInput[$this->ci->Bookmark_model->id] = $this->ci->Bookmark_model->id;
			$this->ci->Bookmark_model->setSelect($selectInput);
			
			$where[$this->ci->Bookmark_model->deleted] = 0;
			$where[$this->ci->Bookmark_model->userid] = $userId;
			$where[$this->ci->Bookmark_model->eventid] = $inputArray['eventId'];
			$this->ci->Bookmark_model->setWhere($where);
			$bookmarkResponse = $this->ci->Bookmark_model->get();
			
			if(is_array($bookmarkResponse) && count($bookmarkResponse) > 0) {
				$output['status'] = FALSE;
				$output["response"]["messages"][] = ERROR_ALREADY_BOOKMARK;
				$output['statusCode'] = STATUS_OK;
				return $output;
			}
		
		/* If he is not bookmarked already save the bookmark */
		$createBookmark[$this->ci->Bookmark_model->userid] = $userId;
		$createBookmark[$this->ci->Bookmark_model->eventid] = $inputArray['eventId'];
		
        $this->ci->Bookmark_model->setInsertUpdateData($createBookmark);
        $bookmarkId = $this->ci->Bookmark_model->insert_data();
		if($bookmarkId) {
			$output['status'] = TRUE;
            $output["response"]["messages"][] = SUCCESS_BOOKMARK_SAVED;
            $output['statusCode'] = STATUS_CREATED;
            return $output;
		}
		$output['status'] = FALSE;
		$output["response"]["messages"][] = ERROR_SAVE_BOOKMARK;
		$output['statusCode'] = STATUS_SERVER_ERROR;
		return $output;
	}
	
	/*
    * Function to remove the bookmark of the event
    *
    * @access	public
    * @param	$inputArray contains
    * 				eventId - integer
    * 				userId - integer
    * @return	array
    */
	function removeBookmark($inputArray) {
		
		$this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventId', 'event id', 'required_strict|is_natural_no_zero');

        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors('message');
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
		}
		
		$userId = $this->ci->customsession->getUserId();
		/* check whether the user already bookmarked it or not */
                $this->ci->Bookmark_model->resetVariable();
		$selectInput[$this->ci->Bookmark_model->id] = $this->ci->Bookmark_model->id;
		$this->ci->Bookmark_model->setSelect($selectInput);
		
		$where[$this->ci->Bookmark_model->deleted] = 0;
		$where[$this->ci->Bookmark_model->userid] = $userId;
		$where[$this->ci->Bookmark_model->eventid] = $inputArray['eventId'];
		$this->ci->Bookmark_model->setWhere($where);
		$bookmarkResponse = $this->ci->Bookmark_model->get();
		
		if(is_array($bookmarkResponse) && count($bookmarkResponse) > 0) {
			/* If he is bookmarked already remove the bookmark */
			$updateBookmarkInput[$this->ci->Bookmark_model->userid] = $userId;
			$updateBookmarkInput[$this->ci->Bookmark_model->eventid] = $inputArray['eventId'];
			
			$updateBookmarkData[$this->ci->Bookmark_model->deleted] = 1;
			$this->ci->Bookmark_model->setWhere($updateBookmarkInput);
			$this->ci->Bookmark_model->setInsertUpdateData($updateBookmarkData);
			
			$removeStatus = $this->ci->Bookmark_model->update_data();
			if($removeStatus) {
				$output['status'] = TRUE;
				$output["response"]["messages"][] = SUCCESS_BOOKMARK_REMOVED;
				$output['statusCode'] = STATUS_UPDATED;
				return $output;
			}
			$output['status'] = TRUE;
			$output["response"]["messages"][] = ERROR_REMOVE_BOOKMARK;
			$output['statusCode'] = STATUS_SERVER_ERROR;
			return $output;
		}
		$output['status'] = TRUE;
		$output["response"]["messages"][] = ERROR_NOT_BOOKMARK;
		$output['statusCode'] = STATUS_SERVER_ERROR;
		return $output;
	}
	
	/*
    * Function to get the bookmarks of the user
    *
    * @access	public
    * @param	$inputArray contains
    * 				userId - integer
    * 				returnEventIds - If `True` return the event Ids array
    * @return	array
    */
	function getUserBookmarks($inputArray) {
		
		$userId = $this->ci->customsession->getUserId();
		/* check whether the user already bookmarked it or not */
            $this->ci->Bookmark_model->resetVariable();
			$selectInput[$this->ci->Bookmark_model->eventid] = $this->ci->Bookmark_model->eventid;
			$this->ci->Bookmark_model->setSelect($selectInput);
			
			$where[$this->ci->Bookmark_model->deleted] = 0;
			$where[$this->ci->Bookmark_model->userid] = $userId;
			if(isset($inputArray['eventId']) && $inputArray['eventId'] > 0) {
				$where[$this->ci->Bookmark_model->eventid] = $inputArray['eventId'];
			}
			$this->ci->Bookmark_model->setWhere($where);
			$bookmarkResponse = $this->ci->Bookmark_model->get();
			
			$bookmarkedEvents = array();
			foreach($bookmarkResponse as $bookmarks) {
				$bookmarkedEvents['eventIdsArray'][] = $bookmarks['eventId'];
			}
			if($inputArray['returnEventIds']) {
                            $bookMarkEvents=(isset($bookmarkedEvents['eventIdsArray']))?$bookmarkedEvents['eventIdsArray']:array();
				$output['status'] = TRUE;
				$output["response"]['bookmarkedEvents'] = $bookMarkEvents;
				$output["response"]['total'] = count($bookMarkEvents);
				$output['statusCode'] = STATUS_OK;
				return $output;
			}
			$this->eventHandler = new Event_handler();
			$bookmarkEvents = $this->eventHandler->getListByEventIds($bookmarkedEvents);
			if($bookmarkEvents['status'] && $bookmarkEvents['response']['total'] > 0) {
				$output['status'] = TRUE;
				$output["response"] = $bookmarkEvents['response'];
				$output["response"]['total'] = $bookmarkEvents['response']['total'];
				$output['statusCode'] = STATUS_OK;
				return $output;
			}
		$output['status'] = TRUE;
		$output["response"]["messages"][] = ERROR_NO_USER_BOOKMARKS;
		$output["response"]['total'] = 0;
		$output['statusCode'] = STATUS_OK;
		return $output;
	}
	
		}
		
