<?php

namespace App\Exceptions;

class ToHighValueException extends \Exception
{
    protected $message = 'Tax must be less than 100!';
}