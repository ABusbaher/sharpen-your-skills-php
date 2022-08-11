<?php

namespace App\Classes;

use App\Exceptions\NotFloatException;
use App\Exceptions\NotPositiveFloatException;
use App\Exceptions\ToHighTaxException;

class Tax {

    private int|float $rate;

    public function __construct(int|float $rate = 20)
    {
        $this->rate = $rate;
    }

    public function getRate(): int|float
    {
        return $this->rate;
    }

    /**
     * @throws NotPositiveFloatException
     * @throws NotFloatException
     * @throws ToHighTaxException
     */
    public function setRate($rate): void
    {
        if (!is_numeric($rate)) {
            throw new NotFloatException();
        }
        if ($rate < 0) {
            throw new NotPositiveFloatException();
        }
        if ($rate > 100) {
            throw new ToHighTaxException();
        }
        $this->rate = $rate;
    }
}