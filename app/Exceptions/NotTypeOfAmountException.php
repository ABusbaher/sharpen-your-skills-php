<?php

namespace App\Exceptions;

class NotTypeOfAmountException extends \Exception
{
    protected $message = 'Type of amount can only be absolute or percentage!';
}