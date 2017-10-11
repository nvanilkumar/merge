<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CampaignAgents
 */
class CampaignAgents
{
    /**
     * @var integer
     */
    private $caId;

    /**
     * @var \ApiBundle\Entity\User
     */
    private $user;

    /**
     * @var \ApiBundle\Entity\Campaign
     */
    private $campaign;


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
     * Set user
     *
     * @param \ApiBundle\Entity\User $user
     * @return CampaignAgents
     */
    public function setUser(\ApiBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ApiBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set campaign
     *
     * @param \ApiBundle\Entity\Campaign $campaign
     * @return CampaignAgents
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
}
