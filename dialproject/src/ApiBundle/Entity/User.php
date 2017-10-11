<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 */
class User
{
    /**
     * @var string
     */
    private $fullName;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $salt;

    /**
     * @var string
     */
    private $gender;

    /**
     * @var integer
     */
    private $extension;

    /**
     * @var integer
     */
    private $pin;

    /**
     * @var string
     */
    private $status;

    /**
     * @var \DateTime
     */
    private $addedOn;

    /**
     * @var \DateTime
     */
    private $updatedOn;

    /**
     * @var boolean
     */
    private $isDeleted;

    /**
     * @var \DateTime
     */
    private $lastLogin;

    /**
     * @var boolean
     */
    private $astriskLogin;

    /**
     * @var boolean
     */
    private $webLogin;
    
    /**
     * @var boolean
     */
    private $hangupAgent;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var \ApiBundle\Entity\Roles
     */
    private $role;


    /**
     * Set fullName
     *
     * @param string $fullName
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
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
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set extension
     *
     * @param integer $extension
     * @return User
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return integer 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set pin
     *
     * @param integer $pin
     * @return User
     */
    public function setPin($pin)
    {
        $this->pin = $pin;

        return $this;
    }

    /**
     * Get pin
     *
     * @return integer 
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return User
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
     * Set addedOn
     *
     * @param \DateTime $addedOn
     * @return User
     */
    public function setAddedOn($addedOn)
    {
        $this->addedOn = $addedOn;

        return $this;
    }

    /**
     * Get addedOn
     *
     * @return \DateTime 
     */
    public function getAddedOn()
    {
        return $this->addedOn;
    }

    /**
     * Set updatedOn
     *
     * @param \DateTime $updatedOn
     * @return User
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get updatedOn
     *
     * @return \DateTime 
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     * @return User
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean 
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set lastLogin
     *
     * @param \DateTime $lastLogin
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return \DateTime 
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set astriskLogin
     *
     * @param boolean $astriskLogin
     * @return User
     */
    public function setAstriskLogin($astriskLogin)
    {
        $this->astriskLogin = $astriskLogin;

        return $this;
    }

    /**
     * Get astriskLogin
     *
     * @return boolean 
     */
    public function getAstriskLogin()
    {
        return $this->astriskLogin;
    }

    /**
     * Set webLogin
     *
     * @param boolean $webLogin
     * @return User
     */
    public function setWebLogin($webLogin)
    {
        $this->webLogin = $webLogin;

        return $this;
    }

    /**
     * Get webLogin
     *
     * @return boolean 
     */
    public function getWebLogin()
    {
        return $this->webLogin;
    }
    
    /**
     * Set hangupAgent
     *
     * @param boolean $hangupAgent
     * @return User
     */
    public function setHangupAgent($hangupAgent)
    {
        $this->hangupAgent = $hangupAgent;

        return $this;
    }

    /**
     * Get hangupAgent
     *
     * @return boolean 
     */
    public function getHangupAgent()
    {
        return $this->hangupAgent;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set role
     *
     * @param \ApiBundle\Entity\Roles $role
     * @return User
     */
    public function setRole(\ApiBundle\Entity\Roles $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \ApiBundle\Entity\Roles 
     */
    public function getRole()
    {
        return $this->role;
    }
}
