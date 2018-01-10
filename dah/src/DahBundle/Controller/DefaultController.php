<?php

namespace DahBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;
use AdminBundle\Entity\DahEnquires;
use AdminBundle\Entity\DahNewsletterSubscribers;
use AdminBundle\Entity\DahUsers;
use AdminBundle\Entity\DahTeachersInfo;
use AdminBundle\Entity\DahMembersPasswordReset;
use AdminBundle\Entity\DahWorkshopEnrollment;

class DefaultController extends Controller {

    public static $template = "DahBundle:templates:default.html.twig";
    public $data = array();

    public function __construct() {
        $this->data['extend_view'] = self::$template;
    }

    /**
     * @Route("/", name="_home")
     * @Template("DahBundle:Default:index.html.twig")
     */
    public function indexAction(Request $request) {
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahContentPages');
        $page = $repository->findOneByPageName('home');
        $error = '';
        $this->session = $request->getSession();
        $sc = $this->get('security.context');
        $access = $sc->isGranted(new Expression('is_remember_me() or is_fully_authenticated()'));
        //check if user alreay logged in
        if (true === $access) {
            // return $this->redirect($this->generateUrl('_dashboard'));
        }
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                    SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $this->session->get(SecurityContext::AUTHENTICATION_ERROR);
            $this->session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        $this->data['page'] = $page;
        $this->data['error'] = $error;
        return $this->data;
    }

    /**
     * @Route("/contact", name="_contact")
     * @Template("DahBundle:Default:contact.html.twig")
     */
    public function contactAction() {
        return $this->data;
    }

