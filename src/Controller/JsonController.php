<?php

namespace App\Controller;

use App\Repository\MesprojetsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/json")
 */
class JsonController extends AbstractController
{
    /**
     * @Route("/mesprojects", name="app_json")
     */
    public function index(MesprojetsRepository $mesprojetsRepository): Response
    {
      return $this->json($mesprojetsRepository->findAll(), 200, [], ['groups' => 'show_projet']);
    }
}
