<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Security\Core\User\LegacyPasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 * @package Fishly\PlaymanBundle\User\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")

 */
class User implements LegacyPasswordAuthenticatedUserInterface, JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="id")
     * @ORM\Column(type="integer")
     *
     */
    private int $id;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=128, unique=true, nullable=false)
     *
     * @Assert\Unique
     */
    private string $email;

    /**
     * @var string
     * @ORM\Column(name="username", type="string", length=128, unique=true, nullable=false)
     *
     * @Assert\Unique
     */
    private string $username;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private string $password;

    /**
     * @var string
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private string $salt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private DateTime $createdAt;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private ?DateTime $updatedAt;


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId(int $id): User
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     * @return User
     */
    public function setSalt(string $salt): User
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     * @return User
     */
    public function setCreatedAt(DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime|null $updatedAt
     * @return User
     */
    public function setUpdatedAt(?DateTime $updatedAt): User
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id'        => $this->id,
            'email'     => $this->email,
            'username'  => $this->username,
            'createdAt' => $this->createdAt
        );
    }
}
