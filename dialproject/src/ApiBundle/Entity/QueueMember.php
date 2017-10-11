<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QueueMember
 */
class QueueMember
{
    /**
     * @var string
     */
    private $membername;

    /**
     * @var string
     */
    private $queueName;

    /**
     * @var string
     */
    private $interface;

    /**
     * @var boolean
     */
    private $penalty;

    /**
     * @var boolean
     */
    private $paused;

    /**
     * @var integer
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
