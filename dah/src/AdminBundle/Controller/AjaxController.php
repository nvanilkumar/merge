<?php

/**
 * @desc this is the contact class for admin panel
 * examples include manageAction(),addNewAction(),editNewAction($clientid)
 * @author Upendra upendramanve@gmail.com , upendrakumar@cestechservices.com
 * @CreatedDate    04-Aug-2015
 * @LastModifyDate 04-Aug-2015
 */

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use AdminBundle\Entity\DahDepartments;
use AdminBundle\Entity\DahTrainings;
use AdminBundle\Entity\DahTrainingVideos;
use AdminBundle\Entity\DahTrainingEnrollment;
use AdminBundle\Entity\DahAssesInfo;
use AdminBundle\Entity\DahQuestionOptions;
use AdminBundle\Entity\DahTrainingKey;
use AdminBundle\Entity\DahTrainingQuestions;
use AdminBundle\Entity\DahAssesmentResult;
use AdminBundle\Entity\DahUserAssesmentResults;
use AdminBundle\Entity\DahWorkshopVideos;

class AjaxController extends Controller {

    public $title = "Administrator login";
    public static $template = "AdminBundle:Templates:default.html.twig";
    public $data = array();

    public function __construct() {
        $this->data['extend_view'] = self::$template;
    }

    /**
     * @Route("/secure/ajax/file/upload", name="_ajx_save_file_url")
     */
    public function ajx_save_file_url(Request $request) {

        $targetFolder = $request->request->get('folder');
        if (!empty($_FILES)) {
            $tempFile = $_FILES['Filedata']['tmp_name'];
            $fileParts = pathinfo($_FILES['Filedata']['name']);
            $newname = time() . '.' . strtolower($fileParts['extension']);
            $targetPath = WEB_DIRECTORY . '/' . $targetFolder . '/';
            $targetFile = $targetPath . $newname;
            //$finfo = new \finfo(FILEINFO_MIME_TYPE);
            //  $mimeType = $finfo->buffer(file_get_contents($tempFile)); 

            move_uploaded_file($tempFile, $targetFile);
        }
        return new Response($newname);
    }

