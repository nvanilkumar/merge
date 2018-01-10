<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahDepartments
 *
 * @ORM\Table(name="dah_departments")
 * @ORM\Entity
 */
class DahDepartments
{
    /**
     * @var string
     *
     * @ORM\Column(name="department", type="string", length=100, nullable=false)
     */
    private $department;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="added_on", type="integer", nullable=false)
     */
    private $addedOn;

    /**
     * @var integer
     *
     * @ORM\Column(name="updated_on", type="integer", nullable=false)
     */
    private $updatedOn;

    /**
     * @var integer
     *
     * @ORM\Column(name="deptid", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $deptid;



    /**
     * Set department
     *
     * @param string $department
     *
     * @return DahDepartments
     */
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return string
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return DahDepartments
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
     * Set addedOn
     *
     * @param integer $addedOn
     *
     * @return DahDepartments
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
     * @return DahDepartments
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
     * Get deptid
     *
     * @return integer
     */
    public function getDeptid()
    {
        return $this->deptid;
    }
}
