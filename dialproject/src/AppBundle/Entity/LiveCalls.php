<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LiveCalls
 *
 * @ORM\Table(name="live_calls", indexes={@ORM\Index(name="FK476A84C239F80F52", columns={"campaign_id"}), @ORM\Index(name="FK476A84C2E132C702", columns={"user_id"})})
 * @ORM\Entity
 */
class LiveCalls
{
    /**
     * @var string
     *
     * @ORM\Column(name="from_number", type="string", length=255, nullable=false)
     */
    private $fromNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="to_number", type="string", length=255, nullable=false)
     */
    private $toNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dial_time", type="datetime", nullable=true)
     */
    private $dialTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ans_time", type="datetime", nullable=true)
     */
    private $ansTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_time", type="datetime", nullable=true)
     */
    private $endTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer", nullable=false)
     */
    private $duration;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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
     * Set fromNumber
     *
     * @param string $fromNumber
     * @return LiveCalls
     */
    public function setFromNumber($fromNumber)
    {
        $this->fromNumber = $fromNumber;

        return $this;
    }

    /**
     * Get fromNumber
     *
     * @return string 
     */
    public function getFromNumber()
    {
        return $this->fromNumber;
    }

    /**
     * Set toNumber
     *
     * @param string $toNumber
     * @return LiveCalls
     */
    public function setToNumber($toNumber)
    {
        $this->toNumber = $toNumber;

        return $this;
    }

    /**
     * Get toNumber
     *
     * @return string 
     */
    public function getToNumber()
    {
        return $this->toNumber;
    }

    /**
     * Set dialTime
     *
     * @param \DateTime $dialTime
     * @return LiveCalls
     */
    public function setDialTime($dialTime)
    {
        $this->dialTime = $dialTime;

        return $this;
    }

    /**
     * Get dialTime
     *
     * @return \DateTime 
     */
    public function getDialTime()
    {
        return $this->dialTime;
    }

    /**
     * Set ansTime
     *
     * @param \DateTime $ansTime
     * @return LiveCalls
     */
    public function setAnsTime($ansTime)
    {
        $this->ansTime = $ansTime;

        return $this;
    }

    /**
     * Get ansTime
     *
     * @return \DateTime 
     */
    public function getAnsTime()
    {
        return $this->ansTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     * @return LiveCalls
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime 
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return LiveCalls
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set campaign
     *
     * @param \AppBundle\Entity\Campaign $campaign
     * @return LiveCalls
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
     * @return LiveCalls
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
