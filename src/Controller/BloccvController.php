<?php

namespace App\Controller;

use App\Entity\Bloccv;
use App\Form\BloccvType;
use App\Repository\BloccvRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/bloccv")
 */
class BloccvController extends AbstractController
{
    /**
     * @Route("/", name="app_bloccv_index", methods={"GET"})
     */
    public function index(BloccvRepository $bloccvRepository): Response
    {
        return $this->render('admin/bloccv/index.html.twig', [
            'bloccvs' => $bloccvRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_bloccv_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BloccvRepository $bloccvRepository): Response
    {
        $bloccv = new Bloccv();
        $form = $this->createForm(BloccvType::class, $bloccv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bloccv->setDate(new \DateTime());
            $bloccv->setContent($form->get('content')->getData());
            $bloccvRepository->add($bloccv, true);

            return $this->redirectToRoute('app_bloccv_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/bloccv/new.html.twig', [
            'bloccv' => $bloccv,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_bloccv_show", methods={"GET"})
     */
    public function show(Bloccv $bloccv): Response
    {
        return $this->render('admin/bloccv/show.html.twig', [
            'bloccv' => $bloccv,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_bloccv_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Bloccv $bloccv, BloccvRepository $bloccvRepository): Response
    {
        $form = $this->createForm(BloccvType::class, $bloccv);
        $form->handleRequest($request);
dump($request->request->get('content'));
        if ($form->isSubmitted() && $form->isValid()) {
            $bloccv->setContent($form->get('content')->getData());
            $bloccv->setDate(new \DateTime());
            $bloccvRepository->add($bloccv, true);

            return $this->redirectToRoute('app_bloccv_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/bloccv/edit.html.twig', [
            'bloccv' => $bloccv,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_bloccv_delete", methods={"POST"})
     */
    public function delete(Request $request, Bloccv $bloccv, BloccvRepository $bloccvRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bloccv->getId(), $request->request->get('_token'))) {
            $bloccvRepository->remove($bloccv, true);
        }

        return $this->redirectToRoute('app_bloccv_index', [], Response::HTTP_SEE_OTHER);
    }
}
