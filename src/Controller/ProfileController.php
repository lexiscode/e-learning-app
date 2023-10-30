<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'title' => 'Profile of ' . $user->getUsername()
        ]);
    }

    #[Route('/users/all', name: 'app_all_users')]
    public function users(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $users = $userRepository->findAll();
        return $this->render('profile/all.html.twig', [
            'users' => $users
        ]);
    }
}