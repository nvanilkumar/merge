<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahTrainingEnrollment
 */
class DahTrainingEnrollment
{
    /**
     * @var integer
     */
    private $enid;

    /**
     * @var \AdminBundle\Entity\DahUsers
     */
    private $uid;

    /**
     * @var \AdminBundle\Entity\DahTrainings
     */
    private $tid;


    /**
     * Get enid
     *
     * @return integer 
     */
    public function getEnid()
    {
        return $this->enid;
    }

    /**
     * Set uid
     *
     * @param \AdminBundle\Entity\DahUsers $uid
     * @return DahTrainingEnrollment
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
     * Set tid
     *
     * @param \AdminBundle\Entity\DahTrainings $tid
     * @return DahTrainingEnrollment
     */
    public function setTid(\AdminBundle\Entity\DahTrainings $tid = null)
    {
        $this->tid = $tid;

        return $this;
    }

    /**
     * Get tid
     *
     * @return \AdminBundle\Entity\DahTrainings 
     */
    public function getTid()
    {
        return $this->tid;
    }
    /**
     * @var string
     */
    private $trainingStatus;

    /**
     * @var string
     */
    private $certificateStatus;


    /**
     * Set trainingStatus
     *
     * @param string $trainingStatus
     * @return DahTrainingEnrollment
     */
    public function setTrainingStatus($trainingStatus)
    {
        $this->trainingStatus = $trainingStatus;

        return $this;
    }

    /**
     * Get trainingStatus
     *
     * @return string 
     */
    public function getTrainingStatus()
    {
        return $this->trainingStatus;
    }

    /**
     * Set certificateStatus
     *
     * @param string $certificateStatus
     * @return DahTrainingEnrollment
     */
    public function setCertificateStatus($certificateStatus)
    {
        $this->certificateStatus = $certificateStatus;

        return $this;
    }

    /**
     * Get certificateStatus
     *
     * @return string 
     */
    public function getCertificateStatus()
    {
        return $this->certificateStatus;
    }
}
