<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date1;

    /**
     * @ORM\Column(type="date")
     */
    private $date2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $beds;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="reservations")
     */
    private $room;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate1(): ?\DateTimeInterface
    {
        return $this->date1;
    }

    public function setDate1(\DateTimeInterface $date1): self
    {
        $this->date1 = $date1;

        return $this;
    }

    public function getDate2(): ?\DateTimeInterface
    {
        return $this->date2;
    }

    public function setDate2(\DateTimeInterface $date2): self
    {
        $this->date2 = $date2;

        return $this;
    }

    public function getBeds(): ?int
    {
        return $this->beds;
    }

    public function setBeds(?int $beds): self
    {
        $this->beds = $beds;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }


}
