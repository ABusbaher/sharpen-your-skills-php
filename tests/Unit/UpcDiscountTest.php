<?php

namespace Tests\Unit;

use App\Classes\UpcDiscount;
use App\Exceptions\NotFloatException;
use App\Exceptions\NotPositiveFloatException;
use App\Exceptions\TooHighValueException;
use PHPUnit\Framework\TestCase;

class UpcDiscountTest extends TestCase {

    private UpcDiscount $upcDiscount;


    protected function setUp() : void
    {
        $this->upcDiscount = new UpcDiscount(15, 12345);
    }

    /** @test */
    public function can_change_discount(): void
    {
        $this->assertEquals(15, $this->upcDiscount->getDiscount());
        $this->upcDiscount->setDiscount(20);
        $this->assertEquals(20, $this->upcDiscount->getDiscount());
    }

    /** @test */
    public function exception_when_set_not_float_or_numeric_tax_rate(): void
    {
        $this->expectException(NotFloatException::class);
        $this->upcDiscount->setDiscount('not number or float');
    }

    /** @test */
    public function exception_when_set_negative_tax_rate(): void
    {
        $this->expectException(NotPositiveFloatException::class);
        $this->upcDiscount->setDiscount(-4);
    }

    /** @test */
    public function exception_when_set_tax_rate_bigger_than_100(): void
    {
        $this->expectException(TooHighValueException::class);
        $this->upcDiscount->setDiscount(120);
    }

    /** @test */
    public function can_set_multiplicative_discount(): void
    {
        $this->assertFalse( $this->upcDiscount->isMultiplicativeDiscount());
        $this->upcDiscount->setMultiplicativeDiscount(true);
        $this->assertTrue( $this->upcDiscount->isMultiplicativeDiscount());
    }

}
