<?php

namespace App\Classes;

use App\Interfaces\ProductInterface;
use App\Classes\Tax;

class Product implements ProductInterface
{
    private string $name;

    private int $upc;

    private float $price;

    public Tax $taxRate;

    public function __construct($name, $upc, $price, Tax $taxRate)
    {
        $this->name = $name;
        $this->upc = $upc;
        $this->price = $price;
        $this->taxRate = $taxRate;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUpc(): int
    {
        return $this->upc;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function taxCost() :float
    {
        return round($this->getPrice() * $this->taxRate->getRate() / 100, 2);
    }

    public function getPriceWithTax() : float
    {
        return $this->getPrice() + $this->taxCost();
    }

}