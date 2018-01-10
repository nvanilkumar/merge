<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahNewsletterMessageQueue
 */
class DahNewsletterMessageQueue
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $status;

    /**
     * @var integer
     */
    private $nqid;
    
    /**
     * @var integer
     */
    private $publishdate;

    /**
     * @var \AdminBundle\Entity\DahNews
     */
    private $newsid;


    /**
     * Set email
     *
     * @param string $email
     * @return DahNewsletterMessageQueue
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
     * Set status
     *
     * @param string $status
     * @return DahNewsletterMessageQueue
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
     * Get nqid
     *
     * @return integer 
     */
    public function getNqid()
    {
        return $this->nqid;
    }
    
    /**
     * Set publishdate
     *
     * @param integer $publishdate
     * @return DahNews
     */
    public function setPublishdate($publishdate)
    {
        $this->publishdate = $publishdate;

        return $this;
    }

    /**
     * Get publishdate
     *
     * @return integer 
     */
    public function getPublishdate()
    {
        return $this->publishdate;
    }

    /**
     * Set newsid
     *
     * @param \AdminBundle\Entity\DahNews $newsid
     * @return DahNewsletterMessageQueue
     */
    public function setNewsid(\AdminBundle\Entity\DahNews $newsid = null)
    {
        $this->newsid = $newsid;

        return $this;
    }

    /**
     * Get newsid
     *
     * @return \AdminBundle\Entity\DahNews 
     */
    public function getNewsid()
    {
        return $this->newsid;
    }
}
