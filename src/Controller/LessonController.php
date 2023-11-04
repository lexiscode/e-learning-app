<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Course;
use App\Entity\Lesson;
use DateTimeImmutable;
use App\Form\LessonFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LessonController extends AbstractController
{
    private $lessonRepository;
    private $courseRepository;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->lessonRepository = $em->getRepository(Lesson::class);
        $this->courseRepository = $em->getRepository(Course::class);
    }

    #[Route('/lesson/{courseId}', name: 'create_lesson')]
    public function createLesson(Request $request, int $courseId): Response
    {
        $this->denyAccessUnlessGranted('ROLE_INSTRUCTOR');

        // Get the course based on $courseId
        $course = $this->courseRepository->find($courseId);

        
        // Check if the user is the instructor of the course, if not, don't grant delete permissions.
        $user = $this->getUser();
        if ($course->getInstructor() !== $user) {
            # Add a flash message and redirect to the main course page
            $this->addFlash('danger', 'You are not authorized to create lessons for this course.');
            return $this->redirectToRoute('create_course');
        }
        

        // Create a new course
        $lesson = new Lesson();
        $lesson->setCourse($course); // Associate the lesson with the course
        
        // Create the form and handle the request
        $form = $this->createForm(LessonFormType::class, $lesson);
        $form->handleRequest($request);

        // If the form is submitted and valid, associate the course with the user and persist it
        if ($form->isSubmitted() && $form->isValid()) {

            // Persist the course id and lesson to the database
            $this->em->persist($lesson);
            $this->em->flush();
    
            // Redirect to the 'create_lesson' route with the 'courseId' parameter
            return $this->redirectToRoute('create_lesson', ['courseId' => $courseId]);
        }

        // Retrieve courses from the database
        $lessons = $this->lessonRepository->findBy(['course' => $courseId]);

        return $this->render('lesson/index.html.twig', [
            'lessonForm' => $form->createView(),
            'lessons' => $lessons
        ]);
    }

    #[Route('/lesson/show/{id}', name: 'view_lesson')]
    public function viewLesson(Request $request, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_INSTRUCTOR');

        // Find the lesson by its ID
        $lesson = $this->lessonRepository->find($id);

        if (!$lesson){
            throw $this->createNotFoundException('Lesson not found');
        }
       
        return $this->render('lesson/show.html.twig', [
            'lesson' => $lesson
        ]);
    }

    #[Route('/lesson/edit/{id}', name: 'edit_lesson')]
    public function editLesson(Request $request, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_INSTRUCTOR');

        // Find the lesson by its ID
        $lesson = $this->lessonRepository->find($id);

        if (!$lesson){
            throw $this->createNotFoundException('Lesson not found');
        }

        // Get the courseId associated with the lesson
        $courseId = $lesson->getCourse()->getId();

        // Create the edit form and handle the request
        $form = $this->createForm(LessonFormType::class, $lesson);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->em->flush();

            $this->addFlash('success', 'Lesson updated successfully');
            return $this->redirectToRoute('create_lesson', ['courseId' => $courseId]);
        }

        return $this->render('lesson/edit.html.twig', [
            'lessonForm' => $form->createView()
        ]);
    }

    #[Route('/lesson/delete/{id}', methods:['GET', 'DELETE'], name: 'delete_lesson')]
    public function delete($id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_INSTRUCTOR');
        
        $lesson = $this->lessonRepository->find($id);

        // Get the courseId associated with the lesson
        $courseId = $lesson->getCourse()->getId();

        $this->em->remove($lesson);
        $this->em->flush();

        return $this->redirectToRoute('create_lesson', ['courseId' => $courseId]);
    }


    // Students Page Routes Below

    #[Route('/student/lessons/{id}', name: 'course_lessons')]
    public function showLessons(Request $request, int $id): Response
    {
        // Find the course by its ID
        $course = $this->courseRepository->find($id);

        if (!$course) {
            throw $this->createNotFoundException('Course not found');
        }

        // Get the lessons associated with the course through the relationship
        $lessons = $course->getLessons();
        //$lessons = $this->lessonRepository->findBy(['course' => $id]);

        return $this->render('student/lesson/index.html.twig', [
            'course' => $course,
            'lessons' => $lessons,
        ]);
    }

    
    #[Route('/student/lesson/{courseId}/{lessonId}', name: 'course_lesson')]
    public function showLesson(Request $request, int $courseId, int $lessonId): Response
    {
        // Find the course by its ID
        $course = $this->courseRepository->find($courseId);

        if (!$course) {
            throw $this->createNotFoundException('Course not found');
        }

        // Get the lesson by its ID associated with the course
        $lesson = $this->lessonRepository->find($lessonId);

        if (!$lesson) {
            throw $this->createNotFoundException('Lesson not found');
        }

        return $this->render('student/lesson/show.html.twig', [
            'course' => $course,
            'lesson' => $lesson,
        ]);
    }

   
}

