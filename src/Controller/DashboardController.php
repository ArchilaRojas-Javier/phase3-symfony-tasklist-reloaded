<?php

namespace App\Controller;

use App\Enums\TaskStatus;
use App\Repository\FolderRepository;
use App\Repository\PriorityRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class DashboardController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/', name: 'app_home')]
    public function index(Request $request, FolderRepository $folderRepository, TaskRepository $taskRepository, PriorityRepository $priorityRepository): Response {
        
        $user = $this->getUser();

        $folders = $folderRepository->findBy(['user' => $user]);
        $priorities = $priorityRepository->findAll();

        $taskPinned = $taskRepository->findBy(['user' => $user, 'isPinned' => true]);

        $selectedFolderId = $request->query->get('folder');
        $statusString = $request->query->get('status');
        $priorityId = $request->query->get('priority');

        $qb = $taskRepository->createQueryBuilder('t');
        $qb->where('t.user = :user')
           ->andWhere('t.isPinned = :pinned')
           ->setParameter('user', $user)
           ->setParameter('pinned', false);

        if ($selectedFolderId) {
            $selectedFolder = $folderRepository->findOneBy(['id' => $selectedFolderId, 'user' => $user]);
            if ($selectedFolder) {
                $qb->andWhere('t.folder = :folder')
                   ->setParameter('folder', $selectedFolder);
            }
        }

        if ($statusString) {
            $enumStatus = TaskStatus::tryFrom($statusString);
            if ($enumStatus) {
                $qb->andWhere('t.status = :status')
                   ->setParameter('status', $enumStatus);
            }
        }

        if ($priorityId) {
            $priority = $priorityRepository->find($priorityId);
            if ($priority) {
                $qb->andWhere('t.priority = :priority')
                   ->setParameter('priority', $priority);
            }
        }
        
        $tasks = $qb->getQuery()->getResult();

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'folders' => $folders,
            'tasks' => $tasks,          
            'taskPinned' => $taskPinned, 
            'priorities' => $priorities,
        ]);
    }
}