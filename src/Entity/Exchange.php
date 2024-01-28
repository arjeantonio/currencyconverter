<?php

namespace App\Entity;

use App\Repository\ExchangeRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExchangeRepository::class)]
#[ORM\Table(name: 'exchanges')]
#[ORM\UniqueConstraint(fields: ['source_currency', 'target_currency'])]
#[UniqueEntity(fields: ['source_currency', 'target_currency'], message: 'Exchange for given currency already exists in database.')]
class Exchange
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 3)]
    private ?string $source_currency = null;

    #[ORM\Column(length: 3)]
    private ?string $target_currency = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 10)]
    private ?string $rate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $last_updated = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourceCurrency(): ?string
    {
        return $this->source_currency;
    }

    public function setSourceCurrency(string $source_currency): static
    {
        $this->source_currency = $source_currency;

        return $this;
    }

    public function getTargetCurrency(): ?string
    {
        return $this->target_currency;
    }

    public function setTargetCurrency(string $target_currency): static
    {
        $this->target_currency = $target_currency;

        return $this;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(string $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getLastUpdated(): ?\DateTimeInterface
    {
        return $this->last_updated;
    }

    public function setLastUpdated(\DateTimeInterface $last_updated): static
    {
        $this->last_updated = $last_updated;

        return $this;
    }
}
