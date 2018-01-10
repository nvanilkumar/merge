<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahUserAssesmentResults
 */
class DahUserAssesmentResults
{
    /**
     * @var string
     */
    private $res;

    /**
     * @var integer
     */
    private $duaid;

    /**
     * @var \AdminBundle\Entity\DahUsers
     */
    private $uid;

    /**
     * @var \AdminBundle\Entity\DahTrainings
     */
    private $tid;

    /**
     * @var \AdminBundle\Entity\DahTrainingQuestions
     */
    private $qid;

    /**
     * @var \AdminBundle\Entity\DahQuestionOptions
     */
    private $opid;


    /**
     * Set res
     *
     * @param string $res
     * @return DahUserAssesmentResults
     */
    public function setRes($res)
    {
        $this->res = $res;

        return $this;
    }

    /**
     * Get res
     *
     * @return string 
     */
    public function getRes()
    {
        return $this->res;
    }

    /**
     * Get duaid
     *
     * @return integer 
     */
    public function getDuaid()
    {
        return $this->duaid;
    }

    /**
     * Set uid
     *
     * @param \AdminBundle\Entity\DahUsers $uid
     * @return DahUserAssesmentResults
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
     * @return DahUserAssesmentResults
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
     * Set qid
     *
     * @param \AdminBundle\Entity\DahTrainingQuestions $qid
     * @return DahUserAssesmentResults
     */
    public function setQid(\AdminBundle\Entity\DahTrainingQuestions $qid = null)
    {
        $this->qid = $qid;

        return $this;
    }

    /**
     * Get qid
     *
     * @return \AdminBundle\Entity\DahTrainingQuestions 
     */
    public function getQid()
    {
        return $this->qid;
    }

    /**
     * Set opid
     *
     * @param \AdminBundle\Entity\DahQuestionOptions $opid
     * @return DahUserAssesmentResults
     */
    public function setOpid(\AdminBundle\Entity\DahQuestionOptions $opid = null)
    {
        $this->opid = $opid;

        return $this;
    }

    /**
     * Get opid
     *
     * @return \AdminBundle\Entity\DahQuestionOptions 
     */
    public function getOpid()
    {
        return $this->opid;
    }
}
