<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use AdminBundle\Entity\DahWorkshops;
use AdminBundle\Entity\DahWrokshopVideos;

class WorkshopController extends Controller {

    public static $template = "AdminBundle:templates:default.html.twig";
    public $data = array();

    public function __construct() {
        $this->data['extend_view'] = self::$template;
    }

    /**
     * @Route("/secure/workshop/manage/{page}", name="_admin_workshop" , defaults={"page"=1} )
     * @Template("AdminBundle:Workshop:manage.html.twig")
     */
    public function manageAction(Request $request, $page) {
        $this->data['title'] = 'Administrator login';
        $common = $this->get('common_handler');
        $limit = 10;
        $key = '';
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $where = ' where 1=1 ';
        $key = trim($request->query->get('keyword'));
        if ($request->query->get('reset') != '') {
            return $this->redirect($this->generateUrl('_admin_workshop'));
        }
        if ($key != '') {

            $where .= " and ( workshop_title like '%" . $key . "%' or workshop_subtitle like '%" . $key . "%'   ) ";
        }
        $statement = $connection->prepare("  select dw.*,count(de.enid) as enrolled from dah_workshops dw left join dah_workshop_enrollment de on dw.wid = de.wid " . $where . " group by dw.wid order by added_on desc limit " . (($page - 1) * $limit) . ",$limit  ");
        $statement->execute();
        $pages = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_workshops " . $where . " ");
        $statement->execute();
        $totalcontentpages = $statement->fetchAll();
        $totalpages = ceil(count($totalcontentpages) / $limit);
        $paginate = $common->paginate('_admin_workshop', array('keyword' => $key), $totalpages, $page, 5);
        $this->data['workshops'] = $pages;
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



        return $this->data;
    }

