<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Form\RecaptchaForm;
use App\Repository\CvRepository;
use App\Repository\MesprojetsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(MesprojetsRepository $mesprojetsRepository, CvRepository $cvRepository,Request $request): Response
    {
        $cv = $cvRepository->findOneBy(['IsActive'=>1]);
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        return $this->render('home/index.html.twig', [

            'mesproject'=>$mesprojetsRepository->findAll(),
            'cv'=>$cv,
            'form'=>$form->createView(),
        ]);
    }
}
