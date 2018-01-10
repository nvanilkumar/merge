<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use AdminBundle\Entity\DahContentPages;

class PagesController extends Controller {

    public static $template = "AdminBundle:templates:default.html.twig";
    public $data = array();

    public function __construct() {
        $this->data['extend_view'] = self::$template;
    }

    /**
     * @Route("/secure/pages/manage/{page}", name="_admin_pages" , defaults={"page"=1} )
     * @Template("AdminBundle:Pages:manage.html.twig")
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
            return $this->redirect($this->generateUrl('_admin_pages'));
        }
        if ($key != '') {
            
            $where .= " and ( page_url like '%" . $key . "%' or page_name like '%" . $key . "%' or page_title like '%" . $key . "%' or page_subtitle like '%" . $key . "%'  ) ";
        }
        $statement = $connection->prepare("  select * from dah_content_pages " . $where . " order by added_on desc limit " . (($page - 1) * $limit) . ",$limit ");
        $statement->execute();
        $pages = $statement->fetchAll();
        $statement = $connection->prepare("  select * from dah_content_pages " . $where . " ");
        $statement->execute();
        $totalcontentpages = $statement->fetchAll();
        $totalpages = ceil(count($totalcontentpages) / $limit);
        $paginate = $common->paginate('_admin_pages', array('keyword' => $key), $totalpages, $page, 5);
        $this->data['pages'] = $pages;
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
     * @Route("/secure/pages/add_new", name="_admin_add_new_page")
     * @Template("AdminBundle:Pages:addNew.html.twig")
     */
    public function addNewAction(Request $request) {
        $this->data['mode'] = "add";
        $this->data['title'] = 'Administrator login';
        $common = $this->get('common_handler');
        $error = array();
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        if ($request->getMethod() == 'POST') {
            $repository = $this->getDoctrine()->getRepository('AdminBundle:DahContentPages');
            $existingPagelink = $repository->findBy(
                    array('pageUrl' => trim($request->request->get('pageLink')))
            );

            $existingPageName = $repository->findBy(
                    array('pageName' => trim($request->request->get('pageName')))
            );
            $subject = trim($request->request->get('pageLink'));
            $pattern = "/^[a-zA-Z0-9\-\_]{4,30}$/";
            if (!(preg_match($pattern, $subject))) {
                $error['pageLink'] = 'Please enter a valid link';
            }
            if (trim($request->request->get('pageLink')) == '') {
                $error['pageLink'] = 'Please enter a page link';
            }
            if (count($existingPagelink) > 0) {
                $error['pageLink'] = 'Page link already exists';
            }
            if (trim($request->request->get('pageTitle')) == '') {
                $error['pageTitle'] = 'Please enter a page title';
            }
            if (trim($request->request->get('pageName')) == '') {
                $error['pageName'] = 'Please enter a page name';
            }
            if (count($existingPageName) > 0) {
                $error['pageName'] = 'Page name already exists';
            }
            $newname = '';
            if (!empty($_FILES) && isset($_FILES)) {

                $tempFile = $_FILES['imageupload']['tmp_name'];


                //$newname= $_FILES['imageupload']['name'];
                if (isset($tempFile) && !empty($tempFile) && $tempFile != '') {
                    $fileParts = pathinfo($_FILES['imageupload']['name']);
                    $newname = md5(uniqid()) . '.' . strtolower($fileParts['extension']);
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
            if (empty($error)) {
                $admin = new DahContentPages();
                $admin->setPageUrl(trim($request->request->get('pageLink')));
                $admin->setPageName(trim($request->request->get('pageName')));
                $admin->setPageTitle(trim($request->request->get('pageTitle')));
                $admin->setPageSubtitle(trim($request->request->get('subTitle')));
                $admin->setPageContent(trim($request->request->get('pageDescription')));
                $admin->setPageImage($newname);
                $admin->setPageMetaTitle(trim($request->request->get('metaTitle')));
                $admin->setPageMetaKeyword(trim($request->request->get('metaKeywords')));
                $admin->setPageMetaDescription(trim($request->request->get('metaDescription')));
                $admin->setAddedOn(time());
                //   $admin->setStatus('active');
                $em->persist($admin);
                $em->flush();
                $id = $admin->getPageId();
                $common->logActivity('added page <a href="' . $this->generateUrl('_admin_edit_page', array('pageid' => $id)) . '">' . trim($request->request->get('pageName')) . '</a>');

                $this->get('session')->getFlashBag()->add('success', ' New Content page added successfully.');
                return $this->redirect($this->generateUrl('_admin_pages'));
            }
        }
        $this->data['mode'] = 'add';
        $this->data['error'] = $error;
        $this->data['title'] = 'Administrator login';
        return $this->data;
    }

    /**
     * @Route("/secure/pages/edit/{pageid}", name="_admin_edit_page",defaults={"pageid"=0})
     * @Template("AdminBundle:Pages:addNew.html.twig")
     */
    public function editAction(Request $request, $pageid) {

        $this->data['mode'] = "edit";
        $common = $this->get('common_handler');
        $error = array();
        $this->data['title'] = 'Administrator login';
        $repository = $this->getDoctrine()->getRepository('AdminBundle:DahContentPages');
        $page = $repository->find($pageid);
        if (!$page) {
            return $this->redirect($this->generateUrl('_admin_pages'));
        }
        $em = $this->getDoctrine()->getManager();
        $validator = $this->get('validator');
        if ($request->getMethod() == 'POST') {

            $pagename = trim($request->request->get('pageName'));
            $pagelink = trim($request->request->get('pageLink'));
            $em = $this->getDoctrine()->getManager();
            $connection = $em->getConnection();
            $statement = $connection->prepare("  select * from dah_content_pages where page_url = '$pagelink' and pageid != '" . $pageid . "' ");
            $statement->execute();
            $existingPagelink = $statement->fetchAll();
            $statement = $connection->prepare("  select * from dah_content_pages where page_name = '$pagename' and pageid != '" . $pageid . "' ");
            $statement->execute();
            $existingPageName = $statement->fetchAll();

            if (trim($request->request->get('pageLink')) == '') {
                $error['pageLink'] = 'Please enter a page link';
            }
            $subject = trim($request->request->get('pageLink'));
            $pattern = "/^[a-zA-Z0-9\-\_]{4,30}$/";
            if (!(preg_match($pattern, $subject))) {
                $error['pageLink'] = 'Please enter a valid link';
            }
            if (count($existingPagelink) > 0) {
                $error['pageLink'] = 'Page link already exists';
            }
            if (trim($request->request->get('pageTitle')) == '') {
                $error['pageTitle'] = 'Please enter a page title';
            }
            if (trim($request->request->get('pageName')) == '') {
                $error['pageName'] = 'Please enter a page name';
            }
            if (count($existingPageName) > 0) {
                $error['pageName'] = 'Page name already exists';
            }

            if (!empty($_FILES) && isset($_FILES)) {
                $tempFile = $_FILES['imageupload']['tmp_name'];


                if (isset($tempFile) && !empty($tempFile) && $tempFile != '') {
                    $fileParts = pathinfo($_FILES['imageupload']['name']);
                    $newname = md5(uniqid()) . '.' . strtolower($fileParts['extension']);
                    //$newname= $_FILES['imageupload']['name'];
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
            if (empty($error)) {

                $page->setPageUrl(trim($request->request->get('pageLink')));
                $page->setPageName(trim($request->request->get('pageName')));
                $page->setPageTitle(trim($request->request->get('pageTitle')));
                $page->setPageSubtitle(trim($request->request->get('subTitle')));
                $page->setPageContent(trim($request->request->get('pageDescription')));
                if (isset($newname) && $newname !== '') {
                    $page->setPageImage($newname);
                }

                $page->setPageMetaTitle(trim($request->request->get('metaTitle')));
                $page->setPageMetaKeyword(trim($request->request->get('metaKeywords')));
                $page->setPageMetaDescription(trim($request->request->get('metaDescription')));
                $page->setUpdatedOn(time());
                $em->persist($page);
                $em->flush();
                $common->logActivity('updated page <a href="' . $this->generateUrl('_admin_edit_page', array('pageid' => $pageid)) . '">' . trim($request->request->get('pageName')) . '</a>');
                $this->get('session')->getFlashBag()->add('success', ' Content page updated successfully.');
                return $this->redirect($this->generateUrl('_admin_pages'));
            }
        }
        $this->data['mode'] = 'edit';
        $this->data['error'] = $error;
        $this->data['content_page'] = $page;
        return $this->data;
    }

}
