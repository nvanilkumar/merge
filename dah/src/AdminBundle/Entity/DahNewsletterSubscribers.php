<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahNewsletterSubscribers
 */
class DahNewsletterSubscribers
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var integer
     */
    private $subscribedOn;

    /**
     * @var string
     */
    private $status;

    /**
     * @var integer
     */
    private $subid;


    /**
     * Set email
     *
     * @param string $email
     * @return DahNewsletterSubscribers
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set subscribedOn
     *
     * @param integer $subscribedOn
     * @return DahNewsletterSubscribers
     */
    public function setSubscribedOn($subscribedOn)
    {
        $this->subscribedOn = $subscribedOn;

        return $this;
    }

    /**
     * Get subscribedOn
     *
     * @return integer 
     */
    public function getSubscribedOn()
    {
        return $this->subscribedOn;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return DahNewsletterSubscribers
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
     * Get subid
     *
     * @return integer 
     */
    public function getSubid()
    {
        return $this->subid;
    }
}
