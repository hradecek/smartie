<?php

namespace Smartie\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Ivo Hradek <ivohradek@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="tutorial_rate")
 */
class TutorialRate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $rate;

    //-----------------------------------------------------
    // Relationships
    //-----------------------------------------------------
    /**
     * @ORM\ManyToOne(targetEntity="AppUser", inversedBy="rates")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Tutorial", inversedBy="rates")
     */
    private $tutorial;
}