<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 */
class Payment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="payments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $method;

    /**
     * @ORM\Column(type="text")
     */
    private $transaction;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;


    /**
     * @ORM\Column(type="text")
     */
    private $payload;

    public function getExpiresAt()
    {
        return $this->date->modify("+" . $this->plan->getPeriod() . " days");
    }


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentPlan")
     */
    private $plan;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getTransaction(): ?string
    {
        return $this->transaction;
    }

    public function setTransaction(string $transaction): self
    {
        $this->transaction = $transaction;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * @return PaymentPlan
     */
    public function getPlan(): ?PaymentPlan
    {
        return $this->plan;
    }

    /**
     * @param PaymentPlan $plan
     * @return Payment
     */
    public function setPlan(PaymentPlan $plan)
    {
        $this->plan = $plan;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param mixed $payload
     * @return Payment
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
        return $this;
    }

}
