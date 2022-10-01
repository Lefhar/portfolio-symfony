<?php

namespace App\Entity;

use App\Repository\CvRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CvRepository::class)
 */
class Cv
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
     * @ORM\OneToMany(targetEntity=Bloccv::class, mappedBy="cv")
     */
    private $bloccvs;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="cvs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;



    /**
     * @ORM\Column(type="boolean")
     */
    private $IsActive;

    public function __construct()
    {
        $this->bloccvs = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Bloccv>
     */
    public function getBloccvs(): Collection
    {
        return $this->bloccvs;
    }

    public function addBloccv(Bloccv $bloccv): self
    {
        if (!$this->bloccvs->contains($bloccv)) {
            $this->bloccvs[] = $bloccv;
            $bloccv->setCv($this);
        }

        return $this;
    }
    public function getTitleFile()
    {
        $string = $this->getTitle();
        $string = preg_replace('#Ç#', 'C', $string);
        $string = preg_replace('#ç#', 'c', $string);
        $string = preg_replace('#è|é|ê|ë#', 'e', $string);
        $string = preg_replace('#È|É|Ê|Ë#', 'E', $string);
        $string = preg_replace('#à|á|â|ã|ä|å#', 'a', $string);
        $string = preg_replace('#@|À|Á|Â|Ã|Ä|Å#', 'A', $string);
        $string = preg_replace('#ì|í|î|ï#', 'i', $string);
        $string = preg_replace('#Ì|Í|Î|Ï#', 'I', $string);
        $string = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $string);
        $string = preg_replace('#Ò|Ó|Ô|Õ|Ö#', 'O', $string);
        $string = preg_replace('#ù|ú|û|ü#', 'u', $string);
        $string = preg_replace('#Ù|Ú|Û|Ü#', 'U', $string);
        $string = preg_replace('#ý|ÿ#', 'y', $string);
        $string = preg_replace('#Ý#', 'Y', $string);
        $string = str_replace("'", '_', $string);
        $string = str_replace(' ', '_', $string);
        return $string;
    }
    public function removeBloccv(Bloccv $bloccv): self
    {
        if ($this->bloccvs->removeElement($bloccv)) {
            // set the owning side to null (unless already changed)
            if ($bloccv->getCv() === $this) {
                $bloccv->setCv(null);
            }
        }

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






    public function isIsActive(): ?bool
    {
        return $this->IsActive;
    }

    public function setIsActive(bool $IsActive): self
    {
        $this->IsActive = $IsActive;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
