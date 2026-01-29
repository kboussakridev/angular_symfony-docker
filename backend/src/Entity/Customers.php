<?php

namespace App\Entity;

use App\Repository\CustomersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomersRepository::class)]
class Customers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $nombre = null;

    #[ORM\Column(length: 45)]
    private ?string $surname_1 = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $surname_2 = null;

    #[ORM\Column]
    private ?int $age = null;

    #[ORM\Column(length: 122)]
    private ?string $email = null;

    #[ORM\Column(length: 17)]
    private ?string $phone_number = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getSurname1(): ?string
    {
        return $this->surname_1;
    }

    public function setSurname1(string $surname_1): static
    {
        $this->surname_1 = $surname_1;

        return $this;
    }

    public function getSurname2(): ?string
    {
        return $this->surname_2;
    }

    public function setSurname2(?string $surname_2): static
    {
        $this->surname_2 = $surname_2;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): static
    {
        $this->phone_number = $phone_number;

        return $this;
    }
}
