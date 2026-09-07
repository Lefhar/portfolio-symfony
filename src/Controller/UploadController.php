<?php

namespace App\Controller;

use App\Service\PublicImageUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    /**
     * @Route("/upload", name="app_upload", methods={"POST"})
     */
    public function upload(Request $request, PublicImageUploader $publicImageUploader): JsonResponse
    {
        if (!$this->isCsrfTokenValid('upload', (string) $request->request->get('_token'))) {
            return $this->json([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Token invalide',
            ], Response::HTTP_FORBIDDEN);
        }

        $fichier = $request->files->get('file');

        if ($fichier === null || !$fichier->isValid()) {
            return $this->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Fichier invalide',
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $name = $publicImageUploader->upload($fichier);
        } catch (\InvalidArgumentException) {
            return $this->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Type de fichier non autorise',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'location' => '/assets/file/' . $name,
        ], Response::HTTP_CREATED);
    }
}
