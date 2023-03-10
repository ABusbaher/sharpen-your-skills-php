<?php

namespace App\Interfaces;

interface ProductInterface
{
    public function getName();

    public function getUpc();

    public function getPrice();

    public function getPriceWithTax();
}