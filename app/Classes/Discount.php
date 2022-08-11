<?php

namespace App\Classes;

use App\Exceptions\NotFloatException;
use App\Exceptions\NotPositiveFloatException;
use App\Exceptions\ToHighValueException;

class Discount
{
    private int|float $discount;

    public function __construct(int|float $discount = 0)
    {
        $this->discount = $discount;
    }

    public function getDiscount(): int|float
    {
        return $this->discount;
    }

    /**
     * @throws NotPositiveFloatException
     * @throws NotFloatException
     * @throws ToHighValueException
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
            throw new ToHighValueException();
        }
        $this->discount = $discount;
    }
}