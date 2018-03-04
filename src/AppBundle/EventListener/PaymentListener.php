<?php

namespace AppBundle\EventListener;

use AppBundle\Mailer\SwiftMailer;
use AppBundle\Payment\StripePayment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;

class PaymentListener
{
    private $payment;
    private $em;
    /**
     * @var Router
     */
    private $router;
    private $swiftmailer;

    public function __construct(StripePayment $payment, EntityManagerInterface $entityManager, RouterInterface $router, SwiftMailer $swiftMailer)
    {
        $this->payment = $payment;
        $this->em = $entityManager;
        $this->router = $router;
        $this->swiftmailer = $swiftMailer;
    }

    public function processingPayment(GetResponseEvent $responseEvent)
    {
        $request = $responseEvent->getRequest();
        if($request->get('_route') == 'confirmation' && $request->getMethod() == 'POST') {
            $id = $request->get('id');
            $reservation = $this->em->getRepository("AppBundle:Reservation")->findOneBy(['id' => $id]);
            if (!$reservation->isPayer()) {
                $emailStripe = $request->request->get("stripeEmail");
                $stripeToken = $request->request->get("stripeToken");
                $this->payment->payed($emailStripe, $stripeToken, $reservation);

                $responseEvent->setResponse(new RedirectResponse($this->router->generate('merci', ['id' => $id])));

                /*
                 * Email
                 */
                $this->swiftmailer->sendEmail($reservation);
            }
        }
    }
}