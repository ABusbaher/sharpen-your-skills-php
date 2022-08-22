<?php

namespace App\Exceptions;

class NotPositiveFloatException extends \Exception {

    protected $message = 'Negative number/float is not allowed!';
}