<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CampaignAgents
 *
 * @ORM\Table(name="campaign_agents", indexes={@ORM\Index(name="campaign_id", columns={"campaign_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class CampaignAgents
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ca_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $caId;

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
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;
    
    /**
     * @var string
     *
     * @ORM\Column(name="pdf_report", type="string", length=150, nullable=true)
     */
    private $pdfReport;
    
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
     * Get caId
     *
     * @return integer 
     */
    public function getCaId()
    {
        return $this->caId;
    }

    /**
     * Set campaign
     *
     * @param \AppBundle\Entity\Campaign $campaign
     * @return CampaignAgents
     */
    public function setCampaign(\AppBundle\Entity\Campaign $campaign = null)
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * Get campaign
     *
     * @return \AppBundle\Entity\Campaign 
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return CampaignAgents
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
