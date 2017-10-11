<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CampaignData
 */
class CampaignData
{
    /**
     * @var integer
     */
    private $dsId;

    /**
     * @var integer
     */
    private $retryCount;

    /**
     * @var integer
     */
    private $assignedTo;

    /**
     * @var integer
     */
    private $cdId;

    /**
     * @var \ApiBundle\Entity\Customer
     */
    private $customer;

    /**
     * @var \ApiBundle\Entity\Campaign
     */
    private $campaign;


    /**
     * Set dsId
     *
     * @param integer $dsId
     * @return CampaignData
     */
    public function setDsId($dsId)
    {
        $this->dsId = $dsId;

        return $this;
    }

    /**
     * Get dsId
     *
     * @return integer 
     */
    public function getDsId()
    {
        return $this->dsId;
    }

    /**
     * Set retryCount
     *
     * @param integer $retryCount
     * @return CampaignData
     */
    public function setRetryCount($retryCount)
    {
        $this->retryCount = $retryCount;

        return $this;
    }

    /**
     * Get retryCount
     *
     * @return integer 
     */
    public function getRetryCount()
    {
        return $this->retryCount;
    }

    /**
     * Set assignedTo
     *
     * @param integer $assignedTo
     * @return CampaignData
     */
    public function setAssignedTo($assignedTo)
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    /**
     * Get assignedTo
     *
     * @return integer 
     */
    public function getAssignedTo()
    {
        return $this->assignedTo;
    }

    /**
     * Get cdId
     *
     * @return integer 
     */
    public function getCdId()
    {
        return $this->cdId;
    }

    /**
     * Set customer
     *
     * @param \ApiBundle\Entity\Customer $customer
     * @return CampaignData
     */
    public function setCustomer(\ApiBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \ApiBundle\Entity\Customer 
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set campaign
     *
     * @param \ApiBundle\Entity\Campaign $campaign
     * @return CampaignData
     */
    public function setCampaign(\ApiBundle\Entity\Campaign $campaign = null)
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * Get campaign
     *
     * @return \ApiBundle\Entity\Campaign 
     */
    public function getCampaign()
    {
        return $this->campaign;
    }
    
    /**
     * @var string
     *
     * @ORM\Column(name="call_recording_file", type="string", length=100, nullable=false)
     */
    private $callRecordingFile;

    /**
     * Get callRecordingFile
     *
     * @return integer 
     */
    public function getCallRecordingFile() {
        return $this->callRecordingFile;
    }

    /**
     * Set callRecordingFile
     *
     * @param integer $callRecordingFile
     * @return CampaignData
     */
    public function setCallRecordingFile($callRecordingFile) {
        $this->callRecordingFile = $callRecordingFile;

        return $this;
    }
    
}
