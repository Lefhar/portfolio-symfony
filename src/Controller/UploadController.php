<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    /**
     * @Route("/upload", name="app_upload")
     */
    public function upload(): JsonResponse
    {

        $fichier = $_FILES['file'];
        //  dd($fichier);
        $aMimeTypes = array("image/gif", "image/jpeg", "image/jpg", "image/png", "image/x-png", "image/tiff", "image/webp");
        if (in_array($fichier['type'], $aMimeTypes)) {
            if(file_exists('assets/file/'.$fichier['name'])){
                $name = date('Ymdis').'-'.$fichier['name'];

            }else{
                $name = $fichier['name'];
            }
            if (move_uploaded_file($fichier['tmp_name'],'assets/file/'.$name)) {

                return $this->json([
                    'location' => '/assets/file/'.$name,

                ], 201
                );
            }
        }else{
            return $this->json([
                'status' => 400,
                'message' => 'error file not accept'
            ], 400
            );
        }
        return $this->json([
            'status' => 400,
            'message' => 'error file not accept'
        ], 400
        );
    }
}
