<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Course;
use DateTimeImmutable;
use App\Form\CourseFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CourseController extends AbstractController
{
    private $courseRepository;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->courseRepository = $em->getRepository(Course::class);
    }

    #[Route('/course', name: 'app_course')]
    public function register(Request $request): Response
    {
        // Create a new course
        $course = new Course();
        
        // Create the form and handle the request
        $form = $this->createForm(CourseFormType::class, $course);
        $form->handleRequest($request);

        // If the form is submitted and valid, associate the course with the user and persist it
        if ($form->isSubmitted() && $form->isValid()) {

            // Fetch the currently logged-in user
            $user = $this->getUser();

            if ($user instanceof User) {
                // Associate the course with the user
                $user->addCourse($course);
    
                // Persist the course and user to the database
                $this->em->persist($course);
                $this->em->persist($user);
                $this->em->flush();
            } 
    
            return $this->redirectToRoute('app_course');
        }

        // Retrieve courses from the database
        $courses = $this->courseRepository->findAll();

        return $this->render('course/index.html.twig', [
            'courseForm' => $form->createView(),
            'courses' => $courses
        ]);
    }

    #[Route('/course/edit/{id}', name: 'edit_course')]
    public function edit($id, Request $request): Response
    {
        $course = $this->courseRepository->find($id);

        $form = $this->createForm(CourseFormType::class, $course);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->em->flush();

            return $this->redirectToRoute('app_course');
        }

        return $this->render('course/show.html.twig', [
            'course' => $course,
            'courseForm' => $form->createView()
        ]);
    }

    #[Route('/course/delete/{id}', methods:['GET', 'DELETE'], name: 'delete_course')]
    public function delete($id): Response
    {
        $course = $this->courseRepository->find($id);

        $this->em->remove($course);
        $this->em->flush();

        return $this->redirectToRoute('app_course');
    }
}

