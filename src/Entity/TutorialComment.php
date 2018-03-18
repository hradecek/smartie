<?php

namespace Smartie\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Ivo Hradek <ivohradek@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="tutorial_comment")
 */
class TutorialComment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * Constructor
     *
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    //-----------------------------------------------------
    // Relationships
    //-----------------------------------------------------
    /**
     * @var TutorialComment
     *
     * @ORM\ManyToOne(targetEntity="TutorialComment", inversedBy="comments")
     */
    private $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection|TutorialComment[]
     *
     * @ORM\OneToMany(targetEntity="TutorialComment", mappedBy="parent")
     */
    private $comments;

    /**
     * @var AppUser
     *
     * @ORM\ManyToOne(targetEntity="AppUser", inversedBy="comments")
     */
    private $author;

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
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param TutorialComment $parent
     */
    public function setParent(TutorialComment $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param TutorialComment $comment
     */
    public function addComment(TutorialComment $comment)
    {
        $this->comments->add($comment);
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor(AppUser $author)
    {
        $this->author = $author;
    }
}