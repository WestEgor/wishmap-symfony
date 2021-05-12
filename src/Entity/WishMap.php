<?php

namespace App\Entity;

use DateTime;
use App\Repository\WishMapRepository;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="string", nullable=true)
     */
    private string $image;

    /**
     * @ORM\ManyToOne (targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     */
    private Category $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $description;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default": "CURRENT_TIMESTAMP"}))
     */
    private ?DateTime $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $finishDate;

    /**
     * @ORM\Column(type="float", nullable=true, options={"default" : 0})
     */
    private float $process;

    /**
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="persons_id", referencedColumnName="id", nullable=false)
     */
    private Person $person;

    /**
     * @ORM\ManyToMany(targetEntity="Comments")
     * @ORM\JoinTable(name="wish_map_comments",
     *      joinColumns={@ORM\JoinColumn(name="wish_map_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="comment_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $comments;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param  $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }


    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): DateTime|null
    {
        return $this->startDate;
    }

    public function setStartDate(DateTime $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getFinishDate(): DateTime
    {
        return $this->finishDate;
    }

    public function setFinishDate(DateTime $finishDate): self
    {
        $this->finishDate = $finishDate;

        return $this;
    }

    /**
     * @return float
     */
    public function getProcess(): float
    {
        return $this->process;
    }

    /**
     * @param float $process
     */
    public function setProcess(float $process): void
    {
        $this->process = $process;
    }

    /**
     * @return Person
     */
    public function getPerson(): Person
    {
        return $this->person;
    }

    /**
     * @param Person $person
     */
    public function setPerson(Person $person): void
    {
        $this->person = $person;
    }

    /**
     * @return Comments
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param Comments $comments
     */
    public function setComments($comments): void
    {
        $this->comments = $comments;
    }


}
