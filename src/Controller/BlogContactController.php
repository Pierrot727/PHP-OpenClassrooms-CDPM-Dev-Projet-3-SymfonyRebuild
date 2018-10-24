<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BlogContactController extends AbstractController
{
    /**
     * @Route("/contactez-moi", name="contact")
     * 
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {



        return $this->render('contact/index.html.twig', [
            'controller_name' => 'BlogContactController',
        ]);
    }
}
