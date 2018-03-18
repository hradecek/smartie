<?php

namespace Smartie\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Ivo Hradek <ivohradek@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="tutorial_category")
 */
class TutorialCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tutorials = new ArrayCollection();
    }

    //-----------------------------------------------------
    // Relationships
    //-----------------------------------------------------
    /**
     * @var \Doctrine\Common\Collections\Collection|Tutorial[]
     *
     * @ORM\OneToMany(targetEntity="Tutorial", mappedBy="category")
     */
    private $tutorials;

    //-----------------------------------------------------
    // Getters & Setters
    //-----------------------------------------------------
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|Tutorial[]
     */
    public function getTutorials()
    {
        return $this->tutorials;
    }

    /**
     * @param Tutorial $tutorial
     */
    public function addTutorial($tutorial)
    {
        $this->tutorials->add($tutorial);
    }
}
