<?php

namespace App\Classes;

use App\Exceptions\NotFloatException;
use App\Exceptions\NotPositiveFloatException;
use App\Exceptions\ToHighValueException;

class UpcDiscount
{
    private int|float $upcDiscount;

    private int $upc;

    private bool $beforeTax;

    public function __construct(int|float $upcDiscount, int $upc, bool $beforeTax = false)
    {
        $this->upcDiscount = $upcDiscount;
        $this->upc = $upc;
        $this->beforeTax = $beforeTax;
    }

    public function getUpc(): int
    {
        return $this->upc;
    }

    public function getUpcDiscount(): int|float
    {
        return $this->upcDiscount;
    }

    public function isBeforeTax(): bool
    {
        return $this->beforeTax;
    }

    /**
     * @throws NotPositiveFloatException
     * @throws NotFloatException
     * @throws ToHighValueException
     */
    public function setUpcDiscount($discount): void
    {
        if (!is_numeric($discount)) {
            throw new NotFloatException();
        }
        if ($discount < 0) {
            throw new NotPositiveFloatException();
        }
        if ($discount > 100) {
            throw new ToHighValueException();
        }
        $this->upcDiscount = $discount;
    }
}
