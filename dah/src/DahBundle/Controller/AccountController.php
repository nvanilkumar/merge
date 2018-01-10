<?php

namespace DahBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use AdminBundle\Entity\DahEnquires;
use AdminBundle\Entity\DahStudentInfo;
use AdminBundle\Entity\DahTeachersInfo;

class AccountController extends Controller {

    public static $template = "DahBundle:templates:default.html.twig";
    public $data = array();

    public function __construct() {
        $this->data['extend_view'] = self::$template;
    }

    /**
     * @Route("/account/dashboard", name="_dashboard")
     * @Template("DahBundle:Account:dashboard.html.twig")
     */
    public function dashboardAction(Request $request) {
        $session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $teachers = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $todaysTimestamp = strtotime(date('d-m-Y'));
        $ref = $session->get('referrer');
        $expuri = explode("/", $ref);
        $lasturi = array_pop($expuri);
        if ($ref != '' && $lasturi != '') {
            $session->remove('referrer');
            return $this->redirect($ref);
        }
        $session->remove('referrer');
        $user = $this->getUser();
        $limit = 5;
        $page = 1;
        $where = " where dt.status='active'  ";
        $statement = $connection->prepare("  select * from dah_trainings dt join dah_departments dp on dt.deptid = dp.deptid join dah_users du on du.uid = dt.uid  " . $where . " order by dt.added_on desc limit " . (($page - 1) * $limit) . ",$limit  ");
        $statement->execute();
        $trainings = $statement->fetchAll();
        $this->data['trainings'] = $trainings;
        $statement = $connection->prepare("  select * from dah_workshops where from_date > $todaysTimestamp  order by added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $pages = $statement->fetchAll();
        $this->data['workshops'] = $pages;
        $todaysTimestamp = strtotime(date('d-m-Y'));
        $where = " where 1=1  and ( future_date is null or future_date <=  $todaysTimestamp ) ";
        $statement = $connection->prepare("  select * from dah_news " . $where . " order by added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $news = $statement->fetchAll();
        $this->data['news'] = $news;
        $where = " where dt.status='active' and featured=1  ";
        $statement = $connection->prepare("  select * from dah_trainings dt join dah_departments dp on dt.deptid = dp.deptid join dah_users du on du.uid = dt.uid  " . $where . " order by dt.added_on desc limit " . (($page - 1) * $limit) . ",$limit  ");
        $statement->execute();
        $poptrainings = $statement->fetchAll();
        $this->data['poptrainings'] = $poptrainings;
        $this->data['user'] = $user;
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

    /**
     * @Route("/account/calendar", name="_account_calendar")
     * @Template("DahBundle:Account:calendar.html.twig")
     */
    public function accountcalendarAction(Request $request) {
        $session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $teachers = array();
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $todaysTimestamp = strtotime(date('d-m-Y'));
        $this->data['user'] = $user;
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

    /**
     * @Route("/account/settings", name="_settings")
     * @Template("DahBundle:Account:settings.html.twig")
     */
    public function setingsAction(Request $request) {
        $session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $teachers = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $session = $request->getSession();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('_home'));
        }
        $user = $this->getUser();
        if ($user->getRole() == 'ROLE_TEACHER') {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTeachersInfo');
        } else {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahStudentInfo');
        }

        if ($request->getMethod() == 'POST') {
            if ($user->getRole() == 'ROLE_TEACHER') {
                $fname = trim($request->request->get('fname'));
                $lname = trim($request->request->get('lname'));
                $email = trim($request->request->get('email'));
                $password = trim($request->request->get('password'));
                $cpassword = trim($request->request->get('cpassword'));
                $department = trim($request->request->get('department'));
                $bio = trim($request->request->get('bio'));
                $phone = trim($request->request->get('phone'));
                $exp = trim($request->request->get('exp'));
                $qualification = trim($request->request->get('qualification'));
                $school = trim($request->request->get('school'));
                $location = trim($request->request->get('location'));
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
                if (!empty($_FILES) && isset($_FILES['manualUpload']) && $_FILES['manualUpload']['size'] > 0) {

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
                    $statement = $connection->prepare("  select * from dah_users where email = '$email' and email!='" . $user->getEmail() . "'  ");
                    $statement->execute();
                    $users = $statement->fetchAll();
                    if (!empty($users)) {
                        $error['email'] = "This email already exists.";
                    } else {
                        $newname = $user->getAvatar();
                        if (!empty($_FILES) && isset($_FILES['manualUpload']) && $_FILES['manualUpload']['size'] > 0) {
                            $tempFile = $_FILES['manualUpload']['tmp_name'];
                            if (isset($tempFile) && !empty($tempFile) && $tempFile != '') {
                                $fileParts = pathinfo($_FILES['manualUpload']['name']);
                                $newname = md5(uniqid('avt_')) . '.' . strtolower($fileParts['extension']);
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
                            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTeachersInfo');
                            $userinfo = $repository->findOneBy(
                                    array('uid' => $user->getUid())
                            );
                            if (!$userinfo) {
                                $userinfo = new DahTeachersInfo();
                            }
                            $userinfo->setUid($user);
                            if ($dpeobj) {
                                $userinfo->setDepid($dpeobj);
                            }
                            $userinfo->setBio($bio);
                            $userinfo->setPhone($phone);
                            $userinfo->setExp($exp);
                            $userinfo->setQualification($qualification);
                            $userinfo->setSchool($school);
                            $userinfo->setLocation($location);
                            $userinfo->setUpdatedOn(time());
                            $em->persist($userinfo);
                            $em->flush();
                            $this->get('session')->getFlashBag()->add('success', 'Profile updated successfully.');
                            return $this->redirect($this->generateUrl('_settings'));
                        }
                    }
                }
            } else {
                $fname = trim($request->request->get('fname'));
                $lname = trim($request->request->get('lname'));
                $email = trim($request->request->get('email'));
                $password = trim($request->request->get('password'));
                $cpassword = trim($request->request->get('cpassword'));
                $location = trim($request->request->get('location'));
                $school = trim($request->request->get('school'));
                $bio = trim($request->request->get('bio'));
                $phone = trim($request->request->get('phone'));
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
                if (!empty($_FILES) && isset($_FILES['manualUpload']) && $_FILES['manualUpload']['size'] > 0) {
                    $filename = $_FILES['manualUpload']['name'];
                    $ext = pathinfo($filename);
                    if (!in_array(strtolower($ext['extension']), $allowed)) {
                        $error['manualUpload'] = "Please upload jpg/png/gif file.";
                    }
                }
                if (empty($error)) {
                    $statement = $connection->prepare("  select * from dah_users where email = '$email' and email!='" . $user->getEmail() . "'  ");
                    $statement->execute();
                    $users = $statement->fetchAll();
                    if (!empty($users)) {
                        $error['email'] = "This email already exists.";
                    } else {
                        $newname = $user->getAvatar();
                        if (!empty($_FILES) && isset($_FILES['manualUpload']) && $_FILES['manualUpload']['size'] > 0) {
                            $tempFile = $_FILES['manualUpload']['tmp_name'];
                            if (isset($tempFile) && !empty($tempFile) && $tempFile != '') {
                                $fileParts = pathinfo($_FILES['manualUpload']['name']);
                                $newname = md5(uniqid('avt_')) . '.' . strtolower($fileParts['extension']);
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
                            }
                        }
                        //add user and send email if needed send password too
                        $sendpassword = false;
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
                            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahStudentInfo');
                            $userinfo = $repository->findOneBy(
                                    array('uid' => $user->getUid())
                            );
                            if (!$userinfo) {
                                $userinfo = new DahStudentInfo();
                            }
                            $userinfo->setUid($user->getUid());
                            $userinfo->setBio($bio);
                            $userinfo->setPhone($phone);
                            $userinfo->setLocation($location);
                            $userinfo->setSchool($school);
                            $userinfo->setUpdatedOn(time());
                            $em->persist($userinfo);
                            $em->flush();
                            $this->get('session')->getFlashBag()->add('success', 'Profile updated successfully.');
                            return $this->redirect($this->generateUrl('_settings'));
                        }
                    }
                }
            }
        }
        $userinfo = $repository->findOneByUid($user->getUid());
        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $this->data['error'] = $error;
        $this->data['departments'] = $totaldeps;
        $this->data['user'] = $user;
        $this->data['userinfo'] = $userinfo;
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

    /**
     * @Route("/account/changepassword", name="_changepass")
     * @Template("DahBundle:Account:changepass.html.twig")
     */
    public function changepassAction(Request $request) {
        $this->data['title'] = 'Administrator login';
        $session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $teachers = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $session = $request->getSession();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('_home'));
        }
        $user = $this->getUser();
        if ($user->getRole() == 'ROLE_TEACHER') {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTeachersInfo');
        } else {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahStudentInfo');
        }
        if ($request->getMethod() == 'POST') {
            $oldpassword = trim($request->request->get('oldpassword'));
            $password = trim($request->request->get('password'));
            $cpassword = trim($request->request->get('cpassword'));
            $encoderFactory = $this->get('security.encoder_factory');
            $encoder = $encoderFactory->getEncoder($user);
            $passwordold = $encoder->encodePassword($oldpassword, $user->getSalt());
            if ($user->getPassword() != $passwordold) {
                $error['oldpassword'] = "You have entered incorrect password";
            }
            if ($oldpassword == '') {
                $error['oldpassword'] = "Please enter old password";
            } elseif (strlen($oldpassword) < 3 || strlen($oldpassword) > 25) {
                $error['oldpassword'] = "Password must not exceed 25 characters and must have atleast 3 characters.";
            }
            if ($password == '') {
                $error['password'] = "Please enter new password";
            }
            if ($password != '') {
                if (strlen($password) > 25) {
                    $error['password'] = "Password must not exceed more than 25 characters";
                }
                if (strlen($password) < 3) {
                    $error['password'] = "Password must contain atleast 3 characters";
                }
                if ($password == $oldpassword) {
                    $error['password'] = "Old password and new password should not be same";
                }
                if ($cpassword != $password) {
                    $error['cpassword'] = "Password mismatch";
                }
            }
            if (empty($error)) {
                if ($password != '') {
                    $salt = md5(uniqid());
                    $newpassword = $encoder->encodePassword($password, $salt);
                    $user->setPassword($newpassword);
                    $user->setSalt($salt);
                    $user->setUpdatedOn(time());
                    $em->persist($user);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'Password changed successfully');
                }
            }
        }
        $this->data['user'] = $user;
        $this->data['error'] = $error;
        return $this->data;
    }

