<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use AdminBundle\Entity\DahTrainings;
use AdminBundle\Entity\DahTrainingVideos;
use AdminBundle\Entity\DahTrainingEnrollment;
use AdminBundle\Entity\DahAssesInfo;
use AdminBundle\Entity\DahQuestionOptions;
use AdminBundle\Entity\DahTrainingKey;
use AdminBundle\Entity\DahTrainingQuestions;
use AdminBundle\Entity\DahAssesmentResult;
use AdminBundle\Entity\DahUserAssesmentResults;


class TrainingsController extends Controller {

    public static $template = "AdminBundle:templates:default.html.twig";
    public $data = array();

    public function __construct() {
        $this->data['extend_view'] = self::$template;
    }

    /**
     * @Route("/secure/trainings/manage/{page}", name="_admin_trainings" , defaults={"page"=1} )
     * @Template("AdminBundle:Trainings:manage.html.twig")
     */
    public function trainingsAction(Request $request, $page) {
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
        $ref = $session->get('referrer');
        if ($ref != '') {
            $session->remove('referrer');
            return $this->redirect($ref);
        }

        $where = " where 1 = 1 ";
        $key = trim($request->query->get('keyword'));
        if ($request->query->get('reset') != '') {
            return $this->redirect($this->generateUrl('_admin_trainings'));
        }
        if ($key != '') {

            $where .= " and ( training_title like '%" . $key . "%'   ) ";
        }
        $statement = $connection->prepare("  select dt.*,dp.department from dah_trainings dt join dah_departments dp on dt.deptid = dp.deptid  " . $where . " order by dt.added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $trainings = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_trainings " . $where . " ");
        $statement->execute();
        $totalcontentpages = $statement->fetchAll();
        $totalpages = ceil(count($totalcontentpages) / $limit);
        $paginate = $common->paginate('_admin_trainings', array('keyword' => $key), $totalpages, $page, 5);
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


        return $this->data;
    }

