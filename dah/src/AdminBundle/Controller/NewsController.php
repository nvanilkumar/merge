<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use AdminBundle\Entity\DahNews;
use AdminBundle\Entity\DahNewsletterMessageQueue;

class NewsController extends Controller {

    public static $template = "AdminBundle:templates:default.html.twig";
    public $data = array();

    public function __construct() {
        $this->data['extend_view'] = self::$template;
    }

    /**
     * @Route("/secure/news/manage/{page}", name="_admin_news" , defaults={"page"=1} )
     * @Template("AdminBundle:News:manage.html.twig")
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
            return $this->redirect($this->generateUrl('_admin_news'));
        }
        if ($key != '') {
            
            $where .= " and ( news_title like '%" . $key . "%' or news_subtitle like '%" . $key . "%'   ) ";
        }
        $statement = $connection->prepare("  select * from dah_news " . $where . " order by added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $pages = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_news " . $where . " ");
        $statement->execute();
        $totalcontentpages = $statement->fetchAll();
        $totalpages = ceil(count($totalcontentpages) / $limit);
        $paginate = $common->paginate('_admin_news', array('keyword' => $key), $totalpages, $page, 5);
        $this->data['news'] = $pages;
        $this->data['key'] = $key;
        $this->data['paginate'] = $paginate;
        $this->data['page'] = $page;
        $this->data['title'] = 'Administrator login';
        $this->data['totalpages'] = count($totalcontentpages);
		
		if(count($totalcontentpages)<$limit)
		{
			$limit=count($totalcontentpages);
		}	
	
	if(count($totalcontentpages)>0)
		{
			if($page>1){
				$l=((($page - 1) * $limit) + 1)+$limit;
				 $this->data['range'] = ((($page - 1) * $limit) + 1) . " - " . $l;
			}
			else{
				 $this->data['range'] = ((($page - 1) * $limit) + 1) . " - " . $limit;
			}
		}
		
       

        return $this->data;
    }

    /**
     * @Route("/secure/news/add_new", name="_admin_add_new_news")
     * @Template("AdminBundle:News:addNew.html.twig")
     */
    public function addNewAction(Request $request) {
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $enteredDate = 0;
        $todaysTimestamp = strtotime(date('d-m-Y'));
        if ($request->getMethod() == 'POST') {
            $newsTitle = trim($request->request->get('newsTitle'));
            $subTitle = trim($request->request->get('subTitle'));
            $newsContent = trim($request->request->get('newsContent'));
            $futureDate = trim($request->request->get('futureDate'));
            $metaTitle = trim($request->request->get('metaTitle'));
            $metaKeywords = trim($request->request->get('metaKeywords'));
            $metaDescription = trim($request->request->get('metaDescription'));
            $newsletter = $request->request->get('newsletter') ? $request->request->get('newsletter') : 'no';
            if ($newsTitle == '') {
                $error['newsTitle'] = "This field cannot be blank";
            }
            if ($futureDate != '') {

                $enteredDate = strtotime($futureDate);

                if ($enteredDate < $todaysTimestamp) {
                    $error['futureDate'] = "Please enter a future date";
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
                $newname = '';
                if (!empty($_FILES) && isset($_FILES['manualUpload']) && $_FILES['manualUpload']['size'] > 0) {
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
                $news = new DahNews();
                $news->setNewsTitle($newsTitle);
                $news->setNewsSubtitle($subTitle);
                $news->setNewsContent($newsContent);
                $news->setNewsMetaTitle($metaTitle);
                $news->setNewsMetaKeyword($metaKeywords);
                $news->setNewsMetaDescription($metaDescription);
                $news->setNewsletter($newsletter);
                if ($enteredDate > 0) {
                    $news->setFutureDate($enteredDate);
                }
                $news->setNewsImage($newname);
                $news->setAddedOn(time());
                $em->persist($news);
                $em->flush();
                if ($newsletter == 'yes') {
                    $statement = $connection->prepare("  select * from dah_newsletter_subscribers where status = 'active' order by subscribed_on desc ");
                    $statement->execute();
                    $subscribers = $statement->fetchAll();
                    foreach ($subscribers as $sub) {
                        $dnmq = new DahNewsletterMessageQueue();
                        $dnmq->setNewsid($news);
                        $dnmq->setEmail($sub['email']);
                        $dnmq->setStatus('pending');
                        if ($enteredDate > 0) {
                            $dnmq->setPublishdate($enteredDate);
                        } else {
                            $dnmq->setPublishdate($todaysTimestamp);
                        }
                        $em->persist($dnmq);
                        $em->flush();
                    }
                }
                $common->logActivity('added new news <a href="' . $this->generateUrl('_admin_edit_news', array('newsid' => $news->getNewsid())) . '">' . $newsTitle . '</a>');
                $this->get('session')->getFlashBag()->add('success', ' New News added successfully.');
                return $this->redirect($this->generateUrl('_admin_news'));
            }
        }
        $this->data['error'] = $error;
        $this->data['mode'] = "add";
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

    /**
     * @Route("/secure/news/edit/{newsid}", name="_admin_edit_news",defaults={"newsid"=0})
     * @Template("AdminBundle:News:addNew.html.twig")
     */
    public function editAction(Request $request, $newsid) {
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahNews');
        $news = $repository->findOneBy(
                array('newsid' => $newsid)
        );
        if (!$news) {
            return $this->redirect($this->generateUrl('_admin_news'));
        }
        $common = $this->get('common_handler');
        $emailhand = $this->get('emails_handler');
        $error = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        $connection = $em->getConnection();
        $enteredDate = 0;
        $todaysTimestamp = strtotime(date('d-m-Y'));
        if ($request->getMethod() == 'POST') {
            $newsTitle = trim($request->request->get('newsTitle'));
            $subTitle = trim($request->request->get('subTitle'));
            $newsContent = trim($request->request->get('newsContent'));
            $futureDate = trim($request->request->get('futureDate'));
            $metaTitle = trim($request->request->get('metaTitle'));
            $metaKeywords = trim($request->request->get('metaKeywords'));
            $metaDescription = trim($request->request->get('metaDescription'));
            $uploadedFile = trim($request->request->get('uploadedFile'));
            $newsletter = $request->request->get('newsletter') ? $request->request->get('newsletter') : 'no';
            if ($newsTitle == '') {
                $error['newsTitle'] = "This field cannot be blank";
            }
            if ($futureDate != '') {

                $enteredDate = strtotime($futureDate);

                if ($enteredDate < $todaysTimestamp) {
                    $error['futureDate'] = "Please enter a future date";
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
                $newname = $uploadedFile;
                if (!empty($_FILES) && isset($_FILES['manualUpload']) && $_FILES['manualUpload']['size'] > 0) {
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
                $news->setNewsTitle($newsTitle);
                $news->setNewsSubtitle($subTitle);
                $news->setNewsContent($newsContent);
                $news->setNewsMetaTitle($metaTitle);
                $news->setNewsMetaKeyword($metaKeywords);
                $news->setNewsMetaDescription($metaDescription);
                $news->setNewsletter($newsletter);
                if ($enteredDate > 0) {
                    $news->setFutureDate($enteredDate);
                }
                $news->setNewsImage($newname);
                $news->setUpdatedOn(time());
                $em->persist($news);
                $em->flush();
                if ($newsletter == 'yes') {
                    $statement = $connection->prepare("  select * from dah_newsletter_subscribers where status = 'active' order by subscribed_on desc ");
                    $statement->execute();
                    $subscribers = $statement->fetchAll();
                    foreach ($subscribers as $sub) {
                        $dnmq = new DahNewsletterMessageQueue();
                        $dnmq->setNewsid($news);
                        $dnmq->setEmail($sub['email']);
                        $dnmq->setStatus('pending');
                        if ($enteredDate > 0) {
                            $dnmq->setPublishdate($enteredDate);
                        } else {
                            $dnmq->setPublishdate($todaysTimestamp);
                        }
                        $em->persist($dnmq);
                        $em->flush();
                    }
                }
                $common->logActivity('updated news <a href="' . $this->generateUrl('_admin_edit_news', array('newsid' => $news->getNewsid())) . '">' . $newsTitle . '</a>');
                $this->get('session')->getFlashBag()->add('success', ' News updated successfully.');
                return $this->redirect($this->generateUrl('_admin_news'));
            }
        }
        $this->data['error'] = $error;
        $this->data['mode'] = "edit";
        $this->data['news'] = $news;
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

}
