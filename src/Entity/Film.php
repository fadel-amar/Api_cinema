<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FilmRepository::class)]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['list_films'])]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    #[Groups(['list_films'])]
    private ?string $titre = null;

    #[ORM\Column]
    #[Groups(['list_films'])]
    private ?int $duree = null;

    #[ORM\OneToMany(mappedBy: 'film', targetEntity: Seance::class)]
    private Collection $seances;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): void
    {
        $this->duree = $duree;
    }

    public function addSeance(Seance $seance): void {
        if (!$this->seances->contains($seance)) {
            $this->seances->add($seance);
            $seance->setFilm($this);
        }
    }

    public function getSeances(): Collection
    {
        return $this->seances;
    }


}
