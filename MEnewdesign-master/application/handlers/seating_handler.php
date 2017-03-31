<?php

/**
 * Organization related Events(grouped)
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created    07-12-2015
 * @Last Modified  
 * @Last Modified by Raviteja V
 */
require_once (APPPATH . 'handlers/handler.php');

class Seating_handler extends Handler {

    var $ci, $eventHandler, $seatingHandler, $userHandler;

    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('Venueseat_model');
        //$this->estdHandler = new ();
    }

    public function getData($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'is_natural_no_zero|required_strict');
        // $this->ci->form_validation->set_rules('userId', 'User Id', 'is_natural_no_zero|required_strict');
        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $eventId = $inputArray['eventid'];

        $selectData['Id'] = $this->ci->Venueseat_model->id;
        $selectData['GridPosition'] = $this->ci->Venueseat_model->gridposition;
        $selectData['Seatno'] = $this->ci->Venueseat_model->seatno;
        $selectData['Status'] = $this->ci->Venueseat_model->status;
        $selectData['type'] = $this->ci->Venueseat_model->type;
        $selectData['eventid'] = $this->ci->Venueseat_model->eventid;
        $selectData['ticketid'] = $this->ci->Venueseat_model->ticketid;
        $selectData['eventsignupid'] = $this->ci->Venueseat_model->eventsignupid;
        $this->ci->Venueseat_model->setSelect($selectData);
        $where[$this->ci->Venueseat_model->eventid] = $eventId;
        $where[$this->ci->Venueseat_model->deleted] = 0;
        $orderBy[] = $this->ci->Venueseat_model->id;
        $this->ci->Venueseat_model->setOrderBy($orderBy);
        //$whereIn[$this->ci->Venueseat_model->type] = 'Level-1';
        $this->ci->Venueseat_model->setWhere($where);
        $ResSeatslevel = $this->ci->Venueseat_model->get();
        $response['status'] = true;
        if (count($ResSeatslevel) == 0) {
            $data['status'] = TRUE;
            $data['response']['total'] = 0;
            $data['response']['messages'][] = ERROR_NO_DATA;
            return $data;
        }
        //print_r($ResSeatslevel);exit;
        foreach ($ResSeatslevel as $value) {
            $finalData[$value['type']][] = $value;       
        }
        $response['data']['ResSeats'] = $finalData;
        return $response;
    }

    public function insertSeats($inputArray) {

        $eventid = $inputArray['eventId'];
        $venueseatid = $inputArray['venueseatid'];
        $seattype = $inputArray['seattype'];
        $rowwise = $inputArray['rowwise'];
        $maxcount = $inputArray['maxcount'];
        $alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $level = array('Level1' => 'AM', 'Level2' => 'KS', 'Level2' => 'TZ', 'Balcony' => 'AM');
        $ticketIds = array('Level1' => 69505, 'Level2' => 69505, 'Level2' => 69506, 'Balcony' => 69505);
        /* 		$levels = array (
          'Level1' => 'AJ',
          'Level2' => 'KS',
          'Level3' => 'TZ',
          'Balcony' => 'AM'
          ); */
        $query = '';
        $rowwise = array(
            'Level1-A' => '35,12x-09i-04x-17i-05x-09i-L',
            'Level1-B' => '38,13x-10i-04x-17i-05x-11i-L',
            'Level1-C' => '41,12x-11i-04x-18i-04x-12i-L',
            'Level1-D' => '42,12x-11i-03x-19i-04x-12i-L',
            'Level1-E' => '42,12x-11i-03x-19i-04x-12i-L',
            'Level1-F' => '47,10x-13i-03x-20i-03x-14i-L',
            'Level1-G' => '49,09x-14i-03x-20i-03x-15i-L',
            'Level1-H' => '50,09x-14i-03x-21i-02x-15i-L',
            'Level1-I' => '50,09x-14i-03x-21i-02x-15i-L',
            'Level1-J' => '53,08x-15i-02x-22i-02x-16i-L',
            'Level2-K' => '54,08x-15i-01x-23i-02x-16i-L',
            'Level2-L' => '53,08x-15i-01x-23i-02x-15i-L',
            'Level2-M' => '24,24x-24i-L',
            'Level2-N' => '61,3x-20i-03x-21i-04x-20i-L',
            'Level2-O' => '61,3x-20i-03x-21i-04x-20i-L',
            'Level2-P' => '60,04x-19i-03x-22i-03x-20i-01x-L',
            'Level2-Q' => '62,03x-20i-03x-22i-03x-21i-L',
            'Level2-R' => '63,03x-20i-03x-23i-02x-21i-L',
            'Level2-S' => '41,05x-18i-02x-23i-L',
            'Level3-T' => '41,06x-17i-02x-24i-L',
            'Level3-U' => '56,07x-16i-02x-24i-02x-16i-L',
            'Level3-V' => '55,08x-15i-01x-25i-02x-15i-L',
            'Level3-W' => '53,09x-14i-01x-25i-02x-14i-L',
            'Level3-X' => '54,09x-14i-01x-26i-01x-14i-L',
            'Level3-Y' => '50,11x-12i-01x-26i-01x-12i-L',
            'Level3-Z' => '52,09x-14i-02x-24i-02x-14i-L',
            'Balcony-A' => '68,23i-02x-22i-03x-23i-L',
            'Balcony-B' => '69,23i-02x-23i-02x-23i-L',
            'Balcony-C' => '69,23i-02x-23i-02x-23i-L',
            'Balcony-D' => '68,01x-22i-01x-24i-02x-22i-L',
            'Balcony-E' => '68,01x-22i-01x-24i-02x-22i-L',
            'Balcony-F' => '71,23i-01x-25i-01x-23i-L',
            'Balcony-G' => '66,02x-21i-01x-25i-01x-20i-L',
            'Balcony-H' => '52,06x-17i-05x-18i-04x-17i-L',
            'Balcony-I' => '50,07x-16i-05x-18i-04x-16i-L',
            'Balcony-J' => '50,07x-16i-04x-19i-04x-15i-L',
            'Balcony-K' => '49,08x-15i-04x-19i-04x-15i-L',
            'Balcony-L' => '52,07x-16i-03x-20i-04x-16i-L',
            'Balcony-M' => '58,04x-19i-04x-20i-03x-19i-L'
        );
        $maxcount = 74;
        $query = "INSERT INTO `VenueSeat` ( `Id` , `VenueId` , `GridPosition` , `Seatno` , `Price` , `Type` , `Status` , `EventId` , `EventSIgnupId` , `BDate`,`ticketid` )
VALUES ";
        //print_r($level);exit;
        foreach ($level as $levelkey => $levelvalue) {
            //  echo $levelkey;
            $startchar = substr($levelvalue, 0, 1);
            $lastchar = substr($levelvalue, 1, 1);
            $ticketId = $ticketIds[$levelkey];
            // running loop for alphabet array containing another loop 74times for seatnumbers
            foreach ($alpha as $key => $value) {
                $alpabet = $value;

                if ((ord($value) >= ord($startchar)) && (ord($lastchar) >= ord($value))) {

                    for ($i = 1; $i <= $maxcount; $i ++) {

                        $rowcheck = $levelkey . '-' . $alpabet;
                        // checking if alignment of a particular row is set or not
                        //	echo $rowcheck;
                        if (array_key_exists($rowcheck, $rowwise)) {
                            $explode = explode(',', $rowwise [$rowcheck]);
                            $startcount = $explode [0];

                            $explode2 = explode('-', $explode [1]);

                            foreach ($explode2 as $ekey => $evalue) {
                                $lastchars = substr($evalue, - 1);
                                $number = substr($evalue, 0, 2);
                                if (strcmp('x', $lastchars) == 0) {
                                    for ($x = 0; $x < $number; $x ++) {
                                        $seatgrid = $alpabet . $i;
                                        $val = 0;
                                        $query .= " (NULL , '$venueseatid', '" . $seatgrid . "', '" . $val . "', '100', '" . $levelkey . "', 'Available', '$eventid', '0','', '$ticketId'
    						),";
                                        $i ++;
                                    }
                                    continue;
                                } else if (strcmp('i', $lastchars) == 0) {

                                    for ($x = 0; $x < $number; $x ++) {
                                        $seatgrid = $alpabet . $i;

                                        $query .= " (
    					NULL , '$venueseatid', '" . $seatgrid . "', '" . $startcount . "', '100', '" . $levelkey . "', 'Available', '$eventid', '0', '','$ticketId'
    					),";
                                        $i ++;
                                        $startcount --;
                                    }
                                    continue;
                                } else {
                                    $remaining = $maxcount - $i;

                                    for ($x = 0; $x < $remaining; $x ++) {
                                        $seatgrid = $alpabet . $i;
                                        $val = 0;
                                        $query .= " (
    		NULL , '$venueseatid', '" . $seatgrid . "', '" . $val . "', '100', '" . $levelkey . "', 'Available', '$eventid', '0', '','$ticketId'
    					),";
                                        $i ++;
                                    }
                                    continue;
                                }
                            }
                        } else {
                            $seatgrid = $alpabet . $i;

                            $val = $i;
                            $query .= " (NULL , '$venueseatid', '" . $seatgrid . "', '" . $val . "', '100', '" . $levelkey . "', 'Available', '$eventid', '0', '','$ticketId'
    							),";
                        }

                        //	echo $i . "<br />";
                    } // end of for with max count
                } // end of char code check
            }
        }

        $query = substr($query, 0, - 1);
        print_r($query);
        //exit;
        $res = $this->ci->db->query($query);
        if ($res) {
            return "Seats Inserted Successfully";
        }
        return $this->ci->db->_error_message();
    }

    public function checkLayout($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'is_natural_no_zero|required_strict');
        // $this->ci->form_validation->set_rules('userId', 'User Id', 'is_natural_no_zero|required_strict');
        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $eventId = $inputArray['eventid'];
        $where[$this->ci->Venueseat_model->eventid] = $eventId;
        $this->ci->Venueseat_model->setWhere($where);
        $countResponse = $this->ci->Venueseat_model->getCount();
        $output['status'] = TRUE;
        $output['statusCode'] = STATUS_OK;
        $output['response']['total'] = 1;
        $output['response']['messages'][] = [];
        $output['response']['seatingEnabled'] = FALSE;
        if ($countResponse > 0) {
            $output['response']['total'] = $countResponse;
            $output['response']['seatingEnabled'] = TRUE;
        }
        return $output;
    }

    public function updateSeats($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('venueseatid', 'venueseatid', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('type', 'type', 'required_strict');
        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $this->ci->Venueseat_model->resetVariable();
        $eventId = $inputArray['eventid'];
        $venueSeatId = $inputArray['venueseatid'];
        $type = strtolower($inputArray['type']);
        $eventsignupId = $inputArray['eventsignupid'];
        $selectVenueSeat['status'] = $this->ci->Venueseat_model->status;
        $this->ci->Venueseat_model->setSelect($selectVenueSeat);
        $where[$this->ci->Venueseat_model->id] = $venueSeatId;
        $this->ci->Venueseat_model->setWhere($where);
        $selectResponse = $this->ci->Venueseat_model->get();
        $data['statusCode'] = STATUS_OK;
        if (count($selectResponse) == 0) {
            $data['status'] = TRUE;
            $data['response']['total'] = 0;
            $data['response']['messages'][] = ERROR_VENUESEAT_NO_RECORD;
            return $data;
        }
        if ($type == "add") {
            if ($selectResponse[0]['status'] == 'Available') {
                $ddate = date('Y-m-d H:i:s');
                $updateVenueSeat[$this->ci->Venueseat_model->status] = 'InProcess';
                $updateVenueSeat[$this->ci->Venueseat_model->eventsignupid] = $eventsignupId;
                $updateVenueSeat[$this->ci->Venueseat_model->eventid] = $eventId;
                $updateVenueSeat[$this->ci->Venueseat_model->bdate] = $ddate;
                $this->ci->Venueseat_model->setInsertUpdateData($updateVenueSeat);
                $where[$this->ci->Venueseat_model->id] = $venueSeatId;
                $this->ci->Venueseat_model->setWhere($where);
                $updatedId = $this->ci->Venueseat_model->update_data();
            } else {
                $data['status'] = TRUE;
                $data['response']['total'] = 0;
                $data['response']['messages'][] = ERROR_VENUESEAT_NOT_AVAILABLE;
                return $data;
            }
        } elseif ($type == "remove") {
            $updateVenueSeat[$this->ci->Venueseat_model->status] = 'Available';
            $updateVenueSeat[$this->ci->Venueseat_model->eventsignupid] = 0;
            $updateVenueSeat[$this->ci->Venueseat_model->eventid] = $eventId;
            $updateVenueSeat[$this->ci->Venueseat_model->bdate] = '0000-00-00 00:00:00';
            $this->ci->Venueseat_model->setInsertUpdateData($updateVenueSeat);
            $where[$this->ci->Venueseat_model->id] = $venueSeatId;
            $this->ci->Venueseat_model->setWhere($where);
            $updatedId = $this->ci->Venueseat_model->update_data();
        } else {
            $data['status'] = TRUE;
            $data['response']['total'] = 0;
            $data['response']['messages'][] = ERROR_VENUESEAT_INVALID_TYPE;
            return $data;
        }
        if ($updatedId) {
            $data['status'] = TRUE;
            $data['response']['total'] = 1;
            $data['response']['messages'][] = [];
            return $data;
        }
        $data['status'] = TRUE;
        $data['response']['total'] = 0;
        $data['response']['messages'][] = ERROR_VENUESEAT_UPDATION;
        return $data;
    }

    public function checkUpdateSeats($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('eventsignupid', 'eventsignupid', 'is_natural_no_zero|required_strict');
        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $eventId = $inputArray['eventid'];
        $eventsignupId = $inputArray['eventsignupid'];
        $where[$this->ci->Venueseat_model->eventid] = $eventId;
        $where[$this->ci->Venueseat_model->eventsignupid] = $eventsignupId;
        $where[$this->ci->Venueseat_model->status] = 'InProcess';
        $this->ci->Venueseat_model->setWhere($where);
        $countResponse = $this->ci->Venueseat_model->getCount();
        //echo $this->ci->db->last_query();exit;
        $output['status'] = TRUE;
        $output['statusCode'] = STATUS_OK;
        $output['response']['total'] = 0;
        $output['response']['messages'][] = [];
        // $output['response']['seatingEnabled'] = FALSE;
        if ($countResponse > 0) {
            $output['response']['total'] = $countResponse;
            //  $output['response']['seatingEnabled'] = TRUE;
        }
        return $output;
    }

    public function getseatNumbers($inputArray){
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventsignupId', 'Registration Numbers', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('eventId', 'Event Id', 'is_natural_no_zero|required_strict');
        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
    	$eventId= $inputArray['eventId'];
        $eventsignupId = $inputArray['eventsignupId'];
        $eventId= $inputArray['eventId'];
        $selectcount['count'] = 'count(1)';
        $this->ci->Venueseat_model->setSelect($selectcount);
        $wherecount[$this->ci->Venueseat_model->eventid] = $eventId;
        $wherecount[$this->ci->Venueseat_model->status] = "'Booked'";
        $wherecount[$this->ci->Venueseat_model->eventsignupid] = $eventsignupId;
        $this->ci->Venueseat_model->setWhereIns($countwhereInArray);
        $this->ci->Venueseat_model->setWhere($wherecount);
        $this->ci->Venueseat_model->setSelect($selectcount);
        $result = $this->ci->Venueseat_model->get(false);
        if ($result[0]['count'] == 0) {
        	$output['status'] = TRUE;
        	$output['statusCode'] = STATUS_OK;
        	$output['response']['total'] = 0;
        	$output['response']['messages'][] = ERROR_NO_RECORDS;
        	return $output;
        }
        $select['eventsignupid'] = $this->ci->Venueseat_model->eventsignupid;
        $select['GridPosition'] = 'group_concat(concat(GridPosition ,"-",Seatno))';
        $where[$this->ci->Venueseat_model->eventid] = $eventId;
        $where[$this->ci->Venueseat_model->status] = "'Booked'";
        $where[$this->ci->Venueseat_model->eventsignupid] = $eventsignupId;
        $this->ci->Venueseat_model->setWhereIns($whereInArray);
        $groupBy = array($this->ci->Venueseat_model->eventsignupid);
        $this->ci->Venueseat_model->setWhere($where);
        $this->ci->Venueseat_model->setSelect($select);
        $orderBy = array($this->ci->Venueseat_model->id . ' desc');
        $this->ci->Venueseat_model->setOrderBy($orderBy);
        $res = $this->ci->Venueseat_model->get(false);
    	$res = commonHelperGetIdArray($res,'eventsignupid');
        if ($res && count($res) == 0) {
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
            $output['response']['total'] = 0;
            $output['response']['messages'][] = ERROR_NO_RECORDS;
            return $output;
        }
        if ($res &&  count($res) > 0) {
        // Concating the seat No and Gridposition 	
        	foreach($res as $keys => $values){
        		$res[$keys]['GridPosition']='';
	        	$Eventsignupseats= explode(',', $values['GridPosition']);
	        	$seatKeys = array_keys($Eventsignupseats);
	        	$last = end($seatKeys);
		        foreach($Eventsignupseats as $key=>$seatValues){
		        	$seat= explode('-', $seatValues);
		        	$seat[0]= preg_replace("/[^A-za-z]/", "", $seat[0]);
		        	
		        	$res[$keys]['GridPosition'] .= $seat[0].$seat[1].",";
		        	if($last == $key){
		        		$res[$keys]['GridPosition'] = substr($res[$keys]['GridPosition'] ,0,-1);
		        	}
		        }
        	}
            $output['status'] = TRUE;
            $output['statusCode'] = STATUS_OK;
        	$output['response']['total'] = count($res);
            $output['response']['messages'][] = '';
            $output['response']['seats'] = $res;
            return $output;
        }
        $output['status'] = FALSE;
        $output['response']['messages'][] = ERROR_INTERNAL_DB_ERROR;
        $output['statusCode'] = STATUS_SERVER_ERROR;
        return $output;
    }
    
    public function getseatNumbersByEventId($inputArray){
    	$this->ci->form_validation->reset_form_rules();
    	$this->ci->form_validation->pass_array($inputArray);
    	//$this->ci->form_validation->set_rules('ticketsArray', 'Ticket Ids', 'is_array|required_strict');
    	$this->ci->form_validation->set_rules('eventId', 'Event Id', 'is_natural_no_zero|required_strict');
    	if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
    		$errorMsg = $this->ci->form_validation->get_errors();
    		$output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
    		return $output;
    	}
    	$eventId= $inputArray['eventId'];
    	$select['ticketId'] = $this->ci->Venueseat_model->ticketid;
    	$select['EventSIgnupId'] = $this->ci->Venueseat_model->eventsignupid;
    	$select['GridPosition'] = "group_concat(concat(GridPosition ,'-',Seatno))";
    	$where[$this->ci->Venueseat_model->eventid] = $eventId;
    	$where[$this->ci->Venueseat_model->status] = "'Booked'";
    	$groupBy = array($this->ci->Venueseat_model->eventsignupid,$this->ci->Venueseat_model->ticketid);
    	$this->ci->Venueseat_model->setWhere($where);
    	$this->ci->Venueseat_model->setSelect($select);
    	$this->ci->Venueseat_model->setGroupBy($groupBy);
    	$orderBy = array($this->ci->Venueseat_model->id . ' desc');
    	$this->ci->Venueseat_model->setOrderBy($orderBy);
    	$res = $this->ci->Venueseat_model->get(false);
    	if ($res && count($res) == 0) {
    		$output['status'] = TRUE;
    		$output['statusCode'] = STATUS_OK;
    		$output['response']['total'] = 0;
    		$output['response']['messages'][] = ERROR_NO_RECORDS;
    		return $output;
    	}
    	if ($res &&  count($res) > 0) {
    		foreach($res as $keys => $values){
    			$res[$keys]['GridPosition']='';
    			$Eventsignupseats= explode(',', $values['GridPosition']);
    			$seatKeys = array_keys($Eventsignupseats);
    			$last = end($seatKeys);
    			foreach($Eventsignupseats as $key=>$seatValues){
    				$seat= explode('-', $seatValues);
	    			$seat[0]= preg_replace("/[^A-za-z]/", "", $seat[0]);
	    			$res[$keys]['GridPosition'] .= $seat[0].$seat[1].",";
	    			if($last == $key){
	    				$res[$keys]['GridPosition'] = substr($res[$keys]['GridPosition'] ,0,-1);
	    			}
    			}
    		}
    		// Concating the seat No and Gridposition
    		foreach($res as $k => $v){
    			$resultArray[$v['EventSIgnupId']][$v['ticketId']] = $v;
    		} 
    		$output['status'] = TRUE;
    		$output['statusCode'] = STATUS_OK;
    		$output['response']['total'] = count($resultArray);
    		$output['response']['messages'][] = '';
    		$output['response']['seats'] = $resultArray;
    		return $output;
    	}
    	$output['status'] = FALSE;
    	$output['response']['messages'][] = ERROR_INTERNAL_DB_ERROR;
    	$output['statusCode'] = STATUS_SERVER_ERROR;
    	return $output;
    }

    public function updateAsBooked($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('eventsignupid', 'eventsignupid', 'is_natural_no_zero|required_strict');
        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $eventId = $inputArray['eventid'];
        $eventsignupId = $inputArray['eventsignupid'];
        $select['id'] = $this->ci->Venueseat_model->id;
        $select['seatno'] = $this->ci->Venueseat_model->seatno;
        $select['gridposition'] = $this->ci->Venueseat_model->gridposition;
        $where[$this->ci->Venueseat_model->eventid] = $eventId;
        $where[$this->ci->Venueseat_model->eventsignupid] = $eventsignupId;
        $where[$this->ci->Venueseat_model->status] = 'InProcess';
        $this->ci->Venueseat_model->setWhere($where);
        $this->ci->Venueseat_model->setSelect($select);
        $response = $this->ci->Venueseat_model->get();
        //echo $this->ci->db->last_query();exit;
        $output['status'] = TRUE;
        $output['statusCode'] = STATUS_OK;
        $output['response']['total'] = 0;
        $output['response']['messages'][] = [];
        // $output['response']['seatingEnabled'] = FALSE;
        $venueSeatIds = array();
        if (count($response) > 0) {
            $indexedResponse = commonHelperGetIdArray($response);
            $venueSeatIds = array_keys($indexedResponse);
        }
        if (count($venueSeatIds) > 0) {
            $updateData[$this->ci->Venueseat_model->status] = 'Booked';
            $whereIns[$this->ci->Venueseat_model->id] = $venueSeatIds;
            $this->ci->Venueseat_model->setWhereIns($whereIns);
            $this->ci->Venueseat_model->setInsertUpdateData($updateData);
            $updateStatus = $this->ci->Venueseat_model->update_data();
        } else {
            $output['response']['messages'][] = ERROR_NO_DATA;
        }
        if ($updateStatus) {
            $output['response']['total'] = count($response);
            $output['response']['seatsData'] = $response;
        } else {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_VENUESEAT_UPDATION;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        return $output;
    }

    public function updateToAvailable($inputArray) {
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('eventsignupid', 'eventsignupid', 'is_natural_no_zero|required_strict');
        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $eventId = $inputArray['eventid'];
        $eventsignupId = $inputArray['eventsignupid'];
        $output['status'] = TRUE;
        $output['statusCode'] = STATUS_OK;
        $output['response']['total'] = 0;
        $output['response']['messages'][] = [];
        // $output['response']['seatingEnabled'] = FALSE;
        $updateData[$this->ci->Venueseat_model->status] = 'Available';
        $updateData[$this->ci->Venueseat_model->eventsignupid] = '0';
        $updateData[$this->ci->Venueseat_model->bdate] = '0000-00-00 00:00:00';
        $whereIns[$this->ci->Venueseat_model->eventid] = $eventId;
        $whereIns[$this->ci->Venueseat_model->eventsignupid] = $eventsignupId;
        $this->ci->Venueseat_model->setWhereIns($whereIns);
        $this->ci->Venueseat_model->setInsertUpdateData($updateData);
        $updateStatus = $this->ci->Venueseat_model->update_data();
        if ($updateStatus) {
            $output['response']['total'] = 1;
        } else {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_VENUESEAT_UPDATION;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        return $output;
    }

    public function getVenue($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('eventid', 'eventid', 'is_natural_no_zero|required_strict');
        //$this->ci->form_validation->set_rules('venueseatid', 'venueseatid', 'is_natural_no_zero|required_strict');
        //$this->ci->form_validation->set_rules('type', 'type', 'required_strict');
        if (!empty($inputArray) && $this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        $eventId = $inputArray['eventid'];
        $select['venueid'] = $this->ci->Venueseat_model->venueid;
        $this->ci->Venueseat_model->setSelect($select);
        $where[$this->ci->Venueseat_model->eventid] = $eventId;
        //$where[$this->ci->Venueseat_model->status] = 1;
        $where[$this->ci->Venueseat_model->deleted] = 0;
        $this->ci->Venueseat_model->setWhere($where);
        $this->ci->Venueseat_model->setRecords(1);
        $response = $this->ci->Venueseat_model->get();
        //echo $this->ci->db->last_query();exit;
        $output['status'] = TRUE;
        $output['statusCode'] = STATUS_OK;
        $output['response']['total'] = 0;
        $output['response']['messages'][] = [];
        $output['response']['venue'] = '';
        if (count($response) == 0) {
            return $output;
        }
        $venueId = $response [0]['venueid'];
        $query = "SELECT Template FROM theatervenue WHERE Id='" . $venueId . "'";
        $response = $this->ci->db->query($query);
        if ($response->num_rows == 0) {
            return $output;
        }
        $output['response']['total'] = $response->num_rows;
        $resultArray = $response->result_array();
        $output['response']['venue'] = $resultArray[0]['Template'];
        return $output;
        //        }
    }
    
    // Function to get all the events those come under seating layouts
    public function getSeatingEvents() {
        
        $select['eventid'] = $this->ci->Venueseat_model->eventid;
        $this->ci->Venueseat_model->setSelect($select);
        $this->ci->Venueseat_model->setGroupBy($this->ci->Venueseat_model->eventid);
        $response = $this->ci->Venueseat_model->get();
        return $response;
    }

}
