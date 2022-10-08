<?php

namespace App\Controller;

use App\Repository\DemarchageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UnsubscribeController extends AbstractController
{
    /**
     * @Route("/unsubscribe/{slug}", name="app_unsubscribe")
     */
    public function index($slug,DemarchageRepository $demarchageRepository,EntityManagerInterface $entityManager): Response
    {
        $demarchage = $demarchageRepository->findOneBy(['email'=>$slug]);
        if($demarchage)
        {
            $demarchage->setUnsubscribe(1);
            $entityManager->flush();
            $this->addFlash('message', 'Votre adresse email a bien été retiré de notre liste');
        }else{
            $this->addFlash('message', 'Erreur adresse email introuvable');
        }

        return $this->render('unsubscribe/index.html.twig', [

        ]);
    }
}
