<?php

namespace Smartie\Facade;

use Smartie\Entity\AppUser;
use Smartie\Repository\AppUserRepository;
use Smartie\Utils\EntityUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Facade for managing users actions.
 *
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class AppUserFacade
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var AppUserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * Constructor
     *
     * @param EntityManagerInterface $entityManager
     * @param AppUserRepository $userRepository
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        AppUserRepository $userRepository,
        UserPasswordEncoderInterface $encoder)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    /**
     * Create an user based on provided data.
     *
     * @param array $data
     * @return AppUser
     */
    public function createUser(array $data)
    {
        $user = new AppUser();
        EntityUtils::fill($user, $data);
        $password = $this->encoder->encodePassword($user, $data['password']);
        $user->setPassword($password);
        $user->setIsActive(false);
        $user->setIsLocked(true);
        $user->setIsEnabled(true);
        $now = new \DateTime();
        $user->setCreatedAt($now);
        $user->setUpdatedAt($now);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}