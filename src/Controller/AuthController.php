<?php

namespace Smartie\Controller;

use Smartie\Facade\AppUserFacade;
use Smartie\Form\AppUserType;
use Smartie\Request\CreateAppUserRequest;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Handles authentication actions e.g. Login, Registration, ...
 *
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
class AuthController extends Controller
{
    /**
     * @var string
     */
    const REGISTRATION_EMAIL = 'dkrjkk@gmail.com';

    /**
     * @var string
     */
    const REGISTRATION_TEMPLATE_PATH = 'auth/email/registration.html.twig';

    /**
     * @var AppUserFacade
     */
    private $userFacade;

    /**
     * Constructor.
     *
     * @param AppUserFacade $userFacade
     */
    public function __construct(AppUserFacade $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * Register and persists a new user.
     *
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/register", name="app_user_registration")
     */
    public function register(Request $request, Swift_Mailer $mailer)
    {
        $userRequest = new CreateAppUserRequest();
        $form = $this->createForm(AppUserType::class, $userRequest);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // TODO: Check
            $this->userFacade->createUser([
                'email' => $userRequest->email,
                'username' => $userRequest->username,
                'password' => $userRequest->password,
                'name' => $userRequest->name,
                'surname' => $userRequest->surname,
                'birthday' => $userRequest->birthday
            ]);

            $recipients = $mailer->send($this->createActivationEmail($userRequest));
            if ($recipients <= 0) {
                throw new Exception();
            }

            return $this->redirectToRoute('app_user_login');
        }

        return $this->render('auth/registration.html.twig', [
            'form' => $form->createView()
            ]
        );
    }

    private function createActivationEmail($userRequest)
    {
        $message = (new Swift_Message('Welcome to Smartie!'))
            ->setFrom(self::REGISTRATION_EMAIL)
            ->setTo($userRequest->email)
            ->setBody(
                $this->renderView(
                    self::REGISTRATION_TEMPLATE_PATH,
                    [
                        'name' => $userRequest->name,
                        'username' => $userRequest->username
                    ]
                ), 'text/html'
            );

        return $message;
    }

    /**
     * Login an existing user.
     *
     * @param Request $request
     * @param AuthenticationUtils $authUtils
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/login", name="app_user_login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
            ]
        );
    }
}
