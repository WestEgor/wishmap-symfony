<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 * @ORM\Table(name="`person`")
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=50, unique=false, nullable=false)
     */
    private string $nickname;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $avatar = null;


    /**
     * @ORM\Column(type="string", length=50, unique=false, nullable=false)
     */
    private string $profileDescription;


    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="users_id", referencedColumnName="id", nullable=false)
     */
    private User $user;

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
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @return string
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return string
     */
    public function getProfileDescription(): string
    {
        return $this->profileDescription;
    }

    /**
     * @param string $profileDescription
     */
    public function setProfileDescription(string $profileDescription): void
    {
        $this->profileDescription = $profileDescription;
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



}
