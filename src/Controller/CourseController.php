<?php

namespace App\Controller;

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
        // creating and storing in the database
        $course = new Course();
        $course->setCreatedAt(new DateTimeImmutable()); 
        $course->setUpdatedAt(new DateTimeImmutable()); 
        $form = $this->createForm(CourseFormType::class, $course);
        $form->handleRequest($request);

        // reading from the database
        $courses = $this->courseRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($course);
            $this->em->flush();

            return $this->redirectToRoute('app_course');
        }

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

