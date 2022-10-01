<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendmailController extends AbstractController
{
    /**
     * @Route("/sendmail", name="app_sendmail")
     */
    public function index(): Response
    {
        return $this->render('sendmail/index.html.twig', [
            'controller_name' => 'SendmailController',
        ]);
    }
}
