<?php

namespace App\Interfaces;

interface ProductInterface
{
    const ALLOWED_CURRENCIES = ['USD', 'GBP', 'JPY'];

    public function getName();

    public function getUpc();

    public function getPrice();

    public function getPriceWithTax();

    public function getCurrency();

    public function setCurrency(string $currency);

    public function allDiscounts();

    public function taxCost();

    public function getPriceWithTaxAndDiscounts();

    public function lowerPrice();

    public function getTransportCost();

    public function getPackagingCost();

    public function calculateCap();

    public function reportCosts();
}