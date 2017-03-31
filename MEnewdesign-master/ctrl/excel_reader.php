<?php
// PHPExcel_IOFactory 
require_once 'includes/phpexcel/PHPExcel/IOFactory.php';
/*
 * Author : Anil Kumar M
 * Date   : 9-10-2014
 *   
 *  To read the excel file type csv,xls, xlsx
 */
class excel_reader {
    public $page="";
    
    public function __construct($page=NULL) {
        $this->page=$page;
    }
    
    public function read_data($input_file_name) {
        $input_file_type = end(explode('.', $input_file_name));//Get the file extention
        $obj_reader = $this->excel_reader_object($input_file_type); 
        

        $filterSubset = new MyReadFilter($this->page);
        //�Tell�the�Reader�that�we�want�to�use�the�Read�Filter�
        $obj_reader->setReadFilter($filterSubset); 
        //��Load�$inputFileName�to�a�PHPExcel�Object� 
        $obj_data = $obj_reader->load($input_file_name);
        $worksheet = $obj_data->getSheet(0);//Get's only first sheet from the excel
        $excel_data=array();
        $i=0;
        foreach ($worksheet->getRowIterator() as $row) {
//            echo '   <br/> Row number: ' . $row->getRowIndex() . "<br/>";
            $cellIterator = $row->getCellIterator();
            foreach ($cellIterator as $cell) {
//                echo '        - Cell: ' . $cell->getCoordinate() . ' - ' . $cell->getCalculatedValue() . "\r\n";
                $excel_data[$i][]=$cell->getCalculatedValue();
            }
            $i++;
        }
        return $excel_data;
//        print_r($excel_data);
    }
    //��Create�a�new�Reader�of�the�type�defined�in�$inputFileType��
    public function excel_reader_object($file_type){
        if($file_type=="xls")
        {
            $objReader = new PHPExcel_Reader_Excel5();
        }else if($file_type=="csv"){
            $objReader = new PHPExcel_Reader_CSV();
        }else{
            $objReader = new PHPExcel_Reader_Excel2007();
            
        }
        return $objReader;
    }
    
    //To Read user registration fileds data
    public function read_user_data($input_file_name) {
        $input_file_type = end(explode('.', $input_file_name));//Get the file extention
        $obj_reader = $this->excel_reader_object($input_file_type); 
        
        $obj_data = $obj_reader->load($input_file_name);
        $worksheet = $obj_data->getSheet(0);//Get's only first sheet from the excel
        $excel_data=array();
        $i=0;
        foreach ($worksheet->getRowIterator() as $row) {

            $cellIterator = $row->getCellIterator();
            foreach ($cellIterator as $cell) {

                $excel_data[$i][]=$cell->getCalculatedValue();
            }
            $i++;
        }
        return $excel_data;
 
    }


}

/*
 * Define�a�Read�Filter�class�implementing�PHPExcel_Reader_IReadFilter�� 
 * 
 */
class MyReadFilter implements PHPExcel_Reader_IReadFilter {
    public $page="";
    
    public function __construct($page=NULL) {
        $this->page=$page;
    }
    public function readCell($column, $row, $worksheetName = '') {
        //� columns�C� - ReferenceNo -event signup id
        // column F - Txn Type - Capture
        //We are reading from 2n row on wards
        //C,F for EBS and M,I for Paytm and A,G for Mobikwik
        $read_colums_list = array("C", "F","G", "M", "I");
        //$read_colums_list = array("B","C","D","E","H","A");
        if(strcmp($this->page, 'Mobikwik')==0){
            $read_colums_list = array("B","C","H");
        }
        if($row > 1){
            if(in_array($column, $read_colums_list)){
                return true;
            }
            
        }

        return false;
    }
}



