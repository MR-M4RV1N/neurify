<?php

namespace App\Service;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\FormError;
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

    public function setUploadDirectory(string $uploadDirectory)
    {
        $this->uploadDirectory = $uploadDirectory;
    }
    public function handleUpload(Form $form, string $fieldName, string $defaultImage = 'default.jpg'): string
    {
        /** @var UploadedFile $image */
        $image = $form->get($fieldName)->getData();
        if ($image) {
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

            if (!in_array($image->guessExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                $form->get($fieldName)->addError(new FormError('Недопустимый формат файла.'));
                return $defaultImage;
            }

            try {
                $this->imageOptimizer->resizeImage($image, 800, 600);
                $image->move($this->uploadDirectory, $newFilename);
                return $newFilename;
            } catch (\Exception $e) {
                $form->get($fieldName)->addError(new FormError('Ошибка загрузки файла: ' . $e->getMessage()));
                return $defaultImage;
            }
        }

        return $defaultImage;
    }
}
