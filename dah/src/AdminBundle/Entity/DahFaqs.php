<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahFaqs
 */
class DahFaqs
{
    /**
     * @var string
     */
    private $question;

    /**
     * @var string
     */
    private $answer;

    /**
     * @var integer
     */
    private $addedOn;

    /**
     * @var integer
     */
    private $updatedOn;

    /**
     * @var string
     */
    private $status;

    /**
     * @var integer
     */
    private $faqid;


    /**
     * Set question
     *
     * @param string $question
     * @return DahFaqs
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
     * Set answer
     *
     * @param string $answer
     * @return DahFaqs
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string 
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set addedOn
     *
     * @param integer $addedOn
     * @return DahFaqs
     */
    public function setAddedOn($addedOn)
    {
        $this->addedOn = $addedOn;

        return $this;
    }

    /**
     * Get addedOn
     *
     * @return integer 
     */
    public function getAddedOn()
    {
        return $this->addedOn;
    }

    /**
     * Set updatedOn
     *
     * @param integer $updatedOn
     * @return DahFaqs
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get updatedOn
     *
     * @return integer 
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return DahFaqs
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
     * Get faqid
     *
     * @return integer 
     */
    public function getFaqid()
    {
        return $this->faqid;
    }
}
