<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\User;
use App\Form\AppointmentFormType;
use App\Form\RegistrationFormType;
use App\Repository\AppointmentRepository;
use App\Utils\GenerateIdentifiant;
use App\Utils\GeneratePassword;
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
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'first_title' => "Contactez l'équipe",
            'second_title' => "pour vos projets web"
        ]);
    }

    #[Route('/appointment', name: 'app_appointment')]
    public function appointment(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager, AppointmentRepository $appointmentRepository): Response
    {
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentFormType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $controle_houre = $appointmentRepository->findBy(array("houre" => $request->query->get("houre"), "date_appointment" => new \DateTime($request->query->get("date"))));
            if(empty($controle_houre)){
                $password = GeneratePassword::create(8);

                $appointment->setDay("Monday");
                $appointment->setHoure($request->query->get("houre"));
                $appointment->setEmail($form->getData()->getEmail());
                $appointment->setDateAppointment(new \DateTime($request->query->get("date")));
                $appointment->setDateAdd(new \DateTime(date("Y-m-d")));

                $entityManager->persist($appointment);
                $entityManager->flush();

                $email = (new TemplatedEmail())
                    ->from(new Address('no-reply@ocvp-solutions.com', 'OCVP Solutions'))
                    ->to($appointment->getEmail())
                    ->subject("Confirmation de rendez-vous")
                    ->htmlTemplate('emails/welcome.html.twig')
                    ->context([
                        'identifier' => "test",
                        'Password' => $password
                    ]);

                $mailer->send($email);

                return $this->redirectToRoute('app_appointment_confirm', array("status" => "true"));
            }else{
                return $this->redirectToRoute('app_appointment_confirm', array("status" => "false"));
            }
        }

        $houre_param = "";
        if(!empty($_GET["houre"])){
            $houre_param = $_GET["houre"];
        }

        $day_list = ["Monday", "Tuesday", "Wednesday", "thursday", "Friday"];
        $houre_list = ["9", "10", "11", "12"];
        $current_houre_list = [];
        foreach ($houre_list as $value){
            $controle_houre = $appointmentRepository->findBy(array("houre" => $value, "date_appointment" => new \DateTime()));
            if(empty($controle_houre)){
                $current_houre_list[$value] = "available";
            }else{
                $current_houre_list[$value] = "not available";
            }
        }

        //dd($current_houre_list);
        return $this->render('contact/appointment.html.twig', [
            'controller_name' => 'ContactController',
            'first_title' => "Prenez rendez-vous",
            'second_title' => "pour votre projets",
            'current_houre_list' => $current_houre_list,
            'houre_param' => $houre_param,
            'appointmentForm' => $form->createView(),
        ]);
    }

    #[Route('/appointment-confirm', name: 'app_appointment_confirm')]
    public function appointmentConfirm(Request $request): Response
    {
        return $this->render('contact/appointment-confirm.html.twig', [
            'controller_name' => 'ContactController',
            'first_title' => "Prenez rendez-vous",
            'second_title' => "pour votre projets",
            'status' => $request->query->get("status"),
        ]);
    }
}
