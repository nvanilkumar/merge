<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LiveAgents
 */
class LiveAgents
{
    /**
     * @var string
     */
    private $status;

    /**
     * @var integer
     */
    private $laId;

    /**
     * @var \ApiBundle\Entity\Campaign
     */
    private $campaign;

    /**
     * @var \ApiBundle\Entity\User
     */
    private $user;


    /**
     * Set status
     *
     * @param string $status
     * @return LiveAgents
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get laId
     *
     * @return integer 
     */
    public function getLaId()
    {
        return $this->laId;
    }

    /**
     * Set campaign
     *
     * @param \ApiBundle\Entity\Campaign $campaign
     * @return LiveAgents
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
     * Set user
     *
     * @param \ApiBundle\Entity\User $user
     * @return LiveAgents
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
}
