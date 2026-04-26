<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class DashboardController extends AbstractController
{
    #[IsGranted('ROLE_USER')]

    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $folder = $entityManager->getRepository('App\Entity\Folder')
        ->findBy(['user' => $user]);
        
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'folders' => $folder
        ]);
    }
}
