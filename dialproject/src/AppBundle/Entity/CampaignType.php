<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CampaignType
 *
 * @ORM\Table(name="campaign_type")
 * @ORM\Entity
 */
class CampaignType
{
    /**
     * @var string
     *
     * @ORM\Column(name="campaign_type", type="string", length=30, nullable=false)
     */
    private $campaignType;

    /**
     * @var integer
     *
     * @ORM\Column(name="ct_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ctId;



    /**
     * Set campaignType
     *
     * @param string $campaignType
     * @return CampaignType
     */
    public function setCampaignType($campaignType)
    {
        $this->campaignType = $campaignType;

        return $this;
    }

    /**
     * Get campaignType
     *
     * @return string 
     */
    public function getCampaignType()
    {
        return $this->campaignType;
    }

    /**
     * Get ctId
     *
     * @return integer 
     */
    public function getCtId()
    {
        return $this->ctId;
    }
    
    
}
