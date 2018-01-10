<?php

namespace AdminBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * DahUsers
 *
 * @ORM\Table(name="dah_users")
 * @ORM\Entity
 */
class DahUsers implements AdvancedUserInterface, \Serializable {

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=150, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100, nullable=true)
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
     * @ORM\Column(name="verify", type="string", length=150, nullable=true)
     */
    private $verify;
    
    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=150, nullable=true)
     */
    private $avatar;

    /**
     * @var integer
     *
     * @ORM\Column(name="roleid", type="integer", nullable=true)
     */
    private $roleid;
    /**
     * @var string
     *
     * @ORM\Column(name="cv", type="string", length=150, nullable=true)
     */
    private $cv;
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=true)
     */
    private $status = 'active';

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
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var integer
     *
     * @ORM\Column(name="last_login", type="integer", nullable=true)
     */
    private $lastLogin;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $uid;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return DahUsers
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return DahUsers
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

    public function getUsername() {
        return $this->email;
    }

    /**
     * Get roles
     *
     * @return [string]
     */
    public function getRoles() {
        return [$this->role];
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return DahUsers
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
     * Set salt
     *
     * @param string $salt
     *
     * @return DahAdmin
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
     * Set verify
     *
     * @param string $verify
     *
     * @return DahAdmin
     */
    public function setVerify($verify) {
        $this->verify = $verify;

        return $this;
    }

    /**
     * Get verify
     *
     * @return string
     */
    public function getVerify() {
        return $this->verify;
    }
    
    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return DahAdmin
     */
    public function setAvatar($avatar) {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar() {
        return $this->avatar;
    }
    /**
     * Set cv
     *
     * @param string $cv
     *
     * @return DahAdmin
     */
    public function setCv($cv) {
        $this->cv = $cv;

        return $this;
    }

    /**
     * Get cv
     *
     * @return string
     */
    public function getCv() {
        return $this->cv;
    }

    /**
     * Set roleid
     *
     * @param integer $roleid
     *
     * @return DahUsers
     */
    public function setRoleid($roleid) {
        $this->roleid = $roleid;

        return $this;
    }

    /**
     * Get roleid
     *
     * @return integer
     */
    public function getRoleid() {
        return $this->roleid;
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
     * Set addedOn
     *
     * @param integer $addedOn
     *
     * @return DahUsers
     */
    public function setAddedOn($addedOn) {
        $this->addedOn = $addedOn;

        return $this;
    }

    /**
     * Get addedOn
     *
     * @return integer
     */
    public function getAddedOn() {
        return $this->addedOn;
    }

    /**
     * Set updatedOn
     *
     * @param integer $updatedOn
     *
     * @return DahUsers
     */
    public function setUpdatedOn($updatedOn) {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get updatedOn
     *
     * @return integer
     */
    public function getUpdatedOn() {
        return $this->updatedOn;
    }

    /**
     * Set lastLogin
     *
     * @param integer $lastLogin
     *
     * @return DahUsers
     */
    public function setLastLogin($lastLogin) {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return integer
     */
    public function getLastLogin() {
        return $this->lastLogin;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive() {
        return $this->isActive;
    }

    /**
     * Get uid
     *
     * @return integer
     */
    public function getUid() {
        return $this->uid;
    }

    /**
     * @var string
     */
    private $fname;

    /**
     * @var string
     */
    private $lname;

    /**
     * @var string
     */
    private $role;

    /**
     * Set fname
     *
     * @param string $fname
     * @return DahUsers
     */
    public function setFname($fname) {
        $this->fname = $fname;

        return $this;
    }

    /**
     * Get fname
     *
     * @return string 
     */
    public function getFname() {
        return $this->fname;
    }

    /**
     * Set lname
     *
     * @param string $lname
     * @return DahUsers
     */
    public function setLname($lname) {
        $this->lname = $lname;

        return $this;
    }

    /**
     * Get lname
     *
     * @return string 
     */
    public function getLname() {
        return $this->lname;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return DahUsers
     */
    public function setRole($role) {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole() {
        return $this->role;
    }

    public function eraseCredentials() {
        
    }

    /** @see \Serializable::serialize() */
    public function serialize() {
        return serialize(array(
            $this->uid,
            $this->email,
            $this->password,
                // see section on salt below
                // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized) {
        list (
                $this->uid,
                $this->username,
                $this->password,
                // see section on salt below
                // $this->salt
                ) = unserialize($serialized);
    }

    public function isEnabled() {
        return $this->getIsActive();
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

}
