<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Helpers\DateHelper;
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
        $this->recordsPerPage = 0;
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
    public function setRecords($recordsCount, $offset = 0)
    {
        $this->recordsPerPage = $recordsCount;
        $this->recordsOffset = $offset;
    }

    //To set the insert array object
    public function setInsertUpdateData($insertUpdateArray)
    {
        $this->insertUpdateArray = $insertUpdateArray;
        
    }
   
    /**
     * To add updated & created date and time values
     * @param type $insertUpdateArray
     */
    public function setInsertDataWithDates($insertUpdateArray)
    {
        $this->insertUpdateArray = $insertUpdateArray;
        $this->insertUpdateArray["created_at"]=DateHelper::todayDateTime();
        $this->insertUpdateArray["updated_at"]=DateHelper::todayDateTime();
        
    }
    /**
     * To add updated & created date and time values
     * @param type $insertUpdateArray
     */
    public function setUpdateDataWithDates($insertUpdateArray)
    {
        $this->insertUpdateArray = $insertUpdateArray;
        $this->insertUpdateArray["updated_at"]=DateHelper::todayDateTime();
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
//            print_r($this->where);exit;
        $records = DB::table($this->dbTable)
                ->where($this->where)
                ->get();
        if (count($records) == 0) {
            return null;
        }

        return $records;
    }

    /**
     * To bring the data from specified table with specified order
     * @return type
     */
    public function getOrderByData($columnName = NULL)
    {
        $records = DB::table($this->dbTable)->where($this->where);
        if ($columnName != NULL) {
            $records = $records->orderBy($columnName, 'DESC');
        }
        $records = $records->get();
        if (count($records) == 0) {
            return null;
        }

        return $records;
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

    /**
     * To insert multiple records at a time
     */
    public function bulkInsert()
    {
        DB::table($this->dbTable)
                ->insert($this->insertUpdateArray);
    }

    //To Update the data into specific table
    public function updateData()
    {
        try {
//            echo "<pre>";
//            print_r($this->where);
//            print_r($this->insertUpdateArray);
//            exit;
            DB::table($this->dbTable)
                    ->where($this->where)
                    ->update($this->insertUpdateArray);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage(), 10024, $ex);
        }
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
        DB::table($this->dbTable)
                ->where($this->where)
                ->delete();
    }

    /**To Enable the transaction
     * 
     */
    public function dbTransactionBegin()
    {
        DB::beginTransaction();
    }

    public function dbTransactionCommit()
    {
        DB::commit();
    }

    public function dbTransactionRollback()
    {
        DB::rollback();
    }

}
