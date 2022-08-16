<?php

namespace Tests\Unit;

use App\Classes\Discount;
use App\Exceptions\NotFloatException;
use App\Exceptions\NotPositiveFloatException;
use App\Exceptions\ToHighValueException;
use PHPUnit\Framework\TestCase;

class DiscountTest extends TestCase {

    private Discount $discount;

    protected function setUp() : void
    {
        $this->discount = new Discount(15);
    }

    /** @test */
    public function can_change_discount(): void
    {
        $this->assertEquals(15, $this->discount->getDiscount());
        $this->discount->setDiscount(20);
        $this->assertEquals(20, $this->discount->getDiscount());
    }

    /** @test */
    public function exception_when_set_not_float_or_numeric_tax_rate(): void
    {
        $this->expectException(NotFloatException::class);
        $this->discount->setDiscount('not number or float');
    }

    /** @test */
    public function exception_when_set_negative_tax_rate(): void
    {
        $this->expectException(NotPositiveFloatException::class);
        $this->discount->setDiscount(-4);
    }

    /** @test */
    public function exception_when_set_tax_rate_bigger_than_100(): void
    {
        $this->expectException(ToHighValueException::class);
        $this->discount->setDiscount(120);
    }

    /** @test */
    public function can_set_multiplicative_discount(): void
    {
        $this->assertFalse( $this->discount->isMultiplicativeDiscount());
        $this->discount->setMultiplicativeDiscount(true);
        $this->assertTrue( $this->discount->isMultiplicativeDiscount());
    }

}
