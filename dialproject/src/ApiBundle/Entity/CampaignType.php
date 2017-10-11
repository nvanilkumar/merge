<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CampaignType
 */
class CampaignType
{
    /**
     * @var string
     */
    private $campaignType;

    /**
     * @var integer
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
