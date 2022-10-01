<?php

namespace App\Controller;

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
        $cv = $cvRepository->findOneBy(['IsActive'=>1,'users'=>$this->getUser()]);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'mesproject'=>$mesprojetsRepository->findAll(),
            'cv'=>$cv,
        ]);
    }
}
