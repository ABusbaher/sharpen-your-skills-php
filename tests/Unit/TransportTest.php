<?php

namespace Tests\Unit;

use App\Classes\Transport;
use App\Exceptions\NotPositiveFloatException;
use App\Exceptions\NotTypeOfAmountException;
use App\Exceptions\TooHighValueException;
use PHPUnit\Framework\TestCase;

class TransportTest extends TestCase
{
    private Transport $transport;

    protected function setUp() : void
    {
        $this->transport = new Transport(1);
    }

    /** @test */
    public function can_change_transport_amount() :void
    {
        $this->assertEquals(1, $this->transport->getAmount());
        $this->transport->setAmount(2);
        $this->assertEquals(2, $this->transport->getAmount());
    }

    /** @test */
    public function exception_when_set_negative_amount(): void
    {
        $this->expectException(NotPositiveFloatException::class);
        $this->transport->setAmount(-4);
    }

    /** @test */
    public function exception_when_set_amount_bigger_than_100(): void
    {
        $this->expectException(TooHighValueException::class);
        $this->transport->setAmount(120);
    }

    /** @test */
    public function can_change_transport_type_of_amount() :void
    {
        $this->assertEquals('absolute', $this->transport->getTypeOfAmount());
        $this->transport->setTypeOfAmount('percentage');
        $this->assertEquals('percentage', $this->transport->getTypeOfAmount());
    }

    /** @test */
    public function exception_when_type_of_amount_is_not_percentage_or_absolute(): void
    {
        $this->expectException(NotTypeOfAmountException::class);
        $this->transport->setTypeOfAmount('not-valid-type-of-amount');
    }
}