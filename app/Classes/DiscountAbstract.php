<?php

namespace App\Classes;

use App\Exceptions\NotFloatException;
use App\Exceptions\NotPositiveFloatException;
use App\Exceptions\TooHighValueException;
use App\Interfaces\DiscountInterface;

class DiscountAbstract implements DiscountInterface
{
    private int|float $discount;

    private bool $beforeTax;

    private bool $multiplicativeDiscount;

    public function __construct(int|float $discount = 0, bool $beforeTax = false, bool $multiplicativeDiscount = false)
    {
        $this->discount = $discount;
        $this->beforeTax = $beforeTax;
        $this->multiplicativeDiscount = $multiplicativeDiscount;
    }

    public function isBeforeTax(): bool
    {
        return $this->beforeTax;
    }

    public function getDiscount(): int|float
    {
        return $this->discount;
    }

    public function isMultiplicativeDiscount(): bool
    {
        return $this->multiplicativeDiscount;
    }

    /**
     * @throws NotPositiveFloatException
     * @throws NotFloatException
     * @throws TooHighValueException
     */
    public function setDiscount($discount): void
    {
        if (!is_numeric($discount)) {
            throw new NotFloatException();
        }
        if ($discount < 0) {
            throw new NotPositiveFloatException();
        }
        if ($discount > 100) {
            throw new TooHighValueException();
        }
        $this->discount = $discount;
    }

    public function setMultiplicativeDiscount(bool $multiplicativeDiscount): void
    {
        $this->multiplicativeDiscount = $multiplicativeDiscount;
    }
}