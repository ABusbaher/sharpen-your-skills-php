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
        return round($this->lowerPrice() * $this->taxRate->getRate() / 100, 2);
    }

    public function universalDiscount() :float
    {
        if ($this->discount) {
            if($this->discount->isBeforeTax()) {
                return round($this->getPrice() * $this->discount->getDiscount() / 100, 2);
            }
            return round($this->lowerPrice() * $this->discount->getDiscount() / 100, 2);
        }
        return 0;
    }

    public function upcDiscountAmount() :float
    {
        if ($this->upcDiscount) {
            if($this->upcDiscount->getUpc() === $this->getUpc()) {
                if($this->upcDiscount->isBeforeTax()) {
                    return round($this->getPrice() * $this->upcDiscount->getUpcDiscount() / 100, 2);
                }
                return round($this->lowerPrice() * $this->upcDiscount->getUpcDiscount() / 100, 2);
            }
            return 0;
        }
        return 0;
    }

    public function lowerPrice() :float
    {
        if ($this->upcDiscount && $this->discount) {
            if ($this->discount->isBeforeTax() && $this->upcDiscount->isBeforeTax()) {
                return $this->getPrice() - $this->universalDiscount() - $this->upcDiscountAmount();
            }else if ($this->discount->isBeforeTax()) {
                return $this->getPrice() - $this->universalDiscount();
            }else if ($this->upcDiscount->isBeforeTax()) {
                return $this->getPrice() - $this->upcDiscountAmount();
            }else {
                return $this->getPrice();
            }
        }elseif ($this->discount) {
            if($this->discount->isBeforeTax()) {
                return $this->getPrice() - $this->universalDiscount();
            }
            return $this->getPrice();
        }elseif ($this->upcDiscount) {
            if($this->upcDiscount->isBeforeTax()) {
                return $this->getPrice() - $this->upcDiscountAmount();
            }
            return $this->getPrice();
        }
        return $this->getPrice();

    }

    public function allDiscounts() :float
    {
        return $this->upcDiscountAmount() + $this->universalDiscount();
    }

    public function getPriceWithTax() : float
    {
        return $this->lowerPrice() + $this->taxCost();
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