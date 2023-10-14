<?php

namespace App\Entity;

use Assert\GreaterThan;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;

use ApiPlatform\Metadata\GetCollection;
use App\Repository\RoomSearcherRepository;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\NumericFilter;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoomSearcherRepository::class)]
#[ApiResource(
    shortName:'search',
    description: 'Search for a room on a specific date and for a specific number of people.',
    operations: [
    new Post(
        name:'search',
        routeName:'room_searcher',
    ),

    ]

)]
#[ApiFilter(NumericFilter::class, properties: ['people'])]
#[ApiFilter(DateFilter::class, properties: ['date'])]
#[ApiFilter(RangeFilter::class, properties: ['date', 'people'])]



class RoomSearcher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Fill in the number of people')]
    #[Assert\GreaterThan(1, message: 'Number of people must be greater than 1')]
    #[Assert\LessThan(101, message: 'Maximum number of people is 100')]

    private ?int $people = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\GreaterThan('now', message: 'Date must be in the future')]
    #[Assert\NotBlank(message: 'Fill in the date')]

    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPeople(): ?int
    {
        return $this->people;
    }

    public function setPeople(int $people): static
    {
        $this->people = $people;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }


}