<?php

namespace Smartie\Security;

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
     * {@inheritdoc}
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUserChecker) {
            return;
        }

        if (!$user->isEnabled()) {
            throw (new DisabledException())->setUser($user);
        } else if ($user->isLocked()) {
            throw (new LockedException())->setUser($user);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkPostAuth(UserInterface $user)
    { }
}