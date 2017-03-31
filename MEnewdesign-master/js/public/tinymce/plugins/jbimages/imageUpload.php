 <?php
                        require_once $_SERVER['DOCUMENT_ROOT'].'/ctrl/MT/cGlobali.php';
                        $Global = new cGlobali();
                        require_once $_SERVER['DOCUMENT_ROOT'].'/ctrl/includes/common_functions.php';
                        $commonFunctions = new functions();
                        $field='userfile';
                        $file["name"]=$_FILES[$field]['name'];
                        $file["tmp_name"]=$_FILES[$field]['tmp_name'];                        
                        $file['unlink']=TRUE;
                        $imgfolder=EVENT_DESCRIPTION_IMAGE;
                        $type='eventdescription';
                        $output = $commonFunctions->fileUpload($Global, $file, $extensions='', $imgfolder, $type, $image="", $resize=array());		
                        if(isset($output['descriptionImage'])){
                            $result['file_name']= $output['descriptionImage'];
                            $result['result']		= "file_uploaded";
                            $result['resultcode']	= 'ok';
                        }else{
                            $result['result']		= 'Error in uploading';
                            $result['resultcode']	= 'failed';
                        }	
                        include_once('ajax_upload_result.php');
?>	