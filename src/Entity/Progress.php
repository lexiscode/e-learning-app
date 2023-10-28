<?php

namespace App\Entity;

use App\Repository\ProgressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgressRepository::class)]
#[ORM\Table(name: 'progress')]
class Progress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: "enrollment_id")]
    private ?int $enrollmentId = null;

    #[ORM\Column(name: "lesson_id")]
    private ?int $lessonId = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $lastAccessed = null;

    #[ORM\ManyToOne(inversedBy: 'progresses')]
    private ?Enrollment $enrollment = null;

    #[ORM\ManyToOne(inversedBy: 'progresses')]
    private ?Lesson $lesson = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getEnrollmentId(): ?int
    {
        return $this->enrollmentId;
    }

    public function setEnrollmentId(int $enrollmentId): static
    {
        $this->enrollmentId = $enrollmentId;

        return $this;
    }

    public function getLessonId(): ?int
    {
        return $this->lessonId;
    }

    public function setLessonId(int $lessonId): static
    {
        $this->lessonId = $lessonId;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getLastAccessed(): ?\DateTimeImmutable
    {
        return $this->lastAccessed;
    }

    public function setLastAccessed(\DateTimeImmutable $lastAccessed): static
    {
        $this->lastAccessed = $lastAccessed;

        return $this;
    }

    public function getEnrollment(): ?Enrollment
    {
        return $this->enrollment;
    }

    public function setEnrollment(?Enrollment $enrollment): static
    {
        $this->enrollment = $enrollment;

        return $this;
    }

    public function getLessons(): ?Lesson
    {
        return $this->lesson;
    }

    public function setLessons(?Lesson $lesson): static
    {
        $this->lesson = $lesson;

        return $this;
    }
}