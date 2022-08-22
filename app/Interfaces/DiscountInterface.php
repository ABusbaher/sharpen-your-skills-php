<?php

namespace App\Interfaces;

interface DiscountInterface
{
    public function isBeforeTax();

    public function getDiscount();

    public function isMultiplicativeDiscount();

    public function setDiscount($discount);

    public function setMultiplicativeDiscount(bool $multiplicativeDiscount);
}