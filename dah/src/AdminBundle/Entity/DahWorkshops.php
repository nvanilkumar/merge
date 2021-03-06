<?php

namespace AdminBundle\Entity;

/**
 * DahWorkshops
 */
class DahWorkshops
{
    /**
     * @var string
     */
    private $workshopTitle;

    /**
     * @var string
     */
    private $workshopSubtitle;

    /**
     * @var string
     */
    private $workshopContent;

    /**
     * @var string
     */
    private $workshopVenue;

    /**
     * @var string
     */
    private $workshopImage;

    /**
     * @var integer
     */
    private $fromDate;

    /**
     * @var integer
     */
    private $toDate;

    /**
     * @var string
     */
    private $workshopSchedule;

    /**
     * @var string
     */
    private $workshopMetaTitle;

    /**
     * @var string
     */
    private $workshopMetaKeyword;

    /**
     * @var string
     */
    private $workshopMetaDescription;

    /**
     * @var integer
     */
    private $addedOn;

    /**
     * @var integer
     */
    private $updatedOn;
    
    /**
     * @var string
     *
     * @ORM\Column(name="public", type="string", nullable=true)
     */
    private $public = 'yes';

    /**
     * @var integer
     */
    private $wid;

    /**
     * @var \AdminBundle\Entity\DahDepartments
     */
    private $deptid;


    /**
     * Set workshopTitle
     *
     * @param string $workshopTitle
     *
     * @return DahWorkshops
     */
    public function setWorkshopTitle($workshopTitle)
    {
        $this->workshopTitle = $workshopTitle;

        return $this;
    }

    /**
     * Get workshopTitle
     *
     * @return string
     */
    public function getWorkshopTitle()
    {
        return $this->workshopTitle;
    }

    /**
     * Set workshopSubtitle
     *
     * @param string $workshopSubtitle
     *
     * @return DahWorkshops
     */
    public function setWorkshopSubtitle($workshopSubtitle)
    {
        $this->workshopSubtitle = $workshopSubtitle;

        return $this;
    }

    /**
     * Get workshopSubtitle
     *
     * @return string
     */
    public function getWorkshopSubtitle()
    {
        return $this->workshopSubtitle;
    }

    /**
     * Set workshopContent
     *
     * @param string $workshopContent
     *
     * @return DahWorkshops
     */
    public function setWorkshopContent($workshopContent)
    {
        $this->workshopContent = $workshopContent;

        return $this;
    }

    /**
     * Get workshopContent
     *
     * @return string
     */
    public function getWorkshopContent()
    {
        return $this->workshopContent;
    }

    /**
     * Set workshopVenue
     *
     * @param string $workshopVenue
     *
     * @return DahWorkshops
     */
    public function setWorkshopVenue($workshopVenue)
    {
        $this->workshopVenue = $workshopVenue;

        return $this;
    }

    /**
     * Get workshopVenue
     *
     * @return string
     */
    public function getWorkshopVenue()
    {
        return $this->workshopVenue;
    }

    /**
     * Set workshopImage
     *
     * @param string $workshopImage
     *
     * @return DahWorkshops
     */
    public function setWorkshopImage($workshopImage)
    {
        $this->workshopImage = $workshopImage;

        return $this;
    }

    /**
     * Get workshopImage
     *
     * @return string
     */
    public function getWorkshopImage()
    {
        return $this->workshopImage;
    }
    
    /**
     * Set public
     *
     * @param string $public
     *
     * @return DahTrainings
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return string
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set fromDate
     *
     * @param integer $fromDate
     *
     * @return DahWorkshops
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    /**
     * Get fromDate
     *
     * @return integer
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Set toDate
     *
     * @param integer $toDate
     *
     * @return DahWorkshops
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;

        return $this;
    }

    /**
     * Get toDate
     *
     * @return integer
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * Set workshopSchedule
     *
     * @param string $workshopSchedule
     *
     * @return DahWorkshops
     */
    public function setWorkshopSchedule($workshopSchedule)
    {
        $this->workshopSchedule = $workshopSchedule;

        return $this;
    }

    /**
     * Get workshopSchedule
     *
     * @return string
     */
    public function getWorkshopSchedule()
    {
        return $this->workshopSchedule;
    }

    /**
     * Set workshopMetaTitle
     *
     * @param string $workshopMetaTitle
     *
     * @return DahWorkshops
     */
    public function setWorkshopMetaTitle($workshopMetaTitle)
    {
        $this->workshopMetaTitle = $workshopMetaTitle;

        return $this;
    }

    /**
     * Get workshopMetaTitle
     *
     * @return string
     */
    public function getWorkshopMetaTitle()
    {
        return $this->workshopMetaTitle;
    }

    /**
     * Set workshopMetaKeyword
     *
     * @param string $workshopMetaKeyword
     *
     * @return DahWorkshops
     */
    public function setWorkshopMetaKeyword($workshopMetaKeyword)
    {
        $this->workshopMetaKeyword = $workshopMetaKeyword;

        return $this;
    }

    /**
     * Get workshopMetaKeyword
     *
     * @return string
     */
    public function getWorkshopMetaKeyword()
    {
        return $this->workshopMetaKeyword;
    }

    /**
     * Set workshopMetaDescription
     *
     * @param string $workshopMetaDescription
     *
     * @return DahWorkshops
     */
    public function setWorkshopMetaDescription($workshopMetaDescription)
    {
        $this->workshopMetaDescription = $workshopMetaDescription;

        return $this;
    }

    /**
     * Get workshopMetaDescription
     *
     * @return string
     */
    public function getWorkshopMetaDescription()
    {
        return $this->workshopMetaDescription;
    }

    /**
     * Set addedOn
     *
     * @param integer $addedOn
     *
     * @return DahWorkshops
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
     *
     * @return DahWorkshops
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
     * Get wid
     *
     * @return integer
     */
    public function getWid()
    {
        return $this->wid;
    }

    /**
     * Set deptid
     *
     * @param \AdminBundle\Entity\DahDepartments $deptid
     *
     * @return DahWorkshops
     */
    public function setDeptid(\AdminBundle\Entity\DahDepartments $deptid = null)
    {
        $this->deptid = $deptid;

        return $this;
    }

    /**
     * Get deptid
     *
     * @return \AdminBundle\Entity\DahDepartments
     */
    public function getDeptid()
    {
        return $this->deptid;
    }
    /**
     * @var string
     */
    private $speakersInfo;


    /**
     * Set speakersInfo
     *
     * @param string $speakersInfo
     *
     * @return DahWorkshops
     */
    public function setSpeakersInfo($speakersInfo)
    {
        $this->speakersInfo = $speakersInfo;

        return $this;
    }

    /**
     * Get speakersInfo
     *
     * @return string
     */
    public function getSpeakersInfo()
    {
        return $this->speakersInfo;
    }
}
