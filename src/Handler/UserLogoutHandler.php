<?php

namespace Smartie\Handler;

use Smartie\Facade\AppUserFacade;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

/**
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class UserLogoutHandler implements LogoutHandlerInterface
{
    /**
     * Url to redirect after successful logout
     */
    const REDIRECT_URL = '/';

    /**
     * AppUserFacade
     */
    private $userFacade;

    /**
     * Construct.
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
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $this->userFacade->activate($token->getUsername(), false);
    }
}
