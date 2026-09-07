<?php

namespace App\Service;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;

class PublicImageUploader
{
    private const ALLOWED_MIME_TYPES = [
        'image/gif' => 'gif',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/tiff' => 'tiff',
        'image/webp' => 'webp',
    ];

    public function __construct(private readonly KernelInterface $kernel)
    {
    }

    public function upload(UploadedFile $file): string
    {
        $mimeType = $file->getMimeType();

        if (!isset(self::ALLOWED_MIME_TYPES[$mimeType])) {
            throw new InvalidArgumentException('Type de fichier non autorise.');
        }

        $directory = $this->kernel->getProjectDir() . '/public/assets/file';

        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new InvalidArgumentException('Dossier upload indisponible.');
        }

        $filename = bin2hex(random_bytes(16)) . '.' . self::ALLOWED_MIME_TYPES[$mimeType];
        $file->move($directory, $filename);

        return $filename;
    }
}
