<?php

namespace Tests\Unit;

use App\Classes\Tax;
use App\Exceptions\NotFloatException;
use App\Exceptions\NotPositiveFloatException;
use App\Exceptions\TooHighValueException;
use PHPUnit\Framework\TestCase;

class TaxTest extends TestCase {

    private Tax $tax;

    protected function setUp() : void
    {
        $this->tax = new Tax(20);
    }

    /** @test */
    public function can_change_tax_rate(): void
    {
        $this->assertEquals(20, $this->tax->getRate());
        $this->tax->setRate(21);
        $this->assertEquals(21, $this->tax->getRate());
    }

    /** @test */
    public function exception_when_set_not_float_or_numeric_tax_rate(): void
    {
        $this->expectException(NotFloatException::class);
        $this->tax->setRate('not number or float');
    }

    /** @test */
    public function exception_when_set_negative_tax_rate(): void
    {
        $this->expectException(NotPositiveFloatException::class);
        $this->tax->setRate(-4);
    }

    /** @test */
    public function exception_when_set_tax_rate_bigger_than_100(): void
    {
        $this->expectException(TooHighValueException::class);
        $this->tax->setRate(120);
    }

}