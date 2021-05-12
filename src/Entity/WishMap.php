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
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true)
     */
    private ?Category $category;

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
    private ?float $process;

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
    private Comments $comments;


    public function getId(): ?int
    {
        return $this->id;
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


    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
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
     * @return $this
     */
    public function setStartDate(DateTime $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
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
     * @return $this
     */
    public function setFinishDate(DateTime $finishDate): self
    {
        $this->finishDate = $finishDate;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getProcess(): ?float
    {
        return $this->process;
    }

    /**
     * @param float $process
     * @return $this
     */
    public function setProcess(float $process): self
    {
        $this->process = $process;
        return $this;
    }

    /**
     * @return Person
     */
    public function getPerson(): Person
    {
        return $this->person;
    }

    public function setPerson(Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    /**
     * @return Comments|null
     */
    public function getComments(): ?Comments
    {
        return $this->comments;
    }

    /**
     * @param Comments $comments
     * @return $this
     */
    public function setComments(Comments $comments): self
    {
        $this->comments = $comments;
        return $this;
    }


}
