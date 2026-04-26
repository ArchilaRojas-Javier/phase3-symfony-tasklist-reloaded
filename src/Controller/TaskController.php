<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\Task1Type;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Enums\TaskStatus;


#[Route('/task')]
#[IsGranted('ROLE_USER')]

final class TaskController extends AbstractController
{
    #[Route(name: 'app_task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
        $user = $this->getUser();
        $tasks = $taskRepository->findBy(['user' => $user]);
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
       
    }

    #[Route('/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $user = $this->getUser();
        $form = $this->createForm(Task1Type::class, $task, [
            'user' => $user,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $task->setUser($user);
            $task = $form->getData();
            $entityManager->persist($task);
            $entityManager->flush();


            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $task = $entityManager->getRepository(Task::class)->find($id);

        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('task_edit-' . $id, $token)) {
        throw $this->createAccessDeniedException('Invalid CSRF token');
        }
        $current = $task->getStatus();
        if ($task) {

            if ($current === TaskStatus::Pending) {
                $task->setStatus(TaskStatus::Encours);
            } elseif ($current === TaskStatus::Encours) {
                $task->setStatus(TaskStatus::Completed);
            } elseif ($current === TaskStatus::Completed){
                $task->setStatus(TaskStatus::Pending);
            }else {
                $task->setStatus(TaskStatus::Completed);
            }
            
            
            $entityManager->flush();
        }
            
return $this->redirect($request->headers->get('referer') ?: $this->generateUrl('app_home'));        
    }

    #[Route('/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
