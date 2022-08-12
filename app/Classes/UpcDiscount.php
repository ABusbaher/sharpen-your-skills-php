<?php

namespace App\Classes;

use App\Exceptions\NotFloatException;
use App\Exceptions\NotPositiveFloatException;
use App\Exceptions\ToHighValueException;

class UpcDiscount
{
    private int|float $upcDiscount;

    private int $upc;

    public function __construct(int|float $upcDiscount, int $upc)
    {
        $this->upcDiscount = $upcDiscount;
        $this->upc = $upc;
    }

    public function getUpc(): int
    {
        return $this->upc;
    }

    public function getUpcDiscount(): int|float
    {
        return $this->upcDiscount;
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
