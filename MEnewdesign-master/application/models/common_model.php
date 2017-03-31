<?php

/**
 * Comon  databse Activie record methods are defined in this class
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2015, MeraEvents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     11-06-2015
 * @Last Modified 02-07-2015
 * @Last Modified By Sridevi
 */
class Common_model extends CI_Model {

    var $select;
    var $dbTable;
    var $where, $like, $notLike, $startingIndex, $recordsPerPage, $whereInArray, $whereNotInArray, $whereInsArray, $findInSetArray, $groupConcat, $recordsOffset, $updateBatchColumn;
    var $formatType; //Accept 0-for Array format, 1-object format
    var $orderBy;
    var $groupByArray;
    var $id;
    var $insertUpdateArray; //contains the field name and values in array format
    var $orWhere;
    var $or_like;

     // taking multiple or conditions as custom string

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('sql'));
        //initializing the variables
        $this->select[] = $this->id;
        $this->where = array();
        $this->like = array();
        $this->or_like = array();
        $this->notLike = array();
        $this->startingIndex = 0;
        $this->recordsPerPage = 0;
        $this->recordsOffset = 0;
        $this->orderBy = "";
        $this->groupByArray = array();
        $this->formatType = 0; //Array format default
        $this->orWhere = "";
        $this->insertArray = array();
        $this->whereInArray = array();
        $this->whereNotInArray = array();
        $this->whereInsArray = array();
        $this->findInSetArray = array();
        $this->groupConcat = array();
        $this->updateBatchColumn = "id";
    }
	
    public function resetVariable() {
		$this->__construct();
	}

    //Set the table Name
    public function setTableName($tablename) {
        $this->dbTable = $tablename;
    }

    //Set the select object array
    public function setSelect($selectArray) {
        $this->select = $selectArray;
    }

    //To set the where condition array
    public function setWhere($whereArray) {
        $this->where = $whereArray;
    }

    public function setGroupConcat($groupConcat, $separator = ',') {
        $this->where = $groupConcat;
    }

    //To set the where (or) type condition from given array
    public function setOrWhere($whereArray, $condition = ' or ', $operater = ' = ', $findInSetArray = array()) {
        if (count($whereArray > 0)) {
            $orWhere = "(";
            foreach ($whereArray as $wKey => $wVal) {
                if (is_array($wVal)) {
                    $orWhere.= $wKey . " IN (" . implode(',', $wVal) . ") " . $condition . " ";
                } else {
                    if ($operater == 'like') {
                        if ($wKey == 'id') {
                            $orWhere.= $wKey . " " . '= ' . " '" . $wVal . "' " . $condition . " ";
                        } else {
                			$orWhere.= $wKey . " " . $operater . " '%" . $wVal . "%' " . $condition . " ";
                		}
                    } else {
                   	 $orWhere.= $wKey . " " . $operater . " '" . $wVal . "' " . $condition . " ";
                	}
                }
            }
            if (count($findInSetArray) == 0) {
                $orWhere = rtrim($orWhere, " $condition ");
            } else {
                $findInWhere = "";
                foreach ($findInSetArray as $key => $value) {
                    $findInWhere .= "FIND_IN_SET('$key',  $value)";
                }
                $orWhere .= rtrim($findInWhere, " $condition ");
            }
            $orWhere.= ")";
            //$this->orWhere .= $orWhere. $condition;
            if (strlen($orWhere) > 2) {
                $orWhere = rtrim($orWhere, " and ");
                $this->db->where($orWhere);
            }
        }
    }

    //find in set
    public function setFindInSet($findInSetArray) {
        $this->findInSetArray = $findInSetArray;
    }

    //To set the like object
    public function setLike($likeArray) {
        $this->like = $likeArray;
    }

    //To set the like object
    public function setConditionLike($likeArray) {
    	$this->or_like = $likeArray;
    }
    
    //To set the like object
    public function setNotLike($notLikeArray) {
        $this->notLike = $notLikeArray;
    }

    //To set the starting index of the records
    public function setStartingIdex($index) {
        $this->startingIndex = $index;
    }

    //No of records per page related settings
    public function setRecords($recordsCount, $offset = 0) {
        $this->recordsPerPage = $recordsCount;
        $this->recordsOffset = $offset;
    }

    //db result shoud return either array format or object format (default array)
    public function setResultSetType($formatType) {
        $this->formatType = $formatType;
    }

    //To set the insert array object
    public function setInsertUpdateData($insertUpdateArray) {
        $this->insertUpdateArray = $insertUpdateArray;
    }

    //To set the order by values
    //Gets the order by column names and type of order in array format
    //ex: $this->db->order_by('title desc, name asc'); 
    public function setOrderBy($orderByArray) {
        $orderText = "";
        if (count($orderByArray) > 0) {
            $orderText = implode(",", $orderByArray);
        }

        $this->orderBy = $orderText;
    }

    //To set the group by values
    public function setGroupBy($groupByField) {
        $this->groupByArray = $groupByField;
    }

    //To feach the data from specified table name
    public function get($protect = true) {
        $response = false;
        $selectString = sqlHelperGetSelect($this->select);
        if (!$protect) {
            $this->db->select($selectString, false);
        } else {
        	$this->db->select($selectString);
        }

        if (count($this->where) > 0) {
            if (!$protect) {
                $this->db->where($this->where, NULL, FALSE);
            } else {
                $this->db->where($this->where);
            }
        }
        if (count($this->like) > 0) {
            $this->db->like($this->like);
        }
        if (count($this->or_like) > 0) {
        	$likeOrarray = $this->or_like;
        		$this->db->or_like($likeOrarray);
        }
        if (count($this->notLike) > 0) {
            foreach ($this->notLike as $key => $value) {
                $this->db->not_like($key, $value);
            }
        }
        //add findinset
        if (count($this->findInSetArray) > 0) {
            foreach ($this->findInSetArray as $key => $value) {
                $this->db->where("FIND_IN_SET('$key',  $value)");
            }
        }
        //Applying limit for the result set
        if ($this->recordsPerPage > 0) {
            $this->db->limit($this->recordsPerPage, $this->recordsOffset);
        }

        $this->db->from($this->dbTable);

        if (count($this->groupByArray) > 0) {
            $this->db->group_by($this->groupByArray);
        }

        if (strlen($this->orderBy) > 0) {
            $this->db->order_by($this->orderBy);
        }

        //set where_in values
        if (count($this->whereInArray) > 1) {
            $this->db->where_in($this->whereInArray[0], $this->whereInArray[1]);
        }
        if (count($this->whereInsArray) > 0) {
            foreach ($this->whereInsArray as $key => $value) {
                $this->db->where_in($key, $value);
            }
        }
        //set where_not_in values
        if (count($this->whereNotInArray) > 0) {
            foreach ($this->whereNotInArray as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
        }
        //Result set format
        if ($this->formatType == 0) {
            $output = $this->db->get()->result_array();
        } else {
            $output = $this->db->get()->result();
        }

        //echo $this->db->last_query()."<br>========================================<br>";

        if (count($output) >= 0) {
            $response = $output;
        }
        $this->whereInsArray = array();
        //echo $this->db->last_query();
        return $response;
    }

    //To insert only single record
    //To Inser the data in to specified table
    //@returns the inserted values. On fail's returns the false.
    public function insert_data() {
        if (!isset($this->insertUpdateArray['createdby'])) {
            $this->insertUpdateArray['createdby'] = getSessionUserId();
            $this->insertUpdateArray['modifiedby'] = getSessionUserId();
        }
       
        $this->db->insert($this->dbTable, $this->insertUpdateArray);
        return $this->db->insert_id();
    }

    //To Update the data into specific table
    public function update_data($setData=array()) {
        //set where_in values
        if (count($this->where) > 0) {
            $this->db->where($this->where);
        }
        //set where_in values
        if (count($this->whereInArray) > 1) {
            $this->db->where_in($this->whereInArray[0], $this->whereInArray[1]);
        }
        if (count($this->whereInsArray) > 0) {
            foreach ($this->whereInsArray as $key => $value) {
                $this->db->where_in($key, $value);
            }
            $this->whereInsArray=array();
        }
        $this->insertUpdateArray['modifiedby'] = getSessionUserId();
        if(count($setData)>0){
            foreach ($setData as $key => $value) {
                $this->db->set($key ,$value, FALSE);
            }
        }
        $status = $this->db->update($this->dbTable, $this->insertUpdateArray);
        if (!$status) {
            $this->db->_error_message();
            $this->db->_error_number();
        }
       // echo $this->db->last_query();
        return $status;
    }

    //To start the transaction type queries
    public function startTransaction() {
        $this->db->trans_begin();
    }

    //To stop the transaction type queries
    public function stopTransaction() {
        $this->db->trans_complete();
    }

    //To complete the transaction type queries & 
    // checks the  status of the trasaction query
    public function transactionStatusCheck() {
        return $this->db->trans_status();
    }

    //To Rool back the latest transaction
    public function rollBackLastTransaction() {
        $this->db->trans_rollback();
    }

    //To commit  the latest transaction
    public function commitLastTransaction() {
        $this->db->trans_commit();
    }

    //To insert only multiple records
    //@returns the count of effected rows.
    public function insertMultiple_data() {
        $insertArray = $this->insertUpdateArray;
        foreach ($insertArray as $key => $value) {
            $insertArray[$key]['modifiedby'] = getSessionUserId();
            $insertArray[$key]['createdby'] = getSessionUserId();
        }
        $this->db->insert_batch($this->dbTable, $insertArray);
        return $this->db->affected_rows();
    }

    //To update only multiple records
    //@returns the count of effected rows.
    public function updateMultiple_data() {
        if (count($this->where) > 0) {
            $this->db->where($this->where);
        }
        $insertArray=$this->insertUpdateArray;
        foreach($insertArray as $key=>$value){
            $insertArray[$key]['modifiedby']=getSessionUserId();
        }
        $this->db->update_batch($this->dbTable, $insertArray, $this->updateBatchColumn);
        return $this->db->affected_rows();
    }

    public function setUpdateBatchColumn($column) {
        $this->updateBatchColumn = $column;
    }

    //where in
    public function setWhereIn($whereInArray) {
        $this->whereInArray = $whereInArray;
    }

    //where in
    public function setWhereIns($whereInsArray) {
        $this->whereInsArray = $whereInsArray;
    }

    //where in
    public function setWhereNotIn($whereNotInArray) {
        $this->whereNotInArray = $whereNotInArray;
    }

    /**
     * To remove the reocrds on specified condition
     * @return type
     */
    public function deleteData() {
        //set where_in values
        if (count($this->whereInArray) > 1) {
            $this->db->where_in($this->whereInArray[0], $this->whereInArray[1]);
        }
        if (count($this->where) > 0) {
            $this->db->where($this->where);
        }
        $status = $this->db->delete($this->dbTable);
        return $status;
    }

    public function getCount() {
        $response = false;

        if (count($this->where) > 0) {
            $this->db->where($this->where);
        }


        if (count($this->like) > 0) {
            $this->db->like($this->like);
        }
        if (count($this->notLike) > 0) {
            foreach ($this->notLike as $key => $value) {
                $this->db->not_like($key, $value);
            }
        }
        if (count($this->or_like) > 0) {
        	$likeOrarray = $this->or_like;
        	$this->db->or_like($likeOrarray);
        }
        //add findinset
        if (count($this->findInSetArray) > 0) {
            foreach ($this->findInSetArray as $key => $value) {
                $this->db->where("FIND_IN_SET('$key',  $value)!=", 0);
            }
        }
        //Applying limit for the result set
        if ($this->recordsPerPage > 0) {
            $this->db->limit($this->recordsPerPage, $this->recordsOffset);
        }

        $this->db->from($this->dbTable);

        if (count($this->groupByArray) > 0) {
            $this->db->group_by($this->groupByArray);
        }

        //set where_in values
        if (count($this->whereInArray) > 1) {
            $this->db->where_in($this->whereInArray[0], $this->whereInArray[1]);
        }
        if (count($this->whereInsArray) > 0) {
            foreach ($this->whereInsArray as $key => $value) {
                $this->db->where_in($key, $value);
            }
        }
        //set where_not_in values
        if (count($this->whereNotInArray) > 0) {
            foreach ($this->whereNotInArray as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
        }
        $output = $this->db->count_all_results();

     //  $this->db->last_query();
        if ($output >= 0) {
            $response = $output;
        }

        return $response;
    }
	
	//To Update the data into specific table
    public function update_set_data() {
        //set where_in values
        if (count($this->where) > 0) {
            $this->db->where($this->where);
        }
        //set where_in values
        if (count($this->whereInArray) > 1) {
            $this->db->where_in($this->whereInArray[0], $this->whereInArray[1]);
        }
        if (count($this->whereInsArray) > 0) {
            foreach ($this->whereInsArray as $key => $value) {
                $this->db->where_in($key, $value);
            }
            $this->whereInsArray=array();
        }
        //$this->insertUpdateArray['modifiedby'] = getSessionUserId();
		
		$userid = getSessionUserId();
		if(!is_null($userid) && $userid > 0)
		{
            $this->insertUpdateArray['modifiedby'] = $userid;
		}
		
		
		$insertUpdateArray = $this->insertUpdateArray;
		foreach($insertUpdateArray as $key => $value) {
			$this->db->set($key, $value,false);
		}
        $status = $this->db->update($this->dbTable);
        if (!$status) {
            $this->db->_error_message();
            $this->db->_error_number();
        }
       // echo $this->db->last_query();
        return $status;
    }
	
	public function get_regex_data($whereArray,$operater = ' REGEXP ') {
        if (count($whereArray > 0)) {
            foreach ($whereArray as $wKey => $wVal) {
                    $where.= $wKey . " " . $operater . " '" . $wVal . "' " ;
            }
            $selectString = sqlHelperGetSelect($this->select);
           
            $this->db->select($selectString);
            $this->db->where($where);
            $this->db->from($this->dbTable);
            if ($this->formatType == 0) { 
                $output = $this->db->get()->result_array();
            } else {
                $output = $this->db->get()->result();
            }
            if (count($output) >= 0) {
                $response = $output;
            }
            return $response;
        }
    }

}
