<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Contact;
use App\Entity\User;
use App\Form\AppointmentFormType;
use App\Form\ContactFormType;
use App\Form\RegistrationFormType;
use App\Repository\AppointmentRepository;
use App\Utils\GenerateIdentifiant;
use App\Utils\GeneratePassword;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                $contact->setFullName($form->getData()->getFullname());
                $contact->setPhone($form->getData()->getPhone());
                $contact->setEmail($form->getData()->getEmail());
                $contact->setMessage($form->getData()->getMessage());
                $contact->setDateAdd(new \DateTime(date("Y-m-d")));

                $entityManager->persist($contact);
                $entityManager->flush();

                $clientEmail = (new TemplatedEmail())
                    ->from(new Address('no-reply@ocvp-solutions.com', 'OCVP Solutions'))
                    ->to($contact->getEmail())
                    ->subject("Confirmation de contact")
                    ->htmlTemplate('emails/contact-client.html.twig')
                    ->context([]);
                $mailer->send($clientEmail);

                $adminEmail = (new TemplatedEmail())
                    ->from(new Address('no-reply@ocvp-solutions.com', 'OCVP Solutions'))
                    ->to("cyrilperardpro@gmail.com") //URL ADMIN
                    ->subject("Nouveau message")
                    ->htmlTemplate('emails/contact-admin.html.twig')
                    ->context([
                        'fullname' => $contact->getFullName(),
                        'message' => $contact->getMessage(),
                    ]);
                $mailer->send($adminEmail);

                return $this->redirectToRoute('app_contact_confirm', array("status" => "true"));
        }

        return $this->render('contact/contact.html.twig', [
            'pageTitle' => 'contact',
            'first_title' => "Contactez l'équipe",
            'second_title' => "Pour vos projets web",
            'contactForm' => $form->createView(),
        ]);
    }

    #[Route('/contact-confirm', name: 'app_contact_confirm')]
    public function contactConfirm(Request $request): Response
    {
        return $this->render('contact/contact-confirm.html.twig', [
            'pageTitle' => 'contact',
            'first_title' => "Prenez rendez-vous",
            'second_title' => "Pour votre projets",
            'status' => $request->query->get("status"),
        ]);
    }
}
