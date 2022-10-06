<?php

namespace App\Controller;

use App\Entity\Mesprojets;
use App\Form\MesprojetsType;
use App\Repository\MesprojetsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/mesprojets")
 */
class MesprojetsController extends AbstractController
{
    /**
     * @Route("/", name="app_mesprojets_index", methods={"GET"})
     */
    public function index(MesprojetsRepository $mesprojetsRepository): Response
    {
        return $this->render('admin/mesprojets/index.html.twig', [
            'mesprojets' => $mesprojetsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_mesprojets_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MesprojetsRepository $mesprojetsRepository): Response
    {
        $mesprojet = new Mesprojets();
        $form = $this->createForm(MesprojetsType::class, $mesprojet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mesprojet->setContent($form->get('content')->getData());
            $mesprojet->setDate(new  \DateTime());
            $mesprojet->setUsers($this->getUser());
            $mesprojetsRepository->add($mesprojet, true);

            return $this->redirectToRoute('app_mesprojets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/mesprojets/new.html.twig', [
            'mesprojet' => $mesprojet,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_mesprojets_show", methods={"GET"})
     */
    public function show(Mesprojets $mesprojet): Response
    {
        return $this->render('admin/mesprojets/show.html.twig', [
            'mesprojet' => $mesprojet,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_mesprojets_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Mesprojets $mesprojet, MesprojetsRepository $mesprojetsRepository): Response
    {
        $form = $this->createForm(MesprojetsType::class, $mesprojet);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $mesprojet->setUsers($this->getUser());
            $mesprojet->setDate(new \DateTime());
            $mesprojet->setContent($form->get('content')->getData());

            $mesprojetsRepository->add($mesprojet, true);
           // dump($form->get('content')->getData());
            return $this->redirectToRoute('app_mesprojets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/mesprojets/edit.html.twig', [
            'mesprojet' => $mesprojet,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_mesprojets_delete", methods={"POST"})
     */
    public function delete(Request $request, Mesprojets $mesprojet, MesprojetsRepository $mesprojetsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mesprojet->getId(), $request->request->get('_token'))) {
            $mesprojetsRepository->remove($mesprojet, true);
        }

        return $this->redirectToRoute('app_mesprojets_index', [], Response::HTTP_SEE_OTHER);
    }
}
