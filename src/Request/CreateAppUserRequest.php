<?php

namespace Smartie\Request;

use DateTime;
use Smartie\Validator\Constraints\Unique;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing a create request for a new user.
 *
 * @author Ivo Hradek <ivohradek@gmail.com>
 *
 * @Unique(entityClass="Smartie\Entity\AppUser", fields={"username", "email"})
 */
class CreateAppUserRequest extends AppRequest
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    public $username;

    /**
     * @var string
     *
     * @Assert\Email()
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    public $email;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    public $password;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $surname;

    /**
     * @var DateTime
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     * @Assert\LessThan("1 year")
     * @Assert\GreaterThan("-200 years")
     */
    public $birthday;
}