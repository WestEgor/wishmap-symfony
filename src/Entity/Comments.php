<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 * @ORM\Table(name="`comments`")
 */
class Comments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string",length=255, nullable=false)
     */
    private string $comment;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"}))
     */
    private DateTime $dateOfSend;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="send_user_id", referencedColumnName="id", nullable=false)
     */
    private User $sendUser;

    /**
     * Comments constructor.
     */
    public function __construct()
    {
        $this->dateOfSend = new DateTime('now');
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return DateTime
     */
    public function getDateOfSend(): DateTime
    {
        return $this->dateOfSend;
    }

    /**
     * @param DateTime $dateOfSend
     */
    public function setDateOfSend(DateTime $dateOfSend): void
    {
        $this->dateOfSend = $dateOfSend;
    }


    /**
     * @return User
     */
    public function getSendUser(): User
    {
        return $this->sendUser;
    }

    /**
     * @param User $sendUser
     */
    public function setSendUser(User $sendUser): void
    {
        $this->sendUser = $sendUser;
    }


}