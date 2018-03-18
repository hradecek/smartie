<?php

namespace Smartie\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Ivo Hradek <ivohradek@gmail.com>
 *
 * @ORM\Entity(repositoryClass="Smartie\Repository\TutorialRepository")
 * @ORM\Table(name="tutorial")
 */
class Tutorial
{
    use HasTimestamps;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    //-----------------------------------------------------
    // Relationships
    //-----------------------------------------------------
    /**
     * @var \Doctrine\Common\Collections\Collection|AppUser[]
     *
     * @ORM\ManyToMany(targetEntity="AppUser", mappedBy="tutorials")
     */
    private $authors;

    /**
     * @var \Doctrine\Common\Collections\Collection|TutorialComment[]
     *
     * @ORM\OneToMany(targetEntity="TutorialComment", mappedBy="tutorial")
     */
    private $comments;

    /**
     * @var TutorialCategory
     *
     * @ORM\ManyToOne(targetEntity="TutorialCategory", inversedBy="tutorials")
     */
    private $category;
}
