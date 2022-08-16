<?php

namespace App\Interfaces;

interface ExpensesInterface
{
    public function getAmount();

    public function getTypeOfAmount();

    public function setAmount(float|int $amount);

    public function setTypeOfAmount(string $typeOfAmount);

}