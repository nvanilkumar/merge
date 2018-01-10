<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahStudentInfo
 */
class DahStudentInfo
{
    /**
     * @var integer
     */
    private $uid;

    /**
     * @var integer
     */
    private $depid;

    /**
     * @var string
     */
    private $bio;

    /**
     * @var string
     */
    private $position;

    /**
     * @var string
     */
    private $exp;

    /**
     * @var string
     */
    private $qualification;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $school;

    /**
     * @var string
     */
    private $location;

    /**
     * @var integer
     */
    private $updatedOn;

    /**
     * @var integer
     */
    private $tinfoid;


    /**
     * Set uid
     *
     * @param integer $uid
     * @return DahStudentInfo
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return integer 
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set depid
     *
     * @param integer $depid
     * @return DahStudentInfo
     */
    public function setDepid($depid)
    {
        $this->depid = $depid;

        return $this;
    }

    /**
     * Get depid
     *
     * @return integer 
     */
    public function getDepid()
    {
        return $this->depid;
    }

    /**
     * Set bio
     *
     * @param string $bio
     * @return DahStudentInfo
     */
    public function setBio($bio)
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Get bio
     *
     * @return string 
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return DahStudentInfo
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set exp
     *
     * @param string $exp
     * @return DahStudentInfo
     */
    public function setExp($exp)
    {
        $this->exp = $exp;

        return $this;
    }

    /**
     * Get exp
     *
     * @return string 
     */
    public function getExp()
    {
        return $this->exp;
    }

    /**
     * Set qualification
     *
     * @param string $qualification
     * @return DahStudentInfo
     */
    public function setQualification($qualification)
    {
        $this->qualification = $qualification;

        return $this;
    }

    /**
     * Get qualification
     *
     * @return string 
     */
    public function getQualification()
    {
        return $this->qualification;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return DahStudentInfo
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

    /**
     * Set school
     *
     * @param string $school
     * @return DahStudentInfo
     */
    public function setSchool($school)
    {
        $this->school = $school;

        return $this;
    }

    /**
     * Get school
     *
     * @return string 
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return DahStudentInfo
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set updatedOn
     *
     * @param integer $updatedOn
     * @return DahStudentInfo
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get updatedOn
     *
     * @return integer 
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Get tinfoid
     *
     * @return integer 
     */
    public function getTinfoid()
    {
        return $this->tinfoid;
    }
}
