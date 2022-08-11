<?php

namespace Tests\Unit;

use App\Classes\Tax;
use PHPUnit\Framework\TestCase;
use App\Classes\Product;

class ProductTest extends TestCase {

    private Product $product;
    private Tax $tax;

    protected function setUp() : void
    {
        $this->tax = new Tax(20);
        $this->product = new Product("The Little Prince", 12345, 20.25, $this->tax);
    }

    /** @test */
    public function can_get_product_name(): void
    {
        $this->assertEquals("The Little Prince", $this->product->getName());
    }

    /** @test */
    public function can_get_product_upc(): void
    {
        $this->assertEquals(12345, $this->product->getUpc());
    }

    /** @test */
    public function can_get_product_price(): void
    {
        $this->assertEquals(20.25, $this->product->getPrice());
    }

    /** @test */
    public function can_calculate_tax_price(): void
    {
        $taxCost = round(20.25 * 20 / 100, 2);
        $this->assertEquals($taxCost, $this->product->taxCost());
    }

    /** @test */
    public function can_calculate_price_with_tax(): void
    {
        $taxCost = round(20.25 * 20 / 100, 2);
        $this->assertEquals($taxCost, $this->product->taxCost());
    }

    /** @test */
    public function price_with_tax_is_changed_when_tax_is_changed(): void
    {
        $taxCost = round(20.25 * 20 / 100, 2);
        $this->assertEquals($taxCost, $this->product->taxCost());
        $this->tax->setRate(21);
        $changedTaxCost = round(20.25 * 21 / 100, 2);
        $this->assertEquals($changedTaxCost, $this->product->taxCost());
    }
}