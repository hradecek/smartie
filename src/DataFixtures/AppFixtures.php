<?php

namespace Smartie\DataFixtures;

use Smartie\Entity\AppUser;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * Constructor
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = new AppUser();
        $user->setUsername('user');
        $user->setEmail('user@gmail.com');
        $user->setBirthday(date_create("1990-01-01"));
        $user->setCreatedAt(new DateTime());
        $user->setUpdatedAt(new DateTime());
        $user->setPassword($this->encoder->encodePassword($user, 'user123#'));
        $user->setName('user');
        $user->setSurname('userowich');
        $user->setIsEnabled(true);
        $user->setIsActive(true);
        $user->setIsLocked(false);

        $manager->persist($user);
        $manager->flush();
    }
}