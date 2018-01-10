<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahUnsubscribe
 */
class DahUnsubscribe
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var integer
     */
    private $addedOn;

    /**
     * @var integer
     */
    private $unsubid;


    /**
     * Set email
     *
     * @param string $email
     * @return DahUnsubscribe
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
     * Set addedOn
     *
     * @param integer $addedOn
     * @return DahUnsubscribe
     */
    public function setAddedOn($addedOn)
    {
        $this->addedOn = $addedOn;

        return $this;
    }

    /**
     * Get addedOn
     *
     * @return integer 
     */
    public function getAddedOn()
    {
        return $this->addedOn;
    }

    /**
     * Get unsubid
     *
     * @return integer 
     */
    public function getUnsubid()
    {
        return $this->unsubid;
    }
}
