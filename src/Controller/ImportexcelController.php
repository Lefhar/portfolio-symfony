<?php

namespace App\Controller;

use App\Entity\Demarchage;
use App\Form\ImportexcelType;
use App\Repository\DemarchageRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Shuchkin\SimpleXLSX;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class ImportexcelController extends AbstractController
{

    /**
     * @Route("/importexcel", name="app_importexcel")
     */
    public function importcsv(Request $request, DemarchageRepository $demarchageRepository): Response
    {
        $xlsx = "";
        $fichier = "";
        $form = $this->createForm(ImportexcelType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            if (!empty($form->get('excel')->getData() && $form->get('excel')->getData() != null)) {
                $fichier = $form->get('excel')->getData();
                dump($fichier->getClientmimeType());
                $aMimeTypes = array("application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                if (in_array($fichier->getClientmimeType(), $aMimeTypes)) {
                    if ($fichier->move('assets/file/excel/', $fichier->getClientOriginalName())) {
//                    $mesprojet->setImage($fichier->getClientOriginalName());
                        $xlsx = SimpleXLSX::parse(getcwd() . '/assets/file/excel/' . $fichier->getClientOriginalName())->rows();
                        $fichier = $fichier->getClientOriginalName();
                       // dump($xlsx);
                    }
                }

            }
        }
        $colonne = $demarchageRepository->show_Columns();

        unset($colonne[0]);
        unset($colonne[1]);
        unset($colonne[4]);
        unset($colonne[5]);
        sort($colonne);
        foreach ($colonne as $row) {
            $table[] = $row["Field"];
        }
        //asort($table);
        //  $xlsx = SimpleXLSX::parse(getcwd() . '/assets/file/excel/'. $fichier->getClientOriginalName());
        return $this->render('admin/importexcel/form.html.twig', [

            'excel' => $xlsx,
            'form' => $form->createView(),
            'demarche' => $table,
            'fichier' => $fichier,
        ]);
    }

    /**
     * @Route("/importexceldatabase", name="app_importexcel_database")
     */
    public function importexceldatabase(Request $request, EntityManagerInterface $entityManager)
    {
        ini_set('max_execution_time', 0);
        if ($request->get('fichier')) {
            $xlsx = SimpleXLSX::parse(getcwd() . '/assets/file/excel/' . $request->get('fichier'))->rows();
            unset($xlsx[0]);

            foreach ($xlsx as $key => $colonne) {
                if (!empty($colonne[$request->get('de')[2]])) {


                    $demarchage = new Demarchage();
                    if (!empty($colonne[$request->get('de')[0]])) {
                        $demarchage->setAdresse($colonne[$request->get('de')[0]]);
                    }
                    if (!empty($colonne[$request->get('de')[1]])) {
                        $demarchage->setCodepostal($colonne[$request->get('de')[1]]);
                    }
                    if (!empty($colonne[$request->get('de')[2]])) {
                    $demarchage->setEmail($colonne[$request->get('de')[2]]);
                }
                    if (!empty($colonne[$request->get('de')[3]])) {
                        $demarchage->setMobile($colonne[$request->get('de')[3]]);
                    }
                    if (!empty($colonne[$request->get('de')[4]])) {
                        $demarchage->setName($colonne[$request->get('de')[4]]);
                    }
                    if (!empty($colonne[$request->get('de')[5]])) {
                        $demarchage->setTelephone($colonne[$request->get('de')[5]]);
                    }
                    if (!empty($colonne[$request->get('de')[6]])) {
                        $demarchage->setVille($colonne[$request->get('de')[6]]);
                    }
                    $demarchage->setUsers($this->getUser());
                    $demarchage->setDate(new \DateTime());
                    $demarchage->setStatus(0);
                    $entityManager->persist($demarchage);
                    $entityManager->flush();
                }

            }
            unlink(getcwd() . '/assets/file/excel/' . $request->get('fichier'));
            return $this->redirectToRoute('app_importexcel');
        }

    }

}
