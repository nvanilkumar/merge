<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Exception;

/**
 * Common database related functions to perform select, insert, update
 * 
 */
class CommonModel extends Model
{

    public $select;
    public $dbTable;
    public $where, $startingIndex, $recordsPerPage, $recordsOffset,
            $whereNotInArray, $whereInArray, $orderBy;
    public $insertUpdateArray;

    public function __construct()
    {

        $this->select[] = array("id");
        $this->where = array();

        $this->startingIndex = 0;
        $this->recordsPerPage = 10;
        $this->recordsOffset = 0;
        $this->orderBy = "";



        $this->insertArray = array();
        $this->whereInArray = array();
        $this->whereNotInArray = array();
        $this->whereInsArray = array();
    }

    public function resetVariable()
    {
        $this->__construct();
    }

    //Set the table Name
    public function setTableName($tablename)
    {
        $this->dbTable = $tablename;
    }

    //Set the select object array
    public function setSelect($selectArray)
    {
        $this->select = $selectArray;
    }

    //To set the where condition array
    public function setWhere($whereArray)
    {
        $this->where = $whereArray;
    }

    //No of records per page related settings
    public function setRecords($recordsCount)
    {
        $this->recordsPerPage = $recordsCount;
    }

    public function setStartingIndex($startingIndex)
    {
        $this->startingIndex = $startingIndex;
    }

    //To set the insert array object
    public function setInsertUpdateData($insertUpdateArray)
    {
        $this->insertUpdateArray = $insertUpdateArray;
    }

    //To set the order by values
    //Gets the order by column names and type of order in array format
    //ex: $this->db->order_by('title desc, name asc'); 
    public function setOrderBy($orderByArray)
    {
        $orderText = "";
        if (count($orderByArray) > 0) {
            $orderText = implode(",", $orderByArray);
        }

        $this->orderBy = $orderText;
    }

    //To feach the data from specified table name
    public function getData()
    {
        try {
//            print_r($this->where);exit;
            $records = DB::table($this->dbTable)
                    ->where($this->where)
                               ->get();
            if(count($records) == 0){
                return null;
            }
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage(), 10023, $ex);
        }
        return $records;
    }
    
    /**
     * To return the data with pagination count
     * @return type
     * @throws Exception
     */
    public function getPaginationData()
    {
        try {
            $data=[];
            $records = DB::table($this->dbTable)
                    ->where($this->where);
            $count=$records->count();
            if ($this->startingIndex > 0) {
                $records->offset($this->startingIndex)
                        ;
            }
            $records = $records->limit($this->recordsPerPage)
                               ->get();
              
            
            if (count($records) == 0) {
                return null;
            }
            $data['count']=$count;
            $data['records']=$records;
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage(), 10023, $ex);
        }
        return $data;
    }

    //To insert only single record
    //To Inser the data in to specified table
    //@returns the inserted values. On fail's returns the false.
    public function insertData()
    {
        try {
//            print_r($this->insertUpdateArray);exit;
            $id = DB::table($this->dbTable)->insertGetId($this->insertUpdateArray);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage(), 10024, $ex);
        }
        return $id;
    }

    public function rawInsertData()
    {
        try {
            //sample syntax
            //'insert into users (id, name) values (?, ?)', [1, 'Dayle']

            $insertQyery = "insert into " . $this->dbTable . " (";
            $valuesText = $columNamesText = "";
            $valuesArray = [];

            foreach ($this->insertUpdateArray as $coulumnName => $value) {
                $columNamesText .= $coulumnName . ", ";
                $valuesText .= "?, ";
                $valuesArray[] = $value;
            }

            if (count($this->insertUpdateArray) > 0) {
                $columNamesText = substr($columNamesText, 0, -2);
                $valuesText = substr($valuesText, 0, -2);
            }

            $insertQyery .= $columNamesText . ") values (" . $valuesText . " )";

            $id = DB::insert($insertQyery, $valuesArray);
//            $id = DB::table($this->dbTable)->insertGetId($this->insertUpdateArray);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage(), 10024, $ex);
        }
        return $id;
    }

    //To Update the data into specific table
    public function updateData()
    {
        try {
//            print_r($this->dbTable);exit;
            DB::table($this->dbTable)
                    ->where($this->where)
                    ->update($this->insertUpdateArray);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage(), 10024, $ex);
        }
    }

    //To insert only multiple records
    //@returns the count of effected rows.
    public function insertMultiple_data()
    {
        
    }

    //To update only multiple records
    //@returns the count of effected rows.
    public function updateMultiple_data()
    {
        
    }

    //where in
    public function setWhereIn($whereInArray)
    {
        $this->whereInArray = $whereInArray;
    }

    //where in
    public function setWhereIns($whereInsArray)
    {
        $this->whereInsArray = $whereInsArray;
    }

    //where in
    public function setWhereNotIn($whereNotInArray)
    {
        $this->whereNotInArray = $whereNotInArray;
    }

    /**
     * To remove the records on specified condition
     * @return type
     */
    public function deleteData()
    {
        
    }

}
