<?php

namespace App\Controller;

use App\Configuration;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\BadgeReaderRepository;
use App\Repository\DocumentRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Utils\GenerateIdentifiant;
use App\Utils\GeneratePassword;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use function Sodium\add;

#[Route('/user')]
class UserController extends AbstractController
{
    /**
     * @Security("is_granted('ROLE_ADMIN')")
     */
    #[Route('/list/{action}', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, string $action = null): Response
    {
        $newUser = false;
        $removeUser = false;
        switch ($action){
            case "submitnewuser":
                $newUser = true;
                break;
            case "submitremoveuser":
                $removeUser = true;
                break;
        }

        $list_users = null;
        $search = null;
        if(!empty($_GET["search"])){
            $search = $_GET["search"];
            $list_users = $userRepository->findUserBySearchBar($search);
        }else{
            $list_users = $userRepository->findBy([], ['id' => 'DESC']);
        }

        return $this->render('user/index.html.twig', [
            'userConnect' => $this->getUser(),
            'users' => $list_users,
            'newUser' => $newUser,
            'removeUser' => $removeUser,
            'search' => $search,
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     */
    #[Route('/show/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, EntityManagerInterface $entityManager): Response
    {
        return $this->render('user/show.html.twig', [
            'userConnect' => $this->getUser(),
            'user' => $user,
            'ged' => Configuration::getInstance()->getGed("active"),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     */
    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MailerInterface $mailer, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = GeneratePassword::create(8);
            $rolesArr = array('ROLE_USER');

            $user->setPassword($userPasswordHasher->hashPassword($user, $password));
            $user->setRoles($rolesArr);

            $identifier = GenerateIdentifiant::create($user);
            $user->setIdentifier($identifier);
            $user->setDateAdd(new \DateTime(date("Y-m-d")));

            $entityManager->persist($user);
            $entityManager->flush();

            $email = (new TemplatedEmail())
                ->from(new Address('contact@erp-base.com', 'ERP Base'))
                ->to($user->getEmail())
                ->subject("Bienvenue sur L'ERP !")
                ->htmlTemplate('emails/welcome.html.twig')
                ->context([
                    'identifier' => $user->getIdentifier(),
                    'Password' => $password
                ]);

            $mailer->send($email);

            return $this->redirectToRoute('app_user_index', array("action" => "submitnewuser"));

        }

        return $this->renderForm('user/new.html.twig', [
            'userConnect' => $this->getUser(),
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     */
    #[Route('/edit/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $updateGeneralIntern = 0;

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $updateGeneralIntern = 1;
        }

        return $this->renderForm('user/edit.html.twig', [
            'userConnect' => $this->getUser(),
            'user' => $user,
            'form' => $form,
            'updateGeneralIntern' => $updateGeneralIntern,
        ]);

    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     */
    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
            if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
                $entityManager->remove($user);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_user_index', array("action" => "submitremoveuser"), Response::HTTP_SEE_OTHER);

    }
}
