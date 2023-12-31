<?php

namespace App\Entity;

use App\Repository\EnrollmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnrollmentRepository::class)]
#[ORM\Table(name: 'enrollment')]
class Enrollment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: "is_enrolled", type: 'boolean')]
    private $isEnrolled = false;

    #[ORM\Column(name: "enrollment_date")]
    private ?\DateTimeImmutable $enrollmentDate = null;

    #[ORM\ManyToOne(inversedBy: 'enrollments', targetEntity: User::class)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'enrollments', targetEntity: Course::class)]
    private ?Course $course = null;

    #[ORM\OneToMany(mappedBy: 'enrollment', targetEntity: Progress::class, orphanRemoval: true)]
    private Collection $progresses;

    public function __construct()
    {
        $this->progresses = new ArrayCollection();
        $this->enrollmentDate = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getEnrollmentDate(): ?\DateTimeImmutable
    {
        return $this->enrollmentDate;
    }

    public function setEnrollmentDate(\DateTimeImmutable $enrollmentDate): static
    {
        $this->enrollmentDate = $enrollmentDate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): static
    {
        $this->course = $course;

        return $this;
    }

    public function getIsEnrolled(): bool
    {
        return $this->isEnrolled;
    }

    public function setIsEnrolled(bool $isEnrolled): void
    {
        $this->isEnrolled = $isEnrolled;
    }

    /**
     * @return Collection<int, Progress>
     */
    public function getProgresses(): Collection
    {
        return $this->progresses;
    }

    public function addProgress(Progress $progress): static
    {
        if (!$this->progresses->contains($progress)) {
            $this->progresses->add($progress);
            $progress->setEnrollment($this);
        }

        return $this;
    }

    public function removeProgress(Progress $progress): static
    {
        if ($this->progresses->removeElement($progress)) {
            // set the owning side to null (unless already changed)
            if ($progress->getEnrollment() === $this) {
                $progress->setEnrollment(null);
            }
        }

        return $this;
    }
}

