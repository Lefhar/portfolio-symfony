<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Mesprojets::class, mappedBy="users")
     */
    private $mesprojets;

    /**
     * @ORM\OneToMany(targetEntity=Cv::class, mappedBy="users")
     */
    private $cvs;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $github;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkedin;

    /**
     * @ORM\OneToMany(targetEntity=Demarchage::class, mappedBy="users")
     */
    private $demarchages;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="users")
     */
    private $messages;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contact;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codepostal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    public function __construct()
    {
        $this->mesprojets = new ArrayCollection();
        $this->cvs = new ArrayCollection();
        $this->demarchages = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Mesprojets>
     */
    public function getMesprojets(): Collection
    {
        return $this->mesprojets;
    }

    public function addMesprojet(Mesprojets $mesprojet): self
    {
        if (!$this->mesprojets->contains($mesprojet)) {
            $this->mesprojets[] = $mesprojet;
            $mesprojet->setUsers($this);
        }

        return $this;
    }

    public function removeMesprojet(Mesprojets $mesprojet): self
    {
        if ($this->mesprojets->removeElement($mesprojet)) {
            // set the owning side to null (unless already changed)
            if ($mesprojet->getUsers() === $this) {
                $mesprojet->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cv>
     */
    public function getCvs(): Collection
    {
        return $this->cvs;
    }

    public function addCv(Cv $cv): self
    {
        if (!$this->cvs->contains($cv)) {
            $this->cvs[] = $cv;
            $cv->setUsers($this);
        }

        return $this;
    }

    public function removeCv(Cv $cv): self
    {
        if ($this->cvs->removeElement($cv)) {
            // set the owning side to null (unless already changed)
            if ($cv->getUsers() === $this) {
                $cv->setUsers(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getEmail();
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): self
    {
        $this->github = $github;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    /**
     * @return Collection<int, Demarchage>
     */
    public function getDemarchages(): Collection
    {
        return $this->demarchages;
    }

    public function addDemarchage(Demarchage $demarchage): self
    {
        if (!$this->demarchages->contains($demarchage)) {
            $this->demarchages[] = $demarchage;
            $demarchage->setUsers($this);
        }

        return $this;
    }

    public function removeDemarchage(Demarchage $demarchage): self
    {
        if ($this->demarchages->removeElement($demarchage)) {
            // set the owning side to null (unless already changed)
            if ($demarchage->getUsers() === $this) {
                $demarchage->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUsers($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUsers() === $this) {
                $message->setUsers(null);
            }
        }

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodepostal(): ?string
    {
        return $this->codepostal;
    }

    public function setCodepostal(?string $codepostal): self
    {
        $this->codepostal = $codepostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }
}
