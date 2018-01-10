<?php

namespace AdminBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\Mapping as ORM;

/**
 * DahAdmin
 *
 * @ORM\Table(name="dah_admin")
 * @ORM\Entity
 */
class DahAdmin implements UserInterface, \Serializable
{
    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=200, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=150, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=150, nullable=true)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150, nullable=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="lastlogin", type="bigint", nullable=true)
     */
    private $lastlogin;

    /**
     * @var string
     *
     * @ORM\Column(name="lastlogin_ip", type="string", length=30, nullable=true)
     */
    private $lastloginIp;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_on", type="bigint", nullable=true)
     */
    private $createdOn;

    /**
     * @var integer
     *
     * @ORM\Column(name="updated_on", type="bigint", nullable=true)
     */
    private $updatedOn;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=true)
     */
    private $status = 'active';

    /**
     * @var string
     *
     * @ORM\Column(name="default_admin", type="string", nullable=true)
     */
    private $defaultAdmin = 'no';

    /**
     * @var integer
     *
     * @ORM\Column(name="adminid", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $adminid;



    /**
     * Set username
     *
     * @param string $username
     *
     * @return DahAdmin
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return DahAdmin
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return DahAdmin
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return DahAdmin
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return DahAdmin
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastlogin
     *
     * @param integer $lastlogin
     *
     * @return DahAdmin
     */
    public function setLastlogin($lastlogin)
    {
        $this->lastlogin = $lastlogin;

        return $this;
    }

    /**
     * Get lastlogin
     *
     * @return integer
     */
    public function getLastlogin()
    {
        return $this->lastlogin;
    }

    /**
     * Set lastloginIp
     *
     * @param string $lastloginIp
     *
     * @return DahAdmin
     */
    public function setLastloginIp($lastloginIp)
    {
        $this->lastloginIp = $lastloginIp;

        return $this;
    }

    /**
     * Get lastloginIp
     *
     * @return string
     */
    public function getLastloginIp()
    {
        return $this->lastloginIp;
    }

    /**
     * Set createdOn
     *
     * @param integer $createdOn
     *
     * @return DahAdmin
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get createdOn
     *
     * @return integer
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Set updatedOn
     *
     * @param integer $updatedOn
     *
     * @return DahAdmin
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
     *
     * @return DahAdmin
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
     * Set defaultAdmin
     *
     * @param string $defaultAdmin
     *
     * @return DahAdmin
     */
    public function setDefaultAdmin($defaultAdmin)
    {
        $this->defaultAdmin = $defaultAdmin;

        return $this;
    }

    /**
     * Get defaultAdmin
     *
     * @return string
     */
    public function getDefaultAdmin()
    {
        return $this->defaultAdmin;
    }

    /**
     * Get adminid
     *
     * @return integer
     */
    public function getAdminid()
    {
        return $this->adminid;
    }
    
    /**
     * Get roles
     *
     * @return [string]
     */
    public function getRoles()
    {
        return ['ROLE_ADMIN'];
    }
    
    public function eraseCredentials()
    {
    }
    
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->adminid,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }
    
    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->adminid,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }
}
