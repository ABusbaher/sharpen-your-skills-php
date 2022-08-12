<?php

namespace Tests\Feature;

use App\Classes\Discount;
use App\Classes\Tax;
use App\Classes\UpcDiscount;
use PHPUnit\Framework\TestCase;
use App\Classes\Product;

class ProductTest extends TestCase {

    private Product $product;
    private Tax $tax;
    private Product $productWithDiscount;
    private Product $productWithTwoDiscounts;

    protected function setUp() : void
    {
        $this->tax = new Tax(20);
        $this->product = new Product("The Little Prince", 12344, 20.25, $this->tax);
        $discount = new Discount(10);
        $upcDiscount = new UpcDiscount(5, 123456);
        $this->productWithDiscount = new Product("The Little Prince", 12345, 100,
            $this->tax, $discount);
        $this->productWithTwoDiscounts = new Product("The Little Prince", 12345, 100,
            $this->tax, $discount, $upcDiscount);
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
        $this->assertEquals($priceWithTaxAndDiscount, $this->productWithDiscount->getPriceWithTaxAndDiscounts());
    }

    /** @test */
    public function can_generate_report_without_discount(): void
    {
        $report = $this->product->reportCosts();
        $this->assertStringContainsString('Cost = $' . $this->product->getPrice(), $report);
        $this->assertStringContainsString('Tax = $' . $this->product->taxCost(), $report);
        $this->assertStringContainsString('TOTAL = $' . $this->product->getPriceWithTaxAndDiscounts(), $report);
        $this->assertStringNotContainsString('Discounts = $' . $this->product->priceDiscount(), $report);
    }

    /** @test */
    public function can_generate_report_with_discount(): void
    {
        $report = $this->productWithDiscount->reportCosts();
        $this->assertStringContainsString('Cost = $' . $this->productWithDiscount->getPrice(), $report);
        $this->assertStringContainsString('Tax = $' . $this->productWithDiscount->taxCost(), $report);
        $this->assertStringContainsString('TOTAL = $' . $this->productWithDiscount->getPriceWithTaxAndDiscounts(), $report);
        $this->assertStringContainsString('Discounts = $' . $this->productWithDiscount->priceDiscount(), $report);
    }

    /** @test */
    public function can_generate_report_with_all_discounts(): void
    {
        $report = $this->productWithTwoDiscounts->reportCosts();
        $this->assertStringContainsString('Cost = $' . $this->productWithTwoDiscounts->getPrice(), $report);
        $this->assertStringContainsString('Tax = $' . $this->productWithTwoDiscounts->taxCost(), $report);
        $this->assertStringContainsString('TOTAL = $' . $this->productWithTwoDiscounts->getPriceWithTaxAndDiscounts(), $report);
        $this->assertStringContainsString('Discounts = $' . $this->productWithTwoDiscounts->priceDiscount(), $report);
    }
}