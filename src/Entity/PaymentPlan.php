<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class PaymentPlan
{

    public function __construct()
    {
        $this->isActive = false;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="integer")
     */
    private $period;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="integer")
     */
    private $displayOrder;

    /**
     * @return mixed
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }

    /**
     * @param mixed $displayOrder
     * @return Course
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;
        return $this;
    }


    private $html;

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return PaymentPlan
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return PaymentPlan
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     * @return PaymentPlan
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param mixed $period
     * @return PaymentPlan
     */
    public function setPeriod($period)
    {
        $this->period = $period;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param mixed $html
     * @return PaymentPlan
     */
    public function setHtml($html)
    {
        $this->html = $html;
        return $this;
    }


    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }


    /**
     * @param bool $isActive
     * @return PaymentPlan
     */
    public function setIsActive(bool $isActive): PaymentPlan
    {
        $this->isActive = $isActive;
        return $this;
    }

}
