<?php

namespace App\Twig\Extension;

use App\Entity\User;
use Twig\TwigFilter;
use App\Entity\Course;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Doctrine\ORM\EntityManagerInterface;
use App\Twig\Runtime\EnrollExtentionRuntime;

class EnrollExtension extends AbstractExtension
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [EnrollExtentionRuntime::class, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [EnrollExtentionRuntime::class, 'doSomething']),
            new TwigFunction('isEnrolled', [$this, 'isEnrolled']),
        ];
    }

    public function isEnrolled(User $user, Course $course)
    {
        $enrollmentRepository = $this->em->getRepository(Enrollment::class);
        $enrollment = $enrollmentRepository->findOneBy(['user' => $user, 'course' => $course]);
        return $enrollment !== null;
    }
}
