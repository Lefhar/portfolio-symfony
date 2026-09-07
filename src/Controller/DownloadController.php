<?php

namespace App\Controller;

use App\Entity\Cv;
use App\Service\CvPdfGenerator;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DownloadController extends AbstractController
{
    /**
     * @Route("/download/cvview/{id}", name="app_download", methods={"GET"})
     */
    public function index(Cv $cv): Response
    {
        return $this->render('download/index.html.twig', [
            'cv' => $cv
        ]);
    }

    /**
     * @Route("/download/cv/{id}", name="app_download_pdf", methods={"GET"})
     */
    public function cvPdf(CvPdfGenerator $cvPdfGenerator, Cv $cv): PdfResponse
    {
        return new PdfResponse(
            $cvPdfGenerator->refresh($cv),
            $cvPdfGenerator->filename($cv),
            'application/pdf',
            'attachment',
            Response::HTTP_OK,
            [
                'Cache-Control' => 'no-store, private',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]
        );
    }
}
