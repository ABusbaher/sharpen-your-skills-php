<?php

namespace App\Classes;

class UpcDiscount extends DiscountAbstract
{
    private int $upc;

    public function __construct(int|float $upcDiscount,int $upc, bool $beforeTax = false, bool $multiplicativeDiscount = false)
    {
        parent::__construct($upcDiscount, $beforeTax, $multiplicativeDiscount);
        $this->upc = $upc;
    }

    public function getUpc(): int
    {
        return $this->upc;
    }
}
