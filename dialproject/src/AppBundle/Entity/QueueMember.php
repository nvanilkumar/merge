<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QueueMember
 *
 * @ORM\Table(name="queue_member")
 * @ORM\Entity
 */
class QueueMember
{
    /**
     * @var string
     *
     * @ORM\Column(name="membername", type="string", length=40, nullable=true)
     */
    private $membername;

    /**
     * @var string
     *
     * @ORM\Column(name="queue_name", type="string", length=50, nullable=true)
     */
    private $queueName;

    /**
     * @var string
     *
     * @ORM\Column(name="interface", type="string", length=128, nullable=true)
     */
    private $interface;

    /**
     * @var boolean
     *
     * @ORM\Column(name="penalty", type="boolean", nullable=true)
     */
    private $penalty;

    /**
     * @var boolean
     *
     * @ORM\Column(name="paused", type="boolean", nullable=true)
     */
    private $paused;

    /**
     * @var integer
     *
     * @ORM\Column(name="uniqueid", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $uniqueid;



    /**
     * Set membername
     *
     * @param string $membername
     * @return QueueMember
     */
    public function setMembername($membername)
    {
        $this->membername = $membername;

        return $this;
    }

    /**
     * Get membername
     *
     * @return string 
     */
    public function getMembername()
    {
        return $this->membername;
    }

    /**
     * Set queueName
     *
     * @param string $queueName
     * @return QueueMember
     */
    public function setQueueName($queueName)
    {
        $this->queueName = $queueName;

        return $this;
    }

    /**
     * Get queueName
     *
     * @return string 
     */
    public function getQueueName()
    {
        return $this->queueName;
    }

    /**
     * Set interface
     *
     * @param string $interface
     * @return QueueMember
     */
    public function setInterface($interface)
    {
        $this->interface = $interface;

        return $this;
    }

    /**
     * Get interface
     *
     * @return string 
     */
    public function getInterface()
    {
        return $this->interface;
    }

    /**
     * Set penalty
     *
     * @param boolean $penalty
     * @return QueueMember
     */
    public function setPenalty($penalty)
    {
        $this->penalty = $penalty;

        return $this;
    }

    /**
     * Get penalty
     *
     * @return boolean 
     */
    public function getPenalty()
    {
        return $this->penalty;
    }

    /**
     * Set paused
     *
     * @param boolean $paused
     * @return QueueMember
     */
    public function setPaused($paused)
    {
        $this->paused = $paused;

        return $this;
    }

    /**
     * Get paused
     *
     * @return boolean 
     */
    public function getPaused()
    {
        return $this->paused;
    }

    /**
     * Get uniqueid
     *
     * @return integer 
     */
    public function getUniqueid()
    {
        return $this->uniqueid;
    }
}
