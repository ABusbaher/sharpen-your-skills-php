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

    public ?UpcDiscount $upcDiscount;

    public function __construct($name, $upc, $price, Tax $taxRate,
                                ?Discount $discount = null, ?UpcDiscount $upcDiscount = null)
    {
        $this->name = $name;
        $this->upc = $upc;
        $this->price = $price;
        $this->taxRate = $taxRate;
        $this->discount = $discount ?? null;
        $this->upcDiscount = $upcDiscount ?? null;
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

    public function priceUpcDiscount() :float
    {
        if ($this->upcDiscount) {
            if($this->upcDiscount->getUpc() === $this->getUpc()) {
                return round($this->getPrice() * $this->upcDiscount->getUpcDiscount() / 100, 2);
            }
            return 0;
        }
        return 0;
    }

    public function allDiscounts() :float
    {
        return $this->priceUpcDiscount() + $this->priceDiscount();
    }

    public function getPriceWithTax() : float
    {
        return $this->getPrice() + $this->taxCost();
    }

    public function getPriceWithTaxAndDiscounts() : float
    {
        return $this->getPrice() + $this->taxCost() - $this->allDiscounts();
    }

    public function reportCosts(): string
    {
        $costs = nl2br('Cost = $' . $this->getPrice() . "\n");
        $tax = nl2br('Tax = $' . $this->taxCost() . "\n");
        $discount = $this->allDiscounts() > 0 ? nl2br('Discounts = $' . $this->allDiscounts() . "\n") : NULL;
        $total = nl2br('TOTAL = $' . $this->getPriceWithTaxAndDiscounts() . "\n");
        return $costs  . $tax  . $discount  . $total;
    }
}