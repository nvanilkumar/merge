<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahActivityLog
 *
 * @ORM\Table(name="dah_activity_log", indexes={@ORM\Index(name="adminid", columns={"adminid"})})
 * @ORM\Entity
 */
class DahActivityLog
{
    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", length=65535, nullable=true)
     */
    private $message;

    /**
     * @var integer
     *
     * @ORM\Column(name="logged_on", type="integer", nullable=true)
     */
    private $loggedOn;

    /**
     * @var integer
     *
     * @ORM\Column(name="logid", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $logid;

    /**
     * @var \AdminBundle\Entity\DahAdmin
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AdminBundle\Entity\DahAdmin")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="adminid", referencedColumnName="adminid")
     * })
     */
    private $adminid;



    /**
     * Set message
     *
     * @param string $message
     *
     * @return DahActivityLog
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set loggedOn
     *
     * @param integer $loggedOn
     *
     * @return DahActivityLog
     */
    public function setLoggedOn($loggedOn)
    {
        $this->loggedOn = $loggedOn;

        return $this;
    }

    /**
     * Get loggedOn
     *
     * @return integer
     */
    public function getLoggedOn()
    {
        return $this->loggedOn;
    }

    /**
     * Set logid
     *
     * @param integer $logid
     *
     * @return DahActivityLog
     */
    public function setLogid($logid)
    {
        $this->logid = $logid;

        return $this;
    }

    /**
     * Get logid
     *
     * @return integer
     */
    public function getLogid()
    {
        return $this->logid;
    }

    /**
     * Set adminid
     *
     * @param \AdminBundle\Entity\DahAdmin $adminid
     *
     * @return DahActivityLog
     */
    public function setAdminid(\AdminBundle\Entity\DahAdmin $adminid)
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
