<?php

namespace App\Controller;

use App\Entity\Blocimage;
use App\Form\BlocimageType;
use App\Repository\BlocimageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blocimage")
 */
class BlocimageController extends AbstractController
{
    /**
     * @Route("/", name="app_blocimage_index", methods={"GET"})
     */
    public function index(BlocimageRepository $blocimageRepository): Response
    {
        return $this->render('blocimage/index.html.twig', [
            'blocimages' => $blocimageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_blocimage_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BlocimageRepository $blocimageRepository): Response
    {
        $blocimage = new Blocimage();
        $form = $this->createForm(BlocimageType::class, $blocimage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blocimage->setContent($form->get('content')->getData());
            $blocimageRepository->add($blocimage, true);

            return $this->redirectToRoute('app_blocimage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blocimage/new.html.twig', [
            'blocimage' => $blocimage,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_blocimage_show", methods={"GET"})
     */
    public function show(Blocimage $blocimage): Response
    {
        return $this->render('blocimage/show.html.twig', [
            'blocimage' => $blocimage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_blocimage_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Blocimage $blocimage, BlocimageRepository $blocimageRepository): Response
    {
        $form = $this->createForm(BlocimageType::class, $blocimage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blocimageRepository->add($blocimage, true);

            return $this->redirectToRoute('app_blocimage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blocimage/edit.html.twig', [
            'blocimage' => $blocimage,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_blocimage_delete", methods={"POST"})
     */
    public function delete(Request $request, Blocimage $blocimage, BlocimageRepository $blocimageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blocimage->getId(), $request->request->get('_token'))) {
            $blocimageRepository->remove($blocimage, true);
        }

        return $this->redirectToRoute('app_blocimage_index', [], Response::HTTP_SEE_OTHER);
    }
}
