<?php

namespace App\Controller;

use App\Entity\Cv;
use App\Repository\CvRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DownloadController extends AbstractController
{
    /**
     * @Route("/download", name="app_download")
     */
    public function index(): Response
    {
        return $this->render('download/index.html.twig', [
            'controller_name' => 'DownloadController',
        ]);
    }

    /**
     * @Route("/download/cv/{id}", name="app_download")
     */
    public function facturePdf(Pdf $knpSnappyPdf,Cv $cv)
    {

        $html = $this->renderView('download/index.html.twig', array(
            'cv' => $cv
        ));
        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html),
            $cv->getTitleFile() . '.pdf'
        );
    }
}
