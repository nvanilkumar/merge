<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Campaign
 */
class Campaign
{
    /**
     * @var string
     */
    private $campaignName;

    /**
     * @var \DateTime
     */
    private $fromDate;

    /**
     * @var \DateTime
     */
    private $toDate;

    /**
     * @var \DateTime
     */
    private $fromTime;

    /**
     * @var \DateTime
     */
    private $toTime;

    /**
     * @var boolean
     */
    private $monday;

    /**
     * @var boolean
     */
    private $tuesday;

    /**
     * @var boolean
     */
    private $wednesday;

    /**
     * @var boolean
     */
    private $thursday;

    /**
     * @var boolean
     */
    private $friday;

    /**
     * @var boolean
     */
    private $saturday;

    /**
     * @var boolean
     */
    private $sunday;

    /**
     * @var string
     */
    private $campaignStatus;

    /**
     * @var \DateTime
     */
    private $addedOn;

    /**
     * @var \DateTime
     */
    private $updatedOn;

    /**
     * @var boolean
     */
    private $isDeleted;

    /**
     * @var boolean
     */
    private $isRunning;

    /**
     * @var boolean
     */
    private $isComplete;

    /**
     * @var integer
     */
    private $campaignId;

    /**
     * @var \ApiBundle\Entity\CampaignType
     */
    private $ct;


    /**
     * Set campaignName
     *
     * @param string $campaignName
     * @return Campaign
     */
    public function setCampaignName($campaignName)
    {
        $this->campaignName = $campaignName;

        return $this;
    }

    /**
     * Get campaignName
     *
     * @return string 
     */
    public function getCampaignName()
    {
        return $this->campaignName;
    }

    /**
     * Set fromDate
     *
     * @param \DateTime $fromDate
     * @return Campaign
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    /**
     * Get fromDate
     *
     * @return \DateTime 
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Set toDate
     *
     * @param \DateTime $toDate
     * @return Campaign
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;

        return $this;
    }

    /**
     * Get toDate
     *
     * @return \DateTime 
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * Set fromTime
     *
     * @param \DateTime $fromTime
     * @return Campaign
     */
    public function setFromTime($fromTime)
    {
        $this->fromTime = $fromTime;

        return $this;
    }

    /**
     * Get fromTime
     *
     * @return \DateTime 
     */
    public function getFromTime()
    {
        return $this->fromTime;
    }

    /**
     * Set toTime
     *
     * @param \DateTime $toTime
     * @return Campaign
     */
    public function setToTime($toTime)
    {
        $this->toTime = $toTime;

        return $this;
    }

    /**
     * Get toTime
     *
     * @return \DateTime 
     */
    public function getToTime()
    {
        return $this->toTime;
    }

    /**
     * Set monday
     *
     * @param boolean $monday
     * @return Campaign
     */
    public function setMonday($monday)
    {
        $this->monday = $monday;

        return $this;
    }

    /**
     * Get monday
     *
     * @return boolean 
     */
    public function getMonday()
    {
        return $this->monday;
    }

    /**
     * Set tuesday
     *
     * @param boolean $tuesday
     * @return Campaign
     */
    public function setTuesday($tuesday)
    {
        $this->tuesday = $tuesday;

        return $this;
    }

    /**
     * Get tuesday
     *
     * @return boolean 
     */
    public function getTuesday()
    {
        return $this->tuesday;
    }

    /**
     * Set wednesday
     *
     * @param boolean $wednesday
     * @return Campaign
     */
    public function setWednesday($wednesday)
    {
        $this->wednesday = $wednesday;

        return $this;
    }

    /**
     * Get wednesday
     *
     * @return boolean 
     */
    public function getWednesday()
    {
        return $this->wednesday;
    }

    /**
     * Set thursday
     *
     * @param boolean $thursday
     * @return Campaign
     */
    public function setThursday($thursday)
    {
        $this->thursday = $thursday;

        return $this;
    }

    /**
     * Get thursday
     *
     * @return boolean 
     */
    public function getThursday()
    {
        return $this->thursday;
    }

    /**
     * Set friday
     *
     * @param boolean $friday
     * @return Campaign
     */
    public function setFriday($friday)
    {
        $this->friday = $friday;

        return $this;
    }

    /**
     * Get friday
     *
     * @return boolean 
     */
    public function getFriday()
    {
        return $this->friday;
    }

    /**
     * Set saturday
     *
     * @param boolean $saturday
     * @return Campaign
     */
    public function setSaturday($saturday)
    {
        $this->saturday = $saturday;

        return $this;
    }

    /**
     * Get saturday
     *
     * @return boolean 
     */
    public function getSaturday()
    {
        return $this->saturday;
    }

    /**
     * Set sunday
     *
     * @param boolean $sunday
     * @return Campaign
     */
    public function setSunday($sunday)
    {
        $this->sunday = $sunday;

        return $this;
    }

    /**
     * Get sunday
     *
     * @return boolean 
     */
    public function getSunday()
    {
        return $this->sunday;
    }

    /**
     * Set campaignStatus
     *
     * @param string $campaignStatus
     * @return Campaign
     */
    public function setCampaignStatus($campaignStatus)
    {
        $this->campaignStatus = $campaignStatus;

        return $this;
    }

    /**
     * Get campaignStatus
     *
     * @return string 
     */
    public function getCampaignStatus()
    {
        return $this->campaignStatus;
    }

    /**
     * Set addedOn
     *
     * @param \DateTime $addedOn
     * @return Campaign
     */
    public function setAddedOn($addedOn)
    {
        $this->addedOn = $addedOn;

        return $this;
    }

    /**
     * Get addedOn
     *
     * @return \DateTime 
     */
    public function getAddedOn()
    {
        return $this->addedOn;
    }

    /**
     * Set updatedOn
     *
     * @param \DateTime $updatedOn
     * @return Campaign
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get updatedOn
     *
     * @return \DateTime 
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     * @return Campaign
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean 
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set isRunning
     *
     * @param boolean $isRunning
     * @return Campaign
     */
    public function setIsRunning($isRunning)
    {
        $this->isRunning = $isRunning;

        return $this;
    }

    /**
     * Get isRunning
     *
     * @return boolean 
     */
    public function getIsRunning()
    {
        return $this->isRunning;
    }

    /**
     * Set isComplete
     *
     * @param boolean $isComplete
     * @return Campaign
     */
    public function setIsComplete($isComplete)
    {
        $this->isComplete = $isComplete;

        return $this;
    }

    /**
     * Get isComplete
     *
     * @return boolean 
     */
    public function getIsComplete()
    {
        return $this->isComplete;
    }

    /**
     * Get campaignId
     *
     * @return integer 
     */
    public function getCampaignId()
    {
        return $this->campaignId;
    }

    /**
     * Set ct
     *
     * @param \ApiBundle\Entity\CampaignType $ct
     * @return Campaign
     */
    public function setCt(\ApiBundle\Entity\CampaignType $ct = null)
    {
        $this->ct = $ct;

        return $this;
    }

    /**
     * Get ct
     *
     * @return \ApiBundle\Entity\CampaignType 
     */
    public function getCt()
    {
        return $this->ct;
    }
}