    /**
     * @Route("/secure/workshop/add_new", name="_admin_add_new_workshop")
     * @Template("AdminBundle:Workshop:addNew.html.twig")
     */
    public function addNewAction(Request $request) {
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $enteredDate = 0;
        $enteredDateTo = 0;
        $todaysTimestamp = strtotime(date('d-m-Y'));
        
        if ($request->getMethod() == 'POST') {
            $workshopTitle = trim($request->request->get('workshopTitle'));
            $workshopSubtitle = trim($request->request->get('workshopSubtitle'));
            $department = trim($request->request->get('department'));
            $workshopContent = trim($request->request->get('workshopContent'));
            $fromDate = trim($request->request->get('fromDate'));
            $toDate = trim($request->request->get('toDate'));
            $workshopVenue = trim($request->request->get('workshopVenue'));
            $workshopSchedule = trim($request->request->get('workshopSchedule'));
            $speakersInfo = trim($request->request->get('speakersInfo'));
            $public = trim($request->request->get('public'));
            $workshopMetaTitle = trim($request->request->get('workshopMetaTitle'));
            $workshopMetaKeywords = trim($request->request->get('workshopMetaKeywords'));
            $workshopMetaDescription = trim($request->request->get('workshopMetaDescription'));
            if ($workshopTitle == '') {
                $error['workshopTitle'] = "This field cannot be blank";
            }
            if ($workshopVenue == '') {
                $error['workshopVenue'] = "This field cannot be blank";
            }
            if ($public == '') {
                $error['public'] = "Please choose one option";
            }
            if ($fromDate != '') {

                $enteredDate = strtotime($fromDate);

                if ($enteredDate < $todaysTimestamp) {
                    $error['fromDate'] = "Please enter a future date";
                }
                if ($toDate != '') {
                    $enteredDateTo = strtotime($toDate);
                    if ($enteredDateTo < $enteredDate) {
                        $error['toDate'] = "Please enter a valid to date";
                    }
                }
            }
            if ($fromDate == '' && $toDate != '') {
                $error['toDate'] = "Please choose a From date before choosing To date";
            }

            if (empty($error)) {

                $wokshop = new DahWorkshops();
                $wokshop->setWorkshopTitle($workshopTitle);
                $wokshop->setWorkshopSubtitle($workshopSubtitle);
                $wokshop->setWorkshopContent($workshopContent);
                $wokshop->setWorkshopVenue($workshopVenue);
                $wokshop->setWorkshopSchedule($workshopSchedule);
                $wokshop->setSpeakersInfo($speakersInfo);
                $wokshop->setPublic($public);
                $wokshop->setWorkshopMetaTitle($workshopMetaTitle);
                $wokshop->setWorkshopMetaKeyword($workshopMetaKeywords);
                $wokshop->setWorkshopMetaDescription($workshopMetaDescription);
                if ($enteredDate > 0) {
                    $wokshop->setFromDate($enteredDate);
                }
                if ($enteredDateTo > 0) {
                    $wokshop->setToDate($enteredDateTo);
                }
                $repository = $this->getDoctrine()->getRepository('AdminBundle:DahDepartments');
                $dpeobj = $repository->findOneBy(
                        array('deptid' => $department)
                );
                if ($dpeobj) {
                    $wokshop->setDeptid($dpeobj);
                }
                $wokshop->setAddedOn(time());
                $em->persist($wokshop);
                $em->flush();

                $common->logActivity('added new workshop <a href="' . $this->generateUrl('_admin_edit_workshop', array('wid' => $wokshop->getWid())) . '">' . $workshopTitle . '</a>');
                $this->get('session')->getFlashBag()->add('success', ' New Workshop added successfully.');
                return $this->redirect($this->generateUrl('_admin_workshop'));
            }
        }
        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $this->data['error'] = $error;
        $this->data['departments'] = $totaldeps;
        $this->data['mode'] = "add";
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

    /**
     * @Route("/secure/workshop/edit/{wid}", name="_admin_edit_workshop",defaults={"wid"=0})
     * @Template("AdminBundle:Workshop:addNew.html.twig")
     */
    public function editAction(Request $request, $wid) {
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahWorkshops');
        $workshop = $repository->findOneBy(
                array('wid' => $wid)
        );
        if (!$workshop) {
            return $this->redirect($this->generateUrl('_admin_workshop'));
        }
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $enteredDate = 0;
        $enteredDateTo = 0;
        $todaysTimestamp = strtotime(date('d-m-Y'));
        if ($request->getMethod() == 'POST') {
            $workshopTitle = trim($request->request->get('workshopTitle'));
            $workshopSubtitle = trim($request->request->get('workshopSubtitle'));
            $department = trim($request->request->get('department'));
            $workshopContent = trim($request->request->get('workshopContent'));
            $fromDate = trim($request->request->get('fromDate'));
            $toDate = trim($request->request->get('toDate'));
            $workshopVenue = trim($request->request->get('workshopVenue'));
            $workshopSchedule = trim($request->request->get('workshopSchedule'));
            $speakersInfo = trim($request->request->get('speakersInfo'));
            $public = trim($request->request->get('public'));
            $workshopMetaTitle = trim($request->request->get('workshopMetaTitle'));
            $workshopMetaKeywords = trim($request->request->get('workshopMetaKeywords'));
            $workshopMetaDescription = trim($request->request->get('workshopMetaDescription'));
            if ($workshopTitle == '') {
                $error['workshopTitle'] = "This field cannot be blank";
            }
            if ($public == '') {
                $error['public'] = "Please choose one option";
            }
            if ($fromDate != '') {

                $enteredDate = strtotime($fromDate);

                if ($enteredDate < $todaysTimestamp) {
                    $error['fromDate'] = "Please enter a future date";
                }
                if ($toDate != '') {
                    $enteredDateTo = strtotime($toDate);
                    if ($enteredDateTo < $enteredDate) {
                        $error['toDate'] = "Please enter a valid to date";
                    }
                }
            }
            if ($fromDate == '' && $toDate != '') {
                $error['toDate'] = "Please choose a From date before choosing To date";
            }
            if (empty($error)) {

                // $wokshop = new DahWorkshops();
                $workshop->setWorkshopTitle($workshopTitle);
                $workshop->setWorkshopSubtitle($workshopSubtitle);
                $workshop->setWorkshopContent($workshopContent);
                $workshop->setWorkshopVenue($workshopVenue);
                $workshop->setWorkshopSchedule($workshopSchedule);
                $workshop->setSpeakersInfo($speakersInfo);
                $workshop->setPublic($public);
                $workshop->setWorkshopMetaTitle($workshopMetaTitle);
                $workshop->setWorkshopMetaKeyword($workshopMetaKeywords);
                $workshop->setWorkshopMetaDescription($workshopMetaDescription);
                if ($enteredDate > 0) {
                    $workshop->setFromDate($enteredDate);
                }
                if ($enteredDateTo > 0) {
                    $workshop->setToDate($enteredDateTo);
                }
                $repository = $this->getDoctrine()->getRepository('AdminBundle:DahDepartments');
                $dpeobj = $repository->findOneBy(
                        array('deptid' => $department)
                );
                if ($dpeobj) {
                    $workshop->setDeptid($dpeobj);
                }
                $workshop->setUpdatedOn(time());
                $em->persist($workshop);
                $em->flush();

                $common->logActivity('updated workshop <a href="' . $this->generateUrl('_admin_edit_workshop', array('wid' => $workshop->getWid())) . '">' . $workshopTitle . '</a>');
                $this->get('session')->getFlashBag()->add('success', ' Workshop updated successfully.');
                return $this->redirect($this->generateUrl('_admin_workshop'));
            }
        }
        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $this->data['error'] = $error;
        $this->data['departments'] = $totaldeps;
        $this->data['mode'] = "edit";
        $this->data['workshop'] = $workshop;
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

    /**
     * @Route("/secure/workshop/students_enrollment/{wid}/{page}", name="_admin_workshop_enrollment", defaults={"wid"=0,"page"=1})
     * @Template("AdminBundle:Workshop:studentsEnrollment.html.twig")
     */
    public function workshopEnrollmentAction(Request $request, $wid,$page) {
        $students = array();
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahWorkshops');
        $workshop = $repository->findOneBy(
                array('wid' => $wid)
        );
        if (!$workshop) {
            return $this->redirect($this->generateUrl('_admin_workshop'));
        }
        
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $limit = 10;
        $key = '';
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $where = " where wid = $wid  ";
        $key = trim($request->query->get('keyword'));
        if ($request->query->get('reset') != '') {
            return $this->redirect($this->generateUrl('_admin_workshop'));
        }
        if ($key != '') {

            $where .= " and ( fname like '%" . $key . "%' or lname like '%" . $key . "%'   ) ";
        }
        if ($request->getMethod() == 'POST') {
           // print('<pre>');
           // print_r($_POST);
           // print('</pre>');
           // exit;
            $pst = $request->request->get('students');
            $status = $request->request->get('status');
            if (!empty($pst)) {
                foreach ($pst as $enid) {
                    $repository = $this->getDoctrine()->getRepository('AdminBundle:DahWorkshopEnrollment');
                    $en = $repository->findOneBy(
                            array('enid' => $enid)
                    );
                    $en->setCertificateStatus($status);
                    $em->persist($en);
                    $em->flush();
                    //send emails to correspondig users
                    $fEmail=$en->getEmail();
                    $maildata = array(
                        "name" =>$en->getFname(),
                        "wid" => $en->getWid()->getWid(),
                        "workshop" =>  $en->getWid()->getWorkshopTitle()
                    );
                    $message = $this->renderView('AdminBundle:Emails:workshopCertificate.html.twig', $maildata);
                    $emailhand->sendEmail($fEmail, 'Workshop Certificate', $message);
                    
                }
                $this->get('session')->getFlashBag()->add('success', ' Enrollment status updated successfully.');
                return $this->redirect($this->generateUrl('_admin_workshop_enrollment',array('wid'=>$wid)));
            }
        }
        $statement = $connection->prepare("  select * from dah_workshop_enrollment de  " . $where . " order by enid desc limit " . (($page - 1) * $limit) . ",$limit  ");
        $statement->execute();
        $pages = $statement->fetchAll();
        $statement = $connection->prepare(" select * from dah_workshop_enrollment de " . $where . " ");
        $statement->execute();
        $totalcontentpages = $statement->fetchAll();
        $totalpages = ceil(count($totalcontentpages) / $limit);
        $paginate = $common->paginate('_admin_workshop_enrollment', array('wid' => $wid), $totalpages, $page, 5);
        $students = $pages;
        $this->data['students'] = $students;
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
        
        
        
        
        $this->data['workshop'] = $workshop;
        return $this->data;
    }
    
    /**
     * @Route("/secure/workshop/upload_videos/{wid}", name="_admin_upload_videos_wrk", defaults={"wid"=0})
     * @Template("AdminBundle:Workshop:uploadvideos.html.twig")
     */
    public function uploadVideosAction(Request $request, $wid) {
        $session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $teachers = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahWorkshops');
        $workshop = $repository->findOneBy(
                array('wid' => $wid)
        );
        if (!$workshop) {
            $this->get('session')->getFlashBag()->add('success', ' Please choose a valid workshop.');
            return $this->redirect($this->generateUrl('_admin_workshop'));
        }
       
        if ($request->getMethod() == 'POST') {
             

            $videoTitles = $request->request->get('videoTitle');
            $tvids = $request->request->get('tvid');
            $videoDescs = $request->request->get('videoDesc');
            foreach ($tvids as $tvid) {
                $repository = $this->getDoctrine()->getRepository('AdminBundle:DahWorkshopVideos');
                $tvidios = $repository->findOneByTvid($tvid);
                
                if ($tvidios) {
                    if (!empty($_FILES) && isset($_FILES['videoThumbnail']['name'][$tvid]) && $_FILES['videoThumbnail']['size'][$tvid] > 0) {
                        $allowed = array('gif', 'png', 'jpg');
                        $tempFile = $_FILES['videoThumbnail']['tmp_name'][$tvid];


                        //$newname= $_FILES['imageupload']['name'];
                        if (isset($tempFile) && !empty($tempFile) && $tempFile != '') {
                            $fileParts = pathinfo($_FILES['videoThumbnail']['name'][$tvid]);
                            $newname = 'vid_thumb_' . md5(uniqid('avt_')) . '.' . strtolower($fileParts['extension']);
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
                            $tvidios->setVideoThumbnail($newname);
                        }
                    }


                    $title = trim($videoTitles[$tvid]);
                    $desc = trim($videoDescs[$tvid]);
                    $tvidios->setVideoTitle($title);
                    $tvidios->setVideoDesc($desc);
                    $em->persist($tvidios);
                    $em->flush();
                }
            }
            $this->get('session')->getFlashBag()->add('success', ' Success.');
            return $this->redirect($this->generateUrl('_admin_workshop'));
        }
        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_workshop_videos where wid = " . $workshop->getWid() . " order by tvid asc ");
        $statement->execute();
        $totvid = $statement->fetchAll();
        $this->data['videos'] = $totvid;
        $this->data['error'] = $error;
        $this->data['departments'] = $totaldeps;
        $this->data['user'] = $user;
        $this->data['wid'] = $wid;
        $this->data['workshop'] = $workshop;
        return $this->data;
    }
    
    /**
     * @Route("/secure/workshop/upload_material/{wid}", name="_admin_upload_material_wrk", defaults={"wid"=0})
     * @Template("AdminBundle:Workshop:uploadmaterial.html.twig")
     */
    public function addMaterialAction(Request $request,$wid) {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
      
        $this->data['materials'] = $em->getRepository('AdminBundle:DahWorkshopMaterial')
                                                ->findBy( array('dwid' => $wid, 'status'=> 'active'),array('id'=>'DESC'));
        $error = array();
        $materialupload = $request->request->get('materialupload'); 
        if ($request->getMethod() == 'POST') {
             $titles=$request->request->get('ftitle');
             $helperHandler = $this->get('helper_handler');
             $workshopCount=count($this->data['materials'] );
             $helperHandler->insertWorkshopMaterial($titles,$wid,$workshopCount);
        if (!$materialupload == '') {
                $error['materialupload'] = "Please attach a training material";
            }
        if (empty($error)) {
              
             $this->get('session')->getFlashBag()->add('success', ' Material Uploaded sucessfully.');
             return $this->redirect($this->generateUrl('_admin_upload_material_wrk' ,array('wid' => $wid)));
        }
        }
        $this->data['error'] = $error;
        return $this->data;
    }
    
    /**
     * @Route("/secure/trainings/student_assesment/{tid}/{uid}", name="_admin_view_certificate", defaults={"uid"=0,"tid"=0})
     * @Template("AdminBundle:Trainings:viewStudentAssesment.html.twig")
     */
    public function viewStudentAssesmentAction(Request $request,$tid, $uid) {
        $session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainings');
        $training = $repository->findOneBy(
                array('tid' => $tid)
        );
        if (!$training) {
            $this->get('session')->getFlashBag()->add('success', ' Please choose a valid training.');
            return $this->redirect($this->generateUrl('_admin_trainings'));
        }
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahAssesmentResult');
        $assesmentres = $repository->findOneBy(
                array('tid' => $tid,'uid'=>$uid)
        );
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahUserAssesmentResults');
        $assesments = $repository->findBy(
                array('tid' => $tid,'uid'=>$uid)
        );
        $ref = $session->get('referrer');
        if ($ref != '') {
            $session->remove('referrer');
            return $this->redirect($ref);
        }
        $user = $this->getUser();

        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $limit = 10;
        $key = '';
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $this->data['user'] = $user;
        $this->data['training'] = $training;
         $this->data['assesmentres'] = $assesmentres;
         $this->data['assesments'] = $assesments;
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

}
