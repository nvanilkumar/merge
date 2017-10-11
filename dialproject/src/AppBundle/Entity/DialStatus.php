<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DialStatus
 *
 * @ORM\Table(name="dial_status")
 * @ORM\Entity
 */
class DialStatus
{
    /**
     * @var string
     *
     * @ORM\Column(name="dial_status", type="string", length=20, nullable=true)
     */
    private $dialStatus;

    /**
     * @var integer
     *
     * @ORM\Column(name="ds_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
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
