<?php

namespace App\Controller;

use App\Entity\Cv;
use App\Repository\CvRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\fileExists;

class DownloadController extends AbstractController
{
    /**
     * @Route("/download/cvview/{id}", name="app_download")
     */
    public function index(Cv $cv): Response
    {
        return $this->render('download/index.html.twig', [
            'cv' => $cv
        ]);
    }

    /**
     * @Route("/download/cv/{id}", name="app_download_pdf")
     */
    public function cvPdf(Pdf $knpSnappyPdf,Cv $cv)
    {

        $html = $this->renderView('download/index.html.twig', array(
            'cv' => $cv
        ));
        $knpSnappyPdf->setTimeout(120);
        $knpSnappyPdf->setOption("enable-local-file-access",true); // added this
            $pdf = $knpSnappyPdf->getOutputFromHtml($html, array(

                'orientation' => 'portrait',

                'page-height' => 297,

                'page-width'  => 210,

                'encoding' => 'utf-8',

                'images' => true,

                'dpi' => 72,

                'enable-external-links' => true,

                'enable-internal-links' => true,
                'margin-top'=>0,
                'margin-bottom'=>0,
                'margin-left'=>0,
                'margin-right'=>0,
                'no-background'=>false,
                'background'=>true

            )
        );
            if(file_exists(getcwd().'/assets/file/'.$cv->getTitleFile() . '.pdf'))
            {
                unlink(getcwd().'/assets/file/'.$cv->getTitleFile() . '.pdf');
            }
        file_put_contents(getcwd().'/assets/file/'.$cv->getTitleFile() . '.pdf', $pdf);

        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html, array(

                'orientation' => 'portrait',

                'page-height' => 297,

                'page-width'  => 210,

                'encoding' => 'utf-8',

                'images' => true,

                'dpi' => 72,

                'enable-external-links' => true,

                'enable-internal-links' => true,
                'margin-top'=>0,
                'margin-bottom'=>0,
                'margin-left'=>0,
                'margin-right'=>0,
                'no-background'=>false,
                'background'=>true

            )),
            $cv->getTitleFile() . '.pdf'
        );

    }
}
