<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DialStatus
 */
class DialStatus
{
    /**
     * @var string
     */
    private $dialStatus;

    /**
     * @var integer
     */
    private $dsId;


    /**
     * Set dialStatus
     *
     * @param string $dialStatus
     * @return DialStatus
     */
    public function setDialStatus($dialStatus)
    {
        $this->dialStatus = $dialStatus;

        return $this;
    }

    /**
     * Get dialStatus
     *
     * @return string 
     */
    public function getDialStatus()
    {
        return $this->dialStatus;
    }

    /**
     * Get dsId
     *
     * @return integer 
     */
    public function getDsId()
    {
        return $this->dsId;
    }
}
