<?php

namespace App\Exceptions;

class NotValidCurrencyException extends \Exception
{
    protected $message = 'Only USD, GBP or JPY allowed!';
}