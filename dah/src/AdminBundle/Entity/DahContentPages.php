<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahContentPages
 */
class DahContentPages
{
    /**
     * @var string
     */
    private $pageName;

    /**
     * @var string
     */
    private $pageTitle;

    /**
     * @var string
     */
    private $pageUrl;

    /**
     * @var string
     */
    private $pageSubtitle;

    /**
     * @var string
     */
    private $pageContent;

    /**
     * @var string
     */
    private $pageImage;

    /**
     * @var string
     */
    private $pageMetaTitle;

    /**
     * @var string
     */
    private $pageMetaKeyword;

    /**
     * @var string
     */
    private $pageMetaDescription;

    /**
     * @var integer
     */
    private $addedOn;

    /**
     * @var integer
     */
    private $updatedOn;

    /**
     * @var integer
     */
    private $pageid;


    /**
     * Set pageName
     *
     * @param string $pageName
     * @return DahContentPages
     */
    public function setPageName($pageName)
    {
        $this->pageName = $pageName;

        return $this;
    }

    /**
     * Get pageName
     *
     * @return string 
     */
    public function getPageName()
    {
        return $this->pageName;
    }

    /**
     * Set pageTitle
     *
     * @param string $pageTitle
     * @return DahContentPages
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }

    /**
     * Get pageTitle
     *
     * @return string 
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * Set pageUrl
     *
     * @param string $pageUrl
     * @return DahContentPages
     */
    public function setPageUrl($pageUrl)
    {
        $this->pageUrl = $pageUrl;

        return $this;
    }

    /**
     * Get pageUrl
     *
     * @return string 
     */
    public function getPageUrl()
    {
        return $this->pageUrl;
    }

    /**
     * Set pageSubtitle
     *
     * @param string $pageSubtitle
     * @return DahContentPages
     */
    public function setPageSubtitle($pageSubtitle)
    {
        $this->pageSubtitle = $pageSubtitle;

        return $this;
    }

    /**
     * Get pageSubtitle
     *
     * @return string 
     */
    public function getPageSubtitle()
    {
        return $this->pageSubtitle;
    }

    /**
     * Set pageContent
     *
     * @param string $pageContent
     * @return DahContentPages
     */
    public function setPageContent($pageContent)
    {
        $this->pageContent = $pageContent;

        return $this;
    }

    /**
     * Get pageContent
     *
     * @return string 
     */
    public function getPageContent()
    {
        return $this->pageContent;
    }

    /**
     * Set pageImage
     *
     * @param string $pageImage
     * @return DahContentPages
     */
    public function setPageImage($pageImage)
    {
        $this->pageImage = $pageImage;

        return $this;
    }

    /**
     * Get pageImage
     *
     * @return string 
     */
    public function getPageImage()
    {
        return $this->pageImage;
    }

    /**
     * Set pageMetaTitle
     *
     * @param string $pageMetaTitle
     * @return DahContentPages
     */
    public function setPageMetaTitle($pageMetaTitle)
    {
        $this->pageMetaTitle = $pageMetaTitle;

        return $this;
    }

    /**
     * Get pageMetaTitle
     *
     * @return string 
     */
    public function getPageMetaTitle()
    {
        return $this->pageMetaTitle;
    }

    /**
     * Set pageMetaKeyword
     *
     * @param string $pageMetaKeyword
     * @return DahContentPages
     */
    public function setPageMetaKeyword($pageMetaKeyword)
    {
        $this->pageMetaKeyword = $pageMetaKeyword;

        return $this;
    }

    /**
     * Get pageMetaKeyword
     *
     * @return string 
     */
    public function getPageMetaKeyword()
    {
        return $this->pageMetaKeyword;
    }

    /**
     * Set pageMetaDescription
     *
     * @param string $pageMetaDescription
     * @return DahContentPages
     */
    public function setPageMetaDescription($pageMetaDescription)
    {
        $this->pageMetaDescription = $pageMetaDescription;

        return $this;
    }

    /**
     * Get pageMetaDescription
     *
     * @return string 
     */
    public function getPageMetaDescription()
    {
        return $this->pageMetaDescription;
    }

    /**
     * Set addedOn
     *
     * @param integer $addedOn
     * @return DahContentPages
     */
    public function setAddedOn($addedOn)
    {
        $this->addedOn = $addedOn;

        return $this;
    }

    /**
     * Get addedOn
     *
     * @return integer 
     */
    public function getAddedOn()
    {
        return $this->addedOn;
    }

    /**
     * Set updatedOn
     *
     * @param integer $updatedOn
     * @return DahContentPages
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get updatedOn
     *
     * @return integer 
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Get pageid
     *
     * @return integer 
     */
    public function getPageid()
    {
        return $this->pageid;
    }
}
