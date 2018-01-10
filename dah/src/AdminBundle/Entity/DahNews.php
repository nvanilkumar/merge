<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahNews
 */
class DahNews
{
    /**
     * @var string
     */
    private $newsTitle;

    /**
     * @var string
     */
    private $newsSubtitle;

    /**
     * @var string
     */
    private $newsContent;

    /**
     * @var string
     */
    private $newsImage;

    /**
     * @var integer
     */
    private $futureDate;

    /**
     * @var string
     */
    private $newsletter;

    /**
     * @var string
     */
    private $newsMetaTitle;

    /**
     * @var string
     */
    private $newsMetaKeyword;

    /**
     * @var string
     */
    private $newsMetaDescription;

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
    private $newsid;


    /**
     * Set newsTitle
     *
     * @param string $newsTitle
     * @return DahNews
     */
    public function setNewsTitle($newsTitle)
    {
        $this->newsTitle = $newsTitle;

        return $this;
    }

    /**
     * Get newsTitle
     *
     * @return string 
     */
    public function getNewsTitle()
    {
        return $this->newsTitle;
    }

    /**
     * Set newsSubtitle
     *
     * @param string $newsSubtitle
     * @return DahNews
     */
    public function setNewsSubtitle($newsSubtitle)
    {
        $this->newsSubtitle = $newsSubtitle;

        return $this;
    }

    /**
     * Get newsSubtitle
     *
     * @return string 
     */
    public function getNewsSubtitle()
    {
        return $this->newsSubtitle;
    }

    /**
     * Set newsContent
     *
     * @param string $newsContent
     * @return DahNews
     */
    public function setNewsContent($newsContent)
    {
        $this->newsContent = $newsContent;

        return $this;
    }

    /**
     * Get newsContent
     *
     * @return string 
     */
    public function getNewsContent()
    {
        return $this->newsContent;
    }

    /**
     * Set newsImage
     *
     * @param string $newsImage
     * @return DahNews
     */
    public function setNewsImage($newsImage)
    {
        $this->newsImage = $newsImage;

        return $this;
    }

    /**
     * Get newsImage
     *
     * @return string 
     */
    public function getNewsImage()
    {
        return $this->newsImage;
    }

    /**
     * Set futureDate
     *
     * @param integer $futureDate
     * @return DahNews
     */
    public function setFutureDate($futureDate)
    {
        $this->futureDate = $futureDate;

        return $this;
    }

    /**
     * Get futureDate
     *
     * @return integer 
     */
    public function getFutureDate()
    {
        return $this->futureDate;
    }

    /**
     * Set newsletter
     *
     * @param string $newsletter
     * @return DahNews
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * Get newsletter
     *
     * @return string 
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Set newsMetaTitle
     *
     * @param string $newsMetaTitle
     * @return DahNews
     */
    public function setNewsMetaTitle($newsMetaTitle)
    {
        $this->newsMetaTitle = $newsMetaTitle;

        return $this;
    }

    /**
     * Get newsMetaTitle
     *
     * @return string 
     */
    public function getNewsMetaTitle()
    {
        return $this->newsMetaTitle;
    }

    /**
     * Set newsMetaKeyword
     *
     * @param string $newsMetaKeyword
     * @return DahNews
     */
    public function setNewsMetaKeyword($newsMetaKeyword)
    {
        $this->newsMetaKeyword = $newsMetaKeyword;

        return $this;
    }

    /**
     * Get newsMetaKeyword
     *
     * @return string 
     */
    public function getNewsMetaKeyword()
    {
        return $this->newsMetaKeyword;
    }

    /**
     * Set newsMetaDescription
     *
     * @param string $newsMetaDescription
     * @return DahNews
     */
    public function setNewsMetaDescription($newsMetaDescription)
    {
        $this->newsMetaDescription = $newsMetaDescription;

        return $this;
    }

    /**
     * Get newsMetaDescription
     *
     * @return string 
     */
    public function getNewsMetaDescription()
    {
        return $this->newsMetaDescription;
    }

    /**
     * Set addedOn
     *
     * @param integer $addedOn
     * @return DahNews
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
     * @return DahNews
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
     * Get newsid
     *
     * @return integer 
     */
    public function getNewsid()
    {
        return $this->newsid;
    }
}
