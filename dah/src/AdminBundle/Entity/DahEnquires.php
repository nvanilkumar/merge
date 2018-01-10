<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahEnquires
 */
class DahEnquires
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $status;

    /**
     * @var integer
     */
    private $receivedOn;

    /**
     * @var integer
     */
    private $enquiryId;


    /**
     * Set name
     *
     * @param string $name
     * @return DahEnquires
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return DahEnquires
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
     * Set message
     *
     * @param string $message
     * @return DahEnquires
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return DahEnquires
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
     * Set receivedOn
     *
     * @param integer $receivedOn
     * @return DahEnquires
     */
    public function setReceivedOn($receivedOn)
    {
        $this->receivedOn = $receivedOn;

        return $this;
    }

    /**
     * Get receivedOn
     *
     * @return integer 
     */
    public function getReceivedOn()
    {
        return $this->receivedOn;
    }

    /**
     * Get enquiryId
     *
     * @return integer 
     */
    public function getEnquiryId()
    {
        return $this->enquiryId;
    }
}
