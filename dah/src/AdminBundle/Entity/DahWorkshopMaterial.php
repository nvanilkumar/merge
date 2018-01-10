<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahWorkshopMaterial
 *
 * @ORM\Table(name="dah_workshop_material", indexes={@ORM\Index(name="FK_dah_training_material", columns={"dtid"})})
 * @ORM\Entity
 */
class DahWorkshopMaterial
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
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
     *
     * @ORM\Column(name="materialupload", type="string", length=150, nullable=true)
     */
    private $materialupload;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=true)
     */
    private $status = 'inactive';

    /**
     * @var integer
     *
     * @ORM\Column(name="dwid", type="integer", nullable=true)
     */
    private $dwid;



    /**
     * Set ftitle
     *
     * @param string $ftitle
     *
     * @return DahWorkshopMaterial
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
     * @return DahWorkshopMaterial
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
     * @return DahWorkshopMaterial
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
     * Set dwid
     *
     * @param integer $dwid
     *
     * @return DahWorkshopMaterial
     */
    public function setDwid($dwid)
    {
        $this->dwid = $dwid;

        return $this;
    }

    /**
     * Get dwid
     *
     * @return integer
     */
    public function getDwid()
    {
        return $this->dwid;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
