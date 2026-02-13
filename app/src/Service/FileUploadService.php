<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploadService
{
    public function __construct(
        private string $uploadDir,
        private SluggerInterface $slugger
    ) {}

    /**
     * Upload a file to the server
     * @param UploadedFile $file
     * @param string $directory Subdirectory inside uploadDir (e.g., 'posts')
     * @return string The filename stored
     */
    public function uploadFile(UploadedFile $file, string $directory = 'posts'): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $uploadPath = $this->uploadDir . '/' . $directory;
        $file->move($uploadPath, $newFilename);

        return $newFilename;
    }

    /**
     * Delete a file from the server
     * @param string $filename
     * @param string $directory
     */
    public function deleteFile(string $filename, string $directory = 'posts'): void
    {
        $filePath = $this->uploadDir . '/' . $directory . '/' . $filename;
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * Get the upload directory path
     */
    public function getUploadDir(): string
    {
        return $this->uploadDir;
    }
}
