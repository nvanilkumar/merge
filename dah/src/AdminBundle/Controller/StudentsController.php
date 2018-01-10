<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use AdminBundle\Entity\DahUsers;
class StudentsController extends Controller {

    public static $template = "AdminBundle:templates:default.html.twig";
    public $data = array();

    public function __construct() {
        $this->data['extend_view'] = self::$template;
    }

   

    /**
     * @Route("/secure/students/manage/{page}", name="_admin_students" , defaults={"page"=1} )
     * @Template("AdminBundle:Students:manage.html.twig")
     */
    public function studentsAction(Request $request, $page) {
       
        $limit = 10;
        $key = '';
        $common = $this->get('common_handler');
        $error = array();
        $teachers = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $where = " where role = 'ROLE_STUDENT' ";
        $key = trim($request->query->get('keyword'));

        if ($request->query->get('reset') != '') {
            return $this->redirect($this->generateUrl('_admin_students'));
        }
        if ($key != '') {
            
            if(strpos($key,' ')){
             $n=explode(' ',$key);
             $where .= " and ( fname like '%" . $n[0] . "%' or lname like '%".$n[1]."')";
            }
            else{
              
            $where .= " and ( fname like '%" . $key . "%' or lname like '%".$key."')";  
            }

        } else {
            // echo $key;
            // exit;
        }
        $statement = $connection->prepare("  select du.*,ti.depid,dd.department from dah_users du 
                                             left join dah_teachers_info ti on du.uid = ti.uid
                                             left join dah_departments dd on ti.depid = dd.deptid   " . $where . "order by added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
          $statement = $connection->prepare("   select * from dah_users du  " . $where . "order by added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        
        
        $statement->execute();
        $students = $statement->fetchAll();
        $statement = $connection->prepare("   select du.*,ti.depid,dd.department from dah_users du 
                                             left join dah_teachers_info ti on du.uid = ti.uid
                                             left join dah_departments dd on ti.depid = dd.deptid " . $where . " ");
        $statement->execute();
        $totalstudents = $statement->fetchAll();
        $totalpages = ceil(count($totalstudents) / $limit);
        $pageinate = $common->paginate('_admin_teachers', array('keyword' => $key), $totalpages, $page, $limit);
        $this->data['paginate'] = $pageinate;
        $this->data['page'] = $page;
        $this->data['key'] = $key;
        $this->data['title'] = 'Administrator login';
        $this->data['totalstudents'] = count($totalstudents);

        if (count($totalstudents) < $limit) {
            $limit = count($totalstudents);
        }

        if (count($totalstudents) > 0) {
            if ($page > 1) {
                $l = ((($page - 1) * $limit) + 1) + $limit;
                $this->data['range'] = ((($page - 1) * $limit) + 1) . " - " . $l;
            } else {
                $this->data['range'] = ((($page - 1) * $limit) + 1) . " - " . $limit;
            }
        }



        $this->data['students'] = $students;
        $this->data['title'] = 'Administrator login';
        return $this->data;

    }
    
    /**
     * @Route("/secure/students/view/{uid}", name="_admin_view_student", defaults={"uid"=0})
     * @Template("AdminBundle:Students:view.html.twig")
     */
    public function viewAction(Request $request,$uid) {
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $students = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        
        $statement = $connection->prepare("   select * from dah_users du where du.uid = $uid ");
        $statement->execute();
        $students = $statement->fetch();
        $this->data['student'] = $students;
        return $this->data;
    }
    
   /**
     * @Route("/secure/students/add_new", name="_admin_add_new_student")
     * @Template("AdminBundle:Students:addNew.html.twig")
     */
    public function addNewAction(Request $request) {
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $teachers = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        if ($request->getMethod() == 'POST') {
            $fname = trim($request->request->get('fname'));
            $lname = trim($request->request->get('lname'));
            $email = trim($request->request->get('email'));
            $password = trim($request->request->get('password'));
            $cpassword = trim($request->request->get('cpassword'));
            $department = trim($request->request->get('department'));
            $bio = trim($request->request->get('bio'));
            if ($fname == '') {
                $error['fname'] = "This field cannot be blank";
            }
            if (strlen($fname) > 100) {
                $error['fname'] = "This field cannot exceed more than 100 characters";
            }
            if ($lname == '') {
                $error['lname'] = "This field cannot be blank";
            }
            if (strlen($lname) > 100) {
                $error['lname'] = "This field cannot exceed more than 100 characters";
            }
            if ($email == '') {
                $error['email'] = "This field cannot exceed more than 100 characters";
            }
            if ($password != '') {
                if (strlen($password) > 25) {
                    $error['password'] = "Password must not exceed more than 25 characters";
                }
                if (strlen($password) < 3) {
                    $error['password'] = "Password must contain atleast 3 characters";
                }
                if ($cpassword != $password) {
                    $error['cpassword'] = "Password mismatch";
                }
            }
            $allowed = array('gif', 'png', 'jpg');
            if (!empty($_FILES) && isset($_FILES['manualUpload']) && $_FILES['manualUpload']['size']>0) {
                $filename = $_FILES['manualUpload']['name'];
                $ext = pathinfo($filename);
                if (!in_array(strtolower($ext['extension']), $allowed)) {
                   $error['manualUpload'] = "Please upload jpg/png/gif file.";
                }
            }
            if (empty($error)) {
                $statement = $connection->prepare("  select * from dah_users where email = '$email'  ");
                $statement->execute();
                $users = $statement->fetchAll();
                if (!empty($users)) {
                    $error['email'] = "This email already exists.";
                } else {
                    $newname = '';
                    if (!empty($_FILES) && isset($_FILES['manualUpload']) && $_FILES['manualUpload']['size']>0) {

                        $tempFile = $_FILES['manualUpload']['tmp_name'];


                        //$newname= $_FILES['imageupload']['name'];
                        if (isset($tempFile) && !empty($tempFile) && $tempFile != '') {
                            $fileParts = pathinfo($_FILES['manualUpload']['name']);
                            $newname = md5(uniqid('avt_')) . '.' . strtolower($fileParts['extension']);
                            $targetPath = WEB_DIRECTORY . '/' . 'uploads' . '/';
                            $targetPath = './' . 'uploads' . '/';
                            $targetFile = $targetPath . $newname;
                            //$finfo = new \finfo(FILEINFO_MIME_TYPE);
                            //  $mimeType = $finfo->buffer(file_get_contents($tempFile)); 

                            if (!is_dir($targetPath)) {
                                mkdir($targetPath, 0777);
                            }
                            if (file_exists($targetFile)) {
                                echo $targetFile;
                                unlink($targetFile);
                            }
                            move_uploaded_file($tempFile, $targetFile);
                        }
                    }
                    //add user and send email if needed send password too
                    $sendpassword = false;
                    $user = new DahUsers();
                    $salt = md5(uniqid());
                    $verify = md5(uniqid());
                    $encoderFactory = $this->get('security.encoder_factory');
                    $encoder = $encoderFactory->getEncoder($user);
                    if ($password == '') {
                        $password = uniqid('dah_');
                        $sendpassword = true;
                    }
                    $newpassword = $encoder->encodePassword($password, $salt);
                    $user->setFname($fname);
                    $user->setLname($lname);
                    $user->setEmail($email);
                    $user->setSalt($salt);
                    $user->setPassword($newpassword);
                    $user->setRole('ROLE_STUDENT');
                    $user->setStatus('inactive');
                    $user->setAvatar($newname);
                    $user->setIsActive(0);
                    $user->setVerify($verify);
                    $user->setAddedOn(time());
                    $em->persist($user);
                    $em->flush();
                   $id = $user->getUid();
                    $common->logActivity('added student <a href="' . $this->generateUrl('_admin_edit_student', array('uid' => $id)) . '">' . trim($request->request->get('fname')) .' ' .trim($request->request->get('lname')). '</a>');
                
                    $maildata = array(
                        "name" => $user->getFname(),
                        "email" => $user->getEmail(),
                        "code" => $verify
                    );
                    $message = $this->renderView('AdminBundle:Emails:userEmailVerify.html.twig', $maildata);
                    $emailhand->sendEmail($email, 'Account Verification Mail', $message);
                    $maildata = array(
                        "name" => $user->getFname(),
                        "email" => $user->getEmail(),
                        "password" => $password
                    );
                    $message = $this->renderView('AdminBundle:Emails:generated.html.twig', $maildata);
                    $emailhand->sendEmail($email, 'Account Login Credentials', $message);
                    $this->get('session')->getFlashBag()->add('success', ' New Student added successfully.');
                    return $this->redirect($this->generateUrl('_admin_students'));
                }
            }
        }
        $this->data['error'] = $error;
        $this->data['mode'] = 'add';
        return $this->data;
       
    }
    
    /**
     * @Route("/secure/students/edit/{uid}", name="_admin_edit_student", defaults={"uid"=0})
     * @Template("AdminBundle:Students:addNew.html.twig")
     */
    public function editAction(Request $request,$uid) {
       
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $students = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $statement = $connection->prepare("   select du.*,dd.deptid,dd.department,ti.bio from dah_users du 
                                             left join dah_teachers_info ti on du.uid = ti.uid
                                             left join dah_departments dd on ti.depid = dd.deptid where du.uid = $uid ");
     
        $statement = $connection->prepare("   select * from dah_users du where du.uid = $uid ");
        $statement->execute();
        $students = $statement->fetch();
        if ($request->getMethod() == 'POST') {
            $fname = trim($request->request->get('fname'));
            $lname = trim($request->request->get('lname'));
            $email = trim($request->request->get('email'));
            $password = trim($request->request->get('password'));
            $cpassword = trim($request->request->get('cpassword'));
            $department = trim($request->request->get('department'));
            $bio = trim($request->request->get('bio'));
            if ($fname == '') {
                $error['fname'] = "This field cannot be blank";
            }
            if (strlen($fname) > 100) {
                $error['fname'] = "This field cannot exceed more than 100 characters";
            }
            if ($lname == '') {
                $error['lname'] = "This field cannot be blank";
            }
            if (strlen($lname) > 100) {
                $error['lname'] = "This field cannot exceed more than 100 characters";
            }
            if ($email == '') {
                $error['email'] = "This field cannot exceed more than 100 characters";
            }
            if ($password != '') {
                if (strlen($password) > 25) {
                    $error['password'] = "Password must not exceed more than 25 characters";
                }
                if (strlen($password) < 3) {
                    $error['password'] = "Password must contain atleast 3 characters";
                }
                if ($cpassword != $password) {
                    $error['cpassword'] = "Password mismatch";
                }
            }
            $allowed = array('gif', 'png', 'jpg');
            if (!empty($_FILES) && isset($_FILES['manualUpload']) && $_FILES['manualUpload']['size']>0) {
                $filename = $_FILES['manualUpload']['name'];
                $ext = pathinfo($filename);
                if (!in_array(strtolower($ext['extension']), $allowed)) {
                   $error['manualUpload'] = "Please upload jpg/png/gif file.";
                }
            }
            if (empty($error)) {
                $statement = $connection->prepare("  select * from dah_users where email = '$email' and email!='" . $students['email'] . "'  ");
                $statement->execute();
                $users = $statement->fetchAll();
                if (!empty($users)) {
                    $error['email'] = "This email already exists.";
                } else {
                    $newname = $students['avatar'];
                    if (!empty($_FILES) && isset($_FILES['manualUpload']) && $_FILES['manualUpload']['size']>0) {

                        $tempFile = $_FILES['manualUpload']['tmp_name'];


                        //$newname= $_FILES['imageupload']['name'];
                        if (isset($tempFile) && !empty($tempFile) && $tempFile != '') {
                            $fileParts = pathinfo($_FILES['manualUpload']['name']);
                            $newname = md5(uniqid('avt_')) . '.' . strtolower($fileParts['extension']);
                            $targetPath = WEB_DIRECTORY . '/' . 'uploads' . '/';
                            $targetPath = './' . 'uploads' . '/';
                            $targetFile = $targetPath . $newname;
                            //$finfo = new \finfo(FILEINFO_MIME_TYPE);
                            //  $mimeType = $finfo->buffer(file_get_contents($tempFile)); 

                            if (!is_dir($targetPath)) {
                                mkdir($targetPath, 0777);
                            }
                            if (file_exists($targetFile)) {
                                echo $targetFile;
                                unlink($targetFile);
                            }
                            move_uploaded_file($tempFile, $targetFile);
                        }
                    }
                    //add user and send email if needed send password too
                    $sendpassword = false;
                    //$user = new DahUsers();
                    $repository = $this->getDoctrine()->getRepository('AdminBundle:DahUsers');
                    $user = $repository->findOneBy(
                            array('uid' => $students['uid'])
                    );
                    if ($user) {
                        $salt = md5(uniqid());
                        $verify = md5(uniqid());
                        $encoderFactory = $this->get('security.encoder_factory');
                        $encoder = $encoderFactory->getEncoder($user);
                        if ($password != '') {
                            $newpassword = $encoder->encodePassword($password, $salt);
                            $user->setPassword($newpassword);
                            $user->setSalt($salt);
                        }
                        $user->setFname($fname);
                        $user->setLname($lname);
                        $user->setEmail($email);
                        $user->setAvatar($newname);
                        $user->setUpdatedOn(time());
                        $em->persist($user);
                        $em->flush();
                        //add deprtment if selected
                        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahDepartments');
                        $dpeobj = $repository->findOneBy(
                                array('deptid' => $department)
                        );
                        $common->logActivity('updated student <a href="' . $this->generateUrl('_admin_edit_student', array('uid' => $user->getUid())) . '">' . trim($request->request->get('fname')) . '</a>');
                        $this->get('session')->getFlashBag()->add('success', 'Student updated successfully.');
                        return $this->redirect($this->generateUrl('_admin_students'));
                    }
                }
            }
        }
        $this->data['error'] = $error;
        $this->data['user'] = $students;
        $this->data['mode'] = 'edit';
        return $this->data;
    }
    

}
