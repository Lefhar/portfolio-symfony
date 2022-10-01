<?php

namespace App\Controller;

use App\Form\MessageType;
use App\Form\RecaptchaForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Config\RecaptchaConfig;

class SendmailController extends AbstractController
{
    /**
     * @Route("/sendmail", name="app_sendmail")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(MessageType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {

        }
        return $this->render('sendmail/index.html.twig', [
            'form' => $form,
        ]);
    }
}
