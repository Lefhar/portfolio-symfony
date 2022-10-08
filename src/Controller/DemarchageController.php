<?php

namespace App\Controller;

use App\Entity\Demarchage;
use App\Form\DemarchageType;
use App\Repository\DemarchageRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/demarchage")
 */
class DemarchageController extends AbstractController
{
    /**
     * @Route("/", name="app_demarchage_index", methods={"GET"})
     */
    public function index(DemarchageRepository $demarchageRepository): Response
    {
        return $this->render('admin/demarchage/index.html.twig', [
            'demarchages' => $demarchageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_demarchage_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DemarchageRepository $demarchageRepository): Response
    {
        $demarchage = new Demarchage();
        $form = $this->createForm(DemarchageType::class, $demarchage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demarchage->setStatus(0);
            $demarchage->setUnsubscribe(0);
            $demarchage->setDate(new DateTime());
            $demarchage->setUsers($this->getUser());
            $demarchageRepository->add($demarchage, true);

            return $this->redirectToRoute('app_demarchage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/demarchage/new.html.twig', [
            'demarchage' => $demarchage,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_demarchage_show", methods={"GET"})
     */
    public function show(Demarchage $demarchage): Response
    {
        return $this->render('admin/demarchage/show.html.twig', [
            'demarchage' => $demarchage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_demarchage_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Demarchage $demarchage, DemarchageRepository $demarchageRepository): Response
    {
        $form = $this->createForm(DemarchageType::class, $demarchage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demarchage->setStatus(0);
            $demarchage->setDate(new DateTime());
            $demarchage->setUnsubscribe(0);
            $demarchage->setUsers($this->getUser());
            $demarchageRepository->add($demarchage, true);

            return $this->redirectToRoute('app_demarchage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/demarchage/edit.html.twig', [
            'demarchage' => $demarchage,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_demarchage_delete", methods={"POST"})
     */
    public function delete(Request $request, Demarchage $demarchage, DemarchageRepository $demarchageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demarchage->getId(), $request->request->get('_token'))) {
            $demarchageRepository->remove($demarchage, true);
        }

        return $this->redirectToRoute('app_demarchage_index', [], Response::HTTP_SEE_OTHER);
    }
}
