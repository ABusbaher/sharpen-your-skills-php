<?php

namespace App\Classes;

use App\Interfaces\ProductInterface;

class Product implements ProductInterface
{
    private string $name;

    private int $upc;

    private float $price;

    public Tax $taxRate;

    public ?Discount $discount;

    public function __construct($name, $upc, $price, Tax $taxRate, ?Discount $discount = null)
    {
        $this->name = $name;
        $this->upc = $upc;
        $this->price = $price;
        $this->taxRate = $taxRate;
        $this->discount = $discount ?? null;
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

    public function priceDiscount() :float
    {
        if ($this->discount) {
            return round($this->getPrice() * $this->discount->getDiscount() / 100, 2);
        }
        return 0;
    }

    public function getPriceWithTax() : float
    {
        return $this->getPrice() + $this->taxCost();
    }

    public function getPriceWithTaxAndDiscount() : float
    {
        return $this->getPrice() + $this->taxCost() - $this->priceDiscount();
    }

}