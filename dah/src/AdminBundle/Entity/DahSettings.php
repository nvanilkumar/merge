<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahSettings
 */
class DahSettings
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $linkedin;

    /**
     * @var string
     */
    private $twitter;

    /**
     * @var string
     */
    private $facebook;

    /**
     * @var string
     */
    private $googlePlus;
    /**
     * @var string
     */
    private $blackboard;
    /**
     * @var integer
     */
    private $updateOn;
    
    /**
     * @var string
     */
    private $address;

    /**
     * @var integer
     */
    private $settingid;


    /**
     * Set email
     *
     * @param string $email
     * @return DahSettings
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
     * @return DahSettings
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
     * Set linkedin
     *
     * @param string $linkedin
     * @return DahSettings
     */
    public function setLinkedin($linkedin)
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    /**
     * Get linkedin
     *
     * @return string 
     */
    public function getLinkedin()
    {
        return $this->linkedin;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     * @return DahSettings
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     * @return DahSettings
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string 
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set googlePlus
     *
     * @param string $googlePlus
     * @return DahSettings
     */
    public function setGooglePlus($googlePlus)
    {
        $this->googlePlus = $googlePlus;

        return $this;
    }

    /**
     * Get googlePlus
     *
     * @return string 
     */
    public function getGooglePlus()
    {
        return $this->googlePlus;
    }
    /**
     * Set blackboard
     *
     * @param string $blackboard
     * @return DahSettings
     */
    public function setBlackboard($blackboard)
    {
        $this->blackboard = $blackboard;

        return $this;
    }
    /**
     * Get blackboard
     *
     * @return string 
     */
    public function getBlackboard()
    {
        return $this->blackboard;
    }
    /**
     * Set updateOn
     *
     * @param integer $updateOn
     * @return DahSettings
     */
    public function setUpdateOn($updateOn)
    {
        $this->updateOn = $updateOn;

        return $this;
    }

    /**
     * Get updateOn
     *
     * @return integer 
     */
    public function getUpdateOn()
    {
        return $this->updateOn;
    }
    
    /**
     * Set address
     *
     * @param string $address
     * @return DahEnquires
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get settingid
     *
     * @return integer 
     */
    public function getSettingid()
    {
        return $this->settingid;
    }
}
