<?php

namespace App\Interfaces;

interface ProductInterface
{
    public function getName();

    public function getUpc();

    public function getPrice();

    public function getPriceWithTax();

    public function allDiscounts();

    public function getPriceWithTaxAndDiscounts();

    public function lowerPrice();

    public function reportCosts();
}