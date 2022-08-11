<?php

namespace Tests\Feature;

use App\Classes\Discount;
use App\Classes\Tax;
use PHPUnit\Framework\TestCase;
use App\Classes\Product;

class ProductTest extends TestCase {

    private Product $product;
    private Tax $tax;
    private Discount $discount;
    private Product $productWithDiscount;

    protected function setUp() : void
    {
        $this->tax = new Tax(20);
        $this->product = new Product("The Little Prince", 12345, 20.25, $this->tax);
        $this->discount = new Discount(10);
        $this->productWithDiscount = new Product("The Little Prince", 12345, 100, $this->tax, $this->discount);
    }

    /** @test */
    public function can_calculate_price_with_tax(): void
    {
        $priceWithTax = $this->product->taxCost() + $this->product->getPrice();
        $this->assertEquals($priceWithTax, $this->product->getPriceWithTax());
    }

    /** @test */
    public function price_with_tax_is_changed_when_tax_rate_is_changed(): void
    {
        $priceWithTax = $this->product->taxCost() + $this->product->getPrice();
        $this->assertEquals($priceWithTax, $this->product->getPriceWithTax());
        $this->tax->setRate(21);
        $changedPriceWithTax = $this->product->taxCost() + $this->product->getPrice();
        $this->assertEquals($changedPriceWithTax, $this->product->getPriceWithTax());
    }

    /** @test */
    public function can_calculate_price_with_tax_and_discount(): void
    {
        $priceWithTaxAndDiscount =
            $this->productWithDiscount->getPrice() +
            $this->productWithDiscount->taxCost() -
            $this->productWithDiscount->priceDiscount();
        $this->assertEquals($priceWithTaxAndDiscount, $this->productWithDiscount->getPriceWithTaxAndDiscount());
    }

}