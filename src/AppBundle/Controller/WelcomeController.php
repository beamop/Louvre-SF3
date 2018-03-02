<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class WelcomeController extends Controller
{
    /**
     * @Route("/", name="welcome")
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('reservation');
    }
}
