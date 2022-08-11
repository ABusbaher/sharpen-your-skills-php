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

    public function reportCosts(): string
    {
        $costs = nl2br('Cost = $' . $this->getPrice() . "\n");
        $tax = nl2br('Tax = $' . $this->taxCost() . "\n");
        $discount = $this->discount ? nl2br('Discounts = $' . $this->priceDiscount() . "\n") : NULL;
        $total = nl2br('TOTAL = $' . $this->getPriceWithTaxAndDiscount() . "\n");
        return $costs  . $tax  . $discount  . $total;
    }
}