    /**
     * @Route("/ajax/add_contact", name="add_contact")
     */
    public function ajaxAddContactAction(Request $request) {
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
            $fullname = trim($request->request->get('fullname'));
            $email = trim($request->request->get('email'));
            $message = trim($request->request->get('message'));
            if ($fullname == '' || $email == '' || $message == '') {
                $res['message'] = $this->get('translator')->trans("server.messages.Pleaseentervaliddetails");
            } else {
                $maildata = array(
                    "name" => $fullname,
                    "email" => $email,
                    "message" => $message
                );
                $message = $this->renderView('AdminBundle:Emails:enquiry.html.twig', $maildata);
                $emailhand->sendEmail('upendramanve@gmail.com', 'User Enquiry', $message);
                $res['message'] = $this->get('translator')->trans("server.messages.thankyouforcontacting");
                $res['status'] = 'success';
            }
        }
        $response->setContent(json_encode($res));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/ajax/dublicate_subsc_email_url", name="dublicate_subsc_email_url")
     */
    public function isEmailAvailable() {
        $email = $this->input->post('email');
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahNewsletterSubscribers');
        $emailcheck = $repository->findOneByPageEmail($email);
        if ($emailcheck) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @Route("/ajax/add_to_newsletter", name="add_to_newsletter")
     */
    public function ajaxAddToNewsletterAction(Request $request) {
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
            $email = trim($request->request->get('email'));
            if ($email == '') {
                $res['message'] = $this->get('translator')->trans("server.messages.Pleaseentervalidemail");
            } else {
                $repository = $this->getDoctrine()->getRepository('AdminBundle:DahNewsletterSubscribers');
                $emailcheck = $repository->findOneByEmail($email);
                if ($emailcheck) {
                    $res['message'] = $this->get('translator')->trans("server.messages.Youhavealreadysubscribetoournewsletter");
                } else {
                    $resetdata = new DahNewsletterSubscribers();
                    $resetdata->setEmail($email);
                    $resetdata->setStatus('active');
                    $resetdata->setSubscribedOn(time());
                    $em->persist($resetdata);
                    $em->flush();
                    $res['message'] = $this->get('translator')->trans("server.messages.Thankyouforsubscribingtoournewsletter");
                    $res['status'] = 'success';
                }
            }
        }
        $response->setContent(json_encode($res));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/choose_user_type", name="_choose_user_type")
     * @Template("DahBundle:Default:chooseUser.html.twig")
     */
    public function addNewDepAction(Request $request) {
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

    /**
     * @Route("/signup/student", name="_signup_student")
     * @Template("DahBundle:Default:signupStudent.html.twig")
     */
    public function signupStudentAction(Request $request) {
        $this->session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $error = array();
        if ($request->getMethod() == 'POST') {
            $fname = trim($request->request->get('fname'));
            $lname = trim($request->request->get('lname'));
            $email = trim($request->request->get('email'));
            $password = trim($request->request->get('password'));
            $cpassword = trim($request->request->get('cpassword'));
            if ($fname == '') {
                $error['fname'] = $this->get('translator')->trans("common.fieldnotblank");
            }
            if (strlen($fname) > 100) {
                $error['fname'] = $this->get('translator')->trans("common.fieldlimit100");
            }
            if ($lname == '') {
                $error['lname'] = $this->get('translator')->trans("common.fieldnotblank");
            }
            if (strlen($lname) > 100) {
                $error['lname'] = $this->get('translator')->trans("common.fieldlimit100");
            }
            if ($email == '') {
                $error['email'] = $this->get('translator')->trans("common.fieldnotblank");
            }
            if ($password == '') {
                $error['password'] = $this->get('translator')->trans("common.fieldnotblank");
            } else {
                if (strlen($password) > 25) {
                    $error['password'] = $this->get('translator')->trans("common.passwordlimit4");
                }
                if (strlen($password) < 3) {
                    $error['password'] = $this->get('translator')->trans("common.passwordlimitmin3");
                }
                if ($cpassword != $password) {
                    $error['cpassword'] = $this->get('translator')->trans("common.passwordmismatch");
                }
            }
            if (empty($error)) {
                $statement = $connection->prepare("  select * from dah_users where email = '$email'  ");
                $statement->execute();
                $users = $statement->fetchAll();
                if (!empty($users)) {
                    $error['email'] = $this->get('translator')->trans("signup.Thisemailisalreadyregistered");
                } else {
                    //add user and send email
                    $user = new DahUsers();
                    $salt = md5(uniqid());
                    $verify = md5(uniqid());
                    $encoderFactory = $this->get('security.encoder_factory');
                    $encoder = $encoderFactory->getEncoder($user);
                    $newpassword = $encoder->encodePassword($password, $salt);
                    $user->setFname($fname);
                    $user->setLname($lname);
                    $user->setEmail($email);
                    $user->setSalt($salt);
                    $user->setPassword($newpassword);
                    $user->setRole('ROLE_STUDENT');
                    $user->setStatus('inactive');
                    $user->setIsActive(0);
                    $user->setVerify($verify);
                    $user->setAddedOn(time());
                    $em->persist($user);
                    $em->flush();
                    $maildata = array(
                        "name" => $user->getFname(),
                        "email" => $user->getEmail(),
                        "code" => $verify
                    );
                    $message = $this->renderView('AdminBundle:Emails:userEmailVerify.html.twig', $maildata);
                    $emailhand->sendEmail($email, 'Account Verification Mail', $message);
                    $this->session->getFlashBag()->add('success', $this->get('translator')->trans("signup.YourEmailwasRegisteredsuccessfullywithus") . ' <br/> <a href="' . $this->generateUrl('_home') . '">' . $this->get('translator')->trans("signup.ClickHere") . '</a> ' . $this->get('translator')->trans("signup.toSignin"));
                    return $this->redirect($this->generateUrl('_signup_student'));
                }
            }
        }
        $this->data['error'] = $error;
        return $this->data;
    }

    /**
     * @Route("/signup/teacher", name="_signup_teacher")
     * @Template("DahBundle:Default:signupTeacher.html.twig")
     */
    public function signupTeacherAction(Request $request) {
        $this->session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $error = array();
        if ($request->getMethod() == 'POST') {
            $fname = trim($request->request->get('fname'));
            $lname = trim($request->request->get('lname'));
            $email = trim($request->request->get('email'));
            $password = trim($request->request->get('password'));
            $cpassword = trim($request->request->get('cpassword'));
            $department = trim($request->request->get('department'));
            $resume = $request->request->get('cvUpload');
            if ($fname == '') {
                $error['fname'] = $this->get('translator')->trans("common.fieldnotblank");
            }
            if (strlen($fname) > 100) {
                $error['fname'] = $this->get('translator')->trans("common.fieldlimit100");
            }
            if ($lname == '') {
                $error['lname'] = $this->get('translator')->trans("common.fieldnotblank");
            }
            if (strlen($lname) > 100) {
                $error['lname'] = $this->get('translator')->trans("common.fieldlimit100");
            }
            if ($email == '') {
                $error['email'] = $this->get('translator')->trans("common.fieldnotblank");
            }
            if ($password == '') {
                $error['password'] = $this->get('translator')->trans("common.fieldnotblank");
            } else {
                if (strlen($password) > 25) {
                    $error['password'] = $this->get('translator')->trans("common.passwordlimit4");
                }
                if (strlen($password) < 3) {
                    $error['password'] = $this->get('translator')->trans("common.passwordlimitmin3");
                }
                if ($cpassword != $password) {
                    $error['cpassword'] = $this->get('translator')->trans("common.passwordmismatch");
                }
            }
            $allowed = array('docx', 'pdf', 'doc');
            if (!empty($_FILES) && isset($_FILES['cvUpload']) && $_FILES['cvUpload']['size'] > 0) {

                $filename = $_FILES['cvUpload']['name'];
                $ext = pathinfo($filename);
                if (!in_array(strtolower($ext['extension']), $allowed)) {
                    $error['cvUpload'] = "Please upload docx/pdf/doc.";
                }
            }
            if (empty($error)) {

                $statement = $connection->prepare("  select * from dah_users where email = '$email'  ");
                $statement->execute();
                $users = $statement->fetchAll();
                if (!empty($users)) {
                    $error['email'] = $this->get('translator')->trans("signup.Thisemailisalreadyregistered");
                } else {
                    $newname = $resume;
                    if (!empty($_FILES) && isset($_FILES['cvUpload']) && $_FILES['cvUpload']['size'] > 0) {
                        $tempFile = $_FILES['cvUpload']['tmp_name'];
                        if (isset($tempFile) && !empty($tempFile) && $tempFile != '') {
                            $fileParts = pathinfo($_FILES['cvUpload']['name']);
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
                    //add user and send email
                    $user = new DahUsers();
                    $salt = md5(uniqid());
                    $verify = md5(uniqid());
                    $encoderFactory = $this->get('security.encoder_factory');
                    $encoder = $encoderFactory->getEncoder($user);
                    $newpassword = $encoder->encodePassword($password, $salt);
                    $user->setFname($fname);
                    $user->setLname($lname);
                    $user->setEmail($email);
                    $user->setCv($newname);
                    $user->setSalt($salt);
                    $user->setPassword($newpassword);
                    $user->setRole('ROLE_TEACHER');
                    $user->setStatus('inactive');
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
                    if ($dpeobj) {
                        $userinfo = new DahTeachersInfo();
                        $userinfo->setUid($user);
                        $userinfo->setDepid($dpeobj);
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
                    $this->session->getFlashBag()->add('success', $this->get('translator')->trans("signup.YourEmailwasRegisteredsuccessfullywithus") . ' <br/> <a href="' . $this->generateUrl('_home') . '">' . $this->get('translator')->trans("signup.ClickHere") . '</a> ' . $this->get('translator')->trans("signup.toSignin"));
                    return $this->redirect($this->generateUrl('_signup_teacher'));
                }
            }
        }
        $statement = $connection->prepare("  select * from dah_departments order by added_on desc ");
        $statement->execute();
        $totaldeps = $statement->fetchAll();
        $this->data['departments'] = $totaldeps;
        $this->data['error'] = $error;
        return $this->data;
    }

    /**
     * @Route("/forgotpassword", name="_user_forgotpassword")
     * @Template("DahBundle:Default:forgotPassword.html.twig")
     */
    public function forgotpasswordAction(Request $request) {
        $this->session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $error = array();
        if ($request->getMethod() == 'POST') {
            $email = trim($request->request->get('email'));
            if ($email == '') {
                $error['email'] = $this->get('translator')->trans("common.fieldnotblank");
            }
            if (empty($error)) {
                $repository = $this->getDoctrine()->getRepository('AdminBundle:DahUsers');
                $user = $repository->findOneBy(
                        array('email' => $email)
                );
                if (!($user)) {
                    $error['email'] = $this->get('translator')->trans("common.Thisemailisnotregistered");
                } else {

                    $newcode = md5(uniqid());
                    $repository = $this->getDoctrine()->getRepository('AdminBundle:DahMembersPasswordReset');
                    $resetdata = $repository->findOneByUid($user->getUid());
                    if (!$resetdata) {
                        $resetdata = new DahMembersPasswordReset();
                    }
                    $resetdata->setCode($newcode);
                    $resetdata->setUid($user);
                    $em->persist($resetdata);
                    $em->flush();
                    $maildata = array(
                        "name" => $user->getFname(),
                        "email" => $user->getEmail(),
                        "code" => $newcode
                    );
                    $resetdata->setCode($newcode);
                    $message = $this->renderView('AdminBundle:Emails:passwordResetMembers.html.twig', $maildata);
                    $this->session->getFlashBag()->add('success', $this->get('translator')->trans("signup.APasswordresetlinkwassenttoyourregisteredemailaddress") . ' <br/> <a href="' . $this->generateUrl('_home') . '">' . $this->get('translator')->trans("signup.ClickHere") . '</a> ' . $this->get('translator')->trans("signup.toSignin"));
                    $emailhand->sendEmail($email, 'Password Reset Link', $message);
                    return $this->redirect($this->generateUrl('_user_forgotpassword'));
                }
            }
        }
        $this->data['error'] = $error;
        return $this->data;
    }

    /**
     * @Route("/email/allemails", name="_test_email")
     * @Template("AdminBundle:Emails:workshopRemainder.html.twig")
     */
    public function emailAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $maildata = array(
            "name" => "Test user",
            "email" => "testmail@test.com",
            "code" => md5(uniqid()),
            "message" => "<p>Some message text.</p>",
            "wid" => 23,
            "workshop" => "Sample workshop",
            "tid" => 23,
            "training" => "Sample training",
        );
        $statement = $connection->prepare("  select * from dah_workshop_enrollment dwe 
                                            join dah_workshops dw on dwe.wid = dw.wid ");
        $statement->execute();
        $subscriber = $statement->fetch();
        $this->data = $subscriber;
        return $this->data;
    }

    /**
     * @Route("/resetpassword", name="_member_resetpassword")
     * @Template("DahBundle:Default:resetPassword.html.twig")
     */
    public function resetpasswordAction(Request $request) {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('_dashboard'));
        }
        $this->session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $code = $request->query->get('code');
        if ($code == '') {
            $this->session->getFlashBag()->add('error', $this->get('translator')->trans("server.messages.ThePasswordresetlinkisinvalidorexpired"));
            return $this->redirect($this->generateUrl('_home'));
        }
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahMembersPasswordReset');
        $resetdata = $repository->findOneByCode($code);
        if (!$resetdata) {
            $this->session->set('brokenlink', $this->get('translator')->trans("server.messages.ThePasswordresetlinkisinvalidorexpired"));
            return $this->redirect($this->generateUrl('_member_brokenlink'));
        }
        if ($request->getMethod() == 'POST') {
            if (trim($request->request->get('newpassword')) == '' || trim($request->request->get('confirmpassword')) == '' || trim($request->request->get('newpassword')) != trim($request->request->get('confirmpassword'))) {
                $this->session->getFlashBag()->add('error', $this->get('translator')->trans("server.messages.Pleaseenteravalidpassword"));
                return $this->redirect($this->generateUrl('_member_resetpassword', array('code' => $code)));
            } else {
                $encoderFactory = $this->get('security.encoder_factory');
                $encoder = $encoderFactory->getEncoder($resetdata->getUid());
                $salt = md5(uniqid());
                $password = $encoder->encodePassword(trim($request->request->get('newpassword')), $salt);
                $resetdata->getUid()->setSalt($salt);
                $resetdata->getUid()->setPassword($password);
                $em->persist($resetdata->getUid());
                $em->flush();
                $em->remove($resetdata);
                $em->flush();
                $this->session->getFlashBag()->add('success', $this->get('translator')->trans("server.messages.Passwordchangedsuccessfully"));
                return $this->redirect($this->generateUrl('_home'));
            }
        }
        return $this->data;
    }

    /**
     * @Route("/brokenlink", name="_member_brokenlink")
     * @Template("DahBundle:Default:brokenLink.html.twig")
     */
    public function brokenlinkAction(Request $request) {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('_dashboard'));
        }
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $this->session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $broses = $this->session->get('brokenlink');
        if ($broses == '') {
            return $this->redirect($this->generateUrl('_home'));
        }
        $this->session->remove('brokenlink');
        return $this->data;
    }

    /**
     * @Route("/verifyuser", name="_verifyuser")
     */
    public function verifyUserAction(Request $request) {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('_dashboard'));
        }
        $this->session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $code = $request->query->get('code');
        if ($code == '') {
            $this->session->getFlashBag()->add('error', $this->get('translator')->trans("server.messages.Theaccountverificationlinkisinvalidorexpired"));
            return $this->redirect($this->generateUrl('_home'));
        }
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahUsers');
        $resetdata = $repository->findOneByVerify($code);
        if (!$resetdata) {
            $this->session->getFlashBag()->add('error', $this->get('translator')->trans("server.messages.Theaccountverificationlinkisinvalidorexpired"));
            return $this->redirect($this->generateUrl('_home'));
        } else {
            $resetdata->setIsActive(1);
            $resetdata->setStatus('active');
            $resetdata->setVerify('');
            $em->persist($resetdata);
            $em->flush();
            $maildata = array(
                "name" => $resetdata->getFname(),
                "email" => $resetdata->getEmail(),
            );
            $message = $this->renderView('AdminBundle:Emails:welcomeMessage.html.twig', $maildata);
            $emailhand->sendEmail($resetdata->getEmail(), 'Welcome email', $message);
            $this->session->getFlashBag()->add('success', $this->get('translator')->trans("server.messages.Userverifiedsuccessfully"));
            return $this->redirect($this->generateUrl('_home'));
        }
    }

    /**
     * @Route("/news/{page}", name="_news" , defaults={"page"=1})
     * @Template("DahBundle:News:news.html.twig")
     */
    public function newsAction(Request $request, $page) {
        $common = $this->get('common_handler');
        $limit = 6;
        $key = '';
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $todaysTimestamp = strtotime(date('d-m-Y'));
        $where = " where 1=1  and ( future_date is null or future_date <=  $todaysTimestamp ) ";
        $key = trim($request->query->get('keyword'));
        if ($request->query->get('reset') != '') {
            return $this->redirect($this->generateUrl('_news'));
        }
        if ($key != '') {

            $where .= " and ( news_title like '%" . $key . "%' or news_subtitle like '%" . $key . "%'   )  ";
        }
        $statement = $connection->prepare("  select * from dah_news " . $where . " order by added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $pages = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_news " . $where . " ");
        $statement->execute();
        $totalcontentpages = $statement->fetchAll();
        $totalpages = ceil(count($totalcontentpages) / $limit);
        $paginate = $common->paginate('_news', array(), $totalpages, $page, 5);
        $this->data['news'] = $pages;
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
     * @Route("/view/news/{newsid}", name="_view_news",defaults={"newsid"=0})
     * @Template("DahBundle:News:view.html.twig")
     */
    public function viewNewsAction(Request $request, $newsid) {
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahNews');
        $news = $repository->findOneBy(
                array('newsid' => $newsid)
        );
        if (!$news) {
            return $this->redirect($this->generateUrl('_news'));
        }
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $this->data['news'] = $news;
        $this->data['metaTitle'] = $news->getNewsMetaTitle();
        $this->data['metaDescription'] = $news->getNewsMetaDescription();
        $this->data['metaKeywords'] = $news->getNewsMetaKeyword();
        return $this->data;
    }

    /**
     * @Route("/workshops/{page}", name="_workshops" , defaults={"page"=1})
     * @Template("DahBundle:Workshops:workshops.html.twig")
     */
    public function workshopsAction(Request $request, $page) {
        $common = $this->get('common_handler');
        $limit = 5;
        $key = '';
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $where = ' where 1=1 ';
        $todaysTimestamp = strtotime(date('d-m-Y'));
        $key = trim($request->query->get('keyword'));
        if ($request->query->get('reset') != '') {
            return $this->redirect($this->generateUrl('_workshops'));
        }
        if ($key != '') {

            $where .= " and ( workshop_title like '%" . $key . "%' or workshop_subtitle like '%" . $key . "%'   ) ";
        }
        $statement = $connection->prepare("  select * from dah_workshops " . $where . " and from_date > $todaysTimestamp  order by added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $pages = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_workshops " . $where . "  and from_date > $todaysTimestamp  ");
        $statement->execute();
        $totalcontentpages = $statement->fetchAll();
        $totalpages = ceil(count($totalcontentpages) / $limit);
        $paginate = $common->paginate('_workshops', array(), $totalpages, $page, 5);
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
     * @Route("/view/workshop/{wid}", name="_view_workshop",defaults={"wid"=0})
     * @Template("DahBundle:Workshops:view.html.twig")
     */
    public function viewWorkshopAction(Request $request, $wid) {
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahWorkshops');
        $workshop = $repository->findOneBy(
                array('wid' => $wid)
        );
        if (!$workshop) {
            return $this->redirect($this->generateUrl('_workshops'));
        }
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $statement = $connection->prepare("  select * from dah_workshop_videos where wid = " . $wid . " order by tvid asc ");
        $statement->execute();
        $totvid = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_workshop_material where dwid = " . $wid . " order by id asc ");
        $statement->execute();
        $tomat = $statement->fetchAll();
        $this->data['workshop'] = $workshop;
        $this->data['videos'] = $totvid;
        $this->data['materials'] = $tomat;
        $this->data['metaTitle'] = $workshop->getWorkshopMetaTitle();
        $this->data['metaDescription'] = $workshop->getWorkshopMetaDescription();
        $this->data['metaKeywords'] = $workshop->getWorkshopMetaKeyword();
        return $this->data;
    }

    /**
     * @Route("/ajax/enroll_to_workshop", name="enroll_to_workshop")
     */
    public function enrollToWorkshopletterAction(Request $request) {
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
            $wid = trim($request->request->get('wid'));
            $fname = trim($request->request->get('fname'));
            $lname = trim($request->request->get('lname'));
            $email = trim($request->request->get('email'));
            $phone = trim($request->request->get('phone'));
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahWorkshops');
            $workshop = $repository->findOneBy(
                    array('wid' => $wid)
            );
            $user = $this->getUser();
            if ($user) {
                $email = $user->getEmail();
            }
            if ($workshop) {
                if ($wid > 0) {
                    if ($email != '') {
                        if (!$user) {
                            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahWorkshopEnrollment');
                            $enroll = $repository->findOneBy(
                                    array('email' => $email, 'wid' => $wid)
                            );
                            if (!$enroll) {
                                $resetdata = new DahWorkshopEnrollment();
                                $resetdata->setEmail($email);
                                $resetdata->setFname($fname);
                                $resetdata->setLname($lname);
                                $resetdata->setPhone($phone);
                                $resetdata->setWid($workshop);
                                $em->persist($resetdata);
                                $em->flush();
                                $maildata = array(
                                    "name" => $fname,
                                    "email" => $email,
                                    "wid" => $wid,
                                    "workshop" => $workshop->getWorkshopTitle(),
                                );
                                $message = $this->renderView('AdminBundle:Emails:workshopenrolled.html.twig', $maildata);
                                $emailhand->sendEmail($email, 'Workshop Enrollment', $message);
                                $res['message'] = $this->get('translator')->trans("workshop.ThankyouforenrollingtothisWorkshop");
                                $res['status'] = 'success';
                            } else {
                                $res['message'] = $this->get('translator')->trans("workshop.Youarealreadyenrolledforthisworkshop");
                                $res['status'] = 'success';
                            }
                        } else {
                            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahWorkshopEnrollment');
                            $enroll = $repository->findOneBy(
                                    array('email' => $email, 'wid' => $wid)
                            );
                            if (!$enroll) {
                                $resetdata = new DahWorkshopEnrollment();
                                $resetdata->setEmail($user->getEmail());
                                $resetdata->setFname($user->getFname());
                                $resetdata->setLname($user->getLname());
                                $resetdata->setUid($user);
                                $resetdata->setWid($workshop);
                                $em->persist($resetdata);
                                $em->flush();
                                $maildata = array(
                                    "name" => $user->getFname(),
                                    "email" => $user->getEmail(),
                                    "wid" => $wid,
                                    "workshop" => $workshop->getWorkshopTitle(),
                                );
                                $message = $this->renderView('AdminBundle:Emails:workshopenrolled.html.twig', $maildata);
                                $emailhand->sendEmail($email, 'Workshop Enrollment', $message);
                                $res['message'] = $this->get('translator')->trans("workshop.ThankyouforenrollingtothisWorkshop");
                                $res['status'] = 'success';
                            } else {
                                $res['message'] = $this->get('translator')->trans("workshop.Youarealreadyenrolledforthisworkshop");
                                $res['status'] = 'success';
                            }
                        }
                    } else {
                        $res['message'] = $this->get('translator')->trans("common.validemail");
                    }
                }
            }
        }
        $response->setContent(json_encode($res));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/faqs/{page}", name="_faqs" , defaults={"page"=1})
     * @Template("DahBundle:Faqs:faqs.html.twig")
     */
    public function faqsAction(Request $request, $page) {
        $common = $this->get('common_handler');
        $limit = 20;
        $key = '';
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $where = ' where status = "active" ';
        $key = trim($request->query->get('keyword'));
        if ($request->query->get('reset') != '') {
            return $this->redirect($this->generateUrl('_faqs'));
        }
        if ($key != '') {

            $where .= " and ( workshop_title like '%" . $key . "%' or workshop_subtitle like '%" . $key . "%'   ) ";
        }
        $statement = $connection->prepare("  select * from dah_faqs " . $where . " order by added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $pages = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_faqs " . $where . " ");
        $statement->execute();
        $totalcontentpages = $statement->fetchAll();
        $totalpages = ceil(count($totalcontentpages) / $limit);
        $paginate = $common->paginate('_faqs', array('keyword' => $key), $totalpages, $page, 5);
        $this->data['faqs'] = $pages;
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
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $limit = 5;
        $page = 1;
        $where = " where dt.status='active'  ";
        $statement = $connection->prepare("  select * from dah_trainings dt join dah_departments dp on dt.deptid = dp.deptid join dah_users du on du.uid = dt.uid  " . $where . " order by dt.added_on desc limit " . (($page - 1) * $limit) . ",$limit  ");
        $statement->execute();
        $trainings = $statement->fetchAll();
        $this->data['trainings'] = $trainings;
        $todaysTimestamp = strtotime(date('d-m-Y'));
        $where = " where 1=1  and ( future_date is null or future_date <=  $todaysTimestamp ) ";
        $statement = $connection->prepare("  select * from dah_news " . $where . " order by added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $news = $statement->fetchAll();
        $this->data['news'] = $news;
        return $this->data;
    }

    /**
     * @Route("/calendar", name="_calendar")
     * @Template("DahBundle:Default:calendar.html.twig")
     */
    public function calendarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $limit = 5;
        $page = 1;
        $where = " where dt.status='active'  ";
        $statement = $connection->prepare("  select * from dah_trainings dt join dah_departments dp on dt.deptid = dp.deptid join dah_users du on du.uid = dt.uid  " . $where . " order by dt.added_on desc limit " . (($page - 1) * $limit) . ",$limit  ");
        $statement->execute();
        $trainings = $statement->fetchAll();
        $this->data['trainings'] = $trainings;
        $todaysTimestamp = strtotime(date('d-m-Y'));
        $where = " where 1=1  and ( future_date is null or future_date <=  $todaysTimestamp ) ";
        $statement = $connection->prepare("  select * from dah_news " . $where . " order by added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $news = $statement->fetchAll();
        $this->data['news'] = $news;
        return $this->data;
    }

    /**
     * @Route("/json-events", name="_xmlout")
     */
    public function xmloutAction(Request $request) {
        $common = $this->get('common_handler');
        $limit = 5;
        $key = '';
        $todaysTimestamp = strtotime(date('d-m-Y'));
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $where = ' where 1=1 ';
        $statement = $connection->prepare("  select * from dah_workshops " . $where . "  and from_date > $todaysTimestamp  order by added_on desc ");
        $statement->execute();
        $pages = $statement->fetchAll();
        $resp = array();
        // for trainings #334878
        foreach ($pages as $page) {
            if ($page['from_date'] > 0) {
                if ($page['to_date'] > 0) {
                    $resp[] = array(
                        "title" => $page['workshop_title'],
                        "start" => date("Y-m-d", $page['from_date']),
                        "end" => date("Y-m-d", $page['to_date']),
                        "color" => "#E8BE6C",
                        "url" => $this->generateUrl('_view_workshop', array('wid' => $page['wid']))
                    );
                } else {
                    $resp[] = array(
                        "title" => $page['workshop_title'],
                        "start" => date("Y-m-d", $page['from_date']),
                        "url" => $this->generateUrl('_view_workshop', array('wid' => $page['wid']))
                    );
                }
            }
        }
        $response = new Response();
        $response->setContent(json_encode($resp));
        $response->headers->set('Content-Type', 'json');
        return $response;
    }

    /**
     * @Route("/processmail", name="_mail_process_test")
     */
    public function emailProcessAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $count = 0;
        $emailhand = $this->get('emails_handler');
        $connection = $em->getConnection();
        $statement = $connection->prepare("  select * from dah_reminder_email where status = 'open' order by updated_on desc limit 0,10 ");
        $statement->execute();
        $email = $statement->fetchAll();
        $this->data['email'] = $email;
        foreach ($email as $eml) {
            $emailhand->sendEmail("upendramanve@gmail.com", $eml['email']." ---> ".$eml['subject'], $eml['message']);
            $count++;
        }
        $resp = "$count emails sent successfully";
        //  print_r($email);
        // exit;
        //  return $this->data['email'];
        $response = new Response();
        $response->setContent(json_encode($resp));
        $response->headers->set('Content-Type', 'json');
        return $response;
    }

}
