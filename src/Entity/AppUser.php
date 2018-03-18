<?php

namespace Smartie\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Entity representing an application user.
 *
 * @author Ivo Hradek <ivohradek@gmail.com>
 *
 * @ORM\Table(name="app_user")
 * @ORM\Entity(repositoryClass="Smartie\Repository\AppUserRepository")
 */
class AppUser implements AdvancedUserInterface, EquatableInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $surname;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     */
    private $birthday;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="is_active")
     */
    private $isActive;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="is_enabled")
     */
    private $isEnabled;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="is_locked")
     */
    private $isLocked;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", name="last_login_at", nullable=true)
     */
    private $lastLoginAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", name="updated_at")
     */
    private $updatedAt;

    //-----------------------------------------------------
    // Relationships
    //-----------------------------------------------------
    /**
     * @var \Doctrine\Common\Collections\Collection|AppRole[]
     *
     * @ORM\ManyToMany(targetEntity="AppRole", inversedBy="users")
     * @ORM\JoinTable(
     *     name="app_role_user",
     *     joinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     *     }
     * )
     */
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    /**
     * @param AppRole
     */
    public function addRole(AppRole $role)
    {
        if ($this->roles->contains($role)) {
            return;
        }

        $this->roles->add($role);
    }

    //-----------------------------------------------------
    // AdvancedUserInterface
    //-----------------------------------------------------
    /**
     * @return AppRole[]|\Doctrine\Common\Collections\Collection
     */
    public function getRoles()
    {
        return $this->roles->map(function (AppRole $role) { return $role->getName(); })->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    { }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return !$this->isLocked;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    //-----------------------------------------------------
    // Equatable
    //-----------------------------------------------------
    /**
     * {@inheritdoc}
     */
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return false;
        }

        if ($user->username !== $this->username) {
            return false;
        }

        if ($user->email !== $this->email) {
            return false;
        }

        if ($user->password !== $user->password) {
            return false;
        }

        return true;
    }

    //-----------------------------------------------------
    // Serializable
    //-----------------------------------------------------
    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->username,
            $this->password,
            $this->roles
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->username,
            $this->password,
            $this->roles
            ) = unserialize($serialized);
    }

    //-----------------------------------------------------
    // Getters & Setters
    //-----------------------------------------------------
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param DateTime $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @param bool $isEnabled
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;
    }

    /**
     * @param bool $isLocked
     */
    public function setIsLocked($isLocked)
    {
        $this->isLocked = $isLocked;
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        return $this->isLocked;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return DateTime
     */
    public function getLastLoginAt()
    {
        return $this->lastLoginAt;
    }

    /**
     * @param DateTime $lastLoginAt
     */
    public function setLastLoginAt($lastLoginAt)
    {
        $this->lastLoginAt = $lastLoginAt;
    }
}
