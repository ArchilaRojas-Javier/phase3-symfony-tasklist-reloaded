<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\FolderRepository;
use App\Repository\TaskRepository;

final class DashboardController extends AbstractController
{
    #[IsGranted('ROLE_USER')]

    #[Route('/', name: 'app_home')]
    public function index(Request $request, FolderRepository $folderRepository, TaskRepository $taskRepository): Response
    {
        $user = $this->getUser();
        
        $folders = $folderRepository->findBy(['user' => $user]);

        $taskPinned = $taskRepository->findBy(['user' => $user, 'isPinned' => true]);

        $selectedFolderId = $request->query->get('folder');
        
        $selectedFolder = null;
        
        $tasks = [];

        if ($selectedFolderId) {
        
            $selectedFolder = $folderRepository->findOneBy(['id' => $selectedFolderId, 'user' => $user]);
            
            if ($selectedFolder) {
                
                $tasks = $taskRepository->findBy(['folder' => $selectedFolder, 'user' => $user]);
           
            } else {
                
                $tasks = $taskRepository->findBy(['user' => $user]);
            }
        
        }else {
            
            $tasks = $taskRepository->findBy(['user' => $user]);
        }
        
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'folders' => $folders,
            'tasks' => $tasks,
            'selectedFolder' => $selectedFolder,
            'taskPinned' => $taskPinned
        ]);
    }
}
