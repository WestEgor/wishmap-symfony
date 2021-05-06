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
     * @ORM\Column(type="string", length=255)
     */
    private string $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $finishDate;

    /**
     * @ORM\Column(type="float")
     */
    private float $process;

    /**
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="persons_id", referencedColumnName="id", nullable=false)
     */
    private int $person;

    /**
     * @ORM\ManyToOne(targetEntity="Comments")
     * @ORM\JoinColumn(name="comments_id", referencedColumnName="id", nullable=false)
     */
    private int $comments;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?DateTime
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
}
