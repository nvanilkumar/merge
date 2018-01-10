<?php

// src/AppBundle/Twig/AppExtension.php

namespace AdminBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class AdminExtension extends \Twig_Extension {

    protected $doctrine;

    public function __construct(RegistryInterface $doctrine) {
        $this->doctrine = $doctrine;
    }

    public function getFunctions() {
        return array(
            'getName' => new \Twig_Function_Method($this, 'getName'),
            'urllink' => new \Twig_Function_Method($this, 'urllinkFilter'),
            'addhttp' => new \Twig_Function_Method($this, 'addhttpFunction'),
            'getConnectedStat' => new \Twig_Function_Method($this, 'getConnectedStatFunction'),
            'getAdditionalLinksStat' => new \Twig_Function_Method($this, 'getAdditionalLinksStatFunction'),
            'getAdditionalLinksDet' => new \Twig_Function_Method($this, 'getAdditionalLinksDetFunction'),
            'getAddressStat' => new \Twig_Function_Method($this, 'getAdressStatFunction'),
            'getConnectedDet' => new \Twig_Function_Method($this, 'getConnectedDetFunction'),
            'getAddressDet' => new \Twig_Function_Method($this, 'getAddressDetFunction'),
            'getStudentsCount' => new \Twig_Function_Method($this, 'getStudentsCountFunction'),
            'getTeachersCount' => new \Twig_Function_Method($this, 'getTeachersCountFunction'),
            'getNewsCount' => new \Twig_Function_Method($this, 'getNewsCountFunction'),
            'getWorkshopsCount' => new \Twig_Function_Method($this, 'getWorkshopsCountFunction'),
            'getTrainingsCount' => new \Twig_Function_Method($this, 'getTrainingsCountFunction'),
            'getUserTrainingEnCount' => new \Twig_Function_Method($this, 'getUserTrainingEnCountFunction'),
            'getUserActiveTrainingCount' => new \Twig_Function_Method($this, 'getUserActiveTrainingCountFunction'),
            'getUserOwnedTrainingEnCount' => new \Twig_Function_Method($this, 'getUserOwnedTrainingEnCountFunction'),
            'getUserTrainingCount' => new \Twig_Function_Method($this, 'getUserTrainingCountFunction'),
            'getUserTrainingEnrollCount' => new \Twig_Function_Method($this, 'getUserTrainingEnrollCountFunction'),
            'getUserTrainingCertCount' => new \Twig_Function_Method($this, 'getUserTrainingCertCountFunction'),
            'getQOptions' => new \Twig_Function_Method($this, 'getQOptionsFunction'),
            'getQOptionChecked' => new \Twig_Function_Method($this, 'getQOptionCheckedFunction'),
            'checkIsVideo' => new \Twig_Function_Method($this, 'checkIsVideoFunction'),
            'checkIsAsses' => new \Twig_Function_Method($this, 'checkIsAssesFunction'),
            'getUserTviewCount' => new \Twig_Function_Method($this, 'getUserTviewCountFunction'),
            'getUserFeaturedTrainingCount' => new \Twig_Function_Method($this, 'getUserFeaturedTrainingCountFunction'),
            'getUserTotalEnrolledCount' => new \Twig_Function_Method($this, 'getUserTotalEnrolledCountFunction'),
            'getUserTotalCertCount' => new \Twig_Function_Method($this, 'getUserTotalCertCountFunction'),
        );
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
        );
    }

    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',') {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$' . $price;

        return $price;
    }

    public function addhttpFunction($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }

    public function urllinkFilter($title, $id) {
        //return $title;
        $url = preg_replace("![^a-z0-9]+!i", "-", strtolower($title));
        $url .= '-' . $id;
        return $url;
    }

    public function getName() {
        return 'app_extension';
    }

    public function getConnectedStatFunction() {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_settings  ");
        $statement->execute();
        $cnt = $statement->fetch();
        if ($cnt['linkedin'] == '' && $cnt['twitter'] == '' && $cnt['facebook'] == '' && $cnt['google_plus'] == '') {
            return false;
        } else {
            return true;
        }
    }

    public function getAdditionalLinksStatFunction() {
        $hydera_followers = array('about-us', 'home', 'terms-of-condition', 'privacy-policy');
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_content_pages where page_url NOT IN ( '" . implode($hydera_followers, "', '") . "' )  ");
        $statement->execute();
        $cnt = $statement->fetchAll();
        if (count($cnt) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getAdditionalLinksDetFunction() {
        $hydera_followers = array('about-us', 'home', 'terms-of-condition', 'privacy-policy');
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_content_pages where page_url NOT IN ( '" . implode($hydera_followers, "', '") . "' )  ");
        $statement->execute();
        $cnt = $statement->fetchAll();

        return $cnt;
    }

    public function getConnectedDetFunction() {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select linkedin,twitter,facebook,blackboard,google_plus from dah_settings order by update_on desc  ");
        $statement->execute();
        $cnt = $statement->fetch();
        return $cnt;
    }

    public function getAdressStatFunction() {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_settings  ");
        $statement->execute();
        $cnt = $statement->fetch();
        if ($cnt['email'] == '' && $cnt['phone'] == '' && $cnt['address'] == '') {
            return false;
        } else {
            return true;
        }
    }

    public function getAddressDetFunction() {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select email,phone,address from dah_settings order by update_on desc  ");
        $statement->execute();
        $cnt = $statement->fetch();
        return $cnt;
    }

    public function getStudentsCountFunction() {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_users where role = 'ROLE_STUDENT'  ");
        $statement->execute();
        $cnt = $statement->fetchAll();
        return count($cnt);
    }

    public function getTeachersCountFunction() {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_users where role = 'ROLE_TEACHER'  ");
        $statement->execute();
        $cnt = $statement->fetchAll();
        return count($cnt);
    }

    public function getNewsCountFunction() {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_news   ");
        $statement->execute();
        $cnt = $statement->fetchAll();
        return count($cnt);
    }

    public function getUserTviewCountFunction($uid) {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select sum(tview) as res from dah_trainings where uid = $uid    ");
        $statement->execute();
        $cnt = $statement->fetch();
        return $cnt['res'];
    }

    public function getWorkshopsCountFunction() {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_workshops   ");
        $statement->execute();
        $cnt = $statement->fetchAll();
        return count($cnt);
    }

    public function getTrainingsCountFunction() {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_trainings   ");
        $statement->execute();
        $cnt = $statement->fetchAll();
        return count($cnt);
    }

    public function getUserTrainingCountFunction($uid) {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_trainings where uid = $uid   ");
        $statement->execute();
        $cnt = $statement->fetchAll();
        return count($cnt);
    }

    public function getUserTrainingEnCountFunction($uid) {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_training_enrollment where uid = $uid   ");
        $statement->execute();
        $cnt = $statement->fetchAll();
        return count($cnt);
    }

    public function getUserActiveTrainingCountFunction($uid) {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_trainings where uid = $uid and status = 'active'   ");
        $statement->execute();
        $cnt = $statement->fetchAll();
        return count($cnt);
    }

    public function getUserOwnedTrainingEnCountFunction($uid) {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_trainings dt join dah_training_enrollment dte on dt.tid=dte.tid where dt.uid = $uid   ");
        $statement->execute();
        $cnt = $statement->fetchAll();
        return count($cnt);
    }

    public function getUserTrainingEnrollCountFunction($tid) {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_training_enrollment where tid = $tid   ");
        $statement->execute();
        $cnt = $statement->fetchAll();
        return count($cnt);
    }

    public function getUserTrainingCertCountFunction($tid) {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_training_enrollment where tid = $tid and certificate_status = 'issued'  ");
        $statement->execute();
        $cnt = $statement->fetchAll();
        return count($cnt);
    }

    public function getQOptionsFunction($qid) {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_question_options where qid = $qid  ");
        $statement->execute();
        $cnt = $statement->fetchAll();
        return $cnt;
    }

    public function getQOptionCheckedFunction($qid, $opid) {
        $qid = intval($qid);
        $opid = intval($opid);
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_training_key where qid = $qid and opid = $opid  ");
        $statement->execute();
        $cnt = $statement->fetch();
        if (!empty($cnt))
            return 'checked="checked"';
        else
            return '';
    }

    public function checkIsVideoFunction($tid) {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_training_videos where tid = $tid   ");
        $statement->execute();
        $cnt = $statement->fetchAll();
        if (!empty($cnt))
            return 'yes';
        else
            return 'no';
    }

    public function checkIsAssesFunction($tid) {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select * from dah_training_questions where tid = $tid   ");
        $statement->execute();
        $cnt = $statement->fetchAll();
        if (!empty($cnt))
            return 'yes';
        else
            return 'no';
    }

    

    public function getUserFeaturedTrainingCountFunction($uid) {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select count(*) as res from dah_trainings where uid = $uid and featured = 1 and status = 'active'   ");
        $statement->execute();
        $cnt = $statement->fetch();
        return $cnt['res'];
    }
    
     public function getUserTotalEnrolledCountFunction($uid) {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select count(*) as res from dah_training_enrollment dte join dah_trainings dt on dte.tid=dt.tid where dt.uid = $uid   ");
        $statement->execute();
        $cnt = $statement->fetch();
        return $cnt['res'];
    }
    
     public function getUserTotalCertCountFunction($uid) {
        $em = $this->doctrine->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare(" select count(*) as res from dah_training_enrollment dte join dah_trainings dt on dte.tid=dt.tid where dt.uid = $uid and dte.certificate_status = 'issued'  ");
        $statement->execute();
        $cnt = $statement->fetch();
        return $cnt['res'];
    }

}
