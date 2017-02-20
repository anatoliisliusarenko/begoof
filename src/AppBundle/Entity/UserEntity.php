<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * UserEntity
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class UserEntity implements AdvancedUserInterface, \Serializable {
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=255)
     */
    private $fullName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registered", type="datetime")
     */
    private $registered;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_active", type="datetime")
     */
    private $lastActive;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255)
     */
    private $role;

    /**
    * Counstructor of the class
    */
    public function __construct() {
        $now = new \DateTime();
        $this->registered = $now;
        $this->lastActive = $now;
        $this->active = false;
        $this->role = 'ROLE_USER';
        $this->tokens = new ArrayCollection();
    }

    /* ===== Public getters and setters ===== */

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return UserEntity
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
     *
     * @return UserEntity
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return UserEntity
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return UserEntity
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
     * Set registered
     *
     * @param \DateTime $registered
     *
     * @return UserEntity
     */
    public function setRegistered($registered) {
        $this->registered = $registered;

        return $this;
    }

    /**
     * Get registered
     *
     * @return \DateTime
     */
    public function getRegistered() {
        return $this->registered;
    }

    /**
     * Set lastActive
     *
     * @param \DateTime $lastActive
     *
     * @return UserEntity
     */
    public function setLastActive($lastActive) {
        $this->lastActive = $lastActive;

        return $this;
    }

    /**
     * Get lastActive
     *
     * @return \DateTime
     */
    public function getLastActive() {
        return $this->lastActive;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return UserEntity
     */
    public function setActive($active) {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive() {
        return $this->active;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return UserEntity
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

    /* ===== Entity Relationships ===== */

    /**
    * @ORM\OneToMany(targetEntity="TokenEntity", mappedBy="user")
    */
    private $tokens;

    /* ===== Methods of AdvancedUserInterface ===== */

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername() {
        return $this->username;
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
    * Get salt
    *
    * @return string
    */
    public function getSalt() {
        return null;
    }

    /**
    * Get roles
    *
    * @return array
    */
    public function getRoles() {
        return array($this->role);   
    }

    /**
    * Erase credentials
    */
    public function eraseCredentials() {

    }

    /**
    * Checks whether or not user is enabled 
    *
    * @return bool
    */
    public function isEnabled() {
        return $this->active;
    }

    /**
    * Checks whether or not user's account is non expired
    *
    * @return bool
    */
    public function isAccountNonExpired() {
        return true;
    }

    /**
    * Checks whether or not user's account is non locked
    *
    * @return bool
    */
    public function isAccountNonLocked() {
        return true;
    }

    /**
    * Checks whether or not user's credentials is non expired
    *
    * @return bool
    */
    public function isCredentialsNonExpired() {
        return true;
    }

    /* ===== Methods of Serializable ===== */

    /**
    * Serialize data of the user
    */
    public function serialize() {
        return serialize(array(
            $this->id,
            $this->username,
            $this->email,
            $this->fullName,
            $this->active
        ));
    }

    /**
    * Unserialize data of the user
    */
    public function unserialize($serialized) {
        list(
            $this->id,
            $this->username,
            $this->email,
            $this->fullName,
            $this->active
        ) = unserialize($serialized);
    }
}

