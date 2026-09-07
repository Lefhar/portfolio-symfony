<?php

namespace App\Service;

use App\Entity\Cv;
use Knp\Snappy\Pdf;
use RuntimeException;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

class CvPdfGenerator
{
    private const PDF_OPTIONS = [
        'orientation' => 'portrait',
        'page-height' => 297,
        'page-width' => 210,
        'encoding' => 'utf-8',
        'images' => true,
        'dpi' => 72,
        'enable-external-links' => true,
        'enable-internal-links' => true,
        'load-error-handling' => 'ignore',
        'load-media-error-handling' => 'ignore',
        'margin-top' => 0,
        'margin-bottom' => 0,
        'margin-left' => 0,
        'margin-right' => 0,
        'no-background' => false,
        'background' => true,
    ];

    public function __construct(
        private readonly Environment $twig,
        private readonly Pdf $pdf,
        private readonly KernelInterface $kernel
    ) {
    }

    public function filename(Cv $cv): string
    {
        return $cv->getTitleFile() . '.pdf';
    }

    public function path(Cv $cv): string
    {
        return $this->kernel->getProjectDir() . '/public/assets/file/' . $this->filename($cv);
    }

    public function refresh(Cv $cv): string
    {
        $output = $this->generate($cv);
        $this->writeCache($cv, $output);

        return $output;
    }

    public function generate(Cv $cv): string
    {
        $html = $this->twig->render('download/index.html.twig', [
            'cv' => $cv,
            'cv_files_url' => $this->publicFilesUri(),
        ]);

        $this->pdf->setTimeout(120);
        $this->pdf->setOption('enable-local-file-access', true);

        return $this->pdf->getOutputFromHtml($html, self::PDF_OPTIONS);
    }

    private function writeCache(Cv $cv, string $output): void
    {
        $path = $this->path($cv);
        $directory = dirname($path);

        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('Unable to create directory "%s".', $directory));
        }

        $temporaryPath = tempnam($directory, 'cv_');

        if ($temporaryPath === false) {
            throw new RuntimeException(sprintf('Unable to create a temporary PDF in "%s".', $directory));
        }

        if (file_put_contents($temporaryPath, $output, LOCK_EX) === false) {
            @unlink($temporaryPath);
            throw new RuntimeException(sprintf('Unable to write temporary PDF "%s".', $temporaryPath));
        }

        if (!@rename($temporaryPath, $path)) {
            @unlink($path);

            if (!@rename($temporaryPath, $path)) {
                @unlink($temporaryPath);
                throw new RuntimeException(sprintf('Unable to replace PDF cache "%s".', $path));
            }
        }
    }

    private function publicFilesUri(): string
    {
        return $this->publicUri() . '/assets/file';
    }

    private function publicUri(): string
    {
        $path = str_replace('\\', '/', $this->kernel->getProjectDir() . '/public');

        if (str_starts_with($path, '/')) {
            return 'file://' . $path;
        }

        return 'file:///' . $path;
    }
}