    /**
     * @Route("/secure/trainings/add_new", name="_admin_add_new_training")
     * @Template("AdminBundle:Trainings:addNew.html.twig")
     */
    public function addNewAction(Request $request) {
        $session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $error = array();
        if ($request->getMethod() == 'POST') {
            $trainingTitle = trim($request->request->get('trainingTitle'));
            $department = trim($request->request->get('department'));
            $trainingDescription = trim($request->request->get('trainingDescription'));
            $public = trim($request->request->get('public'));
            $assesment = trim($request->request->get('assesment'));
            $trainingMetaTitle = trim($request->request->get('trainingMetaTitle'));
            $trainingMetaKeyword = trim($request->request->get('trainingMetaKeyword'));
            $trainingMetaDescription = trim($request->request->get('trainingMetaDescription'));
            $teacher = trim($request->request->get('teacher'));
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahUsers');
            $teacherobj = $repository->findOneBy(
                    array('uid' => $teacher)
            );
            if ($trainingTitle == '') {
                $error['trainingTitle'] = "Please enter training title";
            }
            if ($department == '') {
                $error['department'] = "Please choose a department";
            }
            if ($trainingDescription == '') {
                $error['trainingDescription'] = "Please enter training description";
            }
            if ($public == '') {
                $error['public'] = "Please choose one option";
            }
            if ($assesment == '') {
                $error['assesment'] = "Please choose one option";
            }
            if (!$teacherobj) {
                $error['teacher'] = "Please choose one option";
            }
            if (empty($error)) {

                $training = new DahTrainings();
                $training->setTrainingTitle($trainingTitle);
                $training->setTrainingDescription($trainingDescription);
                $training->setPublic($public);
                $training->setAssesment($assesment);
                $training->setUid($teacherobj);
                $training->setStatus('inactive');
                $repository = $this->getDoctrine()->getRepository('AdminBundle:DahDepartments');
                $dpeobj = $repository->findOneBy(
                        array('deptid' => $department)
                );
                if ($dpeobj) {
                    $training->setDeptid($dpeobj);
                }
                $training->setAddedOn(time());
                $em->persist($training);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', ' New Training added successfully.');
                return $this->redirect($this->generateUrl('_admin_trainings'));
            }
        }
        $statement = $connection->prepare("  select * from dah_users where role='ROLE_TEACHER'  ");
        $statement->execute();
        $allteachers = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $this->data['error'] = $error;
        $this->data['departments'] = $totaldeps;
        $this->data['teachers'] = $allteachers;
        $this->data['mode'] = 'add';
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

    /**
     * @Route("/secure/trainings/edit/{tid}", name="_admin_edit_training", defaults={"tid"=0})
     * @Template("AdminBundle:Trainings:addNew.html.twig")
     */
    public function editAction(Request $request, $tid) {
        $session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $error = array();
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainings');
        $training = $repository->findOneBy(
                array('tid' => $tid)
        );
        if (!$training) {
            $this->get('session')->getFlashBag()->add('success', ' Please choose a valid training.');
            return $this->redirect($this->generateUrl('_admin_trainings'));
        }
        if ($request->getMethod() == 'POST') {
            $trainingTitle = trim($request->request->get('trainingTitle'));
            $department = trim($request->request->get('department'));
            $trainingDescription = trim($request->request->get('trainingDescription'));
            $public = trim($request->request->get('public'));
            $assesment = trim($request->request->get('assesment'));
            $trainingMetaTitle = trim($request->request->get('trainingMetaTitle'));
            $trainingMetaKeyword = trim($request->request->get('trainingMetaKeyword'));
            $trainingMetaDescription = trim($request->request->get('trainingMetaDescription'));
            $teacher = trim($request->request->get('teacher'));
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahUsers');
            $teacherobj = $repository->findOneBy(
                    array('uid' => $teacher)
            );
            if ($trainingTitle == '') {
                $error['trainingTitle'] = "Please enter training title";
            }
            if ($department == '') {
                $error['department'] = "Please choose a department";
            }
            if ($trainingDescription == '') {
                $error['trainingDescription'] = "Please enter training description";
            }
            if ($public == '') {
                $error['public'] = "Please choose one option";
            }
            if ($assesment == '') {
                $error['assesment'] = "Please choose one option";
            }
            if (!$teacherobj) {
                $error['teacher'] = "Please choose one option";
            }
            if (empty($error)) {

               // $training = new DahTrainings();
                $training->setTrainingTitle($trainingTitle);
                $training->setTrainingDescription($trainingDescription);
                $training->setPublic($public);
                $training->setAssesment($assesment);
                $training->setUid($teacherobj);
                $repository = $this->getDoctrine()->getRepository('AdminBundle:DahDepartments');
                $dpeobj = $repository->findOneBy(
                        array('deptid' => $department)
                );
                if ($dpeobj) {
                    $training->setDeptid($dpeobj);
                }
                $training->setUpdatedOn(time());
                $em->persist($training);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', ' New Training added successfully.');
                return $this->redirect($this->generateUrl('_admin_trainings'));
            }
        }
        $statement = $connection->prepare("  select * from dah_users where role='ROLE_TEACHER'  ");
        $statement->execute();
        $allteachers = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $this->data['error'] = $error;
        $this->data['departments'] = $totaldeps;
        $this->data['teachers'] = $allteachers;
         $this->data['training'] = $training;
        $this->data['mode'] = 'edit';
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

    /**
     * @Route("/secure/trainings/assesment", name="_admin_assesment")
     * @Template("AdminBundle:Trainings:assesment.html.twig")
     */
    public function assesmentAction(Request $request) {
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

    /**
     * @Route("/secure/trainings/create_assesment/{tid}", name="_admin_create_assesment", defaults={"tid"=0})
     * @Template("AdminBundle:Trainings:createAssesment.html.twig")
     */
    public function createAssesmentAction(Request $request, $tid) {
        $session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $error = array();
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainings');
        $training = $repository->findOneBy(
                array('tid' => $tid)
        );
        if (!$training) {
            $this->get('session')->getFlashBag()->add('success', ' Please choose a valid training.');
            return $this->redirect($this->generateUrl('_admin_trainings'));
        }
        
        $statement = $connection->prepare("  select * from dah_users where role='ROLE_TEACHER'  ");
        $statement->execute();
        $allteachers = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_training_questions where tid = $tid order by qid desc ");
        $statement->execute();
        $trainingquestions = $statement->fetchAll();
        $this->data['questions'] = $trainingquestions;
        $statement = $connection->prepare("  select * from dah_asses_info where tid = " . $training->getTid() . "  ");
        $statement->execute();
        $assesment = $statement->fetch();
        $this->data['assesment'] = $assesment;
        $this->data['error'] = $error;
        $this->data['departments'] = $totaldeps;
        $this->data['teachers'] = $allteachers;
         $this->data['training'] = $training;
        $this->data['mode'] = 'edit';
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }
    
    /**
     * @Route("/secure/trainings/upload_videos/{tid}", name="_admin_upload_videos", defaults={"tid"=0})
     * @Template("AdminBundle:Trainings:uploadvideos.html.twig")
     */
    public function uploadVideosAction(Request $request, $tid) {
        $session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $teachers = array();
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
       
        if ($request->getMethod() == 'POST') {
            // print('<pre>');
            // print_r($_POST);
            // print('</pre>');
            // print('<pre>');
            // print_r($_FILES);
            // print('</pre>');
            //exit;

            $videoTitles = $request->request->get('videoTitle');
            $tvids = $request->request->get('tvid');
            $videoDescs = $request->request->get('videoDesc');
            foreach ($tvids as $tvid) {
                $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainingVideos');
                $tvidios = $repository->findOneByTvid($tvid);
                if ($tvidios) {
                    if (!empty($_FILES) && isset($_FILES['videoThumbnail']['name'][$tvid]) && $_FILES['videoThumbnail']['size'][$tvid] > 0) {
                        $allowed = array('mp4', 'flv', 'mp3');
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
        }
        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_training_videos where tid = " . $training->getTid() . " order by tvid asc ");
        $statement->execute();
        $totvid = $statement->fetchAll();
        $this->data['videos'] = $totvid;
        $this->data['error'] = $error;
        $this->data['departments'] = $totaldeps;
        $this->data['user'] = $user;
        $this->data['tid'] = $tid;
        $this->data['training'] = $training;
        return $this->data;
    }
    /**
     * @Route("/secure/trainings/students_enrollment/{tid}/{page}", name="_admin_students_enrollment", defaults={"tid"=0,"page"=1})
     * @Template("AdminBundle:Trainings:studentsEnrollment.html.twig")
     */
    public function studentsEnrollmentAction(Request $request, $tid, $page) {
        $session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $teachers = array();
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
        $todaysTimestamp = strtotime(date('d-m-Y'));
        $where = " where tid=$tid  ";

        $key = trim($request->query->get('keyword'));
        if ($request->query->get('reset') != '') {
            return $this->redirect($this->generateUrl('_news'));
        }
        if ($key != '') {

            $where .= " and ( training_title like '%" . $key . "%'  )  ";
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
                    $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainingEnrollment');
                    $en = $repository->findOneBy(
                            array('enid' => $enid)
                    );
                    $en->setCertificateStatus($status);
                    $em->persist($en);
                    $em->flush();

                    //send emails to correspondig users
                    $fEmail = $en->getUid()->getEmail();
                    //$fEmail = "upendramanve@gmail.com";
                    $maildata = array(
                        "name" => $en->getUid()->getName(),
                        "tid" => $en->getTid()->getTid(),
                        "training" => $en->getTid()->getTrainingTitle()
                    );
                    $message = $this->renderView('AdminBundle:Emails:trainingCertificate.html.twig', $maildata);
                    $emailhand->sendEmail($fEmail, 'Training Certificate', $message);
                }
                $this->get('session')->getFlashBag()->add('success', ' Enrollment status updated successfully.');
                return $this->redirect($this->generateUrl('_admin_students_enrollment', array('tid' => $tid)));
            }
        }
        $statement = $connection->prepare("  select * from dah_training_enrollment dt join dah_users du on dt.uid = du.uid  " . $where . " order by dt.enid desc limit " . (($page - 1) * $limit) . ",$limit  ");
        $statement->execute();
        $pages = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_training_enrollment dt join dah_users du on dt.uid = du.uid " . $where . " ");
        $statement->execute();
        $totalcontentpages = $statement->fetchAll();
        $totalpages = ceil(count($totalcontentpages) / $limit);
        $paginate = $common->paginate('_admin_create_assesment', array('tid' => $tid), $totalpages, $page, 5);
        $this->data['enrolls'] = $pages;
        $this->data['key'] = $key;
        $this->data['paginate'] = $paginate;
        $this->data['page'] = $page;
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

        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $this->data['error'] = $error;
        $this->data['departments'] = $totaldeps;
        $this->data['user'] = $user;
        $this->data['training'] = $training;
        return $this->data;
    }

    /**
     * @Route("/secure/certificates", name="_admin_certificates")
     * @Template("AdminBundle:Trainings:certificates.html.twig")
     */
    public function certificatesAction(Request $request) {
        $students = array();
        $students[] = array(
            'uid' => 341,
            'fullname' => 'Steel Sanders',
            'training' => 'Introduction to Big Data',
            'issued' => 'yes'
        );
        $students[] = array(
            'uid' => 342,
            'fullname' => 'Cyrus Ingram',
            'training' => 'Introduction to Big Data',
            'issued' => 'yes'
        );
        $students[] = array(
            'uid' => 343,
            'fullname' => 'Amir Blair',
            'training' => 'Understanding Common Diseases',
            'issued' => 'no'
        );
        $students[] = array(
            'uid' => 344,
            'fullname' => 'Akeem Roy',
            'training' => 'Foundation of Psycology',
            'issued' => 'no'
        );
        $students[] = array(
            'uid' => 345,
            'fullname' => 'Darius Sandoval',
            'training' => 'Introduction to Enterprise Architecture',
            'issued' => 'no'
        );
        $students[] = array(
            'uid' => 346,
            'fullname' => 'Magee Bright',
            'training' => 'Introduction to Enterprise Architecture',
            'issued' => 'yes'
        );
        $students[] = array(
            'uid' => 347,
            'fullname' => 'Lars Willis',
            'training' => 'Foundation of Psycology',
            'issued' => 'yes'
        );
        $students[] = array(
            'uid' => 348,
            'fullname' => 'Jelani Lamb',
            'training' => 'Financial Literacy',
            'issued' => 'yes'
        );
        $students[] = array(
            'uid' => 349,
            'fullname' => 'Hedley Mendoza',
            'training' => 'Introduction to Big Data',
            'issued' => 'yes'
        );
        $students[] = array(
            'uid' => 350,
            'fullname' => 'Fuller Lang',
            'training' => 'Chinese Language and Culture',
            'issued' => 'yes'
        );
        $this->data['students'] = $students;
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

    /**
     * @Route("/secure/trainings/view_certificate/{certid}", name="_admin_view_certificate", defaults={"certid"=0})
     * @Template("AdminBundle:Trainings:viewCertificate.html.twig")
     */
    public function viewCertificateAction(Request $request, $certid) {
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

    /**
     * @Route("/secure/trainings/add_new_dep/{depid}", name="_admin_add_new_dep", defaults={"depid"=0})
     * @Template("AdminBundle:Trainings:modaladdNew.html.twig")
     */
    public function addNewDepAction(Request $request, $depid) {
        $this->data['mode'] = 'add';
        if ($depid > 0) {
            $this->data['mode'] = 'edit';
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahDepartments');
            $department = $repository->find($depid);
            if (!$department) {
                return $this->redirect($this->generateUrl('_admin_departments'));
            }
            $em = $this->getDoctrine()->getManager();
            $validator = $this->get('validator');
            $error = Array();


            if ($request->getMethod() == 'POST') {
                $repository = $this->getDoctrine()->getRepository('AdminBundle:DahDepartments');
                $existingDepartment = $repository->findBy(
                        array('department' => $request->request->get('Department'))
                );

                if (count($existingDepartment) > 1) {
                    $error['Department'] = 'Department already exists';
                }
                if (empty($error)) {
                    $dept->setDepartment($request->request->get('Department'));
                    $service->setUpdatedOn(time());
                    $service->setStatus('active');
                    $em->persist($dept);
                    $em->flush();
                    $res = array('status' => 'Success',
                        'message' => 'Department has been updated Successfully',
                        'response' => ''
                    );
                } else {
                    $res = array('status' => 'Failed',
                        'message' => $error['Department'],
                        'response' => ''
                    );
                }
                $response->setContent(json_encode($res));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }


            $this->data['dapartment'] = $department;
            //print_r($this->data);
        }
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

    /**
     * @Route("/secure/trainings/add_new_mcq/{trainingid}/{mcqid}", name="_admin_add_new_mcq",defaults={"trainingid"=0,"mcqid"=0})
     * @Template("AdminBundle:Trainings:modaladdNewMcq.html.twig")
     */
    public function addNewMcqAction(Request $request, $trainingid, $mcqid) {
        $this->data['mode'] = 'add';
        if ($mcqid > 0) {
            $this->data['mode'] = 'edit';
        }
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

    /**
     * @Route("/secure/trainings/add_new_tf/{trainingid}/{tfid}", name="_admin_add_new_tf",defaults={"trainingid"=0,"tfid"=0})
     * @Template("AdminBundle:Trainings:modaladdNewTf.html.twig")
     */
    public function addNewTfAction(Request $request, $trainingid, $tfid) {
        $this->data['mode'] = 'add';
        if ($tfid > 0) {
            $this->data['mode'] = 'edit';
        }
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

    /**
     * @Route("/secure/trainings/add_new_desc", name="_admin_add_new_desc")
     * @Template("AdminBundle:Trainings:modaladdNewDesc.html.twig")
     */
    public function addNewDescAction(Request $request) {
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }
    /**
     * @Route("/secure/trainings/upload_material/{tid}", name="_admin_upload_material", defaults={"tid"=0})
     * @Template("AdminBundle:Trainings:uploadmaterial.html.twig")
     */
    public function addMaterialAction(Request $request,$tid) {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $materialupload = $request->request->get('materialupload');
        $this->data['materials'] = $em->getRepository('AdminBundle:DahTrainingMaterial')
                                                ->findBy( array('dtid' => $tid, 'status'=> 'active'),array('id'=>'DESC'));
        $error = array();
         $materialupload = $request->request->get('materialupload'); 
         
        if ($request->getMethod() == 'POST') {
             $titles=$request->request->get('ftitle');
             $helperHandler = $this->get('helper_handler');
             $trainingCount=count($this->data['materials'] );
             $helperHandler->insertTrainingMaterial($titles,$tid,$trainingCount);
        if (!$materialupload == '') {
                $error['materialupload'] = "Please attach a training material";
            }
        if (empty($error)) {
              
             $this->get('session')->getFlashBag()->add('success', ' Material Uploaded sucessfully.');
             return $this->redirect($this->generateUrl('_admin_upload_material' ,array('tid' => $tid) ));
        }
        }
        $this->data['error'] = $error;
            return $this->data;
    }

}
