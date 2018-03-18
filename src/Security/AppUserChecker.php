<?php

namespace Smartie\Security;

use Smartie\Entity\AppUser;
use Smartie\Facade\AppUserFacade;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * <p>Check conditions, that have to be met in order to proceed to successful authentication.
 * <p>User account has to be:
 * <ul>
 *  <li>enabled,</li>
 *  <li>unlocked.</li>
 * </ul>
 *
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class AppUserChecker implements UserCheckerInterface
{
    /**
     * @var AppUserFacade
     */
    private $userFacade;

    /**
     * Constructor
     *
     * @param AppUserFacade $userFacade
     */
    public function __construct(AppUserFacade $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * {@inheritdoc}
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if ($user->isLocked()) {
            $e = new LockedException('User account is locked.');
            $e->setUser($user);
            throw $e;
        }

        if (!$user->isEnabled()) {
            $e = new DisabledException('User account is disabled.');
            $e->setUser($user);
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkPostAuth(UserInterface $user)
    {
        $this->userFacade->activate($user->getUsername(), true);
    }
}