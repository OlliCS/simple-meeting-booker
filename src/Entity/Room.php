<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RoomRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
#[ApiResource(
    description: 'A room where a conference can be held.',
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Put(),
        new Delete()],
    normalizationContext: ['groups' => ['room:read']],
    denormalizationContext: ['groups' => ['room:write']],
)]
#[UniqueEntity(fields: ['name'] ,message: 'A room with this name already exists')]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Fill in the name of the room')]
    #[Assert\Length(min: 2, max: 50, minMessage: 'The minimum length of the name is 2 characters', maxMessage: 'The maximum length of the name is 50 characters')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9]+$/', message: 'The name must be alphanumeric')]
    #[Groups(['room:read', 'room:write'])]
    private ?string $name = null;



    #[ORM\Column]
    #[Assert\NotBlank(message: 'Fill in capacity for the room')]
    #[Assert\GreaterThan(1, message: 'Capacity must be greater than 1')]
    #[Assert\LessThan(100, message: 'Capacity must be less than 100')]
    #[Groups(['room:read', 'room:write'])]
    private ?int $capacity = null;



    #[ORM\OneToMany(mappedBy: 'room', targetEntity: Booking::class, orphanRemoval: true)]
    #[Groups(['room:read'])]
    private Collection $bookings;



    public function __construct($name, $capacity)
    {
        $this->name = $name;
        $this->capacity = $capacity;
        $this->bookings = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setRoom($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getRoom() === $this) {
                $booking->setRoom(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }



}
