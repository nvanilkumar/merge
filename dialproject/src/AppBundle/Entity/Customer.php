<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customer
 *
 * @ORM\Table(name="customer", uniqueConstraints={@ORM\UniqueConstraint(name="phone_number", columns={"phone_number"})})
 * @ORM\Entity
 */
class Customer
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=10, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=30, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=30, nullable=false)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=50, nullable=false)
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=20, nullable=false)
     */
    private $phoneNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="acc_code", type="integer", nullable=false)
     */
    private $accCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="customer_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $customerId;



    /**
     * Set title
     *
     * @param string $title
     * @return Customer
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Customer
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Customer
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set company
     *
     * @param string $company
     * @return Customer
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     * @return Customer
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string 
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set accCode
     *
     * @param integer $accCode
     * @return Customer
     */
    public function setAccCode($accCode)
    {
        $this->accCode = $accCode;

        return $this;
    }

    /**
     * Get accCode
     *
     * @return integer 
     */
    public function getAccCode()
    {
        return $this->accCode;
    }

    /**
     * Get customerId
     *
     * @return integer 
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }
}
