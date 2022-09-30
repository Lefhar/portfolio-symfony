<?php

namespace App\Entity;

use App\Repository\MesprojetsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MesprojetsRepository::class)
 */
class Mesprojets
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lien_github;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lien_web;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="mesprojets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLienGithub(): ?string
    {
        return $this->lien_github;
    }

    public function setLienGithub(?string $lien_github): self
    {
        $this->lien_github = $lien_github;

        return $this;
    }

    public function getLienWeb(): ?string
    {
        return $this->lien_web;
    }

    public function setLienWeb(?string $lien_web): self
    {
        $this->lien_web = $lien_web;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }
}
