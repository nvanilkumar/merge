<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahMembersPasswordReset
 */
class DahMembersPasswordReset
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var integer
     */
    private $resetid;

    /**
     * @var \AdminBundle\Entity\DahUsers
     */
    private $uid;


    /**
     * Set code
     *
     * @param string $code
     * @return DahMembersPasswordReset
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get resetid
     *
     * @return integer 
     */
    public function getResetid()
    {
        return $this->resetid;
    }

    /**
     * Set uid
     *
     * @param \AdminBundle\Entity\DahUsers $uid
     * @return DahMembersPasswordReset
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
}
