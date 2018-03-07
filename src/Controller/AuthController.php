<?php

namespace Smartie\Controller;

use Smartie\Facade\AppUserFacade;
use Smartie\Form\AppUserType;
use Smartie\Request\CreateAppUserRequest;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    const REGISTRATION_EMAIL = 'ivohradek@gmail.com';

    /**
     * @var string
     */
    const REGISTRATION_TEMPLATE_PATH = 'auth/email/registration.html.twig';

    /**
     * @var int
     */
    const REGISTRATION_CODE_EXPIRATION = 28800; /* 8 hours */

    /**
     * @var AppUserFacade
     */
    private $userFacade;

    /**
     * @var
     */
    private $cache;

    /**
     * Constructor.
     *
     * @param AppUserFacade $userFacade
     */
    public function __construct(AppUserFacade $userFacade)
    {
        $this->userFacade = $userFacade;
        $this->cache = new FilesystemAdapter();
    }

    /**
     * Register and persists a new user.
     *
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/user/register", name="app_user_register")
     */
    public function register(Request $request, Swift_Mailer $mailer)
    {
        $userRequest = new CreateAppUserRequest();
        $form = $this->createForm(AppUserType::class, $userRequest);
        $userRequest->toArray();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userFacade->createUser($userRequest->toArray());
            if (null == $user) {
                $this->addFlash('error', 'Unable to register a user. Please contact us at: ' . self::REGISTRATION_EMAIL);
                return $this->redirectToRoute('app_user_register');
            }

            $recipients = $mailer->send($this->createActivationEmail($userRequest));
            if ($recipients <= 0) {
                $this->addFlash('error', 'Unable to send a activation email. Please contact us at: ' . self::REGISTRATION_EMAIL);
                return $this->redirectToRoute('app_user_login');
            }

            $this->addFlash('info', 'Confirmation email hast been sent to ' . $userRequest->email . '. Please visit your email.');
            return $this->redirectToRoute('app_user_login');
        }

        return $this->render('auth/registration.html.twig', ['form' => $form->createView()]);
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
                        'username' => $userRequest->username,
                        'code' => $this->createActivationCode($userRequest->username)
                    ]
                ), 'text/html'
            );

        return $message;
    }

    private function createActivationCode($username)
    {
        $code = base64_encode(random_bytes(64));
        $userCode = $this->cache->getItem('activationCode.' . $username);
        $userCode->set($code);
        $userCode->expiresAfter(self::REGISTRATION_CODE_EXPIRATION);
        $this->cache->save($userCode);

        return $code;
    }

    /**
     * Activate user based on registration process.
     *
     * @param $username
     * @param Request $request
     *
     * @Route("/user/{username}/activate", name="app_user_activate")
     *
     * @return Response
     */
    public function activate($username, Request $request)
    {
        $activationCode = $request->query->get('activationCode');
        if (empty($activationCode)) {
            return $this->redirectToRoute('app_user_login');
        }

        $generatedCode = $this->cache->getItem('activationCode.' . $username)->get();
        if ($generatedCode !== $activationCode) {
            $this->cache->deleteItem('activationCode' . $username);
            $this->addFlash('error', 'Activation was not successful. Activation code is not valid!');
            return $this->redirectToRoute('app_user_login');
        }

        $user = $this->userFacade->activateUser($username);
        if (null === $user) {
            $this->addFlash('error', 'No user \''. $username . '\' has been registered!');
            return $this->redirectToRoute('app_user_login');
        }

        $this->cache->deleteItem('activationCode' . $username);
        $this->addFlash('info', 'Activation was successful. Thank you, '. $user->getName() . '!');

        /* TODO: Auto login */
        return $this->redirectToRoute('app_user_login');
    }

    /**
     * Login an existing user.
     *
     * @param Request $request
     * @param AuthenticationUtils $authUtils
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/user/login", name="app_user_login")
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
