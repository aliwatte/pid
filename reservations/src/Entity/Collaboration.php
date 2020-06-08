<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CollaborationRepository")
 * @ORM\Table(name="collaborations ")
 */
class Collaboration
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
	 * @Groups("spectacle:read")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ArtistType", inversedBy="collaborations")
     * @ORM\JoinColumn(nullable=false)
	 * @Groups("spectacle:read")
     */
    private $artistType;

	/**
    * @ORM\ManyToOne(targetEntity="App\Entity\Show", inversedBy="collaborations")
    * @ORM\JoinColumn(nullable=false, name="shows", referencedColumnName="id")
    */ 
    private $shows;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArtistType(): ?ArtistType
    {
        return $this->artistType;
    }

    public function setArtistType(?ArtistType $artistType): self
    {
        $this->artistType = $artistType;

        return $this;
    }

    public function getShows(): ?Show
    {
        return $this->shows;
    }

    public function setShows(?Show $shows): self
    {
        $this->shows = $shows;

        return $this;
    }
}
