<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageUploadHandlerService
{
    private $imageOptimizer;
    private $uploadDirectory;
    private $slugger;

    public function __construct(ImageOptimizerService $imageOptimizer, SluggerInterface $slugger)
    {
        $this->imageOptimizer = $imageOptimizer;
        $this->slugger = $slugger;
    }

    public function setUploadDirectory(string $uploadDirectory): void
    {
        $this->uploadDirectory = $uploadDirectory;
    }

    public function handleUploadFile(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        if (!in_array($file->guessExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
            throw new \Exception('Недопустимый формат файла');
        }

        try {
            $this->imageOptimizer->resizeImage($file, 800, 600);
            $file->move($this->uploadDirectory, $newFilename);

            return $newFilename;
        } catch (\Exception $e) {
            throw new \Exception('Ошибка обработки файла: ' . $e->getMessage());
        }
    }
}
