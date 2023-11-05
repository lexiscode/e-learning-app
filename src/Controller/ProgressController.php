<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Entity\Progress;
use App\Entity\Enrollment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgressController extends AbstractController
{
    private $em;
    private $courseRepository;
    private $lessonRepository;
    private $enrollmentRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->courseRepository = $em->getRepository(Course::class);
        $this->lessonRepository = $em->getRepository(Lesson::class);
        $this->enrollmentRepository = $em->getRepository(Enrollment::class);
    }
    
    #[Route('/student/lesson/complete/{courseId}/{lessonId}', name: 'lesson_complete')]
    public function completeCourse($courseId, $lessonId, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Fetch the lesson based on the 'lessonId' parameter from the URL
        $lesson = $this->lessonRepository->find($lessonId);

        // Fetch the course based on the 'courseId' parameter from the URL
        $course = $this->courseRepository->find($courseId);

        // Check if the user is already enrolled
        $user = $this->getUser(); // Get the currently logged-in user
        $enrollment = $this->enrollmentRepository->findOneBy(['user' => $user, 'course' => $course]);

        $progress = new Progress();
        $progress->setLessons($lesson);
        $progress->setStatus(1);
        $progress->setLastAccessed(new \DateTimeImmutable());
        $progress->setEnrollment($enrollment);

        $this->em->persist($progress);
        $this->em->flush();

        // Redirect to the all courses page
        return $this->redirectToRoute('course_lessons', ['id' => $course->getId()]);
    }

}

