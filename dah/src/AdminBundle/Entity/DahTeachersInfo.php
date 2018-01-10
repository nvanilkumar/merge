<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahTeachersInfo
 */
class DahTeachersInfo
{
    /**
     * @var integer
     */
    private $updatedOn;

    /**
     * @var integer
     */
    private $tinfoid;

    /**
     * @var \AdminBundle\Entity\DahUsers
     */
    private $uid;

    /**
     * @var \AdminBundle\Entity\DahDepartments
     */
    private $depid;


    /**
     * Set updatedOn
     *
     * @param integer $updatedOn
     * @return DahTeachersInfo
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

    /**
     * Set uid
     *
     * @param \AdminBundle\Entity\DahUsers $uid
     * @return DahTeachersInfo
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
     * Set depid
     *
     * @param \AdminBundle\Entity\DahDepartments $depid
     * @return DahTeachersInfo
     */
    public function setDepid(\AdminBundle\Entity\DahDepartments $depid = null)
    {
        $this->depid = $depid;

        return $this;
    }

    /**
     * Get depid
     *
     * @return \AdminBundle\Entity\DahDepartments 
     */
    public function getDepid()
    {
        return $this->depid;
    }
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
     * Set bio
     *
     * @param string $bio
     * @return DahTeachersInfo
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
     * @return DahTeachersInfo
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
     * @return DahTeachersInfo
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
     * @return DahTeachersInfo
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
     * Set phone
     *
     * @param string $phone
     * @return DahTeachersInfo
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
     * @return DahTeachersInfo
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
     * @return DahTeachersInfo
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
}
