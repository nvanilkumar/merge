<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahTrainingKey
 */
class DahTrainingKey
{
    /**
     * @var integer
     */
    private $keyid;

    /**
     * @var \AdminBundle\Entity\DahTrainingQuestions
     */
    private $qid;

    /**
     * @var \AdminBundle\Entity\DahQuestionOptions
     */
    private $opid;


    /**
     * Get keyid
     *
     * @return integer 
     */
    public function getKeyid()
    {
        return $this->keyid;
    }

    /**
     * Set qid
     *
     * @param \AdminBundle\Entity\DahTrainingQuestions $qid
     * @return DahTrainingKey
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
     * @return DahTrainingKey
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
