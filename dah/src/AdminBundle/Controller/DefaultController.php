<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;
use AdminBundle\Entity\DahAdmin;
use AdminBundle\Entity\DahPasswordReset;
use AdminBundle\Entity\DahSettings;
use AdminBundle\Entity\DahReminderEmail;

class DefaultController extends Controller {

    public static $template = "AdminBundle:templates:default.html.twig";
    public $data = array();

    public function __construct() {
        $this->data['extend_view'] = self::$template;
    }

    /**
     * @Route("/", name="_admin_login")
     */
    public function indexAction(Request $request) {
        $error = '';
        $this->session = $request->getSession();
        $sc = $this->get('security.context');
        $access = $sc->isGranted(new Expression('is_remember_me() or is_fully_authenticated()'));
        //check if user alreay logged in
        if (true === $access) {
            return $this->redirect($this->generateUrl('_admin_dashboard'));
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
        if ($request->request->get('login') == 'Signin') {

            if ($request->request->get('_username') == '' || $request->request->get('_password') == '') {
                $this->session->getFlashBag()->add('error', 'Invalid username/password');
                return $this->redirect($this->generateUrl('_admin_login'));
            } else {
                //$this->session->getFlashBag()->add('success', 'login successfull');
                return $this->redirect($this->generateUrl('_admin_dashboard'));
            }
        }
        // last username entered by the user
        $lastUsername = (null === $this->session) ? '' : $this->session->get(SecurityContext::LAST_USERNAME);

        $this->data['error'] = $error;
        $this->data['last_username'] = $lastUsername;
        $this->data['form'] = 'signin';
        return $this->render('AdminBundle:Default:index.html.twig', $this->data);
    }

    /**
     * @Route("/forgotpassword", name="_forgotpassword")
     */
    public function forgotpasswordAction(Request $request) {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('_admin_dashboard'));
        }
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $this->session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == 'POST') {
            if (trim($request->request->get('email')) == '') {
                $this->session->getFlashBag()->add('error', 'Please enter a valid email address');
                return $this->redirect($this->generateUrl('_forgotpassword'));
            } else {
                $email = trim($request->request->get('email'));
                $repository = $this->getDoctrine()->getRepository('AdminBundle:DahAdmin');
                $user = $repository->findOneByEmail($email);
                if (!$user) {
                    $this->session->getFlashBag()->add('error', 'Email does not exist');
                    return $this->redirect($this->generateUrl('_forgotpassword'));
                } else {
                    $newcode = md5(uniqid());
                    $repository = $this->getDoctrine()->getRepository('AdminBundle:DahPasswordReset');
                    $resetdata = $repository->findOneByAdminid($user->getAdminid());
                    if (!$resetdata) {
                        $resetdata = new DahPasswordReset();
                    }
                    $resetdata->setCode($newcode);
                    $resetdata->setAdminid($user);
                    $em->persist($resetdata);
                    $em->flush();
                    $maildata = array(
                        "name" => $user->getUsername(),
                        "email" => $user->getEmail(),
                        "code" => $newcode
                    );
                    $resetdata->setCode($newcode);
                    $message = $this->renderView('AdminBundle:Emails:passwordReset.html.twig', $maildata);
                    $this->session->getFlashBag()->add('success', 'A Password reset link was sent to your registered email address. <br/> <a href="' . $this->generateUrl('_admin_login') . '">Click here</a> to Sign in.');
                    $emailhand->sendEmail($email, 'Password Reset Link', $message);
                    return $this->redirect($this->generateUrl('_forgotpassword'));
                }
            }
        }
        $this->data['form'] = 'forgotpassword';
        return $this->render('AdminBundle:Default:index.html.twig', $this->data);
    }

    /**
     * @Route("/resetpassword", name="_admin_resetpassword")
     */
    public function resetpasswordAction(Request $request) {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('_admin_dashboard'));
        }
        $this->session = $request->getSession();
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $code = $request->query->get('code');
        if ($code == '') {
            $this->session->getFlashBag()->add('error', 'The Password reset link is invalid or expired');
            return $this->redirect($this->generateUrl('_admin_login'));
        }


        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahPasswordReset');
        $resetdata = $repository->findOneByCode($code);
        if (!$resetdata) {
            //$this->session->getFlashBag()->add('brokenlink', 'The Password reset link is invalid or expired');
            $this->session->set('brokenlink', 'The Password reset link is invalid or expired');
            return $this->redirect($this->generateUrl('_brokenlink'));
        }
        if ($request->getMethod() == 'POST') {
            if (trim($request->request->get('newpassword')) == '' || trim($request->request->get('confirmpassword')) == '' || trim($request->request->get('newpassword')) != trim($request->request->get('confirmpassword'))) {
                $this->session->getFlashBag()->add('error', 'Please enter a valid password');
                return $this->redirect($this->generateUrl('_admin_resetpassword', array('code' => $code)));
            } else {
                $adminid = $resetdata->getAdminid()->getAdminid();
                $encoderFactory = $this->get('security.encoder_factory');
                $encoder = $encoderFactory->getEncoder($resetdata->getAdminid());
                $salt = md5(uniqid());
                $password = $encoder->encodePassword(trim($request->request->get('newpassword')), $salt);
                $resetdata->getAdminid()->setSalt($salt);
                $resetdata->getAdminid()->setPassword($password);
                $em->persist($resetdata->getAdminid());
                $em->flush();
                $em->remove($resetdata);
                $em->flush();
                $common->logActivity('has changed his password.', $adminid);
                $this->session->getFlashBag()->add('success', 'Password changed successfully');
                return $this->redirect($this->generateUrl('_admin_login'));
            }
        }
        $this->data['form'] = 'resetpassword';
        return $this->render('AdminBundle:Default:index.html.twig', $this->data);
    }

    /**
     * @Route("/brokenlink", name="_brokenlink")
     */
    public function brokenlinkAction(Request $request) {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('_admin_dashboard'));
        }
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $this->session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $broses = $this->session->get('brokenlink');
        if ($broses == '') {
            return $this->redirect($this->generateUrl('_admin_login'));
        }
        $this->session->remove('brokenlink');
        $this->data['form'] = 'brokenlink';
        return $this->render('AdminBundle:Default:index.html.twig', $this->data);
    }

    /**
     * @Route("/secure/dashboard", name="_admin_dashboard")
     * @Template("AdminBundle:Default:dashboard.html.twig")
     */
    public function dashboardAction() {
        $common = $this->get('common_handler');
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

    /**
     * @Route("/secure/settings", name="_admin_settings")
     * @Template("AdminBundle:Default:settings.html.twig")
     */
    public function settingsdashboardAction(Request $request) {
        $this->session = $request->getSession();
        $common = $this->get('common_handler');
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahSettings');
        $settings = $repository->findOneBy(array());
        $error = array();
        if ($request->getMethod() == 'POST') {
            $username = trim($request->request->get('username'));
            $email = trim($request->request->get('email'));
            $contactemail = trim($request->request->get('contactemail'));
            $phone = trim($request->request->get('phone'));
            $LinkedIn = trim($request->request->get('LinkedIn'));
            $Twitter = trim($request->request->get('Twitter'));
            $g = trim($request->request->get('g'));
            $blackboard = trim($request->request->get('blackboard'));
            $Facebook = trim($request->request->get('Facebook'));
            $Address = trim($request->request->get('Address'));
            if ($username == '') {
                $error['username'] = "Please enter a username";
            } elseif (strlen($username) < 3 || strlen($username) > 50) {
                $error['username'] = "Username must not exceed 50 characters and must have atleast 3 characters.";
            }
            if ($email == '') {
                $error['email'] = "Please enter a new email";
            }
            if ($Address == '') {
                // $error['address'] = "Please enter address";
            }

            if (empty($error)) {
                $statement = $connection->prepare("  select * from dah_admin where username = '$username' and username != '" . $user->getUsername() . "' ");
                $statement->execute();
                $users = $statement->fetchAll();
                if (!empty($users)) {
                    $error['username'] = "This username is already been taken";
                } else {
                    $user->setUsername($username);
                    $user->setUpdatedOn(time());
                    $em->persist($user);
                    $em->flush();

                    if ($settings) {

                        $settings->setEmail($contactemail);
                        $settings->setPhone($phone);
                        $settings->setLinkedin($LinkedIn);
                        $settings->setTwitter($Twitter);
                        $settings->setGooglePlus($g);
                        $settings->setBlackboard($blackboard);
                        $settings->setFacebook($Facebook);
                        $settings->setAddress($Address);
                        $settings->setUpdateOn(time());
                        $em->persist($settings);
                        $em->flush();
                    } else {
                        $settings = new DahSettings();
                        $settings->setEmail($contactemail);
                        $settings->setPhone($phone);
                        $settings->setLinkedin($LinkedIn);
                        $settings->setTwitter($Twitter);
                        $settings->setGooglePlus($g);
                        $settings->setBlackboard($blackboard);
                        $settings->setFacebook($Facebook);
                        $settings->setAddress($Address);
                        $settings->setUpdateOn(time());
                        $em->persist($settings);
                        $em->flush();
                    }
                    $common->logActivity('has changed the settings.');
                    $this->session->getFlashBag()->add('success', 'Settings changed successfully');
                    return $this->redirect($this->generateUrl('_admin_dashboard'));
                }
            }
        }
        $this->data['settings'] = $settings;
        $this->data['user'] = $user;
        $this->data['error'] = $error;
        return $this->data;
    }

    /**
     * @Route("/secure/change_password", name="_admin_change_password")
     * @Template("AdminBundle:Default:changePassword.html.twig")
     */
    public function changepasswordAction(Request $request) {
        $this->session = $request->getSession();
        $common = $this->get('common_handler');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $error = array();
        if ($request->getMethod() == 'POST') {
            $oldpassword = trim($request->request->get('oldpassword'));
            $newpassword = trim($request->request->get('newpassword'));
            $confirmpassword = trim($request->request->get('confirmpassword'));
            if ($oldpassword == '') {
                $error['oldpassword'] = "Please enter old password";
            } elseif (strlen($oldpassword) < 3 || strlen($oldpassword) > 25) {
                $error['oldpassword'] = "Password must not exceed 25 characters and must have atleast 3 characters.";
            }
            if ($newpassword == '') {
                $error['newpassword'] = "Please enter new password";
            } elseif (strlen($newpassword) < 3 || strlen($newpassword) > 25) {
                $error['oldpassword'] = "Password must not exceed 25 characters and must have atleast 3 characters.";
            }
            if ($newpassword != $confirmpassword) {
                $error['confirmpassword'] = "Password mismatch";
            }
            if (empty($error)) {
                $encoderFactory = $this->get('security.encoder_factory');
                $encoder = $encoderFactory->getEncoder($user);
                $password = $encoder->encodePassword($oldpassword, $user->getSalt());
                if ($user->getPassword() != $password) {
                    $error['oldpassword'] = "You have entered incorrect password.";
                } else {
                    $salt = md5(uniqid());
                    $passwordchng = $encoder->encodePassword($newpassword, $salt);
                    $user->setSalt($salt);
                    $user->setPassword($passwordchng);
                    $em->persist($user);
                    $em->flush();
                    $common->logActivity('has changed his password.');
                    $this->session->getFlashBag()->add('success', 'Password changed successfully');
                    return $this->redirect($this->generateUrl('_admin_dashboard'));
                }
            }
        }
        $this->data['error'] = $error;
        return $this->data;
    }

    /**
     * @Route("/secure/activity_log/{page}", name="_admin_activity_log",defaults={"page"=1})
     * @Template("AdminBundle:Default:activityLog.html.twig")
     */
    public function activityLogAction(Request $request, $page) {
        $limit = 20;
        $key = '';
        $common = $this->get('common_handler');
        $error = array();
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $where = ' where 1=1 ';
        $statement = $connection->prepare("  select al.*,da.username from dah_activity_log al join dah_admin da on da.adminid = al.adminid " . $where . "order by logged_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $activities = $statement->fetchAll();
        $statement = $connection->prepare("  select al.*,da.username from dah_activity_log al join dah_admin da on da.adminid = al.adminid " . $where . " ");
        $statement->execute();
        $totalactivities = $statement->fetchAll();
        $totalpages = ceil(count($totalactivities) / $limit);
        $pageinate = $common->paginate('_admin_activity_log', array(), $totalpages, $page, 5);
        $this->data['paginate'] = $pageinate;
        $this->data['page'] = $page;
        $this->data['activities'] = $activities;
        return $this->data;
    }

    /**
     * @Route("/english", name="english")
     */
    public function englishAction(Request $request) {
        $this->get('session')->set('_locale', 'en_UK');
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/arabic",name="arabic")
     */
    public function arabicAction(Request $request) {
        $this->get('session')->set('_locale', 'ar_AR');
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/reminder",name="_admin_reminder")
     * @Template("AdminBundle:Default:reminders.html.twig")
     * 
     */
    public function remindersAction(Request $request) {
        $this->session = $request->getSession();

        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $user = $this->getUser();
        $this->data['departments'] = $em->getRepository('AdminBundle:DahDepartments')->findAll();
        $error = array();

        if ($request->getMethod() == 'POST') {
            $emails = $request->request->get('emailids');
            $studnetCheck = $request->request->get('student_type');
            $trainerCheck = $request->request->get('trainer_type');

            $this->data['message'] = $request->request->get('message');
            $subject = $request->request->get('subject');


            if (empty($studnetCheck) and empty($trainerCheck) and empty($emails)) {
                $error['email'] = "This field cannot be blank";
            }
            if ($subject == '') {
                $error['subject'] = "This field cannot be blank";
            }            
            if (empty($error)) {

                $studentDept = $request->request->get('student_dept');
                $trainerDept = $request->request->get('trainer_dept');

                $message = $this->render('AdminBundle:Emails:reminderEmail.html.twig', $this->data);
                $message = $message->getContent();

                //emaild id set
                $emailIds = explode(",", $emails);

                if (count($emailIds) > 0) {
                    foreach ($emailIds as $email) {
                        if (strlen($email) > 0) {
                            $reminderEmail = new DahReminderEmail();
                            $reminderEmail->setEmail($email);
                            $reminderEmail->setMessage($message);
                            $reminderEmail->setSubject($subject);
                            $reminderEmail->setUpdatedOn();

                            $em->persist($reminderEmail);                          
                            $em->flush();
                        }
                    }
                }
                $helperHandler = $this->get('helper_handler');
                //Student checkbox selected
                if (isset($studnetCheck)) {
                    //bring the selected departments related student email ids
                    if (count($studentDept) > 0) {
                        $depIds = implode(",", $studentDept);
                        $studentDeptQuery = "select du.email from dah_student_info as dsi 
                                            join dah_users as du on du.uid=dsi.uid
                                            where dsi.depid in (" . $depIds . ")";
                        $statement = $connection->prepare($studentDeptQuery);
                        $statement->execute();
                        $emailList = $statement->fetchAll();
                    } else {//All stuedent email ids
                        $studentDeptQuery = "select du.email from dah_student_info as dsi 
                                            join dah_users as du on du.uid=dsi.uid";
                        $statement = $connection->prepare($studentDeptQuery);
                        $statement->execute();
                        $emailList = $statement->fetchAll();
                    }
                    $helperHandler->bulkInsertReminderEmails($emailList, $message, $subject);
                }


                //Trainer checkbox selected

                if (isset($trainerCheck)) {
                    //bring the selected departments related student email ids
                    if (count($trainerDept) > 0) {
                        $depIds = implode(",", $trainerDept);
                        $trainerDeptQuery = "select du.email 
                                         from dah_teachers_info as dt 
                                         join dah_users as du on du.uid=dt.uid
                                         where dt.depid in (" . $depIds . ")";
                        $statement = $connection->prepare($trainerDeptQuery);
                        $statement->execute();
                        $emailList = $statement->fetchAll();
                    } else {//All stuedent email ids
                        $trainerDeptQuery = "select du.email 
                                         from dah_trainings as dt 
                                         join dah_users as du on du.uid=dt.uid";
                        $statement = $connection->prepare($trainerDeptQuery);
                        $statement->execute();
                        $emailList = $statement->fetchAll();
                    }

                    $helperHandler->bulkInsertReminderEmails($emailList, $message, $subject);
                }
                $this->get('session')->getFlashBag()->add('success', ' Reminder Emails sent successfully.');
                return $this->redirect($this->generateUrl('_admin_reminder'));
            }
        }//end of post if


        $this->data['error'] = $error;
        return $this->data;
    }

    /**
     * @Route("/training/{tid}",name="_admin_training_invitation" , defaults={"tid"=0})
     * @Template("AdminBundle:Default:noticeemail.html.twig")
     */
    public function trainingInvitationAction(Request $request, $tid) {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahTrainings');
        $training = $repository->findOneBy(
                array('tid' => $tid)
        );
        if (!$training) {
            $this->get('session')->getFlashBag()->add('success', ' Please choose a valid training.');
            return $this->redirect($this->generateUrl('_admin_trainings'));
        }
        $this->data['departments'] = $em->getRepository('AdminBundle:DahDepartments')->findAll();
        $error = array();

        $this->data['training'] = $training;
        
        if ($request->getMethod() == 'POST') {
            $emails = $request->request->get('emailids');
            $studnetCheck = $request->request->get('student_type');
            $trainerCheck = $request->request->get('trainer_type');
            $gid = trim($request->request->get('gid'));

            $this->data['message'] = $request->request->get('message');
            $subject = "Invitation to Trainings"; //$request->request->get('subject');


            if (empty($studnetCheck) and empty($trainerCheck) and empty($emails)) {
                $error['email'] = "This field cannot be blank";
            }

            if ($gid == '') {
                $error['gid'] = 'Please check atleast one depatment';
            }

            if (empty($error)) {

                $studentDept = $request->request->get('student_dept');
                $trainerDept = $request->request->get('trainer_dept');

                $this->data['trainingurl'] = $url = $this->generateUrl('_view_training', array(), true) . "/" . $tid;


                $message = $this->render('AdminBundle:Emails:reminderEmail.html.twig', $this->data);
                $message = $message->getContent();

                //emaild id set
                $emailIds = explode(",", $emails);

                if (count($emailIds) > 0) {
                    foreach ($emailIds as $email) {
                        if (strlen($email) > 0) {
                            $reminderEmail = new DahReminderEmail();
                            $reminderEmail->setEmail($email);
                            $reminderEmail->setMessage($message);
                            $reminderEmail->setSubject($subject);
                            $reminderEmail->setUpdatedOn();

                            $em->persist($reminderEmail);                          
                            $em->flush();
                        }
                    }
                }
                $helperHandler = $this->get('helper_handler');
                //Student checkbox selected
                if (isset($studnetCheck)) {
                    //bring the selected departments related student email ids
                    if (count($studentDept) > 0) {
                        $depIds = implode(",", $studentDept);
                        $studentDeptQuery = "select du.email from dah_student_info as dsi 
                                            join dah_users as du on du.uid=dsi.uid
                                            where dsi.depid in (" . $depIds . ")";
                        $statement = $connection->prepare($studentDeptQuery);
                        $statement->execute();
                        $emailList = $statement->fetchAll();
                    } else {//All stuedent email ids
                        $studentDeptQuery = "select du.email from dah_student_info as dsi 
                                            join dah_users as du on du.uid=dsi.uid";
                        $statement = $connection->prepare($studentDeptQuery);
                        $statement->execute();
                        $emailList = $statement->fetchAll();
                    }
                    $helperHandler->bulkInsertReminderEmails($emailList, $message, $subject);
                }


                //Trainer checkbox selected

                if (isset($trainerCheck)) {
                    //bring the selected departments related student email ids
                    if (count($trainerDept) > 0) {
                        $depIds = implode(",", $trainerDept);
                        $trainerDeptQuery = "select du.email 
                                         from dah_teachers_info as dt 
                                         join dah_users as du on du.uid=dt.uid
                                         where dt.depid in (" . $depIds . ")";
                        $statement = $connection->prepare($trainerDeptQuery);
                        $statement->execute();
                        $emailList = $statement->fetchAll();
                    } else {//All stuedent email ids
                        $trainerDeptQuery = "select du.email 
                                         from dah_trainings as dt 
                                         join dah_users as du on du.uid=dt.uid";
                        $statement = $connection->prepare($trainerDeptQuery);
                        $statement->execute();
                        $emailList = $statement->fetchAll();
                    }

                    $helperHandler->bulkInsertReminderEmails($emailList, $message, $subject);
                }
                $this->get('session')->getFlashBag()->add('success', ' Mail sent successfully');
                return $this->redirect($this->generateUrl('_admin_training_invitation') . "/" . $tid);
            }
        }//end of post if

        $this->data['error'] = $error;
        return $this->data;
    }

    /**
     * @Route("/secure/certificates/{page}", name="_certificates" , defaults={"page"=1} )
     * @Template("AdminBundle:default:certificates.html.twig")
     */
    public function certificatesAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $emailhand = $this->get('emails_handler');
        $error = array();
        //$connection = $em->getConnection();
        if ($request->getMethod() == 'POST') {
            $course = trim($request->request->get('course'));
            $user = trim($request->request->get('user'));
            $duration = trim($request->request->get('duration'));
            $email = trim($request->request->get('email'));
           // $gid = trim($request->request->get('gid'));
           // $gd = split('-', $gid);
            // print('<pre>');
            // print_r($gd);
            //  print('</pre>');
            //  print $gd[1];
            // exit;
            $type = 'training';
            if ($course == '') {
                $error['course'] = 'Please enter course name';
            }
            if ($user == '') {
                $error['user'] = 'Please enter user name';
            }
            if ($duration == '') {
                $error['duration'] = 'Please enter duration';
            }
            if ($email == '') {
                $error['email'] = 'Please enter email address';
            }
          //  if (!isset($gd[0]) || !isset($gd[1]) || $gd[0] == '' || $gd[1] == '') {
            //    $error['gid'] = 'Please choose atleast one option';
           // }
            if (empty($error)) {
                if ($type == 'training') {
                    $filename = md5(uniqid()) . ".pdf";
                    
                    $pdf = $this->get("white_october.tcpdf")->create();
                    $dt = array(
                        'name' => $user,
                        'training' => $course,
                        'trainingtime' => $duration
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
                    $pdf->Output(WEB_DIRECTORY . '\uploads\\' . $filename, 'F');

                    $maildata = array(
                        "name" => $user,
                        "file" => $filename,
                        "training" => $course
                    );
                    $message = $this->renderView('AdminBundle:Emails:newtrainingCertificate.html.twig', $maildata);
                    $emailhand->sendEmail($email, 'Training Certificate', $message);
                    $this->get('session')->getFlashBag()->add('success', ' Email sent sucessfully.');
                    return $this->redirect($this->generateUrl('_certificates'));
                }
                
            }
        }
        $this->data['error'] = $error;
        $this->data['trainings'] = $em->getRepository('AdminBundle:DahTrainings')->findBy(array('status' => 'active'));
        $this->data['workshops'] = $em->getRepository('AdminBundle:DahWorkshops')->findBy(array());

        return $this->data;
    }
    
}
