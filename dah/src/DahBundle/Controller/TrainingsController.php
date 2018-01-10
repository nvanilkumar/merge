<?php

namespace DahBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use AdminBundle\Entity\DahEnquires;
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

    public static $template = "DahBundle:templates:default.html.twig";
    public $data = array();

    public function __construct() {
        $this->data['extend_view'] = self::$template;
    }

    /**
     * @Route("/account/trainings/{page}", name="_trainings", defaults={"page"=1} )
     * @Template("DahBundle:Trainings:trainings.html.twig")
     */
    public function pageAction(Request $request, $page) {
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
        //  $ref = $session->get('referrer');
        //  if ($ref != '') {
        //      $session->remove('referrer');
        //      return $this->redirect($ref);
        //  }
        $user = $this->getUser();
        if ($user->getRole() == 'ROLE_TEACHER') {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTeachersInfo');
        } else {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahStudentInfo');
        }
        $userinfo = $repository->findOneByUid($user->getUid());
        $where = " where uid = " . $user->getUid() . " ";
        $key = trim($request->query->get('keyword'));
        if ($request->query->get('reset') != '') {
            return $this->redirect($this->generateUrl('_my_trainings'));
        }
        if ($key != '') {

            $where .= " and ( training_title like '%" . $key . "%'   ) ";
        }
        $statement = $connection->prepare("  select * from dah_trainings dt join dah_departments dp on dt.deptid = dp.deptid  " . $where . " order by dt.added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $trainings = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_trainings " . $where . " ");
        $statement->execute();
        $totalcontentpages = $statement->fetchAll();
        $totalpages = ceil(count($totalcontentpages) / $limit);
        $paginate = $common->paginate('_trainings', array('keyword' => $key), $totalpages, $page, 5);
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
     * @Route("/account/training/add_new", name="_add_new_trainings")
     * @Template("DahBundle:Trainings:addNew.html.twig")
     */
    public function addNewAction(Request $request) {
        $session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $teachers = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        // $ref = $session->get('referrer');
        // if ($ref != '') {
        //    $session->remove('referrer');
        //    return $this->redirect($ref);
        // }
        $user = $this->getUser();
        if ($user->getRole() == 'ROLE_STUDENT') {
            $this->get('session')->getFlashBag()->add('success', 'You are not elligible to add trainings.');
            return $this->redirect($this->generateUrl('_home'));
        }
        if ($user->getRole() == 'ROLE_TEACHER') {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTeachersInfo');
        } else {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahStudentInfo');
        }
        $userinfo = $repository->findOneByUid($user->getUid());
        if ($request->getMethod() == 'POST') {
            $trainingTitle = trim($request->request->get('trainingTitle'));
            $department = trim($request->request->get('department'));
            $trainingDescription = trim($request->request->get('trainingDescription'));
            // $videoTitle = $request->request->get('videoTitle');
            // $manualUpload = $request->request->get('manualUpload');
            $public = trim($request->request->get('public'));
            $assesment = trim($request->request->get('assesment'));
            // print('<pre>');
            // print_r($videoTitle);
            // print('</pre>');
            // print('<pre>');
            // print_r($_FILES);
            // print('</pre>');
            // exit;


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
            if (empty($error)) {

                $training = new DahTrainings();
                $training->setTrainingTitle($trainingTitle);
                $training->setTrainingDescription($trainingDescription);
                $training->setPublic($public);
                $training->setAssesment($assesment);
                $training->setUid($user);
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
                return $this->redirect($this->generateUrl('_upload_video_trainings', array('tid' => $training->getTid())));
            }
        }
        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $this->data['error'] = $error;
        $this->data['departments'] = $totaldeps;
        $this->data['user'] = $user;
        $this->data['userinfo'] = $userinfo;
        //tabs control 
        $this->data['activetab'] = 'training';
        $this->data['tablevel'] = 1;
        $this->data['mode'] = 'add';
        return $this->data;
    }

    /**
     * @Route("/account/training/uploadvideo/{tid}", name="_upload_video_trainings" , defaults={"tid"=0})
     * @Template("DahBundle:Trainings:uploadvideo.html.twig")
     */
    public function uploadVideoAction(Request $request, $tid) {
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
                array('tid' => $tid, 'uid' => $user->getUid())
        );
        if (!$training) {
            $this->get('session')->getFlashBag()->add('success', ' Please choose a valid training.');
            return $this->redirect($this->generateUrl('_trainings'));
        }
        //  $ref = $session->get('referrer');
        // if ($ref != '') {
        //     $session->remove('referrer');
        //     return $this->redirect($ref);
        // }

        if ($user->getRole() == 'ROLE_STUDENT') {
            $this->get('session')->getFlashBag()->add('success', 'You are not elligible to add trainings.');
            return $this->redirect($this->generateUrl('_home'));
        }
        if ($user->getRole() == 'ROLE_TEACHER') {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTeachersInfo');
        } else {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahStudentInfo');
        }
        $userinfo = $repository->findOneByUid($user->getUid());
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
                        $allowed = array('.mp4', '.webm', '.flv');
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
        $this->data['userinfo'] = $userinfo;
        $this->data['training'] = $training;
        //tabs control 
        $this->data['activetab'] = 'video';
        $this->data['tablevel'] = 5;
        $this->data['assesmenturl'] = $this->generateUrl('_createassesment_trainings', array('tid' => $tid));
        $this->data['videourl'] = $this->generateUrl('_upload_video_trainings', array('tid' => $tid));
        $this->data['inviteurl'] = $this->generateUrl('_invite_trainings', array('tid' => $tid));
        $this->data['enrollurl'] = $this->generateUrl('_enrollment_trainings', array('tid' => $tid));
        $this->data['trainingurl'] = $this->generateUrl('_edit_training', array('tid' => $tid));
        $this->data['materialurl'] = $this->generateUrl('_teacher_upload_material', array('tid' => $tid));
        if ($training->getPublic() == 'no') {
            // $this->data['invitetab'] = 'yes';
        }
        $this->data['enrolltab'] = 'yes';
        $this->data['tid'] = $tid;
        $this->data['mode'] = 'edit';
        return $this->data;
    }

    /**
     * @Route("/account/training/createassesment/{tid}", name="_createassesment_trainings" , defaults={"tid"=0})
     * @Template("DahBundle:Trainings:createassesment.html.twig")
     */
    public function createassesmentAction(Request $request, $tid) {
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
                array('tid' => $tid, 'uid' => $user->getUid())
        );
        if (!$training) {
            $this->get('session')->getFlashBag()->add('success', ' Please choose a valid training.');
            return $this->redirect($this->generateUrl('_trainings'));
        }
        //   $ref = $session->get('referrer');
        //  if ($ref != '') {
        //      $session->remove('referrer');
        //      return $this->redirect($ref);
        //  }
        $user = $this->getUser();
        if ($user->getRole() == 'ROLE_STUDENT') {
            $this->get('session')->getFlashBag()->add('success', 'You are not elligible to add trainings.');
            return $this->redirect($this->generateUrl('_home'));
        }
        if ($user->getRole() == 'ROLE_TEACHER') {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTeachersInfo');
        } else {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahStudentInfo');
        }
        $userinfo = $repository->findOneByUid($user->getUid());
        if ($request->getMethod() == 'POST') {
            //  print('<pre>');
            // print_r($_POST);
            // print('<pre>');
            // exit;
            $qopt = array();
            $cuttOff = $request->request->get('cuttOff');
            $totalMarks = $request->request->get('totalMarks');
            if (isset($_POST['qopt'])) {
                $qopt = $request->request->get('qopt');
            }

            if (empty($error)) {
                $repository = $this->getDoctrine()->getRepository('AdminBundle:DahAssesInfo');
                $dpeobj = $repository->findOneBy(
                        array('tid' => $training->getTid())
                );
                if (!$dpeobj) {
                    $dpeobj = new DahAssesInfo();
                }
                $dpeobj->setTotalmarks($totalMarks);
                $dpeobj->setCutoff($cuttOff);
                $dpeobj->setTid($training);
                $em->persist($dpeobj);
                $em->flush();
                foreach ($qopt as $key => $opts) {
                    $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainingKey');
                    $tk = $repository->findOneBy(
                            array('qid' => $key)
                    );
                    if (!$tk) {
                        $tk = new DahTrainingKey();
                    }
                    $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainingQuestions');
                    $quest = $repository->findOneBy(
                            array('qid' => $key)
                    );
                    $tk->setQid($quest);
                    $repository = $this->getDoctrine()->getRepository('AdminBundle:DahQuestionOptions');
                    $opt = $repository->findOneBy(
                            array('opid' => $opts)
                    );
                    $tk->setOpid($opt);
                    $em->persist($tk);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', ' Training assesment completed successfully.');
                return $this->redirect($this->generateUrl('_createassesment_trainings', array('tid' => $tid)));
            }
        }
        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $this->data['error'] = $error;
        $statement = $connection->prepare("  select * from dah_training_questions where tid = $tid order by qid desc ");
        $statement->execute();
        $trainingquestions = $statement->fetchAll();
        $this->data['questions'] = $trainingquestions;
        $statement = $connection->prepare("  select * from dah_asses_info where tid = " . $training->getTid() . "  ");
        $statement->execute();
        $assesment = $statement->fetch();
        $this->data['assesment'] = $assesment;
        $this->data['tid'] = $tid;
        $this->data['departments'] = $totaldeps;
        $this->data['user'] = $user;
        $this->data['userinfo'] = $userinfo;
        $this->data['training'] = $training;
        //tabs control  
        $this->data['activetab'] = 'assesment';
        $this->data['videourl'] = $this->generateUrl('_upload_video_trainings', array('tid' => $tid));
        $this->data['inviteurl'] = $this->generateUrl('_invite_trainings', array('tid' => $tid));
        $this->data['trainingurl'] = $this->generateUrl('_edit_training', array('tid' => $tid));
        $this->data['enrollurl'] = $this->generateUrl('_enrollment_trainings', array('tid' => $tid));
        $this->data['materialurl'] = $this->generateUrl('_teacher_upload_material', array('tid' => $tid));
        $this->data['tablevel'] = 5;
        if ($training->getPublic() == 'no') {
            $this->data['invitetab'] = 'yes';
        }
        $this->data['enrolltab'] = 'yes';
        $this->data['mode'] = 'edit';
        return $this->data;
    }

    /**
     * @Route("/ajax/add_assess_qa", name="_ajax_add_mcq")
     */
    public function ajaxAddMcqAction(Request $request) {
        $response = new Response();
        $isAjax = $request->isXmlHttpRequest();
        // get the value of a $_POST parameter
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $this->session = $request->getSession();
        $mode = 'edit';
        $res = array('status' => 'error',
            'message' => $this->get('translator')->trans('server.messages.somethingWentWrong'),
            'response' => '',
            'type' => $mode,
            'id' => 0,
            'marks' => 0
        );

        if ($request->getMethod() == 'POST') {
            $tid = trim($request->request->get('tid'));
            $formType = trim($request->request->get('formType'));
            $qid = trim($request->request->get('qid'));
            $quesn = trim($request->request->get('question'));
            $option = $request->request->get('option');
            $answer = trim($request->request->get('answer'));
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainings');
            $training = $repository->findOneBy(
                    array('tid' => $tid)
            );
            if ($training) {
                if ($tid > 0) {
                    if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {


                        $user = $this->getUser();
                        if (!$user) {
                            $res['message'] = $this->get('translator')->trans("server.messages.somethingWentWrong");
                        } else {
                            switch ($formType) {
                                case "mcq": {
                                        if (!empty($option)) {
                                            $question = new DahTrainingQuestions();
                                            if ($qid > 0) {
                                                $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainingQuestions');
                                                $question = $repository->findOneBy(
                                                        array('qid' => $qid, 'tid' => $tid)
                                                );
                                                if (!$question) {
                                                    $question = new DahTrainingQuestions();
                                                    $mode = 'add';
                                                }
                                            } elseif ($quesn != '') {
                                                $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainingQuestions');
                                                $question = $repository->findOneBy(
                                                        array('question' => $quesn, 'tid' => $tid)
                                                );
                                                if (!$question) {
                                                    $question = new DahTrainingQuestions();
                                                    $mode = 'add';
                                                }
                                            }
                                            $question->setTid($training);
                                            $question->setQuestion($quesn);
                                            $question->setMarks(1);
                                            $question->setQtype('mcq');
                                            $question->setStatus('active');
                                            $em->persist($question);
                                            $em->flush();
                                            $user_services = $em->getRepository('AdminBundle:DahQuestionOptions')
                                                    ->findByQid($question->getQid());

                                            foreach ($user_services as $user_service) {
                                                $em->remove($user_service);
                                            }
                                            $em->flush();
                                            $newanswer = false;
                                            foreach ($option as $opt) {
                                                $nopt = trim($opt);
                                                if ($nopt != '') {
                                                    $qopt = new DahQuestionOptions();
                                                    $qopt->setQid($question);
                                                    $qopt->setOptions($nopt);
                                                    $em->persist($qopt);
                                                    $em->flush();
                                                    if ($nopt == $answer) {
                                                        $newanswer = $qopt;
                                                    }
                                                }
                                            }
                                            //$newanswer = $em->getRepository('AdminBundle:DahQuestionOptions')
                                            //      ->findOneByOptions($answer);
                                            if ($newanswer) {
                                                $qkey = $em->getRepository('AdminBundle:DahTrainingKey')
                                                        ->findOneByQid($question->getQid());
                                                if (!$qkey) {
                                                    $qkey = new DahTrainingKey();
                                                }
                                                $qkey->setQid($question);
                                                $qkey->setOpid($newanswer);
                                                $em->persist($qkey);
                                                $em->flush();
                                            }
                                            $statement = $connection->prepare("  select * from dah_training_questions where qid = " . $question->getQid() . " order by qid desc ");
                                            $statement->execute();
                                            if ($mode == 'add') {
                                                $trainingquestions = $statement->fetchAll();
                                                $maildata['questions'] = $trainingquestions;

                                                $resp = $this->renderView('DahBundle:Trainings:questionEStrip.html.twig', $maildata);
                                            } else {
                                                $trainingquestions = $statement->fetch();
                                                $maildata['question'] = $trainingquestions;

                                                $resp = $this->renderView('DahBundle:Trainings:questionEdStrip.html.twig', $maildata);
                                            }
                                            $statement = $connection->prepare("  select sum(marks) as marks from dah_training_questions where tid = " . $training->getTid() . "  ");
                                            $statement->execute();
                                            $sum = $statement->fetch();
                                            $qassinfo = $em->getRepository('AdminBundle:DahAssesInfo')
                                                    ->findOneByTid($tid);
                                            if (!$qassinfo) {
                                                $qassinfo = new DahAssesInfo();
                                            }
                                            $qassinfo->setTotalmarks($sum['marks']);
                                            $em->persist($qassinfo);
                                            $em->flush();

                                            $res['marks'] = $sum['marks'];
                                            $res['message'] = 'Question saved successfully.';
                                            $res['status'] = 'success';
                                            $res['response'] = $resp;
                                            $res['type'] = $mode;
                                            $res['id'] = $question->getQid();
                                        } else {
                                            $res['message'] = $this->get('translator')->trans("server.messages.somethingWentWrong");
                                        }
                                        break;
                                    }
                                case "tf": {


                                        $question = new DahTrainingQuestions();
                                        if ($qid > 0) {
                                            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainingQuestions');
                                            $question = $repository->findOneBy(
                                                    array('qid' => $qid, 'tid' => $tid)
                                            );
                                            if (!$question) {
                                                $question = new DahTrainingQuestions();
                                                $mode = 'add';
                                            }
                                        } elseif ($quesn != '') {
                                            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainingQuestions');
                                            $question = $repository->findOneBy(
                                                    array('question' => $quesn, 'tid' => $tid)
                                            );
                                            if (!$question) {
                                                $question = new DahTrainingQuestions();
                                                $mode = 'add';
                                            }
                                        }
                                        $question->setTid($training);
                                        $question->setQuestion($quesn);
                                        $question->setMarks(1);
                                        $question->setQtype('tf');
                                        $question->setStatus('active');
                                        $em->persist($question);
                                        $em->flush();
                                        $user_services = $em->getRepository('AdminBundle:DahQuestionOptions')
                                                ->findByQid($question->getQid());

                                        foreach ($user_services as $user_service) {
                                            $em->remove($user_service);
                                        }
                                        $em->flush();

                                        $qoptTrue = new DahQuestionOptions();
                                        $qoptTrue->setQid($question);
                                        $qoptTrue->setOptions('True');
                                        $em->persist($qoptTrue);
                                        $em->flush();
                                        $qoptFalse = new DahQuestionOptions();
                                        $qoptFalse->setQid($question);
                                        $qoptFalse->setOptions('False');
                                        $em->persist($qoptFalse);
                                        $em->flush();

                                        if ($answer != '') {
                                            $qkey = $em->getRepository('AdminBundle:DahTrainingKey')
                                                    ->findOneByQid($question->getQid());
                                            if (!$qkey) {
                                                $qkey = new DahTrainingKey();
                                            }
                                            $qkey->setQid($question);
                                            if ($answer == 'True') {
                                                $qkey->setOpid($qoptTrue);
                                            } else {
                                                $qkey->setOpid($qoptFalse);
                                            }
                                            $em->persist($qkey);
                                            $em->flush();
                                        }
                                        $statement = $connection->prepare("  select * from dah_training_questions where qid = " . $question->getQid() . " order by qid desc ");
                                        $statement->execute();
                                        if ($mode == 'add') {
                                            $trainingquestions = $statement->fetchAll();
                                            $maildata['questions'] = $trainingquestions;

                                            $resp = $this->renderView('DahBundle:Trainings:questionEStrip.html.twig', $maildata);
                                        } else {
                                            $trainingquestions = $statement->fetch();
                                            $maildata['question'] = $trainingquestions;

                                            $resp = $this->renderView('DahBundle:Trainings:questionEdStrip.html.twig', $maildata);
                                        }
                                        $statement = $connection->prepare("  select sum(marks) as marks from dah_training_questions where tid = " . $training->getTid() . "  ");
                                        $statement->execute();
                                        $sum = $statement->fetch();
                                        $qassinfo = $em->getRepository('AdminBundle:DahAssesInfo')
                                                ->findOneByTid($tid);
                                        if (!$qassinfo) {
                                            $qassinfo = new DahAssesInfo();
                                        }
                                        $qassinfo->setTotalmarks($sum['marks']);
                                        $em->persist($qassinfo);
                                        $em->flush();
                                        $res['marks'] = $sum['marks'];
                                        $res['message'] = 'Question saved successfully.';
                                        $res['status'] = 'success';
                                        $res['response'] = $resp;
                                        $res['type'] = $mode;
                                        $res['id'] = $question->getQid();

                                        break;
                                    }
                                default:
                                    $res['message'] = $this->get('translator')->trans("server.messages.somethingWentWrong");
                            }
                        }
                    } else {
                        $this->session->set('referrer', $this->generateUrl('_view_workshop', array('wid' => $wid)));
                        $res['message'] = 'Please <a href="' . $this->generateUrl('_home') . '">Login</a> or <a href="' . $this->generateUrl('_signup_student') . '">Signup</a> to enroll to this Workshop. ';
                    }
                }
            }
        }

        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/ajax/getquestiondet", name="_getquestiondet")
     */
    public function getquestiondetAction(Request $request) {
        $response = new Response();
        $common = $this->get('common_handler');
        $res = array('status' => 'error',
            'message' => $this->get('translator')->trans('server.messages.somethingWentWrong'),
            'response' => ''
        );
        $isAjax = $request->isXmlHttpRequest();
        $qid = $request->request->get('qid');
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        if ($qid > 0) {
            $statement = $connection->prepare("  select * from dah_training_questions where qid = $qid order by qid desc ");
            $statement->execute();
            $question = $statement->fetch();
            if (!empty($question)) {
                $res['status'] = 'success';
                $res['response']['question'] = $question;
                $statement = $connection->prepare(" select * from dah_question_options where qid = $qid  ");
                $statement->execute();
                $cnt = $statement->fetchAll();
                $res['response']['options'] = $cnt;
                $statement = $connection->prepare(" select * from dah_training_key k join dah_question_options o on k.opid = o.opid where k.qid = $qid  ");
                $statement->execute();
                $cnt = $statement->fetch();
                $res['response']['answer'] = $cnt;
            }
        }
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/ajax/settrainingassesdet", name="_settrainingassesdet")
     */
    public function settrainingassesdetAction(Request $request) {
        $response = new Response();
        $common = $this->get('common_handler');
        $res = array('status' => 'error',
            'message' => $this->get('translator')->trans('server.messages.somethingWentWrong'),
            'response' => ''
        );
        $isAjax = $request->isXmlHttpRequest();
        $tid = $request->request->get('tid');
        $cut = $request->request->get('cut');
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        if ($tid > 0 && $cut >= 0) {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainings');
            $training = $repository->findOneBy(
                    array('tid' => $tid)
            );
            if (!$training) {
                //  $this->get('session')->getFlashBag()->add('success', ' Please choose a valid training.');
                // return $this->redirect($this->generateUrl('_trainings'));
                $res['message'] = ' Please choose a valid training.';
            } else {
                $statement = $connection->prepare("  select * from dah_training_questions where tid = $tid  ");
                $statement->execute();
                $cquestion = $statement->fetchAll();
                $qassinfo = $em->getRepository('AdminBundle:DahAssesInfo')
                        ->findOneByTid($tid);
                if (!$qassinfo) {
                    $qassinfo = new DahAssesInfo();
                }
                $qassinfo->setTid($training);
                $qassinfo->setCutoff($cut);
                $qassinfo->setTotalmarks(count($cquestion));
                $em->persist($qassinfo);
                $em->flush();
                $res['message'] = ' Training updated sucessfully.';
                $res['status'] = 'success';
            }
        }
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/account/training/invite/{tid}", name="_invite_trainings" , defaults={"tid"=0})
     * @Template("DahBundle:Trainings:invite.html.twig")
     */
    public function inviteAction(Request $request, $tid) {
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
                array('tid' => $tid, 'uid' => $user->getUid())
        );
        if (!$training) {
            $this->get('session')->getFlashBag()->add('success', ' Please choose a valid training.');
            return $this->redirect($this->generateUrl('_trainings'));
        }
        //  $ref = $session->get('referrer');
        //  if ($ref != '') {
        //      $session->remove('referrer');
        //      return $this->redirect($ref);
        //  }
        $user = $this->getUser();
        if ($user->getRole() == 'ROLE_STUDENT') {
            $this->get('session')->getFlashBag()->add('success', 'You are not elligible to add trainings.');
            return $this->redirect($this->generateUrl('_home'));
        }
        if ($user->getRole() == 'ROLE_TEACHER') {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTeachersInfo');
        } else {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahStudentInfo');
        }
        $userinfo = $repository->findOneByUid($user->getUid());
        if ($request->getMethod() == 'POST') {
            $trainingTitle = trim($request->request->get('trainingTitle'));
            $department = trim($request->request->get('department'));
            $trainingDescription = trim($request->request->get('trainingDescription'));
            $videoTitle = $request->request->get('videoTitle');
            $manualUpload = $request->request->get('manualUpload');
            $public = trim($request->request->get('public'));
            $assesment = trim($request->request->get('assesment'));


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
            if (empty($error)) {

                $training = new DahTrainings();
                $training->setTrainingTitle($trainingTitle);
                $training->setTrainingDescription($trainingDescription);
                $training->setPublic($public);
                $training->setAssesment($assesment);
                $training->setUid($user);

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
                return $this->redirect($this->generateUrl('_trainings'));
            }
        }
        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $this->data['error'] = $error;
        $this->data['departments'] = $totaldeps;
        $this->data['user'] = $user;
        $this->data['userinfo'] = $userinfo;
        $this->data['training'] = $training;
        //tabs control 
        $this->data['activetab'] = 'invite';
        $this->data['videourl'] = $this->generateUrl('_upload_video_trainings', array('tid' => $tid));
        $this->data['inviteurl'] = $this->generateUrl('_invite_trainings', array('tid' => $tid));
        $this->data['assesmenturl'] = $this->generateUrl('_createassesment_trainings', array('tid' => $tid));
        $this->data['enrollurl'] = $this->generateUrl('_enrollment_trainings', array('tid' => $tid));
        $this->data['trainingurl'] = $this->generateUrl('_edit_training', array('tid' => $tid));
        $this->data['materialurl'] = $this->generateUrl('_teacher_upload_material', array('tid' => $tid));
        $this->data['tablevel'] = 5;
        if ($training->getPublic() == 'no') {
            //$this->data['invitetab'] = 'yes';
        }
        $this->data['enrolltab'] = 'yes';
        $this->data['mode'] = 'edit';
        return $this->data;
    }

    /**
     * @Route("/account/training/enrollment/{tid}/{page}", name="_enrollment_trainings" , defaults={"tid"=0,"page"=1})
     * @Template("DahBundle:Trainings:enrollment.html.twig")
     */
    public function enrollmentAction(Request $request, $tid, $page) {
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
                array('tid' => $tid, 'uid' => $user->getUid())
        );
        if (!$training) {
            $this->get('session')->getFlashBag()->add('success', ' Please choose a valid training.');
            return $this->redirect($this->generateUrl('_trainings'));
        }
        //    $ref = $session->get('referrer');
        //  if ($ref != '') {
        //      $session->remove('referrer');
        //     return $this->redirect($ref);
        //}
        $user = $this->getUser();
        if ($user->getRole() == 'ROLE_STUDENT') {
            $this->get('session')->getFlashBag()->add('success', 'You are not elligible to add trainings.');
            return $this->redirect($this->generateUrl('_home'));
        }
        if ($user->getRole() == 'ROLE_TEACHER') {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTeachersInfo');
        } else {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahStudentInfo');
        }
        $userinfo = $repository->findOneByUid($user->getUid());

        $common = $this->get('common_handler');
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
        $statement = $connection->prepare("  select * from dah_training_enrollment dt join dah_users du on dt.uid = du.uid  " . $where . " order by dt.enid desc limit " . (($page - 1) * $limit) . ",$limit  ");
        $statement->execute();
        $pages = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_training_enrollment dt join dah_users du on dt.uid = du.uid " . $where . " ");
        $statement->execute();
        $totalcontentpages = $statement->fetchAll();
        $totalpages = ceil(count($totalcontentpages) / $limit);
        $paginate = $common->paginate('_enrollment_trainings', array('tid' => $tid), $totalpages, $page, 5);
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
        $this->data['userinfo'] = $userinfo;
        $this->data['training'] = $training;
        //tabs control 
        $this->data['activetab'] = 'enrollment';
        $this->data['videourl'] = $this->generateUrl('_upload_video_trainings', array('tid' => $tid));
        $this->data['inviteurl'] = $this->generateUrl('_invite_trainings', array('tid' => $tid));
        $this->data['enrollurl'] = $this->generateUrl('_enrollment_trainings', array('tid' => $tid));
        $this->data['assesmenturl'] = $this->generateUrl('_createassesment_trainings', array('tid' => $tid));
        $this->data['trainingurl'] = $this->generateUrl('_edit_training', array('tid' => $tid));
        $this->data['materialurl'] = $this->generateUrl('_teacher_upload_material', array('tid' => $tid));
        $this->data['tablevel'] = 5;
        if ($training->getPublic() == 'no') {
            // $this->data['invitetab'] = 'yes';
        }
        $this->data['enrolltab'] = 'yes';
        $this->data['mode'] = 'edit';
        return $this->data;
    }

    /**
     * @Route("/account/training/edit_training/{tid}", name="_edit_training" , defaults={"tid"=0})
     * @Template("DahBundle:Trainings:addNew.html.twig")
     */
    public function editTrainingAction(Request $request, $tid) {

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
                array('tid' => $tid, 'uid' => $user->getUid())
        );
        if (!$training) {
            $this->get('session')->getFlashBag()->add('success', ' Please choose a valid training.');
            return $this->redirect($this->generateUrl('_trainings'));
        }
        //   $ref = $session->get('referrer');
        //  if ($ref != '') {
        //     $session->remove('referrer');
        //    return $this->redirect($ref);
        //}

        if ($user->getRole() == 'ROLE_STUDENT') {
            $this->get('session')->getFlashBag()->add('success', 'You are not elligible to add trainings.');
            return $this->redirect($this->generateUrl('_home'));
        }
        if ($user->getRole() == 'ROLE_TEACHER') {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTeachersInfo');
        } else {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahStudentInfo');
        }
        $userinfo = $repository->findOneByUid($user->getUid());
        if ($request->getMethod() == 'POST') {
            $trainingTitle = trim($request->request->get('trainingTitle'));
            $department = trim($request->request->get('department'));
            $trainingDescription = trim($request->request->get('trainingDescription'));
            // $videoTitle = $request->request->get('videoTitle');
            // $manualUpload = $request->request->get('manualUpload');
            $public = trim($request->request->get('public'));
            $assesment = trim($request->request->get('assesment'));
            // print('<pre>');
            // print_r($videoTitle);
            // print('</pre>');
            // print('<pre>');
            // print_r($_FILES);
            // print('</pre>');
            // exit;


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
            if (empty($error)) {

                //$training = new DahTrainings();
                $training->setTrainingTitle($trainingTitle);
                $training->setTrainingDescription($trainingDescription);
                $training->setPublic($public);
                $training->setAssesment($assesment);
                $training->setUid($user);
                $training->setStatus('inactive');
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

                $this->get('session')->getFlashBag()->add('success', ' Training updated successfully.<br/> Please upload training videos.');
                return $this->redirect($this->generateUrl('_upload_video_trainings', array('tid' => $training->getTid())));
            }
        }
        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $this->data['error'] = $error;
        $this->data['departments'] = $totaldeps;
        $this->data['user'] = $user;
        $this->data['userinfo'] = $userinfo;
        $this->data['training'] = $training;
        //tabs control 
        $this->data['activetab'] = 'training';
        $this->data['videourl'] = $this->generateUrl('_upload_video_trainings', array('tid' => $tid));
        $this->data['inviteurl'] = $this->generateUrl('_invite_trainings', array('tid' => $tid));
        $this->data['enrollurl'] = $this->generateUrl('_enrollment_trainings', array('tid' => $tid));
        $this->data['assesmenturl'] = $this->generateUrl('_createassesment_trainings', array('tid' => $tid));
        $this->data['materialurl'] = $this->generateUrl('_teacher_upload_material', array('tid' => $tid));
        $this->data['tablevel'] = 5;
        if ($training->getPublic() == 'no') {
            //$this->data['invitetab'] = 'yes';
        }
        $this->data['enrolltab'] = 'yes';
        $this->data['mode'] = 'edit';
        return $this->data;
    }

    /**
     * @Route("/trainings/{page}", name="_trainingslist" , defaults={"page"=1})
     * @Template("DahBundle:Trainings:list.html.twig")
     */
    public function listAction(Request $request, $page) {
        $common = $this->get('common_handler');
        $limit = 6;
        $key = '';
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $todaysTimestamp = strtotime(date('d-m-Y'));
        $where = " where dt.status='active'  ";

        $key = trim($request->query->get('keyword'));
        $department = trim($request->query->get('department'));
        if ($request->query->get('reset') != '') {
            return $this->redirect($this->generateUrl('_news'));
        }
        if ($key != '') {

            $where .= " and ( training_title like '%" . $key . "%'  )  ";
        }
        if ($department != '') {

            $where .= " and ( dp.deptid = $department  )  ";
        }
        $statement = $connection->prepare("  select * from dah_trainings dt join dah_departments dp on dt.deptid = dp.deptid join dah_users du on du.uid = dt.uid  " . $where . " order by dt.featured desc limit " . (($page - 1) * $limit) . ",$limit  ");
        $statement->execute();
        $pages = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_trainings dt join dah_departments dp on dt.deptid = dp.deptid join dah_users du on du.uid = dt.uid " . $where . " ");
        $statement->execute();
        $totalcontentpages = $statement->fetchAll();
        $totalpages = ceil(count($totalcontentpages) / $limit);
        $paginate = $common->paginate('_trainingslist', array('keyword' => $key), $totalpages, $page, 5);
        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $this->data['departments'] = $totaldeps;
        $this->data['trainings'] = $pages;
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
     * @Route("/training/view/{tid}", name="_view_training",defaults={"tid"=0})
     * @Template("DahBundle:Trainings:view.html.twig")
     */
    public function viewNewsAction(Request $request, $tid) {
        $this->session = $request->getSession();
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainings');
        $training = $repository->findOneBy(
                array('tid' => $tid)
        );
        if (!$training) {
            return $this->redirect($this->generateUrl('_trainingslist'));
        }
        
        
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $training->setTview($training->getTview()+1);
        $em->persist($training);
        $em->flush();
        $statement = $connection->prepare("  select * from dah_training_videos where tid = " . $training->getTid() . " order by tvid asc ");
        $statement->execute();
        $totvid = $statement->fetchAll();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($training->getPublic() == 'no') {
                $this->session->set('referrer', $this->generateUrl('_view_training', array('tid' => $tid)));
                $this->data['publicpopup'] = 'yes';
            }
        }
        $statement = $connection->prepare("  select * from dah_training_material where dtid = " .$training->getTid() . " order by id asc ");
        $statement->execute();
        $tomat = $statement->fetchAll();
        $this->data['materials'] = $tomat;
        $this->data['training'] = $training;
        $this->data['videos'] = $totvid;
        // $this->data['metaTitle'] = $news->getNewsMetaTitle();
        //$this->data['metaDescription'] = $news->getNewsMetaDescription();
        //$this->data['metaKeywords'] = $news->getNewsMetaKeyword();
        return $this->data;
    }

    /**
     * @Route("/training/assesment/{tid}", name="_assesment_training",defaults={"tid"=0})
     * @Template("DahBundle:Trainings:assesment.html.twig")
     */
    public function viewassesmentsAction(Request $request, $tid) {
        $this->session = $request->getSession();
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainings');
        $training = $repository->findOneBy(
                array('tid' => $tid)
        );
        if (!$training) {
            return $this->redirect($this->generateUrl('_trainingslist'));
        }
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $user = $this->getUser();
        $error = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $statement = $connection->prepare("  select * from dah_training_videos where tid = " . $training->getTid() . " order by tvid asc ");
        $statement->execute();
        $totvid = $statement->fetchAll();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('_trainingslist'));
        }
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainingEnrollment');
        $dte = $repository->findOneBy(
                array('tid' => $tid, 'uid' => $user->getUid())
        );
        if (!$dte) {
            $this->get('session')->getFlashBag()->add('error', ' You have not enrolled to this training.');
            return $this->redirect($this->generateUrl('_trainingslist'));
        }
        $statement = $connection->prepare("  select * from dah_training_questions where tid = $tid order by qid desc ");
        $statement->execute();
        $trainingquestions = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_asses_info where tid = " . $training->getTid() . "  ");
        $statement->execute();
        $assesment = $statement->fetch();
        if (empty($assesment)) {
            $this->get('session')->getFlashBag()->add('error', ' Assesment is not prepared for this training.');
            return $this->redirect($this->generateUrl('_enrolled_trainings'));
        }
        $this->data['questions'] = $trainingquestions;
        $qopt = array();
        if ($request->getMethod() == 'POST') {
            $qopt = $request->request->get('qopt');
            if (empty($qopt)) {
                $error['options'] = 'Please answer atleast one question to complete this assesment';
            }
            if (empty($error)) {
                //  print('<pre>');
                //  print_r($_POST);
                //  print('</pre>');
                //  print('<pre>');
                //  print_r($trainingquestions);
                //  print('</pre>');
                //  print('<pre>');
                //  print_r($assesment);
                //  print('</pre>');
                //  exit;
                $maxmarks = isset($assesment['totalmarks']) ? $assesment['totalmarks'] : 0;
                $cuttoff = isset($assesment['cutoff']) ? $assesment['cutoff'] : 0;
                $correct = 0;
                $unassigned = 0;
                $attemptno = 1;

                foreach ($trainingquestions as $tq) {
                    if (array_key_exists($tq['qid'], $qopt)) {
                        echo "The 'first' element is in the array";
                        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainingQuestions');
                        $qidobj = $repository->findOneBy(
                                array('qid' => $tq['qid'])
                        );
                        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahQuestionOptions');
                        $opidobj = $repository->findOneBy(
                                array('opid' => $qopt[$tq['qid']])
                        );
                        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahUserAssesmentResults');
                        $duaidobj = $repository->findOneBy(
                                array('tid' => $tid, 'qid' => $tq['qid'], 'uid' => $user->getUid())
                        );
                        $statement = $connection->prepare(" select * from dah_training_key where qid = " . $tq['qid'] . "   ");
                        $statement->execute();
                        $qories = $statement->fetch();
                        $singleresult = '';
                        if (empty($qories)) {
                            $singleresult = 'unassined';
                            $unassigned++;
                        } else {
                            if ($qories['opid'] == $qopt[$tq['qid']]) {
                                $singleresult = 'correct';
                                $correct++;
                            } else {
                                $singleresult = 'incorrect';
                            }
                        }
                        if (!$duaidobj) {
                            $duaidobj = new DahUserAssesmentResults();
                        }
                        $duaidobj->setUid($user);
                        $duaidobj->setTid($training);
                        $duaidobj->setQid($qidobj);
                        $duaidobj->setOpid($opidobj);
                        $duaidobj->setRes($singleresult);
                        $em->persist($duaidobj);
                        $em->flush();
                    }
                }
                $repository = $this->getDoctrine()->getRepository('AdminBundle:DahAssesmentResult');
                $daridobj = $repository->findOneBy(
                        array('tid' => $tid, 'uid' => $user->getUid())
                );
                if (!$daridobj) {
                    $daridobj = new DahAssesmentResult();
                } else {
                    $attemptno = $daridobj->getAttemptNo() + 1;
                }
                $daridobj->setUid($user);
                $daridobj->setTid($training);
                $daridobj->setMaxMarks($maxmarks);
                $daridobj->setCorrect($correct);
                $daridobj->setUnassigned($unassigned);
                $daridobj->setAttemptNo($attemptno);
                $daridobj->setLastAssesOm(time());
                if ($correct >= $cuttoff) {
                    $daridobj->setResult('pass');
                } else {
                    $daridobj->setResult('failed');
                }
                $em->persist($daridobj);
                $em->flush(); // 
                if ($correct >= $cuttoff) {
                    $dte->setTrainingStatus('complete');
                    $em->persist($dte);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', ' Thankyou for completing the assesment.');
                return $this->redirect($this->generateUrl('_view_assesment_result', array('tid' => $tid)));
            }
        }


        $this->data['assesment'] = $assesment;
        $this->data['error'] = $error;
        $this->data['tid'] = $tid;
        $this->data['training'] = $training;
        $this->data['videos'] = $totvid;
        $this->data['mode'] = 'edit';
        // $this->data['metaTitle'] = $news->getNewsMetaTitle();
        //$this->data['metaDescription'] = $news->getNewsMetaDescription();
        //$this->data['metaKeywords'] = $news->getNewsMetaKeyword();
        return $this->data;
    }

    /**
     * @Route("/training/assesment_result/{tid}", name="_view_assesment_result",defaults={"tid"=0})
     * @Template("DahBundle:Trainings:assesRes.html.twig")
     */
    public function assesmentResultAction(Request $request, $tid) {
        $this->session = $request->getSession();
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainings');
        $training = $repository->findOneBy(
                array('tid' => $tid)
        );
        if (!$training) {
            return $this->redirect($this->generateUrl('_trainingslist'));
        }
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $user = $this->getUser();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('_trainingslist'));
        }
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahAssesmentResult');
        $daridobj = $repository->findOneBy(
                array('tid' => $tid, 'uid' => $user->getUid())
        );
        if (!$daridobj) {
            return $this->redirect($this->generateUrl('_trainingslist'));
        }
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainingEnrollment');
        $dte = $repository->findOneBy(
                array('tid' => $tid, 'uid' => $user->getUid())
        );
        if (!$dte) {
            $this->get('session')->getFlashBag()->add('error', ' You have not enrolled to this training.');
            return $this->redirect($this->generateUrl('_trainingslist'));
        } elseif ($dte->getTrainingStatus() == 'incomplete') {
            $this->get('session')->getFlashBag()->add('error', ' You have not completed this training.');
            return $this->redirect($this->generateUrl('_trainingslist'));
        }
        $this->data['trainingres'] = $daridobj;
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $statement = $connection->prepare("  select * from dah_training_videos where tid = " . $training->getTid() . " order by tvid asc ");
        $statement->execute();
        $totvid = $statement->fetchAll();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($training->getPublic() == 'no') {
                $this->session->set('referrer', $this->generateUrl('_view_training', array('tid' => $tid)));
                $this->data['publicpopup'] = 'yes';
            }
        }
        if ($request->getMethod() == 'POST') {
            if ($dte->getCertificateStatus() == 'issued') {
                $this->get('session')->getFlashBag()->add('success', ' You have already received the Certificate for this training.');
                return $this->redirect($this->generateUrl('_view_assesment_result', array('tid' => $tid)));
            } else {
                $dte->setCertificateStatus('requested');
                $em->persist($dte);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', ' Thankyou. Your request has been sent to our admin sucessfully.');
                return $this->redirect($this->generateUrl('_enrolled_trainings'));
            }
        }
        $this->data['training'] = $training;
        $this->data['videos'] = $totvid;
        // $this->data['metaTitle'] = $news->getNewsMetaTitle();
        //$this->data['metaDescription'] = $news->getNewsMetaDescription();
        //$this->data['metaKeywords'] = $news->getNewsMetaKeyword();
        return $this->data;
    }

    /**
     * @Route("/video-uploadify", name="_video_uploadify")
     */
    public function videouploadifyAction(Request $request) {
        $resp = false;
        $common = $this->get('common_handler');
        $limit = 5;
        $key = '';
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $newname = '';
        $tid = trim($request->request->get('tid'));
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainings');
        $training = $repository->findOneBy(
                array('tid' => $tid)
        );
        if (!empty($_FILES) && isset($_FILES['manualUpload']) && $_FILES['manualUpload']['size'] > 0) {
            $allowed = array('mp4', 'webm', 'flv');
            $tempFile = $_FILES['manualUpload']['tmp_name'];


            //$newname= $_FILES['imageupload']['name'];
            if (isset($tempFile) && !empty($tempFile) && $tempFile != '') {
                $fileParts = pathinfo($_FILES['manualUpload']['name']);
                $newname = 'vid_' . md5(uniqid('avt_')) . '.' . strtolower($fileParts['extension']);
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
                $tvid = new DahTrainingVideos();
                $tvid->setTid($training);
                $tvid->setVideo($newname);
                $tvid->setStatus('active');
                $em->persist($tvid);
                $em->flush();
                $connection = $em->getConnection();
                $statement = $connection->prepare("  select * from dah_training_videos where tvid = " . $tvid->getTvid() . " order by tvid asc ");
                $statement->execute();
                $totvid = $statement->fetchAll();
                $maildata = array();
                $maildata['videos'] = $totvid;
                $resp = $this->renderView('DahBundle:Trainings:videoStrip.html.twig', $maildata);
            }
        }


        $response = new Response();
        $response->setContent(json_encode($resp));
        $response->headers->set('Content-Type', 'json');

        return $response;
    }

    /**
     * @Route("/ajax/edit-video-titles", name="_edit_video_titles")
     */
    public function editvideotitlesAction(Request $request) {
        $response = new Response();
        $isAjax = $request->isXmlHttpRequest();
        // get the value of a $_POST parameter
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $em = $this->getDoctrine()->getEntityManager();

        $res = array('status' => 'error',
            'message' => $this->get('translator')->trans('server.messages.somethingWentWrong'),
            'response' => ''
        );
        if ($request->getMethod() == 'POST') {
            $params = array();
            $content = $this->get("request")->getContent();
            if (!empty($content)) {
                $params = json_decode($content, true); // 2nd param to get as array
            }
            $tvid = isset($params['tvid']) ? $params['tvid'] : 0;
            $title = isset($params['title']) ? trim($params['title']) : '';
            // echo " $tvid $title ";
            //print_r($params);
            //exit;
            if ($tvid == '') {
                $res['message'] = "Please select valid video";
            } else {
                $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainingVideos');
                $tvidios = $repository->findOneByTvid($tvid);
                if ($tvidios) {
                    $res['message'] = $this->get('translator')->trans('server.messages.somethingWentWrong');

                    $tvidios->setVideoTitle($title);
                    $em->persist($tvidios);
                    $em->flush();

                    $res['message'] = "Title updated";
                    $res['status'] = 'success';
                    $res['response'] = $title;
                }
            }
        }

        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/ajax/training/delete", name="_training_delete")
     */
    public function trainingDeleteAction(Request $request) {


        $response = new Response();
        $common = $this->get('common_handler');
        $isAjax = $request->isXmlHttpRequest();
        // get the value of a $_POST parameter
        $tid = $request->request->get('tid');
        $em = $this->getDoctrine()->getEntityManager();
        $training = $em->getRepository('AdminBundle:DahTrainings')->find($tid);
        if ($training) {

            $connection = $em->getConnection();
            $statement = $connection->prepare("  select * from dah_training_videos where tid = " . $training->getTid() . " order by tvid asc ");
            $statement->execute();
            $totvid = $statement->fetchAll();
            foreach ($totvid as $vid) {
                $targetPath = './' . 'uploads' . '/';
                $targetFile = $targetPath . $vid['video'];
                @unlink($targetFile);
            }
            $em->remove($training);

            $em->flush();


            $res = array('status' => 'success',
                'message' => 'Training has been deleted Successfully',
                'response' => ''
            );
        } else {
            $res = array('status' => 'failed',
                'message' => 'went wrong',
                'response' => ''
            );
        }
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/ajax/video/delete", name="video_delete")
     */
    public function videoDeleteAction(Request $request) {

        $response = new Response();
        $common = $this->get('common_handler');
        $isAjax = $request->isXmlHttpRequest();
        // get the value of a $_POST parameter
        $tvid = $request->request->get('tvid');
        $em = $this->getDoctrine()->getEntityManager();
        $contentpage = $em->getRepository('AdminBundle:DahTrainingVideos')->find($tvid);
        if ($contentpage) {
            $targetPath = './' . 'uploads' . '/';
            $targetFile = $targetPath . $contentpage->getVideo();
            @unlink($targetFile);
            $em->remove($contentpage);

            $em->flush();


            $res = array('status' => 'success',
                'message' => 'Video has been deleted Successfully',
                'response' => ''
            );
        } else {
            $res = array('status' => 'failed',
                'message' => 'went wrong',
                'response' => ''
            );
        }
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    /**
     * @Route("/ajax/wvideo/delete", name="wvideo_delete")
     */
    public function videoWDeleteAction(Request $request) {

        $response = new Response();
        $common = $this->get('common_handler');
        $isAjax = $request->isXmlHttpRequest();
        // get the value of a $_POST parameter
        $tvid = $request->request->get('tvid');
        $em = $this->getDoctrine()->getEntityManager();
        $contentpage = $em->getRepository('AdminBundle:DahWorkshopVideos')->find($tvid);
        if ($contentpage) {
            $targetPath = './' . 'uploads' . '/';
            $targetFile = $targetPath . $contentpage->getVideo();
            @unlink($targetFile);
            $em->remove($contentpage);

            $em->flush();


            $res = array('status' => 'success',
                'message' => 'Video has been deleted Successfully',
                'response' => ''
            );
        } else {
            $res = array('status' => 'failed',
                'message' => 'went wrong',
                'response' => ''
            );
        }
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/ajax/enroll_to_training", name="enroll_to_training")
     */
    public function enrollToTrainingAction(Request $request) {
        $response = new Response();
        $isAjax = $request->isXmlHttpRequest();
        // get the value of a $_POST parameter
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $em = $this->getDoctrine()->getEntityManager();
        $this->session = $request->getSession();
        $res = array('status' => 'error',
            'message' => $this->get('translator')->trans('server.messages.somethingWentWrong'),
            'response' => ''
        );
        if ($request->getMethod() == 'POST') {
            $tid = trim($request->request->get('tid'));
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainings');
            $training = $repository->findOneBy(
                    array('tid' => $tid)
            );
            if ($training) {
                if ($tid > 0) {
                    if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {


                        $user = $this->getUser();
                        if (!$user) {
                            $res['message'] = $this->get('translator')->trans("server.messages.somethingWentWrong");
                        } else {
                            if ($user->getUid() == $training->getUid()->getUid()) {
                                $res['message'] = "You cannot enroll to your own training.";
                            } else {
                                $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainingEnrollment');
                                $enroll = $repository->findOneBy(
                                        array('uid' => $user->getUid(), 'tid' => $tid)
                                );
                                if (!$enroll) {
                                    $resetdata = new DahTrainingEnrollment();
                                    $resetdata->setUid($user);
                                    $resetdata->setTid($training);
                                    $resetdata->setTrainingStatus('incomplete');
                                    $resetdata->setCertificateStatus('notIssued');
                                    $em->persist($resetdata);
                                    $em->flush();

                                    $res['message'] = $this->get('translator')->trans("training.ThankyouforenrollingtothisTrainnig");
                                    $res['status'] = 'success';
                                } else {
                                    $res['message'] = $this->get('translator')->trans("training.YouarealreadyenrolledforthisTraining");
                                    $res['status'] = 'success';
                                }
                            }
                        }
                    } else {
                        $this->session->set('referrer', $this->generateUrl('_view_training', array('tid' => $tid)));
                        $res['message'] = 'Please <a href="' . $this->generateUrl('_home') . '">Login</a> or <a href="' . $this->generateUrl('_signup_student') . '">Signup</a> to enroll to this Training. ';
                    }
                }
            }
        }

        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/account/enrolled_trainings/{page}", name="_enrolled_trainings", defaults={"page"=1} )
     * @Template("DahBundle:Trainings:entrainings.html.twig")
     */
    public function enrolledTrainingsAction(Request $request, $page) {
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
        //  $ref = $session->get('referrer');
        //  if ($ref != '') {
        //      $session->remove('referrer');
        //      return $this->redirect($ref);
        //  }
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

            $where .= " and ( training_title like '%" . $key . "%'   ) ";
        }
        $statement = $connection->prepare("  select *,du.uid as userid  from dah_training_enrollment te join dah_trainings dt on te.tid = dt.tid join dah_users du on du.uid = dt.uid join dah_departments dp on dt.deptid = dp.deptid  " . $where . " order by dt.added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $trainings = $statement->fetchAll();
        $statement = $connection->prepare("  select *  from dah_training_enrollment te join dah_trainings dt on te.tid = dt.tid join dah_users du on du.uid = dt.uid join dah_departments dp on dt.deptid = dp.deptid " . $where . " ");
        $statement->execute();
        $totalcontentpages = $statement->fetchAll();
        $totalpages = ceil(count($totalcontentpages) / $limit);
        $paginate = $common->paginate('_enrolled_trainings', array('keyword' => $key), $totalpages, $page, 5);
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
     * @Route("account/rendered_training_pdf/{tid}", name="_rendered_outbnd_pdf", defaults={"tid"=1})
     */
    public function renderedObPdf(Request $request, $tid) {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $pdf = $this->get("white_october.tcpdf")->create();
        $dt = array(
            'name' => 'name',
            'training' => 'training',
            'trainingtime' => 'trainingtime'
        );
        $html = $this->renderView('DahBundle:Default:rendered_pdf.html.twig', $dt);
        //echo $html;
        //exit;
        // Retire le footer/header par défaut, contenant les barres de margin
        $pdf->setPrintFooter(false);
        $pdf->setPrintHeader(false);
        // set margins
        $pdf->SetMargins(0, 0, 0, true);
        $pdf->SetFont('aealarabiya', '', 18);
        // set auto page breaks false
        $pdf->SetAutoPageBreak(false, 0);

        // add a page
        $pdf->AddPage('L', 'A5');
        $targetPath = './' . 'assets/img' . '/training.png';
        // Display image on full page
        $pdf->Image($targetPath, 0, 0, 425, 300, 'png', '', '', true, 200, '', false, false, 0, false, false, true);
        // $html = '<span style="color:white;text-align:center;font-weight:bold;font-size:80pt;">PAGE 3</span>';
        $pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------
//Close and output PDF document
        $pdf->Output(md5(uniqid()) . '.pdf', 'I');
        // $pdf->writeHTML($html, true, false, false, false, '');
        //$pdf->Output('In-Bound-Calls-' . '-' . date('d-m-Y') . '.pdf', 'D');
    }

    /**
     * @Route("account/rendered_workshop_pdf/{wid}", name="_rendered_work_pdf", defaults={"wid"=1})
     */
    public function renderedWkPdf(Request $request, $wid) {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $pdf = $this->get("white_october.tcpdf")->create();
        $dt = array(
            'name' => 'name',
            'workshop' => 'workshop',
            'from' => 'from',
            'to' => 'to'
        );
        
        $html = $this->renderView('DahBundle:Default:workshoprendered_pdf.html.twig', $dt);
        //echo $html;
        //exit;
        // Retire le footer/header par défaut, contenant les barres de margin
        $pdf->setPrintFooter(false);
        $pdf->setPrintHeader(false);
        // set margins
        $pdf->SetMargins(0, 0, 0, true);
        $pdf->SetFont('aealarabiya', '', 18);
        // set auto page breaks false
        $pdf->SetAutoPageBreak(false, 0);

        // add a page
        $pdf->AddPage('L', 'A5');
        $targetPath = './' . 'assets/img' . '/some.jpg';
        // Display image on full page
        $pdf->Image($targetPath, 0, 0, 425, 300, 'JPG', '', '', true, 200, '', false, false, 0, false, false, true);
        // $html = '<span style="color:white;text-align:center;font-weight:bold;font-size:80pt;">PAGE 3</span>';
        $pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------
//Close and output PDF document
        $pdf->Output(md5(uniqid()) . '.pdf', 'I');
        // $pdf->writeHTML($html, true, false, false, false, '');
        //$pdf->Output('In-Bound-Calls-' . '-' . date('d-m-Y') . '.pdf', 'D');
    }

    /**
     * @Route("/ajax/question/delete", name="_question_delete")
     */
    public function ajaxquestionDeleteAction(Request $request) {

        $response = new Response();
        $common = $this->get('common_handler');
        $isAjax = $request->isXmlHttpRequest();
        // get the value of a $_POST parameter
        $qid = $request->request->get('qid');
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $contentpage = $em->getRepository('AdminBundle:DahTrainingQuestions')->find($qid);
        if ($contentpage) {
            $res = array('status' => 'success',
                'message' => 'Question has been deleted Successfully',
                'response' => ''
            );
            $tid = $contentpage->getTid()->getTid();


            $em->remove($contentpage);

            $em->flush();

            $statement = $connection->prepare("  select sum(marks) as marks from dah_training_questions where tid = " . $contentpage->getTid()->getTid() . "  ");
            $statement->execute();
            $sum = $statement->fetch();
            $qassinfo = $em->getRepository('AdminBundle:DahAssesInfo')
                    ->findOneByTid($contentpage->getTid()->getTid());
            if (!$qassinfo) {
                $qassinfo = new DahAssesInfo();
            }
            $qassinfo->setTotalmarks($sum['marks']);
            $em->persist($qassinfo);
            $em->flush();
            $res['marks'] = $sum['marks'];
            if ($qassinfo->getCutoff() < $sum['marks']) {
                $res['selco'] = $qassinfo->getCutoff();
            } else {
                $res['selco'] = 0;
            }
        } else {
            $res = array('status' => 'failed',
                'message' => 'Something went wrong',
                'response' => ''
            );
        }
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route("/account/training/uploadmaterial/{tid}", name="_teacher_upload_material" , defaults={"tid"=0})
     * @Template("DahBundle:Trainings:uploadmaterial.html.twig")
     */
    public function addTrainingMaterialAction(Request $request,$tid) {
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
                array('tid' => $tid, 'uid' => $user->getUid())
        );
        $this->data['training'] = $training;
        $this->data['materials'] = $em->getRepository('AdminBundle:DahTrainingMaterial')
                                                ->findBy( array('dtid' => $tid, 'status'=> 'active'),array('id'=>'DESC'));
        
       // $this->data['mode'] = 'add';
       
        $materialupload = $request->request->get('materialupload');                        
        if ($user->getRole() == 'ROLE_STUDENT') {
            $this->get('session')->getFlashBag()->add('success', 'You are not elligible to add trainings material.');
            return $this->redirect($this->generateUrl('_home'));
        }
        if ($user->getRole() == 'ROLE_TEACHER') {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTeachersInfo');
        } else {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahStudentInfo');
        }
        $userinfo = $repository->findOneByUid($user->getUid());
        
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
             return $this->redirect($this->generateUrl('_teacher_upload_material')."/".$tid);

         }   
         }
        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $this->data['error'] = $error;
        $this->data['departments'] = $totaldeps;
        $this->data['user'] = $user;
        $this->data['userinfo'] = $userinfo;
        $this->data['training'] = $training;
        //tabs control 
        $this->data['activetab'] = 'material';
        $this->data['tablevel'] = 5;
        $this->data['assesmenturl'] = $this->generateUrl('_createassesment_trainings', array('tid' => $tid));
        $this->data['videourl'] = $this->generateUrl('_upload_video_trainings', array('tid' => $tid));
        $this->data['inviteurl'] = $this->generateUrl('_invite_trainings', array('tid' => $tid));
        $this->data['enrollurl'] = $this->generateUrl('_enrollment_trainings', array('tid' => $tid));
        $this->data['trainingurl'] = $this->generateUrl('_edit_training', array('tid' => $tid));
        $this->data['materialurl'] = $this->generateUrl('_teacher_upload_material', array('tid' => $tid));
        if ($training->getPublic() == 'no') {
            // $this->data['invitetab'] = 'yes';
        }        
        $this->data['enrolltab'] = 'yes';
        $this->data['tid'] = $tid;
        $this->data['mode'] = 'edit';
        $this->data['error'] = $error;
        return $this->data;
    }

}
