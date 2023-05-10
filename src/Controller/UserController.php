<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;

class UserController extends AbstractController
{
    /**
    * @Route(path = "/login", name = "login", methods = {"GET"})
    */
    public function login(Request $request, UserAuthenticatorInterface $userAuthenticator, AuthenticatorInterface $authenticator,
                         UserRepository $userRepository): ?Response
    {
        $request->getSession()->set('moodleID', 12346);

        $id = $request->getSession()->get('moodleID');


        // check si l'utilisateur est en DB, et l'authentifie
        $user = $userRepository->findByID($id);
        if($user !== null) {
            $request->request->set('moodleUserID', $id);
            return $userAuthenticator->authenticateUser($user, $authenticator, $request);
        }

        throw new AccessDeniedException();
    }

    /**
     * @Route(path = "/logout", name = "logout", methods = {"GET"})
     */
    public function logout(): void { }

}