<?php

namespace App\Controller;

use App\Entity\Folder;
use App\Form\FolderType;
use App\Repository\FolderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/folder')]
#[IsGranted('ROLE_USER')]
final class FolderController extends AbstractController
{
    #[Route(name: 'app_folder_index', methods: ['GET'])]
    public function index(FolderRepository $folderRepository): Response
    {
        $user = $this->getUser();
        $folders = $folderRepository->findBy(['user' => $user]);
        return $this->render('folder/index.html.twig', [
            'folders' => $folders,
        ]);
    }

    #[Route('/new', name: 'app_folder_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $folder = new Folder();
        $user = $this->getUser();
        $form = $this->createForm(FolderType::class, $folder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $folder->setUser($user);
            $entityManager->persist($folder);
            $entityManager->flush();
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('folder/new.html.twig', [

            'folder' => $folder,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_folder_show', methods: ['GET'])]
    public function show(Folder $folder): Response
    {
        if ($folder->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Acces non authorisé.');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('folder/show.html.twig', [
            'folder' => $folder,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_folder_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Folder $folder, EntityManagerInterface $entityManager): Response
    {
        if ($folder->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Acces non authorisé.');
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(FolderType::class, $folder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_folder_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('folder/edit.html.twig', [
            'folder' => $folder,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_folder_delete', methods: ['POST'])]
    public function delete(Request $request, Folder $folder, EntityManagerInterface $entityManager): Response
    {
        if ($folder->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Acces non authorisé.');
            return $this->redirectToRoute('app_home');
        }
        if ($this->isCsrfTokenValid('delete' . $folder->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($folder);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_folder_index', [], Response::HTTP_SEE_OTHER);
    }
}
