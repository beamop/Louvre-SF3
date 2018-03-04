<?php

namespace AppBundle\Mailer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Reservation;

class SwiftMailer extends Controller
{
    private $twig;
    private $mailer;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(\Twig_Environment $twig_Environment, EntityManagerInterface $entityManager, \Swift_Mailer $mailer)
    {
        $this->twig = $twig_Environment;
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
    }

    public function sendEmail(Reservation $reservation)
    {
        $message = (new \Swift_Message('[Musée du Louvre] Merci pour votre réservation'))
            ->setFrom('noreply@louvre.fr')
            ->setTo($reservation->getEmail())
            ->setBody($this->twig->render('AppBundle/AppController/email/merci.html.twig', array(
                'reservation' => $reservation
            ))
        );

        $message->setContentType("text/html");

        $this->mailer->send($message);

        /*
         * Après que l'email est parti on change le statut du paiement. (dev)
         */
        $reservation->addPayement();

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return $reservation;
    }
}