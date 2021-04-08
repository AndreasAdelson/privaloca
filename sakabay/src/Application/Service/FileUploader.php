<?php

namespace App\Application\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Psr\Log\LoggerInterface;

class FileUploader
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function upload($uploadDir, $file, $filename)
    {
        try {
            $file->move($uploadDir, $filename);
        } catch (FileException $e) {
            $this->logger->error('failed to upload file: ' . $e->getMessage());
            throw new FileException('Failed to upload file');
        }
    }

    public function setFileName($file): ?string
    {
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFileName);
        return $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
    }

    public function deleteOldFile($uploadDir, $filename)
    {
        try {
            unlink($uploadDir . '/' . $filename);
        } catch (FileException $e) {
            throw new FileException('Failed to delete file');
        }
    }
}
