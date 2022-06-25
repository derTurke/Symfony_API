<?php

namespace App\Entity;

use App\Repository\EmployeeProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeProductRepository::class)]
class EmployeeProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $employee_name;

    #[ORM\Column(type: 'string', length: 255)]
    private $product_name;

    #[ORM\Column(type: 'integer')]
    private $duration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployeeName(): ?string
    {
        return $this->employee_name;
    }

    public function setEmployeeName(string $employee_name): self
    {
        $this->employee_name = $employee_name;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->product_name;
    }

    public function setProductName(string $product_name): self
    {
        $this->product_name = $product_name;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
}
