<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArtistTypeRepository")
 * @ORM\Table(name="artist_type")
 */
class ArtistType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @Groups("spectacle:read")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Artist")
     * @ORM\JoinColumn(nullable=false)
	 * @Groups("spectacle:read") 
     */
    private $artist;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Type", inversedBy="type")
     * @ORM\JoinColumn(nullable=false)
	 * @Groups("spectacle:read")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Collaboration", mappedBy="artistType", orphanRemoval=true)
     */
    private $collaborations;

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }
	
	public function __construct()
    {
	     $this->collaborations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setArtist(?Artist $artist): self
    {
        $this->artist = $artist;

        return $this;
    }
	

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Collaboration[]
     */
    public function getCollaborations(): Collection
    {
        return $this->collaborations;
    }

    public function addCollaboration(Collaboration $collaboration): self
    {
        if (!$this->collaborations->contains($collaboration)) {
            $this->collaborations[] = $collaboration;
            $collaboration->setArtistType($this);
        }

        return $this;
    }

    public function removeCollaboration(Collaboration $collaboration): self
    {
        if ($this->collaborations->contains($collaboration)) {
            $this->collaborations->removeElement($collaboration);
            // set the owning side to null (unless already changed)
            if ($collaboration->getArtistType() === $this) {
                $collaboration->setArtistType(null);
            }
        }

        return $this;
    }
}