    /**
     * @Route("/account/enrolled_workshops/{page}", name="_enrolled_workshops", defaults={"page"=1} )
     * @Template("DahBundle:Workshops:enworkshops.html.twig")
     */
    public function enrolledWorkshopsAction(Request $request, $page) {
        $limit = 10;
        $session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $teachers = array();
        $trainings = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $user = $this->getUser();
        if ($user->getRole() == 'ROLE_TEACHER') {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTeachersInfo');
        } else {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahStudentInfo');
        }
        $userinfo = $repository->findOneByUid($user->getUid());
        $where = " where te.uid = " . $user->getUid() . " ";
        $key = trim($request->query->get('keyword'));
        if ($request->query->get('reset') != '') {
            return $this->redirect($this->generateUrl('_enrolled_trainings'));
        }
        if ($key != '') {

            $where .= " and ( workshop_title like '%" . $key . "%'   ) ";
        }
        $statement = $connection->prepare("  select * from dah_workshop_enrollment te join dah_workshops dt on te.wid = dt.wid  join dah_departments dp on dt.deptid = dp.deptid  " . $where . " order by dt.added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $trainings = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_workshop_enrollment te join dah_workshops dt on te.wid = dt.wid  join dah_departments dp on dt.deptid = dp.deptid " . $where . " ");
        $statement->execute();
        $totalcontentpages = $statement->fetchAll();
        $totalpages = ceil(count($totalcontentpages) / $limit);
        $paginate = $common->paginate('_enrolled_workshops', array('keyword' => $key), $totalpages, $page, 5);
        $this->data['trainings'] = $trainings;
        $this->data['key'] = $key;
        $this->data['paginate'] = $paginate;
        $this->data['page'] = $page;
        $this->data['title'] = 'Administrator login';
        $this->data['totalpages'] = count($totalcontentpages);
        if (count($totalcontentpages) < $limit) {
            $limit = count($totalcontentpages);
        }
        if (count($totalcontentpages) > 0) {
            if ($page > 1) {
                $l = ((($page - 1) * $limit) + 1) + $limit;
                $this->data['range'] = ((($page - 1) * $limit) + 1) . " - " . $l;
            } else {
                $this->data['range'] = ((($page - 1) * $limit) + 1) . " - " . $limit;
            }
        }
        $this->data['user'] = $user;
        $this->data['userinfo'] = $userinfo;
        return $this->data;
    }
    /**
     * @Route("/account/settings/download/{filename}", name="_teacher_download_cv")
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
    
    /**
     * @Route("/generic/download/{filename}", name="_generic_download_act")
     * @return BinaryFileResponse
     */
    public function downloaderAction($filename) {
        
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
