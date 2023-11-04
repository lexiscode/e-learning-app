<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Course;
use DateTimeImmutable;
use App\Entity\Enrollment;
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
    private $em;
    private $courseRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->courseRepository = $em->getRepository(Course::class);
    }

    #[Route('/course', name: 'create_course')]
    public function createCourse(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_INSTRUCTOR');

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
    
            return $this->redirectToRoute('create_course');
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
        $this->denyAccessUnlessGranted('ROLE_INSTRUCTOR');

        // Find the course by its ID
        $course = $this->courseRepository->find($id);

        if (!$course){
            throw $this->createNotFoundException('Course not found');
        }

        // Check if the user is the instructor of the course, if not, don't grant edit permissions.
        $user = $this->getUser();
        if ($course->getInstructor() !== $user) {
            // Add a flash message and redirect to the main course page
            $this->addFlash('danger', 'You are not authorized to edit this course.');
            return $this->redirectToRoute('create_course');
        }

        // Create the edit form and handle the request
        $form = $this->createForm(CourseFormType::class, $course);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->em->flush();

            return $this->redirectToRoute('create_course');
        }

        return $this->render('course/edit.html.twig', [
            'courseForm' => $form->createView()
        ]);
    }

    #[Route('/course/delete/{id}', methods:['GET', 'DELETE'], name: 'delete_course')]
    public function delete($id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_INSTRUCTOR');
        
        $course = $this->courseRepository->find($id);

        // Check if the user is the instructor of the course, if not, don't grant delete permissions.
        $user = $this->getUser();
        if ($course->getInstructor() !== $user) {
            // Add a flash message and redirect to the main course page
            $this->addFlash('danger', 'You are not authorized to delete this course.');
            return $this->redirectToRoute('create_course');
        }

        /*
        // The code below is a "manual" way of deleting the foreign constraints of enrollment table data, from course table
        // but this has been done indirectly already in Course entity by adding "orphanRemoval: true, cascade: ["remove"]"
        // beside the $enrollments property

        $enrollments = $course->getEnrollments();
        foreach ($enrollments as $enrollment) {
            $this->em->remove($enrollment);
        }
        
        */

        $this->em->remove($course);
        $this->em->flush();

        return $this->redirectToRoute('create_course');
    }

    // Students Pages Routes Below:

    #[Route('/student/course', name: 'all_courses')]
    public function allCourse(Request $request): Response
    {
        // Retrieve courses from the database
        $courses = $this->courseRepository->findAll();

        // Fetch the currently logged-in user
        $user = $this->getUser();

        return $this->render('student/course/index.html.twig', [
            'courses' => $courses,
            'user' => $user,
        ]);
    }


    #[Route('/student/course/{courseId}', name: 'show_course')]
    public function showCourse($courseId, Request $request): Response
    {
        // Find the course by its ID
        $course = $this->courseRepository->find($courseId);

        // Get the lessons associated with the course through the relationship
        $lessons = $course->getLessons();
        //$lessons = $this->lessonRepository->findBy(['course' => $id]);

        if (!$course){
            throw $this->createNotFoundException('Course not found');
        }

        return $this->render('student/course/show.html.twig', [
            'course' => $course,
            'lessons' => $lessons
        ]);
    }

}

