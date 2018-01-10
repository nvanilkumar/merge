<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahTrainingQuestions
 */
class DahTrainingQuestions
{
    /**
     * @var string
     */
    private $question;

    /**
     * @var integer
     */
    private $marks;

    /**
     * @var string
     */
    private $status;
    
    /**
     * @var string
     */
    private $qtype;

    /**
     * @var integer
     */
    private $qid;

    /**
     * @var \AdminBundle\Entity\DahTrainings
     */
    private $tid;


    /**
     * Set question
     *
     * @param string $question
     * @return DahTrainingQuestions
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set marks
     *
     * @param integer $marks
     * @return DahTrainingQuestions
     */
    public function setMarks($marks)
    {
        $this->marks = $marks;

        return $this;
    }

    /**
     * Get marks
     *
     * @return integer 
     */
    public function getMarks()
    {
        return $this->marks;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return DahTrainingQuestions
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
    
     /**
     * Set qtype
     *
     * @param string $qtype
     * @return DahTrainingQuestions
     */
    public function setQtype($qtype)
    {
        $this->qtype = $qtype;

        return $this;
    }

    /**
     * Get qtype
     *
     * @return string 
     */
    public function getQtype()
    {
        return $this->qtype;
    }

    /**
     * Get qid
     *
     * @return integer 
     */
    public function getQid()
    {
        return $this->qid;
    }

    /**
     * Set tid
     *
     * @param \AdminBundle\Entity\DahTrainings $tid
     * @return DahTrainingQuestions
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
