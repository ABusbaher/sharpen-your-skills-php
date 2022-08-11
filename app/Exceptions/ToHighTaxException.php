<?php

namespace App\Exceptions;

class ToHighTaxException extends \Exception
{
    protected $message = 'Tax must be less than 100!';
}