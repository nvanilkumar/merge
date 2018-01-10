<?php

namespace AdminBundle\Services;

class CommonHandler {

    protected $router;
    protected $session;
    protected $em;

    public function __construct($router, $session, $em, $security) {

        $this->router = $router;
        $this->session = $session;
        $this->em = $em;
        $this->security = $security;
    }

    public function loginCheck() {
        if ($this->session->has('admin_login') && $this->session->get('admin_login') != '') {
            return true;
        } else {

            return false;
        }
    }

    function test() {
        return 'hello';
    }

    function paginate($base_url, $query_str, $total_pages, $current_page, $paginate_limit) {

        $page_array = array();

        $dotshow = true;

        for ($i = 1; $i <= $total_pages; $i ++) {
            if ($i == 1 || $i == $total_pages || ($i >= $current_page - $paginate_limit && $i <= $current_page + $paginate_limit)) {
                $dotshow = true;
                if ($i != $current_page) {
                    $query_str['page'] = $i;
                    $page_array[$i]['url'] = $this->router->generate($base_url, $query_str); //$base_url . "?" . $query_str ."=" . $i;
                }
                $page_array[$i]['text'] = strval($i);
            } else if ($dotshow == true) {
                $dotshow = false;
                $page_array[$i]['text'] = "...";
            }
        }
        return $page_array;
    }

    function logActivity($message = '', $adminid = 0) {
        if ($message != '') {
            if ($adminid == 0) {
                $connection = $this->em->getConnection();
                $message = addslashes($message);
                $usr = $this->security->getToken()->getUser();
                $statement = $connection->prepare("  INSERT INTO dah_activity_log (adminid, message, logged_on)
VALUES (" . $usr->getAdminid() . ", '" . $message . "', " . time() . ")  ");
                $statement->execute();
            } elseif ($adminid > 0) {
                $connection = $this->em->getConnection();
                $message = addslashes($message);
                $statement = $connection->prepare("  INSERT INTO dah_activity_log (adminid, message, logged_on)
VALUES (" . $adminid . ", '" . $message . "', " . time() . ")  ");
                $statement->execute();
            }
        }
    }

}
