<?php

namespace Smartie\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    private $role;

    /**
     * @var \Doctrine\Common\Collections\Collection|AppRole[]
     *
     * @ORM\ManyToMany(targetEntity="AppUser", mappedBy="roles")
     */
    private $users;
}