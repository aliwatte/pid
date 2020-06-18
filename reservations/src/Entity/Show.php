<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShowRepository")
 * @ORM\Table(name="shows")
 * @UniqueEntity("slug")
 * @ApiResource(
 * attributes={
 * "order"={"title": "ASC"}
 * },
 * normalizationContext={"groups"={"spectacle:read"} 
 * })
 */
class Show
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
	 * @Groups("spectacle:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
	 * @Groups("spectacle:read")
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
	 * @Groups("spectacle:read")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
	 * @Groups("spectacle:read")
     */
    private $poster_url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="shows", cascade={"persist"})
	 * @ORM\JoinColumn(name="location_id", referencedColumnName="id", onDelete="CASCADE")
	 * @Groups("spectacle:read")
     */
    private $location_id;
	
	/**
     * @ORM\OneToMany(targetEntity="App\Entity\Representation", mappedBy="show", orphanRemoval=true)
	 * @Groups("spectacle:read")
     */
    private $representations;

    /**
     * @ORM\Column(type="boolean")
	 * @Groups("spectacle:read")
     */
    private $bookable;

    /**
     * @ORM\Column(type="float")
	 * @Groups("spectacle:read")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Collaboration", mappedBy="shows", orphanRemoval=true)
	 * @Groups("spectacle:read")
     */
    private $collaborations;
	
	public function __construct()
    {
	   $this->representations = new ArrayCollection();
	   $this->collaborations = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
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

    public function getPosterUrl(): ?string
    {
        return $this->poster_url;
    }

    public function setPosterUrl(?string $poster_url): self
    {
        $this->poster_url = $poster_url;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location_id;
    }

    public function setLocation(?Location $location_id): self
	{
        $this->location_id = $location_id;

        return $this;
    }

    public function getBookable(): ?bool
	{
        return $this->bookable;
    }

    public function setBookable(bool $bookable): self
    {
        $this->bookable = $bookable;

        return $this;
}

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
	}
	
	/**
     * @return Collection|Representation[]
     */
    public function getRepresentations(): Collection
    {
        return $this->representations;
    }

    public function addRepresentation(Representation $representation): self
    {
        if (!$this->representations->contains($representation)) {
            $this->representations[] = $representation;
            $representation->setShow($this);
        }

        return $this;
    }

    public function removeRepresentation(Representation $representation): self
    {
        if ($this->representations->contains($representation)) {
            $this->representations->removeElement($representation);
            // set the owning side to null (unless already changed)
            if ($representation->getShowId() === $this) {
                $representation->setShowId(null);
            }
        }

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
            $collaboration->setShows($this);
        }

        return $this;
    }

    public function removeCollaboration(Collaboration $collaboration): self
    {
        if ($this->collaborations->contains($collaboration)) {
            $this->collaborations->removeElement($collaboration);
            // set the owning side to null (unless already changed)
            if ($collaboration->getShows() === $this) {
                $collaboration->setShows(null);
            }
        }

        return $this;
    }
	
	/**
    * @return Collection|Collaboration[]
    */
    public function getAuthors(): Collection
    {
        $authors = new ArrayCollection();

        foreach($this->collaborations as $collaboration) {
            if($collaboration->getArtistType()->getType()->getType()=="scènographe") {
                $authors->add($collaboration->getArtistType()->getArtist());
            }
        }
        return $authors;
    }
}
