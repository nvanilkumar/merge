<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahAssesmentResult
 */
class DahAssesmentResult
{
    /**
     * @var integer
     */
    private $maxMarks;

    /**
     * @var integer
     */
    private $correct;

    /**
     * @var integer
     */
    private $unassigned;

    /**
     * @var string
     */
    private $result;

    /**
     * @var integer
     */
    private $attemptNo;

    /**
     * @var integer
     */
    private $lastAssesOm;

    /**
     * @var integer
     */
    private $darid;

    /**
     * @var \AdminBundle\Entity\DahUsers
     */
    private $uid;

    /**
     * @var \AdminBundle\Entity\DahTrainings
     */
    private $tid;


    /**
     * Set maxMarks
     *
     * @param integer $maxMarks
     * @return DahAssesmentResult
     */
    public function setMaxMarks($maxMarks)
    {
        $this->maxMarks = $maxMarks;

        return $this;
    }

    /**
     * Get maxMarks
     *
     * @return integer 
     */
    public function getMaxMarks()
    {
        return $this->maxMarks;
    }

    /**
     * Set correct
     *
     * @param integer $correct
     * @return DahAssesmentResult
     */
    public function setCorrect($correct)
    {
        $this->correct = $correct;

        return $this;
    }

    /**
     * Get correct
     *
     * @return integer 
     */
    public function getCorrect()
    {
        return $this->correct;
    }

    /**
     * Set unassigned
     *
     * @param integer $unassigned
     * @return DahAssesmentResult
     */
    public function setUnassigned($unassigned)
    {
        $this->unassigned = $unassigned;

        return $this;
    }

    /**
     * Get unassigned
     *
     * @return integer 
     */
    public function getUnassigned()
    {
        return $this->unassigned;
    }

    /**
     * Set result
     *
     * @param string $result
     * @return DahAssesmentResult
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return string 
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set attemptNo
     *
     * @param integer $attemptNo
     * @return DahAssesmentResult
     */
    public function setAttemptNo($attemptNo)
    {
        $this->attemptNo = $attemptNo;

        return $this;
    }

    /**
     * Get attemptNo
     *
     * @return integer 
     */
    public function getAttemptNo()
    {
        return $this->attemptNo;
    }

    /**
     * Set lastAssesOm
     *
     * @param integer $lastAssesOm
     * @return DahAssesmentResult
     */
    public function setLastAssesOm($lastAssesOm)
    {
        $this->lastAssesOm = $lastAssesOm;

        return $this;
    }

    /**
     * Get lastAssesOm
     *
     * @return integer 
     */
    public function getLastAssesOm()
    {
        return $this->lastAssesOm;
    }

    /**
     * Get darid
     *
     * @return integer 
     */
    public function getDarid()
    {
        return $this->darid;
    }

    /**
     * Set uid
     *
     * @param \AdminBundle\Entity\DahUsers $uid
     * @return DahAssesmentResult
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
     * @return DahAssesmentResult
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
}
