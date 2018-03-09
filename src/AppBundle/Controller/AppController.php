<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Billet;
use AppBundle\Entity\Reservation;
use AppBundle\Form\ReservationType;
use AppBundle\Mailer\SwiftMailer;
use AppBundle\Payment\StripePayment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller
{
    /**
     * @Route("/reservation", name="reservation")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reservationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $reservation = new Reservation();
        $reservation
            ->addBillet(new Billet())
            ->setIp($request->getClientIp()
        );

        $listDatesCompletes = $em->getRepository('AppBundle:Reservation')->getDateFull();

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($reservation);
            $em->flush();

            return $this->redirectToRoute('confirmation', array(
                'id' => $reservation->getId()
            ));
        }

        $formView = $form->createView();

        return $this->render('AppBundle/AppController/reservation/reservation.html.twig', array(
            'form' => $formView,
            'listDatesCompletes' => $listDatesCompletes
        ));
    }


    /**
     * @Route("/confirmation/{id}", name="confirmation")
     *
     * @Security("request.getClientIp() == reservation.getIp() && reservation.isPayer() == false", statusCode=403, message="Une erreur est survenue.")
     *
     * @param Reservation $reservation
     * @param Request $request
     * @param StripePayment $payment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmationAction(Reservation $reservation, Request $request, StripePayment $payment)
    {
        return $this->render('AppBundle/AppController/payment/payment.html.twig', array(
            'reservation' => $reservation,
            'template' => 'AppBundle/AppController/reservation/reservation.html.twig'
        ));
    }

    /**
     * @Route("/merci/{id}", name="merci")
     *
     * @Security("request.getClientIp() == reservation.getIp() && reservation.isPayer() == true", statusCode=403, message="Une erreur est survenue.")
     *
     * @param Request $request
     * @param Reservation $reservation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function merciAction(Reservation $reservation, Request $request)
    {
        return $this->render('AppBundle/AppController/merci/merci.html.twig', array(
            'reservation' => $reservation,
            'template_reservation' => 'AppBundle/AppController/reservation/reservation.html.twig',
            'template_payment' => 'AppBundle/AppController/payment/payment.html.twig'
        ));
    }

    /**
     * @Route("/files/pdf/{id}", name="pdf")
     *
     * @Security("request.getClientIp() == reservation.getIp() && reservation.isPayer() == true", statusCode=403, message="Une erreur est survenue.")
     */
    public function pdfAction(Reservation $reservation)
    {
        $snappy = $this->get("knp_snappy.pdf");

        $snappy->setOption('zoom', 1.2);

        $view = $this->renderView("AppBundle/AppController/pdf/pdf.html.twig", array(
            'reservation' => $reservation
        ));

        return new Response(
            $snappy->getOutputFromHtml($view, array(
                'title' => 'Billeterie - Musée du Louvre - Votre billet',
                'images' => true
            )),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename=votre_billet.pdf'
            )
        );


    }

}