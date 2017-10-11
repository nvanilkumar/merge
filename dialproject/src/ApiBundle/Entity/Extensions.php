<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Extensions
 */
class Extensions
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $app;

    /**
     * @var string
     */
    private $appdata;

    /**
     * @var string
     */
    private $context;

    /**
     * @var string
     */
    private $exten;

    /**
     * @var boolean
     */
    private $priority;


    /**
     * Set id
     *
     * @param integer $id
     * @return Extensions
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set app
     *
     * @param string $app
     * @return Extensions
     */
    public function setApp($app)
    {
        $this->app = $app;

        return $this;
    }

    /**
     * Get app
     *
     * @return string 
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * Set appdata
     *
     * @param string $appdata
     * @return Extensions
     */
    public function setAppdata($appdata)
    {
        $this->appdata = $appdata;

        return $this;
    }

    /**
     * Get appdata
     *
     * @return string 
     */
    public function getAppdata()
    {
        return $this->appdata;
    }

    /**
     * Set context
     *
     * @param string $context
     * @return Extensions
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string 
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set exten
     *
     * @param string $exten
     * @return Extensions
     */
    public function setExten($exten)
    {
        $this->exten = $exten;

        return $this;
    }

    /**
     * Get exten
     *
     * @return string 
     */
    public function getExten()
    {
        return $this->exten;
    }

    /**
     * Set priority
     *
     * @param boolean $priority
     * @return Extensions
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return boolean 
     */
    public function getPriority()
    {
        return $this->priority;
    }
}
