<?php

namespace App\Classes;

use App\Exceptions\NotPositiveFloatException;
use App\Exceptions\NotTypeOfAmountException;
use App\Exceptions\ToHighValueException;
use App\Interfaces\ExpensesInterface;

abstract class ExpensesAbstract implements ExpensesInterface
{
    private int|float $amount;

    private string $typeOfAmount;

    public function __construct(int|float $amount, string $typeOfAmount = 'absolute')
    {
        $this->amount = $amount;
        $this->typeOfAmount = $typeOfAmount;
    }

    public function getAmount(): float|int
    {
        return $this->amount;
    }

    public function getTypeOfAmount(): string
    {
        return $this->typeOfAmount;
    }

    /**
     * @throws ToHighValueException
     * @throws NotPositiveFloatException
     */
    public function setAmount(float|int $amount): void
    {
        if ($amount < 0) {
            throw new NotPositiveFloatException();
        }
        if ($amount > 100) {
            throw new ToHighValueException();
        }
        $this->amount = $amount;
    }

    /**
     * @throws NotTypeOfAmountException
     */
    public function setTypeOfAmount(string $typeOfAmount): void
    {
        if (!in_array(strtolower($typeOfAmount), self::ALLOWED_TYPES_OF_AMOUNT)) {
            throw new NotTypeOfAmountException();
        }
        $this->typeOfAmount = strtolower($typeOfAmount);
    }

}