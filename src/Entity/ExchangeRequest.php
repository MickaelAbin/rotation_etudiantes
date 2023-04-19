<?php

namespace App\Entity;

use App\Repository\ExchangeRequestRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExchangeRequestRepository::class)
 * @ORM\Table(name = "exchange_requests")
 */
class ExchangeRequest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     * @ORM\Column(name = "id", type = "integer", options = {"unsigned": true})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(name = "request_date", type = "date")
     */
    private ?DateTimeImmutable $requestDate = null;

    /**
     * @ORM\Column(name = "is_accepted", type = "boolean")
     */
    private ?bool $isAccepted = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequestDate(): ?DateTimeImmutable
    {
        return $this->requestDate;
    }

    public function setRequestDate(DateTimeImmutable $requestDate): self
    {
        $this->requestDate = $requestDate;
        return $this;
    }

    public function isIsAccepted(): ?bool
    {
        return $this->isAccepted;
    }

    public function setIsAccepted(bool $isAccepted): self
    {
        $this->isAccepted = $isAccepted;
        return $this;
    }
}
