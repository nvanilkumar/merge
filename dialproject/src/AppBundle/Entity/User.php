<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="email", columns={"email"}), @ORM\UniqueConstraint(name="extension", columns={"extension"})}, indexes={@ORM\Index(name="role_id", columns={"role_id"})})
 * @ORM\Entity
 */
class User implements AdvancedUserInterface, \Serializable {

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=30, nullable=false)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=60, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=20, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=150, nullable=false)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", nullable=true)
     */
    private $gender;

    /**
     * @var integer
     *
     * @ORM\Column(name="extension", type="integer", nullable=false)
     */
    private $extension;

    /**
     * @var integer
     *
     * @ORM\Column(name="pin", type="integer", nullable=false)
     */
    private $pin;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="added_on", type="datetime", nullable=true)
     */
    private $addedOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_on", type="datetime", nullable=true)
     */
    private $updatedOn;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_deleted", type="boolean", nullable=true)
     */
    private $isDeleted;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="astrisk_login", type="boolean", nullable=true)
     */
    private $astriskLogin;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="hangup_agent", type="boolean", nullable=true)
     */
    private $hangupAgent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userId;

    /**
     * @var \AppBundle\Entity\Roles
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Roles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="role_id")
     * })
     */
    private $role;

    /**
     * Constructor
     */
    public function __construct() {

        //$this->role = new ArrayCollection();
        $this->salt = md5(uniqid(null, true));
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     * @return User
     */
    public function setFullName($fullName) {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string 
     */
    public function getFullName() {
        return $this->fullName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender) {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender() {
        return $this->gender;
    }
    
    /**
     * Set hangupAgent
     *
     * @param boolean $hangupAgent
     * @return User
     */
    public function setHangupAgent($hangupAgent)
    {
        $this->hangupAgent = $hangupAgent;

        return $this;
    }

    /**
     * Get hangupAgent
     *
     * @return boolean 
     */
    public function getHangupAgent()
    {
        return $this->hangupAgent;
    }
    
    /**
     * Set extension
     *
     * @param integer $extension
     * @return User
     */
    public function setExtension($extension) {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return integer 
     */
    public function getExtension() {
        return $this->extension;
    }

    /**
     * Set pin
     *
     * @param integer $pin
     * @return User
     */
    public function setPin($pin) {
        $this->pin = $pin;

        return $this;
    }

    /**
     * Get pin
     *
     * @return integer 
     */
    public function getPin() {
        return $this->pin;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return User
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
     * Set addedOn
     *
     * @param \DateTime $addedOn
     * @return User
     */
    public function setAddedOn($addedOn) {
        $this->addedOn = $addedOn;

        return $this;
    }

    /**
     * Get addedOn
     *
     * @return \DateTime 
     */
    public function getAddedOn() {
        return $this->addedOn;
    }

    /**
     * Set updatedOn
     *
     * @param \DateTime $updatedOn
     * @return User
     */
    public function setUpdatedOn($updatedOn) {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get updatedOn
     *
     * @return \DateTime 
     */
    public function getUpdatedOn() {
        return $this->updatedOn;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     * @return User
     */
    public function setIsDeleted($isDeleted) {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean 
     */
    public function getIsDeleted() {
        return $this->isDeleted;
    }
    
    /**
     * Set astriskLogin
     *
     * @param boolean $astriskLogin
     * @return User
     */
    public function setAstriskLogin($astriskLogin) {
        $this->astriskLogin = $astriskLogin;

        return $this;
    }

    /**
     * Get astriskLogin
     *
     * @return boolean 
     */
    public function getAstriskLogin() {
        return $this->astriskLogin;
    }

    /**
     * Set lastLogin
     *
     * @param \DateTime $lastLogin
     * @return User
     */
    public function setLastLogin($lastLogin) {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return \DateTime 
     */
    public function getLastLogin() {
        return $this->lastLogin;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * Set role
     *
     * @param \AppBundle\Entity\Roles $role
     * @return User
     */
    public function setRole(\AppBundle\Entity\Roles $role = null) {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \AppBundle\Entity\Roles 
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return JblUsers
     */
    public function setSalt($salt) {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * Functions UserInterface
     * 
     */
    public function eraseCredentials() {
        
    }

    public function getRoles() {

        return [$this->role];
    }
   
    /*
    public function equals(UserInterface $user) {
        return (boolean)($user->getUsername() === $this->login);
        
    }*/

    public function getUsername() {
        return $this->email;
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function isEnabled() {
        
        return ($this->getStatus() === 'active' );
        
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize() {
        return serialize(array(
            $this->userId,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized) {
        list (
                $this->userId,
                ) = unserialize($serialized);
    }

    /**
     * @var boolean
     */
    private $webLogin;


    /**
     * Set webLogin
     *
     * @param boolean $webLogin
     * @return User
     */
    public function setWebLogin($webLogin)
    {
        $this->webLogin = $webLogin;

        return $this;
    }

    /**
     * Get webLogin
     *
     * @return boolean 
     */
    public function getWebLogin()
    {
        return $this->webLogin;
    }
}
