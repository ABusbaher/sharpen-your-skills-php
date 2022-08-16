<?php

namespace App\Interfaces;

interface ExpensesInterface
{
    const ALLOWED_TYPES_OF_AMOUNT = ['absolute', 'percentage'];

    public function getAmount();

    public function getTypeOfAmount();

    public function setAmount(float|int $amount);

    public function setTypeOfAmount(string $typeOfAmount);

}