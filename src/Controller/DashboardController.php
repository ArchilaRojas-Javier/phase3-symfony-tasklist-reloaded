<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\Task1Type;
use App\Entity\Task;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $form = $this->createForm(Task1Type::class, $task);
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'form' => $form->createView(),
        ]);
    }
}
