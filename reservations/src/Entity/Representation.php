<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RepresentationRepository")
 * @ORM\Table(name="representations")
 */
class Representation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
	 * @Groups("spectacle:read")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Show", inversedBy="representations")
     * @ORM\JoinColumn(nullable=false, name="show_id", referencedColumnName="id")
     */
    private $show;

    /**
     * @ORM\Column(type="datetime")
	 * @Groups("spectacle:read")
     */
    private $schedule;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="representations")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $location;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="representation", orphanRemoval=true)
     */
    private $reservations;

    public function __construct()
    {
        $this->representationUsers = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getShow(): ?Show
    {
        return $this->show;
    }

    public function setShow(?Show $show): self
    {
        $this->show = $show;

        return $this;
    }

    public function getSchedule(): ?\DateTimeInterface
    {
        return $this->schedule;
    }

    public function setSchedule(\DateTimeInterface $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setRepresentation($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getRepresentation() === $this) {
                $reservation->setRepresentation(null);
            }
        }

        return $this;
    }
}
