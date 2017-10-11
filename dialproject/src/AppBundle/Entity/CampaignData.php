<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CampaignData
 *
 * @ORM\Table(name="campaign_data", indexes={@ORM\Index(name="FK3F89467D39F80F52", columns={"campaign_id"}), @ORM\Index(name="customer_id", columns={"customer_id"}), @ORM\Index(name="ds_id", columns={"ds_id"})})
 * @ORM\Entity
 */
class CampaignData {

    /**
     * @var integer
     *
     * @ORM\Column(name="retry_count", type="integer", nullable=false)
     */
    private $retryCount;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="max_retry_count", type="integer", nullable=false)
     */
    private $maxRetryCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="cd_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $cdId;

    /**
     * @var \AppBundle\Entity\DialStatus
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\DialStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ds_id", referencedColumnName="ds_id")
     * })
     */
    private $ds;

    /**
     * @var \AppBundle\Entity\Customer
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Customer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customer_id", referencedColumnName="customer_id")
     * })
     */
    private $customer;

    /**
     * @var \AppBundle\Entity\Campaign
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Campaign")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="campaign_id", referencedColumnName="campaign_id")
     * })
     */
    private $campaign;

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
     * Set maxretryCount
     *
     * @param integer $retryCount
     * @return CampaignData
     */
    public function setMaxRetryCount($retryCount) {
        $this->maxRetryCount = $retryCount;

        return $this;
    }

    /**
     * Get retryCount
     *
     * @return integer 
     */
    public function getMaxRetryCount() {
        return $this->maxRetryCount;
    }

    /**
     * Get cdId
     *
     * @return integer 
     */
    public function getCdId() {
        return $this->cdId;
    }

    /**
     * Set ds
     *
     * @param \AppBundle\Entity\DialStatus $ds
     * @return CampaignData
     */
    public function setDs(\AppBundle\Entity\DialStatus $ds = null) {
        $this->ds = $ds;

        return $this;
    }

    /**
     * Get ds
     *
     * @return \AppBundle\Entity\DialStatus 
     */
    public function getDs() {
        return $this->ds;
    }

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\Customer $customer
     * @return CampaignData
     */
    public function setCustomer(\AppBundle\Entity\Customer $customer = null) {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \AppBundle\Entity\Customer 
     */
    public function getCustomer() {
        return $this->customer;
    }

    /**
     * Set campaign
     *
     * @param \AppBundle\Entity\Campaign $campaign
     * @return CampaignData
     */
    public function setCampaign(\AppBundle\Entity\Campaign $campaign = null) {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * Get campaign
     *
     * @return \AppBundle\Entity\Campaign 
     */
    public function getCampaign() {
        return $this->campaign;
    }

    /**
     * @var integer
     */
    private $dsId;

    /**
     * @var integer
     */
    private $assignedTo;

    /**
     * @var integer
     */
    private $skippedBy;

    /**
     * @var integer
     */
    private $updatedOn;

    /**
     * Set dsId
     *
     * @param integer $dsId
     * @return CampaignData
     */
    public function setDsId($dsId) {
        $this->dsId = $dsId;

        return $this;
    }

    /**
     * Get dsId
     *
     * @return integer 
     */
    public function getDsId() {
        return $this->dsId;
    }

    /**
     * Set assignedTo
     *
     * @param integer $assignedTo
     * @return CampaignData
     */
    public function setAssignedTo($assignedTo) {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    /**
     * Get skippedBy
     *
     * @return integer 
     */
    public function getSkippedBy() {
        return $this->skippedBy;
    }

    /**
     * Set skippedBy
     *
     * @param integer $skippedBy
     * @return CampaignData
     */
    public function setSkippedBy($skippedBy) {
        $this->skippedBy = $skippedBy;

        return $this;
    }

    /**
     * Get updatedOn
     *
     * @return integer 
     */
    public function getUpdatedOn() {
        return $this->updatedOn;
    }

    /**
     * Set updatedOn
     *
     * @param integer $updatedOn
     * @return CampaignData
     */
    public function setUpdatedOn($updatedOn) {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get assignedTo
     *
     * @return integer 
     */
    public function getAssignedTo() {
        return $this->assignedTo;
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
