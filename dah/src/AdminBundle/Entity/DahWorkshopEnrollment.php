<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahWorkshopEnrollment
 */
class DahWorkshopEnrollment
{
    /**
     * @var integer
     */
    private $enid;

    /**
     * @var \AdminBundle\Entity\DahUsers
     */
    private $uid;

    /**
     * @var \AdminBundle\Entity\DahWorkshops
     */
    private $wid;


    /**
     * Get enid
     *
     * @return integer 
     */
    public function getEnid()
    {
        return $this->enid;
    }

    /**
     * Set uid
     *
     * @param \AdminBundle\Entity\DahUsers $uid
     * @return DahWorkshopEnrollment
     */
    public function setUid(\AdminBundle\Entity\DahUsers $uid = null)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return \AdminBundle\Entity\DahUsers 
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set wid
     *
     * @param \AdminBundle\Entity\DahWorkshops $wid
     * @return DahWorkshopEnrollment
     */
    public function setWid(\AdminBundle\Entity\DahWorkshops $wid = null)
    {
        $this->wid = $wid;

        return $this;
    }

    /**
     * Get wid
     *
     * @return \AdminBundle\Entity\DahWorkshops 
     */
    public function getWid()
    {
        return $this->wid;
    }
    /**
     * @var string
     */
    private $certificateStatus = 'notIssued';


    /**
     * Set certificateStatus
     *
     * @param string $certificateStatus
     *
     * @return DahWorkshopEnrollment
     */
    public function setCertificateStatus($certificateStatus)
    {
        $this->certificateStatus = $certificateStatus;

        return $this;
    }

    /**
     * Get certificateStatus
     *
     * @return string
     */
    public function getCertificateStatus()
    {
        return $this->certificateStatus;
    }
    /**
     * @var string
     */
    private $fname;

    /**
     * @var string
     */
    private $lname;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $phone;


    /**
     * Set fname
     *
     * @param string $fname
     *
     * @return DahWorkshopEnrollment
     */
    public function setFname($fname)
    {
        $this->fname = $fname;

        return $this;
    }

    /**
     * Get fname
     *
     * @return string
     */
    public function getFname()
    {
        return $this->fname;
    }

    /**
     * Set lname
     *
     * @param string $lname
     *
     * @return DahWorkshopEnrollment
     */
    public function setLname($lname)
    {
        $this->lname = $lname;

        return $this;
    }

    /**
     * Get lname
     *
     * @return string
     */
    public function getLname()
    {
        return $this->lname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return DahWorkshopEnrollment
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
     * Set phone
     *
     * @param string $phone
     *
     * @return DahWorkshopEnrollment
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
}
