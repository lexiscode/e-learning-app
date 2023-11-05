<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\ProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class ProfileController extends AbstractController
{
    private $em;
  
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var User $user */
        $user = $this->getUser();
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'image_path' => $user->getProfile()?->getImagePath(),
            'title' => 'Profile of <br>' . $user->getUsername()
        ]);
    }

    #[Route('/profile/edit/{id}', name: 'app_profile_edit')]
    public function edit(Request $request, Profile $profile): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(ProfileFormType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
    
            $newProfile = $form->getData();
            $image = $form->get('image')->getData();

            if ($form->isSubmitted() && $form->isValid()) {
                $newProfile = $form->getData();
                $image = $form->get('image')->getData();
    
                if ($image) {
                    $newFileName = uniqid() . '.' . $image->guessExtension();
    
                    try {
                        $image->move(
                            $this->getParameter('kernel.project_dir') . '/public/uploads/profile',
                            $newFileName
                        );
                    } catch (FileException $e) {
                        return new Response($e->getMessage());
                    }
               
                    $newProfile->setUser($this->getUser());
                    $newProfile->setImagePath('/uploads/profile/' . $newFileName);
                }
            }

            $this->em->persist($newProfile);
            $this->em->flush();

            return $this->redirectToRoute('app_profile');
        }

        /** @var User $user */
        $user = $this->getUser();
        return $this->render('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'title' => 'Profile of <br>' . $user->getUsername()
        ]);
    }

    #[Route('/profile/new', name: 'app_profile_new')]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $profile = new Profile();

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(ProfileFormType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newProfile = $form->getData();
            $image = $form->get('image')->getData();

            if ($image) {
                $newFileName = uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/profile',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
             
                $newProfile->setUser($this->getUser());
                $newProfile->setImagePath('/uploads/profile/' . $newFileName);
            }

            $this->em->persist($newProfile);
            $this->em->flush();

            return $this->redirectToRoute('app_profile');
        }

        /** @var User $user */
        $user = $this->getUser();
        return $this->render('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'title' => 'Profile of <br>' . $user->getUsername()
        ]);
    }
}

