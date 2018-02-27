<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Billet;
use AppBundle\Entity\Reservation;
use AppBundle\Form\ReservationType;
use AppBundle\Payment\StripePayment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
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

        return $this->render('reservation/reservation.html.twig', array(
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
        return $this->render('payment/payment.html.twig', array(
            'reservation' => $reservation,
            'template' => 'reservation/reservation.html.twig'
        ));
    }

    /**
     * @Route("/merci/{id}", name="merci")
     *
     * @Security("request.getClientIp() == reservation.getIp()", statusCode=403, message="Une erreur est survenue.")
     *
     * @param Request $request
     * @param Reservation $reservation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function merciAction(Reservation $reservation, Request $request)
    {
        return $this->render('merci/merci.html.twig', array(
            'reservation' => $reservation,
            'template_reservation' => 'reservation/reservation.html.twig',
            'template_payment' => 'payment/payment.html.twig'
        ));
    }

}