<?php

namespace App\Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @ORM\Entity(repositoryClass=WishMapRepository::class)
 * @ORM\Table(name="`wish_map`")
 */
class WishMap
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $image = null;

    /**
     * @ORM\ManyToOne (targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true)
     */
    private ?Category $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $description;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"}))
     */
    private DateTime $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $finishDate;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private ?int $progress;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="persons_id", referencedColumnName="id", nullable=false)
     */
    private User $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isArchived;

    /**
     * @ORM\ManyToMany(targetEntity="Comments", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\JoinTable(name="wish_map_comments",
     *      joinColumns={@ORM\JoinColumn(name="wish_map_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="comment_id", referencedColumnName="id",
     *      unique=true)}
     *      )
     */
    private $comments;


    public function __construct()
    {
        $this->startDate = new DateTime('now');
        $this->progress = 0;
        $this->isArchived = false;
        $this->comments = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }


    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string $image
     * @return $this
     */
    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }


    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param $category
     * @return $this
     */
    public function setCategory($category): self
    {
        $this->category = $category;
        return $this;
    }


    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $startDate
     */
    public function setStartDate(DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return DateTime
     */
    public function getFinishDate(): DateTime
    {
        return $this->finishDate;
    }

    /**
     * @param DateTime $finishDate
     */
    public function setFinishDate(DateTime $finishDate): void
    {
        $this->finishDate = $finishDate;
    }


    /**
     * @return int|null
     */
    public function getProgress(): ?int
    {
        return $this->progress;
    }

    /**
     * @param int|null $progress
     */
    public function setProgress(?int $progress): void
    {
        $this->progress = $progress;
    }


    /**
     * @return ArrayCollection|null
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param  $comments
     * @return $this
     */
    public function setComments($comments): self
    {
        $this->comments[] = $comments;
        return $this;
    }

    /**
     * @return bool
     */
    public function isArchived(): bool
    {
        return $this->isArchived;
    }

    /**
     * @param bool $isArchived
     */
    public function setIsArchived(bool $isArchived): void
    {
        $this->isArchived = $isArchived;
    }


    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function countDateDifference(DateTime $oldStartDate, DateTime $oldFinishDate,
                                        DateTime $newStartDate)
    {
        $dateInterval = date_diff($oldStartDate, $oldFinishDate);
        return date_add($newStartDate, $dateInterval);
    }

}
