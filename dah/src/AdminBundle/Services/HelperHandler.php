<?php

namespace AdminBundle\Services;

use AdminBundle\Entity\DahReminderEmail;
use AdminBundle\Entity\DahTrainingMaterial;
use AdminBundle\Entity\DahWorkshopMaterial;
use Symfony\Component\HttpFoundation\Request;

class HelperHandler {

    public function __construct($request, $em, $doctrine) {
        $this->doctrine = $doctrine;
        $this->em = $em;
        $this->request = $request->getCurrentRequest();
    }

    public function bulkInsertReminderEmails($list, $message, $subject) {
        

        if (count($list) > 0) {
            foreach ($list as $email) {

                if (strlen($email['email']) > 0) {
                    $reminderEmail = new DahReminderEmail();
                    $reminderEmail->setEmail($email['email']);
                    $reminderEmail->setMessage($message);
                    $reminderEmail->setSubject($subject);
                    $reminderEmail->setUpdatedOn();
                    $this->em->persist($reminderEmail);
                    $this->em->flush();
                }
            }
        }

      
    }
    
    /**
     * To Insert and update the training material
     */
     public function insertTrainingMaterial($ftitle,$tid,$preTrainingCount) {
        $filesCount=count($_FILES['materialupload']['name']);
        $materialuploadpath="";
        if(count($filesCount) > 0){
             for($i=0;$i< $filesCount;$i++){
                 
                 if(strlen($_FILES['materialupload']['name'][$i]) > 0){ 
                      $dahTrainingMaterial =new  DahTrainingMaterial();
                 $dahTrainingMaterial->setFtitle($ftitle[$i]);
                 $materialuploadpath=$this->uploadfile("materialupload",$i);
                 $dahTrainingMaterial->setMaterialupload($materialuploadpath);
                 $dahTrainingMaterial->setStatus('active');
                 $dahTrainingMaterial->setDtid($tid);
                 $this->em->persist($dahTrainingMaterial);
                 $this->em->flush();
                     
                 }
                
                 
             }
        }
        $this->updateTrainingMeterial($preTrainingCount,$tid);
        

    }
    
    public function updateTrainingMeterial($preTrainingCount,$tid){
        if($preTrainingCount > 0){
            
         
           $titles=$this->request->get('uftitle');
           $uploadid=$this->request->get('uploadid');
           $ustatus=$this->request->get('ustatus');
//           echo "<pre>";
//           print_r($uploadid);exit;
//           for ($j = 0; $j < $preTrainingCount; $j++) {
               foreach($uploadid as $index => $value){
               
                $dahTrainingMaterial = new DahTrainingMaterial();
                $repository = $this->doctrine->getRepository('AdminBundle:DahTrainingMaterial');
                $trainingMeterial = $repository->findOneBy(
                        array('id' => $value)
                );
                if($ustatus[$value] == 1){
                    $trainingMeterial->setStatus('inactive'); 
                     
                }else{
                    $trainingMeterial->setFtitle($titles[$value]); 
//                      echo "<pre>";
//            print_r($_FILES['umaterialupload']);exit;
                    if(strlen($_FILES['umaterialupload']['name'][$value]) > 0){
                        $materialuploadpath=$this->uploadfile("umaterialupload",$value);
                        $trainingMeterial->setMaterialupload($materialuploadpath);
                    } 
                    
                }
                $this->em->persist($trainingMeterial);
                $this->em->flush();
            }
        }//End If
         
        
    }
    
    /**
     * To Insert and update the training material
     */
     public function insertWorkshopMaterial($ftitle,$wid,$preWorkshopCount) {
        $filesCount=count($_FILES['materialupload']['name']);
        $materialuploadpath="";
        if(count($filesCount) > 0){
             for($i=0;$i< $filesCount;$i++){
                 
                 if(strlen($_FILES['materialupload']['name'][$i]) > 0){ 
                      $dahWorkshopMaterial =new  DahWorkshopMaterial();
                 $dahWorkshopMaterial->setFtitle($ftitle[$i]);
                 $materialuploadpath=$this->uploadfile("materialupload",$i);
                 $dahWorkshopMaterial->setMaterialupload($materialuploadpath);
                 $dahWorkshopMaterial->setStatus('active');
                 $dahWorkshopMaterial->setDwid($wid);
                 $this->em->persist($dahWorkshopMaterial);
                 $this->em->flush();
                     
                 }
                
                 
             }
        }
        $this->updateWorkshopMeterial($preWorkshopCount,$wid);
        

    }
    
    public function updateWorkshopMeterial($preTrainingCount,$wid){
        if($preTrainingCount > 0){
            
         
           $titles=$this->request->get('uftitle');
           $uploadid=$this->request->get('uploadid');
           $ustatus=$this->request->get('ustatus');
//           echo "<pre>";
//           print_r($uploadid);exit;
//           for ($j = 0; $j < $preTrainingCount; $j++) {
               foreach($uploadid as $index => $value){
               
                $dahWorkshopMaterial = new DahWorkshopMaterial();
                $repository = $this->doctrine->getRepository('AdminBundle:DahWorkshopMaterial');
                $workshopMeterial = $repository->findOneBy(
                        array('id' => $value)
                );
                if($ustatus[$value] == 1){
                    $workshopMeterial->setStatus('inactive'); 
                     
                }else{
                    $workshopMeterial->setFtitle($titles[$value]); 
//                      echo "<pre>";
//            print_r($_FILES['umaterialupload']);exit;
                    if(strlen($_FILES['umaterialupload']['name'][$value]) > 0){
                        $materialuploadpath=$this->uploadfile("umaterialupload",$value);
                        $workshopMeterial->setMaterialupload($materialuploadpath);
                    } 
                    
                }
                $this->em->persist($workshopMeterial);
                $this->em->flush();
            }
        }//End If
         
        
    }
    
    public function uploadfile($fileObjectName,$fileIndex) {
            $tempFile = $_FILES[$fileObjectName]['tmp_name'][$fileIndex];
            $fileParts = pathinfo($_FILES[$fileObjectName]['name'][$fileIndex]);
//            echo "<pre>";
//            print_r($_FILES['umaterialupload']);exit;
            $newname = md5(uniqid('dtm_')) . '.' . strtolower($fileParts['extension']);
            $targetPath = WEB_DIRECTORY . '/' . 'uploads' . '/';
            $targetPath = './' . 'uploads' . '/';
            $targetFile = $targetPath . $newname;

            if (!is_dir($targetPath)) {
                mkdir($targetPath, 0777);
            }
            if (file_exists($targetFile)) {
                echo $targetFile;
                unlink($targetFile);
            }
            move_uploaded_file($tempFile, $targetFile);
            return $newname;
        
    }

}
