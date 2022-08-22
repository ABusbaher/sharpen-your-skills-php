<?php

namespace App\Interfaces;

interface TaxInterface
{
    public function getRate();

    public function setRate($rate);
}