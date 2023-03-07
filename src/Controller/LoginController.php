<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{

    #[Route('/ocvpAdmin', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authService): Response
    {
        #Si il y a déjà un utilisateur de connecter, on le redirige vers le dashboard.
        if ($this->getUser() !== null) {
            return $this->redirectToRoute('app_home');
        }

        #Si on accéde à cette page via une requete POST, cela veut dire que l'on tente de se connecter, donc on redirige vers le dashboard.
        if ($request->isMethod("POST")) {
            $response = $this->redirectToRoute('app_home');
            return $response;
        }

        $error = $authService->getLastAuthenticationError();

        #Template avec les variables à passer.
        return $this->render('login/index.html.twig', [
            'last_identifier' => $request->get("identifier"),
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
        $response = new RedirectResponse($this->generateUrl("app_login"));
        return $response;
    }

}