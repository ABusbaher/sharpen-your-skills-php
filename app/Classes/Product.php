<?php

namespace App\Classes;

use App\Exceptions\NotValidCurrencyException;
use App\Interfaces\ProductInterface;

class Product implements ProductInterface
{
    private string $name;

    private int $upc;

    private float $price;

    public Tax $taxRate;

    public ?Discount $discount;

    public ?UpcDiscount $upcDiscount;

    public ?Transport $transport;

    public ?Packaging $packaging;

    public ?Cap $cap;

    private string $currency;

    public function __construct(string $name, int $upc, float $price, Tax $taxRate,
                                ?Discount $discount = null, ?UpcDiscount $upcDiscount = null,
                                ?Transport $transport = null, ?Packaging $packaging = null,
                                ?Cap $cap = null, string $currency = 'USD')
    {
        $this->name = $name;
        $this->upc = $upc;
        $this->price = $price;
        $this->taxRate = $taxRate;
        $this->discount = $discount ?? null;
        $this->upcDiscount = $upcDiscount ?? null;
        $this->transport = $transport ?? null;
        $this->packaging = $packaging ?? null;
        $this->cap = $cap ?? null;
        $this->currency = $currency;
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

    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @throws NotValidCurrencyException
     */
    public function setCurrency(string $currency): void
    {
        if (!in_array(strtoupper($currency), self::ALLOWED_CURRENCIES)) {
            throw new NotValidCurrencyException();
        }
        $this->currency = strtoupper($currency);
    }

    public function lowerPrice() :float
    {
        if ($this->upcDiscount && $this->discount) {
            if ($this->discount->isBeforeTax() && $this->upcDiscount->isBeforeTax()) {
                return $this->getPrice() - $this->universalDiscountAmount() - $this->upcDiscountAmount();
            }else if ($this->discount->isBeforeTax()) {
                return $this->getPrice() - $this->universalDiscountAmount();
            }else if ($this->upcDiscount->isBeforeTax()) {
                return $this->getPrice() - $this->upcDiscountAmount();
            }else {
                return $this->getPrice();
            }
        }elseif ($this->discount) {
            if($this->discount->isBeforeTax()) {
                return $this->getPrice() - $this->universalDiscountAmount();
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

    public function taxCost() :float
    {
        return round($this->lowerPrice() * $this->taxRate->getRate() / 100, 4);
    }

    private function multiplicativeUniversalDiscountAmount(): float
    {
        if ($this->discount->isMultiplicativeDiscount()) {
            return round(($this->getPrice() - $this->upcDiscountAmount()) * $this->discount->getDiscount() / 100, 4);
        }
        return round($this->lowerPrice() * $this->discount->getDiscount() / 100, 4);
    }

    public function universalDiscountAmount() :float
    {
        if ($this->discount) {
            if($this->discount->isBeforeTax()) {
                return round($this->getPrice() * $this->discount->getDiscount() / 100, 4);
            }
            if ($this->upcDiscount) {
                return $this->multiplicativeUniversalDiscountAmount();
            }
            return round($this->lowerPrice() * $this->discount->getDiscount() / 100, 4);
        }
        return 0;
    }

    private function multiplicativeUpcDiscountAmount(): float
    {
        if ($this->upcDiscount->isMultiplicativeDiscount()) {
            return round(($this->getPrice() - $this->universalDiscountAmount()) * $this->upcDiscount->getDiscount() / 100, 4);
        }
        return round($this->lowerPrice() * $this->upcDiscount->getDiscount() / 100, 4);
    }


    public function upcDiscountAmount() :float
    {
        if ($this->upcDiscount) {
            if($this->upcDiscount->getUpc() === $this->getUpc()) {
                if($this->upcDiscount->isBeforeTax()) {
                    return round($this->getPrice() * $this->upcDiscount->getDiscount() / 100, 4);
                }
                if ($this->discount) {
                    return $this->multiplicativeUpcDiscountAmount();
                }
                return round($this->lowerPrice() * $this->upcDiscount->getDiscount() / 100, 4);
            }
            return 0;
        }
        return 0;
    }

    public function getTransportCost() :float
    {
        if ($this->transport) {
            if ($this->transport->getTypeOfAmount() === 'absolute') {
                return $this->transport->getAmount();
            }
            if ($this->transport->getTypeOfAmount() === 'percentage') {
                return round($this->lowerPrice() * $this->transport->getAmount() / 100, 4);
            }
            return 0;
        }
        return 0;
    }

    public function getPackagingCost() :float
    {
        if ($this->packaging) {
            if ($this->packaging->getTypeOfAmount() === 'absolute') {
                return $this->packaging->getAmount();
            }
            if ($this->packaging->getTypeOfAmount() === 'percentage') {
                return round($this->lowerPrice() * $this->packaging->getAmount() / 100, 4);
            }
            return 0;
        }
        return 0;
    }

    public function calculateCap() :?float {
        if ($this->cap) {
            if ($this->cap->getTypeOfAmount() === 'absolute') {
                return $this->cap->getAmount();
            }
            if ($this->cap->getTypeOfAmount() == 'percentage') {
                return round($this->getPrice() * $this->cap->getAmount() / 100, 4);
            }
            return null;
        }
        return null;
    }

    public function allDiscounts() :float
    {
        $allDiscountsWithoutCap = $this->upcDiscountAmount() + $this->universalDiscountAmount();
        if ($this->cap) {
            if ($this->calculateCap() && $this->calculateCap() < $allDiscountsWithoutCap) {
                return $this->calculateCap();
            }
            return $allDiscountsWithoutCap;
        }
        return $allDiscountsWithoutCap;
    }

    public function getPriceWithTax() : float
    {
        return $this->lowerPrice() + $this->taxCost();
    }

    public function getPriceWithTaxAndDiscounts() : float
    {
        return $this->getPrice() + $this->taxCost() - $this->allDiscounts() + $this->getPackagingCost() + $this->getTransportCost();
    }

    public function reportCosts(): string
    {
        $costs = nl2br('Cost = ' . $this->getPrice() . $this->getCurrency() . "\n");
        $tax = nl2br('Tax = ' . round($this->taxCost(),2) . $this->getCurrency() . "\n");
        $discount = $this->allDiscounts() > 0 ?
            nl2br('Discounts = ' . round($this->allDiscounts(),2) . $this->getCurrency() . "\n") : NULL;
        $packaging = $this->getPackagingCost() > 0 ?
            nl2br('Packaging = ' . round($this->getPackagingCost(),2) . $this->getCurrency() . "\n") : NULL;
        $transport = $this->getTransportCost() > 0 ?
            nl2br('Transport = ' . round($this->getTransportCost(),2) . $this->getCurrency() . "\n") : NULL;
        $total = nl2br('TOTAL = ' . round($this->getPriceWithTaxAndDiscounts(), 2) . $this->getCurrency() . "\n");
        return $costs  . $tax  . $discount . $packaging . $transport . $total;
    }

}