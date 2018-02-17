<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Billet;
use AppBundle\Entity\Reservation;
use AppBundle\Form\ReservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
        $reservation->addBillet(new Billet())
            ->setIp($request->getClientIp());

        $listDatesCompletes = $em->getRepository('AppBundle:Reservation')->getDateFull();

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($reservation);
            $em->flush();

            return new Response('OK!');
        }

        $formView = $form->createView();

        return $this->render('reservation/reservation.html.twig', array(
            'form' => $formView,
            'listDatesCompletes' => $listDatesCompletes
        ));

    }

}