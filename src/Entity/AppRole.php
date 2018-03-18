<?php

namespace Smartie\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterfdddace;

/**
 * @author Ivo Hradek <ivohradek@gmail.com>
 *
 * @ORM\Table(name="app_role")
 * @ORM\Entity(repositoryClass="Smartie\Repository\AppRoleRepository")
 */
class AppRole
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
     * @ORM\Column(type="string", unique=true)
     */
    private $name;

    //-----------------------------------------------------
    // Relationships
    //-----------------------------------------------------
    /**
     * @var \Doctrine\Common\Collections\Collection|AppRole[]
     *
     * @ORM\ManyToMany(targetEntity="AppUser", mappedBy="roles")
     */
    private $users;

    /**
     * @param AppUser $user
     */
    public function addUser(AppUser $user)
    {
        if ($this->users->contains($user)) {
            return;
        }

        $this->users->add($user);
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
     * @return \Doctrine\Common\Collections\Collection|AppRole[]
     */
    public function getUsers()
    {
        return $this->users;
    }
}