    /**
     * @Route("/secure/ajax/department/add", name="_ajax_add_department")
     */
    public function ajaxAddDepartmentAction(Request $request) {
        $response = new Response();
        $error = array();
        $common = $this->get('common_handler');
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == 'POST') {
            $dep = trim($request->request->get('Department'));
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahDepartments');
            $existingDepartment = $repository->findBy(
                    array('department' => $dep)
            );
            if ($dep == '') {
                $error['Department'] = 'Please enter Department';
                $error['Department'] = 'Please enter Department';
                $res = array('status' => 'failed',
                    'message' => $error['Department'],
                    'response' => 'Please enter Department'
                );
                $response->setContent(json_encode($res));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
            if (count($existingDepartment) > 0) {
                $error['Department'] = 'Department already exists';
                $res = array('status' => 'failed',
                    'message' => 'department already exists',
                    'response' => 'department already exists'
                );
                $response->setContent(json_encode($res));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }

            if (empty($error)) {
                $admin = new DahDepartments();
                $admin->setDepartment($dep);
                $admin->setAddedOn(time());
                $admin->setUpdatedOn(time());
                $admin->setStatus('active');
                $em->persist($admin);
                $em->flush();

                $common->logActivity('added department <a href="' . $this->generateUrl('_admin_departments') . '">' . $dep . '</a>');

                // $this->get('session')->getFlashBag()->add('success', ' New department added successfully.');
                $respns = '<tr id="dep-' . $admin->getDeptid() . '"> <td class="text-right department">' . $admin->getDepartment() . '</td>   <td class="text-center"><a href="' . $this->generateUrl('_admin_add_new_dep', array('depid' => $admin->getDeptid())) . '" data-depid="' . $admin->getDeptid() . '" data-toggle="modal"  data-target="#myModal" title="Edit" class="btn btn-xs btn-default "><i class="fa fa-pencil"></i> </a> <a href="javascript:;" title="Delete" class="btn btn-xs btn-danger delete"><i class="fa fa-trash"></i> </a></td> </tr> ';
                $res = array('status' => 'success',
                    'message' => 'Department has been added Successfully',
                    'response' => $respns
                );
            } else {
                $res = array('status' => 'failed',
                    'message' => $error['Department'],
                    'response' => $error['Department']
                );
            }
            $response->setContent(json_encode($res));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    /**
     * @Route("/secure/ajax/dept/delete", name="dept_delete")
     */
    public function ajaxDeptDeleteAction(Request $request) {
        $response = new Response();
        $isAjax = $request->isXmlHttpRequest();
        // get the value of a $_POST parameter
        $common = $this->get('common_handler');
        $deptid = $request->request->get('deptId');
        $em = $this->getDoctrine()->getEntityManager();
        $dept = $em->getRepository('AdminBundle:DahDepartments')->find($deptid);
        $em->remove($dept);
        $em->flush();
        $common->logActivity('deleted department <a href="' . $this->generateUrl('_admin_departments') . '">' . $request->request->get('Department') . '</a>');

        $res = array('status' => 'success',
            'message' => 'department has been deleted successfully',
            'response' => ''
        );
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/secure/ajax/dept/edit", name="_ajax_edit_department")
     */
    public function ajaxDeptEditAction(Request $request) {
        $response = new Response();
        $common = $this->get('common_handler');
        $em = $this->getDoctrine()->getManager();
        $dep = trim($request->request->get('Department'));
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahDepartments');
        $existingDepartment = $repository->findBy(array('department' => $dep));

        if ($dep == '') {
            $error['Department'] = 'Please enter Department';
            $res = array('status' => 'failed',
                'message' => $error['Department'],
                'response' => 'Please enter Department'
            );
            $response->setContent(json_encode($res));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        if (count($existingDepartment) > 0) {
            $error['Department'] = 'Department link already exists';
            $res = array('status' => 'failed',
                'message' => 'department already exists',
                'response' => 'department already exists'
            );
            $response->setContent(json_encode($res));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        if (empty($error)) {
            $deptid = $request->request->get('deptid');
            $em = $this->getDoctrine()->getEntityManager();
            $dept = $em->getRepository('AdminBundle:DahDepartments')->find($deptid);
            $dept->setDepartment($dep);
            $dept->setUpdatedOn(time());
            $dept->setStatus('active');
            $em->persist($dept);
            $em->flush();
            $common->logActivity('updated department <a href="' . $this->generateUrl('_admin_departments') . '">' . $dep . '</a>');
            $respns = '<td class="text-right department">' . $dept->getDepartment() . '</td>   <td class="text-center"><a href="' . $this->generateUrl('_admin_add_new_dep', array('depid' => $dept->getDeptid())) . '" data-depid="' . $dept->getDeptid() . '" data-toggle="modal"  data-target="#myModal" title="Edit" class="btn btn-xs btn-default "><i class="fa fa-pencil"></i> </a> <a href="javascript:;" title="Delete" data-depid="' . $dept->getDeptid() . '" class="btn btn-xs btn-danger delete"><i class="fa fa-trash"></i> </a></td>  ';

            $res = array('status' => 'success',
                'message' => 'Department has been updated Successfully',
                'response' => $respns
            );
        } else {
            $res = array('status' => 'failed',
                'message' => '',
                'response' => $error['Department']
            );
        }
        $response->setContent(json_encode($res));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/secure/ajax/pages/delete", name="page_delete")
     */
    public function ajaxPageDeleteAction(Request $request) {

        $response = new Response();
        $common = $this->get('common_handler');
        $isAjax = $request->isXmlHttpRequest();
        // get the value of a $_POST parameter
        $contentid = $request->request->get('contentid');
        $em = $this->getDoctrine()->getEntityManager();
        $contentpage = $em->getRepository('AdminBundle:DahContentPages')->find($contentid);
        $em->remove($contentpage);
        $common->logActivity('deleted page <a href="' . $this->generateUrl('_admin_pages') . '">' . $request->request->get('pageName') . '</a>');

        $em->flush();


        $res = array('status' => 'Success',
            'message' => 'content Page has been deleted Successfully',
            'response' => ''
        );
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/secure/ajax/subscribers/status", name="subscribers_status")
     */
    public function ajaxCSStatusAction(Request $request) {
        $response = new Response();
        $common = $this->get('common_handler');

        $isAjax = $request->isXmlHttpRequest();
        $subId = $request->request->get('subId');
        $status = $request->request->get('status');

        $status = ($status == 'active') ? 'inactive' : 'active';
        $em = $this->getDoctrine()->getEntityManager();
        $contentpage = $em->getRepository('AdminBundle:DahNewsletterSubscribers')->find($subId);
        $contentpage->setStatus($status);
        $em->persist($contentpage);
        $em->flush();
        $res = array('status' => 'success',
            'message' => 'Subscribers status has been changed Successfully',
            'response' => array('status' => $status)
        );
        if ($status == 'inactive') {
            $status = 'Un subscribed';
        } else {
            $status = 'Subscribed';
        }
        $common->logActivity('changed Subscriber <a href="' . $this->generateUrl('_admin_subsribers') . '">' . $contentpage->getEmail() . '</a> status changed to <strong>' . $status . '</strong> ');
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/secure/ajax/subscriber/delete", name="subscribres_delete")
     */
    public function ajaxSubscriberDeleteAction(Request $request) {

        $response = new Response();
        $common = $this->get('common_handler');
        $isAjax = $request->isXmlHttpRequest();
        // get the value of a $_POST parameter
        $contentid = $request->request->get('contentid');
        $em = $this->getDoctrine()->getEntityManager();
        $contentpage = $em->getRepository('AdminBundle:DahNewsletterSubscribers')->find($contentid);
        $em->remove($contentpage);
        $common->logActivity('deleted subscriber <a href="' . $this->generateUrl('_admin_subsribers') . '">' . $contentpage->getEmail() . '</a>');

        $em->flush();


        $res = array('status' => 'Success',
            'message' => 'subscriber has been deleted Successfully',
            'response' => ''
        );
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/secure/ajax/faq/delete", name="faq_delete")
     */
    public function ajaxFaqDeleteAction(Request $request) {
        $response = new Response();

        $common = $this->get('common_handler');

        $isAjax = $request->isXmlHttpRequest();
        $faqid = $request->request->get('faqid');

        $em = $this->getDoctrine()->getEntityManager();
        $faq = $em->getRepository('AdminBundle:DahFaqs')->find($faqid);
        $em->remove($faq);
        $em->flush();
        $common->logActivity('deleted FAQ<a href="' . $this->generateUrl('_admin_faqs') . '">' . $faq->getQuestion() . '</a> ');
        $res = array('status' => 'success',
            'message' => 'Faq deleted Successfully',
            'response' => ''
        );
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/secure/ajax/faq/status", name="faq_status")
     */
    public function ajaxFaqStatusAction(Request $request) {
        $response = new Response();
        $common = $this->get('common_handler');
        $isAjax = $request->isXmlHttpRequest();
        $faqid = $request->request->get('faqid');
        $status = $request->request->get('status');
        $status = ($status == 'active') ? 'inactive' : 'active';
        $em = $this->getDoctrine()->getEntityManager();
        $faq = $em->getRepository('AdminBundle:DahFaqs')->find($faqid);
        $faq->setStatus($status);
        $em->persist($faq);
        $em->flush();
        $common->logActivity('changed status <a href="' . $this->generateUrl('_admin_faqs') . '">' . $faq->getQuestion() . '</a>  to <strong>' . $status . '</strong> ');
        $res = array('status' => 'success',
            'message' => 'Status been changed Successfully',
            'response' => array('status' => $status)
        );
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/secure/ajax/user/delete", name="student_delete")
     */
    public function ajaxStudentDeleteAction(Request $request) {

        $response = new Response();
        $common = $this->get('common_handler');
        $isAjax = $request->isXmlHttpRequest();
        $uid = $request->request->get('uid');
        $em = $this->getDoctrine()->getEntityManager();
        $student = $em->getRepository('AdminBundle:DahUsers')->find($uid);
        $em->remove($student);
        $em->flush();
        $common->logActivity('deleted user <a href="' . $this->generateUrl('_admin_students') . '">' . $student->getFname() . ' ' . $student->getLname() . '</a> ');
        $res = array('status' => 'success',
            'message' => 'User deleted Successfully',
            'response' => ''
        );
        $response->setContent(json_encode($res));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/secure/ajax/user/status", name="student_status")
     */
    public function ajaxStudentStatusAction(Request $request) {
        $response = new Response();
        $common = $this->get('common_handler');
        $isAjax = $request->isXmlHttpRequest();
        $uid = $request->request->get('uid');
        $status = $request->request->get('status');
        $em = $this->getDoctrine()->getEntityManager();
        $student = $em->getRepository('AdminBundle:DahUsers')->find($uid);
        if ($student->getStatus() == 'active') {
            $status = 'inactive';
            $is_active = 0;
        } else {
            $status = 'active';
            $is_active = 1;
        }

        $student->setStatus($status);
        $student->setIsActive($is_active);
        $em->persist($student);
        $em->flush();
        $common->logActivity('changed status of user <a href="' . $this->generateUrl('_admin_students') . '">' . $student->getFname() . ' ' . $student->getLname() . '</a>  to <strong>' . $status . '</strong> ');
        $res = array('status' => 'success',
            'message' => 'Status been changed Successfully',
            'response' => array('status' => $status)
        );
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/secure/ajax/news/delete", name="news_delete")
     */
    public function ajaxNewsDeleteAction(Request $request) {

        $response = new Response();
        $common = $this->get('common_handler');
        $isAjax = $request->isXmlHttpRequest();
        // get the value of a $_POST parameter
        $contentid = $request->request->get('contentid');
        $em = $this->getDoctrine()->getEntityManager();
        $contentpage = $em->getRepository('AdminBundle:DahNews')->find($contentid);
        $em->remove($contentpage);
        $common->logActivity('deleted news <a href="' . $this->generateUrl('_admin_news') . '">' . $request->request->get('pageName') . '</a>');

        $em->flush();


        $res = array('status' => 'success',
            'message' => 'News has been deleted Successfully',
            'response' => ''
        );
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/secure/ajax/workshop/delete", name="workshop_delete")
     */
    public function ajaxWorkshopDeleteAction(Request $request) {

        $response = new Response();
        $common = $this->get('common_handler');
        $isAjax = $request->isXmlHttpRequest();
        // get the value of a $_POST parameter
        $contentid = $request->request->get('contentid');
        $em = $this->getDoctrine()->getEntityManager();
        $contentpage = $em->getRepository('AdminBundle:DahWorkshops')->find($contentid);
        $em->remove($contentpage);
        $common->logActivity('deleted workshop <a href="' . $this->generateUrl('_admin_workshop') . '">' . $request->request->get('pageName') . '</a>');

        $em->flush();


        $res = array('status' => 'success',
            'message' => 'Workshop has been deleted Successfully',
            'response' => ''
        );
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/secure/ajax/training/delete", name="training_delete")
     */
    public function ajaxtrainingDeleteAction(Request $request) {

        $response = new Response();
        $common = $this->get('common_handler');
        $isAjax = $request->isXmlHttpRequest();
        // get the value of a $_POST parameter
        $contentid = $request->request->get('contentid');
        $em = $this->getDoctrine()->getEntityManager();
        $contentpage = $em->getRepository('AdminBundle:DahTrainings')->find($contentid);
        $em->remove($contentpage);
        $common->logActivity('deleted training <a href="' . $this->generateUrl('_admin_trainings') . '">' . $request->request->get('pageName') . '</a>');

        $em->flush();


        $res = array('status' => 'success',
            'message' => 'Traininig has been deleted Successfully',
            'response' => ''
        );
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/secure/ajax/training/status", name="training_status")
     */
    public function ajaxTrainingstatsAction(Request $request) {
        $response = new Response();
        $common = $this->get('common_handler');

        $isAjax = $request->isXmlHttpRequest();
        $tid = $request->request->get('tid');
        // $status = $request->request->get('status');


        $em = $this->getDoctrine()->getEntityManager();
        $contentpage = $em->getRepository('AdminBundle:DahTrainings')->find($tid);
        if ($contentpage) {
            $status = ($contentpage->getStatus() == 'active') ? 'inactive' : 'active';
            $contentpage->setStatus($status);
            $em->persist($contentpage);
            $em->flush();
            $res = array('status' => 'success',
                'message' => 'Training status has been changed Successfully',
                'response' => array('status' => $status)
            );
        } else {
            $res = array('status' => 'failed',
                'message' => 'Training status has been changed Successfully',
                'response' => ''
            );
        }
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/secure/ajax/training/featured", name="training_featured")
     */
    public function ajaxTrainingfeaturedAction(Request $request) {
        $response = new Response();
        $common = $this->get('common_handler');

        $isAjax = $request->isXmlHttpRequest();
        $tid = $request->request->get('tid');
        // $status = $request->request->get('status');


        $em = $this->getDoctrine()->getEntityManager();
        $contentpage = $em->getRepository('AdminBundle:DahTrainings')->find($tid);
        if ($contentpage) {
            $status = ($contentpage->getFeatured() == 1) ? 0 : 1;
            $contentpage->setFeatured($status);
            $em->persist($contentpage);
            $em->flush();
            $res = array('status' => 'success',
                'message' => 'Training status has been changed Successfully',
                'response' => array('status' => $status)
            );
        } else {
            $res = array('status' => 'failed',
                'message' => 'Training status has been changed Successfully',
                'response' => ''
            );
        }
        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("secure/ajax/add_assess_qa", name="_admin_ajax_add_mcq")
     */
    public function adminajaxAddMcqAction(Request $request) {
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
                                            //       ->findOneByOptions($answer);
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

                                                $resp = $this->renderView('AdminBundle:Trainings:questionEStrip.html.twig', $maildata);
                                            } else {
                                                $trainingquestions = $statement->fetch();
                                                $maildata['question'] = $trainingquestions;

                                                $resp = $this->renderView('AdminBundle:Trainings:questionEdStrip.html.twig', $maildata);
                                            }
                                            $statement = $connection->prepare("  select sum(marks) as marks from dah_training_questions where tid = " . $training->getTid() . "  ");
                                            $statement->execute();
                                            $sum = $statement->fetch();
                                            $qassinfo = $em->getRepository('AdminBundle:DahAssesInfo')
                                                    ->findOneByTid($tid);
                                            if (!$qassinfo) {
                                                $qassinfo = new DahAssesInfo();
                                            }
                                            $qassinfo->setTid($training);
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

                                            $resp = $this->renderView('AdminBundle:Trainings:questionEStrip.html.twig', $maildata);
                                        } else {
                                            $trainingquestions = $statement->fetch();
                                            $maildata['question'] = $trainingquestions;

                                            $resp = $this->renderView('AdminBundle:Trainings:questionEdStrip.html.twig', $maildata);
                                        }
                                        $statement = $connection->prepare("  select sum(marks) as marks from dah_training_questions where tid = " . $training->getTid() . "  ");
                                        $statement->execute();
                                        $sum = $statement->fetch();
                                        $qassinfo = $em->getRepository('AdminBundle:DahAssesInfo')
                                                ->findOneByTid($tid);
                                        if (!$qassinfo) {
                                            $qassinfo = new DahAssesInfo();
                                        }
                                        $qassinfo->setTid($training);
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
                        //$this->session->set('referrer', $this->generateUrl('_view_workshop', array('wid' => $wid)));
                        //$res['message'] = 'Please <a href="' . $this->generateUrl('_home') . '">Login</a> or <a href="' . $this->generateUrl('_signup_student') . '">Signup</a> to enroll to this Workshop. ';
                    }
                }
            }
        }

        $response->setContent(json_encode($res));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/secure/ajax/getquestiondet", name="_admin_getquestiondet")
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
     * @Route("/secure/ajax/settrainingassesdet", name="_admin_settrainingassesdet")
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
        //echo $tid.' '.$cut;
        //exit;
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
                $qassinfo = $em->getRepository('AdminBundle:DahAssesInfo')
                        ->findOneByTid($tid);
                if (!$qassinfo) {
                    $qassinfo = new DahAssesInfo();
                }
                $qassinfo->setTid($training);
                $qassinfo->setCutoff($cut);
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
     * @Route("/secure/ajax/question/delete", name="_admin_question_delete")
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
     * @Route("/ajax/video-uploadify", name="_admin_video_uploadify")
     */
    public function videouploadifyAction(Request $request) {
        $resp = false;
          //$targetPath = WEB_DIRECTORY . '/' . 'uploads' . '/';  
         // echo $targetPath;
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
                //  $targetPath = './' . 'uploads' . '/'; 
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
                $resp = $this->renderView('AdminBundle:Trainings:videoStrip.html.twig', $maildata);
            }
        }


        $response = new Response();
        $response->setContent(json_encode($resp));
        $response->headers->set('Content-Type', 'json');

        return $response;
    }
    
    /**
     * @Route("/ajax/video-work-uploadify", name="_admin_video_work_uploadify")
     */
    public function videouploadifyworkAction(Request $request) {
        $resp = false;
          //$targetPath = WEB_DIRECTORY . '/' . 'uploads' . '/';  
         // echo $targetPath;
        $common = $this->get('common_handler');
        $limit = 5;
        $key = '';
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $newname = '';
        $wid = trim($request->request->get('wid'));
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahWorkshops');
        $workshop = $repository->findOneBy(
                array('wid' => $wid)
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
                $tvid = new DahWorkshopVideos();
                $tvid->setWid($workshop);
                $tvid->setVideo($newname);
                $tvid->setStatus('active');
                $em->persist($tvid);
                $em->flush();
                $connection = $em->getConnection();
                $statement = $connection->prepare("  select * from dah_workshop_videos where tvid = " . $tvid->getTvid() . " order by tvid asc ");
                $statement->execute();
                $totvid = $statement->fetchAll();
                $maildata = array();
                $maildata['videos'] = $totvid;
                $resp = $this->renderView('AdminBundle:Workshop:videoStrip.html.twig', $maildata);
            }
        }


        $response = new Response();
        $response->setContent(json_encode($resp));
        $response->headers->set('Content-Type', 'json');

        return $response;
    }
    
    
}
