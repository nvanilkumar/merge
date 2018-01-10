<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use AdminBundle\Entity\DahUsers;
use AdminBundle\Entity\DahTeachersInfo;

class TeachersController extends Controller {

    public static $template = "AdminBundle:templates:default.html.twig";
    public $data = array();

    public function __construct() {
        $this->data['extend_view'] = self::$template;
    }

    /**
     * @Route("/secure/teachers/manage/{page}", name="_admin_teachers" , defaults={"page"=1} )
     * @Template("AdminBundle:Teachers:manage.html.twig")
     */
    public function teachersAction(Request $request, $page) {
        $limit = 10;
        $key = '';
        $common = $this->get('common_handler');
        $error = array();
        $teachers = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $where = " where role = 'ROLE_TEACHER' ";
        $key = trim($request->query->get('keyword'));
        $department = trim($request->query->get('department'));
        if ($request->query->get('reset') != '') {
            return $this->redirect($this->generateUrl('_admin_teachers'));
        }
        if ($key != '') {

            $where .= " and ( du.fname like '%" . $key . "%' or du.lname like '%" . $key . "%' or du.email like '%" . $key . "%'  )";
        } else {
            // echo $key;
            // exit;
        }
        if ($department > 0) {

            $where .= " or dd.deptid = " . $department . " ";
        }
        $statement = $connection->prepare("  select du.*,ti.depid,dd.department from dah_users du 
                                             left join dah_teachers_info ti on du.uid = ti.uid
                                             left join dah_departments dd on ti.depid = dd.deptid   " . $where . "order by added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $teachers = $statement->fetchAll();
        $statement = $connection->prepare("   select du.*,ti.depid,dd.department from dah_users du 
                                             left join dah_teachers_info ti on du.uid = ti.uid
                                             left join dah_departments dd on ti.depid = dd.deptid " . $where . " ");
        $statement->execute();
        $totalteachers = $statement->fetchAll();
        $totalpages = ceil(count($totalteachers) / $limit);
        $pageinate = $common->paginate('_admin_teachers', array('keyword' => $key), $totalpages, $page, $limit);
        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $this->data['departments'] = $totaldeps;
        $this->data['paginate'] = $pageinate;
        $this->data['page'] = $page;
        $this->data['key'] = $key;
        $this->data['title'] = 'Administrator login';
        $this->data['totalpages'] = count($totalteachers);

        if (count($totalteachers) < $limit) {
            $limit = count($totalteachers);
        }

        if (count($totalteachers) > 0) {
            if ($page > 1) {
                $l = ((($page - 1) * $limit) + 1) + $limit;
                $this->data['range'] = ((($page - 1) * $limit) + 1) . " - " . $l;
            } else {
                $this->data['range'] = ((($page - 1) * $limit) + 1) . " - " . $limit;
            }
        }



        $this->data['teachers'] = $teachers;
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

    /**
     * @Route("/secure/teachers/view/{uid}", name="_admin_view_teacher", defaults={"uid"=0})
     * @Template("AdminBundle:Teachers:view.html.twig")
     */
    public function viewAction(Request $request, $uid) {
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $teachers = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $statement = $connection->prepare("select du.*,dd.deptid,dd.department,ti.bio from dah_users du 
                                             left join dah_teachers_info ti on du.uid = ti.uid
                                             left join dah_departments dd on ti.depid = dd.deptid where du.uid = $uid ");        
        $statement->execute();
        $teacher = $statement->fetch();
        $this->data['teacher'] = $teacher;      
        return $this->data;
    }

    /**
     * @Route("/secure/teachers/add_new", name="_admin_add_new_teachers")
     * @Template("AdminBundle:Teachers:addNew.html.twig")
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
            $resume = $request->request->get('cvUpload');
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
            $allowedcv = array('docx', 'pdf', 'doc');
                if (!empty($_FILES) && isset($_FILES['cvUpload']) && $_FILES['cvUpload']['size'] > 0) {
                    
                    $filename = $_FILES['cvUpload']['name'];
                    $ext = pathinfo($filename);
                    if (!in_array(strtolower($ext['extension']), $allowedcv)) {
                        $error['cvUpload'] = "Please upload docx/pdf/doc.";
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
                        $allowed = array('gif', 'png', 'jpg');
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
                    $newnamecv = $resume;
                        if (!empty($_FILES) && isset($_FILES['cvUpload']) && $_FILES['cvUpload']['size'] > 0) {
                            $tempFiles = $_FILES['cvUpload']['tmp_name'];
                            if (isset($tempFiles) && !empty($tempFiles) && $tempFiles != '') {
                                $fileParts = pathinfo($_FILES['cvUpload']['name']);
                                $newnamecv = md5(uniqid('cv_')) . '.' . strtolower($fileParts['extension']);
                                $targetPaths = WEB_DIRECTORY . '/' . 'uploads' . '/';
                                $targetPaths = './' . 'uploads' . '/';
                                $targetFiles = $targetPaths . $newnamecv;
                                if (!is_dir($targetPaths)) {
                                    mkdir($targetPaths, 0777);
                                }
                                if (file_exists($targetFiles)) {
                                    echo $targetFiles;
                                    unlink($targetFiles);
                                }
                                move_uploaded_file($tempFiles, $targetFiles);
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
                    $user->setRole('ROLE_TEACHER');
                    $user->setStatus('inactive');
                    $user->setAvatar($newname);
                    $user->setCv($newnamecv);
                    $user->setIsActive(0);
                    $user->setVerify($verify);
                    $user->setAddedOn(time());
                    $em->persist($user);
                    $em->flush();
                    //add deprtment if selected
                    $repository = $this->getDoctrine()->getRepository('AdminBundle:DahDepartments');
                    $dpeobj = $repository->findOneBy(
                            array('deptid' => $department)
                    );
                    if ($dpeobj || $bio != '') {
                        $userinfo = new DahTeachersInfo();
                        $userinfo->setUid($user);
                        if ($dpeobj) {
                            $userinfo->setDepid($dpeobj);
                        }
                        $userinfo->setBio($bio);
                        $userinfo->setUpdatedOn(time());
                        $em->persist($userinfo);
                        $em->flush();
                    }
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
                    $common->logActivity('added new teacher <a href="' . $this->generateUrl('_admin_edit_teachers', array('uid' => $user->getUid())) . '">' . trim($request->request->get('fname')) . '</a>');
                    $this->get('session')->getFlashBag()->add('success', ' New Teacher added successfully.');
                    return $this->redirect($this->generateUrl('_admin_teachers'));
                }
            }
        }
        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $this->data['error'] = $error;
        $this->data['departments'] = $totaldeps;
        $this->data['mode'] = 'add';
        return $this->data;
    }

    /**
     * @Route("/secure/teachers/edit/{uid}", name="_admin_edit_teachers", defaults={"uid"=0})
     * @Template("AdminBundle:Teachers:addNew.html.twig")
     */
    public function editAction(Request $request, $uid) {      
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $teachers = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $statement = $connection->prepare("   select du.*,dd.deptid,dd.department,ti.bio from dah_users du 
                                             left join dah_teachers_info ti on du.uid = ti.uid
                                             left join dah_departments dd on ti.depid = dd.deptid where du.uid = $uid ");
        $statement->execute();
        $teachers = $statement->fetch();
        if ($request->getMethod() == 'POST') {
            $fname = trim($request->request->get('fname'));
            $lname = trim($request->request->get('lname'));
            $email = trim($request->request->get('email'));
            $password = trim($request->request->get('password'));
            $cpassword = trim($request->request->get('cpassword'));
            $department = trim($request->request->get('department'));
            $bio = trim($request->request->get('bio'));
            $resume = $request->request->get('cvUpload');
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
            if (!empty($_FILES) && isset($_FILES['manualUpload']) && $_FILES['manualUpload']['size']>0 ) {
                
                $filename = $_FILES['manualUpload']['name'];
                $ext = pathinfo($filename);
                if (!in_array(strtolower($ext['extension']), $allowed)) {
                   $error['manualUpload'] = "Please upload jpg/png/gif file.";                  
                }
            }
            $allowedcv = array('docx', 'pdf', 'doc');
                if (!empty($_FILES) && isset($_FILES['cvUpload']) && $_FILES['cvUpload']['size'] > 0) {
                    
                    $filename = $_FILES['cvUpload']['name'];
                    $ext = pathinfo($filename);
                    if (!in_array(strtolower($ext['extension']), $allowedcv)) {
                        $error['cvUpload'] = "Please upload docx/pdf/doc.";
                    }
                 }
            if (empty($error)) {
                $statement = $connection->prepare("  select * from dah_users where email = '$email' and email!='" . $teachers['email'] . "'  ");
                $statement->execute();
                $users = $statement->fetchAll();
                if (!empty($users)) {
                    $error['email'] = "This email already exists.";
                } else {                  
                    $newname = $teachers['avatar'];
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
                        $newnamecv = $resume;
                        if (!empty($_FILES) && isset($_FILES['cvUpload']) && $_FILES['cvUpload']['size'] > 0) {
                            $tempFile = $_FILES['cvUpload']['tmp_name'];
                            if (isset($tempFile) && !empty($tempFile) && $tempFile != '') {
                                $fileParts = pathinfo($_FILES['cvUpload']['name']);
                                $newnamecv = md5(uniqid('cvs_')) . '.' . strtolower($fileParts['extension']);
                                $targetPath = WEB_DIRECTORY . '/' . 'uploads' . '/';
                                $targetPath = './' . 'uploads' . '/';
                                $targetFile = $targetPath . $newnamecv;
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
                            array('uid' => $teachers['uid'])
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
                        $user->setCv($newnamecv);
                        $user->setUpdatedOn(time());                       
                        $em->persist($user);
                        $em->flush();
                        //add deprtment if selected
                        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahDepartments');
                        $dpeobj = $repository->findOneBy(
                                array('deptid' => $department)
                        );
                        if ($dpeobj || $bio != '') {
                            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTeachersInfo');
                            $userinfo = $repository->findOneBy(
                                    array('uid' => $teachers['uid'])
                            );
                            if (!$userinfo) {
                                $userinfo = new DahTeachersInfo();
                            }
                            $userinfo->setUid($user);
                            if ($dpeobj) {
                                $userinfo->setDepid($dpeobj);
                            }
                            $userinfo->setBio($bio);
                            $userinfo->setUpdatedOn(time());
                            $em->persist($userinfo);
                            $em->flush();
                        }

                        $common->logActivity('updated teacher <a href="' . $this->generateUrl('_admin_edit_teachers', array('uid' => $user->getUid())) . '">' . trim($request->request->get('fname')) . '</a>');
                        $this->get('session')->getFlashBag()->add('success', 'Teacher updated successfully.');
                        return $this->redirect($this->generateUrl('_admin_teachers'));
                    }
                }
            }
        }
        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $this->data['error'] = $error;
        $this->data['user'] = $teachers;
        $this->data['departments'] = $totaldeps;
        $this->data['mode'] = 'edit';
        return $this->data;
    }
    
    /**
    * @Route("/teacher/download/{filename}",name="_admin_teacher_download" )
    * @return BinaryFileResponse
    */
    public function downloadAction($filename) {
        
        $request = $this->get('request');
        $path = $this->get('kernel')->getRootDir() . "/../web/uploads/";
        $content = file_get_contents($path . $filename);

        $response = new Response();

        //set headers
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename);

        $response->setContent($content);
        return $response;
    }

}
