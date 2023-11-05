<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Enrollment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EnrollmentController extends AbstractController
{
    private $em;
    private $courseRepository;
    private $enrollmentRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->courseRepository = $em->getRepository(Course::class);
        $this->enrollmentRepository = $em->getRepository(Enrollment::class);
    }
    
    #[Route('/student/course/enroll/{id}', name: 'enroll_course')]
    public function enrollCourse($id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_STUDENT');

        // Fetch the course based on the 'id' parameter from the URL
        $course = $this->courseRepository->find($id);

        // Check if the user is already enrolled
        $user = $this->getUser(); // Get the currently logged-in user
        $enrollment = $this->enrollmentRepository->findOneBy(['user' => $user, 'course' => $course]);

        if (!$enrollment) {
            $enrollment = new Enrollment();
            $enrollment->setUser($user);
            $enrollment->setCourse($course);
            $enrollment->setIsEnrolled(true); // Set the enrollment status to true

            $this->em->persist($enrollment);
            $this->em->flush();
        }

        // Redirect to the all courses page
        return $this->redirectToRoute('course_lessons', ['id' => $course->getId()]);
    }

    #[Route('/student/course/resume/{id}', name: 'resume_course')]
    public function resumeCourse($id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_STUDENT');

        // Fetch the course based on the 'id' parameter from the URL
        $course = $this->courseRepository->find($id);

        // Check if the user is enrolled in the course
        $user = $this->getUser(); // Get the currently logged-in user
        $enrollment = $this->enrollmentRepository->findOneBy(['user' => $user, 'course' => $course]);

        if ($enrollment && $enrollment->getIsEnrolled()) {
            // User is enrolled, so allow them to resume the course
            // You can redirect to the course content or lessons page
            return $this->redirectToRoute('course_lessons', ['id' => $course->getId()]);
        } else {
            // User is not enrolled; you can show a message or redirect them to the enrollment page
            return $this->redirectToRoute('all_courses');
        }
    }


    #[Route('/student/course/unenroll/{id}', name: 'unenroll_course')]
    public function unenrollCourse($id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_STUDENT');
        
        // Fetch the course based on the 'id' parameter from the URL
        $course = $this->courseRepository->find($id);

        // Check if the user is enrolled in the course
        $user = $this->getUser(); // Get the currently logged-in user

        $enrollment = $this->enrollmentRepository->findOneBy(['user' => $user, 'course' => $course]);

        /* 
        //Ensure that there are no associated progress records//
        // The code below is a "manual" way of deleting the foreign constraints of progress table data, from enrollment table
        // but this has been done indirectly already in Enrollment entity by adding "orphanRemoval: true, cascade: ["remove"]"
        // beside the $progresses property

        $progressRecords = $enrollment->getProgresses();
        foreach ($progressRecords as $progress) {
            $this->em->remove($progress);
        }
        */

        // Now, remove the enrollment record
        $this->em->remove($enrollment);
        $this->em->flush();

        // Redirect to the all courses page 
        return $this->redirectToRoute('all_courses');
    }

}
