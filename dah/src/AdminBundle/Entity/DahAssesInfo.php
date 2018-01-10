<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahAssesInfo
 */
class DahAssesInfo
{
    /**
     * @var integer
     */
    private $totalmarks;

    /**
     * @var integer
     */
    private $cutoff;

    /**
     * @var integer
     */
    private $ainid;

    /**
     * @var \AdminBundle\Entity\DahTrainings
     */
    private $tid;


    /**
     * Set totalmarks
     *
     * @param integer $totalmarks
     * @return DahAssesInfo
     */
    public function setTotalmarks($totalmarks)
    {
        $this->totalmarks = $totalmarks;

        return $this;
    }

    /**
     * Get totalmarks
     *
     * @return integer 
     */
    public function getTotalmarks()
    {
        return $this->totalmarks;
    }

    /**
     * Set cutoff
     *
     * @param integer $cutoff
     * @return DahAssesInfo
     */
    public function setCutoff($cutoff)
    {
        $this->cutoff = $cutoff;

        return $this;
    }

    /**
     * Get cutoff
     *
     * @return integer 
     */
    public function getCutoff()
    {
        return $this->cutoff;
    }

    /**
     * Get ainid
     *
     * @return integer 
     */
    public function getAinid()
    {
        return $this->ainid;
    }

    /**
     * Set tid
     *
     * @param \AdminBundle\Entity\DahTrainings $tid
     * @return DahAssesInfo
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
