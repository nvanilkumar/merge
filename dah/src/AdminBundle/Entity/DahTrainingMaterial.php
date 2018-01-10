<?php

namespace AdminBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DahTrainingMaterial
 * 
 * @ORM\Table(name="dah_training_material")
 * @ORM\Entity
 */
class DahTrainingMaterial
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     * 
     * @ORM\Column(name="ftitle", type="string", length=150, nullable=true)
     */
    private $ftitle;

    /**
     * @var string
     * File
     *
     * @var File
     *
     * @ORM\Column(name="materialupload", type="string", length=150, nullable=true)
     * @Assert\File(
     *      maxSize = "15M",
     *      mimeTypes = {"application/pdf", "application/doc", "application/docx"},
     *      maxSizeMessage = "The maxmimum allowed file size is 15M.",
     *      mimeTypesMessage = "Please upload only .pdf | .doc | .docx"
     * ) 
     */
    private $materialupload;
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=true)
     */
    private $status = 'active';
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ftitle
     *
     * @param string $ftitle
     *
     * @return DahTrainingMaterial
     */
    public function setFtitle($ftitle)
    {
        $this->ftitle = $ftitle;

        return $this;
    }

    /**
     * Get ftitle
     *
     * @return string
     */
    public function getFtitle()
    {
        return $this->ftitle;
    }

    /**
     * Set materialupload
     *
     * @param string $materialupload
     *
     * @return DahTrainingMaterial
     */
    public function setMaterialupload($materialupload)
    {
        $this->materialupload = $materialupload;

        return $this;
    }

    /**
     * Get materialupload
     *
     * @return string
     */
    public function getMaterialupload()
    {
        return $this->materialupload;
    }
    /**
     * Set status
     *
     * @param string $status
     *
     * @return DahUsers
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }
    /**
     * @var integer
     */
    private $dtid;


    /**
     * Set dtid
     *
     * @param integer $dtid
     *
     * @return DahTrainingMaterial
     */
    public function setDtid($dtid)
    {
        $this->dtid = $dtid;

        return $this;
    }

    /**
     * Get dtid
     *
     * @return integer
     */
    public function getDtid()
    {
        return $this->dtid;
    }
}
