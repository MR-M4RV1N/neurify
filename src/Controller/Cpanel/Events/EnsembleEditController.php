<?php

namespace App\Controller\Cpanel\Events;

use App\Entity\EnsembleParticipant;
use App\Entity\Event;
use App\Entity\Ensemble;
use App\Entity\User;
use App\Form\EnsembleType;
use App\Repository\EnsembleParticipantRepository;
use App\Repository\EnsembleRepository;
use App\Repository\EventRepository;
use App\Service\EnsembleParticipantService;
use App\Service\ImageUploadHandlerService;
use App\Service\PaginatorService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/cpanel/ensemble")
 */
class EnsembleEditController extends AbstractController
{
    private $participantService;
    private $paginatorService;
    private $imageUploadHandler;

    public function __construct(
        EnsembleParticipantService $participantService,
        PaginatorService $paginatorService,
        ImageUploadHandlerService $imageUploadHandler
    ) {
        $this->participantService = $participantService;
        $this->paginatorService = $paginatorService;
        $this->imageUploadHandler = $imageUploadHandler;
    }

    /**
     * @Route("/new", name="app_ensemble_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine, Security $security): Response
    {
        $entityManager = $doctrine->getManager();
        $ensemble = new Ensemble();

        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Вы должны быть авторизованы для создания события.');
        }
        $ensemble->setAuthor($user);
        $ensemble->setCreatedAt(new \DateTimeImmutable());
        $ensemble->setAiGenerated(false);
        $ensemble->setDraft(false);

        $form = $this->createForm(EnsembleType::class, $ensemble, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ensemble);

            // Проверка и добавление участника ансамбля
            if (!$this->participantService->addParticipant($ensemble, $user)) {
                $this->addFlash('danger', 'Вы уже являетесь участником этого ансамбля.');
                return $this->redirectToRoute('app_ensemble_list', [], Response::HTTP_SEE_OTHER);
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_account_ensembles', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cpanel/events/ensemble/new.html.twig', [
            'ensemble' => $ensemble,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", requirements={"id"="\d+"}, name="app_ensemble_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Ensemble $ensemble, EnsembleRepository $ensembleRepository, SluggerInterface $slugger, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(EnsembleType::class, $ensemble, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    // Перемещаем новый файл в директорию для загрузок
                    $imageFile->move(
                        $this->getParameter('ensemble_image_upload_directory'),
                        $newFilename
                    );

                    $originalImage = $ensemble->getImage();
                    // Если у нас есть старый файл изображения, удаляем его, кроме дефолтного
                    if ($originalImage && $originalImage !== 'default-ensemble.jpg' && $originalImage !== 'default.jpg') {
                        $oldFilePath = $this->getParameter('ensemble_image_upload_directory') . '/' . $originalImage;
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }

                    // Обновляем сущность новым именем файла изображения
                    $ensemble->setImage($newFilename);
                } catch (FileException $e) {
                    $this->get('logger')->error('Failed to upload file: ' . $e->getMessage());
                    $this->addFlash('error', $translator->trans('File upload failed. Please try again.'));
                }
            }

            if ($ensemble->isAiGenerated()) {
                $ensemble->setSelected(false);
            }

            $ensembleRepository->add($ensemble, true);

            return $this->redirectToRoute('app_account_ensembles', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cpanel/events/ensemble/edit.html.twig', [
            'ensemble' => $ensemble,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id"="\d+"}, name="app_ensemble_delete", methods={"POST"})
     */
    public function delete(ManagerRegistry $doctrine, Request $request, Ensemble $ensemble, EnsembleRepository $ensembleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ensemble->getId(), $request->request->get('_token'))) {
            // В таблице EnsembleParticipant удаляем всех участников
            $entityManager = $doctrine->getManager();
            $participants = $entityManager->getRepository(EnsembleParticipant::class)->findBy(['ensemble' => $ensemble->getId()]);
            foreach ($participants as $participant) {
                $entityManager->remove($participant);
            }
            // Удаление самого ансамбля
            $ensembleRepository->remove($ensemble, true);
        }

        return $this->redirectToRoute('app_account_ensembles', [], Response::HTTP_SEE_OTHER);
    }
}
