<?php

namespace App\Controller;

use App\Form\MessageType;
use App\Form\RecaptchaForm;
use App\Repository\CvRepository;
use App\Repository\MesprojetsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(MesprojetsRepository $mesprojetsRepository, CvRepository $cvRepository): Response
    {
        $cv = $cvRepository->findOneBy(['IsActive'=>1]);
        $form = $this->createForm(MessageType::class);
        return $this->render('home/index.html.twig', [

            'mesproject'=>$mesprojetsRepository->findAll(),
            'cv'=>$cv,
            'form'=>$form->createView(),
        ]);
    }
}
