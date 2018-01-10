<?php

namespace AdminBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * DahTrainings
 *
 * @ORM\Table(name="dah_trainings", indexes={@ORM\Index(name="FK_dah_workshops", columns={"deptid"}), @ORM\Index(name="FK_dah_trainings_uid", columns={"uid"})})
 * @ORM\Entity
 */
class DahTrainings
{
    /**
     * @var integer
     *
     * @ORM\Column(name="tid", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $tid;

    /**
     * @var string
     *
     * @ORM\Column(name="training_title", type="string", length=250, nullable=true)
     */
    private $trainingTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="training_description", type="text", nullable=true)
     */
    private $trainingDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="training_meta_title", type="string", length=250, nullable=true)
     */
    private $trainingMetaTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="training_meta_keyword", type="string", length=250, nullable=true)
     */
    private $trainingMetaKeyword;

    /**
     * @var string
     *
     * @ORM\Column(name="training_meta_description", type="string", length=250, nullable=true)
     */
    private $trainingMetaDescription;

    /**
     * @var integer
     *
     * @ORM\Column(name="added_on", type="integer", nullable=true)
     */
    private $addedOn;

    /**
     * @var integer
     *
     * @ORM\Column(name="updated_on", type="integer", nullable=true)
     */
    private $updatedOn;

    /**
     * @var string
     *
     * @ORM\Column(name="public", type="string", nullable=true)
     */
    private $public = 'yes';

    /**
     * @var string
     *
     * @ORM\Column(name="assesment", type="string", nullable=true)
     */
    private $assesment = 'no';

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=true)
     */
    private $status = 'inactive';

    /**
     * @var integer
     *
     * @ORM\Column(name="tview", type="integer", nullable=true)
     */
    private $tview = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="featured", type="boolean", nullable=true)
     */
    private $featured = '0';

    /**
     * @var \DahDepartments
     *
     * @ORM\ManyToOne(targetEntity="DahDepartments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="deptid", referencedColumnName="deptid")
     * })
     */
    private $deptid;

    /**
     * @var \DahUsers
     *
     * @ORM\ManyToOne(targetEntity="DahUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="uid", referencedColumnName="uid")
     * })
     */
    private $uid;



    /**
     * Set trainingTitle
     *
     * @param string $trainingTitle
     *
     * @return DahTrainings
     */
    public function setTrainingTitle($trainingTitle)
    {
        $this->trainingTitle = $trainingTitle;

        return $this;
    }

    /**
     * Get trainingTitle
     *
     * @return string
     */
    public function getTrainingTitle()
    {
        return $this->trainingTitle;
    }

    /**
     * Set trainingDescription
     *
     * @param string $trainingDescription
     *
     * @return DahTrainings
     */
    public function setTrainingDescription($trainingDescription)
    {
        $this->trainingDescription = $trainingDescription;

        return $this;
    }

    /**
     * Get trainingDescription
     *
     * @return string
     */
    public function getTrainingDescription()
    {
        return $this->trainingDescription;
    }

    /**
     * Set trainingMetaTitle
     *
     * @param string $trainingMetaTitle
     *
     * @return DahTrainings
     */
    public function setTrainingMetaTitle($trainingMetaTitle)
    {
        $this->trainingMetaTitle = $trainingMetaTitle;

        return $this;
    }

    /**
     * Get trainingMetaTitle
     *
     * @return string
     */
    public function getTrainingMetaTitle()
    {
        return $this->trainingMetaTitle;
    }

    /**
     * Set trainingMetaKeyword
     *
     * @param string $trainingMetaKeyword
     *
     * @return DahTrainings
     */
    public function setTrainingMetaKeyword($trainingMetaKeyword)
    {
        $this->trainingMetaKeyword = $trainingMetaKeyword;

        return $this;
    }

    /**
     * Get trainingMetaKeyword
     *
     * @return string
     */
    public function getTrainingMetaKeyword()
    {
        return $this->trainingMetaKeyword;
    }

    /**
     * Set trainingMetaDescription
     *
     * @param string $trainingMetaDescription
     *
     * @return DahTrainings
     */
    public function setTrainingMetaDescription($trainingMetaDescription)
    {
        $this->trainingMetaDescription = $trainingMetaDescription;

        return $this;
    }

    /**
     * Get trainingMetaDescription
     *
     * @return string
     */
    public function getTrainingMetaDescription()
    {
        return $this->trainingMetaDescription;
    }

    /**
     * Set addedOn
     *
     * @param integer $addedOn
     *
     * @return DahTrainings
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
     *
     * @return DahTrainings
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
     * Set public
     *
     * @param string $public
     *
     * @return DahTrainings
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return string
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set assesment
     *
     * @param string $assesment
     *
     * @return DahTrainings
     */
    public function setAssesment($assesment)
    {
        $this->assesment = $assesment;

        return $this;
    }

    /**
     * Get assesment
     *
     * @return string
     */
    public function getAssesment()
    {
        return $this->assesment;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return DahTrainings
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
     * Set tview
     *
     * @param integer $tview
     *
     * @return DahTrainings
     */
    public function setTview($tview)
    {
        $this->tview = $tview;

        return $this;
    }

    /**
     * Get tview
     *
     * @return integer
     */
    public function getTview()
    {
        return $this->tview;
    }

    /**
     * Set featured
     *
     * @param boolean $featured
     *
     * @return DahTrainings
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;

        return $this;
    }

    /**
     * Get featured
     *
     * @return boolean
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * Get tid
     *
     * @return integer
     */
    public function getTid()
    {
        return $this->tid;
    }

    /**
     * Set uid
     *
     * @param \AdminBundle\Entity\DahUsers $uid
     *
     * @return DahTrainings
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
     * Set deptid
     *
     * @param \AdminBundle\Entity\DahDepartments $deptid
     *
     * @return DahTrainings
     */
    public function setDeptid(\AdminBundle\Entity\DahDepartments $deptid = null)
    {
        $this->deptid = $deptid;

        return $this;
    }

    /**
     * Get deptid
     *
     * @return \AdminBundle\Entity\DahDepartments
     */
    public function getDeptid()
    {
        return $this->deptid;
    }
}
