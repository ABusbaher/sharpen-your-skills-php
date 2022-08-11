<?php

namespace App\Exceptions;

class NotFloatException extends \Exception {

    protected $message = 'Only number/floats allowed!';
}