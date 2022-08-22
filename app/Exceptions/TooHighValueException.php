<?php

namespace App\Exceptions;

class TooHighValueException extends \Exception
{
    protected $message = 'Tax must be less than 100!';
}