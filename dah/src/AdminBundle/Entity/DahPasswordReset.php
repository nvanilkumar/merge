<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahPasswordReset
 */
class DahPasswordReset
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
     * @var \AdminBundle\Entity\DahAdmin
     */
    private $adminid;


    /**
     * Set code
     *
     * @param string $code
     * @return DahPasswordReset
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
     * Set adminid
     *
     * @param \AdminBundle\Entity\DahAdmin $adminid
     * @return DahPasswordReset
     */
    public function setAdminid(\AdminBundle\Entity\DahAdmin $adminid = null)
    {
        $this->adminid = $adminid;

        return $this;
    }

    /**
     * Get adminid
     *
     * @return \AdminBundle\Entity\DahAdmin 
     */
    public function getAdminid()
    {
        return $this->adminid;
    }
}
