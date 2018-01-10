<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahQuestionOptions
 */
class DahQuestionOptions
{
    /**
     * @var string
     */
    private $options;

    /**
     * @var integer
     */
    private $opid;

    /**
     * @var \AdminBundle\Entity\DahTrainingQuestions
     */
    private $qid;


    /**
     * Set options
     *
     * @param string $options
     * @return DahQuestionOptions
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get options
     *
     * @return string 
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get opid
     *
     * @return integer 
     */
    public function getOpid()
    {
        return $this->opid;
    }

    /**
     * Set qid
     *
     * @param \AdminBundle\Entity\DahTrainingQuestions $qid
     * @return DahQuestionOptions
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
}
