<?php

namespace App\Controller;

use App\Entity\Demarchage;
use App\Form\ImportexcelType;
use App\Repository\DemarchageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Shuchkin\SimpleXLSX;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class ImportexcelController extends AbstractController
{
    public function __construct(private readonly KernelInterface $kernel)
    {
    }

    /**
     * @Route("/importexcel", name="app_importexcel", methods={"GET", "POST"})
     */
    public function importcsv(Request $request, DemarchageRepository $demarchageRepository): Response
    {
        $xlsx = "";
        $fichier = "";
        $form = $this->createForm(ImportexcelType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form->get('excel')->getData();
            if ($uploadedFile instanceof UploadedFile && $this->isAllowedSpreadsheet($uploadedFile)) {
                $fichier = bin2hex(random_bytes(16)) . '.xlsx';
                $path = $this->excelDirectory() . '/' . $fichier;
                $uploadedFile->move($this->excelDirectory(), $fichier);

                $reader = SimpleXLSX::parse($path);
                if ($reader === false) {
                    @unlink($path);
                    $fichier = "";
                } else {
                    $xlsx = $reader->rows();
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

        return $this->render('admin/importexcel/form.html.twig', [

            'excel' => $xlsx,
            'form' => $form->createView(),
            'demarche' => $table,
            'fichier' => $fichier,
        ]);
    }

    /**
     * @Route("/importexceldatabase", name="app_importexcel_database", methods={"POST"})
     */
    public function importexceldatabase(Request $request, EntityManagerInterface $entityManager): Response
    {
        ini_set('max_execution_time', 0);

        if (!$this->isCsrfTokenValid('import_excel', (string) $request->request->get('_token'))) {
            return $this->redirectToRoute('app_importexcel');
        }

        $fichier = (string) $request->request->get('fichier', '');

        if ($this->isSafeStoredSpreadsheetName($fichier)) {
            $path = $this->excelDirectory() . '/' . $fichier;
            $reader = is_file($path) ? SimpleXLSX::parse($path) : false;

            if ($reader === false) {
                return $this->redirectToRoute('app_importexcel');
            }

            $xlsx = $reader->rows();
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
            @unlink($path);
            return $this->redirectToRoute('app_importexcel');
        }

        return $this->redirectToRoute('app_importexcel');
    }

    private function isAllowedSpreadsheet(UploadedFile $file): bool
    {
        return $file->isValid()
            && strtolower($file->getClientOriginalExtension()) === 'xlsx'
            && in_array($file->getMimeType(), [
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/octet-stream',
                'application/zip',
            ], true);
    }

    private function isSafeStoredSpreadsheetName(string $filename): bool
    {
        return preg_match('/^[a-f0-9]{32}\.xlsx$/', $filename) === 1;
    }

    private function excelDirectory(): string
    {
        $directory = $this->kernel->getProjectDir() . '/var/import_excel';

        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new \RuntimeException(sprintf('Unable to create import directory "%s".', $directory));
        }

        return $directory;
    }
}
