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
    * @Route(path = "/login/{id}", requirements = {"id" = "^\d+$"}, name = "login", methods = {"GET"})
    */
    public function login(Request $request, UserAuthenticatorInterface $userAuthenticator, AuthenticatorInterface $authenticator,
                         UserRepository $userRepository, int $id): ?Response
    {
        // TODO supprimer ghost code
        // id Super Admin
        //$request->getSession()->set('moodleID', 12346);
        // id Admin
//        $request->getSession()->set('moodleID', 12346);

        // id Etudiant
        // $request->getSession()->set('moodleID', 15018);
        // $id = $request->getSession()->get('moodleID');


        // check si l'utilisateur est en DB, puis l'authentifie (cf. app/Security/Authenticator.php)
        $user = $userRepository->findByID($id);
        if($user !== null) {
            $request->request->set('moodleUserID', $user->getMoodleUserId());
            return $userAuthenticator->authenticateUser($user, $authenticator, $request);
        }

        throw new AccessDeniedException("Accès non autorisé");
    }

    /**
     * @Route(path = "/logout", name = "logout", methods = {"GET"})
     */
    public function logout(): void { }

}