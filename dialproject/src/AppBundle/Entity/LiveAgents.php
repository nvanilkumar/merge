<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LiveAgents
 *
 * @ORM\Table(name="live_agents", indexes={@ORM\Index(name="FKA2CDC8C139F80F52", columns={"campaign_id"}), @ORM\Index(name="FKA2CDC8C1E132C702", columns={"user_id"})})
 * @ORM\Entity
 */
class LiveAgents
{
    /**
     * @var integer
     *
     * @ORM\Column(name="la_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $laId;

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
     * @param \AppBundle\Entity\Campaign $campaign
     * @return LiveAgents
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
     * @return LiveAgents
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
    /**
     * @var string
     */
    private $status;


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
}
