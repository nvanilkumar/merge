<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Campaign
 *
 * @ORM\Table(name="campaign", indexes={@ORM\Index(name="ct_id", columns={"ct_id"})})
 * @ORM\Entity
 */
class Campaign
{
    /**
     * @var string
     *
     * @ORM\Column(name="campaign_name", type="string", length=50, nullable=false)
     */
    private $campaignName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="from_date", type="datetime", nullable=false)
     */
    private $fromDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="to_date", type="datetime", nullable=false)
     */
    private $toDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="from_time", type="datetime", nullable=false)
     */
    private $fromTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="to_time", type="datetime", nullable=false)
     */
    private $toTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="monday", type="boolean", nullable=false)
     */
    private $monday;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuesday", type="boolean", nullable=false)
     */
    private $tuesday;

    /**
     * @var boolean
     *
     * @ORM\Column(name="wednesday", type="boolean", nullable=false)
     */
    private $wednesday;

    /**
     * @var boolean
     *
     * @ORM\Column(name="thursday", type="boolean", nullable=false)
     */
    private $thursday;

    /**
     * @var boolean
     *
     * @ORM\Column(name="friday", type="boolean", nullable=false)
     */
    private $friday;

    /**
     * @var boolean
     *
     * @ORM\Column(name="saturday", type="boolean", nullable=false)
     */
    private $saturday;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sunday", type="boolean", nullable=false)
     */
    private $sunday;

    /**
     * @var string
     *
     * @ORM\Column(name="campaign_status", type="string", nullable=true)
     */
    private $campaignStatus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="added_on", type="datetime", nullable=false)
     */
    private $addedOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_on", type="datetime", nullable=false)
     */
    private $updatedOn;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_deleted", type="boolean", nullable=true)
     */
    private $isDeleted;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_running", type="boolean", nullable=true)
     */
    private $isRunning;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_complete", type="boolean", nullable=true)
     */
    private $isComplete;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_paused", type="boolean", nullable=true)
     */
    private $isPaused;
    
    

    /**
     * @var integer
     *
     * @ORM\Column(name="campaign_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $campaignId;

    /**
     * @var \AppBundle\Entity\CampaignType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CampaignType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ct_id", referencedColumnName="ct_id")
     * })
     */
    private $ct;

    /**
     * @var string
     *
     * @ORM\Column(name="pdf_report", type="string", length=150, nullable=true)
     */
    private $pdfReport;
    /**
     * @var integer
     *
     * @ORM\Column(name="retry_count", type="integer", nullable=false)
     */
    private $retryCount;
     /**
     * @var string
     *
     * @ORM\Column(name="voice_file", type="string",length=50, nullable=false)
     */
    private $voiceFile;
    /**
     * Set pdfReport
     *
     * @param string $pdfReport
     * @return DialStatus
     */
    public function setPdfReport($pdfReport)
    {
        $this->pdfReport = $pdfReport;

        return $this;
    }

    /**
     * Get pdfReport
     *
     * @return string 
     */
    public function getPdfReport()
    {
        return $this->pdfReport;
    }


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
     * Set isPaused
     *
     * @param boolean $isPaused
     * @return Campaign
     */
    public function setIsPaused($isPaused)
    {
        $this->isPaused = $isPaused;

        return $this;
    }

    /**
     * Get isPaused
     *
     * @return boolean 
     */
    public function getIsPaused()
    {
        return $this->isPaused;
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
     * @param \AppBundle\Entity\CampaignType $ct
     * @return Campaign
     */
    public function setCt(\AppBundle\Entity\CampaignType $ct = null)
    {
        $this->ct = $ct;

        return $this;
    }

    /**
     * Get ct
     *
     * @return \AppBundle\Entity\CampaignType 
     */
    public function getCt()
    {
        return $this->ct;
    }


      /**
     * Set retryCount
     *
     * @param integer $retryCount
     * @return CampaignData
     */
    public function setRetryCount($retryCount) {
        $this->retryCount = $retryCount;

        return $this;
    }

    /**
     * Get retryCount
     *
     * @return integer
     */
    public function getRetryCount() {
        return $this->retryCount;
    }


     /**
     * Get voiceFile
     *
     * @return srting
     */
    public function getVoiceFile()
    {
        return $this->voiceFile;
    }

    /**
     * Set voiceFile
     *
     * @param string voiceFile
     * @return voiceFile
     */
    public function setVoiceFile($voiceFile)
    {
        $this->voiceFile = $voiceFile;

        return $this;
    }
}
